@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="padding-bottom: 2em;">
        <div class="col-md-8">
            <div class="float-right">
                <button type="submit" class="btn btn-success" onclick="new_design_option()">Save for Customer</button>
                <button type="submit" class="btn btn-primary" onclick="new_design_option()">Save for Builder</button>
            </div>
        </div>
    </div>
    <div id="design-options-div">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header container">
                            <div class="row">
                                <h3 style="padding-top: 0.25em;padding-left: 1em;">Spec Build Out</h3>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="container">
                                @foreach(DesignOption::where('house_plan', $house_plan->id)->get() as $design_option)

                                    <div class="row p-3">
                                        <div class="col-sm col-xs-12 p-3">
                                            <p>{{ $design_option->name }}</p>
                                        </div>
                                        <div class="col-sm col-xs-12 p-3">
                                            <select class="form-control" id="design_option_{{ $design_option->id }}" data-toggle="select" title="Simple select" data-live-search="true" data-live-search-placeholder="Search ...">
                                                <option value="" data-amount="0" disabled selected>Select your option</option>
                                               
                                                @foreach(PriceSheet::where('design_option', $design_option->id)->get() as $price_sheet)
                                                    <option data-amount="{{ $price_sheet->price }}">{{ $price_sheet->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm col-xs-12 p-3">
                                            <p id="price_{{ $design_option->id }}">$0.00</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <h2 class="float-right">Subtotal: <span id="subtotal">$0.00</span></h2>
                        </div>

                    </div>
                </div>
            </div>
    </div>  

</div>
@endsection

@section('scripts')
<script type="text/javascript">

    const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2
    });

    var amounts = [];

    $(document).ready(function() {
        $("select[id^=design_option_]").on('change', function(e) {
            var obj = $(e.target);
            var id = e.target.id.split('_')[2];
            var amount = obj.find(":selected").data('amount');
            amounts[id] = amount;

            $('#price_' + id).text(formatter.format(amount));

            var subtotal = 0;
            $.each(amounts, function( index, value ) {
                if(value !== undefined) subtotal += value;
            });

            $('#subtotal').text(formatter.format(subtotal));
        });
    });

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