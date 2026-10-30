@extends('admin.layout.page-app')
@section('page_title', __('label.sales_reports'))
@section('tab_title', __('label.sales_reports'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.sales_reports') }}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-8">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.sales_reports') }}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <select name="seller_id" class="form-control" id="seller_id">
                            <option value="0">{{__('label.all_seller')}}</option>
                            @for ($i = 0; $i < count($seller); $i++) 
                            <option value="{{ $seller[$i]['id'] }}">
                                {{ $seller[$i]['user_name'] }} → {{ $seller[$i]['full_name'] }}
                            </option>
                            @endfor
                        </select>
                    </div>
                </div>

            </div>


            <!-- Chart -->
            <div class="row pl-3">
                <div class="col-12 col-xl-8 cart-bg">
                    <div class="video-box cart-bg">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-chart-column fa-lg mr-2"></i>{{__('label.seller_revenue_vs_commission')}}</h2>
                        </div>
                        <div class="summary-table-card">
                            <div id="Restaurant_Revenue_Chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="video-box cart-bg" style="padding: 25px 25px 66px 25px;">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-chart-pie fa-lg mr-2"></i>{{__('label.seller_cancellation_rate')}}</h2>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div id="Restaurant_Cancellation_Chart" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Most Salling Item -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{__('label.most_selling_items')}}</h5>
                <div class="page-search mb-3 mt-3">
                    <div class="col-9">
                        <label class="text-gray pt-2 font-weight-bold"><i class="fa-solid fa-circle-info fa-2xl mr-3"></i>{{__('label.only_the_following_data_will_be_captured_in_this_file')}}</label>
                    </div>
                    <div class="col-3">
                        <div class="d-flex justify-content-around">
                            <button id="ms_excel" class="btn btn-default"><i class="fa-sharp fa-solid fa-file-excel mr-2 font-weight-bold"></i>{{__('label.ms_excel')}}</button>
                            <button id="pdf" class="btn btn-default"><i class="fa-solid fa-file-pdf mr-2 font-weight-bold" style="font-size:18px"></i>{{__('label.pdf')}}</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th>{{__('label.#')}}</th>
                                <th>{{__('label.image')}}</th>
                                <th>{{__('label.name')}}</th>
                                <th>{{__('label.unit')}}</th>
                                <th>{{__('label.total_sold')}}</th>
                                <th>{{__('label.total_revenue')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        
                    </table>
                </div>
            </div>

            <!-- Least Salling Item -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{__('label.least_selling_items')}}</h5>
                <div class="page-search mb-3 mt-3">
                    <div class="col-9">
                        <label class="text-gray pt-2 font-weight-bold"><i class="fa-solid fa-circle-info fa-2xl mr-3"></i>{{__('label.only_the_following_data_will_be_captured_in_this_file')}}</label>
                    </div>
                    <div class="col-3">
                        <div class="d-flex justify-content-around">
                            <button id="ms_excel1" class="btn btn-default"><i class="fa-sharp fa-solid fa-file-excel mr-2 font-weight-bold"></i>{{__('label.ms_excel')}}</button>
                            <button id="pdf1" class="btn btn-default"><i class="fa-solid fa-file-pdf mr-2 font-weight-bold" style="font-size:18px"></i>{{__('label.pdf')}}</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped text-center table-bordered" id="datatable1">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th>{{__('label.#')}}</th>
                                <th>{{__('label.image')}}</th>
                                <th>{{__('label.name')}}</th>
                                <th>{{__('label.unit')}}</th>
                                <th>{{__('label.total_sold')}}</th>
                                <th>{{__('label.total_revenue')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                      
                    </table>
                </div>
            </div>


        </div>

    </div>
@endsection

@section('pagescript')
<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- Export Files LInk (PDF, CSV, MS-Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script>
    $("#seller_id").select2();
    $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.sales.report.top_items' ,['id' => $seller_id]) }}",
                    data: function(d) {
                        d.seller_id = $('#seller_id').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
					data: 'item.portrait_img',
					name: 'item.portrait_img',
					orderable: false,
					searchable: false,
					render: function(data) {
                        return `<a href='${data}' target='_blank'><img src='${data}' class='rounded-circle' style='height:55px; width:55px'></a>`;
					},
				},
                {
                    data: 'item.name',
                    name: 'item.name',
                    render: function(data) {
                        return data 
                            ? (data.length > 85 ? data.slice(0, 85) + '...' : data)
                            : "-";
                    }
                },
				
                {
                    data: 'item',
                    name: 'item',
                    render: function(data) {
                        if (data) {
                            let unit = data.unit ? data.unit : '';
                            let perunit = data.per_unit ? data.per_unit : '';
                            return perunit + ' ' + unit;
                        }
                        return "-";
                    }
                },
                {
                    data: 'total_sold',
                    name: 'total_sold',
                    render: function(data) {
                        return data ? data : "0";
                    }
                },
                {
                    data: 'total_revenue',
                    name: 'total_revenue',
                    render: function(data) {
                        return data ? data : "0";
                    }
                },
                ],
                buttons: [{
                        extend: 'excel',
                        filename: "{{App_Name()}} - {{__('label.most_selling_items')}}",
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5]
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row:first c', sheet).attr('s', '2');
                        },
                    },
                    {
                        extend: 'pdf',
                        title: "{{App_Name()}} - {{__('label.most_selling_items')}}",
                        filename: "{{App_Name()}} - {{__('label.most_selling_items')}}",
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5]
                        },
                        customize: function(doc) {
                            doc.styles.tableHeader.fontSize = 10;
                            doc.defaultStyle.fontSize = 8;
                            doc.content[1].table.widths = ['5%', '30%', '20%', '20%', '15%'];
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
            $('#pdf').on('click', function() {

                var Demo_Mode = '{{ Demo_Mode() }}';
                if(Demo_Mode == 1){
                    var table = $('#datatable').DataTable();
                    table.button('1').trigger();
                } else {
                    showError();
                }
            });
            $('#seller_id').change(function() {
                table.draw();
            });

            var table1 = $('#datatable1').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.sales.report.least_items' ,['id' => $seller_id]) }}",
                    data: function(d) {
                        d.seller_id = $('#seller_id').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
					data: 'item.portrait_img',
					name: 'item.portrait_img',
					orderable: false,
					searchable: false,
					render: function(data) {
                        return `<a href='${data}' target='_blank'><img src='${data}' class='rounded-circle' style='height:55px; width:55px'></a>`;
					},
				},
                {
                    data: 'item.name',
                    name: 'item.name',
                    render: function(data) {
                        return data 
                            ? (data.length > 85 ? data.slice(0, 85) + '...' : data)
                            : "-";
                    }
                },
				
                {
                    data: 'item',
                    name: 'item',
                    render: function(data) {
                        if (data) {
                            let unit = data.unit ? data.unit : '';
                            let perunit = data.per_unit ? data.per_unit : '';
                            return perunit + ' ' + unit;
                        }
                        return "-";
                    }
                },
                {
                    data: 'total_sold',
                    name: 'total_sold',
                    render: function(data) {
                        return data ? data : "0";
                    }
                },
                {
                    data: 'total_revenue',
                    name: 'total_revenue',
                    render: function(data) {
                        return data ? data : "0";
                    }
                },
                ],
                buttons: [{
                        extend: 'excel',
                        filename: "{{App_Name()}} - {{__('label.least_selling_items')}}",
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5]
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row:first c', sheet).attr('s', '2');
                        },
                    },
                    {
                        extend: 'pdf',
                        title: "{{App_Name()}} - {{__('label.least_selling_items')}}",
                        filename: "{{App_Name()}} - {{__('label.least_selling_items')}}",
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5]
                        },
                        customize: function(doc) {
                            doc.styles.tableHeader.fontSize = 10;
                            doc.defaultStyle.fontSize = 8;
                            doc.content[1].table.widths = ['5%', '30%', '20%', '20%', '15%'];
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
            $('#ms_excel1').on('click', function() {
                
                var Demo_Mode = '{{ Demo_Mode() }}';
                if(Demo_Mode == 1){
                    var table = $('#datatable1').DataTable();
                    table.button('0').trigger();
                } else {
                    showError();
                }
            });
            $('#pdf1').on('click', function() {

                var Demo_Mode = '{{ Demo_Mode() }}';
                if(Demo_Mode == 1){
                    var table = $('#datatable1').DataTable();
                    table.button('1').trigger();
                } else {
                    showError();
                }
            });
            $('#seller_id').change(function() {
                table1.draw();
            });

            getChartData(0);
        });

     // restaurant change
     $('#seller_id').on('change', function () {
            let sellerId = $(this).val();
            getChartData(sellerId);
        });
        function getChartData (sellerId) {
            $.ajax({
                url: "{{ route('admin.report.sellers') }}",
                type: "GET",
                data: { seller_id: sellerId },
                success: function (res) {
                    
                    // Update stacked bar chart
                    stackedBarChart.updateOptions({
                        series: [
                            { name: '{{__("label.revenue")}}', data: res.total_revenue ?? [] },
                            { name: '{{__("label.commission")}}', data: res.total_comission ?? [] },
                        ]
                    });

                    // Payment chart
                    chartOrderCancel.updateOptions({
                        series: [
                            res.orders_parcebtage ?? 0,
                            res.cancel_orders_parcebtage ?? 0
                        ],
                        labels: [
                            "{{ __('label.total_orders') }} ("+(res.total_orders ?? 0)+")",
                            "{{ __('label.cancelled_orders') }} ("+(res.total_cancel_orders ?? 0)+")"
                        ]
                    });
                },
                error: function (xhr) {
                    console.error("AJAX error:", xhr.responseText);
                }
            });
        }

        // stacked bar chart options
        let stackedBarOptions = {
            chart: {
                type: 'bar',
                height: 400,
                stacked: true, // enable stacked bars
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    endingShape: 'rounded'
                }
            },
            colors: ['#6a11cb', '#f7971e'], // Revenue, Commission, Total Amount
            series: [
                { name: '{{__("label.revenue")}}', data: [] },
                { name: '{{__("label.commission")}}', data: [] },
            ],
            xaxis: {
                categories: [
                    '{{__("label.jan")}}', '{{__("label.feb")}}', '{{__("label.mar")}}', '{{__("label.apr")}}',
                    '{{__("label.may")}}', '{{__("label.jun")}}', '{{__("label.jul")}}', '{{__("label.aug")}}',
                    '{{__("label.sep")}}', '{{__("label.oct")}}', '{{__("label.nov")}}', '{{__("label.dec")}}'
                ],
                labels: { style: { fontSize: '14px', fontWeight: 'bold' } }
            },
            yaxis: {
                labels: { style: { fontSize: '14px', fontWeight: 'bold' } },
                title: { text: 'Amount' }
            },
            legend: {
                position: 'bottom',
                fontSize: '16px',
                fontWeight: 'bold',
                labels: { colors: ['#333'], useSeriesColors: false }
            },
            tooltip: {
                theme: 'dark',
                y: { formatter: function(val) { return val; } }
            }
        };
        let stackedBarChart = new ApexCharts(document.querySelector("#Restaurant_Revenue_Chart"), stackedBarOptions);
        stackedBarChart.render();

        // order cancellation
        var orderCancelOptions = {
            chart: {
                type: 'pie',
                height: 350
            },
            series: [ 0, 0],
            labels: [
                "{{ __('label.total_orders') }} (0)",
                "{{ __('label.cancelled_orders') }} (0)"
            ],
            colors: ['#6a11cb', '#f7971e'], // Total: purple, Cancelled: orange
            legend: {
                position: 'bottom',
                fontSize: '14px'
            },
            tooltip: {
                y: {
                    formatter: function(val) { return val + "%" }
                }
            }
        };
        var chartOrderCancel = new ApexCharts(document.querySelector("#Restaurant_Cancellation_Chart"), orderCancelOptions);
        chartOrderCancel.render();

</script>
@endsection
