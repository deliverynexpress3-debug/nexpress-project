@extends('admin.layout.page-app')
@section('page_title', __('label.compensation'))
@section('tab_title', __('label.compensation'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.compensation') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.compensation') }}
                        </li>
                    </ol>
                </div>

            </div>

            <!-- Add Compensation -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{ __('label.add_compensation') }}</h5>
                <div class="card-body">
                    <form id="compensation" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">

                        <div class="form-row">
                            <div class="col-md-10">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('label.compensation_type') }}<span class="text-danger">*</span></label>
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="type" id="type_amount" class="custom-control-input" value="2" checked>
                                                        <label class="custom-control-label" for="type_amount">{{__('label.fixed_amount')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="type" id="type_percentage" class="custom-control-input" value="1">
                                                        <label class="custom-control-label" for="type_percentage">{{__('label.percentage')}}</label>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label> {{ __('label.compensation_amount') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="amount" class="form-control"
                                                placeholder="{{ __('label.enter_compensation_amount') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120"
                                onclick="save_compensation()">{{ __('label.save') }}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search && Table -->
            <div class="card custom-border-card mt-3">
                
                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th> {{ __('label.#') }} </th>
                                <th> {{ __('label.compensation_type') }} </th>
                                <th> {{ __('label.compensation_amount') }} </th>
                                <th> {{ __('label.compensation_status') }} </th>
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
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.edit_compensation') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_compensation" autocomplete="off">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ __('label.compensation_type') }}<span class="text-danger">*</span></label>
                                                    <div class="radio-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="type" id="is_type_amount"
                                                                class="custom-control-input" value="2">
                                                            <label class="custom-control-label"
                                                                for="is_type_amount">{{ __('label.fixed_amount') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="type" id="is_type_percentage"
                                                                class="custom-control-input" value="1">
                                                            <label class="custom-control-label"
                                                                for="is_type_percentage">{{ __('label.percentage') }}</label>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ __('label.compensation_amount') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" name="amount" id="edit_amount" class="form-control"
                                                        placeholder="{{ __('label.enter_compensation_amount') }}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                               
                                <input type="hidden" name="id" id="edit_id">
                               
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                    onclick="update_compensation()">{{ __('label.update') }}</button>
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
        sidebar_down(800);

        $("#parent_category_id").select2();
        $("#industries_id").select2();
        $("#edit_industries_id").select2();

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.compensation.index') }}",
                   
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                
                    {
						data: 'type',
						name: 'type',
						render: function(data) {
                            if (data == 1) {
                                return data ? "<h6 class='primary-color'><strong> {{__('label.percentage')}} </strong></h6>" : "-";
                            }else if(data ==2){
                                return data ? "<h6 class='primary-color'><strong> {{__('label.fixed_amount')}} </strong></h6>" : "-";
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

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
          

        });

        function save_compensation() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#compensation")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.compensation.store') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'compensation', '{{ route('admin.compensation.index') }}');
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

        $(document).on("click", ".edit_compensation", function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var amount = $(this).data('amount');
            var is_type = $(this).data('type');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_name").val(name);
            $(".modal-body #edit_amount").val(amount);

            if(is_type == 1){ 
                $('.modal-body #is_type_percentage').val(is_type).prop("checked", true);
                
            }else if(is_type == 2){
                $('.modal-body #is_type_amount').val(is_type).prop("checked", true);
               
            }
        });

        function update_compensation() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#edit_compensation")[0]);

                var Edit_Id = $("#edit_id").val();
                var url = '{{ route('admin.compensation.update', ':id') }}';
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
                        get_responce_message(resp, 'edit_compensation', "{{ route('admin.compensation.index') }}");
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
                var url = `{{ route('admin.compensation.show', '') }}/${id}`;

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
