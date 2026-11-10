@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="page-title">{{ isset($question) ? 'Edit Quiz Question' : 'Add Quiz Question' }}</h4>
            </div>
            <div class="col-sm-4 text-right m-b-30">
                <a href="{{ route('admin.daily-quiz.index') }}" class="btn btn-default btn-rounded float-right">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ isset($question) ? route('admin.daily-quiz.update', $question->id) : route('admin.daily-quiz.store') }}">
                            @csrf
                            @if(isset($question)) @method('PUT') @endif

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Question <span class="text-danger">*</span></label>
                                        <textarea name="question" class="form-control" rows="3" required maxlength="500">{{ old('question', $question->question ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Quiz Date <span class="text-danger">*</span></label>
                                        <input type="date" name="quiz_date" class="form-control" required
                                            value="{{ old('quiz_date', isset($question) ? $question->quiz_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Time Limit (sec) <span class="text-danger">*</span></label>
                                        <input type="number" name="time_limit" class="form-control" min="10" max="120"
                                            value="{{ old('time_limit', $question->time_limit ?? 30) }}" required>
                                        <small class="text-muted">10 to 120 seconds</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="badge badge-secondary">A</span> Option A <span class="text-danger">*</span></label>
                                        <input type="text" name="option_a" class="form-control" required maxlength="255"
                                            value="{{ old('option_a', $question->option_a ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="badge badge-secondary">B</span> Option B <span class="text-danger">*</span></label>
                                        <input type="text" name="option_b" class="form-control" required maxlength="255"
                                            value="{{ old('option_b', $question->option_b ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="badge badge-secondary">C</span> Option C <span class="text-danger">*</span></label>
                                        <input type="text" name="option_c" class="form-control" required maxlength="255"
                                            value="{{ old('option_c', $question->option_c ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><span class="badge badge-secondary">D</span> Option D <span class="text-danger">*</span></label>
                                        <input type="text" name="option_d" class="form-control" required maxlength="255"
                                            value="{{ old('option_d', $question->option_d ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Correct Answer <span class="text-danger">*</span></label>
                                        <select name="correct_answer" class="form-control" required>
                                            @foreach(['A','B','C','D'] as $opt)
                                            <option value="{{ $opt }}" {{ old('correct_answer', $question->correct_answer ?? '') == $opt ? 'selected' : '' }}>
                                                Option {{ $opt }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Category <span class="text-danger">*</span></label>
                                        <select name="category" class="form-control" required>
                                            @foreach(['General','Health','Science','History','Geography','Sports','Technology','Math'] as $cat)
                                            <option value="{{ $cat }}" {{ old('category', $question->category ?? 'General') == $cat ? 'selected' : '' }}>
                                                {{ $cat }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Explanation <small class="text-muted">(shown to user after answering)</small></label>
                                        <textarea name="explanation" class="form-control" rows="2" maxlength="1000">{{ old('explanation', $question->explanation ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="m-t-20">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> {{ isset($question) ? 'Update Question' : 'Save Question' }}
                                </button>
                                <a href="{{ route('admin.daily-quiz.index') }}" class="btn btn-default ml-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card" style="background:#1A237E;color:#fff;border-radius:16px;">
                    <div class="card-body text-center">
                        <div style="font-size:36px;">&#129504;</div>
                        <div style="font-size:13px;margin-top:8px;font-weight:700;">Daily Quiz</div>
                        <div style="font-size:11px;opacity:0.8;">BrainFit</div>
                        <hr style="border-color:rgba(255,255,255,0.3);">
                        <div style="font-size:11px;">Time: <strong id="prev_time">30s</strong></div>
                        <div style="font-size:11px;margin-top:4px;">Category: <strong id="prev_cat">General</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('[name="time_limit"]').addEventListener('input', function() {
    document.getElementById('prev_time').textContent = this.value + 's';
});
document.querySelector('[name="category"]').addEventListener('change', function() {
    document.getElementById('prev_cat').textContent = this.value;
});
</script>
@endsection
