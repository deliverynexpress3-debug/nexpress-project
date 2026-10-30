@extends('admin.layout.page-app')
@section('page_title', __('label.zones'))
@section('tab_title', __('label.zones'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.zones') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.zones') }}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.zones.create') }}" class="btn btn-default mw-120" style="margin-top: -14px;">{{__('label.add_zones')}}</a>
                </div>
            </div>

            <!-- Search && Table -->
            {{-- <div class="card custom-border-card mt-3"> --}}
                <div class="page-search mb-3">
                    <div class="input-group" title="Search">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control" placeholder="{{ __('label.search') }}"
                            aria-label="Search" aria-describedby="basic-addon1">
                    </div>

                </div>
                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th> {{ __('label.#') }} </th>
                                <th> {{ __('label.zone_name') }} </th>
                                <th class="text-left"> {{ __('label.zipcode') }} </th>
                                <th> {{ __('label.status') }} </th>
                                <th> {{ __('label.action') }} </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            {{-- </div> --}}

        
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        sidebar_down(700);
        
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.zones.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                      
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },

                    {
                        data: 'zone_name',
                        name: 'zone_name',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
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
        function change_status(id) {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var url = `{{ route('admin.zones.show', '') }}/${id}`;

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
