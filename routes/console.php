<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tạo ca uống thuốc đến giờ
Schedule::command('doses:generate')
    ->everyMinute()
    ->withoutOverlapping();

// Chốt các ca uống thuốc quá hạn
Schedule::command('doses:missed')
    ->everyMinute()
    ->withoutOverlapping();
