<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyQuizBatch;
use App\Models\DailyQuizQuestion;
use App\Models\DailyQuizAttempt;
use Illuminate\Http\Request;

class DailyQuizController extends Controller
{
    // GET /v1/daily-quiz/debug
    public function debug(Request $request)
    {
        $now = now();
        $tablesExist = [
            'daily_quiz_batches'   => \Schema::hasTable('daily_quiz_batches'),
            'daily_quiz_questions' => \Schema::hasTable('daily_quiz_questions'),
            'batch_id_col'         => \Schema::hasColumn('daily_quiz_questions', 'batch_id'),
            'starts_at_col'        => \Schema::hasColumn('daily_quiz_questions', 'starts_at'),
        ];
        $batches   = \Schema::hasTable('daily_quiz_batches') ? \DB::table('daily_quiz_batches')->get() : [];
        $questions = \Schema::hasTable('daily_quiz_questions') ? \DB::table('daily_quiz_questions')->get() : [];
        return response()->json([
            'server_time' => $now->toDateTimeString(),
            'timezone'    => config('app.timezone'),
            'tables'      => $tablesExist,
            'batches'     => $batches,
            'questions'   => $questions,
        ]);
    }

    // GET /v1/daily-quiz/today
    public function today(Request $request)
    {
        // Fallback: if batches table doesn't exist yet (migration pending)
        if (!\Schema::hasTable('daily_quiz_batches') || !\Schema::hasColumn('daily_quiz_questions', 'batch_id')) {
            return $this->todayLegacy($request);
        }

        $batch = DailyQuizBatch::activeBatch();

        if (!$batch) {
            return response()->json(['status' => false, 'message' => 'No active quiz right now'], 404);
        }

        $userId = $request->auth_user->id ?? null;

        // ── Participant limit check ───────────────────────────────────────────
        if ($batch->max_participants !== null) {
            $participantCount = $batch->participantCount();
            // Check if this user is already a participant
            $userAlreadyIn = $userId && DailyQuizAttempt::whereIn('question_id', $batch->questions()->pluck('id'))
                ->where('user_id', $userId)->exists();

            if (!$userAlreadyIn && $participantCount >= $batch->max_participants) {
                return response()->json([
                    'status'           => false,
                    'limit_full'       => true,
                    'message'          => 'Is quiz mein participant limit full ho chuki hai. Agli quiz ka wait karein!',
                    'max_participants' => $batch->max_participants,
                    'current_count'    => $participantCount,
                ], 403);
            }
        }
        // ─────────────────────────────────────────────────────────────────────

        $questions = $batch->questions()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($q) use ($userId) {
                $attempted = false;
                $myAnswer  = null;

                if ($userId) {
                    $attempt = DailyQuizAttempt::where('user_id', $userId)
                        ->where('question_id', $q->id)
                        ->first();
                    if ($attempt) {
                        $attempted = true;
                        $myAnswer  = $attempt->selected_answer;
                    }
                }

                return [
                    'id'             => $q->id,
                    'question'       => $q->question,
                    'category'       => $q->category,
                    'time_limit'     => $q->time_limit,
                    'options'        => [
                        'A' => $q->option_a,
                        'B' => $q->option_b,
                        'C' => $q->option_c,
                        'D' => $q->option_d,
                    ],
                    'attempted'      => $attempted,
                    'my_answer'      => $myAnswer,
                    'correct_answer' => $attempted ? $q->correct_answer : null,
                    'explanation'    => $attempted ? $q->explanation : null,
                ];
            });

