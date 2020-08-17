@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col">
                            <h5 style="padding-top: 0.25em;">{{ $project->name }}</h5>
                        </div>
                        <div class="col" style="text-align: right;">
                            <button type="submit" class="btn btn-sm btn-primary" onclick="new_lot();">Add Lot</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container" id="lots-div">
                        @foreach(Lot::where('project', $project->id)->orderBy('number', 'DESC')->get() as $lot)
                            <div class="row p-3">
                                <h5><a href="/lot?id={{ $lot->id }}">Lot #{{ $lot->number }}</a></h5>
                            </div>
                        @endforeach
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
                            @foreach(File::where('relational_id', $project->id)->where('relational_table', 'projects')->get() as $file)
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
@endsection

@section('scripts')
<script type="text/javascript">

    async function new_lot() {
        const { value: lot_number } = await Swal.fire({
            title: 'Lot #:',
            input: 'text',
            inputPlaceholder: '1',
            showCancelButton: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    resolve();
                });
            }
        });

        if(lot_number) {
            $.ajax({
                url: "/create-lot",
                type: 'POST',
                data: {
                    project: "{{ $project->id }}",
                    number: lot_number,
                    _token: '{{ csrf_token() }}'
                },
            }).done(function (msg) {
                if(msg['icon'] !== 'success') {
                    Swal.fire({
                        icon: msg['icon'],
                        text: msg['msg']
                    });
                } else {
                    var html = `
                        <div class="row p-3">
                            <h5><a href="/lot?id=${msg['id']}">Lot #${lot_number}</a></h5>
                        </div>
                    `;

                    $('#lots-div').append(html);
                }
            });
        }

    }
</script>

<script type="text/javascript">
    $("#file-1").fileinput({
        theme: 'fas',
        uploadUrl: "/file-upload",
        uploadExtraData: function() {
            return {
                table: 'projects',
                relational_id: "{{ $project->id }}",
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