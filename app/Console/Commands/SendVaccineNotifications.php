<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChildVaccine;
use App\Services\FirebaseNotification;
use Carbon\Carbon;

class SendVaccineNotifications extends Command
{
    protected $signature   = 'vaccine:send-notifications';
    protected $description = 'Send notifications for vaccines due today or tomorrow';

    public function handle(): void {
        $today    = Carbon::today()->format('Y-m-d');
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        // Aaj aur kal due vaccines jo abhi tak nahi lagi
        $dues = ChildVaccine::whereNull('given_date')
            ->whereIn('due_date', [$today, $tomorrow])
            ->with('child.user')
            ->get();

        foreach ($dues as $vaccine) {
            $child = $vaccine->child;
            $user  = $child?->user;
            if (!$user || !$user->fcm_token) continue;

            $isToday = $vaccine->due_date === $today;
            $title   = $isToday
                ? "💉 Aaj Tika Lagwana Hai!"
                : "⏰ Kal Tika Lagwana Hai!";

            $body = "{$child->name} ko aaj '{$vaccine->vaccine_name}' tika lagwana hai. Doctor se milein!";
            if (!$isToday) {
                $body = "{$child->name} ko kal '{$vaccine->vaccine_name}' tika lagwana hai. Appointment book karein!";
            }

            FirebaseNotification::send($user->fcm_token, $title, $body, [
                'type'         => 'vaccine_reminder',
                'child_id'     => (string) $child->id,
                'vaccine_name' => $vaccine->vaccine_name,
            ]);
        }

        $this->info("Vaccine notifications sent: {$dues->count()}");
    }
}
