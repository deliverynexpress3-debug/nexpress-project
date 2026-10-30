@extends('admin.layout.page-app')
@section('page_title', __('label.settlement'))
@section('tab_title', __('label.settlement'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')
         <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.settlement') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('label.settlement') }}</li>
                    </ol>
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
                
                <div class="sorting w-25 mr-2">
                    <label>{{__('label.sort_by')}}</label>
                    <select class="form-control" id="input_status">
                        <option value="all">{{__('label.all_status')}}</option>
                        <option value="1">{{__('label.pending')}}</option>
                        <option value="2">{{__('label.settled')}}</option>
                    </select>
                </div>
                <div class="sorting mr-2" style="width: 19%;">
                    <input type="date" name="input_date" id="input_date"  value="{{ request()->get('input_date') }}" placeholder="dd-mm-yyyy" class="form-control">
                </div>
            </div>
            
            <div class="table-responsive table">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th>{{__('label.#')}}</th>
                            <th>{{__('label.settelment_no')}}</th>
                            <th>{{__('label.type')}}</th>
                            <th>{{__('label.user')}}</th>
                            <th>{{__('label.group_order_id')}}</th>
                            <th>{{__('label.commission_amount')}}</th>
                            <th>{{__('label.adjustment_amount')}}</th>
                            <th>{{__('label.settlement_amount')}}</th>
                            <th>{{__('label.date')}}</th>
                            <th>{{__('label.status')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')

    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        sidebar_down(700);

        $("#input_seller_id").select2();
        const currencyCode = "{{ currency_code() }}";

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.settlement.index') }}",
                    data: function(d) {
                        d.input_status = $('#input_status').val();
                        d.input_date = $('#input_date').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'settelment_no',
                        name: 'settelment_no',
                        render: function(data) {
                            return data ?
                                `<h6 class='primary-color'><strong> ${data} </strong></h6>` : "-";

                        }
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data, type, row) {
                        
                            if (row.type == 1) {
                                return `<span style="font-weight:700;">Seller</span>`;
                            } 
                            else if (row.type == 2) {
                                return `<span style="font-weight:700;">Delivery Boy</span>`;
                            }
                        
                            return '-';
                        }
                    },

                    {
                        data: 'type_id',
                        name: 'type_id',
                        render: function(data, type, row) {
                        
                            if (row.type == 1 && row.seller) {
                            
                                return `
                                    <div style="text-align: center;">
                                        ${row.seller.full_name ?? ''} 
                                        <br>
                                        <span style="font-size:14px;font-weight:600;">
                                            ${row.seller.user_name ?? ''}
                                        </span>
                                    </div>
                                `;
                                
                            } else if (row.type == 2 && row.deliveryboy) {
                            
                                return `
                                    <div style="text-align: center;">
                                        ${row.deliveryboy.full_name ?? ''} 
                                        <br>
                                        <span style="font-size:14px;font-weight:600;">
                                            ${row.deliveryboy.user_name ?? ''}
                                        </span>
                                    </div>
                                `;
                                
                            }
                        
                            return '-';
                        }
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
                        data: 'commission_amount',
                        name: 'commission_amount',
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
                        data: 'adjustment_amount',
                        name: 'adjustment_amount',
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
                        data: 'settlement_amount',
                        name: 'settlement_amount',
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
                        data: 'date',
                        name: 'date'
                    },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                ],
            });
                    
            $('#input_status ,#input_date').change(function() {
                table.draw();
            });
        });
    
    </script>
@endsection
