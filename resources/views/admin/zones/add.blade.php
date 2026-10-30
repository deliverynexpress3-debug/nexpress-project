@extends('admin.layout.page-app')
@section('page_title', __('label.add_zones'))
@section('tab_title', __('label.add_zones'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.add_zones') }}</h1>

            <div class="border-bottom row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.zones.index') }}">{{ __('label.zones') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('label.add_zones') }}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.zones.index') }}" class="btn btn-default mw-120"
                        style="margin-top:-14px">{{ __('label.zones') }}</a>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <form id="save_zones" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-row">
                                <!-- Zone Name and Add More Button -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('label.zone_name') }}<span class="text-danger">*</span></label>
                                        <input type="text" name="zone_name" class="form-control required"
                                            placeholder="{{ __('label.enter_name') }}" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-2 add-more-pading">
                                    <div class="form-group">
                                        <a class="btn btn-success add-more text-white" onclick="add_more_link()">
                                            {{ __('label.+_add_more_option') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-3 option_list">
                                    <div class="form-group">
                                        <label>{{ __('label.select_zipcode') }}<span class="text-danger">*</span></label>
                                        <select class="form-control zipcode-dropdown" id="default-zipcode " style="width:100%!important;" name="zipcode_id[]">
                                            <option value="">{{ __('label.select_zipcode') }}</option>
                                            @foreach ($zipcode as $key => $value)
                                                <option value="{{ $value->id }}">{{$value->area_name}} → {{ $value->zipcode }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="after-add-more"></div>

                        </div>
                    </div>
                    <div class="border-top pt-3 text-right">
						<button type="button" class="btn btn-default mw-120" onclick="save_zones()">{{__('label.save')}}</button>
						<a href="{{route('admin.zones.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
                  
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
<script>
    sidebar_down(700);
    
    function save_zones() {
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if (Demo_Mode == 1) {
            $("#dvloader").show();
            var formData = new FormData($("#save_zones")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.zones.store') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_zones', '{{ route('admin.zones.index') }}');
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

    // Function to handle adding more dropdowns
    function add_more_link() {
        var data = `
            <div class="form-row option_list">
                <div class="col-md-3 offset-md-6 option_list">
                    <div class="form-group">
                        <select class="form-control zipcode-dropdown" style="width:100%!important;" name="zipcode_id[]" id="zipcode_id">
                            <option value="">{{ __('label.select_zipcode') }}</option>
                            @foreach ($zipcode as $key => $value)
                                <option value="{{ $value->id }}">{{$value->area_name}} → {{ $value->zipcode }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-1 col-md-3">
                    <div class="flex-grow-1 d-inline-flex">
                        <div class="change mt-1" id="add_btn">
                            <a class="btn btn-danger add-more text-white remove_link">-</a>
                        </div>
                    </div>
                </div>
            </div>`;
    
        // Append the new dropdown
        $('.after-add-more').append(data);
        
        // Bind the change event to update all dropdowns when one is changed
        $('.zipcode-dropdown').off('change').on('change', handleDropdownChange);
    
        // Call to disable options initially
        disableSelectedOptions();
    }
    
    // Function to disable selected options in all dropdowns except the one where the value is selected
    function disableSelectedOptions() {
        var selectedValues = [];
    
        // Collect all selected values from all dropdowns
        $('select[name="zipcode_id[]"]').each(function() {
            var selected = $(this).val();
            if (selected) {
                selectedValues.push(selected);
            }
        });
    
        // Disable selected values in all dropdowns, but exclude the current dropdown's own value
        $('select[name="zipcode_id[]"]').each(function() {
            var dropdown = $(this);
            var currentValue = dropdown.val();
            dropdown.find('option').prop('disabled', false); // Enable all options first
            selectedValues.forEach(function(value) {
                if (value !== currentValue) {
                    dropdown.find('option[value="' + value + '"]').prop('disabled', true); // Disable in other dropdowns
                }
            });
        });
    }
    
    // Function to handle changes in any dropdown
    function handleDropdownChange() {
        disableSelectedOptions(); // Re-run the disabling function when a dropdown value changes
    }
    
    // Remove dynamically added dropdown
    $(document).on('click', '.remove_link', function(e) {
        $(this).closest('.option_list').remove();
        disableSelectedOptions(); // Re-run to update the disabled options
    });
    
    // Bind the change event to the default dropdown on page load
    $(document).ready(function() {
        // Bind the default dropdown
        $('#default-zipcode').on('change', handleDropdownChange);
    
        // Bind dynamically added dropdowns
        $(document).on('change', '.zipcode-dropdown', handleDropdownChange);
    
        // Initial call to disable options
        disableSelectedOptions();
    });
    </script>
   
@endsection
