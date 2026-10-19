<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class DatabaseBackupController extends Controller
{
    public function index(): View
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        return view('admin.database-backup.index', [
            'databaseName' => $connection->getDatabaseName(),
            'driver' => $driver,
            'supported' => in_array($driver, ['mysql', 'mariadb'], true),
        ]);
    }

    public function download(): BinaryFileResponse
    {
        $connection = DB::connection();
        if (! in_array($connection->getDriverName(), ['mysql', 'mariadb'], true)) {
            abort(422, 'Database backup downloads currently support MySQL and MariaDB only.');
        }

        $path = tempnam(sys_get_temp_dir(), 'doctor-db-backup-');
        if ($path === false) {
            throw new RuntimeException('Unable to create a temporary database backup file.');
        }

        $stream = fopen($path, 'wb');
        if ($stream === false) {
            unlink($path);
            throw new RuntimeException('Unable to open the temporary database backup file.');
        }

        try {
            $pdo = $connection->getPdo();
            $databaseName = $connection->getDatabaseName();
            $tables = [];
            foreach ($connection->select('SHOW FULL TABLES') as $tableInfo) {
                $values = array_values((array) $tableInfo);
                if (($values[1] ?? null) === 'BASE TABLE') {
                    $tables[] = (string) $values[0];
                }
            }

            $this->write($stream, "-- Database backup for {$databaseName}\n");
            $this->write($stream, '-- Created at '.now()->toDateTimeString()."\n\n");
            $this->write($stream, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

            foreach ($tables as $table) {
                $quotedTable = $this->quoteIdentifier($table);
                $createTable = $connection->selectOne("SHOW CREATE TABLE {$quotedTable}");
                $createTableSql = array_values((array) $createTable)[1] ?? null;
                if (! is_string($createTableSql) || $createTableSql === '') {
                    throw new RuntimeException("Unable to read the schema for table {$table}.");
                }

                $this->write($stream, "-- Table structure for {$quotedTable}\n");
                $this->write($stream, "DROP TABLE IF EXISTS {$quotedTable};\n{$createTableSql};\n\n");

                $columns = [];
                $columnTypes = [];
                foreach ($connection->select("SHOW FULL COLUMNS FROM {$quotedTable}") as $columnInfo) {
                    $column = (array) $columnInfo;
                    $name = (string) ($column['Field'] ?? '');
                    $type = (string) ($column['Type'] ?? '');
                    $extra = (string) ($column['Extra'] ?? '');
                    if ($name === '' || preg_match('/\b(?:VIRTUAL|STORED)\s+GENERATED\b/i', $extra)) {
                        continue;
                    }

                    $columns[] = $name;
                    $columnTypes[$name] = $type;
                }

                if ($columns === []) {
                    continue;
                }

                $quotedColumns = implode(', ', array_map([$this, 'quoteIdentifier'], $columns));
                foreach (DB::table($table)->select($columns)->cursor() as $row) {
                    $values = [];
                    foreach ($columns as $column) {
                        $value = $row->{$column};
                        $values[] = $this->quoteValue($pdo, $value, $columnTypes[$column]);
                    }

                    $this->write(
                        $stream,
                        "INSERT INTO {$quotedTable} ({$quotedColumns}) VALUES (".implode(', ', $values).");\n"
                    );
                }

                $this->write($stream, "\n");
            }

            $this->write($stream, "SET FOREIGN_KEY_CHECKS=1;\n");
            if (! fflush($stream)) {
                throw new RuntimeException('Unable to finish writing the database backup file.');
            }
            if (! fclose($stream)) {
                $stream = false;
                throw new RuntimeException('Unable to close the database backup file.');
            }
            $stream = false;
        } catch (Throwable $exception) {
            if (is_resource($stream)) {
                fclose($stream);
            }
            if (is_file($path)) {
                unlink($path);
            }

            throw $exception;
        }

        $filename = Str::slug($connection->getDatabaseName()).'_backup_'.now()->format('Ymd_His').'.sql';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/sql; charset=UTF-8',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ])->deleteFileAfterSend(true);
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`'.str_replace('`', '``', $identifier).'`';
    }

    private function quoteValue(\PDO $pdo, mixed $value, string $type): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (preg_match('/^(?:tinyint|smallint|mediumint|int|integer|bigint|decimal|numeric|float|double|real|year)\b/i', $type)
            && is_numeric($value)) {
            return (string) $value;
        }

        if (preg_match('/^(?:tinyblob|blob|mediumblob|longblob|binary|varbinary|bit|geometry|point|linestring|polygon|multipoint|multilinestring|multipolygon|geometrycollection)\b/i', $type)) {
            return '0x'.bin2hex((string) $value);
        }

        $quoted = $pdo->quote((string) $value);
        if ($quoted === false) {
            throw new RuntimeException('Unable to encode a database value in the SQL backup.');
        }

        return $quoted;
    }

    private function write(mixed $stream, string $content): void
    {
        while ($content !== '') {
            $written = fwrite($stream, $content);
            if ($written === false || $written === 0) {
                throw new RuntimeException('Unable to write the database backup file.');
            }

            $content = substr($content, $written);
        }
    }
}
