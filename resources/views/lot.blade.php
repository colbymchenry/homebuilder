@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col-6">
                            <h5 style="padding-top: 0.25em;">{{ $project->name }} Lot #{{ $lot->number }}</h5>
                        </div>
                        <div class="col-6" style="text-align: right;">
                            @if($lot->plan !== null && HousePlan::where('id', $lot->plan)->exists())
                                <a class="btn btn-sm btn-primary" href="/house-plan-builder?id={{ $lot->plan }}&lot={{ $lot->id }}&project={{ $project->id }}" id="spec_build_btn">Build Out</a>
                            @else
                                <a class="btn btn-sm btn-primary disabled" href="#" id="spec_build_btn">Build Out</a>    
                            @endif
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_lot();"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Plan:</h4>
                            </div>
                            <div class="col-sm-12">
                                <select class="selectpicker" id="plan_selection" data-toggle="select">
                                    @if($lot->plan !== null && HousePlan::where('id', $lot->plan)->exists())
                                        <option value="" data-id="-1">Select...</option>
                                        <option value="" data-id="{{ $lot->plan }}" selected>{{ HousePlan::where('id', $lot->plan)->first()->name }}</option>
                                    @else
                                        <option value="" data-id="-1" selected>Select...</option>
                                    @endif

                                    @foreach(HousePlan::get() as $plan)
                                        @if($lot->plan !== $plan->id)
                                            <option data-id="{{ $plan->id }}">{{ $plan->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="pt-3">Address:</h4>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <form class="w-100" action="/lot-save-address" method="POST">
                                        @csrf
                                        <input id="lot_id" name="lot_id" type="text" value="{{ $lot->id }}" class="form-control hidden" hidden>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input id="address" type="text" class="form-control" name="address" value="{{ $lot->address }}">
                                            </div>
                                        </div>
                                        <div class="row pt-2">
                                            <div class="col-sm-12" style="text-align: right;">
                                                <button type="submit" class="btn btn-primary" style="text-align: right;">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                      
                            </div>
                        </div>
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
                        <div class="col-6">
                            <h5 style="padding-top: 0.25em;">Schedule</h5>
                        </div>
                        <div class="col-6" style="text-align: right;">
                            <button type="button" id="show-template-btn" class="btn btn-sm btn-success" data-toggle="modal" data-target="#template_modal" clic>Template</button>
                            <button type="submit" class="btn btn-sm btn-primary" onclick="delete_lot();">+</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container">
                        @foreach(Task::where('relational_table', 'lots')->where('relational_id', $lot->id)->get() as $task)
                            <div class="row">
                                <div class="col-6">
                                    <h5><a href="/task?id={{ $task->id }}">{{ $task->name }}</a></h5>
                                </div>
                                <div class="col-6">
                                    <div class="btn-group">
                                        <button type="button" id="#task-{{ $task->id }}" class="btn btn-sm {{ ($task->status == 'completed' ? 'btn-success' : ($task->status == 'in-progress' ? 'btn-warning' : ($task->status == 'not-started' ? 'btn-danger' : 'btn-danger'))) }} dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ ucwords(str_replace('-', ' ', $task->status)) }}</button>
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

<!-- Modal -->
<div class="modal fade" id="template_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Template</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="hide-new-order-btn">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container" id="order_div">
            <select class="selectpicker w-75" id="template_selection" data-toggle="select">
                @foreach(TaskTemplate::get() as $template)
                    <option data-id="{{ $template->id }}">{{ $template->name }}</option>
                @endforeach
            </select>
        </div>
        <br />
        <div class="container">
            <div class="row">
                <button type="button" class="btn btn-success float-right" onclick="load_template();">Load Template</button>
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

    function load_template() {
        var obj = $('#template_selection');
        var template_id = obj.find(":selected").data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! It will remove any current tasks!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "/load-template",
                    type: 'POST',
                    data: {
                        lot_id: "{{ $lot->id }}",
                        template_id: template_id, 
                        _token: '{{ csrf_token() }}'
                    },
                }).done(function (msg) {
                    Swal.fire({
                        icon: msg['icon'],
                        text: msg['msg']
                    });
                }, function(){
                    history.go(0);
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

    $(document).ready(function() {
        $("#plan_selection").on('change', function(e) {
            var id = $(this).find(':selected').data('id');
            var href = "&lot={{ $lot->id }}&project={{ $project->id }}";
             $.ajax({
                url: "/set-lot-plan",
                type: 'POST',
                data: {
                    id: "{{ $lot->id }}",
                    plan_id: id,
                    _token: '{{ csrf_token() }}'
                },
            }).done(function (msg) {
                if(msg['icon'] != 'success') {
                    Swal.fire({
                        icon: msg['icon'],
                        text: msg['msg']
                    });
                } else {
                    if(id !== -1) {
                        $("#spec_build_btn").removeClass('disabled');
                        $("#spec_build_btn").prop('href', `/house-plan-builder?id=${id}${href}`);
                    } else {
                        $("#spec_build_btn").addClass('disabled');
                        $("#spec_build_btn").prop('href', '#');
                    }
                }
            });
        });
       
    });
</script>
@endsection