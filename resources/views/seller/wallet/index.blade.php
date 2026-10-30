@extends('seller.layout.page-app')
@section('page_title', __('label.wallet'))
@section('tab_title', __('label.wallet'))

@section('content')
	@include('seller.layout.sidebar')

	<div class="right-content">
		@include('seller.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.wallet')}}</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('seller.dashboard') }}">{{__('label.dashboard')}}</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">{{__('label.wallet')}}</li>
					</ol>
				</div>
			</div>

            <div class="card custom-border-card">
                <h5 class="card-header">{{__('label.wallet')}}</h5>
                <div class="card-body">
                    <div class="input-group">
                        <div class="col-1">
                            <label class="pt-3" style="font-size:16px; font-weight:500; color:#1b1b1b">{{__('label.amount')}}</label>
                        </div>
                        <div class="col-3">
                            <input type="text" readonly value="@if($data){{$data->wallet_amount}}@endif" class="form-control" autofocus>
                        </div>
                    </div>
                </div>
            </div>

            <form id="withdrawal_request" enctype="multipart/form-data" autocomplete="off">
                <div class="card custom-border-card">
                    <h5 class="card-header">{{__('label.withdrawal_request')}}</h5>
                    <div class="card-body">
                        <div class="input-group">
                            <div class="col-1">
                                <label class="pt-3" style="font-size:16px; font-weight:500; color:#1b1b1b">{{__('label.amount')}}</label>
                            </div>
                            <div class="col-3">
                                <input type="text" name="amount" class="form-control" placeholder="{{__('label.enter_withdrawal_amount')}}">
                                <label class="mt-3 text-gray">{{__('label.minimum_withdrawal')}}</label>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-default mw-120" onclick="send_withdrawal_request()">{{__('label.send')}}</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card custom-border-card">
                <h5 class="card-header">{{__('label.withdrawal_list')}}</h5>
                <div class="card-body">
                    <div class="table-responsive table">
                        <table class="table table-striped text-center table-bordered" id="datatable">
                            <thead>
                                <tr style="background: #F9FAFF;">
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.amount')}}</th>
                                    <th>{{__('label.type')}}</th>
                                    <th>{{__('label.detail')}}</th>
                                    <th>{{__('label.date')}}</th>
                                    <th>{{__('label.status')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
        // Sidebar Scroll Down
        let sidebarHeight = $('.sidebar')[0].scrollHeight;
        sidebar_down(sidebarHeight);
        
        function send_withdrawal_request(){
            
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var formData = new FormData($("#withdrawal_request")[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{route("seller.wallet.store")}}',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'withdrawal_request', '{{ route("seller.wallet.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg,'failed');         
                    }
                });
            } else {
                showError();
            }
		}

        $(document).ready(function() {
            $(function() {
                var table = $('#datatable').DataTable({
                    ...dataTableDefaults,
                    ajax: "{{ route('seller.wallet.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'price',
                            name: 'price',
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return data;
                                } else {
                                    return "-";
                                }
                            }
                        },
                        {
                            data: 'payment_type',
                            name: 'payment_type',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return data;
                                } else {
                                    return "-";
                                }
                            }
                        },
                        {
                            data: 'payment_detail',
                            name: 'payment_detail',
                            orderable: false,
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return data;
                                } else {
                                    return "-";
                                }
                            }
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });
            });
        });
	</script>
@endsection