        return response()->json([
            'status' => true,
            'batch'  => [
                'id'        => $batch->id,
                'title'     => $batch->title,
                'ends_at'   => $batch->ends_at->toIso8601String(),
                'starts_at' => $batch->starts_at->toIso8601String(),
            ],
            'data' => $questions,
        ]);
    }

    // Legacy fallback (before batch migration)
    private function todayLegacy(Request $request)
    {
        $question = DailyQuizQuestion::where('quiz_date', today())
            ->where('is_active', true)
            ->first();

        if (!$question) {
            return response()->json(['status' => false, 'message' => 'No quiz for today'], 404);
        }

        $userId   = $request->auth_user->id ?? null;
        $attempted = false;
        $myAnswer  = null;

        if ($userId) {
            $attempt = DailyQuizAttempt::where('user_id', $userId)
                ->where('question_id', $question->id)->first();
            if ($attempt) { $attempted = true; $myAnswer = $attempt->selected_answer; }
        }

        return response()->json([
            'status' => true,
            'batch'  => [
                'id'        => 0,
                'title'     => 'Daily Quiz',
                'starts_at' => now()->startOfDay()->toIso8601String(),
                'ends_at'   => now()->endOfDay()->toIso8601String(),
            ],
            'data' => [[
                'id'             => $question->id,
                'question'       => $question->question,
                'category'       => $question->category,
                'time_limit'     => $question->time_limit,
                'options'        => ['A'=>$question->option_a,'B'=>$question->option_b,'C'=>$question->option_c,'D'=>$question->option_d],
                'attempted'      => $attempted,
                'my_answer'      => $myAnswer,
                'correct_answer' => $attempted ? $question->correct_answer : null,
                'explanation'    => $attempted ? $question->explanation : null,
            ]],
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

        // Check batch is still active
        if ($question->batch_id) {
            $batch = DailyQuizBatch::find($question->batch_id);
            if (!$batch || !now()->between($batch->starts_at, $batch->ends_at) || !$batch->is_active) {
                return response()->json(['status' => false, 'message' => 'Quiz has ended'], 403);
            }
            // Participant limit check on submit too
            if ($batch->max_participants !== null) {
                $alreadyAttempted = DailyQuizAttempt::where('user_id', $userId)
                    ->whereIn('question_id', $batch->questions()->pluck('id'))->exists();
                if (!$alreadyAttempted) {
                    $count = $batch->participantCount();
                    if ($count >= $batch->max_participants) {
                        return response()->json([
                            'status'     => false,
                            'limit_full' => true,
                            'message'    => 'Participant limit full ho chuki hai.',
                        ], 403);
                    }
                }
            }
        }

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

        $total   = DailyQuizAttempt::where('question_id', $question->id)->count();
        $correct = DailyQuizAttempt::where('question_id', $question->id)->where('is_correct', true)->count();

        return response()->json([
            'status'         => true,
            'is_correct'     => $isCorrect,
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

        // All attempts with question + batch info
        $attempts = DailyQuizAttempt::with(['question.batch'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        // Overall stats
        $totalPlayed  = $attempts->count();
        $totalCorrect = $attempts->where('is_correct', true)->count();

        // Group by batch_id (or by date if no batch)
        $grouped = $attempts->groupBy(function ($a) {
            return $a->question->batch_id ?? 'no_batch_' . $a->created_at->toDateString();
        });

        $sessions = $grouped->map(function ($group) {
            $first      = $group->first();
            $batch      = $first->question->batch ?? null;
            $correct    = $group->where('is_correct', true)->count();
            $total      = $group->count();
            $accuracy   = $total > 0 ? round(($correct / $total) * 100) : 0;

            $questions = $group->map(function ($a) {
                return [
                    'question'        => $a->question->question ?? '',
                    'category'        => $a->question->category ?? '',
                    'options'         => [
                        'A' => $a->question->option_a ?? '',
                        'B' => $a->question->option_b ?? '',
                        'C' => $a->question->option_c ?? '',
                        'D' => $a->question->option_d ?? '',
                    ],
                    'explanation'     => $a->question->explanation ?? null,
                    'selected_answer' => $a->selected_answer,
                    'correct_answer'  => $a->question->correct_answer ?? '',
                    'is_correct'      => (bool) $a->is_correct,
                    'time_taken'      => $a->time_taken,
                    'attempted_at'    => $a->created_at->toIso8601String(),
                ];
            })->values();

            return [
                'batch_id'    => $batch->id ?? null,
                'batch_title' => $batch->title ?? 'Quiz',
                'played_at'   => $first->created_at->toIso8601String(),
                'total'       => $total,
                'correct'     => $correct,
                'wrong'       => $total - $correct,
                'accuracy'    => $accuracy,
                'questions'   => $questions,
            ];
        })->values();

        return response()->json([
            'status' => true,
            'stats'  => [
                'total_sessions' => $sessions->count(),
                'total_played'   => $totalPlayed,
                'total_correct'  => $totalCorrect,
                'total_wrong'    => $totalPlayed - $totalCorrect,
                'accuracy'       => $totalPlayed > 0 ? round(($totalCorrect / $totalPlayed) * 100) : 0,
            ],
            'sessions' => $sessions,
        ]);
    }
}
