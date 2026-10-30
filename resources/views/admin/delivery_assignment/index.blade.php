@extends('admin.layout.page-app')
@section('page_title', __('label.delivery_assignments'))
@section('tab_title', __('label.delivery_assignments'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.delivery_assignments') }} </h1>
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

            <!-- Counter -->
            <div class="row counter-row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-user-check fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($AssignedOrders ?? 0) }}</h3>
                                <span>{{ __('label.assign') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card custom-card card-color-primary">
                        <div class="card-body">
                            <div class="card-icon-primary card-color-primary">
                                <i class="fa-solid fa-box-open fa-2x"></i>
                            </div>
                            <div class="text-right">
                                <h3>{{ No_Format($PickedUpOrders ?? 0) }}</h3>
                                <span>{{ __('label.pickup') }}</span>
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
                                <h3>{{ No_Format($OnthewayOrders ?? 0) }}</h3>
                                <span>{{ __('label.on_the_way') }}</span>
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
                                <h3>{{ No_Format($DeliveredOrders ?? 0) }}</h3>
                                <span>{{ __('label.delivered') }}</span>
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
            <div class="page-search mb-3">
                <div class="input-group" title="Search">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                    </div>
                    <input type="text" id="input_search" class="form-control" placeholder="{{ __('label.search') }}" aria-label="Search" aria-describedby="basic-addon1">
                </div>
                
                <div class="sorting w-50 mr-2">
                    <label>{{ __('label.sort_by') }}</label>
                        <select class="form-control" name="delivery_boy_id" id="input_delivery_boy_id">
                            <option value="all">{{ __('label.all_deliveryboy') }}</option>
                            @foreach ($deliveryboy as $key => $value)
                                <option value="{{ $value->id }}">
                                    {{ $value['user_name'] }} → {{ $value['full_name'] }}
                                </option>
                            @endforeach
                        </select>
                </div>
                <div class="sorting w-25 mr-2">
                    <select class="form-control" name="status" id="input_status">
                        <option value="all">{{ __('label.all_status') }}</option>
                        <option value="1">{{ __('label.assign') }}</option>
                        <option value="2">{{ __('label.pickup') }}</option>
                        <option value="3">{{ __('label.on_the_way') }}</option>
                        <option value="4">{{ __('label.delivered') }}</option>
                        <option value="5">{{ __('label.cancelled') }}</option>
                        <option value="6">{{ __('label.rejected') }}</option>
                       
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
                            <a class="nav-link active" data-status="1" href="#" role="tab" aria-selected="true">{{__('label.assign')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="2" href="#" role="tab" aria-selected="false">{{__('label.pickup')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="3" href="#" role="tab" aria-selected="false">{{__('label.on_the_way')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="4" href="#" role="tab" aria-selected="false">{{__('label.delivered')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="5" href="#" role="tab" aria-selected="false">{{__('label.cancelled')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="6" href="#" role="tab" aria-selected="false">{{__('label.rejected')}}</a>
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
                                            <th>{{ __('label.delivery_boy') }}</th>
                                            <th>{{ __('label.pickup_details') }}</th>
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
                            <input type="hidden" name="id" id="id">
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

        $("#input_delivery_boy_id").select2();
        const currencyCode = "{{ currency_code() }}";

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.delivery-assignment.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                        d.input_delivery_boy_id = $('#input_delivery_boy_id').val();
                        d.input_status = $('#input_status').val();
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
                        data: 'deliveryboy',
                        name: 'deliveryboy',
                        render: function(data, type, row) {
                            return `<div style="text-align: center;">${row.deliveryboy.full_name || ''} <br><span style="font-size: 14px; font-weight: 600;">${row.deliveryboy.user_name || ''}</span>`;
                        }
                    },
                  
                    {
                        data: 'pickup_details',
                        name: 'pickup_details',
                        orderable: false,
                        searchable: false
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
            $('#input_delivery_boy_id ,#input_status')
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

        $(document).on('click', '.order-status-btn', function () {
            var Id = $(this).data('id');
            let status  = $(this).data('status');

            $('#id').val(Id);
            $('#req_status').val(status);
          
            let message = "";
            let iconClass = "";
            let iconColor = "";

            // Convert status to string (safe comparison)
            status = status.toString();

            if (status === "1") { // Accept
                message = "<b>{{__('label.order_msg')}} <span class='text-success'>{{__('label.ACCEPTED')}}</span>?</b>";
                iconClass = "fa-circle-check fa-2xl";
                iconColor = "text-success";
            }

            else if (status === "6") { // Reject
                message = "<b>{{__('label.order_msg')}} <span class='text-danger'>{{__('label.REJECTED')}}</span>?</b>";
                iconClass = "fa-circle-xmark fa-2xl";
                iconColor = "text-danger";
            }

            else if (status === "2") { // Pickup Confirm
                message = "<b>{{__('label.order_msg')}} <span class='text-primary'>{{__('label.PICKUP_CONFIRMED')}}</span>?</b>";
                iconClass = "fa-truck fa-2xl";
                iconColor = "text-primary";
            }

            else if (status === "4") { // Deliverd
                message = "<b>{{__('label.order_msg')}} <span class='text-success'>{{__('label.DELIVERED')}}</span>?</b>";
                iconClass = "fa-circle-check fa-2xl";
                iconColor = "text-success";
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
                var formData = $("#orderStatusForm").serialize(); // 👈 FormData nahi
            
                var Edit_Id = $("#order_id").val();
                var url = '{{ route('admin.delivery-assignment.show', ':id') }}';
                url = url.replace(':id', Edit_Id);
            
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'GET',
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
                    
                        get_responce_message(resp, 'orderStatusForm', "{{ route('admin.delivery-assignment.index') }}");
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
