@extends('admin.layout.page-app')
@section('page_title', __('label.view_orders'))
@section('tab_title', __('label.orders'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.orders') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.order.index') }}">{{ __('label.orders') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.view_orders') }}
                        </li>
                    </ol>
                </div>
              
                
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.order.index') }}" class="btn btn-default mw-120"
                        style="margin-top: -14px;">{{ __('label.back') }}</a>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="order-details">
                    <div class="order-header">
                        <div class="order-number">
                            <strong>
                                {{ __('label.group_order_id') }} :
                                <span>#{{ $orders['group_order_id'] }}</span>
                            </strong></br>
                            
                            <strong>
                                {{ __('label.paymentmethod') }} :

                                @if($orders['payment_method'] ==1)
                                <span class="status-badge badge-dark">{{ __('label.cod') }}</span>
                                @elseif($orders['payment_method'] ==2)
                                <span class="status-badge badge-dark">{{ __('label.online') }}</span>
                                @else
                                <span class="status-badge badge-dark"> - </span>
                                @endif

                            </strong></br>
                            <strong>
                                {{ __('label.payment') }} :
                                @if($orders['payment_status'] ==1)
                                <span class="text-danger"> {{ __('label.pending') }}</span>
                                @else
                                <span class="text-danger">{{ __('label.paid') }}</span>
                                @endif
                              
                            </strong></br>
                            <strong>
                                {{ __('label.delivery_otp') }} :
                                {{$orders['delivery_otp'] ?? '-'}}
                                
                            </strong></br>
                            <strong> Orders Notes : </strong>
                            {{$orders['order_notes'] ?? '-'}}
                        </div>
                        <div class="order-number d-flex flex-column">

                            {{-- Order Date --}}
                            <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                                <strong>
                                    {{ __('label.order_place') }} :
                                    <span>{{ $order_date }}</span>
                                </strong>
                            </div>
                            {{-- STATUS + INVOICE (SAME ROW) --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                        
                                {{-- Order Status --}}
                                @if($orders->order_status == 1)
                                    <span class="status-badge badge-warning">{{ __('label.pending') }}</span>
                                @elseif($orders->order_status == 2)
                                    <span class="status-badge badge-primary">{{ __('label.confirmed') }}</span>
                                @elseif($orders->order_status == 3)
                                    <span class="status-badge badge-info">{{ __('label.picking') }}</span>
                                @elseif($orders->order_status == 4)
                                    <span class="status-badge badge-secondary">{{ __('label.packed') }}</span>
                                @elseif($orders->order_status == 5)
                                    <span class="status-badge badge-dark">{{ __('label.out_for_delivery') }}</span>
                                @elseif($orders->order_status == 6)
                                    <span class="status-badge badge-success">{{ __('label.delivered') }}</span>
                                @elseif($orders->order_status == 7)
                                    <span class="status-badge badge-danger">{{ __('label.cancelled') }}</span>
                                @elseif($orders->order_status == 8)
                                    <span class="status-badge badge-danger">{{ __('label.rejected') }}</span>
                                @endif
                        
                                {{-- Invoice --}}
                                @if($orders->order_status == 6)
                                    <a href="{{ route('admin.order.download.invoice', ['id' => $orders->group_order_id]) }}"
                                       target="_blank">
                                        <button class="invoice-btn">
                                            {{ __('label.invoice') }}
                                        </button>
                                    </a>
                                @endif
                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card custom-border-card">

                        <div class="info-section">
                            
                            <!-- Shipping Info -->
                            <div class="info-card">
                                <h4>{{__('label.shipping_info')}}</h4>
                                <div class="info-content">
                                    <div class="info-text">
                                        <p><strong>{{__('label.name')}}:</strong> {{ optional($orders->customer)->full_name ?? '-' }}</p>
                                        <p><strong>{{__('label.contact')}}:</strong> {{ optional($orders->customer)->country_code }} {{ optional($orders->customer)->mobile_number }}</p>
                                        <p><strong>{{__('label.email')}}:</strong> {{ optional($orders->customer)->email ?? '-' }}</p>
                                        <p>
                                            <strong>{{ __('label.address') }}:</strong> 
                                            {{ collect([
                                                optional($orders->customer_address)->address,
                                                optional($orders->customer_address)->area,
                                                optional($orders->customer_address)->city,
                                                optional($orders->customer_address)->state,
                                                optional($orders->customer_address)->country,
                                                optional($orders->customer_address)->pincode
                                            ])->filter()->implode(', ') }}
                                        </p>
                                    </div>
                                    <img src="{{ optional($orders->customer)->image }}" alt="Customer Image">
                                   
                                </div>
                            </div>
                            <!-- Seller Info -->
                            <div class="info-card">
                                <h4>{{__('label.seller_info')}}</h4>
                                <div class="info-content">
                                    <div class="info-text">
                                        <p><strong>{{__('label.seller_name')}}:</strong> {{ optional($orders->seller)->full_name ?? '-' }}</p>
                                        <p><strong>{{__('label.contact')}}:</strong> {{ optional($orders->seller)->country_code }} {{ optional($orders->seller)->mobile_number }}</p>
                                        <p><strong>{{__('label.email')}}:</strong> {{ optional($orders->seller)->email ?? '-' }}</p>
                                    </div>
                                    <img src="{{ optional($orders->seller)->image }}" alt="Seller Image">
                                </div>
                            </div>
                            <!-- Delivery Assignment Info -->
                            @if($assign_orders != null && $assign_orders->deliveryboy)
                            @php
                                // Pickup Time
                                $pickupTime = $assign_orders->pickup_time ? date('d M h:i A', strtotime($assign_orders->pickup_time))  : '--';
                                $pickupAddress = $assign_orders->pickup_location ? e($assign_orders->pickup_location) : '--';
                                $estimatedTime = $assign_orders->estimated_delivery_time? date('d M h:i A', strtotime($assign_orders->estimated_delivery_time)) : '--';
                             
                            @endphp
                            <div class="info-card">
                                <h4>{{__('label.delivery_assignment_info')}}</h4>
                                <div class="info-content">
                                    <div class="info-text">
                                        <p><strong>{{__('label.delivery_boy_name')}}:</strong> {{ $assign_orders->deliveryboy->full_name }}</p>
                                        <p><strong>{{__('label.contact')}}:</strong> {{ $assign_orders->deliveryboy->country_code }} {{ $assign_orders->deliveryboy->mobile_number }}</p>
                                        <p><strong>{{__('label.pickup_time')}}: </strong> {{$pickupTime}} </p>
                                        <p><strong>{{__('label.pickup_location')}}: </strong> {{$pickupAddress}} </p>
                                        <p><strong>{{__('label.estimated_time')}}: </strong> {{$estimatedTime}} </p>

                                    </div>
                                    <img src="{{ $assign_orders->deliveryboy->image }}" alt="Seller Image">
                                </div>
                            </div>
                            @endif
                            
                        </div>
                    </div>
                </div>
             
            </div>

            <div class="row mt-3">
                <div class="col-md-9">
                    <div class="card custom-border-card">
                        <div class="table-responsive">
                            <table class="table table-striped text-center table-bordered" id="datatable">
                                <thead>
                                    <tr style="background: #F9FAFF;">

                                        <th>{{ __('label.order_no') }}</th>
                                        <th>{{ __('label.product') }}</th>
                                        <th>{{ __('label.variation') }}</th>
                                        <th>{{ __('label.unit') }}</th>
                                        <th>{{ __('label.order_quantity') }}</th>
                                        <th>{{ __('label.price') }}</th>
                                        <th>{{ __('label.total_item_price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tbody>
                                        @foreach($orders['items'] as $value)
                                        <tr>
                                            <td>
                                                <h6 class='primary-color'><strong> {{ $value->order_number }} </strong></h6>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{$value->item_image}}" target="_blank">
                                                        <img src="{{$value->item_image}}" class="img-thumbnail"
                                                            style="height:55px; width:60px; object-fit:cover; border-radius: 8px;">
                                                    </a>
                                                    <span class="ml-3 font-weight-bold" style="white-space: nowrap;">
                                                        {{ strlen($value->item_name) > 20 ? substr($value->item_name, 0, 20) . '...' : $value->item_name }}
                                                    </span>
                                                </div>
                                            </td>
                                           
                                            <td>
                                                @if($value->variation != null)
                                                    @php
                                                        $variationData = json_decode($value->variation, true);
                                                    @endphp
                                            
                                                    @if(is_array($variationData) && count($variationData) > 0)
                                                        <div class="text-left">
                                                            @foreach($variationData as $key => $val)
                                                                <i class="fa-solid fa-arrow-right mr-2"></i><strong>{{ ucfirst($key) }}:</strong> {{ $val }}<br>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ $value->per_unit}}
                                            </td>
                                           
                                            <td>
                                                {{ $value->order_quantity}}
                                            </td>
                                            <td>
                                                <strong>{{ $value->item_price }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $value->order_quantity_amount }}</strong>
                                            </td>
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">

                    <div class="card custom-border-card">
                        <div class="order-summary">
                            <h4>{{__('label.total_order_amount')}}</h4>
                            <div class="summary-item">
                                <span>{{__('label.sub_total')}}</span>
                                <span>{{currency_Code()}}{{$orders['item_total']}}</span>
                            </div>
                            <div class="summary-item">
                                <span>{{__('label.delivery_charges')}}</span>
                                <span>{{currency_Code()}}{{$orders['delivery_charges']}}</span>
                            </div>
                            <div class="summary-item">
                                <span>{{__('label.handling_charges')}}</span>
                                <span>{{currency_Code()}}{{$orders['handling_charges']}}</span>
                            </div>
                            <div class="summary-item">
                                <span>{{__('label.donation_amount')}}</span>
                                <span>{{currency_Code()}}{{$orders['donation_amount']}}</span>
                            </div>
                            <div class="summary-item">
                                <span>{{__('label.discount_amount')}}</span>
                                <span>{{currency_Code()}}{{$orders['discount_amount']}}</span>
                            </div>
                            <hr />
                            <div class="summary-item total">
                                <span>{{__('label.total_amount')}}</span>
                                <span>{{currency_Code()}}{{ $orders['total_payable_amount'] }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card custom-border-card">
                <h5 class="card-header">{{ __('label.order_tracking') }}</h5>
            
                @php
                    $allSteps = [
                        1 => ['icon' => 'fas fa-hourglass-half', 'label' => __('label.order_placed')],
                        2 => ['icon' => 'fas fa-check-circle', 'label' => __('label.confirmed')],
                        3 => ['icon' => 'fas fa-box-open', 'label' => __('label.picking')],
                        4 => ['icon' => 'fas fa-box', 'label' => __('label.packed')],
                        5 => ['icon' => 'fas fa-truck-fast', 'label' => __('label.out_for_delivery')],
                        6 => ['icon' => 'fas fa-truck', 'label' => __('label.delivered')],
                    ];
            
                    $rejectedStep = [
                        8 => ['icon' => 'fas fa-times-circle text-danger', 'label' => __('label.rejected')],
                    ];
            
                    $statusDates = [];
                    $lastStatus = 0;
            
                    if (!empty($order_tracking) && count($order_tracking) > 0) {
                        $trackingCollection = collect($order_tracking);
            
                        foreach ($trackingCollection as $track) {
                            $statusDates[$track->status] = date('d M Y h:i A', strtotime($track->created_at));
                            $lastStatus = max($lastStatus, $track->status);
                        }
                    }
            
                    // Decide which steps to show
                    if ($lastStatus == 8) {
                        // If Rejected → show only Order Placed + Rejected
                        $steps = [
                            1 => $allSteps[1],
                            8 => $rejectedStep[8],
                        ];
                    } else {
                        // Otherwise show steps up to last status
                        $steps = [];
                        foreach ($allSteps as $key => $step) {
                            if ($key <= $lastStatus) {
                                $steps[$key] = $step;
                            }
                        }
                    }
                @endphp
            
            
                {{-- Delivered Box --}}
                @if(isset($statusDates[6]))
                    <div class="order-details">
                        <div class="order-header">
                            <div class="order-number">
                                <div class="delivery-status mt-3">
                                    <p>
                                        <strong>{{ __('label.delivered') }}:</strong>
                                        {{ $statusDates[6] }}
                                    </p>
                                    <p>{{ __('label.deliverd_note') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            
            
                <div class="order-tracking">
                    @foreach($steps as $key => $step)
                        <div class="step {{ $lastStatus >= $key ? 'active' : '' }}">
                            <div class="icon">
                                <i class="{{ $step['icon'] }}"></i>
                            </div>
                            <div class="label">
                                <p>{{ $step['label'] }}</p>
                                <span>{{ $statusDates[$key] ?? '' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            
            </div>

            @if(!empty($delivery_instructor) && count($delivery_instructor) > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card custom-border-card">
                            <h5 class="card-header">{{ __('label.delivery_instructor') }}</h5>
                            <div class="card-body">
                                <ul class="delivery-instructor-list">
                                    @foreach ($delivery_instructor as $value)
                                    <li>
                                        <div class="line"></div>
                                        <img src="{{ $value['image'] }}" alt="Instructor" class="delivery-instructor-img">
                                        <span class="delivery-instructor-title">{{ $value['title'] }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                   
                </div>
                
            @endif
                
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.change_order_status')}}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="order_change_status">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.order_status')}}</label>
                                            <select class="form-control" name="order_status" id="edit_order_status">
                                               <option value="1">{{__('label.pending')}}</option>
                                               <option value="2">{{__('label.confirmed')}}</option>
                                               <option value="3">{{__('label.shipped')}}</option>
                                               <option value="6">{{__('label.rejected')}}</option>
                                             
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="edit_id">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="change_status()">{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        sidebar_down(480);
        
        $(document).on("click", ".order_status_change", function() {
            var id = $(this).data('id');
            var order_status = $(this).data('order_status');
            // Set the ID in the modal
            $(".modal-body #edit_id").val(id);
            $("#edit_order_status").val(order_status);

            if (order_status != 1) {
            
                $("#edit_order_status option[value='6']").hide();
            
                // Safety: If rejected previously selected
                if ($("#edit_order_status").val() == 6) {
                    $("#edit_order_status").val(1);
                }
            
            } else {
                // 🟢 Pending → Show Rejected
                $("#edit_order_status option[value='6']").show();
            }
        });


        function change_status() {
      
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#order_change_status")[0]);

                var Edit_Id = $("#edit_id").val();
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
                        $('#EditModel').modal('toggle');
                        get_responce_message(resp, 'order_change_status', '{{ route("admin.order.edit" ,[$group_order_no]) }}');
                       
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
