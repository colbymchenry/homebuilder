@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col-sm">
                            <h5 style="padding-top: 0.25em;">Task Templates</h5>
                        </div>
                        <div class="col-sm" style="text-align: right;">
                            <button type="button" class="btn btn-sm btn-primary" onclick="new_template();">+ New</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container" id="templates-div">
                        @foreach(TaskTemplate::get() as $template)
                            <div class="row p-3">
                                <h4><a href="/template?id={{ $template->id }}">{{ $template->name }}</a></h4>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="button" id="show-new-template-btn" class="btn btn-primary hidden" data-toggle="modal" data-target="#new_template_modal" hidden></button>

<!-- Modal -->
<div class="modal fade" id="new_template_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Template</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="hide-new-template-btn">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row">
            <div class="col">
              <input type="text" class="form-control" placeholder="Name of template..." id="template_name"></input>
            </div>
          </div>
          <div class="row pt-3">
            <div class="container" id="tasks_div">

            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="container">
          <div class="row">
            <div class="col">
              <input type="text" class="form-control" placeholder="Name..." id="task_name"></input>
            </div>
            <div class="col-md-4">
              <input type="number" class="form-control" placeholder="Days..." id="task_days"></input>
            </div>
          </div>
          <div class="row">
              <div class="container pt-3">
                <button type="button" class="btn btn-secondary float-right" onclick="add_task()">Add</button>
              </div>
          </div>
          <div class="row">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="save_template()">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    function new_template() {
        $('#tasks_div').empty();
        $('#template_name').val("");
        $('#show-new-template-btn').click();
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

      var html = `
        <div class="row pt-3">
          <div class="col">
            <input type="text" class="form-control" value="${name}"></input>
          </div>
          <div class="col-md-4">
            <input type="number" class="form-control" value="${days}"></input>
          </div>
        </div>
        `;

        $("#tasks_div").append(html);

        $("#task_name").val("");
        $("#task_days").val("");
        $("#task_name").removeClass('is-invalid');
        $("#task_days").removeClass('is-invalid');
    }

    function save_template() {
      var name = $("#template_name").val();

      if(!name.trim()) {
        $('#template_name').addClass('is-invalid');
        return;
      }

      $('#hide-new-template-btn').click();

      var found_key = false;
      var key = "";
      var value = "";

      var tasks = [];

      $("#tasks_div input").each(function(index) {
        if(!found_key) {
            key = $(this).val();
            found_key = true;
        } else {
            value = $(this).val();
            found_key = false;
            tasks.push(key + ":" + value);
        }
      });

      $.ajax({
          url: "/create-template",
          type: 'POST',
          dataType: "json",
          data: {
              name: name,
              tasks: tasks,
              _token: '{{ csrf_token() }}'
          },
      }).done(function (msg) {
          if(msg['icon'] == 'success') {
            history.go(0);
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