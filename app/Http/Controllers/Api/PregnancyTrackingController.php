<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PregnancyTracking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PregnancyTrackingController extends Controller
{
    // Baby size by week (fruit comparison)
    private function babySize(int $week): array {
        $sizes = [
            4  => ['fruit' => 'Poppy Seed',   'emoji' => '🌱', 'size' => '1mm'],
            5  => ['fruit' => 'Sesame Seed',   'emoji' => '🌿', 'size' => '2mm'],
            6  => ['fruit' => 'Sweet Pea',     'emoji' => '🫛', 'size' => '6mm'],
            7  => ['fruit' => 'Blueberry',     'emoji' => '🫐', 'size' => '1cm'],
            8  => ['fruit' => 'Kidney Bean',   'emoji' => '🫘', 'size' => '1.6cm'],
            9  => ['fruit' => 'Grape',         'emoji' => '🍇', 'size' => '2.3cm'],
            10 => ['fruit' => 'Strawberry',    'emoji' => '🍓', 'size' => '3.1cm'],
            11 => ['fruit' => 'Lime',          'emoji' => '🍋', 'size' => '4.1cm'],
            12 => ['fruit' => 'Plum',          'emoji' => '🍑', 'size' => '5.4cm'],
            13 => ['fruit' => 'Peach',         'emoji' => '🍑', 'size' => '7.4cm'],
            14 => ['fruit' => 'Lemon',         'emoji' => '🍋', 'size' => '8.7cm'],
            15 => ['fruit' => 'Apple',         'emoji' => '🍎', 'size' => '10.1cm'],
            16 => ['fruit' => 'Avocado',       'emoji' => '🥑', 'size' => '11.6cm'],
            17 => ['fruit' => 'Pear',          'emoji' => '🍐', 'size' => '13cm'],
            18 => ['fruit' => 'Bell Pepper',   'emoji' => '🫑', 'size' => '14.2cm'],
            19 => ['fruit' => 'Mango',         'emoji' => '🥭', 'size' => '15.3cm'],
            20 => ['fruit' => 'Banana',        'emoji' => '🍌', 'size' => '16.5cm'],
            21 => ['fruit' => 'Carrot',        'emoji' => '🥕', 'size' => '26.7cm'],
            22 => ['fruit' => 'Papaya',        'emoji' => '🍈', 'size' => '27.8cm'],
            23 => ['fruit' => 'Grapefruit',    'emoji' => '🍊', 'size' => '28.9cm'],
            24 => ['fruit' => 'Corn',          'emoji' => '🌽', 'size' => '30cm'],
            25 => ['fruit' => 'Cauliflower',   'emoji' => '🥦', 'size' => '34.6cm'],
            26 => ['fruit' => 'Lettuce',       'emoji' => '🥬', 'size' => '35.6cm'],
            27 => ['fruit' => 'Cabbage',       'emoji' => '🥬', 'size' => '36.6cm'],
            28 => ['fruit' => 'Eggplant',      'emoji' => '🍆', 'size' => '37.6cm'],
            29 => ['fruit' => 'Butternut',     'emoji' => '🎃', 'size' => '38.6cm'],
            30 => ['fruit' => 'Cabbage',       'emoji' => '🥬', 'size' => '39.9cm'],
            31 => ['fruit' => 'Coconut',       'emoji' => '🥥', 'size' => '41.1cm'],
            32 => ['fruit' => 'Squash',        'emoji' => '🎃', 'size' => '42.4cm'],
            33 => ['fruit' => 'Pineapple',     'emoji' => '🍍', 'size' => '43.7cm'],
            34 => ['fruit' => 'Cantaloupe',    'emoji' => '🍈', 'size' => '45cm'],
            35 => ['fruit' => 'Honeydew',      'emoji' => '🍈', 'size' => '46.2cm'],
            36 => ['fruit' => 'Papaya',        'emoji' => '🍈', 'size' => '47.4cm'],
            37 => ['fruit' => 'Watermelon',    'emoji' => '🍉', 'size' => '48.6cm'],
            38 => ['fruit' => 'Pumpkin',       'emoji' => '🎃', 'size' => '49.8cm'],
            39 => ['fruit' => 'Watermelon',    'emoji' => '🍉', 'size' => '50.7cm'],
            40 => ['fruit' => 'Small Pumpkin', 'emoji' => '🎃', 'size' => '51.2cm'],
        ];
        if ($week < 4)  return ['fruit' => 'Tiny Seed', 'emoji' => '🌱', 'size' => '<1mm'];
        if ($week > 40) return $sizes[40];
        return $sizes[$week] ?? $sizes[max(4, min(40, $week))];
    }

    private function trimester(int $week): array {
        if ($week <= 13) return ['number' => 1, 'label' => '1st Trimester', 'color' => '#4CAF50'];
        if ($week <= 26) return ['number' => 2, 'label' => '2nd Trimester', 'color' => '#2196F3'];
        return              ['number' => 3, 'label' => '3rd Trimester', 'color' => '#E91E8C'];
    }

    private function weeklyTip(int $week): string {
        $tips = [
            4  => 'Baby ka dil ban raha hai! Folic acid lena mat bhoolen.',
            8  => 'Ultrasound schedule karein. Morning sickness normal hai.',
            12 => 'Pahla trimester khatam! Risk kam ho gaya.',
            16 => 'Baby ki haddiyan strong ho rahi hain.',
            20 => 'Anatomy scan karwayein. Baby ki movements feel ho sakti hain.',
            24 => 'Baby sunna shuru kar deta hai!',
            28 => 'Teesra trimester shuru! Iron aur calcium zaruri hai.',
            32 => 'Baby ki position check karein.',
            36 => 'Hospital bag taiyaar karein!',
            40 => 'Due date aa gayi! Doctor se milein.',
        ];
        foreach (array_reverse($tips, true) as $w => $tip) {
            if ($week >= $w) return $tip;
        }
        return 'Healthy diet aur rest lein.';
    }

    public function get(Request $request) {
        $user   = $request->auth_user;
        $record = PregnancyTracking::where('user_id', $user->id)->latest()->first();
        if (!$record) return response()->json(['status' => 404, 'data' => null]);

        $lmp  = Carbon::parse($record->lmp_date);
        $now  = Carbon::now();
        $days = $lmp->diffInDays($now);
        $week = (int) floor($days / 7);
        $month = (int) ceil($week / 4.33);
        $dayOfWeek = $days % 7;
        $progress = min(round(($days / 280) * 100), 100);

        return response()->json([
            'status' => 200,
            'data'   => [
                'lmp_date'     => $record->lmp_date,
                'edd'          => $record->edd,
                'week'         => $week,
                'month'        => min($month, 9),
                'day_of_week'  => $dayOfWeek,
                'total_days'   => $days,
                'progress'     => $progress,
                'trimester'    => $this->trimester($week),
                'baby'         => $this->babySize($week),
                'tip'          => $this->weeklyTip($week),
                'days_left'    => max(0, 280 - $days),
            ],
        ]);
    }

    public function save(Request $request) {
        $request->validate(['lmp_date' => 'required|date']);
        $lmp = Carbon::parse($request->lmp_date);
        $edd = $lmp->copy()->addDays(280)->format('Y-m-d');

        $user   = $request->auth_user;
        $record = PregnancyTracking::updateOrCreate(
            ['user_id' => $user->id],
            ['lmp_date' => $request->lmp_date, 'edd' => $edd]
        );

        $days  = $lmp->diffInDays(Carbon::now());
        $week  = (int) floor($days / 7);
        $month = (int) ceil($week / 4.33);

        return response()->json([
            'status' => 200,
            'data'   => [
                'lmp_date'  => $record->lmp_date,
                'edd'       => $record->edd,
                'week'      => $week,
                'month'     => min($month, 9),
                'trimester' => $this->trimester($week),
                'baby'      => $this->babySize($week),
                'progress'  => min(round(($days / 280) * 100), 100),
                'days_left' => max(0, 280 - $days),
                'tip'       => $this->weeklyTip($week),
            ],
        ]);
    }
}
