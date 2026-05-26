<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseNotification;
use Carbon\Carbon;

class SendBookingReminders extends Command
{
    protected $signature   = 'bookings:send-reminders';
    protected $description = 'Send FCM reminders 60, 30, 15, 5 minutes before appointment';

    public function handle()
    {
        $now     = Carbon::now('Asia/Kolkata');
        $today   = $now->format('Y-m-d');
        $nowMins = $now->hour * 60 + $now->minute;

        $bookings = DB::table('prescription_invoice')
            ->join('users', 'prescription_invoice.user_id', '=', 'users.id')
            ->where('prescription_invoice.booking_date', $today)
            ->where(function($q) {
                $q->whereNull('prescription_invoice.status')
                  ->orWhere('prescription_invoice.status', '!=', 'cancelled');
            })
            ->whereNotNull('users.fcm_token')
            ->whereNotNull('prescription_invoice.user_id')
            ->select(
                'prescription_invoice.id',
                'prescription_invoice.booking_time',
                'prescription_invoice.patient_name',
                'users.fcm_token'
            )
            ->get();

        foreach ($bookings as $booking) {
            try {
                $bt = Carbon::createFromFormat('h:i A', trim($booking->booking_time), 'Asia/Kolkata');
            } catch (\Exception $e) {
                try {
                    $bt = Carbon::createFromFormat('H:i', trim($booking->booking_time), 'Asia/Kolkata');
                } catch (\Exception $e2) { continue; }
            }

            $bookingMins = $bt->hour * 60 + $bt->minute;
            $diffMins    = $bookingMins - $nowMins;

            foreach ([60, 30, 15, 5] as $minutesBefore) {
                if ($diffMins < $minutesBefore || $diffMins >= ($minutesBefore + 1)) continue;

                // Pehle DB mein insert karo (duplicate prevent)
                $inserted = DB::table('booking_reminders')->insertOrIgnore([
                    'invoice_id'     => $booking->id,
                    'minutes_before' => $minutesBefore,
                    'sent_at'        => now(),
                ]);

                // Agar insert nahi hua matlab already sent hai
                if (!$inserted) continue;

                $time = $booking->booking_time;
                $msg  = match($minutesBefore) {
                    60 => "आपकी अपॉइंटमेंट 1 घंटे बाद {$time} पर है। कृपया तैयार होकर अस्पताल/क्लिनिक पहुँचें।",
                    30 => "आपकी अपॉइंटमेंट 30 मिनट बाद {$time} पर है। कृपया अभी अस्पताल/क्लिनिक के लिए निकलें।",
                    15 => "आपकी अपॉइंटमेंट 15 मिनट बाद {$time} पर है। कृपया अभी अस्पताल/क्लिनिक पहुँचें।",
                    5  => "आपकी अपॉइंटमेंट सिर्फ 5 मिनट बाद {$time} पर है। कृपया तुरंत अस्पताल/क्लिनिक में उपस्थित हों!",
                };

                FirebaseNotification::send(
                    $booking->fcm_token,
                    '🏥 अपॉइंटमेंट रिमाइंडर',
                    $msg,
                    ['type' => 'reminder', 'invoice_id' => (string)$booking->id, 'minutes_before' => (string)$minutesBefore]
                );

                $this->info("Sent {$minutesBefore}min reminder → booking #{$booking->id}");
            }
        }

        return 0;
    }
}
