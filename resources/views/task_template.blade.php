@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col-sm">
                            <div class="row">
                                <div class="col-xs-2">
                                    <h3 style="padding-top: 0.25em;padding-left: 1em;" id="template_name">{{ $template->name }}</h3>
                                </div>
                                <div class="col-xs-2 pl-1 pt-1">
                                    <a href="javascript:rename_template()"><i class="far fa-edit"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm" style="text-align: right;">
                            <div class="float-right">
                                <button type="submit" class="btn btn-sm btn-primary" onclick="save_template();"><i class="ni ni-check-bold"></i></button>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="delete_template();"><i class="fas fa-trash-alt"></i></button>
                            </div>
                            <!-- <button type="button" class="btn btn-sm btn-danger" onclick="delete_template();"><i class="fas fa-trash-alt"></i></button> -->
                          </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container all-slides" id="templates-div">
                        @foreach($template->getTasks() as $task)
                        <div class="row pt-3 slide" id="task-{{ $task->id }}" data-id="{{ $task->id }}">
                          <div class="col-2 pt-2">
                              <div class="row">
                                <div class="col-xs-2">
                                  <a href="javascript:move_up('{{ $task->id }}');" class="float-left"><i class="fas fa-arrow-up"></i></a>
                                </div>
                                <div class="col-xs-2">
                                  <a href="javascript:move_down('{{ $task->id }}');" class="float-right"><i class="fas fa-arrow-down"></i></a>
                                </div>
                              </div>
                          </div>
                          <div class="col-5">
                            <input type="text" class="form-control" value="{{ $task->name }}" id="task_name_{{ $task->id }}"></input>
                          </div>
                          <div class="col-3">
                            <input type="number" class="form-control" value="{{ $task->alloted_days }}" id="task_days_{{ $task->id }}"></input>
                          </div>
                          <div class="col-1 pt-2">
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_task('{{ $task->id }}');"><i class="fas fa-trash-alt"></i></button>
                          </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="container pt-6">
                      <div class="row">
                        <div class="col-8">
                          <input type="text" class="form-control" placeholder="Name..." id="task_name"></input>
                        </div>
                        <div class="col-2">
                          <input type="number" class="form-control" placeholder="Days..." id="task_days"></input>
                        </div>
                        <div class="col-2" style="text-align: center;">
                          <button type="button" class="btn btn-secondary" onclick="add_task()">Add</button>
                        </div>
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

  function delete_template() {
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
              url: "/delete-template",
              type: 'POST',
              data: {
                  id: '{{ $template->id }}',
                  _token: '{{ csrf_token() }}'
              },
          }).done(function (msg) {
            if(msg['icon'] == 'success') {
              window.location.href = "/task-templates";
            } else {
              Swal.fire({
                  icon: msg['icon'],
                  text: msg['msg']
              });
            }
          });
      }
    });
  }

  function save_template() {
    var div = document.getElementById('templates-div');
    var divs = div.getElementsByTagName('div');
    var divArray = [];
    var namesAndDays = [];
    for (var i = 0; i < divs.length; i += 1) {
        if(divs[i].id.includes('task-')) {
          var id = divs[i].id.split('-')[1];
            divArray.push(id);
            namesAndDays[id] = $(`#task_name_${id}`).val() + ":" + $(`#task_days_${id}`).val();
        }
    }

    $.ajax({
        url: "/save-template",
        type: 'POST',
        data: {
            order: divArray,
            namesAndDays: namesAndDays,
            template_id: '{{ $template->id }}',
            _token: '{{ csrf_token() }}'
        },
    }).done(function (msg) {
        if(msg['icon'] !== 'success') {
            Swal.fire({
                icon: msg['icon'],
                text: msg['msg']
            });
        } else {
            history.go(0);
        }
    });
  }

  function move_up(id) {
        var obj = $(`#task-${id}`);
        var above = obj.prev('div');
        var beneath = obj.next('div');

        if(above.length != 0) {
            obj.insertBefore(above);
        }
    }

    function move_down(id) {
        var obj = $(`#task-${id}`);
        var above = obj.prev('div');
        var beneath = obj.next('div');

        if(beneath.length != 0) {
            obj.insertAfter(beneath);
        }
    }

    async function rename_template() {

      const { value: name } = await Swal.fire({
          title: 'Name of template:',
          input: 'text',
          inputAttributes: {
              autocapitalize: 'on'
          },
          inputPlaceholder: 'Base',
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
              url: "/rename-task-template",
              type: 'POST',
              data: {
                  name: name,
                  id: '{{ $template->id }}',
                  _token: '{{ csrf_token() }}'
              },
          }).done(function (msg) {
              if(msg['icon'] !== 'success') {
                  Swal.fire({
                      icon: msg['icon'],
                      text: msg['msg']
                  });
              } else {
                  $('#template_name').text(name);
              }
          });
      }

    }

  function delete_task(id) {
    $.ajax({
        url: "/delete-template-task",
        type: 'POST',
        data: {
            id: id,
            _token: '{{ csrf_token() }}'
        },
    }).done(function (msg) {
      if(msg['icon'] == 'success') {
        $(`#task-${id}`).remove();
      } else {
        Swal.fire({
            icon: msg['icon'],
            text: msg['msg']
        });
      }
    });
  }

  function add_task() {
      var name = $("#task_name").val();
      var days = $('#task_days').val();


      if (!name.trim()) {
        $('#task_name').addClass('is-invalid');
        return;
      }

      if (!days.trim()) {
        $('#task_days').addClass('is-invalid');
        return;
      }

    $.ajax({
        url: "/create-template-task",
        type: 'POST',
        data: {
            template_id: '{{ $template->id }}',
            name: name,
            days: days,
            _token: '{{ csrf_token() }}'
        },
    }).done(function (msg) {
      if(msg['icon'] == 'success') {
        $('#task_name').val("");
        $('#task_days').val("");

        var html = `
          <div class="row pt-3 slide" id="task-${msg['id']}">
            <div class="col-1 pt-2">
              <i class="fas fa-bars"></i>
            </div>
            <div class="col-8">
              <input type="text" class="form-control" value="${name}"></input>
            </div>
            <div class="col-2">
              <input type="number" class="form-control" value="${days}"></input>
            </div>
            <div class="col-1 pt-2">
              <button type="button" class="btn btn-sm btn-danger" onclick="delete_task(${msg['id']});"><i class="fas fa-trash-alt"></i></button>
            </div>
          </div>
          `;

          $("#templates-div").append(html);
      } else {
        Swal.fire({
            icon: msg['icon'],
            text: msg['msg']
        });
      }
    });
  }

 
</script>
@endsection