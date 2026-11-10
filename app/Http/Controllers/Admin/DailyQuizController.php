<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyQuizBatch;
use App\Models\DailyQuizQuestion;
use App\Models\DailyQuizAttempt;
use App\Models\User;
use App\Services\FirebaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyQuizController extends Controller
{
    public function index()
    {
        $batches = DailyQuizBatch::withCount('questions')->latest()->paginate(15);
        return view('admin.daily_quiz.index', compact('batches'));
    }

    public function create()
    {
        return view('admin.daily_quiz.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'nullable|string|max:200',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after:starts_at',
            'questions'              => 'required|array|min:1',
            'questions.*.question'   => 'required|string|max:500',
            'questions.*.option_a'   => 'required|string|max:255',
            'questions.*.option_b'   => 'required|string|max:255',
            'questions.*.option_c'   => 'required|string|max:255',
            'questions.*.option_d'   => 'required|string|max:255',
            'questions.*.correct_answer' => 'required|in:A,B,C,D',
            'questions.*.category'   => 'required|string|max:100',
            'questions.*.time_limit' => 'required|integer|min:10|max:300',
            'questions.*.explanation'=> 'nullable|string|max:1000',
        ]);

        $batch = DailyQuizBatch::create([
            'title'     => $request->title,
            'starts_at' => $request->starts_at,
            'ends_at'   => $request->ends_at,
        ]);

        foreach ($request->questions as $i => $q) {
            DailyQuizQuestion::create([
                'batch_id'       => $batch->id,
                'question'       => $q['question'],
                'option_a'       => $q['option_a'],
                'option_b'       => $q['option_b'],
                'option_c'       => $q['option_c'],
                'option_d'       => $q['option_d'],
                'correct_answer' => $q['correct_answer'],
                'explanation'    => $q['explanation'] ?? null,
                'category'       => $q['category'],
                'time_limit'     => $q['time_limit'],
                'sort_order'     => $i,
                'quiz_date'      => now()->toDateString(),
            ]);
        }

        // ── Push notification to all users ──────────────────────────────────
        $this->sendQuizNotification($batch, count($request->questions));
        // ────────────────────────────────────────────────────────────────────

        return redirect()->route('admin.daily-quiz.index')
            ->with('success', count($request->questions) . ' questions added in batch #' . $batch->id . '!');
    }

    public function edit($id)
    {
        $batch = DailyQuizBatch::with('questions')->findOrFail($id);
        return view('admin.daily_quiz.add', compact('batch'));
    }

    public function update(Request $request, $id)
    {
        $batch = DailyQuizBatch::findOrFail($id);

        $request->validate([
            'title'     => 'nullable|string|max:200',
            'starts_at' => 'required|date',
            'ends_at'   => 'required|date|after:starts_at',
            'questions'              => 'required|array|min:1',
            'questions.*.question'   => 'required|string|max:500',
            'questions.*.option_a'   => 'required|string|max:255',
            'questions.*.option_b'   => 'required|string|max:255',
            'questions.*.option_c'   => 'required|string|max:255',
            'questions.*.option_d'   => 'required|string|max:255',
            'questions.*.correct_answer' => 'required|in:A,B,C,D',
            'questions.*.category'   => 'required|string|max:100',
            'questions.*.time_limit' => 'required|integer|min:10|max:300',
            'questions.*.explanation'=> 'nullable|string|max:1000',
        ]);

        $batch->update([
            'title'     => $request->title,
            'starts_at' => $request->starts_at,
            'ends_at'   => $request->ends_at,
        ]);

        // Delete old questions and re-insert
        $batch->questions()->delete();

        foreach ($request->questions as $i => $q) {
            DailyQuizQuestion::create([
                'batch_id'       => $batch->id,
                'question'       => $q['question'],
                'option_a'       => $q['option_a'],
                'option_b'       => $q['option_b'],
                'option_c'       => $q['option_c'],
                'option_d'       => $q['option_d'],
                'correct_answer' => $q['correct_answer'],
                'explanation'    => $q['explanation'] ?? null,
                'category'       => $q['category'],
                'time_limit'     => $q['time_limit'],
                'sort_order'     => $i,
                'quiz_date'      => now()->toDateString(),
            ]);
        }

        return redirect()->route('admin.daily-quiz.index')->with('success', 'Batch updated successfully!');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Push notification helper — called after a new quiz batch is created
    // ─────────────────────────────────────────────────────────────────────────
    private function sendQuizNotification(DailyQuizBatch $batch, int $questionCount): void
    {
        try {
            // Collect FCM tokens of all regular users (deduplicated)
            $tokens = User::whereHas('role', fn($q) => $q->where('role', 'user'))
                ->whereNotNull('fcm_token')
                ->distinct('fcm_token')
                ->pluck('fcm_token')
                ->toArray();

            if (empty($tokens)) return;

            // Compose an engaging message
            $title = '🎯 Naya Daily Quiz Aa Gaya!';

            if ($batch->title) {
                $body = "\"" . $batch->title . "\" quiz abhi available hai! Apni knowledge test karo aur top leaderboard par aao. 🏆";
            } else {
                $body = "Aaj ka naya quiz available hai! " . $questionCount . " exciting sawaalon ke sath — kya aap tayaar hain? 💡";
            }

            $result = FirebaseNotification::sendBulk(
                $tokens,
                $title,
                $body,
                [
                    'type'     => 'daily_quiz',
                    'batch_id' => (string) $batch->id,
                    'screen'   => 'DailyQuiz',
                ]
            );

            // Log to notification_logs for audit trail
            DB::table('notification_logs')->insert([
                'title'        => $title,
                'message'      => $body,
                'target'       => 'all',
                'user_id'      => null,
                'sent_count'   => $result['sent'],
                'failed_count' => $result['failed'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

        } catch (\Exception $e) {
            // Log the error but don't block the quiz creation
            \Log::error('Daily Quiz Notification Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $batch = DailyQuizBatch::findOrFail($id);
        $batch->questions()->delete();
        $batch->delete();
        return redirect()->route('admin.daily-quiz.index')->with('success', 'Batch deleted.');
    }

    public function toggle($id)
    {
        $batch = DailyQuizBatch::findOrFail($id);
        $batch->update(['is_active' => !$batch->is_active]);
        return back()->with('success', 'Status updated.');
    }

    public function results($id)
    {
        $batch = DailyQuizBatch::with('questions')->findOrFail($id);
        $questionIds = $batch->questions->pluck('id');
        $attempts = DailyQuizAttempt::with(['user', 'question'])
            ->whereIn('question_id', $questionIds)
            ->latest()
            ->paginate(30);
        $stats = [
            'total'   => DailyQuizAttempt::whereIn('question_id', $questionIds)->count(),
            'correct' => DailyQuizAttempt::whereIn('question_id', $questionIds)->where('is_correct', true)->count(),
        ];
        return view('admin.daily_quiz.results', compact('batch', 'attempts', 'stats'));
    }
}
