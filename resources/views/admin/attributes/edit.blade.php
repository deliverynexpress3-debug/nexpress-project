@extends('admin.layout.page-app')
@section('page_title', __('label.edit_attributes'))
@section('tab_title', __('label.edit_attributes'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.edit_attributes')}}</h1>
            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.attributes.index') }}">{{__('label.attributes')}}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{__('label.edit_attributes')}}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.attributes.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.attributes')}}</a>
                </div>
            </div>
            <form enctype="multipart/form-data" id="update_attributes">
                <input type="hidden" name="id" value="{{ $result['id'] }}">
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
                                                    <input type="text" name="title" value="{{$result->title}}" class="form-control" placeholder="{{__('label.name_here')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="type">{{__('label.type')}}<span class="text-danger">*</span></label>
                                                    <select class="form-control" name="field_type" id="type">
                                                        <option value="">{{__('label.select_type')}}</option>
                                                        <option value="1" {{ $result->field_type == 1 ? 'selected' : ''}}>{{__('label.text')}}</option>
                                                        <option value="2" {{ $result->field_type == 2 ? 'selected' : ''}}>{{__('label.numeric')}}</option>
                                                        <option value="3" {{ $result->field_type == 3 ? 'selected' : ''}}>{{__('label.dropdown')}}</option>
                                                        <option value="4" {{ $result->field_type == 4 ? 'selected' : ''}}>{{__('label.radio_button')}}</option>
                                                        <option value="5" {{ $result->field_type == 5 ? 'selected' : ''}}>{{__('label.checkbox')}}</option>
                                                        <option value="6" {{ $result->field_type == 6 ? 'selected' : ''}}>{{__('label.file_upload')}}</option>
                                                      
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5 ml-2">
                                                <div class="form-group">
                                                    <label>{{__('label.is_required')}}<span class="text-danger">*</span></label>
                                                    <div class="radio-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="is_required" id="required_no" class="custom-control-input" value="0" {{ $result->is_required == 0 ? 'checked' : ''}}>
                                                            <label class="custom-control-label" for="required_no">{{__('label.no')}}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="is_required" id="required_yes" class="custom-control-input" value="1" {{ $result->is_required == 1 ? 'checked' : ''}}>
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
                                        @for ($i = 0; $i < count($attributes_option); $i++)
                                        <div class="form-row option_list">
                                            <div class="col-md-6 option_list">
                                                <div class="form-group">
                                                    <input type="text" name="option[]" value="{{$attributes_option[$i]['option']}}" class="form-control" placeholder="{{__('label.option_here')}}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-1 col-md-3">
                                                <div class="flex-grow-1 d-inline-flex">
                                                    <div class="change mt-1" id="add_btn">
                                                        <a class="btn btn-danger add-more text-white remove_link">-</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endfor
                                        <div class="after-add-more"></div>
                                        
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ml-3">
                                            <label>{{__('label.icon')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type="file" name="icon" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                                <label class="avatar-preview drop-area">
                                                    <img src="{{$result['icon']}}" class="imagePreview">
                                                    <input type="hidden" name="old_icon" value="@if($result){{$result->icon}}@endif">
                                                </label>
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        
                        <div class="card custom-border-card mt-3">
                            {{-- @if(CheckDefaultIndustries())
                            <h5 class="card-header">{{__('label.industries')}}</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('label.industries') }}<span class="text-danger">*</span></label>
                                            <div class="row">
                                                
                                                @foreach ($industires as $ind)
                                                <div class="custom-control custom-radio ml-3">
                                                    <input type="radio" name="industries_id" value="{{ $ind->id }}" id="industires_{{ $ind->id }}" class="custom-control-input"  {{ $result->industries_id == $ind->id ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="industires_{{ $ind->id }}">{{ $ind->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif --}}
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
                    <button type="button" class="btn btn-default mw-120" onclick="update_attributes()">{{__('label.update')}}</button>
                    <a href="{{route('admin.attributes.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                    <input type="hidden" name="_method" value="PATCH">
               
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
         sidebar_down(300);
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
        let category_id = "<?php echo $result->category_id ?>".split(',');  // Convert string to array

        $(document).ready(function() {

            function addnewslot(categories) {
                let data = '<div>';
            
                categories.forEach(function(category) {
                    data += '<div class="form-group" style="display: block; margin-bottom: 0;">';
                    data += '<label>';
                    // Check if the current category is selected
                    let checked = category_id.includes(String(category.id)) ? 'checked' : ''; // If id is in category_id, set checked
                    data += '<input type="checkbox" class="click_checkbox" name="category_id[]" value="' + category.id + '" id="category_' + category.id + '" ' + checked + '> ' + category.name;
                    data += '</label>';

                    // Check if subcategories exist
                    if (category.sub_category && category.sub_category.length > 0) {
                        let subcategoryVisible = ''; 

                        // Check if any subcategory or the main category itself is selected
                        let subcategorySelected = category_id.includes(String(category.id)) ? 'block' : 'none';
                        category.sub_category.forEach(function(subcategory) {
                            if (category_id.includes(String(subcategory.id))) {
                                subcategoryVisible = 'block';  // If subcategory is selected, show subcategory
                            }
                        });

                        let visibility = subcategoryVisible || subcategorySelected;
                        data += '<i style="font-size:15px" class="fas fa-play fa-1x toggle-button ml-2" data-target="#subcategory_' + category.id + '"></i>';
                        data += '<div class="subcategories pl-3" id="subcategory_' + category.id + '" style="display: ' + visibility + ';">';

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
                    if(optionValue == 3 || optionValue == 4 || optionValue == 5){
                        $('#add_more_option_btn').show();
                        $('.option_list').show();
                    } else {
                        $('#add_more_option_btn').hide();
                        $('.option_list').hide();
                    }

                });
            }).change();
        });
        
        function update_attributes() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#update_attributes")[0]);
                $.ajax({
                    type: 'POST',
                    url:'{{ route("admin.attributes.update", [$result->id]) }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'update_attributes', '{{ route("admin.attributes.index") }}');
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