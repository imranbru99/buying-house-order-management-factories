<?php

use App\Jobs\SendTnaReminderJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SendTnaReminderJob)->dailyAt('08:00')->name('tradeflow-tna-reminders');
