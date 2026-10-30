@extends('admin.layout.page-app')
@section('page_title', __('label.types'))
@section('tab_title', __('label.types'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.types')}}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-11">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.types')}}</li>
                    </ol>
                </div>
                <div class="col-sm-1 d-flex justify-content-start mb-3" title="{{__('label.sortable')}}">
                    <button type="button" data-toggle="modal" data-target="#sortableModal" onclick="sortableBTN()" class="btn btn-default" style="border-radius: 10px;">
                        <i class="fa-solid fa-sort fa-1x"></i>
                    </button>
                </div>
            </div>

            <!-- Add Type -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{ __('label.add_type') }}</h5>
                <div class="card-body">
                    <form id="type" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <div class="form-row">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('label.name') }}<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="{{ __('label.enter_name') }}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.background_color')}}</label>
                                    <div class="input-group colorpicker-component">
                                        <input type="text" id="hexcolor-1" class="form-control hexcolor" value="#000000" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                        <input type="color" id="colorpicker-1" name="bg_color" value="#000000" class="colorpicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-md-2">
                                <div class="form-group ml-3">
                                    <label>{{__('label.icon')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type="file" name="icon" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                        <label class="avatar-preview drop-area">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" class="imagePreview">
                                        </label>
                                    </div>
                                </div>
                               
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.banner_image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type="file" name="banner_image" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                        <label class="avatar-preview landscape-preview drop-area">
                                            <img src="{{asset('assets/imgs/upload_img_land.png')}}" class="imagePreview" id="imagePreviewLandscape">
                                        </label>
                                    </div>
                                
                                </div>
                                
                            </div>
                            
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="save_type()">{{ __('label.save') }}</button>
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
                                <th>{{__('label.icon')}}</th>
                                <th>{{__('label.banner_image')}}</th>
                                <th>{{__('label.name')}}</th>
                                <th>{{__('label.background_color')}}</th>
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
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_type')}}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_type" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="old_icon" id="edit_old_icon">
                                <input type="hidden" name="old_banner_image" id="edit_old_banner_image">
                                <div class="form-row">
                                    <div class="col-md-8">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                                    <input type="text" name="name" id="edit_name" class="form-control" placeholder="{{__('label.enter_name')}}">
                                                </div>
                                            </div>
                                        </div>
                                       
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{__('label.background_color')}}</label>
                                                    <div class="input-group colorpicker-component">
                                                        <input type="text" id="hexcolor-2" class="form-control hexcolor" value="#000000" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                                        <input type="color" id="colorpicker-2" name="bg_color" value="#000000" class="colorpicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                
                                                    <div class="form-group">
                                                        <label>{{__('label.banner_image')}}</label>
                                                        <div class="avatar-upload my-2 image-upload-wrapper">
                                                            <input type="file" name="banner_image" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                                            <label class="avatar-preview landscape-preview drop-area">
                                                                <img src="{{asset('assets/imgs/no_img.png')}}" class="imagePreviewLandscapeModel">
                                                            </label>
                                                        </div>
                                                    
                                                    </div>

                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group ml-3">
                                            <label>{{__('label.icon')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type="file" name="icon" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                                <label class="avatar-preview drop-area">
                                                    <img src="{{asset('assets/imgs/no_img.png')}}" class="imagePreviewModel">
                                                </label>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_type()">{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- sortableModal -->
            <div class="modal fade" id="sortableModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="sortableModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="sortableModalLabel">{{__('label.type_sortable_list')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true" class="text-dark">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="imageListId">
                                
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form enctype="multipart/form-data" id="save_type_sortable">
                                @csrf
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                <div class="w-100 text-center">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_type_sortable()">{{__('label.save')}}</button>
                                </div>
                            </form>
                        </div>
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
        // Color Picker
        $(document).ready(function() {
            // Event handler for color picker input change
            $('.colorpicker').on('input', function() {
                var target = $(this).attr('id').split('-')[1];
                $('#hexcolor-' + target).val(this.value.toUpperCase());
            });

            // Event handler for hex color input change
            $('.hexcolor').on('input', function() {
                var target = $(this).attr('id').split('-')[1];
                const hexPattern = /^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/;
                if (hexPattern.test(this.value)) {
                    $('#colorpicker-' + target).val(this.value);
                }
            });
        });

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.type.index') }}",
                    data: function(d) {
                        d.input_type = $('#input_type').val();
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'icon',
                        name: 'icon',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return "<a href='" + data + "' target='_blank'><img src='" + data + "' class='img-thumbnail' style='height:55px; width:55px'></a>";
                        },
                    },
                    {
                        data: 'banner_image',
                        name: 'banner_image',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            return "<a href='" + data + "' target='_blank'><img src='" + data + "' class='img-thumbnail' style='height:60px; width:auto; max-width:200px; object-fit:cover;'></a>";
                        },
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'bg_color',
                        name: 'bg_color',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            if (data) {
                                return `
                                <div class="bg-color-box" style="background-color: ${data};">
                                    ${data}
                                </div>`;
                            }
                            return "-";
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

            $('#input_type').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });

        function save_type() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#type")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.type.store") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'type', '{{ route("admin.type.index") }}');
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

        $(document).on("click", ".edit_type", function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var icon = $(this).data('icon');
            var banner_image = $(this).data('banner_image');
            var bg_color = $(this).data('bg_color');
        
            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_name").val(name);
            $(".modal-body #hexcolor-2").val(bg_color);
            $(".modal-body #colorpicker-2").val(bg_color);
            $(".modal-body .imagePreviewModel").attr("src", icon);
            $(".modal-body #edit_old_icon").val(icon);
            $(".modal-body .imagePreviewLandscapeModel").attr("src", banner_image);
            $(".modal-body #edit_old_banner_image").val(banner_image);
          
        });

        function update_type() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#update_type")[0]);
                var Edit_Id = $("#edit_id").val();

                var url = '{{ route("admin.type.update", ":id") }}';
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
                        get_responce_message(resp, 'update_type', '{{ route("admin.type.index") }}');
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
                var url = `{{ route('admin.type.show', '') }}/${id}`;

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
                            type_data = resp.active_types;  // fresh updated list
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
        // Sortable type
        $("#imageListId").sortable({
            update: function(event, ui) {
                getIdsOfImages();
            } //end update
        });
        function getIdsOfImages() {
            var values = [];
            $('.listitemClass').each(function(index) {
                values.push($(this).attr("id")
                    .replace("imageNo", ""));
            });
            $('#outputvalues').val(values);
        }
        var type_data = JSON.parse('<?php echo json_encode($data); ?>'); // Convert PHP to JS array
        function sortableBTN() {
            var listContainer = $('#imageListId');
            listContainer.html('');

            var htmlContent = ''; 
            for (var i = 0; i < type_data.length; i++) { 
                htmlContent += '<div id="'+ type_data[i].id +'" class="listitemClass mb-2" style="background-color: #e9ecef; border: 1px solid black; cursor: s-resize;">' +
                                '<p class="m-2">'+ type_data[i].name +'</p>' +
                            '</div>';
            }

            listContainer.append(htmlContent);
        }
        function save_type_sortable() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var formData = new FormData($("#save_type_sortable")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.type.sortable.save") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_type_sortable', '{{ route("admin.type.index") }}');
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