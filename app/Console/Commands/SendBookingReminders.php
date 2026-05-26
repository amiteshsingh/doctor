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
        $now      = Carbon::now('Asia/Kolkata');
        $today    = $now->format('Y-m-d');
        $nowMins  = $now->hour * 60 + $now->minute;

        // All active bookings for today with user fcm_token
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
            // Parse booking time to total minutes
            try {
                $bt = Carbon::createFromFormat('h:i A', trim($booking->booking_time), 'Asia/Kolkata');
            } catch (\Exception $e) {
                try {
                    $bt = Carbon::createFromFormat('H:i', trim($booking->booking_time), 'Asia/Kolkata');
                } catch (\Exception $e2) {
                    continue;
                }
            }

            $bookingMins = $bt->hour * 60 + $bt->minute;
            $diffMins    = $bookingMins - $nowMins; // minutes remaining

            // Check each reminder window
            foreach ([60, 30, 15, 5] as $minutesBefore) {
                // Only send if diff is within this window (between minutesBefore and minutesBefore-1)
                if ($diffMins < $minutesBefore || $diffMins >= ($minutesBefore + 1)) {
                    continue;
                }

                // Check if already sent
                $alreadySent = DB::table('booking_reminders')
                    ->where('invoice_id', $booking->id)
                    ->where('minutes_before', $minutesBefore)
                    ->exists();

                if ($alreadySent) continue;

                // Send notification
                $msg = match($minutesBefore) {
                    60 => "⏰ Your appointment is in 1 hour at {$booking->booking_time}. Please get ready and reach the hospital/clinic on time.",
                    30 => "⏰ Your appointment is in 30 minutes at {$booking->booking_time}. Please start heading to the hospital/clinic.",
                    15 => "⏰ Your appointment is in 15 minutes at {$booking->booking_time}. Please reach the hospital/clinic now.",
                    5  => "🚨 Your appointment is in just 5 minutes at {$booking->booking_time}. Please be at the hospital/clinic!",
                };

                $sent = FirebaseNotification::send(
                    $booking->fcm_token,
                    '🏥 Appointment Reminder',
                    $msg,
                    [
                        'type'           => 'reminder',
                        'invoice_id'     => (string)$booking->id,
                        'minutes_before' => (string)$minutesBefore,
                    ]
                );

                if ($sent) {
                    // Mark as sent so it doesn't send again
                    DB::table('booking_reminders')->insert([
                        'invoice_id'     => $booking->id,
                        'minutes_before' => $minutesBefore,
                        'sent_at'        => now(),
                    ]);
                    $this->info("✅ Sent {$minutesBefore}min reminder → booking #{$booking->id} at {$booking->booking_time}");
                }
            }
        }

        return 0;
    }
}
