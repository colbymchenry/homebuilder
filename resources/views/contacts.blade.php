@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs" style="margin-bottom: -1.3em;" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="trades-tab" data-toggle="tab" href="#trades" role="tab" aria-controls="trades" aria-selected="true">Trades</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="agents-tab" data-toggle="tab" href="#agents" role="tab" aria-controls="agents" aria-selected="false">Agents</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="admins-tab" data-toggle="tab" href="#admins" role="tab" aria-controls="admins" aria-selected="false">Admins</a>
                        </li>
                        @if(Auth::user()->admin)
                        <li class="nav-item">
                            <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false">All</a>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active container" id="trades" role="tabpanel" aria-labelledby="trades-tab">
                            @foreach(User::where('trade', true)->orderBy('name', 'ASC')->get() as $trade)
                                <div class="row p-3">
                                    <h4><a href="#">{{ $trade->name }}</a></h4>
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane fade container" id="agents" role="tabpanel" aria-labelledby="agents-tab">
                            @foreach(User::where('agent', true)->orderBy('name', 'ASC')->get() as $agent)
                                <div class="row p-3">
                                    <h4><a href="#">{{ $agent->name }}</a></h4>
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane fade container" id="admins" role="tabpanel" aria-labelledby="admins-tab">
                            @foreach(User::where('admin', true)->orderBy('name', 'ASC')->get() as $admin)
                                <div class="row p-3">
                                    <h4><a href="#">{{ $admin->name }}</a></h4>
                                </div>
                            @endforeach
                        </div>
                        @if(Auth::user()->admin)
                        <div class="tab-pane fade container" id="all" role="tabpanel" aria-labelledby="all-tab">
                            @foreach(User::orderBy('name', 'ASC')->get() as $user)
                                <div class="row p-3">
                                    <h4><a href="javascript:assign_user('{{ $user->id }}', '{{ $user->trade }}', '{{ $user->agent }}', '{{ $user->admin }}')">{{ $user->name }}</a></h4>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    async function assign_user(id, is_trade, is_agent, is_admin) {
        console.log(is_trade);
        console.log(is_agent);
        console.log(is_admin);
        Swal.fire({
            title: 'Assign user as:',
            html: `
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="trade" ` + (is_trade == '1' ? 'checked' : '') + `>
                <label class="custom-control-label" for="trade">Trade</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="agent" ` + (is_agent == '1' ? 'checked' : '') + `>
                <label class="custom-control-label" for="agent">Agent</label>
            </div>
            <div class="custom-control custom-checkbox" style="padding-left: 2.3em;">
                <input type="checkbox" class="custom-control-input" id="admin" ` + (is_admin == '1' ? 'checked' : '') + `>
                <label class="custom-control-label" for="admin">Admin</label>
            </div>
            `,
            focusConfirm: false,
            preConfirm: () => {
                var trade = document.getElementById('trade').checked;
                var agent = document.getElementById('agent').checked;
                var admin = document.getElementById('admin').checked;

                $.ajax({
                    url: "/assign-roles",
                    type: 'POST',
                    data: {
                        user_id: id,
                        trade: trade,
                        agent: agent,
                        admin: admin, 
                        _token: '{{ csrf_token() }}'
                    },
                }).done(function (msg) {
                    Swal.fire({
                        icon: msg['icon'],
                        text: msg['msg']
                    });
                });
            }
        });

    }
</script>
@endsection