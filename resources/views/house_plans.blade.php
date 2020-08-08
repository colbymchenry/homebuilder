@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header container">
                    <div class="row">
                        <div class="col-sm">
                            <h5 style="padding-top: 0.25em;">House Plans</h5>
                        </div>
                        <div class="col-sm" style="text-align: right;">
                            <button type="submit" class="btn btn-sm btn-primary" onclick="new_house_plan();">+ New</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container" id="plans-div">
                        @foreach(HousePlan::get() as $house_plan)
                            <div class="row p-3">
                                <h5><a href="/house-plan?id={{ $house_plan->id }}">{{ $house_plan->name }}</a></h5>
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

    async function new_house_plan() {

        const { value: name } = await Swal.fire({
            title: 'Name of house plan:',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'on'
            },
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
                url: "/create-house-plan",
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
                        <h5><a href="/house-plan?id=${msg['id']}">${name}</a></h5>
                    </div>
                `;

                $('#plans-div').append(html);
            }
            });
        }

    }
</script>
@endsection