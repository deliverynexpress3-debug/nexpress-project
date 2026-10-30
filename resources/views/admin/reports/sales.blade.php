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
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-line fa-lg mr-2"></i>{{__('label.sales_activity_overview')}}</h2>
                    </div>
                    <div class="row mt-2 mb-2">
                        <div class="col-12 col-sm-12">
                            <Button id="year" class="btn btn-default mr-3">{{__('label.this_year')}}</Button>
                            <Button id="month" class="btn btn-default mr-3">{{__('label.this_month')}}</Button>
                            <Button id="week" class="btn btn-default mr-3">{{__('label.this_week')}}</Button>
                            <Button id="today" class="btn btn-default mr-3">{{__('label.today')}}</Button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div id="OrdersChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="video-box cart-bg" style="padding: 25px 25px 66px 25px;">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-chart-pie fa-lg mr-2"></i>{{__('label.orders_by_payment_method')}}</h2>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div id="Order_Payment_Method"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Most Profitable Items -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{__('label.most_profitable_items')}}</h5>
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
                    filename: "{{App_Name()}} - {{__('label.most_profitable_items')}}",
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
                    title: "{{App_Name()}} - {{__('label.most_profitable_items')}}",
                    filename: "{{App_Name()}} - {{__('label.most_profitable_items')}}",
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
        getChartData(0);
    });

    // restaurant change
    $('#seller_id').on('change', function () {
        let sellerId = $(this).val();
        getChartData(sellerId);
    });
        
    function getChartData (sellerId) {
        $.ajax({
            url: "{{ route('admin.sales.report') }}",
            type: "GET",
            data: { seller_id: sellerId },
            success: function (res) {
                
                // Safe access helpers
                const yearlyData = res.yearly_sales?.sum || [];
                const monthlyData = res.monthly_sales?.sum || [];
                const weeklyData = res.weekly_sales?.sum || [];
                const weeklyLabels = res.weekly_sales?.labels || [];
                const dailyData = res.daily_sales?.sum || [];
                const dailyLabels = res.daily_sales?.labels || [];
                // Update main chart (default: yearly)
                chart.updateOptions({
                    series: [{ name: 'Orders', data: yearlyData }],
                    xaxis: { categories: [
                        '{{__("label.jan")}}','{{__("label.feb")}}','{{__("label.mar")}}','{{__("label.apr")}}',
                        '{{__("label.may")}}','{{__("label.jun")}}','{{__("label.jul")}}','{{__("label.aug")}}',
                        '{{__("label.sep")}}','{{__("label.oct")}}','{{__("label.nov")}}','{{__("label.dec")}}'
                    ] }
                });
                // Year toggle
                $("#year").off().on("click", function() {
                    chart.updateOptions({
                        series: [{ name: 'Orders', data: yearlyData }],
                        xaxis: { categories: [
                            '{{__("label.jan")}}', '{{__("label.feb")}}', '{{__("label.mar")}}', '{{__("label.apr")}}',
                            '{{__("label.may")}}', '{{__("label.jun")}}', '{{__("label.jul")}}', '{{__("label.aug")}}',
                            '{{__("label.sep")}}', '{{__("label.oct")}}', '{{__("label.nov")}}', '{{__("label.dec")}}'
                        ] }
                    });
                });
                // Month toggle
                $("#month").off().on("click", function() {
                    chart.updateOptions({
                        series: [{ name: 'Orders', data: monthlyData }],
                        xaxis: { categories: Array.from({length: monthlyData.length}, (_, i) => i+1) }
                    });
                });
                // Week toggle
                $("#week").off().on("click", function() {
                    chart.updateOptions({
                        series: [{ name: 'Orders', data: weeklyData }],
                        xaxis: { categories: weeklyLabels }
                    });
                });
                // Today toggle
                $("#today").off().on("click", function() {
                    chart.updateOptions({
                        series: [{ name: 'Orders', data: dailyData }],
                        xaxis: { categories: dailyLabels.map(h => h + ":00") }
                    });
                });
                // Payment chart
                chart_payment.updateOptions({
                    series: [
                        res.cash_percentage ?? 0,
                        res.online_percentage ?? 0
                    ],
                    labels: [
                        "{{ __('label.cod') }} ("+(res.cash_orders ?? 0)+")",
                        "{{ __('label.online') }} ("+(res.online_orders ?? 0)+")"
                    ]
                });
            },
            error: function (xhr) {
                console.error("AJAX error:", xhr.responseText);
            }
        });
    }
    // Init charts
    var options = {
        chart: {
            type: 'line',
            height: 350,
            toolbar: { show: false }
        },
        hover: {
            mode: 'nearest',
            intersect: true,
            animationDuration: 400,
        },
        series: [{ name: 'Orders', data: [] }],   // default empty
        xaxis: { categories: [] },  
        colors: ['#4e45b8'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 5
        }
    };
    var chart = new ApexCharts(document.querySelector("#OrdersChart"), options);
    chart.render();
    var Payment_options = {
        chart: {
            type: 'pie',
            height: 350
        },
        series: [0, 0],
        labels: [
            "{{ __('label.cash_on_delivery') }} ({{ $cash_orders }})",
            "{{ __('label.online') }} ({{ $online_orders }})"
        ],
        colors: ['#4e45b8', '#f39c12'],
        legend: {
            position: 'bottom'
        }
    };
    var chart_payment = new ApexCharts(document.querySelector("#Order_Payment_Method"), Payment_options);
    chart_payment.render();

</script>
@endsection
