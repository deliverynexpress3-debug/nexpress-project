@extends('admin.layout.page-app')
@section('page_title', __('label.order_return'))
@section('tab_title', __('label.order_return'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.order_return') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.order_return') }}
                        </li>
                    </ol>
                </div>

            </div>
            <div class="order-counter-container">
                <div class="order-counter">
                    <div class="order-icon-container order-counter-gray">
                        <i class="fa-solid fa-hourglass-start"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $PendingCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.pending') }}</p>
                    </div>
                </div>

                <div class="order-counter">
                    <div class="order-icon-container order-counter-green">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $ApprovedCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.approved') }}</p>
                    </div>
                </div>

                <div class="order-counter">
                    <div class="order-icon-container order-counter-red">
                        <i class="fa-solid fa-times-circle"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $RejectedCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.rejected') }}</p>
                    </div>
                </div>

                <div class="order-counter">
                    <div class="order-icon-container order-counter-orange">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $PickedCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.picked') }}</p>
                    </div>
                </div>

                <div class="order-counter">
                    <div class="order-icon-container order-counter-purple">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div class="order-text-container">
                        <p class="count">{{ $CompletedCount ?? 0 }}</p>
                        <p class="order-label">{{ __('label.completed') }}</p>
                    </div>
                </div>

            </div>
          
            <div class="page-search mt-4">
                <div class="sorting w-50 mr-3">
                    <label>{{ __('label.sort_by') }}</label>
                    <select class="form-control" name="order_id" id="input_order_id">
                        <option value="all">{{ __('label.all_orders') }}</option>
                        @foreach ($order as $key => $value)
                            <option value="{{ $value->id }}">
                                {{ $value->order_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sorting w-50 mr-3">
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
                        <option value="2">{{ __('label.approved') }}</option>
                        <option value="3">{{ __('label.rejected') }}</option>
                        <option value="4">{{ __('label.picked') }}</option>
                        <option value="5">{{ __('label.completed') }}</option>
                      

                    </select>

                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th>{{ __('label.orders') }}</th>
                            <th>{{ __('label.customer') }}</th>
                            <th>{{ __('label.return_reason') }}</th>
                            <th>{{ __('label.product') }}</th>
                            <th>{{ __('label.order_quantity') }}</th>
                            <th>{{ __('label.amount') }}</th>
                            <th>{{ __('label.status') }}</th>
                            
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        sidebar_down(480);

        $("#input_order_id ,#input_item_id ,#input_customer_id").select2();

        $(document).ready(function() {
            var previousGroupOrderID = null; // To track the previous group_order_id
            var groupTotals = {}; // To store totals for each group_order_id

            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.return-request.index') }}",
                    data: function(d) {
                        d.input_order_id = $('#input_order_id').val();
                        d.input_item_id = $('#input_item_id').val();
                        d.input_customer_id = $('#input_customer_id').val();
                        d.input_status = $('#input_status').val();
                       
                    },
                },
                columns: [
                    {
						data: 'orders',
						name: 'orders',
						render: function(data) {
                            return data ? data : "-";
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
                        data: 'return_reason.reason',
                        name: 'return_reason.reason',
                        render: function(data) {
                            return `<div style="text-align: left;"><span style="font-size: 16px; font-weight: 600;">${data}</span></div>`;
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'item.portrait_img',
                        name: 'item.portrait_img',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let itemName = row.item.name ? row.item.name : "-";
                            return `
                                <div class="d-flex align-items-center">
                                    <a href="${data}" target="_blank">
                                        <img src="${data}" class="img-thumbnail" style="height:55px; width:60px; object-fit:cover; border-radius: 8px;">
                                    </a>
                                    <span class="ml-3 font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">${itemName}</span>
                                </div>
                                `;
                        }
                    },
                    {
                        data: 'order.order_quantity',
                        name: 'order.order_quantity',
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
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    
                   
                ],
              
            });

            // Redraw the table when filters are applied
            $('#input_order_id,#input_item_id, #input_customer_id ,#input_status')
                .change(function() {
                    table.draw();
                });
           
        });

        function change_status(id, status) {
          
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var url = `{{ route('admin.return-request.show', '') }}/${id}`;

                $.ajax({
                    type: "GET",
                    url: url,
                    data:{ status: status ,
                            order_id :id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(resp) {
                        $("#dvloader").hide();

                        if (resp.status == 200) {
                            get_responce_message(resp, '', '{{ route("admin.return-request.index") }}');
                        } else {
                            toastr.error(resp.errors);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
        };
    </script>
@endsection
