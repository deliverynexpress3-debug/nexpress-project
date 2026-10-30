@extends('admin.layout.page-app')
@section('page_title', __('label.delivery_boys'))
@section('tab_title', __('label.delivery_boys'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.delivery_boys')}}</h1>

            <div class="border-bottom row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.delivery_boys')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.deliveryboy.create') }}" class="btn btn-default mw-120" style="margin-top: -14px;">{{__('label.add_delivery_boy')}}</a>
                </div>
            </div>

            <!-- Export Files -->
            <div class="page-search mb-3 mt-3">
                <div class="col-8">
                    <label class="text-gray pt-2 font-weight-bold"><i class="fa-solid fa-circle-info fa-2xl mr-3"></i>{{__('label.only_the_following_data_will_be_captured_in_this_file')}}</label>
                </div>
                <div class="col-4">
                    <div class="d-flex justify-content-around">
                        <button id="ms_excel" class="btn btn-default"><i class="fa-sharp fa-solid fa-file-excel mr-2 font-weight-bold"></i>{{__('label.ms_excel')}}</button>
                        <button id="csv" class="btn btn-default"><i class="fa-solid fa-file-csv mr-2 font-weight-bold" style="font-size:18px"></i>{{__('label.csv')}}</button>
                        <button id="pdf" class="btn btn-default"><i class="fa-solid fa-file-pdf mr-2 font-weight-bold" style="font-size:18px"></i>{{__('label.pdf')}}</button>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="page-search mb-3">
                <div class="input-group" title="Search">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                    </div>
                    <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                </div>
                
            
                <div class="sorting w-50">
                    <label>{{__('label.sort_by')}}</label>
                    <select class="form-control" id="input_status_type">
                        <option value="all">{{__('label.all_status')}}</option>
                        <option value="0">{{__('label.unavailable')}}</option>
                        <option value="1">{{__('label.available')}}</option>
                        {{-- <option value="2">{{__('label.busy')}}</option>
                        <option value="3">{{__('label.on_delivery')}}</option> --}}
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th>{{__('label.#')}}</th>
                            <th>{{__('label.image')}}</th>
                            <th>{{__('label.user_name')}}</th>
                            <th>{{__('label.full_name')}}</th>
                            <th>{{__('label.email')}}</th>
                            <th>{{__('label.mobile')}}</th>
                            <th>{{__('label.name')}}</th>
                            <th>{{__('label.contact')}}</th>
                            {{-- <th>{{__('label.serviceable_zones')}}</th> --}}
                            <th>{{__('label.status')}}</th>
                            {{-- <th>{{__('label.req_status')}}</th> --}}
                            <th>{{__('label.type')}}</th>
                            <th>{{__('label.login_type_opt_google_apple_normal')}}</th>
                            <th>{{__('label.action')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')

    <!-- Export Files LInk (PDF, CSV, MS-Excel) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>

    <script>
        sidebar_down(700);
        
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.deliveryboy.index') }}",
                    data: function(d) {
                        d.input_status_type = $('#input_status_type').val();
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },

                    {
						data: 'image',
						name: 'image',
						orderable: false,
						searchable: false,
						render: function(data) {
                            return `<a href='${data}' target='_blank'>
                                        <img src='${data}' class='rounded-circle' style='height:60px; width:60px'>
                                    </a>`;
						},
					},
                    {
						data: 'user_name',
						name: 'user_name',
                        orderable: false,
                        searchable: false,
                        visible: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
						
					},
					{
						data: 'full_name',
						name: 'full_name',
                        orderable: false,
                        searchable: false,
                        visible: false,
						render: function(data) {
                            return data ? data : "-";
                        }
					},
                    {
                        data: 'email',
                        name: 'email',
                        orderable: false,
                        searchable: false,
                        visible: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'mobile_number',
                        name: 'mobile_number',
                        orderable: false,
                        searchable: false,
                        visible: false,
                        render: function(data, type, full) {
                            return data ? (full.country_code ? `${full.country_code} ${data}` : data) : "-";
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `<div style="text-align: center;">${row.full_name || ''} <br><span style="font-size: 14px; font-weight: 600;">${row.user_name || ''}</span>`;
                        }
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(data, type, row) {
                            return `<div style="text-align: center;"> ${row.country_code || ''} ${row.mobile_number || ''}<br><span style="font-size: 14px; font-weight: 600;">${row.email || ''}</span>`;
                        }
                    },
                    // {
                    //     data: 'zone_id',
                    //     name: 'zone_id',
                    //     orderable: false,
                    //     searchable: false,
                    // },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                       
                    },
                    // {
                    //     data: 'req_status',
                    //     name: 'req_status',
                    //     orderable: false,
                    //     searchable: false,
                       
                    // },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data) {
                            if (data == 1) {
                                return "<i class='fa-solid fa-mobile-screen-button fa-3x'></i>";
                            } else if (data == 2) {
                                return "<i class='fa-brands fa-google fa-3x'></i>";
                            } else if (data == 3) {
                                return "<i class='fa-brands fa-apple fa-3x'></i>";
                            } else if (data == 4) {
                                return "<i class='fa-solid fa-lock fa-3x'></i>";
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'type',
                        name: 'Login Type',
                        orderable: false,
                        searchable: false,
                        visible: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    { data: 'action', name: 'action', width: '10%', orderable: false, searchable: false },
                ],
                buttons: [{
                        extend: 'excel',
                        filename: "{{App_Name()}} - {{__('label.delivery_boys')}}",
                        exportOptions: {
                            columns: [
                                0, 2, 3, 4, 5, 8,12, 
                            ],
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row:first c', sheet).attr('s', '2');
                        },
                    },
                    {
                        extend: 'csv',
                        filename: "{{App_Name()}} - {{__('label.delivery_boys')}}",
                        exportOptions: {
                            columns: [
                                0, 2, 3, 4, 5, 8,12, 
                            ],
                        },
                    },
                    {
                        extend: 'pdf',
                        title: "{{App_Name()}} - {{__('label.delivery_boys')}}",
                        filename: "{{App_Name()}} - {{__('label.delivery_boys')}}",
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [
                                0, 2, 3, 4, 5, 8,12, 
                            ],
                            
                        },
                        customize: function(doc) {
                            doc.styles.tableHeader.fontSize = 10;
                            doc.defaultStyle.fontSize = 8;
                            doc.content[1].table.widths = ['5%', '15%', '15%', '15%', '15%', '10%','10%'];
                            doc.content[1].layout = "borders";
                            doc.styles.title.fontSize = 22;
                            doc.styles.title.alignment = 'center';
                            doc.defaultStyle.alignment = 'center';

                            // Create a header
                            doc['header'] = (function(page, pages) {
                                return {
                                    columns: [{
                                            alignment: 'left',
                                            bold: true,
                                            text: "{{App_Name()}}",
                                        },
                                        {
                                            alignment: 'right',
                                            bold: true,
                                            text: ['Total Page ', {
                                                text: pages.toString()
                                            }],
                                        }
                                    ],
                                    margin: [20, 20],
                                }
                            });
                            // Create a footer
                            doc['footer'] = (function(page, pages) {
                                return {
                                    columns: [{
                                        alignment: 'center',
                                        bold: true,
                                        text: ['Page ', {
                                            text: page.toString()
                                        }, ' of ', {
                                            text: pages.toString()
                                        }],
                                    }],
                                }
                            });
                        }
                    }
                ],
            });

            $('#ms_excel').on('click', function() {
                
                var Demo_Mode = '{{ Demo_Mode() }}';
                if(Demo_Mode == 1){
                    var table = $('#datatable').DataTable();
                    table.button('0').trigger();
                } else {
                    showError();
                }
            });
            $('#csv').on('click', function() {

                var Demo_Mode = '{{ Demo_Mode() }}';
                if(Demo_Mode == 1){
                    var table = $('#datatable').DataTable();
                    table.button('1').trigger();
                } else {
                    showError();
                }
            });
            $('#pdf').on('click', function() {

                var Demo_Mode = '{{ Demo_Mode() }}';
                if(Demo_Mode == 1){
                    var table = $('#datatable').DataTable();
                    table.button('2').trigger();
                } else {
                    showError();
                }
            });

            $('#input_status_type').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });

        function change_status(id ,status) {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var url = `{{ route('admin.deliveryboy.show', ':id') }}`.replace(':id', id);
            
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
                            get_responce_message(resp, '', '{{ route("admin.deliveryboy.index") }}');
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
        // function update_status() {

        //     var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        //     if (Demo_Mode == 1) {

        //         $("#dvloader").show();
        //         var formData = new FormData($("#change_status")[0]);

        //         var Edit_Id = $("#edit_id").val();
        //         var url = '{{ route('admin.deliveryboy.change_status', ':id') }}';
        //         url = url.replace(':id', Edit_Id);
               
        //         $.ajax({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             enctype: 'multipart/form-data',
        //             type: 'POST',
        //             url: url,
        //             data: formData,
        //             cache: false,
        //             contentType: false,
        //             processData: false,
        //             success: function(resp) {
        //                 $("#dvloader").hide();
        //                 $('#EditModel').modal('toggle');
                       
        //                 get_responce_message(resp, 'change_status', "{{ route('admin.deliveryboy.index') }}");
        //             },
        //             error: function(XMLHttpRequest, textStatus, errorThrown) {
        //                 $("#dvloader").hide();
        //                 toastr.error(errorThrown, textStatus);
        //             }
        //         });
        //     } else {
        //         showError();
        //     }
        // }
    </script>
@endsection