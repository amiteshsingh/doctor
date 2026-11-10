@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-5">
                <h4 class="page-title">🧠 Daily Quiz — BrainFit</h4>
            </div>
            <div class="col-sm-4 col-7 text-right m-b-30">
                <a href="{{ route('admin.daily-quiz.create') }}" class="btn btn-primary btn-rounded float-right">
                    <i class="fa fa-plus"></i> Add Question
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
                                <th>Quiz Date</th>
                                <th>Question</th>
                                <th>Category</th>
                                <th>Time Limit</th>
                                <th>Correct Ans</th>
                                <th>Status</th>
                                <th>Attempts</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($questions as $q)
                            <tr>
                                <td>{{ $q->id }}</td>
                                <td>
                                    <strong>{{ $q->quiz_date->format('d M Y') }}</strong>
                                    @if($q->quiz_date->isToday())
                                        <span class="custom-badge status-green">Today</span>
                                    @elseif($q->quiz_date->isFuture())
                                        <span class="custom-badge status-blue">Upcoming</span>
                                    @else
                                        <span class="custom-badge status-grey">Past</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($q->question, 60) }}</td>
                                <td><span class="custom-badge status-blue">{{ $q->category }}</span></td>
                                <td>{{ $q->time_limit }}s</td>
                                <td>
                                    <span class="badge badge-success" style="font-size:14px;">
                                        {{ $q->correct_answer }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.daily-quiz.toggle', $q->id) }}">
                                        @if($q->is_active)
                                            <span class="custom-badge status-green">Active</span>
                                        @else
                                            <span class="custom-badge status-red">Inactive</span>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.daily-quiz.results', $q->id) }}" class="btn btn-xs btn-info">
                                        {{ $q->attempts()->count() }} attempts
                                    </a>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('admin.daily-quiz.edit', $q->id) }}">
                                                <i class="fa fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.daily-quiz.results', $q->id) }}">
                                                <i class="fa fa-bar-chart m-r-5"></i> Results
                                            </a>
                                            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#delete_modal" data-url="{{ route('admin.daily-quiz.destroy', $q->id) }}">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No quiz questions added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box-footer clearfix mt-3">
            {{ $questions->links() }}
        </div>
    </div>
</div>

<div id="delete_modal" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{ asset('admin/assets/img/sent.png') }}" alt="" width="50" height="46">
                <h3>Delete this quiz question?</h3>
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
