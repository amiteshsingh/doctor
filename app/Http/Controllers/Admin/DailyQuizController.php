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
use PhpOffice\PhpSpreadsheet\IOFactory;

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
    // Excel Import — show import form
    // ─────────────────────────────────────────────────────────────────────────
    public function importForm()
    {
        return view('admin.daily_quiz.import');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Excel Import — process uploaded file
    // ─────────────────────────────────────────────────────────────────────────
    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
            'title'      => 'nullable|string|max:200',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after:starts_at',
        ]);

        try {
            $file        = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, false); // 0-indexed

            // Row 0 = header, data starts from row 1
            $validCategories = ['General', 'Health', 'Science', 'History', 'Geography', 'Sports', 'Technology', 'Math'];
            $questions       = [];
            $errors          = [];

            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex === 0) continue; // skip header

                // Skip completely empty rows
                $nonEmpty = array_filter(array_map('trim', array_map('strval', $row)));
                if (empty($nonEmpty)) continue;

                $lineNo  = $rowIndex + 1; // human-readable line number
                $q       = trim((string)($row[0] ?? ''));
                $optA    = trim((string)($row[1] ?? ''));
                $optB    = trim((string)($row[2] ?? ''));
                $optC    = trim((string)($row[3] ?? ''));
                $optD    = trim((string)($row[4] ?? ''));
                $correct = strtoupper(trim((string)($row[5] ?? '')));
                $cat     = trim((string)($row[6] ?? 'General'));
                $time    = (int)($row[7] ?? 30);
                $expl    = trim((string)($row[8] ?? ''));

                // Validate row
                if (empty($q))                         { $errors[] = "Row {$lineNo}: Question khali hai."; continue; }
                if (empty($optA))                      { $errors[] = "Row {$lineNo}: Option A khali hai."; continue; }
                if (empty($optB))                      { $errors[] = "Row {$lineNo}: Option B khali hai."; continue; }
                if (empty($optC))                      { $errors[] = "Row {$lineNo}: Option C khali hai."; continue; }
                if (empty($optD))                      { $errors[] = "Row {$lineNo}: Option D khali hai."; continue; }
                if (!in_array($correct, ['A','B','C','D'])) { $errors[] = "Row {$lineNo}: Correct answer '{$correct}' galat hai (A/B/C/D hona chahiye)."; continue; }
                if (!in_array($cat, $validCategories)) $cat = 'General';
                if ($time < 10 || $time > 300)         $time = 30;

                $questions[] = [
                    'question'       => $q,
                    'option_a'       => $optA,
                    'option_b'       => $optB,
                    'option_c'       => $optC,
                    'option_d'       => $optD,
                    'correct_answer' => $correct,
                    'category'       => $cat,
                    'time_limit'     => $time,
                    'explanation'    => $expl,
                ];
            }

            if (!empty($errors)) {
                return back()
                    ->withInput()
                    ->with('import_errors', $errors)
                    ->with('error', count($errors) . ' row(s) me error hai. Neeche details dekho.');
            }

            if (empty($questions)) {
                return back()->withInput()->with('error', 'Excel file me koi valid question nahi mila.');
            }

            // Create batch
            $batch = DailyQuizBatch::create([
                'title'     => $request->title,
                'starts_at' => $request->starts_at,
                'ends_at'   => $request->ends_at,
            ]);

            foreach ($questions as $i => $q) {
                DailyQuizQuestion::create([
                    'batch_id'       => $batch->id,
                    'question'       => $q['question'],
                    'option_a'       => $q['option_a'],
                    'option_b'       => $q['option_b'],
                    'option_c'       => $q['option_c'],
                    'option_d'       => $q['option_d'],
                    'correct_answer' => $q['correct_answer'],
                    'explanation'    => $q['explanation'],
                    'category'       => $q['category'],
                    'time_limit'     => $q['time_limit'],
                    'sort_order'     => $i,
                    'quiz_date'      => now()->toDateString(),
                ]);
            }

            // Push notification to all users
            $this->sendQuizNotification($batch, count($questions));

            return redirect()->route('admin.daily-quiz.index')
                ->with('success', '✅ Excel se ' . count($questions) . ' questions import ho gaye! Batch #' . $batch->id . ' create hua.');

        } catch (\Exception $e) {
            \Log::error('Quiz Excel Import Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'File read karne me error aaya: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Sample Excel template download
    // ─────────────────────────────────────────────────────────────────────────
    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Quiz Questions');

        // Header row — style karte hain
        $headers = ['Question', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Answer (A/B/C/D)', 'Category', 'Time Limit (sec)', 'Explanation (optional)'];
        foreach ($headers as $col => $header) {
            $cell = $sheet->getCellByColumnAndRow($col + 1, 1);
            $cell->setValue($header);
            $cell->getStyle()->getFont()->setBold(true);
            $cell->getStyle()->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('1A237E');
            $cell->getStyle()->getFont()->getColor()->setRGB('FFFFFF');
        }

        // Sample rows
        $samples = [
            ['Human body me kitni haddiya hoti hain?', '206', '208', '210', '212', 'A', 'Health', 30, 'Adult human body me 206 bones hoti hain.'],
            ['Paani ka chemical formula kya hai?', 'H2O', 'CO2', 'O2', 'H2SO4', 'A', 'Science', 20, 'Paani do hydrogen aur ek oxygen atom se bana hai.'],
            ['India ka sabse bada state kaun sa hai (area me)?', 'Maharashtra', 'Madhya Pradesh', 'Rajasthan', 'Uttar Pradesh', 'C', 'Geography', 30, 'Rajasthan area ke hisaab se India ka sabse bada state hai.'],
        ];

        foreach ($samples as $rowIndex => $row) {
            foreach ($row as $col => $value) {
                $sheet->getCellByColumnAndRow($col + 1, $rowIndex + 2)->setValue($value);
            }
        }

        // Auto-width for columns
        foreach (range(1, count($headers)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        // Add dropdown validation for Correct Answer column (F)
        $validation = $sheet->getDataValidation('F2:F1000');
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(false)
            ->setShowDropDown(false)
            ->setFormula1('"A,B,C,D"');

        // Category dropdown validation (G column)
        $catValidation = $sheet->getDataValidation('G2:G1000');
        $catValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
            ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(true)
            ->setShowDropDown(false)
            ->setFormula1('"General,Health,Science,History,Geography,Sports,Technology,Math"');

        $filename = 'quiz_import_template.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
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
        $batch       = DailyQuizBatch::with('questions')->findOrFail($id);
        $questionIds = $batch->questions->pluck('id');

        $attempts = DailyQuizAttempt::with(['user', 'question'])
            ->whereIn('question_id', $questionIds)
            ->latest()
            ->paginate(30);

        $stats = [
            'total'   => DailyQuizAttempt::whereIn('question_id', $questionIds)->count(),
            'correct' => DailyQuizAttempt::whereIn('question_id', $questionIds)->where('is_correct', true)->count(),
        ];

        // ── Top 3 Winners ────────────────────────────────────────────────────
        // Ranking: most correct answers first; tie-break = least total time taken
        $winners = DailyQuizAttempt::with('user')
            ->whereIn('question_id', $questionIds)
            ->selectRaw('user_id,
                         SUM(is_correct) as correct_count,
                         SUM(time_taken) as total_time,
                         COUNT(*) as answered')
            ->groupBy('user_id')
            ->orderByDesc('correct_count')
            ->orderBy('total_time')
            ->limit(3)
            ->get();
        // ─────────────────────────────────────────────────────────────────────

        return view('admin.daily_quiz.results', compact('batch', 'attempts', 'stats', 'winners'));
    }
}
