@extends('seller.layout.page-app')
@section('page_title', __('label.add_product'))
@section('tab_title', __('label.add_product'))

@section('content')
	@include('seller.layout.sidebar')

	<div class="right-content">
		@include('seller.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.add_product')}}</h1>

			<div class="border-bottom row">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{__('label.dashboard')}}</a></li>
						<li class="breadcrumb-item"><a href="{{ route('seller.item.index') }}">{{__('label.product')}}</a></li>
						<li class="breadcrumb-item active" aria-current="page">{{__('label.add_product')}}</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('seller.item.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.product')}}</a>
				</div>
			</div>

			<form enctype="multipart/form-data" id="productForm">
				@csrf
			
				<!-- Tab Navigation -->
				<ul class="nav nav-pills custom-tabs inline-tabs" id="pills-tab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="basicInfo-tab" data-toggle="tab" href="#basicInfo" role="tab" aria-controls="basicInfo" aria-selected="true">Basic Information</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="taxes-stock-tab" data-toggle="tab" href="#taxesstockInfo" role="tab" aria-controls="taxesstockInfo" aria-selected="false">{{__('label.product_taxes')}}/{{__('label.product_stock')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="quantity-delivery-tab" data-toggle="tab" href="#quantitydeliveryInfo" role="tab" aria-controls="quantitydeliveryInfo" aria-selected="false">{{__('label.product_quantity')}} /{{__('label.delivery_setting')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="attributes-tab" data-toggle="tab" href="#attributesInfo" role="tab" aria-controls="attributesInfo" aria-selected="false">{{__('label.attributes')}} / {{__('label.other_images')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="other-tab" data-toggle="tab" href="#otherInfo" role="tab" aria-controls="otherInfo" aria-selected="false">{{__('label.product_other_info')}}</a>
					</li>
					
				</ul>
			
				<!-- Tab Content -->
				<div class="tab-content">
					<!-- Tab 1: Basic Information -->
					<div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">
						<div class="card custom-border-card">
							<h5 class="card-header">{{__('label.product_information')}}</h5>
							<div class="card-body">
								
								<div class="form-row">
									<div class="col-xl-8 col-md-12">
										<div class="form-row">
											<div class="col-md-12">
												<div class="form-group">
													<label>{{__('label.product_name')}}<span class="text-danger">*</span></label>
													<input name="name" type="text" class="form-control" placeholder="{{__('label.enter_product_name')}}" autofocus>
												</div>
											</div>
											
										</div>
						
										<div class="form-row">
											<div class="col-md-8">
												<div class="form-group">
													<label> {{ __('label.category') }}<span class="text-danger">*</span></label>
													<select class="form-control" id="category_id">
														<option value="">{{__('label.select_category')}}</option>
													</select>
													<input type="hidden" name="category_id" id="category_id_hidden">
													<input type="hidden" name="sub_category_id" id="sub_category_id">
													
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label> {{ __('label.brand') }}<span class="text-danger">*</span></label>
													<select class="form-control" style="width:100%!important;" name="brand_id" id="brand_id">
														<option value=""> {{__('label.select_brand')}}</option>
														@foreach ($brand as $key => $value)
														<option value="{{ $value->id}}">
															{{ $value->name }}
														</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
						
										<div class="form-row">
											<div class="col-md-8">
												<div class="form-group">
													<label> {{ __('label.product_type') }}<span class="text-danger">*</span></label>
													<div class="radio-group">
														<div class="custom-control custom-radio">
															<input type="radio" name="product_type" id="type_physical" class="custom-control-input" value="1" checked>
															<label class="custom-control-label" for="type_physical">{{__('label.physical')}}</label>
														</div>
														<div class="custom-control custom-radio">
															<input type="radio" name="product_type" id="type_digital" class="custom-control-input" value="2">
															<label class="custom-control-label" for="type_digital">{{__('label.digital')}}</label>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label> {{ __('label.price') }}<span class="text-danger">*</span></label>
													<input type="number" name="price" min="0.01" step="0.01" inputmode="decimal" class="form-control" placeholder="{{__('label.enter_price')}}">
												</div>
											</div>
										</div>
									</div>
						
									<!-- Image Upload Section -->
									<div class="col-xl-2 col-md-6">
										<div class="form-group ml-5">
											<label>{{__('label.portrait_img')}}<span class="text-danger">*</span></label>
											<div class="avatar-upload">
												<div class="avatar-edit">
													<input type='file' name="portrait_img" id="imageUpload" accept=".png, .jpg, .jpeg" />
													<label for="imageUpload" title="{{__('label.upload_file')}}"></label>
												</div>
												<div class="avatar-preview">
													<img src="{{asset('assets/imgs/upload_img.png')}}" id="imagePreview">
												</div>
											</div>
											<label class="mt-3 text-gray">{{__('label.image_note')}}</label>
										</div>
									</div>
						
									<div class="col-xl-2 col-md-6">
										<div class="form-group">
											<label>{{__('label.landscape_img')}}<span class="text-danger">*</span></label>
											<div class="avatar-upload-landscape">
												<div class="avatar-edit-landscape">
													<input type='file' name="landscape_img" id="imageUploadLandscape" accept=".png, .jpg, .jpeg" />
													<label for="imageUploadLandscape" title="{{__('label.upload_file')}}"></label>
												</div>
												<div class="avatar-preview-landscape">
													<img src="{{asset('assets/imgs/upload_img.png')}}" id="imagePreviewLandscape">
												</div>
											</div>
											<label class="mt-3 text-gray">{{__('label.image_note')}}</label>
										</div>
									</div>
								</div>
						
								<!-- Tags Section -->
								<div class="form-row">
									<div class="col-md-8">
										<div class="form-group">
											<label> {{ __('label.tag') }}</label>
											<select class="form-control" style="width:100%!important;" name="tag_id[]" multiple id="tag_id">
											
												@foreach ($tag as $key => $value)
												<option value="{{ $value->id}}">
													{{ $value->name }}
												</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<!-- Tab 2: Taxes && Stock Information -->
					<div class="tab-pane fade" id="taxesstockInfo" role="tabpanel" aria-labelledby="taxes-stock-tab">
						<div class="row mt-3">
							<div class="col-xl-6">
								<div class="card custom-border-card">
									<h5 class="card-header">{{__('label.product_taxes')}}</h5>
									<div class="card-body">
										<div class="form-row">
											<div class="col-md-12">
												<div class="form-row">
													<div class="col-md-4">
														<div class="form-group">
															<label>{{__('label.tax_include_price')}}</label><br>
															<label class="text-muted" style="font-size: 14px;">{{__('label.enable_disable')}}</label>
															<label class="switch ml-3">
																<input type="checkbox" name="tax_include_price" id="taxToggle">
																<span class="slider round"></span>
															</label>
															
														</div>
													</div>
													<div class="col-md-8" id="taxDetails">
														<div class="form-group">
															<label> {{ __('label.tax') }}<span class="text-danger">*</span></label>
															<select class="form-control" style="width:100%!important;" name="tax_id[]" multiple id="tax_id">
															
																@foreach ($taxes as $key => $value)
																<option value="{{ $value->id}}">
																	{{ $value->name }}
																</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>
											</div>
										
										</div>
											
									</div>
									
								</div>
							</div>
							<div class="col-xl-6">
								<div class="card custom-border-card">
									<h5 class="card-header">{{__('label.enable_stock_management')}}</h5>
									<div class="card-body">
										<div class="form-row">
											<div class="col-md-12">
												<div class="form-row">
													<div class="col-md-4">
														<div class="form-group">
															<label>{{__('label.enable_stock')}}</label><br>
															<label class="text-muted" style="font-size: 14px;">{{__('label.enable_disable')}}</label>
															<label class="switch ml-2">
																<input type="checkbox" name="enable_stock" id="stockToggle">
																<span class="slider round"></span>
															</label>
															
														</div>
													</div>
													<div class="col-md-3" id="totalStock">
														<div class="form-group">
															<label> {{ __('label.total_stock') }}<span class="text-danger">*</span></label>
															<input type="number" name="total_stock" class="form-control" placeholder="{{__('label.enter_total_stock')}}">
														</div>
													</div>
													<div class="col-md-5" id="stockStatus">
														<div class="form-group">
															<label> {{ __('label.stock_status') }}<span class="text-danger">*</span></label>
															<select class="form-control" style="width:100%!important;" name="stock_status_id" id="stock_status_id">
																<option value=""> {{__('label.select_stock_status')}}</option>
																@foreach ($stock_status as $key => $value)
																<option value="{{ $value->id}}">
																	{{ $value->name }}
																</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>
										
											</div>		
										</div>
											
									</div>
									
								</div>
							</div>
						</div>
						
					
					</div>
					<!-- Tab 3: Quantity && Delivery Setting -->
					<div class="tab-pane fade" id="quantitydeliveryInfo" role="tabpanel" aria-labelledby="quantity-delivery-tab">
						<div class="card custom-border-card">
							<h5 class="card-header">{{__('label.product_quantity')}} && {{__('label.delivery_setting')}}</h5>
							<div class="card-body">
								<div class="form-row">
									<div class="col-md-12">
										<div class="form-row">
											<div class="col-md-3">
												<div class="form-group">
													<label> {{ __('label.total_allowed_quantity') }}</label>
													<input type="number" name="total_allowed_quantity" class="form-control" placeholder="{{__('label.enter_total_allowed_quantity')}}">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label> {{ __('label.minimum_order_quantity') }}</label>
													<input type="number" name="minimum_order_quantity" class="form-control" placeholder="{{__('label.enter_minimum_order_quantity')}}">
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">
													<label> {{ __('label.minimum_free_order') }}</label>
													<input type="number" name="minimum_free_order_quantity" class="form-control" placeholder="{{__('label.enter_minimum_free_order')}}">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label> {{ __('label.regular_delivery_charges') }}</label>
													<input type="number" name="regular_delivery_charges" class="form-control" placeholder="{{__('label.enter_regular_delivery_charges')}}">
												</div>
											</div>
											
										
										</div>
										<div class="form-row">
												
											<div class="col-md-2">
												<div class="form-group">
													<label>{{__('label.is_cod_allowed')}}</label>
													<label class="switch ml-3">
														<input type="checkbox" name="is_cod">
														<span class="slider round"></span>
													</label>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label>{{__('label.is_returnable')}}</label>
													<label class="switch ml-3">
														<input type="checkbox" name="is_returnable">
														<span class="slider round"></span>
													</label>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label>{{__('label.is_cancelable')}}</label>
													<label class="switch ml-3">
														<input type="checkbox" name="is_cancelable">
														<span class="slider round"></span>
													</label>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label>{{__('label.fast_delivery')}}</label>
													<label class="switch ml-3">
														<input type="checkbox" name="fast_delivery" id="EnableToogle" checked>
														<span class="slider round"></span>
													</label>
												</div>
											</div>
											<div class="col-md-2" id="FastDelivery">
												<div class="form-group">
													<label> {{ __('label.fast_delivery_charges') }}</label>
													<input type="number" name="fast_delivery_charges" class="form-control" placeholder="{{__('label.enter_fast_delivery_charges')}}">
												</div>
											</div>
										
										</div>
									</div>
								
								</div>
								
							</div>
						</div>
					
					</div>
					<!-- Tab 4: Attributes && Other Images -->
					<div class="tab-pane fade" id="attributesInfo" role="tabpanel" aria-labelledby="attributes-tab">
						<div class="row mt-3">
							<div class="col-xl-8">
								<div class="card custom-border-card">
									<h5 class="card-header">{{__('label.attributes')}}</h5>
								    <div class="card-body">
										<div class="after-add-more" id="more"></div>
									</div>
								</div>
								<div class="card custom-border-card">
									<h5 class="card-header">{{__('label.faq')}}</h5>
								    <div class="card-body">
										<div class="form-row">
											<div class="col-5">
												<div class="form-group">
													<label>{{__('label.question')}}</label>
													<input type="text" name="question[]" class="form-control" placeholder="{{__('label.question_here')}}">
												</div>
											</div>
											<div class="col-5">
												<div class="form-group">
													<label>{{__('label.answer')}}</label>
													<input type="text" name="answer[]" class="form-control" placeholder="{{__('label.answer_here')}}">
												</div>
											</div>
											<div class="col-1 mt-3">
												<div class="flex-grow-1 px-5 d-inline-flex">
													<div class="change mr-3 mt-4" id="add_btn">
														<a class="btn btn-success add-more text-white" onclick="add_more_faq()">+</a>
													</div>
												</div>
											</div>
										</div>
				 
										<div class="after-faq-add-more"></div>
										
									</div>
								</div>
							</div>
							
							<div class="col-xl-4">
								<div class="card custom-border-card">
									<h5 class="card-header">
										{{ __('label.other_images') }}
										<label class="mt-3 text-gray">({{__('label.image_note')}})</label>
									</h5>
									<div class="card-body">
										<div class="form-row">
											<!-- Container for dynamic upload fields -->
											<div id="uploadContainer" class="col-12">
												<!-- Template for upload field -->
												<div class="form-group upload-field d-flex align-items-center mb-4">
													<div class="avatar-upload col-6">
														<div class="avatar-edit">
															<input type="file" id="imageUpload1" name="other_image[]" class="image-upload-input" accept=".png, .jpg, .jpeg" />
															<label for="imageUpload1" title="{{ __('label.upload_file') }}"></label>
														</div>
														<div class="avatar-preview">
															<img src="{{ asset('assets/imgs/upload_img.png') }}" class="image-preview" alt="Preview">
														</div>
													</div>
													<div class="avatar-upload col-6">
														<div class="avatar-edit">
															<input type="file" id="imageUpload2" name="other_image[]" class="image-upload-input" accept=".png, .jpg, .jpeg" />
															<label for="imageUpload2" title="{{ __('label.upload_file') }}"></label>
														</div>
														<div class="avatar-preview">
															<img src="{{ asset('assets/imgs/upload_img.png') }}" class="image-preview" alt="Preview">
														</div>
													</div>
												</div>
											</div>
		
											<!-- Button to add new upload fields -->
											<div class="col-12 mt-3">
												<button type="button" id="addUploadField" class="btn btn-default btn-block p-2" style="border-radius: 0;">
													{{ __('label.add_more_images') }}
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<!-- Tab 5: Product Other Information -->
					<div class="tab-pane fade" id="otherInfo" role="tabpanel" aria-labelledby="other-tab">
						<div class="card custom-border-card">
							<h5 class="card-header">{{__('label.product_other_info')}}</h5>
							<div class="card-body">
								<div class="form-row">
									<div class="col-md-12">
										<div class="form-row">
											<div class="col-md-12">
												<div class="form-row">
													<div class="col-md-12">
														<div class="form-group">
															<label> {{ __('label.sort_description') }}</label>
															<textarea name="sort_description" class="form-control" rows="4" style="min-height:110px;" placeholder="{{ __('label.description_here') }}"></textarea>
														</div>
													</div>
													<div class="col-12">
														<div class="form-group">
															<label>{{__('label.long_description')}}</label>
															<textarea class="form-control" name="long_description" id="summernote"></textarea>
														</div>
													</div>
												</div>
												
											</div>											
										</div>
										<div class="form-row">
											<div class="col-md-12">
												<div class="form-row">
													<div class="col-md-3">
														<div class="form-group">
															<label>{{__('label.video_type')}}<span class="text-danger">*</span></label>
															<select name="video_type" id="video_type" class="form-control">
																<option selected="selected" value="1">{{__('label.server_video')}}</option>
																<option value="2">{{__('label.external_url')}}</option>
																
															</select>
														</div>
													</div>
													
													<div class="form-group col-md-5 video_box">
														<div style="display: block;">
															<label>{{__('label.upload_video')}}</label>
															<div id="filelist"></div>
															<div id="container" style="position: relative;">
																<div class="form-group">
																	<input type="file" id="uploadFile" name="uploadFile" style="position: relative; z-index: 1;" class="form-control">
																</div>
																<input type="hidden" name="video" id="mp3_file_name" class="form-control">
							
															</div>
														</div>
													</div>
													<div class="col-2 mt-5 video_box">
														<div class="form-group">
															<a id="upload" class="btn text-white" style="background-color:#4e45b8;">{{__('label.upload_files')}}</a>
														</div>
														
													</div>
													<div class="col-6 url">
														<div class="form-group">
															<label>{{__('label.url')}}</label>
															<input name="url" type="text" class="form-control" placeholder="{{__('label.enter_url')}}">
															
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
					
					</div>
					<!--- Next ,Previous ,Submit ---->
					<div class="pt-2 text-right">
						<button type="button" id="prevBtn" class="btn btn-cancel mw-120 mr-2" style="display: none;">{{__('label.previous')}}</button>
						<button type="button" id="nextBtn" class="btn btn-default mw-120">{{__('label.next')}}</button>
						<button type="submit" id="saveBtn" class="btn btn-default mw-120" style="display: none;">{{__('label.save')}}</button>
					</div>
					
				</div>

			</form>
		</div>
	</div>
