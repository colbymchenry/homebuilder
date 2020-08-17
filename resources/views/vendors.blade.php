@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col">
                            <h3 style="padding-top: 0.25em;">Vendors</h3>
                        </div>
                        <div class="col" style="text-align: right;">
                            <button type="button" id="show-new-vendor-btn" class="btn btn-primary" data-toggle="modal" data-target="#new_vendor_modal"></button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container" id="projects-div">
                        @foreach(Vendor::get() as $vendor)
                            <div class="row p-3">
                                <h4><a href="javascript:show_vendor('{{ $vendor->id }}');">{{ $vendor->name }}</a></h4>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="new_vendor_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New vendor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="hide-new-vendor-btn">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row">
                    <form method="POST" action="/create-vendor" style="width: 100%;">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="name" type="text" class="form-control" name="name" placeholder="Name" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="email" type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="phone-number" type="text" class="form-control" name="phone-number" placeholder="Telephone" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <input id="address" type="text" class="form-control" name="address" placeholder="Address">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea id="description" class="form-control" name="description" placeholder="Description"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-0 p-2 float-right">
                            <button type="submit" class="btn btn-primary float-right">
                                Create
                            </button>
                        </div>
                    </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    function show_vendor(id) {
        $.ajax({
            url: "/vendor",
            type: 'GET',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
        }).done(function (msg) {
            if(msg['icon'] !== undefined) {
                Swal.fire({
                    icon: msg['icon'],
                    text: msg['msg']
                });
            } else {
                console.log(msg);
            }
        });
    }
</script>
@endsection