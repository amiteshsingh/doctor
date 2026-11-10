<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyQuizQuestion;
use App\Models\DailyQuizAttempt;
use Illuminate\Http\Request;

class DailyQuizController extends Controller
{
    public function index()
    {
        $questions = DailyQuizQuestion::latest('quiz_date')->paginate(20);
        return view('admin.daily_quiz.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.daily_quiz.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question'       => 'required|string|max:500',
            'option_a'       => 'required|string|max:255',
            'option_b'       => 'required|string|max:255',
            'option_c'       => 'required|string|max:255',
            'option_d'       => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
            'explanation'    => 'nullable|string|max:1000',
            'category'       => 'required|string|max:100',
            'quiz_date'      => 'required|date|unique:daily_quiz_questions,quiz_date',
            'time_limit'     => 'required|integer|min:10|max:120',
        ]);

        DailyQuizQuestion::create($request->all());

        return redirect()->route('admin.daily-quiz.index')->with('success', 'Quiz question added successfully!');
    }

    public function edit($id)
    {
        $question = DailyQuizQuestion::findOrFail($id);
        return view('admin.daily_quiz.add', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $question = DailyQuizQuestion::findOrFail($id);

        $request->validate([
            'question'       => 'required|string|max:500',
            'option_a'       => 'required|string|max:255',
            'option_b'       => 'required|string|max:255',
            'option_c'       => 'required|string|max:255',
            'option_d'       => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
            'explanation'    => 'nullable|string|max:1000',
            'category'       => 'required|string|max:100',
            'quiz_date'      => 'required|date|unique:daily_quiz_questions,quiz_date,' . $id,
            'time_limit'     => 'required|integer|min:10|max:120',
        ]);

        $question->update($request->all());

        return redirect()->route('admin.daily-quiz.index')->with('success', 'Quiz question updated successfully!');
    }

    public function destroy($id)
    {
        DailyQuizQuestion::findOrFail($id)->delete();
        return redirect()->route('admin.daily-quiz.index')->with('success', 'Question deleted.');
    }

    public function toggle($id)
    {
        $q = DailyQuizQuestion::findOrFail($id);
        $q->update(['is_active' => !$q->is_active]);
        return back()->with('success', 'Status updated.');
    }

    public function results($id)
    {
        $question = DailyQuizQuestion::with('attempts.user')->findOrFail($id);
        $attempts = DailyQuizAttempt::with('user')
            ->where('question_id', $id)
            ->latest()
            ->paginate(30);
        $stats = [
            'total'   => $attempts->total(),
            'correct' => DailyQuizAttempt::where('question_id', $id)->where('is_correct', true)->count(),
        ];
        return view('admin.daily_quiz.results', compact('question', 'attempts', 'stats'));
    }
}
