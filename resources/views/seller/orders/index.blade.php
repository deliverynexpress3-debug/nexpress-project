@extends('seller.layout.page-app')
@section('page_title', __('label.orders'))
@section('tab_title', __('label.orders'))
@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.orders') }} </h1>
            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.orders') }}
                        </li>
                    </ol>
                </div>

            </div>

            <!--- Counter --->
            <div class="order-counter-container">
                <div class="order-counter">
                    <div class="order-icon-container order-counter-pending">
                        <i class="fa-solid fa-hourglass-start"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $PendingCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.pending') }}</p>
                    </div>
                </div>
                <div class="order-counter">
                    <div class="order-icon-container order-counter-confirmed">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $ConfirmedCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.confirmed') }}</p>
                    </div>
                </div>
                <div class="order-counter">
                    <div class="order-icon-container order-counter-shipped">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $ShippedCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.shipped') }}</p>
                    </div>
                </div>
                <div class="order-counter">
                    <div class="order-icon-container order-counter-out-delivery">
                        <i class="fa-solid fa-truck-fast"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $OutDeliveryCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.out_for_delivery') }}</p>
                    </div>
                </div>
                <div class="order-counter">
                    <div class="order-icon-container order-counter-delivered">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $DeliveredCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.delivered') }}</p>
                    </div>
                </div>
                <div class="order-counter">
                    <div class="order-icon-container order-counter-cancelled">
                        <i class="fa-solid fa-times-circle"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $CancelledCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.cancelled') }}</p>
                    </div>
                </div>
                <div class="order-counter">
                    <div class="order-icon-container order-counter-request">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $ReturnRequestCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.returnrequest') }}</p>
                    </div>
                </div>
                <div class="order-counter">
                    <div class="order-icon-container order-counter-inprocess">
                        <i class="fa-solid fa-spinner"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $ReturnInProcessCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.returninprocess') }}</p>
                    </div>
                </div>
                <div class="order-counter">
                    <div class="order-icon-container order-counter-returned">
                        <i class="fa-solid fa-undo-alt"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $ReturnedCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.returned') }}</p>
                    </div>
                </div>
                <div class="order-counter">
                    <div class="order-icon-container order-counter-returned-reject">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $ReturnedRejectedCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.returned_rejected') }}</p>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="card custom-border-card mt-4">
                <div class="page-search">
                    <div class="input-group" title="Search">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control"
                            placeholder="{{ __('label.search_orders') }}" aria-label="Search"
                            aria-describedby="basic-addon1">
                    </div>
                    
                    <div class="sorting mr-3" style="width: 45%;">
                        <label>{{ __('label.sort_by') }}</label>
                        <select class="form-control" name="payment_method" id="input_payment_method">
                            <option value="all">{{ __('label.all_method') }}</option>
                            <option value="1">{{ __('label.cod') }}</option>
                            <option value="2">{{ __('label.online') }}</option>
                        </select>
                    </div>
                    

                </div>
                <div class="page-search mb-3">
                    <div class="sorting w-50 mr-3">
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
                   
                    <div class="sorting w-50 mr-3">
                        <select class="form-control" name="customer_id" id="input_customer_id">
                            <option value="all">{{ __('label.all_customer') }}</option>
                            @foreach ($customer as $key => $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sorting w-50 mr-3">
                        <select class="form-control" name="status" id="input_status">
                            <option value="all">{{ __('label.all_status') }}</option>
                            <option value="1">{{ __('label.pending') }}</option>
                            <option value="2">{{ __('label.confirmed') }}</option>
                            <option value="3">{{ __('label.shipped') }}</option>
                            <option value="4">{{ __('label.out_for_delivery') }}</option>
                            <option value="5">{{ __('label.delivered') }}</option>
                            <option value="6">{{ __('label.cancelled') }}</option>
                            <option value="7">{{ __('label.return_request') }}</option>
                            <option value="8">{{ __('label.return_inprocess') }}</option>
                            <option value="9">{{ __('label.returned') }}</option>
                            <option value="10">{{ __('label.returnedrejected') }}</option>
                        </select>

                    </div>
                </div>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">

                                <th>{{ __('label.order_id') }}</th>
                                <th>{{ __('label.order_no') }}</th>
                                <th>{{ __('label.customer') }}</th>
                                <th>{{ __('label.product') }}</th>
                                <th>{{ __('label.order_quantity') }}</th>
                                <th>{{ __('label.paid_amount') }}</th>
                                <th>{{ __('label.payment_method') }}</th>
                                <th>{{ __('label.payment_status') }}</th>
                                <th>{{ __('label.order_status') }}</th>
                                <th>{{ __('label.action') }}</th>
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
    <script>

        $("#input_item_id ,#input_customer_id").select2();

        $(document).ready(function() {
            var previousGroupOrderID = null; // To track the previous group_order_id
            var groupTotals = {}; // To store totals for each group_order_id

            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('seller.order.index') }}",
                    data: function(d) {
                        d.input_payment_method = $('#input_payment_method').val();
                        d.input_item_id = $('#input_item_id').val();
                        d.input_customer_id = $('#input_customer_id').val();
                        d.input_status = $('#input_status').val();
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [{
                        data: 'group_order_id',
                        name: 'group_order_id',
                        visible: false, // Hide the group_order_id column
                    },
                    {
                        data: 'order_number',
                        name: 'order_number',
                        render: function(data) {
                            return data ?
                                `<h6 class='primary-color'><strong> ${data} </strong></h6>` : "-";

                        }
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
                        data: 'item.name',
                        name: 'item.name',
                        render: function(data, type, full, meta) {
                            return data 
                                ? '<div style="text-align: left; font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">' + data + '</div>'
                                : "-";
                        }
                    },
                    
                    {
                        data: 'order_quantity',
                        name: 'order_quantity',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount',
                        orderable: false,
                        searchable: false
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
                        data: 'payment_status',
                        name: 'payment_status',
                        render: function(data) {
                            if (data == 1) {
                                return "<h6 class='text-danger'><strong>{{ __('label.pending') }}</strong></h5>";
                            } else if (data == 2) {
                                return "<h6 class='text-success'><strong>{{ __('label.paid') }}</strong></h5>";
                            } else {
                                return "-";
                            }
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
                        width: '7%',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'group_action',
                        name: 'group_action',
                        orderable: false,
                        searchable: false,
                        visible: false // Hide the group_action column inside grouped rows
                    },

                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    groupTotals = {}; // Reset group totals before each draw

                    api.column(0, {
                        page: 'current'
                    }).data().each(function(group_order_id, i) {
                        var rowTotalAmount = parseFloat(api.cell(i, 5).data()) ||
                            0; // Get the total_amount for the current row
                        if (!groupTotals[group_order_id]) {
                            groupTotals[group_order_id] = 0;
                        }
                        groupTotals[group_order_id] +=
                            rowTotalAmount; // Accumulate the total for the group
                        if (last !== group_order_id) {
                            var actionIndex = api.column('group_action:name')
                                .index(); // Get the index of the action column

                            // Get the action content for the current row (group)
                            var actionContent = api.cell(i, actionIndex).data();

                            // Insert the group header with the Group Order ID and Action column content
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="11">' +
                                '{{ __('label.group_order_id') }}: <strong><span class="primary-color"> ' +
                                group_order_id + ' </span></strong>' +
                                '<span class="float-right">' + actionContent + '</span>' +
                                '</td></tr>'
                            );
                            last = group_order_id;
                        }
                    });

                    // Append the footer with the group total after the last row of each group
                    var currentGroupOrderID = null;
                    var footerRow = null;
                    api.column(0, {
                        page: 'current'
                    }).data().each(function(group_order_id, i) {
                        if (currentGroupOrderID !== group_order_id) {
                            if (footerRow) {
                                $(rows).eq(i - 1).after(
                                    footerRow
                                    ); // Append the previous footer before switching to the new group
                            }
                            footerRow =
                                '<tr style="background: #F9FAFF" class="group-footer">' +
                                '<td colspan="10">' +
                                '<strong>{{ __('label.total_group_amount') }} ' +
                                group_order_id +
                                ': ' +
                                '<span class="primary-color">' + groupTotals[group_order_id]
                                .toFixed(2) + '</span>' +
                                '</strong>' +
                                '</td>' +
                                '</tr>';


                            currentGroupOrderID = group_order_id;
                        }
                    });

                    if (footerRow) {
                        $(rows).eq(rows.length - 1).after(footerRow); // Append the last footer row
                    }
                },
            });

            // Redraw the table when filters are applied
            $('#input_payment_method ,#input_item_id,#input_customer_id ,#input_status')
                .change(function() {
                    table.draw();
                });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });

    </script>
@endsection
