@extends('seller.layout.page-app')
@section('page_title', __('label.dashboard'))
@section('tab_title', __('label.dashboard'))
@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.dashboard') }}</h1>

            <!--- Counter --->
            <div class="stats-grid mb-4">
             
                <div class="stat-card blue">
                    <div class="text">
                        <h2 class="counter-value counting" data-count="{{ no_format($ProductCount ?? 0) }}">{{ no_format($ProductCount ?? 0) }}</h2>
                        <p>{{__('label.product')}}</p>
                    </div>
                    <div class="stat-card">
                      <i class="fa-solid fa-cart-plus icon-bg"></i>
                    </div>
                </div>
                <div class="stat-card blue">
                  <div class="text">
                    <h2 class="counter-value counting" data-count="{{ no_format($OrdersCount ?? 0) }}">{{ no_format($OrdersCount ?? 0) }}</h2>
                        <p>{{__('label.orders')}}</p>
                  </div>
                  <div class="stat-card">
                    <i class="fa-solid fa-cart-shopping icon-bg"></i>
                  </div>
                </div>
                <div class="stat-card blue">
                  <div class="text">
                    <h2 class="counter-value counting" data-count="{{ no_format($ReturnOrderCount ?? 0) }}">{{ no_format($ReturnOrderCount ?? 0) }}</h2>
                    <p>{{__('label.return_request')}}</p>
                  </div>
                  <div class="stat-card">
                    <i class="fa-solid fa-clock-rotate-left icon-bg"></i>
                  </div>
                </div>
                <div class="stat-card blue">
                  <div class="text">
                    <h2 class="counter-value counting" data-count="{{ no_format($CancelOrderCount ?? 0) }}">{{ no_format($CancelOrderCount ?? 0) }}</h2>
                    <p>{{__('label.cancel_request')}}</p>
                  </div>
                  <div class="stat-card">
                    <i class="fa-solid fa-times-circle icon-bg"></i>
                  </div>
                </div>
                <div class="stat-card blue">
                  <div class="text">
                    <h2 class="counter-value counting" data-count="{{ no_format($ReviewCount ?? 0) }}">{{ no_format($ReviewCount ?? 0) }}</h2>
                    <p>{{__('label.reviews')}}</p>
                  </div>
                  <div class="stat-card">
                    <i class="fa-solid fa-comments icon-bg"></i>
                  </div>
                </div>
                <div class="stat-card blue">
                    <div class="text">
                        <h2 class="counter-value counting" data-count="{{ no_format($CategoryCount ?? 0) }}">{{ no_format($CategoryCount ?? 0) }}</h2>
                        <p>{{__('label.category')}}</p>
                    </div>
                    <div class="stat-card">
                      <i class="fa-solid fa-cart-plus icon-bg"></i>
                    </div>
                </div>
                <div class="stat-card blue">
                    <div class="text">
                        <h2 class="counter-value counting" data-count="{{ no_format($BrandCount ?? 0) }}">{{ no_format($BrandCount ?? 0) }}</h2>
                        <p>{{__('label.brand')}}</p>
                    </div>
                    <div class="stat-card">
                      <i class="fa-solid fa-cart-plus icon-bg"></i>
                    </div>
                </div>
                <div class="stat-card blue">
                    <div class="text">
                        <h2 class="counter-value counting" data-count="{{ no_format($OffersCount ?? 0) }}">{{ no_format($OffersCount ?? 0) }}</h2>
                        <p>{{__('label.offers')}}</p>
                    </div>
                    <div class="stat-card">
                      <i class="fa-solid fa-cart-plus icon-bg"></i>
                    </div>
                </div>
                <div class="stat-card blue">
                    <div class="text">
                        <h2 class="counter-value counting" data-count="{{ no_format($PromocodeCount ?? 0) }}">{{ no_format($PromocodeCount ?? 0) }}</h2>
                        <p>{{__('label.promocode')}}</p>
                    </div>
                    <div class="stat-card">
                      <i class="fa-solid fa-cart-plus icon-bg"></i>
                    </div>
                </div>
                <div class="stat-card blue">
                    <div class="text">
                        <h2 class="counter-value counting" data-count="{{ no_format($WalletAmount ?? 0) }}">{{ no_format($WalletAmount ?? 0) }}</h2>
                        <p>{{__('label.wallet')}}</p>
                    </div>
                    <div class="stat-card">
                      <i class="fa-solid fa-cart-plus icon-bg"></i>
                    </div>
                </div>
            </div>  
            
             <!-- Orders Overview -->
             <div class="row mt-3">
                <div class="col-12 col-xl-12 earning-cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-line fa-lg mr-2"></i>{{__('label.orders_overview')}}</h2>
                    </div>
                    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                        <!-- Today Orders -->
                        <div id="todayOrders" style="width: 250px;"></div>
                    
                        <!-- Monthly Orders -->
                        <div id="monthlyOrders" style="width: 600px;"></div>
                    
                        <!-- Yearly Orders -->
                        <div id="yearlyOrders" style="width: 600px;"></div>
                    
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
<!-- Chart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Orders Charts
    
    var todayOrders = {
            chart: { type: 'radialBar', height: 250 },
            series: [<?= $todayorders ?>],
            labels: ['{{__('label.today')}}'],
            colors: ['#00b894'], // Course-themed color
            plotOptions: {
              radialBar: {
                hollow: { size: '70%' },
                dataLabels: {
                  name: { fontSize: '16px' },
                  value: { fontSize: '22px', fontWeight: 'bold' }
                }
              }
            }
        };
        new ApexCharts(document.querySelector("#todayOrders"), todayOrders).render();

        var monthlyOrders = {
          chart: { type: 'bar', height: 400 },  // Increased height for Monthly Sales
          series: [{
            name: '{{__('label.monthly_orders')}}',
            data: {!! json_encode($sales_data['count']) !!}  // Sales count data
        }
          ],
          plotOptions: {
            bar: {
                horizontal: false,  // Set to false for vertical bars
                columnWidth: '95%',  // Adjust width for the bars (increase/decrease the percentage)
                endingShape: 'rounded'  // Optional: make bars have rounded edges
            }
        },
          xaxis: {
            categories: {!! json_encode($sales_data['month']) !!},
            labels: {
                style: {
                    fontSize: '14px',  // Set font size for the labels
                    fontWeight: 'bold',  // Set font weight to bold
                    colors: ['#333']  // Label color (optional, set to match the chart's color scheme)
                }
            }
          },
          yaxis: {
            labels: {
                style: {
                    fontSize: '14px',  // Set font size for the y-axis labels (0, 100, etc.)
                    fontWeight: 'bold',  // Set font weight to bold for y-axis labels
                    colors: ['#333']  // Label color (optional)
                }
            }
        },
        legend: {
                position: 'bottom',
                fontSize: '14px',
                fontWeight: 'bold',
                labels: {
                    colors: ['#333'],
                    
                }
            },
          colors: ['#ff6347','#00b894'],  // Course-related color (Tomato)
          dataLabels: { enabled: true,
         },
          title: {
            text: "{{__('label.monthly_orders')}}",
            align: 'left',
            style: {
              fontSize: '16px',
              fontWeight: 'bold',
              color: '#333'
            }
          }
        };
        new ApexCharts(document.querySelector("#monthlyOrders"), monthlyOrders).render();

        const currentYear = new Date().getFullYear();
        const yearLabels = {!! json_encode($yearsData['years']) !!};
        const yearlyOrdersCount = {!! json_encode($yearsData['counts']) !!};
        

        // Static data for those years
    
            var yearlyOrdersOptions = {
                chart: {
                    type: 'line',
                    height: 400,  // Increased height for Yearly Sales
                    zoom: {
                      enabled: true,  // Enable zoom
                      type: 'x', // Enable horizontal zoom
                      zoomedArea: {
                        fill: {
                          color: '#90CAF9',
                          opacity: 0.4
                        }
                      }
                    }
                },
                series: [{
                  name: '{{__('label.yearly_orders')}}',
                  data: yearlyOrdersCount
                }],
                xaxis: {
                  categories: yearLabels
                },
                stroke: {
                  curve: 'smooth',
                  width: 3
                },
                colors: ['#6c5ce7'],
                markers: {
                  size: 5
                 },
                dataLabels: {
                  enabled: true
                },
                title: {
                  text: "{{__('label.yearly_orders')}}",
                  align: 'left',
                  style: {
                    fontSize: '16px',
                    fontWeight: 'bold',
                    color: '#333'
                  }
                },
                toolbar: {
                  show: true,  // Show toolbar (includes zoom in/out)
                  tools: {
                    zoomin: '<i class="fa fa-search-plus"></i>',  // Zoom in icon
                    zoomout: '<i class="fa fa-search-minus"></i>' // Zoom out icon
                  }
                }
            };

        var coursechart = new ApexCharts(document.querySelector("#yearlyOrders"), yearlyOrdersOptions);
        coursechart.render();

</script>
@endsection
