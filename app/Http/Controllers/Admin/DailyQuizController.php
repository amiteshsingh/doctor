<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyQuizBatch;
use App\Models\DailyQuizQuestion;
use App\Models\DailyQuizAttempt;
use App\Models\User;
use App\Services\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyQuizController extends Controller
{
    public function index()
    {
        $batches = DailyQuizBatch::withCount('questions')->latest()->paginate(15);
        return view('admin.daily_quiz.index', compact('batches'));
    }

    public function create()
    {
        return view('admin.daily_quiz.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'nullable|string|max:200',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after:starts_at',
            'questions'              => 'required|array|min:1',
            'questions.*.question'   => 'required|string|max:500',
            'questions.*.option_a'   => 'required|string|max:255',
            'questions.*.option_b'   => 'required|string|max:255',
            'questions.*.option_c'   => 'required|string|max:255',
            'questions.*.option_d'   => 'required|string|max:255',
            'questions.*.correct_answer' => 'required|in:A,B,C,D',
            'questions.*.category'   => 'required|string|max:100',
            'questions.*.time_limit' => 'required|integer|min:10|max:300',
            'questions.*.explanation'=> 'nullable|string|max:1000',
        ]);

        $batch = DailyQuizBatch::create([
            'title'     => $request->title,
            'starts_at' => $request->starts_at,
            'ends_at'   => $request->ends_at,
        ]);

        foreach ($request->questions as $i => $q) {
            DailyQuizQuestion::create([
                'batch_id'       => $batch->id,
                'question'       => $q['question'],
                'option_a'       => $q['option_a'],
                'option_b'       => $q['option_b'],
                'option_c'       => $q['option_c'],
                'option_d'       => $q['option_d'],
                'correct_answer' => $q['correct_answer'],
                'explanation'    => $q['explanation'] ?? null,
                'category'       => $q['category'],
                'time_limit'     => $q['time_limit'],
                'sort_order'     => $i,
                'quiz_date'      => now()->toDateString(),
            ]);
        }

        // ── Push notification to all users ──────────────────────────────────
        $this->sendQuizNotification($batch, count($request->questions));
        // ────────────────────────────────────────────────────────────────────

        return redirect()->route('admin.daily-quiz.index')
            ->with('success', count($request->questions) . ' questions added in batch #' . $batch->id . '!');
    }

    public function edit($id)
    {
        $batch = DailyQuizBatch::with('questions')->findOrFail($id);
        return view('admin.daily_quiz.add', compact('batch'));
    }

    public function update(Request $request, $id)
    {
        $batch = DailyQuizBatch::findOrFail($id);

        $request->validate([
            'title'     => 'nullable|string|max:200',
            'starts_at' => 'required|date',
            'ends_at'   => 'required|date|after:starts_at',
            'questions'              => 'required|array|min:1',
            'questions.*.question'   => 'required|string|max:500',
            'questions.*.option_a'   => 'required|string|max:255',
            'questions.*.option_b'   => 'required|string|max:255',
            'questions.*.option_c'   => 'required|string|max:255',
            'questions.*.option_d'   => 'required|string|max:255',
            'questions.*.correct_answer' => 'required|in:A,B,C,D',
            'questions.*.category'   => 'required|string|max:100',
            'questions.*.time_limit' => 'required|integer|min:10|max:300',
            'questions.*.explanation'=> 'nullable|string|max:1000',
        ]);

        $batch->update([
            'title'     => $request->title,
            'starts_at' => $request->starts_at,
            'ends_at'   => $request->ends_at,
        ]);

        // Delete old questions and re-insert
        $batch->questions()->delete();

        foreach ($request->questions as $i => $q) {
            DailyQuizQuestion::create([
                'batch_id'       => $batch->id,
                'question'       => $q['question'],
                'option_a'       => $q['option_a'],
                'option_b'       => $q['option_b'],
                'option_c'       => $q['option_c'],
                'option_d'       => $q['option_d'],
                'correct_answer' => $q['correct_answer'],
                'explanation'    => $q['explanation'] ?? null,
                'category'       => $q['category'],
                'time_limit'     => $q['time_limit'],
                'sort_order'     => $i,
                'quiz_date'      => now()->toDateString(),
            ]);
        }

        return redirect()->route('admin.daily-quiz.index')->with('success', 'Batch updated successfully!');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Excel Import — show import form
    // ─────────────────────────────────────────────────────────────────────────
    public function importForm()
    {
        return view('admin.daily_quiz.import');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Excel Import — process uploaded file (no external library needed)
    // Supports: .xlsx (native ZipArchive+SimpleXML) and .csv (fgetcsv)
    // ─────────────────────────────────────────────────────────────────────────
    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            'title'      => 'nullable|string|max:200',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after:starts_at',
        ]);

        try {
            $file = $request->file('excel_file');
            $ext  = strtolower($file->getClientOriginalExtension());

            // Parse file into a 2D array of rows
            if ($ext === 'csv') {
                $rows = $this->parseCsv($file->getPathname());
            } else {
                // xlsx / xls — use native ZipArchive + SimpleXML
                $rows = $this->parseXlsx($file->getPathname());
            }

            $validCategories = ['General', 'Health', 'Science', 'History', 'Geography', 'Sports', 'Technology', 'Math'];
            $questions = [];
            $errors    = [];

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex === 0) continue; // skip header row

                // Pad row to at least 9 columns
                $row = array_pad((array)$row, 9, '');

                // Skip completely empty rows
                $nonEmpty = array_filter(array_map('trim', array_map('strval', $row)));
                if (empty($nonEmpty)) continue;

                $lineNo  = $rowIndex + 1;
                $q       = trim((string)($row[0] ?? ''));
                $optA    = trim((string)($row[1] ?? ''));
                $optB    = trim((string)($row[2] ?? ''));
                $optC    = trim((string)($row[3] ?? ''));
                $optD    = trim((string)($row[4] ?? ''));
                $correct = strtoupper(trim((string)($row[5] ?? '')));
                $cat     = trim((string)($row[6] ?? 'General'));
                $time    = (int)($row[7] ?? 30);
                $expl    = trim((string)($row[8] ?? ''));

                if (empty($q))                              { $errors[] = "Row {$lineNo}: Question khali hai."; continue; }
                if (empty($optA))                           { $errors[] = "Row {$lineNo}: Option A khali hai."; continue; }
                if (empty($optB))                           { $errors[] = "Row {$lineNo}: Option B khali hai."; continue; }
                if (empty($optC))                           { $errors[] = "Row {$lineNo}: Option C khali hai."; continue; }
                if (empty($optD))                           { $errors[] = "Row {$lineNo}: Option D khali hai."; continue; }
                if (!in_array($correct, ['A','B','C','D'])) { $errors[] = "Row {$lineNo}: Correct answer '{$correct}' galat hai (A/B/C/D hona chahiye)."; continue; }
                if (!in_array($cat, $validCategories))      $cat  = 'General';
                if ($time < 10 || $time > 300)              $time = 30;

                $questions[] = compact('q', 'optA', 'optB', 'optC', 'optD', 'correct', 'cat', 'time', 'expl');
            }

            if (!empty($errors)) {
                return back()->withInput()
                    ->with('import_errors', $errors)
                    ->with('error', count($errors) . ' row(s) me error hai. Neeche details dekho.');
            }

            if (empty($questions)) {
                return back()->withInput()->with('error', 'Excel file me koi valid question nahi mila.');
            }

            $batch = DailyQuizBatch::create([
                'title'     => $request->title,
                'starts_at' => $request->starts_at,
                'ends_at'   => $request->ends_at,
            ]);

            foreach ($questions as $i => $q) {
                DailyQuizQuestion::create([
                    'batch_id'       => $batch->id,
                    'question'       => $q['q'],
                    'option_a'       => $q['optA'],
                    'option_b'       => $q['optB'],
                    'option_c'       => $q['optC'],
                    'option_d'       => $q['optD'],
                    'correct_answer' => $q['correct'],
                    'explanation'    => $q['expl'],
                    'category'       => $q['cat'],
                    'time_limit'     => $q['time'],
                    'sort_order'     => $i,
                    'quiz_date'      => now()->toDateString(),
                ]);
            }

            $this->sendQuizNotification($batch, count($questions));

            return redirect()->route('admin.daily-quiz.index')
                ->with('success', '✅ Excel se ' . count($questions) . ' questions import ho gaye! Batch #' . $batch->id . ' create hua.');

        } catch (\Exception $e) {
            \Log::error('Quiz Excel Import Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->withInput()->with('error', 'File read karne me error aaya: ' . $e->getMessage());
        }
    }

    // ── Parse .xlsx using native ZipArchive + SimpleXML (no library needed) ──
    private function parseXlsx(string $filePath): array
    {
        if (!class_exists('ZipArchive')) {
            throw new \Exception('ZipArchive PHP extension enable nahi hai. CSV format use karo.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception('Excel file open nahi ho saki. File corrupt ho sakti hai.');
        }

        // Read shared strings (text values stored separately in xlsx)
        $sharedStrings = [];
        $ssXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($ssXml !== false) {
            $ss = simplexml_load_string($ssXml);
            if ($ss) {
                foreach ($ss->si as $si) {
                    // si can have direct <t> or multiple <r><t> (rich text)
                    if (isset($si->t)) {
                        $sharedStrings[] = (string)$si->t;
                    } else {
                        $text = '';
                        foreach ($si->r ?? [] as $r) {
                            $text .= (string)($r->t ?? '');
                        }
                        $sharedStrings[] = $text;
                    }
                }
            }
        }

        // Find first sheet file
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXml === false) {
            // Try to find sheet via workbook relationships
            $wbRel = $zip->getFromName('xl/_rels/workbook.xml.rels');
            $sheetFile = 'xl/worksheets/sheet1.xml';
            if ($wbRel !== false) {
                $rel = simplexml_load_string($wbRel);
                foreach ($rel->Relationship ?? [] as $r) {
                    if (stripos((string)$r['Type'], 'worksheet') !== false) {
                        $target = (string)$r['Target'];
                        $sheetFile = strpos($target, 'xl/') === 0 ? $target : 'xl/' . $target;
                        break;
                    }
                }
            }
            $sheetXml = $zip->getFromName($sheetFile);
        }
        $zip->close();

        if ($sheetXml === false) {
            throw new \Exception('Excel file ka worksheet nahi mila.');
        }

        $sheet = simplexml_load_string($sheetXml);
        if (!$sheet) {
            throw new \Exception('Excel file parse nahi hui (invalid XML).');
        }

        $rows = [];

        // Register the namespace used in xlsx
        $ns = $sheet->getNamespaces(true);
        $defaultNs = $ns[''] ?? 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

        $sheetData = $sheet->sheetData ?? $sheet->children($defaultNs)->sheetData;

        foreach ($sheetData->row ?? [] as $row) {
            $rowIndex = (int)($row['r'] ?? 0) - 1; // 0-based
            $cols = [];

            foreach ($row->c ?? [] as $cell) {
                // Get column letter from cell reference (e.g. "B3" → col index 1)
                $ref = (string)($cell['r'] ?? '');
                preg_match('/^([A-Z]+)/', $ref, $m);
                $colStr = $m[1] ?? 'A';
                $colIdx = 0;
                foreach (str_split($colStr) as $ch) {
                    $colIdx = $colIdx * 26 + (ord($ch) - ord('A') + 1);
                }
                $colIdx--; // 0-based

                $type  = (string)($cell['t'] ?? '');
                $value = (string)($cell->v ?? '');

                if ($type === 's') {
                    // Shared string
                    $value = $sharedStrings[(int)$value] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = (string)($cell->is->t ?? '');
                }
                // else: number, date, formula result — keep as-is

                $cols[$colIdx] = $value;
            }

            if (!empty($cols)) {
                // Fill gaps so row is contiguous
                $maxCol = max(array_keys($cols));
                $rowData = [];
                for ($c = 0; $c <= $maxCol; $c++) {
                    $rowData[] = $cols[$c] ?? '';
                }
                $rows[$rowIndex] = $rowData;
            }
        }

        ksort($rows);
        return array_values($rows);
    }

    // ── Parse .csv ────────────────────────────────────────────────────────────
    private function parseCsv(string $filePath): array
    {
        $rows   = [];
        $handle = fopen($filePath, 'r');
        if (!$handle) throw new \Exception('CSV file open nahi ho saki.');

        // Detect BOM (UTF-8 with BOM)
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") rewind($handle);

        while (($row = fgetcsv($handle, 2000, ',')) !== false) {
            $rows[] = $row;
        }
        fclose($handle);
        return $rows;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Sample Excel template download — generates a proper .xlsx inline
    // Uses native ZipArchive, no external library
    // ─────────────────────────────────────────────────────────────────────────
    public function downloadTemplate()
    {
        if (!class_exists('ZipArchive')) {
            // Fallback: send CSV template
            return $this->downloadCsvTemplate();
        }

        $headers = ['Question', 'Option A', 'Option B', 'Option C', 'Option D',
                    'Correct Answer (A/B/C/D)', 'Category', 'Time Limit (sec)', 'Explanation (optional)'];

        $samples = [
            ['Human body mein kitni haddiyaan hoti hain?', '206', '208', '210', '212', 'A', 'Health', 30, 'Ek adult human body mein total 206 bones hoti hain.'],
            ['Paani ka chemical formula kya hai?', 'H2O', 'CO2', 'O2', 'H2SO4', 'A', 'Science', 20, 'Paani 2 hydrogen aur 1 oxygen atom se milkar bana hai.'],
            ['India ka sabse bada state (area) kaun sa hai?', 'Maharashtra', 'Madhya Pradesh', 'Rajasthan', 'Uttar Pradesh', 'C', 'Geography', 30, 'Rajasthan sabse bada state hai.'],
            ['Olympic Games kitne saal mein ek baar hote hain?', '2 saal', '3 saal', '4 saal', '5 saal', 'C', 'Sports', 20, 'Olympic Games har 4 saal mein hote hain.'],
            ['CPU ka full form kya hai?', 'Central Processing Unit', 'Computer Personal Unit', 'Central Program Utility', 'Core Processing Unit', 'A', 'Technology', 25, 'CPU = Central Processing Unit.'],
        ];

        // Build xlsx XML parts
        $sharedStrings = [];
        $ssIndex = [];
        $getSS = function(string $val) use (&$sharedStrings, &$ssIndex): int {
            if (!isset($ssIndex[$val])) {
                $ssIndex[$val] = count($sharedStrings);
                $sharedStrings[] = $val;
            }
            return $ssIndex[$val];
        };

        // Build sheet rows XML
        $sheetRowsXml = '';
        $allRows = array_merge([$headers], $samples);
        foreach ($allRows as $ri => $row) {
            $rowNum = $ri + 1;
            $sheetRowsXml .= "<row r=\"{$rowNum}\">";
            $cols = ['A','B','C','D','E','F','G','H','I'];
            foreach ($row as $ci => $val) {
                $col = $cols[$ci] ?? chr(65 + $ci);
                $ref = "{$col}{$rowNum}";
                if (is_numeric($val) && $ri > 0) {
                    // Store as number for time limit column
                    $sheetRowsXml .= "<c r=\"{$ref}\"><v>" . htmlspecialchars((string)$val, ENT_XML1) . "</v></c>";
                } else {
                    $idx = $getSS((string)$val);
                    $sheetRowsXml .= "<c r=\"{$ref}\" t=\"s\"><v>{$idx}</v></c>";
                }
            }
            $sheetRowsXml .= '</row>';
        }

        // Shared strings XML
        $ssCount = count($sharedStrings);
        $ssXml   = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
        $ssXml  .= "<sst xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" count=\"{$ssCount}\" uniqueCount=\"{$ssCount}\">";
        foreach ($sharedStrings as $s) {
            $ssXml .= '<si><t xml:space="preserve">' . htmlspecialchars($s, ENT_XML1 | ENT_QUOTES) . '</t></si>';
        }
        $ssXml .= '</sst>';

        // Styles XML (header bold + blue bg)
        $stylesXml = <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="2">
    <font><sz val="11"/><name val="Calibri"/></font>
    <font><sz val="11"/><b/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>
  </fonts>
  <fills count="3">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="FF1A237E"/></patternFill></fill>
  </fills>
  <borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>
  <cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>
  <cellXfs count="2">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/>
  </cellXfs>
</styleSheet>
XML;

        // Sheet XML
        $sheetXml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>
  <cols>
    <col min="1" max="1" width="55" customWidth="1"/>
    <col min="2" max="5" width="22" customWidth="1"/>
    <col min="6" max="6" width="22" customWidth="1"/>
    <col min="7" max="7" width="16" customWidth="1"/>
    <col min="8" max="8" width="14" customWidth="1"/>
    <col min="9" max="9" width="45" customWidth="1"/>
  </cols>
  <sheetData>
    {$sheetRowsXml}
  </sheetData>
</worksheet>
XML;

        // Apply header style (s="1") to row 1 cells — rebuild row 1
        $sheetXml = preg_replace(
            '/<c r="([A-I])1" t="s"><v>(\d+)<\/v><\/c>/',
            '<c r="$11" t="s" s="1"><v>$2</v></c>',
            $sheetXml
        );

        // Workbook XML
        $workbookXml = <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets><sheet name="Quiz Questions" sheetId="1" r:id="rId1"/></sheets>
</workbook>
XML;

        // Relationships
        $relsXml = <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>
XML;

        $rootRelsXml = <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>
XML;

        $contentTypesXml = <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
</Types>
XML;

        // Build the zip in a temp file
        $tmpFile = tempnam(sys_get_temp_dir(), 'quiz_') . '.xlsx';
        $zip = new \ZipArchive();
        $zip->open($tmpFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml',              $contentTypesXml);
        $zip->addFromString('_rels/.rels',                      $rootRelsXml);
        $zip->addFromString('xl/workbook.xml',                  $workbookXml);
        $zip->addFromString('xl/_rels/workbook.xml.rels',       $relsXml);
        $zip->addFromString('xl/worksheets/sheet1.xml',         $sheetXml);
        $zip->addFromString('xl/sharedStrings.xml',             $ssXml);
        $zip->addFromString('xl/styles.xml',                    $stylesXml);
        $zip->close();

        return response()->download($tmpFile, 'quiz_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // Fallback CSV download if ZipArchive not available
    private function downloadCsvTemplate()
    {
        $lines = [
            'Question,Option A,Option B,Option C,Option D,Correct Answer (A/B/C/D),Category,Time Limit (sec),Explanation (optional)',
            '"Human body mein kitni haddiyaan hoti hain?","206","208","210","212","A","Health","30","206 bones hoti hain."',
            '"Paani ka chemical formula kya hai?","H2O","CO2","O2","H2SO4","A","Science","20","H2O"',
            '"India ka sabse bada state kaun sa hai?","Maharashtra","Madhya Pradesh","Rajasthan","Uttar Pradesh","C","Geography","30","Rajasthan"',
        ];
        $csv = implode("\n", $lines);
        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="quiz_import_template.csv"',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Push notification helper — called after a new quiz batch is created
    // ─────────────────────────────────────────────────────────────────────────
    private function sendQuizNotification(DailyQuizBatch $batch, int $questionCount): void
    {
        try {
            // Collect FCM tokens of all regular users (deduplicated)
            $tokens = User::whereHas('role', fn($q) => $q->where('role', 'user'))
                ->whereNotNull('fcm_token')
                ->distinct('fcm_token')
                ->pluck('fcm_token')
                ->toArray();

            if (empty($tokens)) return;

            // Format start time: "15 Nov 2026, 06:30 PM"
            $startTime = $batch->starts_at->format('d M Y, h:i A');
            // Short form for body: "15 Nov, 6:30 PM"
            $startShort = $batch->starts_at->format('d M, g:i A');

            // Compose title & body with start time
            $title = '🎯 Naya Daily Quiz Add Ho Gaya!';

            if ($batch->title) {
                $body = "\"" . $batch->title . "\" — " . $questionCount . " sawaal | ⏰ Live: " . $startShort . "\nTaiyaar raho aur sabse pehle attempt karo! 🏆";
            } else {
                $body = $questionCount . " exciting sawaalon wala naya quiz aa raha hai!\n⏰ Live hoga: " . $startShort . "\nMiss mat karna! 💡";
            }

            $result = FirebaseNotification::sendBulk(
                $tokens,
                $title,
                $body,
                [
                    'type'       => 'daily_quiz',
                    'batch_id'   => (string) $batch->id,
                    'screen'     => 'DailyQuiz',
                    'start_time' => $batch->starts_at->toIso8601String(),
                    'end_time'   => $batch->ends_at->toIso8601String(),
                ]
            );

            // Log to notification_logs for audit trail
            DB::table('notification_logs')->insert([
                'title'        => $title,
                'message'      => $body,
                'target'       => 'all',
                'user_id'      => null,
                'sent_count'   => $result['sent'],
                'failed_count' => $result['failed'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

        } catch (\Exception $e) {
            // Log the error but don't block the quiz creation
            \Log::error('Daily Quiz Notification Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $batch = DailyQuizBatch::findOrFail($id);
        $batch->questions()->delete();
        $batch->delete();
        return redirect()->route('admin.daily-quiz.index')->with('success', 'Batch deleted.');
    }

    public function toggle($id)
    {
        $batch = DailyQuizBatch::findOrFail($id);
        $batch->update(['is_active' => !$batch->is_active]);
        return back()->with('success', 'Status updated.');
    }

    public function results($id)
    {
        $batch       = DailyQuizBatch::with('questions')->findOrFail($id);
        $questionIds = $batch->questions->pluck('id');

        $attempts = DailyQuizAttempt::with(['user', 'question'])
            ->whereIn('question_id', $questionIds)
            ->latest()
            ->paginate(30);

        $stats = [
            'total'   => DailyQuizAttempt::whereIn('question_id', $questionIds)->count(),
            'correct' => DailyQuizAttempt::whereIn('question_id', $questionIds)->where('is_correct', true)->count(),
        ];

        // ── Top 3 Winners ────────────────────────────────────────────────────
        // Ranking: most correct answers first; tie-break = least total time taken
        $winners = DailyQuizAttempt::with('user')
            ->whereIn('question_id', $questionIds)
            ->selectRaw('user_id,
                         SUM(is_correct) as correct_count,
                         SUM(time_taken) as total_time,
                         COUNT(*) as answered')
            ->groupBy('user_id')
            ->orderByDesc('correct_count')
            ->orderBy('total_time')
            ->limit(3)
            ->get();
        // ─────────────────────────────────────────────────────────────────────

        return view('admin.daily_quiz.results', compact('batch', 'attempts', 'stats', 'winners'));
    }
}
