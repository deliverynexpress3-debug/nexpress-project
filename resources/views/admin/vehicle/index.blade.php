@extends('admin.layout.page-app')
@section('page_title', __('label.vehicle'))
@section('tab_title', __('label.vehicle'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.vehicle') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.vehicle') }}
                        </li>
                    </ol>
                </div>

            </div>

            <!-- Add vehicle -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{ __('label.add_vehicle') }}</h5>
                <div class="card-body">
                    <form id="vehicle" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('label.name') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="{{ __('label.enter_name') }}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('label.vehicle_number') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="number" class="form-control"
                                                placeholder="{{ __('label.enter_vehicle_number') }}">
                                        </div>
                                    </div>
                                  
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('label.brand') }}<span class="text-danger">*</span></label>
                                            <div class="row">
                                                @foreach ($brand as $value)
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="brand_id" value="{{ $value->id }}" id="brand_{{ $value->id }}" class="custom-control-input">
                                                    <label class="custom-control-label" for="brand_{{ $value->id }}">{{ $value->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                  
                               
                                </div>
                            </div>
                            
                        </div>

                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120"
                                onclick="save_vehicle()">{{ __('label.save') }}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search && Table -->
            <div class="card custom-border-card mt-3">
                <div class="page-search mb-3">
                    <div class="input-group" title="Search">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control"
                            placeholder="{{ __('label.search') }}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                    <div class="sorting w-50">
                        <label>Sort by :</label>
                        <select class="form-control" name="input_brand" id="input_brand_id">
                            <option value="all" selected>{{__('label.all_brand')}}</option>
                            @for ($i = 0; $i < count($brand); $i++)
                                <option value="{{ $brand[$i]['id'] }}"
                                    @if (isset($_GET['input_brand_id'])) {{ $_GET['input_brand_id'] == $brand[$i]['id'] ? 'selected' : '' }} @endif>
                                    {{ $brand[$i]['name'] }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th> {{ __('label.#') }} </th>
                                <th> {{ __('label.name') }} </th>
                                <th> {{ __('label.vehicle_number') }} </th>
                                <th> {{ __('label.brand') }} </th>
                                <th> {{ __('label.is_used') }} </th>
                                <th> {{ __('label.status') }} </th>
                                <th> {{ __('label.action') }} </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- EditModel -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.edit_vehicle') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_vehicle" autocomplete="off">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('label.name') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="name" id="edit_name" class="form-control"
                                                        placeholder="{{ __('label.enter_name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('label.vehicle_number') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="number" id="edit_number" class="form-control"
                                                        placeholder="{{ __('label.enter_vehicle_number') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('label.brand') }}<span class="text-danger">*</span></label>
                                            <div class="row">
                                                @foreach ($brand as $value)
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="brand_id" value="{{ $value->id }}" id="brand_{{ $value->id }}" class="custom-control-input">
                                                    <label class="custom-control-label" for="brand_{{ $value->id }}">{{ $value->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                 
                                </div>
                                <input type="hidden" name="id" id="edit_id">
                               
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                    onclick="update_vehicle()">{{ __('label.update') }}</button>
                                <button type="button" class="btn btn-cancel mw-120"
                                    data-dismiss="modal">{{ __('label.close') }}</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        sidebar_down(500);
        $('#brand_id, #input_brand_id, #edit_brand_id').select2();


        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.vehicle.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                        d.input_brand_id = $('#input_brand_id').val();
                       
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'number',
                        name: 'number',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'brand.name',
                        name: 'brand.name',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'is_used',
                        name: 'is_used',
                        render: function(data, type, full, meta) {
                            if (data ==1 ) {
                                return `<h6 class='primary-color'><strong> {{__('label.yes')}}</strong></h6>`;
                            
                            } else if(data ==0) {
                                return `<h6 class='primary-color'><strong> {{__('label.no')}}</strong></h6>`;
                               
                            }
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
            $('#input_brand_id').change(function() {
                    table.draw();
                });
            $('#input_search').keyup(function() {
                table.draw();
            });

        });

        function save_vehicle() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#vehicle")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.vehicle.store') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'vehicle', '{{ route('admin.vehicle.index') }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        }

        $(document).on("click", ".edit_vehicle", function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var number = $(this).data('number');
            var brand_id = $(this).data('brand_id');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_name").val(name);
            $(".modal-body #edit_number").val(number);

            $(".modal-body input[name='brand_id']").each(function() {
                $(this).prop("checked", $(this).val() == brand_id);
            });
        
        });

        function update_vehicle() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#edit_vehicle")[0]);

                var Edit_Id = $("#edit_id").val();
                var url = '{{ route('admin.vehicle.update', ':id') }}';
                url = url.replace(':id', Edit_Id);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        $('#EditModel').modal('toggle');
                        get_responce_message(resp, 'edit_vehicle', "{{ route('admin.vehicle.index') }}");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        }

        function change_status(id) {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var url = `{{ route('admin.vehicle.show', '') }}/${id}`;

                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(resp) {
                        $("#dvloader").hide();

                        if (resp.status == 200) {
                            if (resp.status_code == 1) {
                                $('#' + id).text('{{__("label.show")}}').removeClass('hide-btn').addClass('show-btn');
                            } else {
                                $('#' + id).text('{{__("label.hide")}}').removeClass('show-btn').addClass('hide-btn');
                            }

                            toastr.success(resp.success);
                        } else {
                            toastr.error(resp.errors);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });

            } else {
                showError();
            }
        };
    </script>
@endsection
