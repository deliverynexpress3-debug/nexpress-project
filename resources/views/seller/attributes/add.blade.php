@extends('seller.layout.page-app')
@section('page_title', __('label.add_attributes'))
@section('tab_title', __('label.add_attributes'))
@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.add_attributes')}}</h1>
            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('seller.dashboard') }}">{{__('label.dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('seller.attributes.index') }}">{{__('label.attributes')}}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{__('label.add_attributes')}}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('seller.attributes.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.attributes')}}</a>
                </div>
            </div>
            <form enctype="multipart/form-data" id="save_amenity">
                <input type="hidden" name="id" value="">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card custom-border-card">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-9">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="name">{{__('label.name')}}<span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control" placeholder="{{__('label.name_here')}}" autofocus>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="type">{{__('label.type')}}<span class="text-danger">*</span></label>
                                                    <select class="form-control" name="field_type" id="type">
                                                        <option value="">{{__('label.select_type')}}</option>
                                                        <option value="1">{{__('label.text')}}</option>
                                                        <option value="2">{{__('label.numeric')}}</option>
                                                        <option value="3">{{__('label.dropdown')}}</option>
                                                        <option value="4">{{__('label.radio_button')}}</option>
                                                        <option value="5">{{__('label.checkbox')}}</option>
                                                        <option value="6">{{__('label.file_upload')}}</option>
                                                      
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5 ml-2">
                                                <div class="form-group">
                                                    <label>{{__('label.is_required')}}<span class="text-danger">*</span></label>
                                                    <div class="radio-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="is_required" id="required_no" class="custom-control-input" value="0" checked>
                                                            <label class="custom-control-label" for="required_no">{{__('label.no')}}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="is_required" id="required_yes" class="custom-control-input" value="1">
                                                            <label class="custom-control-label" for="required_yes">{{__('label.yes')}}</label>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row" id="add_more_option_btn">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <a class="btn btn-success add-more text-white" onclick="add_more_link()">{{__('label.+_add_more_option')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row option_list">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{__('label.option')}}</label>
                                                    <input type="text" name="option[]" class="form-control" placeholder="{{__('label.option_here')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="after-add-more"></div>
                                        
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ml-3">
                                            <label class="">{{__('label.icon')}}</label>
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' name="icon" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload" title="Select File"></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <img src="{{asset('assets/imgs/upload_img.png')}}" alt="upload_img.png" id="imagePreview">
                                                </div>
                                            </div>
                                            <label class="mt-3 text-gray">{{__('label.image_note')}}</label>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        
                        <div class="card custom-border-card mt-3">
                           
                            <h5 class="card-header">{{__('label.category')}}</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="category">
                                                <div class="category-header">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <button type="button" class="btn btn-default mw-120" onclick="save_amenity()">{{__('label.save')}}</button>
                    <a href="{{route('seller.attributes.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
       
        $('#add_more_option_btn').hide();
        $('.option_list').hide();
      
        $('#type').on('change', function () {

            var Type = $(this).val();
          
            if(Type == 3 || Type == 4 || Type == 5){
                $('#add_more_option_btn').show();
                $('.option_list').show();
            } else {
                $('#add_more_option_btn').hide();
                $('.option_list').hide();
            }
        });
        function add_more_link(){

            var data = `
                    <div class="form-row option_list">
                        <div class="form-group col-md-6">
                            <input type="text" name="option[]" class="form-control" placeholder="{{__("label.option_here")}}">
                        </div>
                        <div class="col-xl-1 col-md-3">
                            <div class="flex-grow-1 d-inline-flex">
                                <div class="change mt-1" id="add_btn">
                                    <a class="btn btn-danger add-more text-white remove_link">-</a>
                                </div>
                            </div>
                        </div>
                    </div>`;

            $('.after-add-more').append(data);
        }
        $("body").on("click", ".remove_link", function(e) {
            $(this).parents('.option_list').remove();
        });

        let categories = <?php echo json_encode($category); ?>;

        $(document).ready(function() {
           
            // Function to generate category and subcategory HTML
            function addnewslot(categories) {
                let data = '<div>';
                // Iterate over categories
                categories.forEach(function(category) {
                    data += '<div class="form-group" style="display: block; margin-bottom: 0;">'; // Ensure block display for vertical alignment
                    data += '<label>';
                    data += '<input type="checkbox" class="click_checkbox" name="category_id[]" value="' + category.id + '" id="category_' + category.id + '"> ' + category.name;
                    data += '</label>';
                    // Check if subcategories exist
                    if (category.sub_category && category.sub_category.length > 0) {
                        data += '<i style="font-size:15px color:" class="fas fa-play fa-1x toggle-button ml-2" data-target="#subcategory_' + category.id + '"></i>';
                        data += '<div class="subcategories pl-3" id="subcategory_' + category.id + '" style="display: none;">';
                        // Recursively add subcategories
                        data += addnewslot(category.sub_category);
                        data += '</div>';
                    }
                    data += '</div>';
                });
                data += '</div>';

                return data;
            }

            // Append the generated HTML to the category header
            $('.category-header').append(addnewslot(categories));

            // Toggle subcategories on click
            $('.toggle-button').click(function() {
                var target = $(this).data('target');
                $(target).slideToggle();
            });

            $("#type").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    if (optionValue == 1 || optionValue == 2 || optionValue == 3) {
                        $("#Input").show();
                        $("#Dropdown").hide();
                    } else if (optionValue == 4 || optionValue == 5 || optionValue == 6) {
                        $("#Dropdown").show();
                        $("#Input").hide();
                    }
                });
            }).change();

            // Initialize Select2
            $('#Custom_Field').select2({
                placeholder: "Select Options",
                tags: true,
                tokenSeparators: [',', ' '], // Allow multiple tags to be entered using comma or space
                createTag: function(params) {
                    return {
                        id: params.term,
                        text: params.term,
                        newOption: true
                    };
                }
            });
        });

        function save_amenity() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#save_amenity")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("seller.attributes.store") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_amenity', '{{ route("seller.attributes.index") }}');
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