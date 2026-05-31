<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseNotification;
use Carbon\Carbon;

class SendPeriodReminders extends Command
{
    protected $signature   = 'period:send-reminders';
    protected $description = 'Send period & fertile window reminders to users';

    public function handle()
    {
        $today   = Carbon::today('Asia/Kolkata');
        $records = DB::table('period_tracking')
            ->join('users', 'period_tracking.user_id', '=', 'users.id')
            ->whereNotNull('users.fcm_token')
            ->select('period_tracking.*', 'users.fcm_token', 'users.name')
            ->get();

        foreach ($records as $r) {
            $last        = Carbon::parse($r->last_period_date);
            $cycle       = (int)$r->cycle_length;
            $nextPeriod  = $last->copy()->addDays($cycle);
            $ovulation   = $last->copy()->addDays($cycle - 14);
            $fertileStart = $ovulation->copy()->subDays(5);
            $fertileEnd   = $ovulation->copy()->addDay();
            $daysUntil   = $today->diffInDays($nextPeriod, false);

            $name = $r->name ?? 'User';

            // 3 days before period
            if ($daysUntil == 3) {
                $this->notify($r->fcm_token,
                    '🩸 Period Reminder',
                    "नमस्ते! आपका अगला पीरियड 3 दिन बाद आने वाला है। तैयार रहें।",
                    'period_reminder'
                );
            }

            // 1 day before period
            if ($daysUntil == 1) {
                $this->notify($r->fcm_token,
                    '🩸 Period कल आएगा',
                    "आपका पीरियड कल शुरू होने वाला है। जरूरी सामान तैयार रखें।",
                    'period_reminder'
                );
            }

            // Period day
            if ($daysUntil == 0) {
                $this->notify($r->fcm_token,
                    '🩸 आज Period का दिन है',
                    "आज आपका पीरियड शुरू होने की संभावना है। अपना ख्याल रखें। 💕",
                    'period_day'
                );
            }

            // Fertile window start
            if ($today->isSameDay($fertileStart)) {
                $this->notify($r->fcm_token,
                    '🌸 Fertile Window शुरू',
                    "आज से आपकी Fertile Window शुरू हो रही है। यह गर्भधारण के लिए सबसे अच्छा समय है।",
                    'fertile_window'
                );
            }

            // Ovulation day
            if ($today->isSameDay($ovulation)) {
                $this->notify($r->fcm_token,
                    '🥚 Ovulation Day',
                    "आज आपका Ovulation Day है — यह गर्भधारण के लिए सबसे उपयुक्त दिन है! 🌟",
                    'ovulation'
                );
            }
        }

        return 0;
    }

    private function notify(string $token, string $title, string $body, string $type): void
    {
        FirebaseNotification::send($token, $title, $body, ['type' => $type]);
        $this->info("Sent: $title");
    }
}
