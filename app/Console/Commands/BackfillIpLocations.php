<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\IpLocationService;
use Illuminate\Console\Command;

class BackfillIpLocations extends Command
{
    protected $signature = 'users:backfill-ip-locations {--limit= : Max users to process}';

    protected $description = 'Fetch IP-based location for users who have ip_address but no ip_city';

    public function handle(IpLocationService $ipService): int
    {
        $query = User::whereNotNull('ip_address')->whereNull('ip_city');

        $limit = $this->option('limit');
        if ($limit) {
            $query->take((int) $limit);
        }

        $users = $query->get(['id', 'ip_address']);
        $total   = $users->count();
        $success = 0;
        $skipped = 0;

        $this->info("Processing {$total} users...");

        $privateRanges = ['127.', '10.', '192.168.', '::1'];

        foreach ($users as $user) {
            $ip = $user->ip_address;

            // Private IP check
            $isPrivate = false;
            foreach ($privateRanges as $range) {
                if (str_starts_with($ip, $range) || $ip === 'localhost') {
                    $isPrivate = true;
                    break;
                }
            }

            if ($isPrivate) {
                $this->line("User #{$user->id} [{$ip}]: skipped (private IP)");
                $skipped++;
                continue;
            }

            $location = $ipService->fetch($ip);

            if (!$location) {
                $this->warn("User #{$user->id} [{$ip}]: failed (no data from API)");
                continue;
            }

            $user->update([
                'ip_city'    => $location['city'],
                'ip_region'  => $location['region'],
                'ip_country' => $location['country'],
                'ip_isp'     => $location['isp'],
                'ip_lat'     => $location['lat'],
                'ip_lng'     => $location['lng'],
            ]);

            $this->info("User #{$user->id} [{$ip}]: {$location['city']}, {$location['country']}");
            $success++;

            // Rate limit: ip-api.com allows 45 req/min — 1.5s delay
            sleep(1);
            usleep(500000);
        }

        $this->newLine();
        $this->info("Done. Total: {$total}, Success: {$success}, Skipped: {$skipped}");

        return Command::SUCCESS;
    }
}
