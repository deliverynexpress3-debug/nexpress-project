@extends('admin.layout.page-app')
@section('page_title', __('label.edit_delivery_boy'))
@section('tab_title', __('label.edit_delivery_boy'))

@section('content')
	@include('admin.layout.sidebar')

	<div class="right-content">
		@include('admin.layout.header')
		<!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.add_delivery_boy')}}</h1>

			<div class="border-bottom row">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
						<li class="breadcrumb-item"><a href="{{ route('admin.deliveryboy.index') }}">{{__('label.delivery_boys')}}</a></li>
						<li class="breadcrumb-item active" aria-current="page">{{__('label.add_delivery_boy')}}</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('admin.deliveryboy.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.delivery_boys')}}</a>
				</div>
			</div>

			<form id="edit_delivery_boy" enctype="multipart/form-data">
				<input type="hidden" name="id" value="{{$result->id}}">
				<div class="card custom-border-card">
                    <h5 class="card-header">{{__('label.personal_info')}}</h5>
                    <div class="card-body">
						<div class="form-row">
							<div class="col-md-9">
								<div class="form-row">
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.full_name')}}<span class="text-danger">*</span></label>
											<input name="full_name" value="{{$result->full_name}}" type="text" class="form-control" placeholder="{{__('label.full_name_here')}}">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>{{__('label.country_code')}}<span class="text-danger">*</span></label>
											<input name="country_code" value="{{$result->country_code}}" type="text" class="form-control" placeholder="{{__('label.+91')}}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.mobile_number')}}<span class="text-danger">*</span></label>
											<input name="mobile_number" value="{{$result->mobile_number}}" type="text" class="form-control" placeholder="{{__('label.mobile_number_here')}}">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label>{{__('label.country_name')}}<span class="text-danger">*</span></label>
											<input name="country_name" value="{{$result->country_name}}" type="text" class="form-control" placeholder="{{__('label.ind')}}">
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-md-6">
										<div class="form-group">
											<label>{{__('label.email')}}<span class="text-danger">*</span></label>
											<input name="email" value="{{$result->email}}" type="email" class="form-control" placeholder="{{__('label.email_here')}}">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>{{__('label.password')}}<span class="text-danger">*</span></label>
											<input name="password" type="password" class="form-control" placeholder="{{__('label.password_here')}}">
										</div>
									</div>
								
								</div>
								{{-- <div class="form-row">
									<div class="col-md-12">
										<div class="form-group">
											<?php $z = explode(",", $result->zone_id); ?>
											<label>{{__('label.serviceable_zones')}}</label>
											<select class="form-control" style="width:100%!important;" name="zone_id[]" multiple id="zone_id">
												@foreach ($zone as $key => $value)
												<option value="{{ $value->id }}"  {{(in_array($value->id, $z)) ? 'selected' : ''}}>
													{{ $value->zone_name }}
												</option>
												@endforeach
											</select>
										</div>
									</div>
								</div> --}}
							</div>
							<div class="col-md-3">
								<div class="form-group ml-3">
									<label>{{__('label.image')}}</label>
									<div class="avatar-upload my-2 image-upload-wrapper">
										<input type="file" name="image" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
										<label class="avatar-preview drop-area">
											<img src="{{$result['image']}}" class="imagePreview">
											<input type="hidden" name="old_image" value="{{$result->image}}">
										</label>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<div class="card custom-border-card">
                    <h5 class="card-header">{{__('label.vehicle_info')}}</h5>
                    <div class="card-body">
						<div class="form-row">
							<div class="col-md-9">
								<div class="form-row">
									<div class="col-md-6">
										<div class="form-group">
											<label>{{__('label.vehcile_name')}}<span class="text-danger">*</span></label>
											<input name="vehicle_name" value="{{$result->vehicle_name}}" type="text" class="form-control" placeholder="{{__('label.enter_name')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>{{__('label.vehcile_number')}}<span class="text-danger">*</span></label>
											<input name="vehicle_number" value="{{$result->vehicle_number}}" type="text" class="form-control" placeholder="{{__('label.enter_number')}}">
										</div>
									</div>
								
								</div>
							
							</div>
							
						</div>
					</div>
				</div>

				<div class="border-top pt-3 text-right">
					<button type="button" class="btn btn-default mw-120" onclick="update_delivery_boy()">{{__('label.update')}}</button>
					<a href="{{route('admin.deliveryboy.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
					<input type="hidden" name="_method" value="PATCH">
				</div>
			</form>
			
		</div>
	</div>
@endsection

@section('pagescript')
	<!-- Select2 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
	<script>
		sidebar_down(700);
		$("#zone_id").select2({placeholder: "{{__('label.select_zone')}}"});

		function update_delivery_boy(){
			
			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

				$("#dvloader").show();
				var formData = new FormData($("#edit_delivery_boy")[0]);
				$.ajax({
					type:'POST',
					url:'{{ route("admin.deliveryboy.update", [$result->id]) }}',
					data:formData,
					cache:false,
					contentType: false,
					processData: false,
					success:function(resp){
						$("#dvloader").hide();
						get_responce_message(resp, 'edit_delivery_boy', '{{ route("admin.deliveryboy.index") }}');
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