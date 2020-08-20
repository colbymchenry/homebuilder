<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <title>Summary Build Out</title>

        <link rel="stylesheet" href="{{ asset('css/bootstrap337.css') }}">
            <style>
                .page-break {
                    page-break-after: always;
                }
            </style>
        </head>

    <body>
        <div class="container">

        <div class="row justify-content-center">
            <div class="col-xs-2">
                <div class="row">
                    <h5>Project: {{ $project->name }}</h5>
                    <p>Lot: #{{ $lot->number }}</p>
                    <small>
                    @foreach(explode(',', $lot->address) as $line)
                        {{ ltrim($line) }}<br />
                    @endforeach
                    </small>
                </div>
            </div>
            <div class="col-xs-7">
                <div class="row" style="text-align: center;">
                    <h1><u>Site Selection Plan</u></h1>
                    <h4>David Patterson Homes</h4>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="row" style="text-align: right;">
                    <small style="text-align: right;">{{ date("m/d/Y") }}</small>
                </div>
            </div>
        </div>
        <hr />
        @php
        $count = 0;
        $max = DesignCategory::where('house_plan', $house_plan->id)->count();
        @endphp

        @foreach(DesignCategory::where('house_plan', $house_plan->id)->orderBy('order', 'ASC')->get() as $category)
        @if($category->hasOptions())
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col"><h3 style="margin-bottom: -0.5em;">{{ $category->name }}</h3></th>
                                <th scope="col">Selection</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(DesignOption::where('house_plan', $house_plan->id)->orderBy('name')->where('category', $category->id)->get() as $design_option)
                            <tr>
                                <th scope="row">{{ $design_option->name }}</th>
                                @if(array_key_exists($design_option->id, $choices))
                                    <td>{{ PriceSheet::where('id', $choices[$design_option->id])->first()->name }}</td>
                                    <td>{{ PriceSheet::where('id', $choices[$design_option->id])->first()->getFormattedPrice() }}</td>
                                @else
                                    <td>Standard</td>
                                    <td>$0</td>
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <th scope="row"><b>Subtotal:</b></th>
                                <td></td>
                                @if(array_key_exists($category->id, $sub_totals))
                                    <td><b>{{ PriceSheet::formatToCurrency($sub_totals[$category->id]) }}</b></td>
                                @else
                                    <td><b>$0</b></td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if($count > 0 && $count < ($max - 2))
                <!-- <div class="page-break"></div> -->
            @endif
            @php
                $count += 1;
                \Log::info($count . ':' . $max);
            @endphp
        @endif
        @endforeach
        
        
        </div>      
    </body>
</html>