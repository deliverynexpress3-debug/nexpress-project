@extends('admin.layout.page-app')
@section('page_title', __('label.delivery_instructor'))
@section('tab_title', __('label.delivery_instructor'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')
        
        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.delivery_instructor')}}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.delivery_instructor')}}</li>
                    </ol>
                </div>
               
            </div>

            <!-- Add Type -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{ __('label.add_delivery_instructor') }}</h5>
                <div class="card-body">
                    <form id="delivery_instructor" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <div class="form-row">
                            
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>{{ __('label.title') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" placeholder="{{ __('label.enter_name') }}" autofocus>
                                </div>
                            </div>
                          
                            <div class="col-md-2">
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
                            <button type="button" class="btn btn-default mw-120" onclick="save_instructor()">{{ __('label.save') }}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>


            <!-- Search && Table -->
            <div class="card custom-border-card mt-3">
                <div class="page-search mb-3">
                    <div class="input-group" title="{{__('label.search')}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                   
                     
                </div>

                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th>{{__('label.#')}}</th>
                                <th>{{__('label.image')}}</th>
                                <th>{{__('label.title')}}</th>
                                <th>{{__('label.status')}}</th>
                                <th>{{__('label.action')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Model -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_delivery_instructor')}}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_delivery_instructor" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="old_image" id="edit_old_image">
                                <div class="form-row">
                                    <div class="col-md-12">
                                       
                                        <div class="form-group">
                                            <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="edit_title" class="form-control" placeholder="{{__('label.enter_name')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.image')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type="file" name="image" class="imageUploadModel" accept=".png, .jpg, .jpeg" hidden="">
                                                <label class="avatar-preview drop-area">
                                                    <img src="{{ asset('assets/imgs/no_img.png') }}" class="imagePreviewModel">
                                                </label>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                            
                              
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_instructor()">{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
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
    <!-- Sortable -->
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <script>
        sidebar_down(550);

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.deliveryinstructor.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return "<a href='" + data + "' target='_blank'><img src='" + data + "' class='img-thumbnail' style='height:55px; width:55px'></a>";
                        },
                    },
                    
                    {
                        data: 'title',
                        name: 'title',
                        render: function(data) {
                            return data ? data : "-";
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

        function save_instructor() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#delivery_instructor")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.deliveryinstructor.store") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'delivery_instructor', '{{ route("admin.deliveryinstructor.index") }}');
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
                var url = `{{ route('admin.deliveryinstructor.show', '') }}/${id}`;

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
                            sortableBTN();
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

        $(document).on("click", ".edit_delivery_instructor", function() {
            var id = $(this).data('id');
            var title = $(this).data('title');
            var image = $(this).data('image');
       
            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_title").val(title);
            $(".modal-body .imagePreviewModel").attr("src", image);
            $(".modal-body #edit_old_image").val(image);
          
        });

        function update_instructor() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#update_delivery_instructor")[0]);
                var Edit_Id = $("#edit_id").val();

                var url = '{{ route("admin.deliveryinstructor.update", ":id") }}';
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

                        if (resp.status == 200) {
                            $('#EditModel').modal('toggle');
                        }

                        get_responce_message(resp, 'update_delivery_instructor', '{{ route("admin.deliveryinstructor.index") }}');
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

     
    </script>
@endsection