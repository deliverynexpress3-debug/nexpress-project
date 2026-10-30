@extends('admin.layout.page-app')
@section('page_title', __('label.product_sales_report'))
@section('tab_title', __('label.product_sales_report'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.product_sales_report') }}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('label.product_sales_report') }}</li>
                    </ol>
                </div>

            </div>

            <!-- Earning Cards -->
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card-earning">
                        <p class="earning-title">{{ __('label.total_transactions_today') }}</p>
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $today_sum['total_earning'] ?? 00 }} </p>
                                <p class="earning-title">{{ __('label.total_earning') }}</p>
                            </div>
                            <div class="earning-divider"></div>
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $today_sum['seller_earning'] ?? 00 }} </p>
                                <p class="earning-title">{{ __('label.seller_earning') }}</p>
                            </div>
                            <div class="earning-divider"></div>
                            <div>
                                <p class="earning-amount">
                                    {{ Currency_Code() }}{{ $today_sum['deliveryboy_earning'] ?? 00 }}</p>
                                <p class="earning-title">{{ __('label.deliveryboy_earning') }}</p>
                            </div>
                        </div>
                      
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card-earning">
                        <p class="earning-title">{{ __('label.total_transactions_month') }}</p>
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $month_sum['total_earning'] ?? 00 }} </p>
                                <p class="earning-title">{{ __('label.total_earning') }}</p>
                            </div>
                            <div class="earning-divider"></div>
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $month_sum['seller_earning'] ?? 00 }} </p>
                                <p class="earning-title">{{ __('label.seller_earning') }}</p>
                            </div>
                            <div class="earning-divider"></div>
                            <div>
                                <p class="earning-amount">
                                    {{ Currency_Code() }}{{ $month_sum['deliveryboy_earning'] ?? 00 }}</p>
                                <p class="earning-title">{{ __('label.deliveryboy_earning') }}</p>
                            </div>
                        </div>
                      
                    </div>
                   
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card-earning">
                        <p class="earning-title">{{ __('label.total_transactions_year') }}</p>
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $year_sum['total_earning'] ?? 00 }} </p>
                                <p class="earning-title">{{ __('label.total_earning') }}</p>
                            </div>
                            <div class="earning-divider"></div>
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $year_sum['seller_earning'] ?? 00 }} </p>
                                <p class="earning-title">{{ __('label.seller_earning') }}</p>
                            </div>
                            <div class="earning-divider"></div>
                            <div>
                                <p class="earning-amount">
                                    {{ Currency_Code() }}{{ $year_sum['deliveryboy_earning'] ?? 00 }}</p>
                                <p class="earning-title">{{ __('label.deliveryboy_earning') }}</p>
                            </div>
                        </div>
                      
                    </div>

                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <div class="page-search mb-3">
                    <div class="input-group" title="Search">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                    <div class="sorting mr-2 w-50">
                        <label>{{__('label.sort_by')}}</label>
                        <select class="form-control" name="input_customer" id="input_customer">
                            <option value="all" selected>{{__('label.all_customer')}}</option>
                            @for ($i = 0; $i < count($customer); $i++)
                                <option value="{{ $customer[$i]['id'] }}">
                                    {{ $customer[$i]['user_name'] }} → {{ $customer[$i]['full_name'] }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="sorting mr-2 w-50">
                        <select class="form-control" name="input_item" id="input_item">
                            <option value="all" selected>{{__('label.all_item')}}</option>
                            @for ($i = 0; $i < count($item); $i++)
                                <option value="{{ $item[$i]['id'] }}">
                                    {{ $item[$i]['name'] }} 
                                </option>
                            @endfor
                        </select>
                    </div>
                 
                </div>
                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th>{{__('label.#')}}</th>
                                <th>{{__('label.users')}}</th>
                                <th>{{__('label.seller')}}</th>
                                <th class="text-left">{{__('label.item')}}</th>
                                <th>{{__('label.item_price')}}</th>
                                <th>{{__('label.quantity')}}</th>
                                <th>{{__('label.earning')}}</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        sidebar_down(700);
        const defaultImg = "{{ asset('assets/imgs/no_img.png') }}";

        $("#input_customer,#input_item").select2();

        const currencyCode = "{{ currency_code() }}";

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.sales-report-item.index') }}",
                    data: function(d) {
                        d.input_customer = $('#input_customer').val();
                        d.input_item = $('#input_item').val();
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'customer',
                        name: 'customer',
                        render: function(data, type, row) {
                            return `<div style="text-align: center;">${row.customer.full_name || ''} <br><span style="font-size: 14px; font-weight: 600;">${row.customer.user_name || ''}</span>`;
                        }
                    },
                    {
                        data: 'seller',
                        name: 'seller',
                        render: function(data, type, row) {
                            return `<div style="text-align: center;">${row.seller.full_name || ''} <br><span style="font-size: 14px; font-weight: 600;">${row.seller.user_name || ''}</span>`;
                        }
                    },
                    {
                        data: 'item',
                        name: 'item',
                                            width:'30%',
                        render: function(data, type, row) {
                            const image = row.item_image_path || '';
                            let Name  = row.item?.name || '';
                        
                            // 🔹 Limit name to 20 characters
                            const limit = 45;
                            if (Name.length > limit) {
                                Name = Name.substring(0, limit) + '...';
                            }
                        
                            const imgTag = image
                                ? `<a href="${image}" target="_blank">
                                        <img src="${image}" class="rounded-circle"
                                             style="height:55px;width:55px;object-fit:cover;">
                                   </a>`
                                : `
                                <a href="${defaultImg}" target="_blank" rel="noopener">
                                  <img src="${defaultImg}" class="rounded-circle"
                                       style="height:55px;width:55px;object-fit:cover;">
                                </a>`;
                            
                            return `
                                <div style="display:flex; align-items:center; gap:10px;">
                                    ${imgTag}
                                    <div title="${row.item?.name || ''}">
                                        ${Name}
                                    </div>
                                </div>
                            `;
                        }
                    },

                    {
                        data: 'item_price',
                        name: 'item_price',
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (data) {
                                return `<h6 class='text-dark'><strong>${currencyCode}${data}</strong></h6>`;
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: null,
                        name: 'quantity_price',
                        searchable: false,

                        render: function (data, type, full, meta) {
                        
                            let qty = full.order_quantity ?? 0;
                        
                            let totalPrice = full.order_quantity_amount ?? 0;
                        
                            return `
                                <div class="text-start">
                                
                                    <h6 class="mb-1">
                                        <strong>Quantity :</strong> ${qty}
                                    </h6>
                                
                                    <h6 class="mb-0">
                                        <strong>Total Quantity Price :</strong> 
                                        ${currencyCode}${totalPrice}
                                    </h6>
                                
                                </div>
                            `;
                        }
                    },
                    {
                        data: null,
                        name: 'earning',
                        searchable: false,

                        render: function (data, type, full, meta) {
                        
                            let sellerEarn = full.seller_earning ?? 0;
                        
                            let boyEarn = full.deliveryboy_earning ?? 0;
                        
                            return `
                        
                            <div class="text-start">
                            
                                <h6 class="mb-1">
                                    <strong>{{__('label.seller_earning')}} :</strong> 
                                    ${currencyCode}${sellerEarn}
                                </h6>
                            
                                <h6 class="mb-0">
                                    <strong>{{__('label.deliveryboy_earning')}} :</strong> 
                                    ${currencyCode}${boyEarn}
                                </h6>
                            
                            </div>
                        
                            `;
                        }
                    },
                
                ],
            });
                    
            $('#input_customer ,#input_item').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
				table.draw();
			});
        });
    </script>
@endsection
