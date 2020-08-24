@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col">
                            <h3 style="padding-top: 0.25em;">Projects</h3>
                        </div>
                        <div class="col" style="text-align: right;">
                            <button type="submit" class="btn btn-sm btn-primary" onclick="new_project();">Create</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container" id="projects-div">
                        @foreach(Project::get() as $project)
                            <div class="row p-3">
                                <h4><a href="/project?id={{ $project->id }}">{{ $project->name }}</a></h4>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    async function new_project() {

        const { value: name } = await Swal.fire({
            title: 'Name of Project:',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'on'
            },
            inputPlaceholder: 'Fields Bridge',
            showCancelButton: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    resolve();
                });
            }
        });

        if(name) {
            Swal.showLoading();

            $.ajax({
                url: "/create-project",
                type: 'POST',
                data: {
                    name: name,
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
                            <h5><a href="/project?id=${msg['id']}'">${name}</a></h5>
                        </div>
                    `;

                    $("#projects-div").append(html);
                }
            });
        }

    }
</script>
@endsection