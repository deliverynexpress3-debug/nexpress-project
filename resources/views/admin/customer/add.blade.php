@extends('admin.layout.page-app')
@section('page_title', __('label.add_user'))
@section('tab_title', __('label.add_user'))

@section('content')
	@include('admin.layout.sidebar')

	<div class="right-content">
		@include('admin.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.add_user')}}</h1>

			<div class="border-bottom row">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
						<li class="breadcrumb-item"><a href="{{ route('admin.customer.index') }}">{{__('label.users')}}</a></li>
						<li class="breadcrumb-item active" aria-current="page">{{__('label.add_user')}}</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('admin.customer.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.users')}}</a>
				</div>
			</div>

			<div class="card custom-border-card mt-3">
				<form id="save_customer" enctype="multipart/form-data">
					<input type="hidden" name="id" value="">
						
						<div class="form-row">
							<div class="col-md-9">
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
									<div class="col-md-6">
										<div class="form-group">
											<label>{{__('label.email')}}<span class="text-danger">*</span></label>
											<input name="email" type="email" class="form-control" placeholder="{{__('label.email_here')}}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.password')}}<span class="text-danger">*</span></label>
											<input name="password" type="password" class="form-control" placeholder="{{__('label.password_here')}}">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group ml-3">
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
						<div class="border-top pt-3 text-right">
							<button type="button" class="btn btn-default mw-120" onclick="save_customer()">{{__('label.save')}}</button>
							<a href="{{route('admin.customer.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
		$("#industries_id").select2();
		function save_customer(){

			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

				$("#dvloader").show();
				var formData = new FormData($("#save_customer")[0]);
				$.ajax({
					type:'POST',
					url:'{{ route("admin.customer.store") }}',
					data:formData,
					cache:false,
					contentType: false,
					processData: false,
					success:function(resp){
						$("#dvloader").hide();
						get_responce_message(resp, 'save_customer', '{{ route("admin.customer.index") }}');
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