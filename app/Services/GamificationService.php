<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Learning\StudentGamification;

class GamificationService
{
    public function awardXP(int $studentId, int $xp): void
    {
        $game = StudentGamification::firstOrCreate([
            'student_id' => $studentId
        ]);

        $today = Carbon::today();

        if (!$game->last_activity_date) {
            $game->streak_days = 1;
        } elseif (
            $game->last_activity_date->copy()->addDay()->eq($today)
        ) {
            $game->streak_days++;
        } elseif (!$game->last_activity_date->eq($today)) {
            $game->streak_days = 1;
        }

        $game->last_activity_date = $today;
        $game->xp += $xp;
        $game->level = floor($game->xp / 100) + 1;

        $game->save();
    }
}
