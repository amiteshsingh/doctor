@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="page-title">{{ isset($batch) ? 'Edit Quiz Batch' : 'Add Quiz Batch' }}</h4>
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

        <form method="POST" action="{{ isset($batch) ? route('admin.daily-quiz.update', $batch->id) : route('admin.daily-quiz.store') }}">
            @csrf
            @if(isset($batch)) @method('PUT') @endif

            {{-- Batch Schedule --}}
            <div class="card mb-3">
                <div class="card-body" style="background:#E8EAF6;border-radius:8px;">
                    <h5 style="color:#1A237E;"><i class="fa fa-clock-o"></i> Quiz Schedule</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Batch Title <small class="text-muted">(optional)</small></label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Week 1 Health Quiz"
                                    value="{{ old('title', $batch->title ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quiz Starts At <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="starts_at" class="form-control" required
                                    value="{{ old('starts_at', isset($batch) ? $batch->starts_at->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted">Quiz will be available from this time</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quiz Ends At <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="ends_at" class="form-control" required
                                    value="{{ old('ends_at', isset($batch) ? $batch->ends_at->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted">Quiz will close after this time</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Questions --}}
            <div id="questions-container">
                @php
                    $existingQuestions = isset($batch) ? $batch->questions->toArray() : [null];
                @endphp

                @foreach($existingQuestions as $qi => $eq)
                <div class="question-block card mb-3" data-index="{{ $qi }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 style="color:#1A237E;margin:0;">
                                <i class="fa fa-question-circle"></i>
                                Question <span class="q-num">{{ $qi + 1 }}</span>
                            </h6>
                            <button type="button" class="btn btn-xs btn-danger remove-question" style="display:{{ $qi === 0 ? 'none' : 'inline-block' }};">
                                <i class="fa fa-trash"></i> Remove
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Question <span class="text-danger">*</span></label>
                                    <textarea name="questions[{{ $qi }}][question]" class="form-control" rows="2" required maxlength="500">{{ old("questions.$qi.question", $eq['question'] ?? '') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select name="questions[{{ $qi }}][category]" class="form-control" required>
                                        @foreach(['General','Health','Science','History','Geography','Sports','Technology','Math'] as $cat)
                                        <option value="{{ $cat }}" {{ old("questions.$qi.category", $eq['category'] ?? 'General') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Time Limit (sec) <span class="text-danger">*</span></label>
                                    <input type="number" name="questions[{{ $qi }}][time_limit]" class="form-control"
                                        min="10" max="300" required
                                        value="{{ old("questions.$qi.time_limit", $eq['time_limit'] ?? 30) }}">
                                    <small class="text-muted">10–300s</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @foreach(['a','b','c','d'] as $opt)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><span class="badge badge-secondary">{{ strtoupper($opt) }}</span> Option {{ strtoupper($opt) }} <span class="text-danger">*</span></label>
                                    <input type="text" name="questions[{{ $qi }}][option_{{ $opt }}]" class="form-control" required maxlength="255"
                                        value="{{ old("questions.$qi.option_$opt", $eq['option_'.$opt] ?? '') }}">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Correct Answer <span class="text-danger">*</span></label>
                                    <select name="questions[{{ $qi }}][correct_answer]" class="form-control" required>
                                        @foreach(['A','B','C','D'] as $opt)
                                        <option value="{{ $opt }}" {{ old("questions.$qi.correct_answer", $eq['correct_answer'] ?? '') == $opt ? 'selected' : '' }}>Option {{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Explanation <small class="text-muted">(shown after answering)</small></label>
                                    <textarea name="questions[{{ $qi }}][explanation]" class="form-control" rows="1" maxlength="1000">{{ old("questions.$qi.explanation", $eq['explanation'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mb-3">
                <button type="button" id="add-more-btn" class="btn btn-outline-primary">
                    <i class="fa fa-plus"></i> Add More Question
                </button>
            </div>

            <div class="m-t-20">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa fa-save"></i> {{ isset($batch) ? 'Update Batch' : 'Save All Questions' }}
                </button>
                <a href="{{ route('admin.daily-quiz.index') }}" class="btn btn-default ml-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
let qIndex = {{ isset($batch) ? $batch->questions->count() - 1 : 0 }};

const questionTemplate = (index) => `
<div class="question-block card mb-3" data-index="${index}">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 style="color:#1A237E;margin:0;"><i class="fa fa-question-circle"></i> Question <span class="q-num">${index + 1}</span></h6>
            <button type="button" class="btn btn-xs btn-danger remove-question"><i class="fa fa-trash"></i> Remove</button>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Question <span class="text-danger">*</span></label>
                    <textarea name="questions[${index}][question]" class="form-control" rows="2" required maxlength="500"></textarea>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Category <span class="text-danger">*</span></label>
                    <select name="questions[${index}][category]" class="form-control" required>
                        ${['General','Health','Science','History','Geography','Sports','Technology','Math'].map(c => `<option value="${c}">${c}</option>`).join('')}
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Time Limit (sec) <span class="text-danger">*</span></label>
                    <input type="number" name="questions[${index}][time_limit]" class="form-control" min="10" max="300" value="30" required>
                    <small class="text-muted">10–300s</small>
                </div>
            </div>
        </div>
        <div class="row">
            ${['a','b','c','d'].map(opt => `
            <div class="col-md-3">
                <div class="form-group">
                    <label><span class="badge badge-secondary">${opt.toUpperCase()}</span> Option ${opt.toUpperCase()} <span class="text-danger">*</span></label>
                    <input type="text" name="questions[${index}][option_${opt}]" class="form-control" required maxlength="255">
                </div>
            </div>`).join('')}
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Correct Answer <span class="text-danger">*</span></label>
                    <select name="questions[${index}][correct_answer]" class="form-control" required>
                        ${['A','B','C','D'].map(o => `<option value="${o}">Option ${o}</option>`).join('')}
                    </select>
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label>Explanation <small class="text-muted">(shown after answering)</small></label>
                    <textarea name="questions[${index}][explanation]" class="form-control" rows="1" maxlength="1000"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>`;

document.getElementById('add-more-btn').addEventListener('click', function () {
    qIndex++;
    const container = document.getElementById('questions-container');
    container.insertAdjacentHTML('beforeend', questionTemplate(qIndex));
    renumberQuestions();
    bindRemoveButtons();
});

function bindRemoveButtons() {
    document.querySelectorAll('.remove-question').forEach(btn => {
        btn.onclick = function () {
            this.closest('.question-block').remove();
            renumberQuestions();
        };
    });
}

function renumberQuestions() {
    document.querySelectorAll('.question-block').forEach((block, i) => {
        block.querySelector('.q-num').textContent = i + 1;
    });
}

bindRemoveButtons();
</script>
@endsection
