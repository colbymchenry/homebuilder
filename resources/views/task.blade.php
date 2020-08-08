@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col-sm">
                            <p>{{ $task->name }}</p>
                        </div>
                        <div class="col-sm" style="text-align: right;">
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_task();">Delete</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container">
                        <div id="notes-div">
                            @foreach(Note::where('relational_table', 'tasks')->where('relational_id', $task->id)->orderBy('id', 'DESC')->get() as $note)
                                <div class="row p-3">
                                    <div class="col-xs">
                                    <small>{{ $note->getEST() }}</small>
                                    </div>
                                    <div class="col-sm">
                                        <p>{{ $note->text }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row p-3">
                            <div class="container">
                                <div class="row">
                                    <textarea class="form-control" id="new_note" placeholder="New note..."></textarea>
                                </div>
                                <div class="row float-right pt-3">
                                    <button type="button" class="btn btn-sm btn-success float-right" onclick="create_note();">+ Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br />
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
                            @foreach(File::where('relational_id', $task->id)->where('relational_table', 'tasks')->get() as $file)
                            <div class="col-sm">
                                <a href="{{ $file->getURL() }}">{{ $file->name }}</a>
                            </div>
                            <div class="col-sm float-right">
                                <a href="{{ $file->getURL() }}" class="button float-right" download="{{ $file->name }}"><i class="fa fa-download"></i></a>
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
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    function create_note() {
        $.ajax({
            url: "/note-create",
            type: 'POST',
            data: {
                relational_table: 'tasks',
                relational_id: '{{ $task->id }}',
                text: $('#new_note').val(),
                _token: '{{ csrf_token() }}'
            },
        }).done(function (msg) {
            if(msg['icon'] == 'success') {
                var html = `
                    <div class="row p-3">
                        <div class="col-xs">
                            <small>${msg['timestamp']}</small>
                        </div>
                        <div class="col-sm">
                            <p>${$('#new_note').val()}</p>
                        </div>
                    </div>
                `;
                $("#notes-div").prepend(html);
                $('#new_note').val("");
            } else {
                Swal.fire({
                    icon: msg['icon'],
                    text: msg['msg']
                });
            }
        });
    }

    $("#file-1").fileinput({
        theme: 'fas',
        uploadUrl: "/file-upload",
        uploadExtraData: function() {
            return {
                table: 'tasks',
                relational_id: "{{ $task->id }}",
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