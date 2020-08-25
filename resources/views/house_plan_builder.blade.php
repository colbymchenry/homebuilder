@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="padding-bottom: 2em;">
        <div class="col-md-8">
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
                                        <p>{{ $design_option->name }}</p>
                                    </div>
                                    <div class="col-sm-8">

                                    @php
                                        $first = true;
                                    @endphp

                                        <select class="selectpicker" id="design_option_{{ $design_option->id }}" data-toggle="select" title="Simple select">
                                            <optgroup label="Standard">
                                                @foreach(PriceSheet::where('design_option', $design_option->id)->where('price', '<', 1)->get() as $price_sheet)
                                                    @if($first)
                                                        <option data-amount="{{ $price_sheet->price }}" data-id="{{ $price_sheet->id }}" selected>{{ $price_sheet->name }}</option>
                                                        @php
                                                        $first = false;
                                                        @endphp
                                                    @else
                                                        <option data-amount="{{ $price_sheet->price }}" data-id="{{ $price_sheet->id }}">{{ $price_sheet->name }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Upgrade">
                                                @foreach(PriceSheet::where('design_option', $design_option->id)->where('price', '>', 0)->get() as $price_sheet)
                                                    <option data-amount="{{ $price_sheet->price }}" data-id="{{ $price_sheet->id }}">{{ $price_sheet->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <p id="price_{{ $design_option->id }}">$0.00</p>
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
</script>
@endsection