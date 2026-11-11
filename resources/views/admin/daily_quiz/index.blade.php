@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-5">
                <h4 class="page-title">🧠 Daily Quiz — BrainFit</h4>
            </div>
            <div class="col-sm-4 col-7 text-right m-b-30">
                <a href="{{ route('admin.daily-quiz.import-form') }}" class="btn btn-success btn-rounded float-right mr-2">
                    <i class="fa fa-file-excel-o"></i> Import Excel
                </a>
                <a href="{{ route('admin.daily-quiz.create') }}" class="btn btn-primary btn-rounded float-right">
                    <i class="fa fa-plus"></i> Add Quiz Batch
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-border table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Starts At</th>
                                <th>Ends At</th>
                                <th>Duration</th>
                                <th>Questions</th>
                                <th>Status</th>
                                <th>Attempts</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                            @php
                                $now = now();
                                $isLive = $batch->is_active && $now->between($batch->starts_at, $batch->ends_at);
                                $isUpcoming = $batch->is_active && $now->lt($batch->starts_at);
                                $isPast = $now->gt($batch->ends_at);
                                $diffMins = $batch->starts_at->diffInMinutes($batch->ends_at);
                                $duration = $diffMins >= 60
                                    ? floor($diffMins/60).'h '.($diffMins%60 > 0 ? ($diffMins%60).'m' : '')
                                    : $diffMins.'m';
                                $totalAttempts = \App\Models\DailyQuizAttempt::whereIn('question_id', $batch->questions->pluck('id'))->count();
                            @endphp
                            <tr>
                                <td>{{ $batch->id }}</td>
                                <td>
                                    <strong>{{ $batch->title ?: 'Batch #'.$batch->id }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $batch->starts_at->format('d M Y') }}</strong><br>
                                    <small class="text-muted">{{ $batch->starts_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $batch->ends_at->format('d M Y') }}</strong><br>
                                    <small class="text-muted">{{ $batch->ends_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ trim($duration) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-primary" style="font-size:13px;">
                                        {{ $batch->questions_count }} Qs
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.daily-quiz.toggle', $batch->id) }}">
                                        @if($isLive)
                                            <span class="custom-badge status-green">🟢 Live</span>
                                        @elseif($isUpcoming)
                                            <span class="custom-badge status-blue">⏳ Upcoming</span>
                                        @elseif($isPast)
                                            <span class="custom-badge status-grey">Past</span>
                                        @else
                                            <span class="custom-badge status-red">Inactive</span>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.daily-quiz.results', $batch->id) }}" class="btn btn-xs btn-info">
                                        {{ $totalAttempts }} attempts
                                    </a>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('admin.daily-quiz.edit', $batch->id) }}">
                                                <i class="fa fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.daily-quiz.results', $batch->id) }}">
                                                <i class="fa fa-bar-chart m-r-5"></i> Results
                                            </a>
                                            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('admin.daily-quiz.destroy', $batch->id) }}">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No quiz batches added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box-footer clearfix mt-3">{{ $batches->links() }}</div>
    </div>
</div>

<div id="delete_modal" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{ asset('admin/assets/img/sent.png') }}" alt="" width="50" height="46">
                <h3>Delete this quiz batch?</h3>
                <p class="text-muted">All questions in this batch will also be deleted.</p>
                <div class="m-t-20">
                    <a href="#" class="btn btn-white" data-dismiss="modal">Cancel</a>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-target="#delete_modal"]').forEach(el => {
    el.addEventListener('click', function () {
        document.getElementById('deleteForm').action = this.dataset.url;
    });
});
</script>
@endsection
