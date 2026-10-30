@extends('seller.layout.page-app')
@section('page_title', __('label.edit_profile'))
@section('tab_title', __('label.edit_profile'))

@section('content')
	@include('seller.layout.sidebar')

	<div class="right-content">
		@include('seller.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.edit_profile')}}</h1>

			<div class="border-bottom row">
				<div class="col-sm-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{__('label.dashboard')}}</a></li>
						
						<li class="breadcrumb-item active" aria-current="page">{{__('label.edit_profile')}}</li>
					</ol>
				</div>
				
			</div>

			
            <form id="update_seller" enctype="multipart/form-data">
				<input type="hidden" name="id" value="{{$result['id']}}">
				
				<div class="card custom-border-card">
                    <h5 class="card-header">{{__('label.personal_info')}}</h5>
                    <div class="card-body">
						
						<div class="form-row">
							<div class="col-9">
								<div class="form-row">
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.full_name')}}<span class="text-danger">*</span></label>
											<input name="full_name" type="text" value="{{$result['full_name']}}" class="form-control" placeholder="{{__('label.full_name_here')}}" autofocus>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.country_code')}}<span class="text-danger">*</span></label>
											<input name="country_code" value="{{$result['country_code']}}" type="text" class="form-control" placeholder="{{__('label.+91')}}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.mobile_number')}}<span class="text-danger">*</span></label>
											<input name="mobile_number" value="{{$result['mobile_number']}}" type="text" class="form-control" placeholder="{{__('label.mobile_number_here')}}">
										</div>
									</div>
									
								</div>
								<div class="form-row">
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.email')}}<span class="text-danger">*</span></label>
											<input name="email" value="{{$result['email']}}" type="email" class="form-control" placeholder="{{__('label.email_here')}}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.country_name')}}<span class="text-danger">*</span></label>
											<input name="country_name" value="{{$result['country_name']}}" type="text" class="form-control" placeholder="{{__('label.ind')}}">
										</div>
									</div>
									
								</div>
								
							</div>
							<div class="col-3">
								<div class="form-group ml-5">
									<label>{{__('label.image')}}<span class="text-danger">*</span></label>
									<div class="avatar-upload">
										<div class="avatar-edit">
											<input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
											<label for="imageUpload" title="{{__('label.upload_file')}}"></label>
										</div>
										<div class="avatar-preview">
											<img src="{{ $result['image'] }}" id="imagePreview">
											
										</div>
									</div>
									<input type="hidden" name="old_image" value="{{$result['image']}}">
									<label class="mt-3 text-gray">{{__('label.image_note')}}</label>
								</div>
							</div>
						</div>
					</div>
					<h5 class="card-header">{{__('label.store_info')}}</h5>
					<div class="card-body">
                        <div class="form-row">
                            <div class="col-9">
                                <div class="form-row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{__('label.store_name')}}<span class="text-danger">*</span></label>
                                            <input name="store_name" value="{{$otherData['store_name']}}" type="text" class="form-control" placeholder="{{__('label.store_name_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{__('label.store_url')}}</label>
                                            <input name="store_url" value="{{$otherData['store_url']}}" type="text" class="form-control" placeholder="{{__('label.store_url_here')}}">
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.store_address')}}<span class="text-danger">*</span></label>
                                            <textarea name="store_address" rows="1" class="form-control" placeholder="{{__('label.store_address_here')}}">{{$otherData['store_address']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group ml-5">
                                    <label>{{__('label.store_logo')}}<span class="text-danger">*</span></label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' name="store_logo" id="imgUpload" accept=".png, .jpg, .jpeg" />
                                            <label for="imgUpload" title="{{__('label.upload_file')}}"></label>
                                        </div>
                                        <div class="avatar-preview">
											<img src="{{$otherData['store_logo']}}" id="imgPreview">
                                
                                        </div>
                                    </div>
									<input type="hidden" name="old_store_logo" value="{{$otherData['store_logo']}}">
                                    <label class="mt-3 text-gray">{{__('label.image_note')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>

					<div class="border-top pt-3 text-right">
						<button type="button" class="btn btn-default mw-120" onclick="update_profile()">{{__('label.update')}}</button>
						<input type="hidden" name="_method" value="PATCH">
					</div>
				</div>
				
                <!-- Bank Details -->
                {{-- <div class="card custom-border-card">
                    <h5 class="card-header">{{__('label.bank_details')}}</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-9">
                                <div class="form-row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>{{__('label.bank_name')}}</label>
                                            <input name="bank_name" value="{{$otherData['bank_name']}}" type="text" class="form-control" placeholder="{{__('label.bank_name_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>{{__('label.account_holder_name')}}</label>
                                            <input name="account_holder_name" value="{{$otherData['account_holder_name']}}" type="text" class="form-control" placeholder="{{__('label.account_holder_name_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>{{__('label.account_no')}}</label>
                                            <input name="account_no" value="{{$otherData['account_no']}}" type="text" class="form-control" placeholder="{{__('label.account_no_here')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.bank_code')}}</label>
                                            <input name="ifsc_code" value="{{$otherData['ifsc_code']}}" type="text" class="form-control" placeholder="{{__('label.bank_code_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>{{__('label.bank_address')}}</label>
                                            <textarea name="bank_address" rows="1" class="form-control" placeholder="{{__('label.bank_address_here')}}">{{$otherData['bank_address']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group ml-5">
                                    <label>{{__('label.bank_proof')}}</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' name="bank_proof" id="imageUploadModel" accept=".png, .jpg, .jpeg" />
                                            <label for="imageUploadModel" title="{{__('label.upload_file')}}"></label>
                                        </div>
                                        <div class="avatar-preview">
											<img src="{{$otherData['bank_proof']}}" id="imagePreviewModel">
                                           
                                        </div>
                                    </div>
									<input type="hidden" name="old_bank_proof" value="{{$otherData['bank_proof']}}">
                                    <label class="mt-3 text-gray">{{__('label.image_note')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
             
               
            </form>
			 <!-- Change Password  -->
			 <div class="card custom-border-card">
                <form id="change_password" enctype="multipart/form-data">
					<form id="change_password" enctype="multipart/form-data">
						<input type="hidden" name="id" value="{{ $result->id }}">
						<h5 class="card-header">{{__('label.change_password')}}</h5>
						<div class="card-body">
							<div class="form-row">
								<div class="col-md-4">
									<div class="form-group">
										<label>{{__('label.current_password')}}<span class="text-danger">*</span></label>
										<input type="password" name="current_password" class="form-control" placeholder="{{__('label.current_password_here')}}">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-4">
									<div class="form-group">
										<label>{{__('label.new_password')}}<span class="text-danger">*</span></label>
										<input type="password" name="new_password" class="form-control" placeholder="{{__('label.new_password_here')}}">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>{{__('label.confirm_password')}}<span class="text-danger">*</span></label>
										<input type="password" name="confirm_password" class="form-control" placeholder="{{__('label.confirm_password_here')}}">
									</div>
								</div>
							</div>
						</div>
						<div class="border-top pt-3 text-right">
							<button type="button" class="btn btn-default mw-120" onclick="update_password()">{{__('label.update')}}</button>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</form>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('pagescript')

	<script>
		function update_profile(){

			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var formData = new FormData($("#update_seller")[0]);

                $.ajax({
                    type: 'POST',
					url:'{{ route("seller.profile.update", [$result->id]) }}',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'update_seller', '{{ route("seller.profile.index") }}');
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
		function update_password() {

			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var formData = new FormData($("#change_password")[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("seller.profile.changepassword") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'change_password', '{{ route("seller.profile.index") }}');
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