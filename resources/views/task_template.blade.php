@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col-sm">
                            <h5 style="padding-top: 0.25em;">Template - {{ $template->name }}</h5>
                        </div>
                        <div class="col-sm" style="text-align: right;">
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_template();"><i class="fas fa-trash-alt"></i></button>
                          </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container all-slides" id="templates-div">
                        @foreach($template->getTasks() as $task)
                        <div class="row pt-3 slide" id="task-{{ $task->id }}" data-id="{{ $task->id }}">
                          <div class="col-1 pt-2">
                            <i class="fas fa-bars"></i>
                          </div>
                          <div class="col-8">
                            <input type="text" class="form-control" value="{{ $task->name }}"></input>
                          </div>
                          <div class="col-2">
                            <input type="number" class="form-control" value="{{ $task->alloted_days }}"></input>
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
    $(document).ready(function() {
      $(".all-slides").sortable({
        axis: "y",
        revert: true,
        scroll: false,
        placeholder: "sortable-placeholder",
        cursor: "move",
        change: function(event, ui) {
            var order_number = ui.placeholder.index();
            var id = ui.item.data('id');
            $.ajax({
                url: "/sort-template-task",
                type: 'POST',
                data: {
                    id: id,
                    order: order_number,
                    _token: '{{ csrf_token() }}'
                },
            }).done(function (msg) {
              if(msg['icon'] != 'success') {
                Swal.fire({
                    icon: msg['icon'],
                    text: msg['msg']
                });
              }
            });
        }
      });
    });

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