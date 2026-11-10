<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyQuizQuestion;
use App\Models\DailyQuizAttempt;
use Illuminate\Http\Request;

class DailyQuizController extends Controller
{
    // GET /v1/daily-quiz/today
    public function today(Request $request)
    {
        $question = DailyQuizQuestion::todayQuestion();

        if (!$question) {
            return response()->json(['status' => false, 'message' => 'No quiz for today'], 404);
        }

        $userId = $request->auth_user->id ?? null;
        $attempted = false;
        $myAnswer  = null;

        if ($userId) {
            $attempt = DailyQuizAttempt::where('user_id', $userId)
                ->where('question_id', $question->id)
                ->first();
            if ($attempt) {
                $attempted = true;
                $myAnswer  = $attempt->selected_answer;
            }
        }

        return response()->json([
            'status' => true,
            'data'   => [
                'id'             => $question->id,
                'question'       => $question->question,
                'category'       => $question->category,
                'time_limit'     => $question->time_limit,
                'quiz_date'      => $question->quiz_date->format('Y-m-d'),
                'options'        => [
                    'A' => $question->option_a,
                    'B' => $question->option_b,
                    'C' => $question->option_c,
                    'D' => $question->option_d,
                ],
                'attempted'      => $attempted,
                'my_answer'      => $myAnswer,
                // Only reveal correct answer if already attempted
                'correct_answer' => $attempted ? $question->correct_answer : null,
                'explanation'    => $attempted ? $question->explanation : null,
            ],
        ]);
    }

    // POST /v1/daily-quiz/submit
    public function submit(Request $request)
    {
        $request->validate([
            'question_id'     => 'required|exists:daily_quiz_questions,id',
            'selected_answer' => 'required|in:A,B,C,D',
            'time_taken'      => 'nullable|integer|min:0',
        ]);

        $userId   = $request->auth_user->id;
        $question = DailyQuizQuestion::findOrFail($request->question_id);

        // Prevent double attempt
        $existing = DailyQuizAttempt::where('user_id', $userId)
            ->where('question_id', $question->id)
            ->first();

        if ($existing) {
            return response()->json(['status' => false, 'message' => 'Already attempted'], 409);
        }

        $isCorrect = $request->selected_answer === $question->correct_answer;

        DailyQuizAttempt::create([
            'user_id'         => $userId,
            'question_id'     => $question->id,
            'selected_answer' => $request->selected_answer,
            'is_correct'      => $isCorrect,
            'time_taken'      => $request->time_taken ?? 0,
        ]);

        // Stats
        $total   = DailyQuizAttempt::where('question_id', $question->id)->count();
        $correct = DailyQuizAttempt::where('question_id', $question->id)->where('is_correct', true)->count();

        return response()->json([
            'status'     => true,
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
            'explanation'    => $question->explanation,
            'stats' => [
                'total_attempts'   => $total,
                'correct_attempts' => $correct,
                'accuracy'         => $total > 0 ? round(($correct / $total) * 100) : 0,
            ],
        ]);
    }

    // GET /v1/daily-quiz/history
    public function history(Request $request)
    {
        $userId = $request->auth_user->id;

        $attempts = DailyQuizAttempt::with('question')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(20);

        $data = $attempts->map(function ($a) {
            return [
                'date'            => $a->question->quiz_date->format('Y-m-d'),
                'question'        => $a->question->question,
                'category'        => $a->question->category,
                'selected_answer' => $a->selected_answer,
                'correct_answer'  => $a->question->correct_answer,
                'is_correct'      => $a->is_correct,
                'time_taken'      => $a->time_taken,
            ];
        });

        $totalCorrect = DailyQuizAttempt::where('user_id', $userId)->where('is_correct', true)->count();
        $totalPlayed  = DailyQuizAttempt::where('user_id', $userId)->count();

        return response()->json([
            'status' => true,
            'stats'  => [
                'total_played'  => $totalPlayed,
                'total_correct' => $totalCorrect,
                'accuracy'      => $totalPlayed > 0 ? round(($totalCorrect / $totalPlayed) * 100) : 0,
            ],
            'data' => $data,
        ]);
    }
}
