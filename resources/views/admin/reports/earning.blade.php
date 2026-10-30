@extends('admin.layout.page-app')
@section('page_title', __('label.earning_report'))
@section('tab_title', __('label.earning_report'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')
        
        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.earning_report') }}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.sales_reports') }}
                        </li>
                    </ol>
                </div>
                
            </div>
            <!--- Counter --->
            <div class="row counter-row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-sack-dollar fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($year_totalRevenue ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.total_revenue_yearly') }} ({{$year}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-building-columns fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($year_adminEarned ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.admin_earned_yearly') }} ({{$year}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-store fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($year_sellerEarned ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.seller_earned_yearly') }} ({{$year}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-truck fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($year_deliveryBoyEarned ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.deliveryboy_earned_yearly') }} ({{$year}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row counter-row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-sack-dollar fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($month_totalRevenue ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.total_revenue_yearly') }} ({{$month}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-building-columns fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($month_adminEarned ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.admin_earned_yearly') }} ({{$month}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-store fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($month_sellerEarned ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.seller_earned_yearly') }} ({{$month}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-truck fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($month_deliveryBoyEarned ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.deliveryboy_earned_yearly') }} ({{$month}})</span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="row counter-row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-sack-dollar fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($week_totalRevenue ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.total_revenue_week') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-building-columns fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($week_adminEarned ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.admin_earned_week') }} </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-store fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($week_sellerEarned ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.seller_earned_week') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-truck fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($week_deliveryBoyEarned ?? 0) }} {{ Currency_Code() }}</h3>
                                <span>{{ __('label.deliveryboy_earned_week') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <!--- Yearly Orders Earning --->
            <div class="row mb-3 pl-3">
                <div class="col-12 col-xl-8 cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-column fa-lg mr-2"></i>{{__('label.yearly_orders_overview')}}</h2>
                    </div>
                   
                    <div id="earningChart"></div>
                    
                </div>
                <div class="col-12 col-xl-4">
                    <div class="video-box pb-2">
                        <div class="box-title mt-0" style="color: var(--partition-color);">
                            <h2 class="title"><i class="fa-solid fa-chart-pie fa-lg mr-2"></i>{{__('label.withdrawal_current_year')}}</h2>
                            <a href="{{ route('admin.withdrawal.index') }}" class="btn btn-link">{{__('label.view_all')}}</a>
                        </div>
                        <div class="summary-table-card mt-2">
                            <div id="WithdrawalChart"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const currencySymbol = `{{ Currency_Code() }}`;

    var adminData = @json($adminData);
    var sellerData = @json($sellerData);
    var deliveryData = @json($deliveryData);

    var options = {
        series: [
            {
                name: "Admin Earning",
                data: adminData
            },
            {
                name: "Seller Earning",
                data: sellerData
            },
            {
                name: "Delivery Boy Earning",
                data: deliveryData
            }
        ],
        chart: {
            type: 'area',
            height: 450,
            toolbar: { show: false }
        },
        colors: ['#4f46e5', '#16a34a', '#f97316'],
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                opacityFrom: 0.4,
                opacityTo: 0.1
            }
        },

        // 🔥 X-Axis Bold
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                         'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            labels: {
                style: {
                    fontSize: '13px',
                    fontWeight: 600
                }
            }
        },

        // 🔥 Y-Axis Bold
        yaxis: {
            labels: {
                formatter: function (val) {
                    return "₹ " + val.toLocaleString();
                },
                style: {
                    fontSize: '13px',
                    fontWeight: 600
                }
            }
        },

        // 🔥 Legend Bold
        legend: {
            position: 'top',
            fontSize: '14px',
            fontWeight: 700
        },

        // 🔥 Tooltip Bold
        tooltip: {
            shared: true,
            intersect: false,
            style: {
                fontSize: '14px',
                fontWeight: 600
            },
            y: {
                formatter: function (val) {
                    return "₹ " + val.toLocaleString();
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#earningChart"), options);
    chart.render();

     // Withdrawal
     const withdrawalData = JSON.parse(`<?php echo $withdrawal_earning ?>`);
            const withdrawal_option = {
                chart: {
                    type: 'donut',
                    height: 400,
                },
                series: withdrawalData.sum,
                labels: ["{{__('label.pending')}}", "{{__('label.completed')}}"],
                colors: ['#e3000b', '#058f00'],
                plotOptions: {
                    pie: {
                        startAngle: -90,
                        endAngle: 90,
                        offsetY: 10
                    }
                },
                dataLabels: {
                    enabled: false
                },
                fill: {
                    type: 'gradient',
                },
                tooltip: {
                    y: {
                        formatter: function (value) {
                            return value + ' ' + currencySymbol;
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '18px',
                    fontWeight: 'bold',
                    offsetY: -90,
                    labels: {
                        colors: 'var(--partition-color)',
                        useSeriesColors: false
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: { width: 300 },
                        legend: {
                            position: 'bottom',
                            offsetY: -5
                        }
                    }
                }]
            };
        new ApexCharts(document.querySelector("#WithdrawalChart"), withdrawal_option).render();
        
</script>

@endsection
