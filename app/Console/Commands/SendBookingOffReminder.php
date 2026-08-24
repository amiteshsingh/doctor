<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseNotification;

class SendBookingOffReminder extends Command
{
    protected $signature   = 'doctor:booking-off-reminder';
    protected $description = 'Morning 8 AM reminder to doctors who have online booking turned off';

    public function handle()
    {
        // Un doctors ke users find karo jinke saare invoice_master OFFLINE hain
        $users = DB::table('users')
            ->join('invoice_master', 'invoice_master.added_by', '=', 'users.id')
            ->whereNotNull('users.fcm_token')
            ->groupBy('users.id', 'users.fcm_token', 'users.name')
            ->havingRaw('SUM(CASE WHEN invoice_master.booking_mode IN ("ONLINE","BOTH") THEN 1 ELSE 0 END) = 0')
            ->select('users.id', 'users.fcm_token', 'users.name')
            ->get();

        foreach ($users as $user) {
            FirebaseNotification::send(
                $user->fcm_token,
                '🔔 Online Booking Band Hai!',
                'Aapki online booking abhi OFF hai. Kya aap aaj ke liye booking ON karna chahte hain? App kholen aur enable karein.',
                ['type' => 'booking_off_reminder']
            );
            $this->info("Reminder sent → user #{$user->id} ({$user->name})");
        }

        $this->info("Total: {$users->count()} doctors notified.");
        return 0;
    }
}
