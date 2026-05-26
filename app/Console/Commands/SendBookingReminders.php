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
        $reminders = [60, 30, 15, 5];

        foreach ($reminders as $minutesBefore) {
            $targetTime = Carbon::now('Asia/Kolkata')->addMinutes($minutesBefore);
            $targetDate = $targetTime->format('Y-m-d');
            $targetHour = $targetTime->format('H');
            $targetMin  = $targetTime->format('i');

            // Get active bookings whose time matches target window (within 1 min)
            $bookings = DB::table('prescription_invoice')
                ->join('users', 'prescription_invoice.user_id', '=', 'users.id')
                ->where('prescription_invoice.booking_date', $targetDate)
                ->where(function($q) { $q->whereNull('prescription_invoice.status')->orWhere('prescription_invoice.status', '!=', 'cancelled'); })
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
                // Parse booking time to H:i
                try {
                    $bt = Carbon::createFromFormat('h:i A', trim($booking->booking_time));
                } catch (\Exception $e) {
                    try {
                        $bt = Carbon::createFromFormat('H:i', trim($booking->booking_time));
                    } catch (\Exception $e2) {
                        continue;
                    }
                }

                $bookingHour = (int)$bt->format('H');
                $bookingMin  = (int)$bt->format('i');

                // Check if booking time matches target time (within ±1 minute)
                $bookingTotalMin = $bookingHour * 60 + $bookingMin;
                $targetTotalMin  = (int)$targetHour * 60 + (int)$targetMin;

                if (abs($bookingTotalMin - $targetTotalMin) > 1) continue;

                $msg = match($minutesBefore) {
                    60 => "Your appointment is in 1 hour at {$booking->booking_time}. Please get ready and reach the hospital/clinic on time.",
                    30 => "Your appointment is in 30 minutes at {$booking->booking_time}. Please start heading to the hospital/clinic.",
                    15 => "Your appointment is in 15 minutes at {$booking->booking_time}. Please reach the hospital/clinic now.",
                    5  => "Your appointment is in just 5 minutes at {$booking->booking_time}. Please be at the hospital/clinic.",
                };

                FirebaseNotification::send(
                    $booking->fcm_token,
                    '⏰ Appointment Reminder',
                    $msg,
                    ['type' => 'reminder', 'invoice_id' => (string)$booking->id, 'minutes_before' => (string)$minutesBefore]
                );

                $this->info("Sent {$minutesBefore}min reminder to booking #{$booking->id}");
            }
        }

        return 0;
    }
}
