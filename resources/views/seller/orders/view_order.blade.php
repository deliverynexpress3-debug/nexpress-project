@extends('seller.layout.page-app')
@section('page_title', __('label.view_orders'))
@section('tab_title', __('label.orders'))
@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.orders') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('seller.order.index') }}">{{ __('label.orders') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.view_orders') }}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('seller.order.index') }}" class="btn btn-default mw-120"
                        style="margin-top:-14px">{{ __('label.back') }}</a>
                </div>

            </div>


            <div class="card custom-border-card">
                <div class="order-details">

                    <div class="order-header">
                        <div class="order-number">
                            <strong>{{ __('label.order_id') }}</strong>
                            <p>#{{ $data['group_order_id'] }}</p>
                        </div>

                        <div class="order-date-invoice">
                           
                            @if($data['order_status'] ==5)
                            <span class="ml-4"><strong>{{__('label.order_date')}}:</strong> {{ $data['order_date'] }}</span>
                            <a href="{{ route('admin.order.download.invoice', ['type' => 'one', 'id' => $data['order_number']]) }}">
                                <button class="invoice-btn">{{__('label.invoice')}}</button>
                            </a>
                            @else
                            <span class="ml-5"><strong>{{__('label.order_date')}}:</strong> {{ $data['order_date'] }}</span>
                            @endif

                        </div>

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="card custom-border-card">

                        <div class="info-section">
                            <!-- Customer Info -->
                            <div class="info-card">
                                <h4>{{ __('label.customer_info') }}</h4>
                                <div class="info-content">
                                    <div class="info-text">
                                        <p><strong>{{ __('label.name') }}:</strong> {{ $data->customer->full_name }}</p>
                                        <p><strong>{{ __('label.contact') }}:</strong> {{ $data->customer->country_code }}
                                            {{ $data->customer->mobile_number }}</p>
                                        <p><strong>{{ __('label.email') }}:</strong> {{ $data->customer->email }}</p>
                                    </div>
                                    <img src="{{ $data->customer->image }}" alt="Customer Image">
                                </div>
                            </div>

                            <!-- Shipping Info -->
                            <div class="info-card">
                                <h4>{{ __('label.shipping_info') }}</h4>
                                <div class="info-content">
                                    <div class="info-text">
                                        <p><strong>{{ __('label.name') }}:</strong> {{ $data->customer->full_name }}</p>
                                        <p><strong>{{ __('label.contact') }}:</strong> {{ $data->customer->country_code }}
                                            {{ $data->customer->mobile_number }}</p>
                                        <p><strong>{{ __('label.address') }}:</strong>
                                            {{ $data->customer_address->address }}</p>
                                    </div>
                                    <img src="{{ $data->customer->image }}" alt="Customer Image">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">

                    <div class="card custom-border-card">
                        <div class="order-summary">
                            <h4>{{__('label.payment_info')}}</h4>
                            <div class="summary-item">
                                <span>{{__('label.paymentmethod')}}</span>
                                @if($data['payment_method'] ==1)
                                <span class="order-badge order-badge-gray">{{ __('label.cod') }}</span>
                                @elseif($data['payment_method'] ==2)
                                <span class="order-badge order-badge-gray">{{ __('label.online') }}</span>
                                @else
                                <span class="order-badge order-badge-gray"> - </span>
                                @endif
                              
                            </div>
                            <div class="summary-item">
                                <span>{{__('label.payment')}}</span>
                                @if($data['payment_status'] ==1)
                                <span><h4 class="text-danger">{{__('label.pending')}}</h4></span>
                                @else
                                <span><h4 class="text-success">{{__('label.paid')}}</h4></span>
                                @endif
                               
                            </div>

                        </div>

                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-9">
                    <div class="card custom-border-card">
                        <div class="table-responsive">
                            <table class="table table-striped text-center table-bordered" id="datatable">
                                <thead>
                                    <tr style="background: #F9FAFF;">

                                        <th>{{ __('label.order_no') }}</th>
                                        <th>{{ __('label.product') }}</th>
                                        <th>{{ __('label.variation') }}</th>
                                        <th>{{ __('label.order_quantity') }}</th>
                                        <th>{{ __('label.item_amount') }}</th>
                                        <th>{{ __('label.order_status') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h6 class='primary-color'><strong> {{ $data->order_number }} </strong></h6>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{$data->item->portrait_img}}" target="_blank">
                                                    <img src="{{ $data->item->portrait_img }}" class="img-thumbnail"
                                                        style="height:55px; width:60px; object-fit:cover; border-radius: 8px;">
                                                </a>
                                                <span class="ml-3 font-weight-bold"
                                                    style="white-space: nowrap;">{{ $data->item->name }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            @if($data->variation != null)
                                                @php
                                                    $variationData = json_decode($data->variation, true);
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
                                            {{ $data->order_quantity }}
                                        </td>
                                        <td>
                                            {{ $data->item_amount }}
                                        </td>
                                        <td>
                                            @if ($data->order_status == 1)
                                                <span
                                                    class="order-badge order-badge-gray mr-1">{{ __('label.pending') }}</span>
                                            @elseif($data->order_status == 2)
                                                <span
                                                    class="order-badge order-badge-blue mr-1">{{ __('label.confirmed') }}</span>
                                            @elseif($data->order_status == 3)
                                                <span
                                                    class="order-badge order-badge-orange mr-1">{{ __('label.shipped') }}</span>
                                            @elseif($data->order_status == 4)
                                                <span
                                                    class="order-badge order-badge-purple mr-1">{{ __('label.out_for_delivery') }}</span>
                                            @elseif($data->order_status == 5)
                                                <span
                                                    class="order-badge order-badge-green mr-1">{{ __('label.delivered') }}</span>
                                            @elseif($data->order_status == 6)
                                                <span
                                                    class="order-badge order-badge-red mr-1">{{ __('label.cancelled') }}</span>
                                            @elseif($data->order_status == 7)
                                                <span
                                                    class="order-badge order-badge-yellow mr-1">{{ __('label.return_requested') }}</span>
                                            @elseif($data->order_status == 8)
                                                <span
                                                    class="order-badge order-badge-light-blue mr-1">{{ __('label.return_inprocess') }}</span>
                                            @elseif($data->order_status == 9)
                                                <span
                                                    class="order-badge order-badge-dark-green mr-1">{{ __('label.returned') }}</span>
                                            @elseif($data->order_status == 10)
                                                <span
                                                    class="order-badge order-badge-dark-orange mr-1">{{ __('label.returnedrejected') }}</span>
                                            @endif

                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card custom-border-card">
                        <div class="order-summary">
                            <h4>{{ __('label.total_order_amount') }}</h4>
                            <div class="summary-item">
                                <span>{{ __('label.sub_total') }}</span>
                                <span>€ {{ $total_amount }}</span>
                            </div>
                            <div class="summary-item">
                                <span>{{ __('label.shipping_charges') }}</span>
                                <span>€ {{ $delivery_charges }}</span>
                            </div>
                            <div class="summary-item">
                                <span>{{ __('label.tax_charges') }}</span>
                                <span>€ {{ $tax_amount }}</span>
                            </div>
                            <div class="summary-item">
                                <span>{{ __('label.discount_amount') }}</span>
                                <span>€ {{ $discount_amount }}</span>
                            </div>
                            <hr />
                            <div class="summary-item total">
                                <span>{{ __('label.total_amount') }}</span>
                                <span>€ {{ $final_total }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card custom-border-card">
                <h5 class="card-header">{{ __('label.order_tracking') }}</h5>
                <div class="order-details">
                    <div class="order-header">
                        <div class="order-number">
                            @if ($data['order_status'] == 5)
                                <div class="delivery-status mt-3">
                                    <p><strong>{{ __('label.delivered') }}: </strong>{{ $data['tracking_order_date'] }}
                                    </p>
                                    <p>{{ __('label.deliverd_note') }}</p>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>

                <div class="order-tracking">
                    <div class="step {{ $data['order_status'] == 1 || $data['order_status'] >= 2 ? 'active' : '' }}">
                        <div class="icon">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                        <div class="label">
                            <p>{{ __('label.place_order') }}</p>
                            @if ($data['order_status'] == 1)
                                <span>{{ $data['tracking_order_date'] }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="step {{ $data['order_status'] == 2 || $data['order_status'] >= 2 ? 'active' : '' }}">
                        <div class="icon">
                            <i class="fa fa-clipboard-check"></i>

                        </div>
                        <div class="label">
                            <p>{{ __('label.confirmed_order') }}</p>
                            @if ($data['order_status'] == 2)
                                <span>{{ $data['tracking_order_date'] }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="step {{ $data['order_status'] == 3 || $data['order_status'] >= 4 ? 'active' : '' }}">
                        <div class="icon">
                            <i class="fa-solid fa-truck"></i>

                        </div>
                        <div class="label">
                            <p>{{ __('label.shipped_order') }}</p>
                            @if ($data['order_status'] == 3)
                                <span>{{ $data['tracking_order_date'] }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="step {{ $data['order_status'] == 4 || $data['order_status'] >= 5 ? 'active' : '' }}">
                        <div class="icon">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div class="label">
                            <p>{{ __('label.out_for_delivery') }}</p>
                            @if ($data['order_status'] == 4)
                                <span>{{ $data['tracking_order_date'] }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="step {{ $data['order_status'] == 5 ? 'active' : '' }}">
                        <div class="icon">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <div class="label">
                            <p>{{ __('label.product_delivered') }}</p>
                            @if ($data['order_status'] == 5)
                                <span>{{ $data['tracking_order_date'] }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="step {{ $data['order_status'] == 5 ? 'active' : '' }}">
                        <div class="icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="label">
                            <p>{{ __('label.completed') }}</p>
                            @if ($data['order_status'] == 5)
                                <span>{{ $data['tracking_order_date'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.change_order_status') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="order_change_status">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('label.order_status') }}</label>
                                            <select class="form-control" name="order_status" id="edit_order_status">


                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="edit_id">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                    onclick="change_status()">{{ __('label.update') }}</button>
                                <button type="button" class="btn btn-cancel mw-120"
                                    data-dismiss="modal">{{ __('label.close') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('pagescript')
   
@endsection
