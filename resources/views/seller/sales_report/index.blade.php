@extends('seller.layout.page-app')
@section('page_title', __('label.sales_reports'))
@section('tab_title', __('label.sales_reports'))

@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.sales_reports')}}</h1>

            <div class="border-bottom row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.sales_reports')}}</li>
                    </ol>
                </div>
              
            </div>
            <!-- Earning Cards -->
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card-earning">
                        <p class="earning-title">{{__('label.total_earning_today')}}</p>
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $today_sum['total_earning'] ?? 00 }}</p>
                                <p class="earning-title">{{__('label.total_earning')}}</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card-earning">
                        <p class="earning-title">{{__('label.total_earning_current_month')}}</p>
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $month_sum['total_earning'] ?? 00 }}</p>
                                <p class="earning-title">{{__('label.total_earning')}}</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card-earning">
                        <p class="earning-title">{{__('label.total_earning_current_year')}}</p>
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $year_sum['total_earning'] ?? 00 }}</p>
                                <p class="earning-title">{{__('label.total_earning')}}</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-search mt-3 mb-3">
                <div class="sorting mr-3 w-50">
                    <label>{{ __('label.sort_by') }}</label>
                    <select class="form-control" name="item_id" id="input_item_id">
                        <option value="all">{{ __('label.all_item') }}</option>
                        @foreach ($item as $key => $value)
                            <option value="{{ $value->id }}">
                                {{ $value->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
              
                <div class="sorting mr-3 w-50">
                    <select class="form-control" name="customer_id" id="input_customer_id">
                        <option value="all">{{ __('label.all_customer') }}</option>
                        @foreach ($customer as $key => $value)
                            <option value="{{ $value->id }}">
                                {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sorting mr-3 w-50">
                    <select class="form-control" name="status" id="input_status">
                        <option value="all">{{ __('label.all_method') }}</option>
                        <option value="1">{{ __('label.cod') }}</option>
                        <option value="2">{{ __('label.online') }}</option>
                      
                    </select>

                </div>
            </div>
            <div class="table-responsive table">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th> {{ __('label.order_number') }} </th>
                            <th> {{ __('label.customer') }} </th>
                            <th> {{ __('label.product') }} </th>
                            <th> {{ __('label.order_quantity') }} </th>
                            <th> {{ __('label.variation') }} </th>
                            <th> {{ __('label.payment') }} </th>
                            <th> {{ __('label.total_sales') }} </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" style="text-align:right;">{{__('label.total')}}:</th>
                            <th id="paid_total_footer" style="background :#a6a6a6"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        // Sidebar Scroll Down
        let sidebarHeight = $('.sidebar')[0].scrollHeight;
        sidebar_down(sidebarHeight);
        
        $("#input_item_id,#input_customer_id").select2();
         
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('seller.sales-report.index') }}",
                    data: function(d) {
                        d.input_item_id = $('#input_item_id').val();
                        d.input_customer_id = $('#input_customer_id').val();
                        d.input_status = $('#input_status').val();
                    },
                },
                columns: [
                    {
                        data: 'orders',
                        name: 'orders',
                        orderable: false,
                        searchable: false,
                       
                    },
                    {
                        data: 'customer.user_name',
                        name: 'customer.user_name',
                        render: function(data, type, row) {
                            let Uname = data ? data : "-";
                            let Fname = row.customer.full_name ? row.customer.full_name : "-";
                            return `
                                <div class="d-flex align-items-center">
                                   
                                    <div class="ml-3">
                                        <span style="white-space: nowrap; display: block; font-weight: bold;">${Uname}</span>
                                        <span style="white-space: nowrap; display: block; color: gray;">${Fname}</span>
                                    </div>
                                </div>
                                `;
                        }
                    },
                    {
                        data: 'product',
                        name: 'product',
                        orderable: false,
                        searchable: false,
                       
                    },
                  
                    {
                        data: 'order_quantity',
                        name: 'order_quantity',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'data.variation',
                        name: 'data.variation',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method',
                        render: function(data) {
                            if (data == 1) {
                                return "<h6 class='primary-color'><strong>{{ __('label.cod') }}</strong></h5>";
                            } else if (data == 2) {
                                return "<h6 class='primary-color'><strong>{{ __('label.online') }}</strong></h5>";
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount',
                        orderable: false,
                        searchable: false,
                       
                    },
                  
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    var currency = "{{ Currency_Code() }}";

                    // Function to parse amount
                    var intVal = function(i) {
                        return typeof i === 'string'
                            ? parseFloat(i.replace(/[^\d.-]/g, '')) || 0
                            : typeof i === 'number'
                            ? i
                            : 0;
                    };

                    // Total on current page
                    var pageTotal = api
                        .column(6, { page: 'current' })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Debug
                    console.log("Page Total:", pageTotal);

                    // Display in footer
                    $('#paid_total_footer').html(currency + ' ' + pageTotal.toFixed(2));
                },
            });
            $('#input_item_id,#input_customer_id ,#input_status').change(function() {
                table.draw();
            });
           
        });
    </script>
@endsection