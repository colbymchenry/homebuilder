@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="padding-bottom: 2em;">
        <div class="col-md-8">
            <button type="button" class="btn btn-success" onclick="saveBuildOut();">Save <i class="far fa-save"></i></button>
            <div class="float-right">
                <form action="/buildout-pdf" method="POST" target="_blank" id="export_pdf">
                    @csrf
                    <input name="lot" value="{{ $lot->id }}" hidden></input>
                    <input name="project" value="{{ $project->id }}" hidden></input>
                    <input name="house_plan" value="{{ $house_plan->id }}" hidden></input>
                    <button type="submit" class="btn btn-danger">Export to PDF <i class="far fa-file-pdf"></i></button>
                </form>
            </div>
        </div>
    </div>
    <div id="design-options-div">
        @foreach(DesignCategory::where('house_plan', $house_plan->id)->orderBy('order', 'ASC')->get() as $design_category)
        @if($design_category->hasOptions())
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header container">
                        <div class="pl-3 pt-3">
                            <div class="row">
                                <div class="col">
                                    <h3>{{ $design_category->name }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="container">

                        
                            @foreach(DesignOption::where('house_plan', $house_plan->id)->orderBy('name', 'ASC')->where('category', $design_category->id)->get() as $design_option)
                                @if($design_option->hasPriceSheets())
                                <div class="row p-3">
                                    <div class="col-sm-12">
                                        <p><b><u>{{ $design_option->name }}</u></b></p>
                                    </div>
                                    <div class="col-sm-8">

                                    @php
                                        $first = true;
                                    @endphp

                                        <select class="selectpicker" id="design_option_{{ $design_option->id }}" data-toggle="select" data-style="btn-primary" title="Simple select">
                                            <optgroup label="Standard">
                                                @foreach(PriceSheet::where('design_option', $design_option->id)->where('price', '<', 1)->get() as $price_sheet)
                                                    
                                                    @if($lot->getSelection($design_option->id) == null)
                                                        <option data-amount="{{ $price_sheet->price }}" id="{{ $price_sheet->name }}_{{ $design_option->id }}_{{ $price_sheet->id }}" @if($first) selected @endif>{{ $price_sheet->name }}</option>
                                                        @php
                                                        $first = false;
                                                        @endphp
                                                    @else
                                                        <option data-amount="{{ $price_sheet->price }}" id="{{ $price_sheet->name }}_{{ $design_option->id }}_{{ $price_sheet->id }}" @if($lot->getSelection($design_option->id) == $price_sheet->id) selected @endif>{{ $price_sheet->name }}</option>
                                                    @endif

                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Upgrade">
                                                @foreach(PriceSheet::where('design_option', $design_option->id)->where('price', '>', 0)->get() as $price_sheet)
                                                    <option data-amount="{{ $price_sheet->price }}" id="{{ $price_sheet->name }}_{{ $design_option->id }}_{{ $price_sheet->id }}" @if($lot->getSelection($design_option->id) == $price_sheet->id) selected @endif>{{ $price_sheet->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <h1><span class="badge badge-secondary" id="price_{{ $design_option->id }}">@if($lot->getSelection($design_option->id) != null) ${{ PriceSheet::where('id', $lot->getSelection($design_option->id))->first()->price }} @else $0.00 @endif</span></h1>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>  

    <h2 class="float-right">Subtotal: <span id="subtotal">$0.00</span></h2>

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

        $('#export_pdf').on('submit', function(event) {
            $("select[id^=design_option_]").each(function(e) {
                var obj = $(this);
                var id = obj.prop('id').split('_')[2];
                var selected_id = obj.find(":selected").data('id');

                $("<input />").attr("type", "hidden")
                .attr("name", "design_option_" + id)
                .attr("value", selected_id)
                .appendTo("#export_pdf");
            });
        });
    });

    function saveBuildOut() {
        var lot = '{{ $lot->id }}';
        var house_plan = '{{ $house_plan->id }}';
        var options = [];

        $('select[id^="design_option_"]').each(function() {
            var design_option_id = $(this).prop('id').split('_')[2];

            Array.from($(this).find(':selected')).map(function(item) {
                $(`option[id^="${$(item).text()}_${design_option_id}_"]`).each(function() {
                    var price_sheet_id = $(this).prop('id').split('_')[2];
                    options[design_option_id] = price_sheet_id;
                });
            });
        }).promise().done(function() {
            $.ajax({
                url: "/save-buildout",
                type: 'POST',
                data: {
                    lot: lot,
                    house_plan: house_plan,
                    selections: options, 
                    _token: '{{ csrf_token() }}'
                },
            }).done(function (msg) {
                Swal.fire({
                    icon: msg['icon'],
                    text: msg['msg']
                });
            });
         });

    }
</script>
@endsection