@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="page-title">Quiz Results — {{ $batch->title ?: 'Batch #'.$batch->id }}</h4>
                <small class="text-muted">
                    {{ $batch->starts_at->format('d M Y, h:i A') }} → {{ $batch->ends_at->format('d M Y, h:i A') }}
                </small>
            </div>
            <div class="col-sm-4 text-right m-b-30">
                <a href="{{ route('admin.daily-quiz.index') }}" class="btn btn-default btn-rounded float-right">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        {{-- Batch Stats --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center" style="background:#E8F5E9;">
                    <div class="card-body py-3">
                        <h3 style="color:#2E7D32;margin:0;">{{ $stats['total'] }}</h3>
                        <small>Total Attempts</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center" style="background:#E3F2FD;">
                    <div class="card-body py-3">
                        <h3 style="color:#1565C0;margin:0;">{{ $stats['correct'] }}</h3>
                        <small>Correct Answers</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center" style="background:#FFF3E0;">
                    <div class="card-body py-3">
                        <h3 style="color:#E65100;margin:0;">
                            {{ $stats['total'] > 0 ? round(($stats['correct']/$stats['total'])*100) : 0 }}%
                        </h3>
                        <small>Accuracy</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center" style="background:#F3E5F5;">
                    <div class="card-body py-3">
                        <h3 style="color:#6A1B9A;margin:0;">{{ $batch->questions->count() }}</h3>
                        <small>Questions</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Top 3 Winners ═══════════════════════════════════════════════ --}}
        @if($winners->count() > 0)
        @php
            $medals = [
                1 => ['icon' => '🥇', 'label' => '1st Place', 'bg' => 'linear-gradient(135deg, #FFD700, #FFA000)', 'border' => '#FFC107', 'text' => '#5D4037', 'size' => '1.6rem'],
                2 => ['icon' => '🥈', 'label' => '2nd Place', 'bg' => 'linear-gradient(135deg, #E0E0E0, #9E9E9E)', 'border' => '#BDBDBD', 'text' => '#37474F', 'size' => '1.4rem'],
                3 => ['icon' => '🥉', 'label' => '3rd Place', 'bg' => 'linear-gradient(135deg, #FFCC80, #FF7043)', 'border' => '#FF8A65', 'text' => '#4E342E', 'size' => '1.3rem'],
            ];
            $totalQuestions = $batch->questions->count();
        @endphp

        <div class="card mb-4" style="border:none; box-shadow: 0 4px 20px rgba(26,35,126,0.12);">
            <div class="card-body" style="background: linear-gradient(135deg, #1A237E 0%, #283593 100%); border-radius:10px; padding: 24px;">
                <h5 class="text-white mb-4 text-center" style="letter-spacing:1px;">
                    🏆 &nbsp; Top 3 Winners — {{ $batch->title ?: 'Batch #'.$batch->id }}
                </h5>

                <div class="row justify-content-center">
                    @foreach($winners as $rank => $winner)
                    @php
                        $pos = $rank + 1;
                        $m   = $medals[$pos];
                        $pct = $totalQuestions > 0 ? round(($winner->correct_count / $totalQuestions) * 100) : 0;
                        $mins = floor($winner->total_time / 60);
                        $secs = $winner->total_time % 60;
                        $timeStr = $mins > 0 ? "{$mins}m {$secs}s" : "{$secs}s";
                        $avatar = strtoupper(substr($winner->user->name ?? 'U', 0, 1));
                    @endphp

                    <div class="col-md-4 col-sm-4 mb-3">
                        <div style="background: {{ $m['bg'] }}; border-radius: 12px; padding: 20px 16px;
                                    text-align: center; border: 2px solid {{ $m['border'] }};
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                                    transform: {{ $pos == 1 ? 'scale(1.06)' : 'scale(1)' }};">

                            {{-- Medal + Rank --}}
                            <div style="font-size: {{ $m['size'] }}; line-height:1;">{{ $m['icon'] }}</div>
                            <div style="font-size:11px; font-weight:700; color:{{ $m['text'] }}; opacity:0.8; margin-top:2px; letter-spacing:1px; text-transform:uppercase;">
                                {{ $m['label'] }}
                            </div>

                            {{-- Avatar --}}
                            <div style="width:52px; height:52px; border-radius:50%; background: rgba(255,255,255,0.35);
                                        margin: 10px auto 6px; display:flex; align-items:center; justify-content:center;
                                        font-size:22px; font-weight:700; color:{{ $m['text'] }}; border:2px solid rgba(255,255,255,0.6);">
                                {{ $avatar }}
                            </div>

                            {{-- Name --}}
                            <div style="font-weight:700; font-size:14px; color:{{ $m['text'] }}; margin-bottom:2px;">
                                {{ $winner->user->name ?? 'Unknown User' }}
                            </div>
                            <div style="font-size:11px; color:{{ $m['text'] }}; opacity:0.75; margin-bottom:10px;">
                                {{ $winner->user->phone_no ?? ($winner->user->email ?? '') }}
                            </div>

                            {{-- Score --}}
                            <div style="background:rgba(255,255,255,0.4); border-radius:8px; padding:8px 10px;">
                                <div style="font-size:22px; font-weight:800; color:{{ $m['text'] }}; line-height:1.1;">
                                    {{ $winner->correct_count }}<span style="font-size:12px; font-weight:400;">/{{ $totalQuestions }}</span>
                                </div>
                                <div style="font-size:11px; color:{{ $m['text'] }}; opacity:0.8;">Correct — {{ $pct }}%</div>
                                <div style="font-size:11px; color:{{ $m['text'] }}; opacity:0.7; margin-top:3px;">
                                    ⏱ {{ $timeStr }} total time
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Tie-break note --}}
                <p class="text-center mb-0 mt-2" style="color:rgba(255,255,255,0.55); font-size:11px;">
                    Ranking: Sabse zyada correct answers → Tie me sabse kam time
                </p>
            </div>
        </div>
        @endif
        {{-- ═══════════════════════════════════════════════════════════════ --}}

        {{-- Per-Question Summary --}}
        @foreach($batch->questions as $qi => $q)
        @php
            $qAttempts = $q->attempts()->count();
            $qCorrect  = $q->attempts()->where('is_correct', true)->count();
        @endphp
        <div class="card mb-2" style="border-left:4px solid #1A237E;">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Q{{ $qi+1 }}:</strong> {{ Str::limit($q->question, 100) }}
                        <span class="badge badge-secondary ml-2">{{ $q->category }}</span>
                        <span class="badge badge-info ml-1">{{ $q->time_limit }}s</span>
                    </div>
                    <div class="text-right" style="min-width:160px;">
                        <span class="badge badge-success">✓ {{ $qCorrect }}</span>
                        <span class="badge badge-danger ml-1">✗ {{ $qAttempts - $qCorrect }}</span>
                        <span class="badge badge-primary ml-1">{{ $qAttempts }} total</span>
                    </div>
                </div>
                <div class="mt-1">
                    @foreach(['A'=>$q->option_a,'B'=>$q->option_b,'C'=>$q->option_c,'D'=>$q->option_d] as $key=>$val)
                    <span class="badge {{ $key == $q->correct_answer ? 'badge-success' : 'badge-light' }}" style="font-size:11px;margin-right:4px;">
                        {{ $key }}: {{ $val }} @if($key == $q->correct_answer) ✓ @endif
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        {{-- Attempts Table --}}
        <h5 class="mt-4 mb-3">All Attempts</h5>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-border table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Question</th>
                                <th>Selected</th>
                                <th>Result</th>
                                <th>Time Taken</th>
                                <th>Attempted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attempts as $a)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $a->user->name ?? 'Unknown' }}<br><small class="text-muted">{{ $a->user->phone ?? '' }}</small></td>
                                <td><small>{{ Str::limit($a->question->question ?? '', 50) }}</small></td>
                                <td>
                                    <span class="badge badge-secondary">{{ $a->selected_answer }}</span>
                                </td>
                                <td>
                                    @if($a->is_correct)
                                        <span class="custom-badge status-green">Correct</span>
                                    @else
                                        <span class="custom-badge status-red">Wrong</span>
                                    @endif
                                </td>
                                <td>{{ $a->time_taken }}s</td>
                                <td>{{ $a->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No attempts yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $attempts->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
