@extends('admin.layout.page-app')
@section('page_title', __('label.dashboard'))
@section('tab_title', __('label.dashboard'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')
        
        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.dashboard') }}</h1>

            <!--- Counter --->
            <div class="row counter-row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-users fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($CustomerCount ?? 0) }}</h3>
                                <span>{{ __('label.customer') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-user-clock fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($SellerCount ?? 0) }}</h3>
                                <span>{{ __('label.seller') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-person-biking fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($DeliveryBoyCount ?? 0) }}</h3>
                                <span>{{ __('label.delivery_boys') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-cart-plus fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($ItemCount ?? 0) }}</h3>
                                <span>{{ __('label.product') }}</span>
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
                                <i class="fa-solid fa-cart-shopping fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($OrdersCount ?? 0) }}</h3>
                                <span>{{ __('label.orders') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-shapes fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($CategoryCount ?? 0) }}</h3>
                                <span>{{ __('label.category') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-briefcase fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($BrandCount ?? 0) }}</h3>
                                <span>{{ __('label.brand') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-gift fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($OffersCount ?? 0) }}</h3>
                                <span>{{ __('label.offers') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>

            <!--- Join Staticstic with Top Brand -->
            <div class="row mb-3 pl-3">
                <div class="col-12 col-xl-8 cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-column fa-lg mr-2"></i>{{__('label.join_customers_&_seller_deliveryboy_statistice')}}</h2>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 col-sm-12">
                            <Button id="year" class="btn btn-default">{{__('label.this_year')}}</Button>
                            <Button id="month" class="btn btn-default">{{__('label.this_month')}}</Button>
                        </div>
                    </div>
                    <div class="summary-table-card mt-2">
                        <div id="Customer_Seller_DeliveryBoy_Chart"></div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="category-box">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-table-cells-large fa-lg mr-2"></i>{{__('label.top_brand')}}</h2>
                            <a href="{{ route('admin.brand.index') }}" class="btn btn-link">{{ __('label.view_all') }}</a>
                        </div>
                        <div class="pt-3 mt-0">
                            <div class="row pr-3">
                                @for ($i = 0; $i < count($top_brand); $i++)
                                    @if ($i > 0 && ($i % 4 == 1 || $i % 4 == 2))
                                        <div class="col-5 mb-2 pr-0">
                                            <img src="{{ $top_brand[$i]['logo'] }}" class="category-image">
                                            <div class="centered">{{ $top_brand[$i]['name'] }}</div>
                                        </div>
                                    @else
                                        <div class="col-7 mb-2 pr-0">
                                            <img src="{{ $top_brand[$i]['logo'] }}" class="category-image">
                                            <div class="centered">{{ $top_brand[$i]['name'] }}</div>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!--- Orders Overview -->
            <div class="row mb-3 pl-3 pr-3">
               <div class="col-12 col-xl-12 cart-bg">
                   <div class="box-title">
                       <h2 class="title"><i class="fa-solid fa-chart-column fa-lg mr-2"></i>{{__('label.orders_overview')}}</h2>
                   </div>
                   <div class="row mt-2 mb-3">
                       <div class="col-12 col-sm-12">
                           <button id="weekBtn" class="btn btn-default active">{{__('label.weekly')}}</button>
                           <Button id="monthBtn" class="btn btn-default">{{__('label.monthly')}}</Button>
                           <Button id="yearBtn" class="btn btn-default">{{__('label.yearly')}}</Button>
                       </div>
                   </div>
               
                   <canvas id="ordersChart" height="90"></canvas>
                
               </div>
           
            </div>

            <!--- Top Selling Product & Most Used Hashtag -->
            <div class="row mb-3 pl-3">
                <div class="col-12 col-xl-8 cart-bg">
                    <div class="box-title" style="color: var(--partition-color);">
                        <h2 class="title"><i class="fa-solid fa-chart-bar fa-lg mr-2"></i>{{__('label.top_selling_product')}} </h2>
                    </div>
                    
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade active show" id="pills-course-view" role="tabpanel" aria-labelledby="pills-course-view-tab">
                            <div class="summary-table-card" style="color: var(--black-color);">
                                @foreach($top_item as $key=> $value)
                                <div class="border-card bg-white">
                                    <div class="row">
                                        <div class="col-1">
                                            {{$key + 1 . '.'}}
                                            
                                        </div>
                                        <div class="col-8">
                                            <span class="avatar-control" title="K.G.F: Chapter 2">
                                                <img src="{{$value['portrait_img']}}" style="height:40px; width:40px">
                                                {{ Str::limit($value['name'], 75) }}
                                            </span>
                                        </div>
                                        <div class="col-3 d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-cart-shopping fa-xl mr-2 primary-color"></i>
                                            <span>{{ No_Format($value['total_sold'] ?? 0) }}</span>
                                        </div>
                                        
                                    </div>
                                </div>
                                @endforeach
                             
                            </div>
                        </div>
                       
                       
                    </div>
                   
                </div>
                <div class="col-12 col-xl-4 ">
                    <div class="video-box pb-2">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-hashtag fa-lg mr-2"></i>{{__('label.most_used_hashtag')}}</h2>
                            <a href="{{ route('admin.hashtag.index') }}" class="btn btn-link">{{__('label.view_all')}}</a>
                        </div>
                        <div class="summary-table-card mt-3">
                            @for ($i = 0; $i < count($most_used_hashtag); $i++)
                                <div class="hashtag-card mb-3">
                                    <div class="row">
                                        <div class="col-10 pl-2">
                                            <h5 class="m-0 primary-color">#{{ String_Cut($most_used_hashtag[$i]['name'],25) }}</h5>
                                        </div>
                                        <div class="col-2 pl-0">
                                            <h5 class="m-0 primary-color">{{ No_Format($most_used_hashtag[$i]['total_used'] ?? 0) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Rated item -->
            <div class="row mt-3 pl-3">
                <div class="col-12">
                    <div class="box-title">
                        <h2 class="title">{{__('label.top_rated_items')}}</h2>
                        <a href="{{ route('admin.item.index')}}" class="btn btn-link">{{__('label.view_all')}}</a>
                    </div>
                    <div class="row artist-row">
                        @if(isset($top_rating_item) && $top_rating_item != null)
                            @foreach ($top_rating_item as $value)
                                <div class="col-6 col-md-2">
                                    <div class="artist-grid-card p-2">
                                        <span class="avatar-control">
                                            <img src="{{ $value->item->portrait_img }}" class="img-thumbnail" style="height: 130px; width: 100%;" />
                                        </span>
                                        <p class="name mt-2" style="display: inline-block; text-overflow:ellipsis; white-space:nowrap; overflow:hidden; width:100%;font-size: 16px;">{{$value->item->name}}</p>
                                        <div class="d-flex justify-content-center align-items-center mt-1">
                                            <i class="fa-solid fa-star fa-lg mr-2" style="color: var(--secondary-color);"></i>
                                            <h5 class="m-0">{{ round($value->ratings, 1) ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            
            <!--- Top Seller -->
            <div class="row">
                <div class="col-12">
                    <div class="box-title d-flex justify-content-between align-items-center mb-3">
                        <h2 class="title header-text">{{ __('label.top_seller') }}</h2>
                    </div>
            
                    <div class="row g-3">
                        @if(!empty($top_seller))
                            @foreach ($top_seller as $value)
                                @if($value)
                                    @php
                                        // Get total sold for this seller
                                        $totalSold = $value['total_sold'];
                                    @endphp
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                        <div class="topseller-card shadow-sm h-100">
                                            <div class="avatar-wrapper">
                                                <a href="{{ $value->image ?? asset('assets/imgs/default.png') }}" target="_blank">
                                                    <img src="{{ $value->image ?? asset('assets/imgs/default.png') }}" alt="Seller Image" class="img-fluid rounded-circle">
                                                </a>
                                            </div>
                                            <div class="topseller-details">
                                                <div class="topseller-name fw-semibold mb-1">
                                                    {{ Str::limit($value['user_name'], 15) }}
                                                </div>
                                                <div class="topseller-fullname mb-1">{{ Str::limit($value['full_name'], 20) }}</div>
                                                <div class="topseller-total-sold">{{__('label.total_sold')}}: {{ $value['total_sold'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
<!-- Chart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get dynamic data from Laravel controller
    const statuses = @json($statuses);              // ['Pending','Confirmed',...]
    const statusColors = @json($statusColors);      // { 'Pending':'#f59e0b', ... }
    const weekData = @json($weekData);              // { labels: [...], data: {...} }
    const monthData = @json($monthData);
    const yearData = @json($yearData);

    // Function to build Chart.js datasets dynamically
    function buildDatasets(data) {
        const datasets = [];
        for (const [status, values] of Object.entries(data.data)) {
            datasets.push({
                label: status,
                data: values,
                backgroundColor: statusColors[status],
                hoverBackgroundColor: shadeColor(statusColors[status], 20), // hover effect
                borderRadius: 5
            });
        }
        return datasets;
    }

    // Utility: lighten/darken color for hover
    function shadeColor(color, percent) {
        let R = parseInt(color.substring(1,3),16);
        let G = parseInt(color.substring(3,5),16);
        let B = parseInt(color.substring(5,7),16);

        R = parseInt(R * (100 + percent) / 100);
        G = parseInt(G * (100 + percent) / 100);
        B = parseInt(B * (100 + percent) / 100);

        R = (R>255)?255:R;
        G = (G>255)?255:G;
        B = (B>255)?255:B;

        const RR = ((R.toString(16).length==1)?"0"+R.toString(16):R.toString(16));
        const GG = ((G.toString(16).length==1)?"0"+G.toString(16):G.toString(16));
        const BB = ((B.toString(16).length==1)?"0"+B.toString(16):B.toString(16));

        return "#"+RR+GG+BB;
    }

    // Prepare datasets for Chart.js
    const dataSets = {
        week: { labels: weekData.labels, datasets: buildDatasets(weekData) },
        month: { labels: monthData.labels, datasets: buildDatasets(monthData) },
        year: { labels: yearData.labels, datasets: buildDatasets(yearData) },
    };

    const ctx = document.getElementById('ordersChart').getContext('2d');

    const chartConfig = {
        type: 'bar',
        data: dataSets.week, // default: week
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 12,
                        padding: 12,
                        font: { size: 13, weight: 'bold' }
                    }
                },
                tooltip: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    padding: 10,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 14, weight: 'bold' }
                }
            },
            interaction: { mode: 'index', intersect: false },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false },
                    ticks: { font: { size: 13, weight: 'bold' } }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: '#e5e7eb' },
                    ticks: { stepSize: 10, font: { size: 13, weight: 'bold' } }
                }
            },
            animation: { duration: 1000, easing: 'easeOutQuart' },
            barPercentage: 0.5,
            categoryPercentage: 0.6,
            hover: { mode: 'index', intersect: true }
        }
    };

    const ordersChart = new Chart(ctx, chartConfig);

    // Filter buttons
    const buttons = [
        { id: 'weekBtn', period: 'week' },
        { id: 'monthBtn', period: 'month' },
        { id: 'yearBtn', period: 'year' }
    ];

    buttons.forEach(btn => {
        document.getElementById(btn.id).addEventListener('click', function() {
            buttons.forEach(b => document.getElementById(b.id).classList.remove('active'));
            this.classList.add('active');

            ordersChart.data = dataSets[btn.period];
            ordersChart.update();
        });
    });
</script>
<script>
    // Get data from your Laravel controller
        let customerYear = JSON.parse(`<?php echo $customer_year ?>`);
        let customerMonth = JSON.parse(`<?php echo $customer_month ?>`);
        let sellerYear = JSON.parse(`<?php echo $seller_year ?>`);
        let sellerMonth = JSON.parse(`<?php echo $seller_month ?>`);
        let deliveryboyYear = JSON.parse(`<?php echo $deliveryboy_year ?>`);
        let deliveryboyMonth = JSON.parse(`<?php echo $deliveryboy_month ?>`);

        let chartOptions = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: { show: false }
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '70%',
                    endingShape: 'rounded'
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#4e45b8'],
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 0.8,
                    stops: [0, 100]
                }
            },
            colors: ['#6a11cb', '#f7971e' ,'#446494'],
            grid: {
                borderColor: '#e0e0e0',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '14px'
                }
            },
            series: [],
            xaxis: {
                categories: []
            },
            legend: {
                position: 'bottom',
                fontSize: '16px',
                fontWeight: 'bold',
                labels: {
                    colors: ['#333'],
                    useSeriesColors: false
                }
            },
        };
        let chart = new ApexCharts(document.querySelector("#Customer_Seller_DeliveryBoy_Chart"), chartOptions);
        chart.render();

        // Function to load chart data
        function loadChartData(type) {
            if (type === 'year') {
                chart.updateOptions({
                    series: [
                        { name: "{{ __('label.customer') }}", data: customerYear.sum },
                        { name: "{{ __('label.seller') }}", data: sellerYear.sum },
                        { name: "{{ __('label.delivery_boys') }}", data: deliveryboyYear.sum },
                    ],
                    xaxis: {
                        categories: [
                            '{{__("label.jan")}}', '{{__("label.feb")}}', '{{__("label.mar")}}', '{{__("label.apr")}}',
                            '{{__("label.may")}}', '{{__("label.jun")}}', '{{__("label.jul")}}', '{{__("label.aug")}}',
                            '{{__("label.sep")}}', '{{__("label.oct")}}', '{{__("label.nov")}}', '{{__("label.dec")}}'
                        ],
                        labels: {
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold'
                            }
                        }
                    },
                });
            } else {
                let daysInMonth = customerMonth.sum.length;
                chart.updateOptions({
                    series: [
                        { name: "{{ __('label.customer') }}", data: customerMonth.sum },
                        { name: "{{ __('label.seller') }}", data: sellerMonth.sum },
                        { name: "{{ __('label.delivery_boys') }}", data: deliveryboyMonth.sum },
                    ],
                    xaxis: {
                        categories: Array.from({ length: daysInMonth }, (_, i) => (i + 1).toString())
                    },
                });
            }
        }

        loadChartData('year');
        document.getElementById('year').addEventListener('click', function () {
            loadChartData('year');
        });
        document.getElementById('month').addEventListener('click', function () {
            loadChartData('month');
        });

    
</script>
@endsection
