@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="padding-bottom: 2em;">
        <div class="col">
            <div class="float-left">
                <button type="button" id="show-order-btn" class="btn btn-primary" data-toggle="modal" data-target="#order_modal" clic>Modify Order</button>
            </div>
        </div>
        <div class="col">
            <div class="float-right">
                <button type="button" class="btn btn-success" onclick="new_design_category()">+ Category</button>
            </div>
        </div>
    </div>
    <div id="design-categories-div">
    @foreach(DesignCategory::where('house_plan', $house_plan->id)->orderBy('order', 'ASC')->get() as $design_category)
            <div class="row justify-content-center">
                    <div class="card w-100">
                        <div class="card-header container" data-toggle="collapse" data-target="#cat_body_{{ $design_category->id }}" aria-expanded="false" aria-controls="cat_body_{{ $design_category->id }}">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                            <div class="col">
                                                <h3 style="padding-top: 0.25em;padding-left: 1em;" id="category_name_{{ $design_category->id }}">{{ $design_category->name }}</h3>
                                            </div>
                                            <div class="col">
                                                <a href="javascript:rename_category('{{ $design_category->id }}')"><i class="far fa-edit"></i></a>
                                            </div>
                                            </div>
                                         
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <button type="button" class="btn btn-sm btn-success float-right" onclick="new_design_option('{{ $design_category->id }}')">+ Option</button>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-sm btn-danger float-right" onclick="delete_design_category('{{ $design_category->id }}')"><i class="fa fa-trash-alt"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="card-body collapse" style="background-color: lightgray;" id="cat_body_{{ $design_category->id }}">
                            <div class="container" id="design_category_{{ $design_category->id }}">
                               

        @foreach(DesignOption::where('house_plan', $house_plan->id)->orderBy('name', 'ASC')->where('category', $design_category->id)->get() as $design_option)
            <div class="row justify-content-center" id="div_design_option_{{ $design_option->id }}">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header container">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <h3 style="padding-top: 0.25em;padding-left: 1em;" id="option_name_{{ $design_option->id }}">{{ $design_option->name }}</h3>
                                        </div>
                                        <div class="col-xs-2 pl-1 pt-1">
                                            <a href="javascript:rename_option('{{ $design_option->id }}');"><i class="far fa-edit"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-sm btn-danger float-right" onclick="delete_design_option('{{ $design_option->id }}')"><i class="fa fa-trash-alt"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="container" id="design_option_{{ $design_option->id }}">
                            @foreach(PriceSheet::where('design_option', $design_option->id)->get() as $price_sheet)
                                <div class="row" id="price_sheet_{{ $price_sheet->id }}">
                                    <div class="col-6  p-3">
                                        <input id="price_sheet_name_{{ $price_sheet->id }}" type="text" class="form-control" value="{{ $price_sheet->name }}">
                                    </div>
                                    <div class="col-3 p-3">
                                        <input id="price_sheet_price_{{ $price_sheet->id }}" type="number" class="form-control" value="{{ $price_sheet->price }}">
                                    </div>
                                    <div class="col-3 p-3">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="delete_price_sheet('{{ $price_sheet->id }}');"><i class="ni ni-fat-delete"></i></button>
                                            <button type="submit" class="btn btn-sm btn-primary" onclick="update_price_sheet('{{ $price_sheet->id }}');"><i class="ni ni-check-bold"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="container">
                            <div class="row justify-content-md-center">
                                <div class="col-6 p-3">
                                    <input id="price_sheet_new_name_{{ $design_option->id }}" type="text" name="price_sheet_new_name" class="form-control" placeholder="Variation">
                                </div>
                                <div class="col-3 p-3">
                                    <input id="price_sheet_new_price_{{ $design_option->id }}" type="number" class="form-control" placeholder="Price">
                                </div>
                                <div class="col-3 p-3">
                                    <div class="float-right">
                                        <button type="submit" class="btn btn-success" onclick="new_price_sheet('{{ $design_option->id }}');">+ Add</button>
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
                    </div>
            </div>
        @endforeach






       
