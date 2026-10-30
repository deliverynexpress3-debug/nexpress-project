@extends('seller.layout.page-app')
@section('page_title', __('label.tax'))
@section('tab_title', __('label.tax'))
@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.tax') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.tax') }}
                        </li>
                    </ol>
                </div>

            </div>

            <!-- Add Taxes -->
            @if(Setting_Data()['taxes_access'] ==1)
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{ __('label.add_tax') }}</h5>
                <div class="card-body">
                    <form id="taxes" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-row">
                                
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('label.tax_name') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control required"
                                                placeholder="{{ __('label.enter_tax_name') }}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('label.tax_type') }}<span class="text-danger">*</span></label>
                                            <div class="row">

                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="type" value="1" id="type_1"
                                                        class="custom-control-input" checked>
                                                    <label class="custom-control-label"
                                                        for="type_1">{{ __('label.percentage') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="type" value="2" id="type_2"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="type_2">{{ __('label.fixed') }}</label>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('label.tax_amount') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="amount" class="form-control"
                                                placeholder="{{ __('label.enter_tax_amount') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120"
                                onclick="save_taxes()">{{ __('label.save') }}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>
            @endif

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
                    <div class="sorting" style="width: 30%;">
                        <label>{{ __('label.sort_by') }}</label>
                        <select class="form-control" id="input_type">
                            <option value="all">{{ __('label.all_type') }}</option>
                            <option value="1">{{ __('label.percentage') }}</option>
                            <option value="2">{{ __('label.fixed') }}</option>

                        </select>
                    </div>
                </div>
                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th> {{ __('label.#') }} </th>
                                <th> {{ __('label.tax_name') }} </th>
                                <th> {{ __('label.tax_type') }} </th>
                                <th> {{ __('label.tax_amount') }} </th>
                                <th> {{ __('label.tax_status') }} </th>
                                @if(Setting_Data()['taxes_access'] ==1)
                                <th> {{ __('label.action') }} </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- EditModel -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.edit_tax') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_taxes" autocomplete="off">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ __('label.tax_name') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="name" id="edit_name" class="form-control"
                                                        placeholder="{{ __('label.enter_tax_name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label> {{ __('label.tax_type') }}<span class="text-danger">*</span></label>
                                                    <div class="row">
            
                                                        <div class="custom-control custom-radio ml-3">
                                                            <input type="radio" name="type" value="1" id="edit_type_1"
                                                                class="custom-control-input">
                                                            <label class="custom-control-label"
                                                                for="edit_type_1">{{ __('label.percentage') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio ml-3">
                                                            <input type="radio" name="type" value="2" id="edit_type_2"
                                                                class="custom-control-input">
                                                            <label class="custom-control-label"
                                                                for="edit_type_2">{{ __('label.fixed') }}</label>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>{{ __('label.tax_amount') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" name="amount" id="edit_amount" class="form-control"
                                                        placeholder="{{ __('label.enter_tax_amount') }}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                               
                               
                                <input type="hidden" name="id" id="edit_id">
                               
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                    onclick="update_taxes()">{{ __('label.update') }}</button>
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
    <script>
        
        $("#parent_category_id").select2();
        $("#industries_id").select2();
        $("#edit_industries_id").select2();

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('seller.tax.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                        d.input_type = $('#input_type').val();
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
						data: 'type',
						name: 'type',
						render: function(data) {
                            if (data == 1) {
                                return data ? "<h6 class='primary-color'><strong> {{__('label.percentage')}} </strong></h6>" : "-";
                            }else if(data ==2){
                                return data ? "<h6 class='primary-color'><strong> {{__('label.fixed')}} </strong></h6>" : "-";
                            }else {
                                return "-";
                            }
                           
                        }
					},
                   
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    @if(Setting_Data()['taxes_access'] ==1)
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    @endif
                ],
            });
            $('#input_type').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });

        });

        function save_taxes() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#taxes")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('seller.tax.store') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'taxes', '{{ route('seller.tax.index') }}');
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

        $(document).on("click", ".edit_taxes", function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var amount = $(this).data('amount');
            var is_type = $(this).data('type');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_name").val(name);
            $(".modal-body #edit_amount").val(amount);

            if(is_type == 1){ 
                $('#edit_type_1').prop('checked', true);
                
            }else if(is_type == 2){
                $('#edit_type_2').prop('checked', true);
               
            }
        });

        function update_taxes() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#edit_taxes")[0]);

                var Edit_Id = $("#edit_id").val();
                var url = '{{ route('seller.tax.update', ':id') }}';
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
                       
                        if(resp.status == 200){
                            $('#EditModel').modal('toggle');
                        }
                        get_responce_message(resp, 'edit_taxes', "{{ route('seller.tax.index') }}");
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
                var url = `{{ route('seller.tax.show', '') }}/${id}`;

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
                                $('#' + id).text('{{ __('label.active') }}').removeClass('status-inactive')
                                    .addClass('status-active');
                            } else {
                                $('#' + id).text('{{ __('label.inactive') }}').removeClass('status-active')
                                    .addClass('status-inactive');
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
