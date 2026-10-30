@extends('admin.layout.page-app')
@section('page_title', __('label.attributes'))
@section('tab_title', __('label.attributes'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.attributes')}} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.category.index') }}">{{__('label.category')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.attributes')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.category.index') }}" class="btn btn-default mw-120" style="margin-top: -14px;">{{__('label.category')}}</a>
                </div>
                {{-- <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.attributes.create') }}" class="btn btn-default mw-120" style="margin-top: -14px;">{{__('label.add_attributes')}}</a>
                </div> --}}
            </div>
        
            <!-- Search && Table -->
            <div class="page-search mb-3">
                <div class="input-group" title="Search">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                    </div>
                    <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                </div>
              
               
                <div class="sorting mr-3 w-50">
                    <label>{{ __('label.sort_by') }}</label>
                    <select class="form-control" name="input_type" id="input_type">
                        <option value="all">{{__('label.all_type')}}</option>
                        <option value="1">{{__('label.text')}}</option>
                        <option value="2">{{__('label.numeric')}}</option>
                        <option value="3">{{__('label.dropdown')}}</option>
                        <option value="4">{{__('label.radio_button')}}</option>
                        <option value="5">{{__('label.checkbox')}}</option>
                        <option value="6">{{__('label.file_upload')}}</option>
                    </select>
                </div>
            </div>
         
            <div class="table-responsive table">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th> {{__('label.#')}} </th>
                            <th> {{__('label.icon')}} </th>
                            <th> {{__('label.name')}} </th>
                            <th> {{__('label.type')}} </th>
                            <th> {{__('label.is_required')}} </th>
                            <th> {{__('label.option_value')}} </th>
                            <th>{{__('label.status')}} </th>
                            {{-- <th> {{__('label.action')}} </th> --}}
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(function() {
                var table = $('#datatable').DataTable({
                    ...dataTableDefaults,
                    ajax: {
                        url:'{{ route("admin.category.attributes.index", [$category_id]) }}',
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
                            data: 'icon',
                            name: 'icon',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                return "<a href='" + data + "' target='_blank' title='Watch'><img src='" + data + "' class='img-thumbnail' style='height:55px; width:55px'></a>";
                            },
                        },
                        {
                            data: 'title',
                            name: 'title',
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return data;
                                } else {
                                    return "-";
                                }
                            }
                        },
                        {
                            data: 'field_type',
                            name: 'field_type',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                if (data == 1) {
                                    return "{{__('label.text')}}";
                                } else if (data == 2) {
                                    return "{{__('label.numeric')}}";
                                } else if (data == 3) {
                                    return "{{__('label.dropdown')}}";
                                } else if (data == 4) {
                                    return "{{__('label.radio_button')}}";
                                } else if (data == 5) {
                                    return "{{__('label.checkbox')}}";
                                } else if (data == 6) {
                                    return "{{__('label.file_upload')}}";
                                } else {
                                    return "-";
                                }
                            }
                        },
                        {
                            data: 'is_required',
                            name: 'is_required',
                            render: function(data) {
                                return data == 1 ? '<b class="primary-color">{{__("label.yes")}}</b>' : (data == 0 ? '<b class="primary-color">{{__("label.no")}}</b>' : '-');
                            }
                        },
                        {
                            data: 'option',
                            name: 'option',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        // {
                        //     data: 'action',
                        //     name: 'action',
                        //     orderable: false,
                        //     searchable: false
                        // },
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
        function change_status(id) {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var url = `{{ route('admin.attributes.show', '') }}/${id}`;

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