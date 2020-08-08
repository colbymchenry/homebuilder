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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" placeholder="Name..."></input>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    function new_template() {
        $('#show-new-template-btn').click();
    }
</script>
@endsection