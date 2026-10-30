@extends('seller.layout.page-app')
@section('page_title', __('label.sub_category'))
@section('tab_title', __('label.sub_category'))
@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.sub_category') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-9">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('label.sub_category') }}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('seller.category.index') }}" class="btn btn-default mw-120"
                        style="margin-top: -14px;">{{ __('label.category_list') }}</a>
                </div>
                <div class="col-sm-1 d-flex justify-content-start mb-3" title="{{ __('label.sortable') }}">
                    <button type="button" data-toggle="modal" data-target="#sortableModal" onclick="sortableBTN()"
                        class="btn btn-default" style="border-radius: 10px;">
                        <i class="fa-solid fa-sort fa-1x"></i>
                    </button>
                </div>
            </div>
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{ __('label.root_category') }} <span
                        class="subcategory-title btn-default py-1 px-3"
                        style="border-radius:0px;">{{ $category['name'] ?? '-' }}</span></h5>
            </div>

            <!-- Search && Table -->
            <div class="card amenity-border-card mt-3">
                <div class="page-search mb-3">
                    <div class="input-group" title="Search">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control"
                            placeholder="{{ __('label.search') }}" aria-label="Search"
                            aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
            <div class="table-responsive table">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th> {{ __('label.#') }} </th>
                            <th> {{ __('label.image') }} </th>
                            <th> {{ __('label.category_name') }} </th>
                            <th> {{ __('label.sub_category') }}</th>
                            <th> {{ __('label.attributes') }}</th>
                            <th> {{ __('label.status') }}</th>
                            @if(Setting_Data()['category_access'] ==1)
                            <th> {{ __('label.action') }} </th>
                            @endif
                           
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- EditModel -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.edit_category') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_category" autocomplete="off">
                            <div class="modal-body">
                               
                                <div class="form-row">
                                    <div class="col-md-8">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ __('label.category_name') }}<span class="text-danger">*</span></label>
                                                    <input type="text" name="name" id="edit_name" class="form-control"
                                                        placeholder="{{ __('label.enter_category_name') }}" autofocus>
                                                </div>
                                            </div>
                                           
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="ml-5">{{ __('label.image') }}<span
                                                    class="text-danger">*</span></label>
                                            <div class="avatar-upload ml-5">
                                                <div class="avatar-edit">
                                                    <input type='file' name="image" id="imageUpload"
                                                        accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload" title="Select File"></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <img src="" alt="upload_img.png" id="imagePreview">
                                                </div>
                                            </div>
                                            <label class="mt-3 ml-5 text-gray">{{ __('label.image_note') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="parent_category_id" id="edit_parent_category_id">
                                <input type="hidden" name="old_image" id="edit_old_image">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                    onclick="update_category()">{{ __('label.update') }}</button>
                                <button type="button" class="btn btn-cancel mw-120"
                                    data-dismiss="modal">{{ __('label.close') }}</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- sortableModal -->
            <div class="modal fade" id="sortableModal" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="sortableModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="sortableModalLabel">
                                {{ __('label.category_sortable_list') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                id="close">
                                <span aria-hidden="true" class="text-dark">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="imageListId">

                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form enctype="multipart/form-data" id="save_category_sortable">
                                @csrf
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                @if(Setting_Data()['category_access'] ==1)
                                <div class="w-100 text-center">
                                    <button type="button" class="btn btn-default mw-120"
                                        onclick="save_category_sortable()">{{ __('label.save') }}</button>
                                </div>
                                @endif
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $("#input_type").select2();
       
        const sortCategory = @json($sort_category);

        $(document).ready(function() {
            $(function() {
                var table = $('#datatable').DataTable({
                    ...dataTableDefaults,
                    ajax: {
                        url: "{{ route('seller.subcategory_list', [$category->id]) }}",
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
                            data: 'image',
                            name: 'image',
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
                            data: 'sub_category',
                            name: 'sub_category'
                        },
                        {
                            data: 'attributes',
                            name: 'attributes'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        @if(Setting_Data()['category_access'] ==1)
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
        });

        $(document).on("click", ".edit_category", function() {
            var id = $(this).data('id');
            var parent_category_id = $(this).data('parent_category_id');
            var name = $(this).data('name');
            var industries_id = $(this).data('industries_id');
            var image = $(this).data('image');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_parent_category_id").val(parent_category_id);
            $(".modal-body #edit_name").val(name);
            $(".modal-body #imagePreview").attr("src", image);
            $(".modal-body #edit_old_image").val(image);
            $(".modal-body input[name='industries_id']").each(function() {
                $(this).prop("checked", $(this).val() == industries_id);
            });

        });

        function update_category() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#edit_category")[0]);

                var Edit_Id = $("#edit_id").val();
                var url = '{{ route('seller.category.update', ':id') }}';
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

                        get_responce_message(resp, 'edit_category',
                            "{{ route('seller.subcategory_list', [$category->id]) }}");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError()
            }
        }

        // Sortable category
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

        // Sortable category get data 
        function sortableBTN() {
            $("#dvloader").show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: '{{ route('seller.subcategory_list', [$category->id]) }}',
                success: function(resp) {
                    $("#dvloader").hide();
                    $('#imageListId').html('');
                    for (var i = 0; i < sortCategory.length; i++) {

                        var data = '<div id="' + sortCategory[i].id +
                            '" class="listitemClass mb-2" style="background-color: #e9ecef;border: 1px solid black; cursor: s-resize;">' +
                            '<p class="m-2">' + sortCategory[i].name + '</p>' +
                            '</div>';

                        $('#imageListId').append(data);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // Sortable save
        function save_category_sortable() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#save_category_sortable")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('seller.category.sortable.save') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_category_sortable',
                            "{{ route('seller.subcategory_list', [$category->id]) }}");
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

        function child_change_status(id) {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var url = `{{ route('seller.category.child.status', '') }}/${id}`;

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
                                    location.reload();
                            } else {
                                $('#' + id).text('{{ __('label.hide') }}').removeClass('status-active')
                                    .addClass('status-inactive');
                                    location.reload();
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
