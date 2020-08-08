@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="padding-bottom: 2em;">
    <div class="col-md-8">
        <div class="col">
            <div class="float-left">
                <a class="btn btn-primary" href="/house-plan-builder?id={{ $house_plan->id }}">Builder</a>
            </div>
        </div>
        <div class="col">
            <div class="float-right">
                <button type="submit" class="btn btn-success" onclick="new_design_option()">+ Add Design Option</button>
            </div>
        </div>
</div>
    </div>
    <div id="design-options-div">
        @foreach(DesignOption::where('house_plan', $house_plan->id)->get() as $design_option)
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header container">
                            <div class="row">
                                <h3 style="padding-top: 0.25em;padding-left: 1em;">{{ $design_option->name }}</h3>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="container">
                                <div class="container" id="design_option_{{ $design_option->id }}">
                                    @foreach(PriceSheet::where('design_option', $design_option->id)->get() as $price_sheet)
                                        <div class="row">
                                            <div class="col-sm col-xs-12 p-3">
                                                <input id="price_sheet_name_{{ $price_sheet->id }}" type="text" class="form-control" value="{{ $price_sheet->name }}">
                                            </div>
                                            <div class="col-sm col-xs-12 p-3">
                                                <input id="price_sheet_price_{{ $price_sheet->id }}" type="number" class="form-control" value="{{ $price_sheet->price }}">
                                            </div>
                                            <div class="col-sm col-xs-12 p-3">
                                                <div class="float-right">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="delete_price_sheet('{{ $price_sheet->id }}');"><i class="ni ni-fat-delete"></i></button>
                                                    <button type="submit" class="btn btn-sm btn-primary" onclick="update_price_sheet('{{ $price_sheet->id }}');"><i class="ni ni-check-bold"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                    @endforeach
                                </div>
                                <hr />
                                <div class="row justify-content-md-center">
                                    <div class="col-sm col-xs-12 p-3">
                                        <input id="price_sheet_new_name_{{ $design_option->id }}" type="text" name="price_sheet_new_name" class="form-control" placeholder="Variation">
                                    </div>
                                    <div class="col-sm col-xs-12 p-3">
                                        <input id="price_sheet_new_price_{{ $design_option->id }}" type="number" class="form-control" placeholder="Price">
                                    </div>
                                    <div class="col-sm col-xs-12 p-3">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-success" onclick="new_price_sheet('{{ $design_option->id }}');">+ Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>  

</div>
@endsection

@section('scripts')
<script type="text/javascript">

    async function new_design_option() {

        const { value: name } = await Swal.fire({
            title: 'Name of design option:',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'on'
            },
            inputPlaceholder: 'Kitchen Cabinets',
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
                url: "/create-design-option",
                type: 'POST',
                data: {
                    name: name,
                    house_plan: "{{ $house_plan->id }}",
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
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header container">
                                        <div class="row">
                                            <h3 style="padding-top: 0.25em;padding-left: 1em;">${name}</h3>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="container">
                                            <div class="container" id="design_option_${msg['id']}">
                                            </div>
                                            <hr />
                                            <div class="row justify-content-md-center">
                                                <div class="col-sm col-xs-12 p-3">
                                                    <input id="price_sheet_new_name_${msg['id']}" type="text" name="price_sheet_new_name" class="form-control" placeholder="Variation">
                                                </div>
                                                <div class="col-sm col-xs-12 p-3">
                                                    <input id="price_sheet_new_price_${msg['id']}" type="number" class="form-control" placeholder="Price">
                                                </div>
                                                <div class="col-sm col-xs-12 p-3">
                                                    <div class="float-right">
                                                        <button type="submit" class="btn btn-success" onclick="new_price_sheet('${msg['id']}');">+ Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $('#design-options-div').append(html);
                }
            });
        }

    }

    async function new_price_sheet(design_option) {
        name = $("#price_sheet_new_name_" + design_option).val();
        price = $("#price_sheet_new_price_" + design_option).val();

        $.ajax({
            url: "/create-price-sheet",
            type: 'POST',
            data: {
                name: name,
                price: price,
                design_option: design_option,
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
                    <div class="row">
                        <div class="col-sm col-xs-12 p-3">
                            <input id="price_sheet_name_${msg['id']}" type="text" class="form-control" value="${name}">
                        </div>
                        <div class="col-sm col-xs-12 p-3">
                            <input id="price_sheet_price_${msg['id']}" type="number" class="form-control" value="${price}">
                        </div>
                        <div class="col-sm col-xs-12 p-3">
                            <div class="float-right">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="delete_price_sheet('${msg['id']}');"><i class="ni ni-fat-delete"></i></button>
                                <button type="submit" class="btn btn-sm btn-primary" onclick="update_price_sheet('${msg['id']}');"><i class="ni ni-check-bold"></i></button>
                            </div>
                        </div>
                    </div>
                    `;

                $("#design_option_" + design_option).append(html);
                $("#price_sheet_new_name_" + design_option).val("");
                $("#price_sheet_new_price_" + design_option).val("");
            }
        });
    }

    async function delete_price_sheet(id) {
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
                    url: "/delete-price-sheet",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                }).done(function (msg) {
                    Swal.fire({
                        icon: msg['icon'],
                        text: msg['msg']
                    });
                }, function(){
                });
            }
        });
    }

    async function update_price_sheet(id) {
        name = $("#price_sheet_name_" + id).val();
        price = $("#price_sheet_price_" + id).val();

        $.ajax({
            url: "/update-price-sheet",
            type: 'POST',
            data: {
                name: name,
                price: price,
                id: id,
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