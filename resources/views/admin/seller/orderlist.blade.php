@extends('admin.layout.page-app')
@section('page_title', __('label.seller_orders'))
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.seller.index') }}">{{ __('label.seller') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.orders') }}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.seller.index') }}" class="btn btn-default mw-120"
                        style="margin-top:-14px">{{ __('label.back') }}</a>
                </div>

            </div>

                <!-- Search -->
                
                <div class="page-search">
                    <div class="input-group" title="Search">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control"
                            placeholder="{{ __('label.search_orders') }}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                   
                        <div class="sorting mr-3" style="width: 50%;">
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
                                <th>{{ __('label.payment_method') }}</th>
                                <th>{{ __('label.payment_status') }}</th>
                                <th>{{ __('label.order_status') }}</th>
                                <th>{{ __('label.action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Assign Delivery Model -->
                <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.delivery_assignment') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="delivery_assign" autocomplete="off">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label> {{ __('label.delivery_type') }}<span class="text-danger">*</span></label>
                                            <div class="row">

                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="delivery_status" value="1"
                                                        id="edit_status_1" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="edit_status_1">{{ __('label.assigned') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="delivery_status" value="2"
                                                        id="edit_status_2" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="edit_status_2">{{ __('label.picked_up') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="delivery_status" value="3"
                                                        id="edit_status_3" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="edit_status_3">{{ __('label.out_for_delivery') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="delivery_status" value="4"
                                                        id="edit_status_4" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="edit_status_4">{{ __('label.delivered') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="delivery_status" value="5"
                                                        id="edit_status_5" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="edit_status_5">{{ __('label.return_requested') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="delivery_status" value="6"
                                                        id="edit_status_6" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="edit_status_6">{{ __('label.return_pickup') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="delivery_status" value="7"
                                                        id="edit_status_7" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="edit_status_7">{{ __('label.return_inprocess') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="delivery_status" value="8"
                                                        id="edit_status_8" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="edit_status_8">{{ __('label.return_completed') }}</label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="DeliveryBoy">
                                        <div class="form-group">
                                            <label>{{ __('label.delivery_boys') }}<span
                                                    class="text-danger">*</span></label>
                                            <select name="delivery_boy_id" id="edit_delivery_boy_id" class="form-control"
                                                style="width:100%!important;">


                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-6" id="Vehicle">
                                        <div class="form-group">
                                            <label>{{ __('label.vehicle') }}<span class="text-danger">*</span></label>
                                            <select name="vehicle_id" id="edit_vehicle_id" class="form-control"
                                                style="width:100%!important;">
                                                <option value="">{{ __('label.select_vehicle') }}</option>
                                                @foreach ($vehicle as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['name'] }} -
                                                        {{ $item['number'] }}</option>
                                                @endforeach

                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-4" id="PickUpTime">
                                        <div class="form-group">
                                            <label>{{ __('label.pickup_time') }}<span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" id="edit_pickup_time"
                                                name="pickup_time" min="{{ date('Y-m-d\TH:i') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-8" id="PickUpLocation">
                                        <div class="form-group">
                                            <label>{{ __('label.pickup_location') }}<span
                                                    class="text-danger">*</span></label>
                                            <textarea name="pickup_location" id="edit_pickup_location" class="form-control" rows="1"
                                                placeholder="{{ __('label.enter_pickup_location') }}"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="ReturnPickUpTime">
                                        <div class="form-group">
                                            <label>{{ __('label.return_pickup_time') }}<span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control" id="edit_return_pickup_time"
                                                name="return_pickup_time" min="{{ date('Y-m-d\TH:i') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('label.notes') }}<span class="text-danger">*</span></label>
                                            <textarea name="notes" id="edit_notes" class="form-control" rows="2"
                                                placeholder="{{ __('label.start_right_here') }}"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="order_id" id="edit_order_id">
                                <input type="hidden" name="seller_id" id="edit_seller_id">
                                <input type="hidden" name="id" id="edit_id">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                    onclick="update_delivery_assign()">{{ __('label.update') }}</button>
                                <button type="button" class="btn btn-cancel mw-120" id="closeButton"
                                    data-dismiss="modal">{{ __('label.close') }}</button>
                                <input type="hidden" name="_method" value="PATCH">
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
      

        $("#input_industries_id , #input_item_id ,#input_customer_id").select2();

        $(document).ready(function() {
            var previousGroupOrderID = null; // To track the previous group_order_id
            var groupTotals = {}; // To store totals for each group_order_id

            var table = $('#datatable').DataTable({
                dom: "<'top'f>rt<'row'<'col-2'i><'col-1'l><'col-9'p>>",
                searching: false,
                autoWidth: false,
                responsive: true,
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [10, 100, 500, -1],
                    [10, 100, 500, "All"]
                ],
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-chevron-left'></i>",
                        next: "<i class='fa-solid fa-chevron-right'></i>"
                    }
                },
                ajax: {
                    url: "{{ route('admin.seller.order.list', $seller_id) }}",
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
                        width: '6%',
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
            
            });

            // Redraw the table when filters are applied
            $( '#input_payment_method ,#input_item_id ,#input_customer_id ,#input_status').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });
        $(document).on("click", ".delivery_assign", function() {
            var id = $(this).data('id');
            var order_id = $(this).data('order_id');
            var delivery_boy_id = $(this).data('delivery_boy_id');
            var vehicle_id = $(this).data('vehicle_id');
            var pickup_time = $(this).data('pickup_time');
            var pickup_location = $(this).data('pickup_location');
            var return_pickup_time = $(this).data('return_pickup_time');
            var notes = $(this).data('notes');
            var status = $(this).data('delivery_status');
           
            // Set form values in the modal
            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_order_id").val(order_id);
            $(".modal-body #edit_pickup_time").val(pickup_time);
            $(".modal-body #edit_pickup_location").val(pickup_location);
            $(".modal-body #edit_return_pickup_time").val(return_pickup_time);
            $(".modal-body #edit_notes").val(notes);
            $('#edit_status_' + status).prop('checked', true);

            if(status == 1){
                $('#DeliveryBoy').show();
                $('#Vehicle').show();
                $('#PickUpTime').show();
                $('#PickUpLocation').show();
                $('#ReturnPickUpTime').hide();
            }else if(status == 5){
                $('#DeliveryBoy').show();
                $('#Vehicle').show();
                $('#ReturnPickUpTime').show();
                $('#PickUpTime').hide();
                $('#PickUpLocation').hide();
            
            } else if(status ==2 || status ==3 || status ==4 || status ==6 || status ==7 || status ==8) {
                $('#DeliveryBoy').hide();
                $('#Vehicle').hide();
                $('#ReturnPickUpTime').hide();
                $('#PickUpTime').hide();
                $('#PickUpLocation').hide();
            }

            const deliveryStatusRadios = document.querySelectorAll('input[name="delivery_status"]');
           
            deliveryStatusRadios.forEach((radio) => {
                radio.addEventListener('click', function() {
                    // Retrieve the value of the clicked radio button
                    const selectedValue = this.value;
                   
                    if(selectedValue == 1){
                        $('#DeliveryBoy').show();
                        $('#Vehicle').show();
                        $('#PickUpTime').show();
                        $('#PickUpLocation').show();
                        $('#ReturnPickUpTime').hide();
                    }else if(selectedValue == 5){
                        $('#DeliveryBoy').show();
                        $('#Vehicle').show();
                        $('#ReturnPickUpTime').show();
                        $('#PickUpTime').hide();
                        $('#PickUpLocation').hide();
                    
                    } else if(selectedValue ==2 || selectedValue ==3 || selectedValue ==4 || selectedValue ==6 || selectedValue ==7 || selectedValue ==8) {
                        $('#DeliveryBoy').hide();
                        $('#Vehicle').hide();
                        $('#ReturnPickUpTime').hide();
                        $('#PickUpTime').hide();
                        $('#PickUpLocation').hide();
                    }
            
                   
                });
            });


            // Perform AJAX request to get delivery boys
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET", // You may want to use POST if sensitive data is involved
                url: '{{ route('admin.list.match.deliveryboy') }}', // Ensure this route is correct
                data: {
                    order_id: order_id // Send order ID to filter delivery boys
                },
                success: function(resp) {
                    var list = '<option value="">{{ __('label.select_delivery_boy') }}</option>';
                    if (resp.status === 200 && resp.result.length > 0) {
                        for (var i = 0; i < resp.result.length; i++) {
                            list += "<option value='" + resp.result[i].id + "'>" + resp.result[i]
                                .full_name + "</option>";
                        }
                    }
                    $('#edit_delivery_boy_id').html(list); // Update the dropdown list
                    $(".modal-body #edit_delivery_boy_id").val(delivery_boy_id).change(); 
                
                    var vehicleOptions = '<option value="">{{ __('label.select_vehicle') }}</option>';
                    if (resp.vehicles && resp.vehicles.length > 0) {
                        for (var j = 0; j < resp.vehicles.length; j++) {
                            var vehicle = resp.vehicles[j];
                            vehicleOptions += "<option value='" + vehicle.id + "'>" + vehicle.name + " - " + vehicle.number + "</option>";
                        }
                    }
                    $('#edit_vehicle_id').html(vehicleOptions); // Update the dropdown
                    $(".modal-body #edit_vehicle_id").val(vehicle_id).change(); // Set selected vehicle
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching delivery boys:", error); // Log any errors
                }
            });
        });
        document.getElementById('closeButton').addEventListener('click', function() {
            location.reload()   
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

                        get_responce_message(resp, 'delivery_assign', "{{ route('admin.seller.order.list',$seller_id) }}");
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
