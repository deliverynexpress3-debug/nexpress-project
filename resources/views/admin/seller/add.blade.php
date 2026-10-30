@extends('admin.layout.page-app')
@section('page_title', __('label.add_seller'))
@section('tab_title', __('label.add_seller'))

@section('content')
	@include('admin.layout.sidebar')

	<div class="right-content">
		@include('admin.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.add_seller')}}</h1>

			<div class="border-bottom row">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
						<li class="breadcrumb-item"><a href="{{ route('admin.seller.index') }}">{{__('label.seller')}}</a></li>
						<li class="breadcrumb-item active" aria-current="page">{{__('label.add_seller')}}</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('admin.seller.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.seller')}}</a>
				</div>
			</div>

            <form id="save_seller" enctype="multipart/form-data">
                <input type="hidden" name="id" value="">
                
				<div class="card custom-border-card">
                    <h5 class="card-header">{{__('label.personal_info')}}</h5>
                    <div class="card-body">
                        
						<div class="form-row">
							<div class="col-9">
								<div class="form-row">
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.full_name')}}<span class="text-danger">*</span></label>
											<input name="full_name" type="text" class="form-control" placeholder="{{__('label.full_name_here')}}" autofocus>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>{{__('label.country_code')}}<span class="text-danger">*</span></label>
											<input name="country_code" type="text" class="form-control" placeholder="{{__('label.+91')}}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.mobile_number')}}<span class="text-danger">*</span></label>
											<input name="mobile_number" type="text" class="form-control" placeholder="{{__('label.mobile_number_here')}}">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>{{__('label.country_name')}}<span class="text-danger">*</span></label>
											<input name="country_name" type="text" class="form-control" placeholder="{{__('label.ind')}}">
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.email')}}<span class="text-danger">*</span></label>
											<input name="email" type="email" class="form-control" placeholder="{{__('label.email_here')}}">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>{{__('label.password')}}<span class="text-danger">*</span></label>
											<input name="password" type="password" class="form-control" placeholder="{{__('label.password_here')}}">
										</div>
									</div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.printer_mac_address')}}</label>
                                            <input name="printer_mac_address" type="text" class="form-control" placeholder="{{__('label.address_here')}}">
                                        </div>
                                    </div>
								</div>
								
							</div>
							<div class="col-3">
                                <div class="form-group ml-5">
                                    <label>{{__('label.image')}}<span class="text-danger">*</span></label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type="file" name="image" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                        <label class="avatar-preview drop-area">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" class="imagePreview">
                                        </label>
                                    </div>
                                </div>
								
							</div>
						</div>
					</div>
				</div>
				<div class="card custom-border-card">
                    <h5 class="card-header">{{__('label.store_info')}}</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-9">
                                <div class="form-row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{__('label.store_name')}}<span class="text-danger">*</span></label>
                                            <input name="store_name" type="text" class="form-control" placeholder="{{__('label.store_name_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{__('label.store_url')}}</label>
                                            <input name="store_url" type="text" class="form-control" placeholder="{{__('label.store_url_here')}}">
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.store_address')}}<span class="text-danger">*</span></label>
                                            <textarea name="store_address" rows="1" class="form-control" placeholder="{{__('label.store_address_here')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group ml-5">
                                    <label>{{__('label.store_logo')}}<span class="text-danger">*</span></label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type="file" name="store_logo" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                        <label class="avatar-preview drop-area">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" class="imagePreview">
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bank Details -->
                <div class="card custom-border-card">
                    <h5 class="card-header">{{__('label.bank_details')}}</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-9">
                                <div class="form-row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>{{__('label.bank_name')}}</label>
                                            <input name="bank_name" type="text" class="form-control" placeholder="{{__('label.bank_name_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>{{__('label.account_holder_name')}}</label>
                                            <input name="account_holder_name" type="text" class="form-control" placeholder="{{__('label.account_holder_name_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>{{__('label.account_no')}}</label>
                                            <input name="account_no" type="text" class="form-control" placeholder="{{__('label.account_no_here')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.bank_code')}}</label>
                                            <input name="ifsc_code" type="text" class="form-control" placeholder="{{__('label.bank_code_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>{{__('label.bank_address')}}</label>
                                            <textarea name="bank_address" rows="1" class="form-control" placeholder="{{__('label.bank_address_here')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group ml-5">
                                    <label>{{__('label.bank_proof')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type="file" name="bank_proof" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                        <label class="avatar-preview drop-area">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" class="imagePreview">
                                        </label>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
             
                <div class="border-top pt-3 text-right">
                    <button type="button" class="btn btn-default mw-120" onclick="save_seller()">{{__('label.save')}}</button>
                    <a href="{{route('admin.seller.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </form>
		</div>
	</div>
@endsection

@section('pagescript')

	<script>
		function save_seller(){

			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

				$("#dvloader").show();
				var formData = new FormData($("#save_seller")[0]);
				$.ajax({
					type:'POST',
					url:'{{ route("admin.seller.store") }}',
					data:formData,
					cache:false,
					contentType: false,
					processData: false,
					success:function(resp){
						$("#dvloader").hide();
						get_responce_message(resp, 'save_seller', '{{ route("admin.seller.index") }}');
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