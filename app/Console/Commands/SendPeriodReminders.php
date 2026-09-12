<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\FirebaseNotification;

class SendPeriodReminders extends Command
{
    protected $signature   = 'period:send-reminders';
    protected $description = 'Send period reminder notifications to users';

    public function handle()
    {
        $today    = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $records = DB::table('period_tracking')
            ->join('users', 'users.id', '=', 'period_tracking.user_id')
            ->whereNotNull('users.fcm_token')
            ->select('period_tracking.*', 'users.fcm_token', 'users.name')
            ->get();

        $sent = 0;

        foreach ($records as $record) {
            $lastPeriod  = Carbon::parse($record->last_period_date);
            $cycleLength = (int) $record->cycle_length;
            $nextPeriod  = $lastPeriod->copy()->addDays($cycleLength);

            // Agar next period already past ho gayi, recalculate karo
            while ($nextPeriod->lt($today)) {
                $nextPeriod->addDays($cycleLength);
            }

            $daysLeft = $today->diffInDays($nextPeriod, false);

            $title = null;
            $body  = null;

            if ($daysLeft == 2) {
                $title = '🌸 Yaad Dilana';
                $body  = "Hi {$record->name}! Bas 2 din baaki hain — apna khayal rakhein, aaram karein aur zaroori cheezein ready rakhein. 💗";
            } elseif ($daysLeft == 1) {
                $title = '🌸 Kal Ka Din';
                $body  = "Hi {$record->name}! Kal thoda alag feel ho sakta hai — heating pad, chai aur rest ready rakhein. Apna khayal rakhein! 🫖";
            } elseif ($daysLeft == 0) {
                $title = '🌸 Aaj Ka Din';
                $body  = "Hi {$record->name}! Aaj thoda aaram karein, hydrated rahein aur apne aap ko time dein. Aap strong hain! 💪";
            }

            if ($title && $record->fcm_token) {
                $success = FirebaseNotification::send(
                    $record->fcm_token,
                    $title,
                    $body,
                    ['type' => 'period_reminder', 'screen' => 'PeriodTracker']
                );
                if ($success) $sent++;
            }

            // ── Ovulation Notifications ──────────────────────────────
            $ovulationDay = $lastPeriod->copy()->addDays($cycleLength - 14);
            while ($ovulationDay->lt($today)) {
                $ovulationDay->addDays($cycleLength);
            }
            $ovDaysLeft = $today->diffInDays($ovulationDay, false);

            $ovTitle = null;
            $ovBody  = null;

            if ($ovDaysLeft == 2) {
                $ovTitle = '✨ Khaas Din Aane Wala Hai';
                $ovBody  = "Hi {$record->name}! Agli 2-3 din mein aapki energy aur mood dono peak par honge — yeh time kuch naya shuru karne ke liye best hai! 🌟";
            } elseif ($ovDaysLeft == 1) {
                $ovTitle = '✨ Kal Khaas Din Hai';
                $ovBody  = "Hi {$record->name}! Kal aap sabse zyada energetic feel karengi — exercise, outing ya koi bhi plan ke liye perfect din! 🌺";
            } elseif ($ovDaysLeft == 0) {
                $ovTitle = '✨ Aaj Aapka Best Din Hai!';
                $ovBody  = "Hi {$record->name}! Aaj aapki energy, mood aur confidence peak par hai — apne aap ko celebrate karein! 🎉";
            }

            if ($ovTitle && $record->fcm_token) {
                $success = FirebaseNotification::send(
                    $record->fcm_token,
                    $ovTitle,
                    $ovBody,
                    ['type' => 'ovulation_reminder', 'screen' => 'PeriodTracker']
                );
                if ($success) $sent++;
            }
        }

        $this->info("Period & ovulation reminders sent: {$sent}");
        return 0;
    }
}
