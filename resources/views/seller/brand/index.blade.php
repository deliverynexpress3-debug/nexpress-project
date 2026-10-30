@extends('seller.layout.page-app')
@section('page_title', __('label.brand'))
@section('tab_title', __('label.brand'))
@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.brand') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.brand') }}
                        </li>
                    </ol>
                </div>

            </div>

            <!-- Add Brand -->
            @if(Setting_Data()['brand_access'] ==1)
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{ __('label.add_brand') }}</h5>
                <div class="card-body">
                    <form id="brand" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                       
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-row">
                                   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('label.brand_name') }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control required"
                                                placeholder="{{ __('label.enter_brand_name') }}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>{{ __('label.brand_description') }}</label>
                                            <textarea name="description" class="form-control" rows="1" placeholder="{{ __('label.description_here') }}"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="ml-3">{{ __('label.logo') }}<span class="text-danger">*</span></label>
                                            <div class="avatar-upload ml-3">
                                                <div class="avatar-edit">
                                                    <input type='file' name="logo" id="imageUpload"
                                                        accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload" title="Select File"></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <img src="{{ asset('assets/imgs/upload_img.png') }}" alt="upload_img.png"
                                                        id="imagePreview">
                                                </div>
                                            </div>
                                            <label class="mt-3 ml-3 text-gray">{{ __('label.image_note') }}</label>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                           
                        </div>

                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120"
                                onclick="save_brand()">{{ __('label.save') }}</button>
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
                 
                </div>
                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th> {{ __('label.#') }} </th>
                                <th> {{ __('label.logo') }} </th>
                                <th> {{ __('label.brand_name') }} </th>
                                <th> {{ __('label.brand_status') }} </th>
                                @if(Setting_Data()['brand_access'] ==1)
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
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.edit_brand') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_brand" autocomplete="off">
                            <div class="modal-body">
                               
                                <div class="form-row">
                                    <div class="col-md-8">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ __('label.brand_name') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" name="name" id="edit_name" class="form-control"
                                                        placeholder="{{ __('label.enter_brand_name') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{__('label.brand_description')}}</label>
                                                    <textarea name="description" id="edit_description" class="form-control" rows="1" placeholder="{{__('label.description_here')}}"></textarea>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="ml-5">{{ __('label.logo') }}<span
                                                    class="text-danger">*</span></label>
                                            <div class="avatar-upload ml-5">
                                                <div class="avatar-edit">
                                                    <input type='file' name="logo" id="imageUploadModel"
                                                        accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUploadModel" title="Select File"></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <img src="" alt="upload_img.png" id="imagePreviewModel">
                                                </div>
                                            </div>
                                            <label class="mt-3 ml-5 text-gray">{{ __('label.image_note') }}</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="old_logo" id="edit_old_logo">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                    onclick="update_brand()">{{ __('label.update') }}</button>
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
        $('#parent_category_id, #input_industries_id, #industries_id, #edit_industries_id').select2();

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('seller.brand.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                       
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'logo',
                        name: 'logo',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return "<a href='" + data +
                                "' target='_blank' title='Watch'><img src='" + data +
                                "' class='img-thumbnail' style='height:55px; width:55px'></a>";
                        },
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
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    @if(Setting_Data()['brand_access'] ==1)
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    @endif
                   
                ],
            });
           
            $('#input_search').keyup(function() {
                table.draw();
            });

        });

        function save_brand() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#brand")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('seller.brand.store') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'brand', '{{ route('seller.brand.index') }}');
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

        $(document).on("click", ".edit_brand", function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            var logo = $(this).data('logo');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_name").val(name);
            $(".modal-body #edit_description").val(description);
            $(".modal-body #imagePreviewModel").attr("src", logo);
            $(".modal-body #edit_old_logo").val(logo);

    
           
        });

        function update_brand() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#edit_brand")[0]);

                var Edit_Id = $("#edit_id").val();
                var url = '{{ route('seller.brand.update', ':id') }}';
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
                        get_responce_message(resp, 'edit_brand', "{{ route('seller.brand.index') }}");
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
                var url = `{{ route('admin.brand.show', '') }}/${id}`;

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
                                $('#' + id).text('{{ __('label.show') }}').removeClass('status-inactive')
                                    .addClass('status-active');
                            } else {
                                $('#' + id).text('{{ __('label.hide') }}').removeClass('status-active')
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
