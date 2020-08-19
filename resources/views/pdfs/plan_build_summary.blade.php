<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Summary Build Out</title>

            <!-- Fonts -->
            <link rel="dns-prefetch" href="//fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

            <link type="text/css" href="{{ asset('/assets/css/argon.min.css') }}" rel="stylesheet">

            <!-- Icons -->
            <link href="{{ asset('/assets/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
            <link href="{{ asset('/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    </head>

    <body>
        <div class="container">

        @foreach(DesignCategory::where('house_plan', $house_plan)->orderBy('order', 'ASC')->get() as $category)
        @if($category->hasOptions())
            <h1>{{ $category->name }}</h1>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Selection</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(DesignOption::where('house_plan', $house_plan)->orderBy('name')->where('category', $category->id)->get() as $design_option)
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

        @endif
        @endforeach
        
        
        </div>      
    </body>
</html>