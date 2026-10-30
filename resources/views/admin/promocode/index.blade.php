@extends('admin.layout.page-app')
@section('page_title', __('label.promocode'))
@section('tab_title', __('label.promocode'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.promocode') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.promocode') }}
                        </li>
                    </ol>
                </div>

            </div>

            <!-- Add Promocode -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{ __('label.add_promocode') }}</h5>
                <div class="card-body">
                    <form id="promocode" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        
                        <div class="form-row">
                            <div class="col-md-9">
                                <div class="form-row">
                            
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('label.promocode_name') }}<span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" placeholder="{{ __('label.enter_promocode_name') }}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('label.type') }}<span class="text-danger">*</span></label>
                                            <div class="row">

                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="type" value="2" id="type_1" class="custom-control-input" checked>
                                                    <label class="custom-control-label" for="type_1">{{ __('label.percentage') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="type" value="2" id="type_2" class="custom-control-input">
                                                    <label class="custom-control-label" for="type_2">{{ __('label.fixed') }}</label>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('label.discount_amount') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="discount_amount" class="form-control"
                                                placeholder="{{ __('label.enter_discount_amount') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('label.valid_from') }}<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="valid_from" name="valid_from" min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    
                                   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.valid_until')}}<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="valid_until" name="valid_until" min="{{ date('Y-m-d') }}">
                                                    
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('label.usage_limit')}}</label>
                                            <select class="form-control" name="usage_limit" id="usage_limit">
                                                <option value="0">{{__('label.all')}}</option>
                                                <option value="1">{{__('label.user_limit')}}</option>
                                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2" id="Userlimit">
                                        <div class="form-group">
                                            <label> {{ __('label.no_of_users') }}<span class="text-danger">*</span></label>
                                            <input type="number" name="user_limit_per_user" class="form-control" placeholder="{{ __('label.enter_no') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ml-3">
                                    <label>{{__('label.image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type="file" name="image" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                        <label class="avatar-preview drop-area">
                                            <img src="{{ asset('assets/imgs/upload_img.png') }}" class="imagePreview">
                                        </label>
                                    </div>
                                </div>
                               
                            </div>
                        </div>

                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120"
                                onclick="save_promocode()">{{ __('label.save') }}</button>
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
                    
                </div>
                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th> {{ __('label.#') }} </th>
                                <th> {{ __('label.promocode') }} </th>
                                <th> {{ __('label.valid_from') }} </th>
                                <th> {{ __('label.valid_until') }} </th>
                                <th> {{ __('label.usage_limit') }} </th>
                                <th> {{ __('label.no_of_users') }} </th>
                                <th> {{ __('label.type') }} </th>
                                <th> {{ __('label.discount_amount') }} </th>
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
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.edit_promocode') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_promocode" autocomplete="off">
                            <div class="modal-body">
                               
                                <div class="form-row">
                                    <div class="col-md-8">
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ __('label.promocode') }}</label>
                                                    <input type="text" name="name" id="edit_promo_code" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label>{{ __('label.promocode_name') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="name" id="edit_name" class="form-control"
                                                        placeholder="{{ __('label.enter_promocode_name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label> {{ __('label.type') }}<span class="text-danger">*</span></label>
                                                    <div class="row">
            
                                                        <div class="custom-control custom-radio ml-3">
                                                            <input type="radio" name="type" value="2" id="edit_type_1"
                                                                class="custom-control-input">
                                                            <label class="custom-control-label"
                                                                for="edit_type_1">{{ __('label.percentage') }}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio ml-3">
                                                            <input type="radio" name="type" value="1" id="edit_type_2"
                                                                class="custom-control-input">
                                                            <label class="custom-control-label"
                                                                for="edit_type_2">{{ __('label.fixed') }}</label>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>{{ __('label.discount_amount') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" name="discount_amount" id="edit_discount_amount" class="form-control"
                                                        placeholder="{{ __('label.enter_discount_amount') }}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.image')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type="file" name="image" class="imageUploadModel" accept=".png, .jpg, .jpeg" hidden="">
                                                <label class="avatar-preview drop-area">
                                                    <img src="{{ asset('assets/admin/imgs/no_img.png') }}" class="imagePreviewModel">
                                                </label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.valid_from')}}<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="edit_valid_from" name="valid_from" min="{{ date('Y-m-d') }}">
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.valid_until')}}<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="edit_valid_until" name="valid_until" min="{{ date('Y-m-d') }}">
                                           
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.usage_limit')}}</label>
                                            <select class="form-control" name="usage_limit" id="edit_usage_limit">
                                                <option value="0">{{__('label.all')}}</option>
                                                <option value="1">{{__('label.user_limit')}}</option>
                                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="EditUserlimit">
                                        <div class="form-group">
                                            <label> {{ __('label.no_of_users') }}<span class="text-danger">*</span></label>
                                            <input type="number" name="user_limit_per_user" id="edit_user_limit_per_user" class="form-control" placeholder="{{ __('label.enter_no') }}">
                                        </div>
                                    </div>
                                </div>
                               
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="old_image" id="edit_old_image">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                    onclick="update_promocode()">{{ __('label.update') }}</button>
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
        sidebar_down(300);
        $('#Userlimit').hide();
        
        $(document).ready(function() {
            // Function to handle showing/hiding based on select value
            function toggleUserLimit(selector, target) {
                var value = $(selector).val();
                if(value == 1) {
                    $(target).show();
                } else {
                    $(target).hide();
                }
            }

            // On change of usage_limit
            $('#usage_limit').on('change', function () {
                toggleUserLimit(this, '#Userlimit');
            });

            // On change of edit_usage_limit
            $('#edit_usage_limit').on('change', function () {
                toggleUserLimit(this, '#EditUserlimit');
            });

            // Initial load to check the current value of the select fields
            toggleUserLimit('#usage_limit', '#Userlimit');
            toggleUserLimit('#edit_usage_limit', '#EditUserlimit');
        });

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.promocode.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                   
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                   
                    {
                        data: 'promo_code',
                        name: 'promo_code',
                        render: function(data) {
                            return data ? `<h6 class='primary-color'><strong> ${data} </strong></h6>` : "-";
                        }
                    },

                    {
                        data: 'valid_from',
                        name: 'valid_from',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'valid_until',
                        name: 'valid_until',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'usage_limit',
                        name: 'usage_limit',
                        
                        render: function(data) {
                            if (data == 0) {
                                return "{{__('label.all')}}";
                            } else if (data == 1) {
                                return "{{__('label.user_limit')}}";
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'user_limit_per_user',
                        name: 'user_limit_per_user',
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
                                return "{{__('label.fixed')}}";
                            } else if (data == 2) {
                                return "{{__('label.percentage')}}";
                            } else {
                                return "-";
                            }

                           
                        }
					},
                    {
                        data: 'discount_amount',
                        name: 'discount_amount',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data ? `<h6'><strong> ${data} </strong></h6>` : "-";

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
            
            $('#input_search').keyup(function() {
                table.draw();
            });

        });

        function save_promocode() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#promocode")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.promocode.store') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'promocode', '{{ route('admin.promocode.index') }}');
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

        $(document).on("click", ".edit_promocode", function() {
            var id = $(this).data('id');
            var promo_code = $(this).data('promo_code');
            var name = $(this).data('name');
            var discount_amount = $(this).data('discount_amount');
            var valid_from = $(this).data('valid_from');
            var valid_until = $(this).data('valid_until');
            var usage_limit = $(this).data('usage_limit');
            var user_limit_per_user = $(this).data('user_limit_per_user');
            var is_type = $(this).data('type');
            var image = $(this).data('image');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_promo_code").val(promo_code);
            $(".modal-body #edit_name").val(name);
            $(".modal-body #edit_discount_amount").val(discount_amount);
            $(".modal-body #edit_valid_from").val(valid_from);
            $(".modal-body #edit_valid_until").val(valid_until);
            $('.modal-body #edit_usage_limit').val(usage_limit).attr("selected", "selected");
            $(".modal-body #edit_user_limit_per_user").val(user_limit_per_user);
            $(".modal-body .imagePreviewModel").attr("src", image);
            $(".modal-body #edit_old_image").val(image);
           
            if(is_type == 2){ 
                $('#edit_type_1').prop('checked', true);
            }else if(is_type == 1){
                $('#edit_type_2').prop('checked', true);
            }

            if(usage_limit == 1){
                $('#EditUserlimit').show();
            } else {
                $('#EditUserlimit').hide();
            }

        });

        function update_promocode() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#edit_promocode")[0]);

                var Edit_Id = $("#edit_id").val();
                var url = '{{ route('admin.promocode.update', ':id') }}';
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
                        get_responce_message(resp, 'edit_promocode', "{{ route('admin.promocode.index') }}");
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

        function change_status(id, status) {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var url = `{{ route('admin.promocode.show', '') }}/${id}`;

                $.ajax({
                    type: "GET",
                    url: url,
                    data:{ status: status },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(resp) {
                        $("#dvloader").hide();

                        if (resp.status == 200) {
                            get_responce_message(resp, '', '{{ route("admin.promocode.index") }}');
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