</div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header container">
                        <div class="row">
                            <div class="col-sm">
                                <h5 style="padding-top: 0.25em;">Files</h5>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="container">
                            <div class="row justify-content-md-center">
                                @foreach(File::where('relational_id', $house_plan->id)->where('relational_table', 'house_plans')->get() as $file)
                                <div class="col-sm">
                                    <a href="{{ $file->getURL() }}">{{ $file->name }}</a>
                                </div>
                                <div class="col-sm float-right">
                                    <a href="{{ $file->getURL() }}" class="button float-right" download><i class="fa fa-download"></i></a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <br />
                        <div class="container">
                            @include('file_upload_html')
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>




<!-- Modal -->
<div class="modal fade" id="order_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modify Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="hide-new-order-btn">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container" id="order_div">
              @foreach(DesignCategory::orderBy('order', 'ASC')->get() as $design_category)
                <div class="row shadow p-1 mb-3 bg-white rounded" id="placement_{{ $design_category->id }}">
                    <div class="col-8 pt-3">
                        <p>{{ $design_category->name }}</p>
                    </div>
                    <div class="col-4 pt-3">
                            <div class="col">
                                <a href="javascript:move_up('{{ $design_category->id }}');" class="float-left"><i class="fas fa-arrow-up"></i></a>
                            </div>
                            <div class="col">
                                <a href="javascript:move_down('{{ $design_category->id }}');" class="float-right"><i class="fas fa-arrow-down"></i></a>
                            </div>
                    </div>
                </div>
              @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    async function delete_design_option(id) {
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
                    url: "/delete-design-option",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                }).done(function (msg) {
                    if(msg['icon'] == 'success') {
                        $("#div_design_option_" + id).remove();
                    } else {
                        Swal.fire({
                            icon: msg['icon'],
                            text: msg['msg']
                        });
                    }
                });
            }
        });
    }

    async function new_design_option(category) {

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
                    category: category,
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
                        <div class="row justify-content-center" id="div_design_option_${msg['id']}">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header container">
                                        <div class="row">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-xs-2">
                                                        <h3 style="padding-top: 0.25em;padding-left: 1em;" id="option_name_${msg['id']}">${name}</h3>
                                                    </div>
                                                    <div class="col-xs-2 pl-1 pt-1">
                                                        <a href="javascript:rename_option('${msg['id']}');"><i class="far fa-edit"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <button type="button" class="btn btn-sm btn-danger float-right" onclick="delete_design_option('${msg['id']}')"><i class="fa fa-trash-alt"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="container" id="design_option_${msg['id']}">
                                        <div class="row justify-content-md-center">
                                            <div class="col-6 p-3">
                                                <input id="price_sheet_new_name_${msg['id']}" type="text" name="price_sheet_new_name" class="form-control" placeholder="Variation">
                                            </div>
                                            <div class="col-3 p-3">
                                                <input id="price_sheet_new_price_${msg['id']}" type="number" class="form-control" placeholder="Price">
                                            </div>
                                            <div class="col-3 p-3">
                                                <div class="float-right">
                                                    <button type="submit" class="btn btn-success" onclick="new_price_sheet('${msg['id']}');">+ Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $('#design_category_' + category).append(html);
                }
            });
        }

    }

    async function delete_design_category(id) {
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
                    url: "/delete-design-category",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                }).done(function (msg) {
                    if(msg['icon'] == 'success') {
                        $("#design_category_" + id).remove();
                    } else {
                        Swal.fire({
                            icon: msg['icon'],
                            text: msg['msg']
                        });
                    }
                });
            }
        });
    }

    async function new_design_category() {

        const { value: name } = await Swal.fire({
            title: 'Name of design category:',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'on'
            },
            inputPlaceholder: 'Exterior, Interior, Kitchen',
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
                url: "/create-design-category",
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
                                    <div class="card-header container" data-toggle="collapse" data-target="#cat_body_${msg['id']}" aria-expanded="false" aria-controls="cat_body_${msg['id']}">
                                        <div class="row">
                                            <div class="col">
                                                <button type="button" class="btn btn-sm btn-success float-right" onclick="new_design_option('${msg['id']}')">+ Add Design Option</button>
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-sm btn-danger float-right" onclick="delete_design_category('${msg['id']}')"><i class="fa fa-trash-alt"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body collapse" style="background-color: lightgray;" id="cat_body_${msg['id']}">
                                        <div class="container" id="design_category_${msg['id']}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $('#design-categories-div').append(html);
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
                    <div class="row" id="price_sheet_${msg['id']}">
                        <div class="col-6 p-3">
                            <input id="price_sheet_name_${msg['id']}" type="text" class="form-control" value="${name}">
                        </div>
                        <div class="col-3 p-3">
                            <input id="price_sheet_price_${msg['id']}" type="number" class="form-control" value="${price}">
                        </div>
                        <div class="col-3 p-3">
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
                    if(msg['icon'] != 'success') {
                        Swal.fire({
                            icon: msg['icon'],
                            text: msg['msg']
                        });
                    } else {
                        $('#price_sheet_' + id).remove();
                    }
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

    async function rename_category(id) {

        const { value: name } = await Swal.fire({
            title: 'Name of design category:',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'on'
            },
            inputPlaceholder: 'Exterior, Interior, Kitchen',
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
                url: "/rename-design-category",
                type: 'POST',
                data: {
                    name: name,
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
            }).done(function (msg) {
                if(msg['icon'] !== 'success') {
                    Swal.fire({
                        icon: msg['icon'],
                        text: msg['msg']
                    });
                } else {
                    $('#category_name_' + id).text(name);
                }
            });
        }

    }

    async function rename_option(id) {

        const { value: name } = await Swal.fire({
            title: 'Name of design option:',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'on'
            },
            inputPlaceholder: 'Brass, Brown, White',
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
                url: "/rename-design-option",
                type: 'POST',
                data: {
                    name: name,
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
            }).done(function (msg) {
                if(msg['icon'] !== 'success') {
                    Swal.fire({
                        icon: msg['icon'],
                        text: msg['msg']
                    });
                } else {
                    $('#option_name_' + id).text(name);
                }
            });
        }

    }

    function move_up(id) {
        var obj = $(`#placement_${id}`);
        var above = obj.prev('div');
        var beneath = obj.next('div');

        if(above.length != 0) {
            obj.insertBefore(above);
        }
    }

    function move_down(id) {
        var obj = $(`#placement_${id}`);
        var above = obj.prev('div');
        var beneath = obj.next('div');

        if(beneath.length != 0) {
            obj.insertAfter(beneath);
        }
    }

    $(document).ready(function() {
        $('#order_modal').on('hidden.bs.modal', function (e) {
            var div = document.getElementById('order_div');
            var divs = div.getElementsByTagName('div');
            var divArray = [];
            for (var i = 0; i < divs.length; i += 1) {
                if(divs[i].id.includes('placement_')) {
                    divArray.push(divs[i].id.split('_')[1]);
                }
            }

            $.ajax({
                url: "/set-design-category-orders",
                type: 'POST',
                data: {
                    categories: divArray,
                    _token: '{{ csrf_token() }}'
                },
            }).done(function (msg) {
                if(msg['icon'] !== 'success') {
                    Swal.fire({
                        icon: msg['icon'],
                        text: msg['msg']
                    });
                } else {
                    history.go(0);
                }
            });
        });
    });
</script>

<script type="text/javascript">
    $("#file-1").fileinput({
        theme: 'fas',
        uploadUrl: "/file-upload",
        uploadExtraData: function() {
            return {
                table: 'house_plans',
                relational_id: "{{ $house_plan->id }}",
                _token: "{{ @csrf_token() }}",
            };
        },
        allowedFileExtensions: ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'zip', 'jpeg'],
        overwriteInitial: true,
        maxFileSize:200000,
        maxFilesNum: 10
    });
</script>
@endsection