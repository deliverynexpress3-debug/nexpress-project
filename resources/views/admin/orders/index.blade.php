@extends('admin.layout.page-app')
@section('page_title', __('label.orders'))
@section('tab_title', __('label.orders'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.orders') }} </h1>
            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.orders') }}
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
                                <i class="fa-solid fa-hourglass-start fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($PendingOrders ?? 0) }}</h3>
                                <span>{{ __('label.pending') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-circle-check fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($ConfirmedOrders ?? 0) }}</h3>
                                <span>{{ __('label.confirmed') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-hand fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($PickingOrders ?? 0) }}</h3>
                                <span>{{ __('label.picking') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-box fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($PackedOrders ?? 0) }}</h3>
                                <span>{{ __('label.packed') }}</span>
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
                                <i class="fa-solid fa-truck-fast fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($OutforOrders ?? 0) }}</h3>
                                <span>{{ __('label.out_for_delivery') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-check-circle fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($DeliverdOrders ?? 0) }}</h3>
                                <span>{{ __('label.delivered') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-ban fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($CancelledOrders ?? 0) }}</h3>
                                <span>{{ __('label.cancelled') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-xmark-circle fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($RejectedOrders ?? 0) }}</h3>
                                <span>{{ __('label.rejected') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Search -->
            <div class="page-search">
                <div class="input-group" title="Search">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                    </div>
                    <input type="text" id="input_search" class="form-control" placeholder="{{ __('label.search') }}" aria-label="Search" aria-describedby="basic-addon1">
                </div>
                
                <div class="sorting w-25 mr-2">
                    <label>{{ __('label.sort_by') }}</label>
                    <select class="form-control" name="payment_method" id="input_payment_method">
                        <option value="all">{{ __('label.all_method') }}</option>
                        <option value="1">{{ __('label.cash_on_delivery') }}</option>
                        <option value="2">{{ __('label.online') }}</option>
                    </select>
                </div>
               
            </div>
            <div class="page-search mb-3">
               
                <div class="sorting w-50 mr-2">
                    <select class="form-control" name="seller_id" id="input_seller_id">
                        <option value="all">{{ __('label.all_seller') }}</option>
                        @foreach ($seller as $key => $value)
                            <option value="{{ $value->id }}">
                                {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sorting w-50 mr-2">
                    <select class="form-control" name="customer_id" id="input_customer_id">
                        <option value="all">{{ __('label.all_customer') }}</option>
                        @foreach ($customer as $key => $value)
                            <option value="{{ $value->id }}">
                                {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sorting w-25 mr-2">
                    <select class="form-control" name="input_type" id="input_type">
                        <option value="all">{{__('label.all_type')}}</option>
                        <option value="today">{{__('label.today')}}</option>
                        <option value="month">{{__('label.month')}}</option>
                        <option value="year">{{__('label.year')}}</option>
                       
                    </select>
                </div>
               
            </div>

            <div class="row">
                <div class="col-md-12">
                    <ul class="tabs nav nav-pills custom-tabs inline-tabs" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" data-status="0" href="#" role="tab" aria-selected="true">{{__('label.all')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" data-status="1" href="#" role="tab" aria-selected="true">{{__('label.pending')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="2" href="#" role="tab" aria-selected="false">{{__('label.confirmed')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="3" href="#" role="tab" aria-selected="false">{{__('label.picking')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="4" href="#" role="tab" aria-selected="false">{{__('label.packed')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="5" href="#" role="tab" aria-selected="false">{{__('label.out_for_delivery')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="6" href="#" role="tab" aria-selected="false">{{__('label.delivered')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="7" href="#" role="tab" aria-selected="false">{{__('label.cancelled')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="8" href="#" role="tab" aria-selected="false">{{__('label.rejected')}}</a>
                        </li>
                
                       
                    </ul>
                    <div class="card custom-border-card">
                        <div class="table-responsive table">
                            <div class="table-responsive">
                                <table class="table table-striped text-center table-bordered" id="datatable">
                                    <thead>
                                        <tr style="background: #F9FAFF;">
                                            <th>{{ __('label.#') }}</th>
                                            <th>{{ __('label.group_order_id') }}</th>
                                            <th>{{ __('label.customer') }}</th>
                                            <th>{{ __('label.seller') }}</th>
                                            <th>{{ __('label.total_payable_amount') }}</th>
                                            <th>{{ __('label.earning') }}</th>
                                            <th>{{ __('label.payment_info') }}</th>
                                            <th>{{ __('label.status') }}</th>
                                            <th>{{ __('label.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                         
                        </div>
                    </div>

                </div>
            </div>
            
            <!-- Assign Delivery Modal -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <!-- Header -->
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('label.delivery_assignment') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <form id="delivery_assign" autocomplete="off">
                            <div class="modal-body">
                            
                                <!-- Delivery Boy + Time -->
                                <div class="form-row">
                                
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('label.delivery_boys') }} <span class="text-danger">*</span></label>
                                            <select class="form-control" name="delivery_boy_id" id="edit_delivery_boy_id" style="width:100%!important;">
                                                <option value="">{{ __('label.select_delivery_boy') }}</option>
                                                @foreach ($deliveryboy as $item)
                                                    <option value="{{ $item['id'] }}">
                                                        {{ $item['user_name'] }} → {{ $item['full_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{ __('label.pickup_time') }} <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" name="pickup_time">
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.estimated_delivery_time')}} <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" name="estimated_delivery_time">
                                        </div>
                                    </div>
                                
                                </div>
                            
                                <!-- Group Order ID & Pickup Location -->
                                <div class="col-md-12 mb-3">
                                    <div class="card bg-light border-0">
                                        <div class="card-body py-2">
                                        
                                            <div class="mb-2">
                                                {{__('label.group_order_id')}} :
                                                <strong id="show_group_order_id">--</strong>
                                            </div>
                                        
                                            <div>
                                                {{__('label.pickup_location')}} :
                                                <strong id="pickup_location_text">--</strong>
                                            </div>
                                            <input type="hidden" name="pickup_location" id="pickup_location">
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- Order Items -->
                                <div class="col-md-12">
                                    <div class="card shadow-sm">
                                        <div class="card-header">
                                            <strong>{{__('label.order_item')}}</strong>
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-striped table-bordered mb-0">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>{{__('label.order_number')}}</th>
                                                        <th>{{__('label.item')}}</th>
                                                        <th class="text-center">{{__('label.Qty')}}</th>
                                                        <th class="text-right">{{__('label.price')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="order_items_table">
                                                    <tr>
                                                        <td colspan="4" class="text-center">
                                                            Select order to load items
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- Total Payable Amount -->
                                <div class="col-md-12 mt-3 text-right">
                                    <strong>{{__('label.total_payable_amount')}}:</strong>
                                    <strong id="show_total_amount" class="text-success"> 0</strong>
                                </div>
                            
                                <!-- Hidden Fields -->
                                <input type="hidden" name="group_order_id" id="edit_order_id">
                                <input type="hidden" name="id" id="edit_id">
                            
                            </div>
                        
                            <!-- Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                        onclick="update_delivery_assign()">
                                    {{ __('label.update') }}
                                </button>
                                <button type="button" class="btn btn-cancel mw-120"
                                        data-dismiss="modal">
                                    {{ __('label.close') }}
                                </button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="orderActionModal"  tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabelAction" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content shadow border-0">
                        
                        <div class="modal-header">
                            <h5 class="modal-title">{{__('label.confirm_action')}}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <form id="orderStatusForm" autocomplete="off">
                            <div class="modal-body text-center">

                                <div class="mb-4">
                                    <i id="statusIcon" class="fa-solid fa-triangle-exclamation text-warning" style="font-size:40px;"></i>
                                </div>
                                
                                <p id="modalMessage"
                                   class="mb-0 text-dark"
                                   style="font-size:17px; font-weight:700;">
                                </p>
    
                            </div>
                            <input type="hidden" name="group_order_id" id="order_id">
                            <input type="hidden" name="req_status" id="req_status">

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_order_status()">{{__('label.yes_confirm')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{ __('label.cancel') }}</button>
                            </div>
                        </form>
                      
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        sidebar_down(480);

        $("#input_item_id , #input_seller_id ,#input_customer_id").select2();
        $("#edit_delivery_boy_id").select2({
            dropdownParent: $('#EditModel')
        });
        const currencyCode = "{{ currency_code() }}";

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.order.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                        d.input_seller_id = $('#input_seller_id').val();
                        d.input_customer_id = $('#input_customer_id').val();
                        d.input_payment_method = $('#input_payment_method').val();
                        d.input_type = $('#input_type').val();
                        d.input_req_status = $('.nav-link.active').data('status');

                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'group_order_id',
                        name: 'group_order_id',
                        render: function(data) {
                            return data ?
                                `<h6 class='primary-color'><strong> ${data} </strong></h6>` : "-";

                        }
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
                        data: 'total_payable_amount',
                        name: 'total_payable_amount',
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
                        name: 'earning',
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <div>
                                    <span class="" style="font-size:15px;">
                                        <strong>{{__('label.deliveryBoy')}} :</strong>
                                    </span><b
                                    <strong>${currencyCode}${row.delivery_boy_commission}</strong>
                                    
                                </div>
                                 <div>
                                    <span class="" style="font-size:15px;">
                                        <strong>{{__('label.seller')}} :</strong>
                                    </span><b
                                    <strong>${currencyCode}${row.seller_commission}</strong>
                                    
                                </div>
                            `;
                        }
                    },

                    {
                        data: null,
                        name: 'payment_info',
                        render: function (data, type, row) {
                        
                            let paymentMethod = '-';
                            if (row.payment_method == 1) {
                                paymentMethod = "<span class='primary-color'><strong>{{ __('label.cod') }}</strong></span>";
                            } else if (row.payment_method == 2) {
                                paymentMethod = "<span class='primary-color'><strong>{{ __('label.online') }}</strong></span>";
                            }
                        
                            let paymentStatus = '-';
                            if (row.payment_status == 1) {
                                paymentStatus = "<span class='text-danger'><strong>{{ __('label.pending') }}</strong></span>";
                            } else if (row.payment_status == 2) {
                                paymentStatus = "<span class='text-success'><strong>{{ __('label.paid') }}</strong></span>";
                            }
                        
                            return `
                                <div>
                                    <div><strong>{{ __('label.payment_method') }}</strong> - ${paymentMethod}</div>
                                    <div><strong>{{ __('label.payment_status') }}</strong> - ${paymentStatus}</div>
                                </div>
                            `;
                        }
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
            $('#input_payment_method ,#input_type ,#input_seller_id ,#input_customer_id')
                .change(function() {
                    table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
            $('.nav-link').on('click', function(e) {
                e.preventDefault();
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                table.draw();
            });

        });

        $(document).on("click", ".delivery_assign", function() {
            var id = $(this).data('id');
            var group_order_id = $(this).data('order_id');
            var delivery_boy_id = $(this).data('delivery_boy_id');
        
        
            // Set form values in the modal
            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_order_id").val(group_order_id);
            if (delivery_boy_id) {
                $("#edit_delivery_boy_id").val(delivery_boy_id).trigger('change');
            } else {
                $("#edit_delivery_boy_id").val('').trigger('change');
            }
            
            // clear old data
            $("#show_group_order_id").text('');
            $("#pickup_location").text('');
            $("#order_items_table").html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
            $("#show_total_amount").text('');

            // AJAX call for order details
            $.ajax({
                url: "{{ route('admin.order.view', ':group_order_id') }}"   .replace(':group_order_id', group_order_id),
                type: "GET",
                success: function (res) {

                    $("#show_group_order_id").text(res.group_order_id);
                    $("#pickup_location_text").text(res.pickup_location);
                    $("#pickup_location").val(res.pickup_location);
                    $("#show_total_amount").text(currencyCode + res.total_payable_amount);

                    if (res.delivery_boy_id) {
                        $("#edit_delivery_boy_id").val(res.delivery_boy_id).trigger('change');
                    }

                    if (res.pickup_time) {
                        $('input[name="pickup_time"]').val(res.pickup_time.replace(' ', 'T').slice(0, 16));
                    } else {
                        let now = new Date();
                        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                        $('input[name="pickup_time"]').val(now.toISOString().slice(0, 16));
                    }
                    if (res.estimated_time) {
                        $('input[name="estimated_delivery_time"]').val(res.estimated_time.replace(' ', 'T').slice(0, 16));
                    } else {
                        let est = new Date(Date.now() + 30 * 60000);
                        est.setMinutes(est.getMinutes() - est.getTimezoneOffset());
                        $('input[name="estimated_delivery_time"]').val(est.toISOString().slice(0, 16));
                    }
                    let html = '';
                    if (res.items && res.items.length > 0) {
                        res.items.forEach(function (item) {
                            html += `
                                <tr>
                                    <td>#${item.order_number}</td>
                                    <td>${item.item_name}</td>
                                    <td class="text-center">${item.qty}</td>
                                    <td class="text-right">${currencyCode}${item.price}</td>
                                </tr>
                            `;
                        });
                    } else {
                        html = `<tr><td colspan="4" class="text-center">No items found</td></tr>`;
                    }

                    $("#order_items_table").html(html);
                },

                error: function () {
                    alert("Something went wrong while loading order details.");
                }
            });
    
          
        });
        
        function update_delivery_assign() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#delivery_assign")[0]);

                var Edit_Id = $("#edit_order_id").val();
                var url = '{{ route('admin.order.assignment', ':id') }}';
                url = url.replace(':id', Edit_Id);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        if (resp.status == 200) {
                            $('#EditModel').modal('toggle');
                        }

                        get_responce_message(resp, 'delivery_assign', "{{ route('admin.order.index') }}");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        }

        $(document).on('click', '.order-status-btn', function () {
            var orderId = $(this).data('order_id');
            let status  = $(this).data('status');

            $('#order_id').val(orderId);
            $('#req_status').val(status);
          

            let message = "";
            let iconClass = "";
            let iconColor = "";

            // Convert status to string (safe comparison)
            status = status.toString();

            if (status === "2") { // Accept
                message = "<b>{{__('label.order_msg')}} <span class='text-success'>{{__('label.ACCEPTED')}}</span>?</b>";
                iconClass = "fa-circle-check fa-2xl";
                iconColor = "text-success";
            }

            else if (status === "8") { // Reject
                message = "<b>{{__('label.order_msg')}} <span class='text-danger'>{{__('label.REJECTED')}}</span>?</b>";
                iconClass = "fa-circle-xmark fa-2xl";
                iconColor = "text-danger";
            }

            else if (status === "3") { // Preparing
                message = "<b>{{__('label.order_msg')}} <span class='text-info'>{{__('label.PREPARING')}}</span>?</b>";
                iconClass = "fa-box-open fa-2xl";
                iconColor = "text-info";
            }

            else if (status === "4") { // Ready for Pickup
                message = "<b>{{__('label.order_msg')}} <span class='text-primary'>{{__('label.READY_FOR_PICKUP')}}</span>?</b>";
                iconClass = "fa-box fa-2xl";
                iconColor = "text-primary";
            }

            // Set Message
            $('#modalMessage').html(message);

            // Change Icon Dynamically
            $('#statusIcon')
                .removeClass()
                .addClass('fa-solid ' + iconClass + ' ' + iconColor)
                .css('font-size', '55px');

        });

        function update_order_status() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
            
                $("#dvloader").show();
                var formData = new FormData($("#orderStatusForm")[0]);
            
                var Edit_Id = $("#order_id").val();
                var url = '{{ route('admin.order.change.status', ':id') }}';
                url = url.replace(':id', Edit_Id);
            
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        if (resp.status == 200) {
                            $('#EditModel').modal('toggle');
                        }
                    
                        get_responce_message(resp, 'orderStatusForm', "{{ route('admin.order.index') }}");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        }
        
    </script>
@endsection
