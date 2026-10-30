@extends('admin.layout.page-app')
@section('page_title', __('label.order_cancel_request'))
@section('tab_title', __('label.order_cancel_request'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.order_cancel_request') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.order_cancel_request') }}
                        </li>
                    </ol>
                </div>

            </div>
            
            <div class="page-search">
                <div class="sorting mr-3" style="width: 30%;">
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
               
                <div class="sorting mr-3" style="width: 30%;">
                    <select class="form-control" name="customer_id" id="input_customer_id">
                        <option value="all">{{ __('label.all_customer') }}</option>
                        @foreach ($customer as $key => $value)
                            <option value="{{ $value->id }}">
                                {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sorting mr-3" style="width: 30%;">
                    <select class="form-control" name="reason_id" id="input_reason_id">
                        <option value="all">{{ __('label.all_reason') }}</option>
                        @foreach ($reason as $key => $value)
                            <option value="{{ $value->id }}">
                                {{ $value->reason }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sorting mr-3" style="width: 19%;">
                    <input type="date" name="input_date" id="input_date"  value="{{ request()->get('input_date') }}" placeholder="dd-mm-yyyy" class="form-control">
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

            <div class="modal fade" id="decisionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content decision-modal-content" id="decisionModalContent">
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close"> 
                            <span aria-hidden="true">&times;</span>
                        </button>  
                        <div class="decision-modal-body">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="icon">
                                <img src="" alt="Decision" id="modal_icon" class="decision-icon-img">
                            </div>
                            <h2 id="modal_title"></h2>
                            <p class="modal-style" style="font: bold;" id="modal_message"></p>
            
                            <!-- Button that triggers JavaScript action based on context -->
                            <a href="javascript:void(0);" id="modal_button" class="badge mt-1"></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        sidebar_down(480);

        $("#input_order_id ,#input_customer_id").select2();

        $(document).ready(function() {
            var previousGroupOrderID = null; // To track the previous group_order_id
            var groupTotals = {}; // To store totals for each group_order_id

            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.cancel-request.index') }}",
                    data: function(d) {
                        d.input_order_id = $('#input_order_id').val();
                        d.input_reason_id = $('#input_reason_id').val();
                        d.input_customer_id = $('#input_customer_id').val();
                        d.input_date = $('#input_date').val();
                       
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
                        data: 'reason.reason',
                        name: 'reason.reason',
                        render: function(data) {
                            return `<div style="text-align: left;"><span style="font-size: 16px; font-weight: 600;">${data}</span></div>`;
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'product',
                        name: 'product',
                        orderable: false,
                        searchable: false
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    
                   
                ],
              
            });

            // Redraw the table when filters are applied
            $('#input_order_id,#input_reason_id, #input_customer_id ,#input_date')
                .change(function() { table.draw();
                
            });
           
        });

        $(document).on("click", ".openModal", function() {
        var id = $(this).data('id');
        var action = $(this).data('action');
       
        $(".decision-modal-body #edit_id").val(id);
        $("#modal_id").val(id);

        if (action === "approved") {
            $("#modal_icon").attr("src", "{{ asset('assets/imgs/right.png') }}");
            $("#modal_title").text("{{__('label.approved_text')}}").removeClass("decision-error-text").addClass("decision-success-text");
            $("#modal_message").text("{{__('label.approved_description')}}");
            $("#modal_button").text("{{__('label.approved')}}").removeClass("badge-danger").addClass("badge-success");
            $("#modal_button").attr("onclick", "change_status(1);"); 
        } else if (action === "rejected") {
            $("#modal_icon").attr("src", "{{ asset('assets/imgs/wrong.png') }}");
            $("#modal_title").text("{{__('label.rejected_text')}}").removeClass("decision-success-text").addClass("decision-error-text");
            $("#modal_message").text("{{__('label.rejected_description')}}");
            $("#modal_button").text("{{__('label.rejected')}}").removeClass("badge-success").addClass("badge-danger");
            $("#modal_button").attr("onclick", "change_status(0);"); // Set the button to trigger the rejected action
        }

    });
       
    function change_status(status) {
        
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if (Demo_Mode == 1) {

            $("#dvloader").show();
            var Edit_Id = $("#edit_id").val();
            
            var url = '{{ route("admin.cancel-request.show" , ":id") }}';
            url = url.replace(':id', Edit_Id);
                
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: url,
                data:{
                    status:status
                },
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, '', '{{ route("admin.cancel-request.index") }}');
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
