@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col">
                            <h5 style="padding-top: 0.25em;">Make Admin</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container" id="lots-div">
                        @foreach(User::orderBy('name', 'ASC')->get() as $user)
                            <div class="row p-3">
                                <h5><a href="javascript:make_admin('{{ $user->id }}');">{{ $user->name }}</a></h5>
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

    async function make_admin(id) {
        $.ajax({
            url: "/assign-roles",
            type: 'POST',
            data: {
                user_id: id,
                trade: 'false',
                agent: 'false',
                admin: 'true', 
                _token: '{{ csrf_token() }}'
            },
        }).done(function (msg) {
            Swal.fire({
                icon: msg['icon'],
                text: msg['msg']
            });
        });
    }
</script>
@endsection