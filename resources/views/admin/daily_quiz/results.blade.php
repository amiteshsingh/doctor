@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="page-title">Quiz Results — {{ $question->quiz_date->format('d M Y') }}</h4>
            </div>
            <div class="col-sm-4 text-right m-b-30">
                <a href="{{ route('admin.daily-quiz.index') }}" class="btn btn-default btn-rounded float-right">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        {{-- Question Card --}}
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card" style="border-left:4px solid #1A237E;">
                    <div class="card-body">
                        <h5 style="color:#1A237E;">{{ $question->question }}</h5>
                        <div class="row mt-3">
                            @foreach(['A'=>$question->option_a,'B'=>$question->option_b,'C'=>$question->option_c,'D'=>$question->option_d] as $key=>$val)
                            <div class="col-md-6 mb-2">
                                <span class="badge {{ $key == $question->correct_answer ? 'badge-success' : 'badge-secondary' }}" style="font-size:13px;padding:6px 10px;">
                                    {{ $key }}: {{ $val }}
                                    @if($key == $question->correct_answer) &#10003; Correct @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @if($question->explanation)
                        <div class="alert alert-info mt-3 mb-0">
                            <strong>Explanation:</strong> {{ $question->explanation }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-6">
                        <div class="card text-center" style="background:#E8F5E9;">
                            <div class="card-body py-3">
                                <h3 style="color:#2E7D32;margin:0;">{{ $stats['total'] }}</h3>
                                <small>Total Attempts</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card text-center" style="background:#E3F2FD;">
                            <div class="card-body py-3">
                                <h3 style="color:#1565C0;margin:0;">{{ $stats['correct'] }}</h3>
                                <small>Correct</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="card text-center" style="background:#FFF3E0;">
                            <div class="card-body py-3">
                                <h3 style="color:#E65100;margin:0;">
                                    {{ $stats['total'] > 0 ? round(($stats['correct']/$stats['total'])*100) : 0 }}%
                                </h3>
                                <small>Accuracy</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attempts Table --}}
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-border table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Selected Answer</th>
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
                                <td>
                                    <span class="badge badge-secondary" style="font-size:13px;">
                                        {{ $a->selected_answer }}:
                                        @php
                                            $opts = ['A'=>$question->option_a,'B'=>$question->option_b,'C'=>$question->option_c,'D'=>$question->option_d];
                                        @endphp
                                        {{ $opts[$a->selected_answer] ?? '' }}
                                    </span>
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
                                <td colspan="6" class="text-center text-muted">No attempts yet.</td>
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