@endsection

@section('pagescript')
	
	<script>
			$('input[name="product_type"]').change(function() {
            var value = $('input[name="product_type"]:checked').val();
            $('#taxes-stock-tab ,#quantity-delivery-tab').hide();
            
            if (value == '1') {
                $('#taxes-stock-tab').show();
				$('#quantity-delivery-tab').show();
            }
            else if (value == '2') {
                $('#taxes-stock-tab').hide();
				$('#quantity-delivery-tab').hide();
            }
        });
        $('input[name="product_type"]:checked').change();

		$("#seller_id, #category_id,#tag_id, #brand_id , #stock_status_id").select2();
		$("#tax_id").select2({placeholder: "{{__('label.select_tax')}}"});
		$("#tag_id").select2({placeholder: "{{__('label.select_tags')}}"});

		$("#taxDetails").hide();
		$('#taxToggle').change(function() {	
        	if($(this).is(':checked')) {
        	    $('#taxDetails').show(); // Show div when toggle is ON
        	} else {
        	    $('#taxDetails').hide(); // Hide div when toggle is OFF
        	}
    	});

		$("#totalStock").hide();
		$("#stockStatus").hide();
		$('#stockToggle').change(function() {	
        	if($(this).is(':checked')) {
        	    $('#totalStock').show(); // Show div when toggle is ON
				$('#stockStatus').show();
        	} else {
        	    $('#totalStock').hide(); // Hide div when toggle is OFF
				$('#stockStatus').hide();
        	}
    	});

		$("#FastDelivery").show();
		$('#EnableToogle').change(function() {	
        	if($(this).is(':checked')) {
        	    $('#FastDelivery').show(); // Show div when toggle is ON
        	} else {
        	    $('#FastDelivery').hide(); // Hide div when toggle is OFF
        	}
    	});

		$(".url").hide();
		$("#video_type").change(function() {
            $(this).find("option:selected").each(function() {
                var optionValue = $(this).attr("value");
				
                if (optionValue ==1 ) {
                    $(".video_box").show();
					$(".url").hide();
                  
                } else {
					$(".video_box").hide();
					$(".url").show();
                }
            });
        }).change();

		// Summernote
		$('#summernote').summernote({
            placeholder: "Description Here...",
            height: 200,
            toolbar: [
                // Style Formatting
                ['style', ['bold', 'italic', 'underline', 'clear']],
                // Font Options
                ['font', ['fontname', 'fontsize']],
                ['color', ['forecolor']],
                // Paragraph Formatting
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                // Insert Options
                ['insert', ['link', 'picture', 'video']],
                // Additional Formatting
                ['table', ['table']],
                // View Options
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            fontSizes: ['8', '10', '12', '14', '16', '18', '20', '22', '24', '26', '28', '30', '32', '34', '36', '38', '40', '44', '48', '52', '56', '60', '64', '68', '72', '78', '82', '86', '90', '94', '100'],
            lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '1.8', '2.0', '3.0'],   
        });

		// Initialize Tabes
		const tabs = ['basicInfo-tab', 'taxes-stock-tab', 'quantity-delivery-tab', 'attributes-tab', 'other-tab'];
    	// Function to handle tab navigation and button display
    	function handleTabNavigation(activeTabId) {
    	    const index = tabs.indexOf(activeTabId);

    	    // If first tab, hide the Previous button and show Next
    	    if (index === 0) {
    	        $('#prevBtn').hide();
    	        $('#nextBtn').show();
    	        $('#saveBtn').hide();
    	    } 
    	    // If last tab, hide the Next button and show Save
    	    else if (index === tabs.length - 1) {
    	        $('#prevBtn').show();
    	        $('#nextBtn').hide();
    	        $('#saveBtn').show();
    	    } 
    	    // If any intermediate tab, show both Previous and Next
    	    else {
    	        $('#prevBtn').show();
    	        $('#nextBtn').show();
    	        $('#saveBtn').hide();
    	    }
    	}
    	// Initialize on page load
    	handleTabNavigation('basicInfo-tab');
    	// Function for moving to the next tab
    	$('#nextBtn').on('click', function() {
    	    let currentTab = $('.nav-link.active').parent();
    	    let nextTab = currentTab.next().find('a[data-toggle="tab"]');
    	    if (nextTab.length) {
    	        nextTab.tab('show');
    	    }
    	});
    	// Function for moving to the previous tab
    	$('#prevBtn').on('click', function() {
    	    let currentTab = $('.nav-link.active').parent();
    	    let prevTab = currentTab.prev().find('a[data-toggle="tab"]');
    	    if (prevTab.length) {
    	        prevTab.tab('show');
    	    }
    	});
    	// Handle tab changes via click
    	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    	    handleTabNavigation(e.target.id);
    	});
		$('#productForm').on('submit', function(event) {
        	event.preventDefault();  // Prevent the page from reloading
        	save_product();
    	});
		//Show as Category && Parent categorys
		let categories = <?php echo json_encode($categories); ?>;
		// Function to create indentation for sub-categories
		function getIndent(level) {
		    return '\u00A0'.repeat(level * 4) + '|--\u00A0';
		}
		// Recursively generate options with data-parent for sub-categories
		function generateCategoryOptions(categories, parentElement, level = 0, parentId = null) {
		    categories.forEach(category => {
		        const option = document.createElement('option');
		        option.value = category.id;
		        option.textContent = getIndent(level) + category.name;
		        if (parentId !== null) {
		            option.setAttribute('data-parent', parentId);
		        }
		        parentElement.appendChild(option);
			
		        if (category.sub_category && Array.isArray(category.sub_category)) {
		            generateCategoryOptions(category.sub_category, parentElement, level + 1, category.id);
		        }
		    });
		}
		document.addEventListener('DOMContentLoaded', function () {
    		const selectElement = document.getElementById('category_id');
    		const categoryHidden = document.getElementById('category_id_hidden');
    		const subCategoryHidden = document.getElementById('sub_category_id');

    		// Fill the select with categories
    		generateCategoryOptions(categories, selectElement);

    		// Handle change
    		$('#category_id').on('change', function () {
    		    const selectedOption = this.options[this.selectedIndex];
    		    const selectedValue = selectedOption.value;
    		    const parentId = selectedOption.getAttribute('data-parent');

    		    if (parentId) {
    		        // Sub-category
    		        categoryHidden.value = parentId;
    		        subCategoryHidden.value = selectedValue;
    		        CategoryChange(selectedValue);
    		    } else {
    		        // Main category
    		        categoryHidden.value = selectedValue;
    		        subCategoryHidden.value = 0;
    		        CategoryChange(selectedValue);
    		    }
    		});
		});
        function CategoryChange(category_id) {
			
    		var cat_id = category_id;
			
    		if (cat_id != "") {
        		$.ajax({
            		headers: {
            		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Laravel CSRF token for security
            		},
            		type: "POST",
            		url: "{{ route('seller.item.attributes') }}", // Laravel route for fetching category attributes
            		data: {
            		    cat_id: cat_id,
            		},
            		success: function(resp) {
                		// Clear the form section before adding new content
                		$('.after-add-more').empty();
					
                		// Check for a successful response
                		if (resp.status == 200) {
							
                	    	// Loop through each attribute returned from the server
                	    	resp.result.forEach(function(attribute, i) {
                	        	var data = '<div class="form-row">';
                	        	data += '<input name="array[title_' + i + '][amenity_id]" value="' + attribute.id + '" type="hidden">';
                	        	data += '<div class="row col-lg-12">';

                	        	// Title Field
                	        	data += '<div class="form-group col-lg-3">';
                	        	data += '<label>{{ __("label.title") }}</label>';
                	        	data += '<input name="array[title_' + i + '][title_name]" value="' + attribute.title + '" type="text" class="form-control title" id="title" placeholder="Enter Field Title" readonly>';
                	        	data += '</div>';

                	        	// Field Type Handling
                	        	switch (attribute.field_type) {
									
                	        	    case 1: // Text Field
                	        	        data += getTextField(i);
                	        	        break;
                	        	    case 2: // Numeric Field
                	        	        data += getNumericField(i);
                	        	        break;
                	        	    case 3: // Dropdown Field
                	        	        data += getDropdownField(i, attribute.attributes_option);
                	        	        break;
                	        	    case 4: // Radio Button Field
                	        	        data += getRadioField(i, attribute.attributes_option);
                	        	        break;
                	        	    case 5: // Checkboxes
                	        	        data += getCheckboxField(i, attribute.attributes_option);
                	        	        break;
                	        	    case 6: // File Upload
                	        	        data += getFileUploadField(i);
                	        	        break;
                	        	}

                	        	data += '</div>'; // Close row
                	        	$('.after-add-more').append(data); // Append the dynamically created form field
                	    	});

                	    	// Initialize select2 plugin for checkboxes dropdown
                	    	$('.checkboxes').select2({
                	    	    placeholder: "{{ __('label.select_option') }}",
                	    	    multiple: true
                	    	});
                		} else if (resp.status == 400) {
							var noAttributesMsg = `
                    			<div>
                    			    <h5 class="text-center">
                    			        <li class="breadcrumb-item active" aria-current="page">{{__('label.category_text')}}</li>
                    			    </h5>    
                    			</div>
                			`;
                			$('.after-add-more').append(noAttributesMsg);
                		   
                		}
            		},
            		error: function(xhr, textStatus, errorThrown) {
                		toastr.error(errorThrown, textStatus); // Show error notification using toastr
            		}
        		});
    		} else {
        		// Clear the form if no category is selected
        		$('.after-add-more').empty();
   			}
		}
		
		// Functions to generate specific types of form fields
		function getTextField(i) {
		    return `
		        <div class="form-group col-lg-3">
		            <label>{{ __("label.type") }}</label>
		            <select class="form-control link" name="array[title_${i}][field_type]" id="type_link_${i}">
		                <option value="1">{{ __("label.text") }}</option>
		            </select>
		        </div>
		        <div class="form-group col-lg-6" id="text_type_link_${i}">
		            <label for="name">{{ __("label.value") }}</label>
		            <input type="text" name="array[title_${i}][field_value]" class="form-control" placeholder="{{ __("label.enter_value") }}">
		        </div>
		    `;
		}
		function getNumericField(i) {
    		return `
    		    <div class="form-group col-lg-3">
    		        <label>{{ __("label.type") }}</label>
    		        <select class="form-control link" name="array[title_${i}][field_type]" id="type_link_${i}">
    		            <option value="2">{{ __("label.numeric") }}</option>
    		        </select>
    		    </div>
    		    <div class="form-group col-lg-6" id="input_type_link_${i}">
    		        <label for="name">{{ __("label.value") }}</label>
    		        <input type="number" name="array[title_${i}][field_value]" class="form-control" placeholder="{{ __("label.enter_value") }}">
    		    </div>
    		`;
		}
		function getDropdownField(i, options) {
			
    		var dropdown = `
    		    <div class="form-group col-lg-3">
    		        <label>{{ __("label.type") }}</label>
    		        <select class="form-control link" name="array[title_${i}][field_type]" id="type_link_${i}">
    		            <option value="3">{{ __("label.dropdown") }}</option>
    		        </select>
    		    </div>
    		    <div class="form-group col-lg-6 dropdown_type" id="dropdown_type_link_${i}">
    		        <label for="name">{{ __("label.option") }}</label>
    		        <select class="form-control custom_field checkboxes" name="array[title_${i}][field_value][]" id="Custom_Field_${i}" style="width:100%!important;" multiple>
    		            <option value="">{{ __("label.select_option") }}</option>
    		`;
					
    		options.forEach(function(option) {
    		    dropdown += `<option value="${option.option}">${option.option}</option>`;
    		});

    		dropdown += `</select></div>`;
    		return dropdown;
		}
		function getRadioField(i, options) {
    		var radio = `
    		    <div class="form-group col-lg-3">
    		        <label>{{ __("label.type") }}</label>
    		        <select class="form-control link" name="array[title_${i}][field_type]" id="type_link_${i}">
    		            <option value="4">{{ __("label.radio_button") }}</option>
    		        </select>
    		    </div>
    		    <div class="form-group col-lg-6 dropdown_type" id="dropdown_type_link_${i}">
    		        <label for="name">{{ __("label.option") }}</label>
    		        <select class="form-control custom_field" name="array[title_${i}][field_value]" id="Custom_Field_${i}">
    		            <option value="">{{ __("label.select_option") }}</option>
    		`;
    
    		options.forEach(function(option) {
    		    radio += `<option value="${option.option}">${option.option}</option>`;
    		});

    		radio += `</select></div>`;
    		return radio;
		}
		function getCheckboxField(i, options) {
    		var checkbox = `
    		    <div class="form-group col-lg-3">
    		        <label>{{ __("label.type") }}</label>
    		        <select class="form-control link" name="array[title_${i}][field_type]" id="type_link_${i}">
    		            <option value="5">{{__("label.checkbox")}}</option>
    		        </select>
    		    </div>
    		    <div class="form-group col-lg-6 dropdown_type" id="dropdown_type_link_${i}">
    		        <label for="name">{{ __("label.option") }}</label>
    		        <select class="form-control custom_field checkboxes" name="array[title_${i}][field_value][]" style="width:100%!important;" multiple id="Custom_Field_${i}">
    		`;

    		options.forEach(function(option) {
    		    checkbox += `<option value="${option.option}">${option.option}</option>`;
    		});

    		checkbox += `</select></div>`;
    		return checkbox;
		}
		function getFileUploadField(i) {
    		return `
    		    <div class="form-group col-lg-3">
    		        <label>{{ __("label.type") }}</label>
    		        <select class="form-control link" name="array[title_${i}][field_type]" id="type_link_${i}">
    		            <option value="6">{{ __("label.file_upload") }}</option>
    		        </select>
    		    </div>
    		    <div class="form-group col-lg-5">
    		        <label>{{ __("label.image") }}</label>
    		        <input type="file" name="array[title_${i}][icon]" class="form-control import-file social_img" id="social_img_${i}">
    		    </div>
    		    <div class="form-group mt-2">
    		        <div class="custom-file">
    		            <img src="{{ asset('assets/imgs/upload_img.png') }}" style="height: 50px; width: 50px;" class="change mr-3 mt-4 img-thumbnail" id="link_img_social_img_${i}">
    		        </div>
    		    </div>
    		`;
		}
        // Multipal Img Show 
        $(document).on('change', '.social_img', function() {
            // alert("this.id: " + this.id);
            readURL(this, this.id);
        });
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#link_img_' + id).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

		 // FAQs
		function add_more_faq() {
            var data = `
                <div class="form-row faq-additional-row">
                    <div class="col-5">
                        <div class="form-group">
                            <input type="text" name="question[]" class="form-control" placeholder="{{__('label.question_here')}}">
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group">
                            <input type="text" name="answer[]" class="form-control" placeholder="{{__('label.answer_here')}}">
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="flex-grow-1 px-5 d-inline-flex">
                            <div class="change mr-3 mt-1" id="remove_btn">
                                <a class="btn btn-danger text-white faq-remove-more">-</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('.after-faq-add-more').append(data);
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        }
        // Remove added faqs rows
        $("body").on("click", ".faq-remove-more", function(e) {
            $(this).closest('.faq-additional-row').remove();
        });
		
		// Other Images
		document.addEventListener('DOMContentLoaded', () => {
            const uploadContainer = document.getElementById('uploadContainer');
            const addUploadField = document.getElementById('addUploadField');

            let fileInputCount = 2; // To generate unique IDs for inputs and labels

            // Function to create a new upload row with two image upload fields
            function createUploadField() {
                fileInputCount += 2;

                const row = document.createElement('div');
                row.classList.add('form-group', 'upload-field', 'd-flex', 'align-items-center', 'mb-3');

                row.innerHTML = `
                    <div class="avatar-upload col-6">
                        <div class="avatar-edit">
                            <input type="file" id="imageUpload${fileInputCount - 1}" name="other_image[]" class="image-upload-input" accept=".png, .jpg, .jpeg" />
                            <label for="imageUpload${fileInputCount - 1}" title="{{ __('label.upload_file') }}"></label>
                        </div>
                        <div class="avatar-preview">
                            <img src="{{ asset('assets/imgs/upload_img.png') }}" class="image-preview" alt="Preview">
                        </div>
                        <button type="button" class="btn btn-danger btn-block btn-sm remove-upload mt-4">
                            {{ __('label.remove') }}
                        </button>
                    </div>
                    <div class="avatar-upload col-6">
                        <div class="avatar-edit">
                            <input type="file" id="imageUpload${fileInputCount}" name="other_image[]" class="image-upload-input" accept=".png, .jpg, .jpeg" />
                            <label for="imageUpload${fileInputCount}" title="{{ __('label.upload_file') }}"></label>
                        </div>
                        <div class="avatar-preview">
                            <img src="{{ asset('assets/imgs/upload_img.png') }}" class="image-preview" alt="Preview">
                        </div>
                        <button type="button" class="btn btn-danger btn-block btn-sm remove-upload mt-4">
                            {{ __('label.remove') }}
                        </button>
                    </div>
                `;

                // Add event listeners for preview and remove functionality
                row.querySelectorAll('.image-upload-input').forEach((input) => {
                    input.addEventListener('change', handleImagePreview);
                });

                row.querySelectorAll('.remove-upload').forEach((button) => {
                    button.addEventListener('click', () => handleRemoveImage(button, row));
                });

                uploadContainer.appendChild(row);
            }

            // Function to handle file preview
            function handleImagePreview(event) {
                const input = event.target;
                const preview = input.closest('.avatar-upload').querySelector('.image-preview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Function to handle removing an image
            function handleRemoveImage(button, row) {
                const avatarUpload = button.closest('.avatar-upload');
                avatarUpload.remove();

                // Remove the row if both upload fields are gone
                if (row.querySelectorAll('.avatar-upload').length === 0) {
                    row.remove();
                }
            }

            // Add initial event listeners to existing fields
            document.querySelectorAll('.image-upload-input').forEach((input) => {
                input.addEventListener('change', handleImagePreview);
            });

            document.querySelectorAll('.remove-upload').forEach((button) => {
                const row = button.closest('.form-group');
                button.addEventListener('click', () => handleRemoveImage(button, row));
            });

            // Add event listener for "Add More Images" button
            addUploadField.addEventListener('click', createUploadField);
        });


		//Save Product
		function save_product() {
		
			var Demo_Mode = '<?php echo Demo_Mode(); ?>'; // Assume this checks for demo mode status
			if (Demo_Mode == 1) {
				$("#dvloader").show();
				var formData = new FormData($("#productForm")[0]);
	
				$.ajax({
					type: 'POST',
					url: '{{ route("seller.item.store") }}',
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					success: function(resp) {
						$("#dvloader").hide();
						get_responce_message(resp, 'productForm', '{{ route("seller.item.index") }}');
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