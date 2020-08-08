@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col-sm">
                            <h5 style="padding-top: 0.25em;">{{ $project->name }} Lot #{{ $lot->number }}</h5>
                        </div>
                        <div class="col-sm" style="text-align: right;">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="delete_lot();">Delete Lot</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col-sm">
                            <h5 style="padding-top: 0.25em;">Schedule</h5>
                        </div>
                        <div class="col-sm" style="text-align: right;">
                            <button type="submit" class="btn btn-sm btn-primary" onclick="delete_lot();">Add Item</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container">
                        @foreach(Task::where('relational_table', 'lots')->where('relational_id', $lot->id)->get() as $task)
                            <div class="row">
                                <div class="col-sm">
                                    <h5><a href="/task?id={{ $task->id }}">{{ $task->name }}</a></h5>
                                </div>
                                <div class="col-sm">
                                    <div class="btn-group">
                                        <button type="button" id="#task-{{ $task->id }}" class="btn {{ ($task->status == 'completed' ? 'btn-success' : ($task->status == 'in-progress' ? 'btn-warning' : ($task->status == 'not-started' ? 'btn-danger' : 'btn-danger'))) }} dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ ucwords(str_replace('-', ' ', $task->status)) }}</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" onclick="set_status('{{ $task->id }}', 'not-started')">Not Started</a>
                                            <a class="dropdown-item" onclick="set_status('{{ $task->id }}', 'in-progress')">In Progress</a>
                                            <a class="dropdown-item" onclick="set_status('{{ $task->id }}', 'completed')">Completed</a>
                                        </div>
                                    </div><!-- /btn-group -->
                                </div>
                            </div>
                            <hr />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col-sm">
                            <h5 style="padding-top: 0.25em;">Files</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container">
                        <div class="row justify-content-md-center">
                            @foreach(File::where('relational_id', $lot->id)->where('relational_table', 'lots')->get() as $file)
                            <div class="col-sm">
                                <a href="{{ $file->getURL() }}">{{ $file->name }}</a>
                            </div>
                            <div class="col-sm float-right">
                                <a href="{{ $file->getURL() }}" class="button float-right" download><i class="fa fa-download"></i></a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <br />
                    <div class="container">
                        @include('file_upload_html')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    function delete_lot() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "/delete-lot",
                    type: 'POST',
                    data: {
                        id: "{{ $lot->id }}",
                        _token: '{{ csrf_token() }}'
                    },
                }).done(function (msg) {
                    Swal.fire({
                        icon: msg['icon'],
                        text: msg['msg']
                    });
                }, function(){
                    window.location.href = "/project?id={{ $project->id }}";
                });
            }
        });
    }

    function set_status(task_id, status) {
        $.ajax({
            url: "/task-status-update",
            type: 'POST',
            data: {
                id: task_id,
                status: status,
                _token: '{{ csrf_token() }}'
            },
        }).done(function (msg) {
            if(msg['icon'] == 'success') {
                $(document.getElementById(`#task-${task_id}`)).prop("class", "btn " + (status == "completed" ? "btn-success" : status == "in-progress" ? "btn-warning" : status == "not-started" ? "btn-danger" : "btn-danger") + " dropdown-toggle");
                $(document.getElementById(`#task-${task_id}`)).text((status == "completed" ? "Completed" : status == "in-progress" ? "In Progress" : status == "not-started" ? "Not Started" : "btn-danger"))
            } else {
                Swal.fire({
                    icon: msg['icon'],
                    text: msg['msg']
                });
            }
        });
    }
</script>

<script type="text/javascript">
    $("#file-1").fileinput({
        theme: 'fas',
        uploadUrl: "/file-upload",
        uploadExtraData: function() {
            return {
                table: 'lots',
                relational_id: "{{ $lot->id }}",
                _token: "{{ @csrf_token() }}",
            };
        },
        allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'zip', 'jpeg'],
        overwriteInitial: true,
        maxFileSize:200000,
        maxFilesNum: 10
    });
</script>
@endsection