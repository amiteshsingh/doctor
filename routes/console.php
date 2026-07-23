<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run every minute to check for upcoming appointment reminders
Schedule::command('bookings:send-reminders')->everyMinute();

// Period reminders - daily at 9 AM
Schedule::command('period:send-reminders')->dailyAt('09:00');

// Pregnancy weekly notifications - daily at 10 AM (sirf weekly anniversary wale users ko jayega)
Schedule::command('pregnancy:weekly-notify')->dailyAt('10:00');

// Vaccine reminders - daily at 9 AM
Schedule::command('vaccine:send-notifications')->dailyAt('09:00');
