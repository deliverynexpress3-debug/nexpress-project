@extends('admin.layout.page-app')
@section('page_title', __('label.section'))
@section('tab_title', __('label.section'))

@section('content')
    @include('admin.layout.sidebar')
    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.section') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-11">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('label.section') }}</li>
                    </ol>
                </div>
                <div class="col-sm-1 d-flex justify-content-start mb-3" title="Sortable">
                    <button type="button" data-toggle="modal" data-target="#sortableModal" onclick="sortableBTN()" class="btn btn-default" style="border-radius: 10px;">
                        <i class="fa-solid fa-sort fa-1x"></i>
                    </button>
                </div>
               
            </div>
                
            <ul class="tabs nav nav-pills custom-tabs inline-tabs " id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="app-tab" onclick="Top_Content('1', '0')" data-is_home_screen="1"
                        data-id="0" data-type="0" data-toggle="pill" href="#app" role="tab" aria-controls="app" aria-selected="true">{{__('label.home')}}</a>
                </li>
                @for ($i = 0; $i < count($type); $i++) 
                <li class="nav-item">
                    <a class="nav-link" id="{{$type[$i]['name']}}-tab" onclick="Top_Content('2' , '{{$type[$i]['id']}}')" data-is_home_screen="2" 
                    data-id="{{$type[$i]['id']}}" data-type="{{$type[$i]['type']}}" data-toggle="pill" href="#{{$type[$i]['name']}}" role="tab" aria-controls="{{$type[$i]['name']}}" aria-selected="true">{{ $type[$i]['name']}}</a>
                </li>
                @endfor
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.add_section')}}</h5>
                        <div class="card-body">
                            <form id="save_content_section" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" placeholder="{{__('label.enter_title')}}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.short_title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="short_title" class="form-control" placeholder="{{__('label.enter_short_title')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 content_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.content_type')}}<span class="text-danger">*</span></label>
                                            <select name="content_type" class="form-control" id="content_type">
                                                <option value="">{{__('label.select_type')}}</option>
                                                <option value="1">{{__('label.category')}}</option>
                                                <option value="2">{{__('label.product')}}</option>
                                               
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-3 product_content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.product_content_type')}}<span class="text-danger">*</span></label>
                                            <select name="product_content_type" class="form-control" id="product_content_type">
                                                <option value="">{{__('label.select_type')}}</option>
                                                <option value="1">{{__('label.basic')}}</option>
                                                <option value="2">{{__('label.top_seller')}}</option>
                                                <option value="3">{{__('label.top_selling')}}</option>
                                                <option value="4">{{__('label.top_rating')}}</option>
                                                <option value="5">{{__('label.price_range')}}</option>
                                                <option value="6">{{__('label.intrest_based')}}</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-3 section_type">
                                        <div class="form-group">
                                            <label>{{__('label.section_type')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="section_type_dynamic" class="custom-control-input" value="0" checked>
                                                    <label class="custom-control-label" for="section_type_dynamic">{{__('label.dynamic')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="section_type_manually" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="section_type_manually">{{__('label.manually')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-3 category_drop">
                                        <div class="form-group">
                                            <label> {{ __('label.category') }}</label>
                                            <select class="form-control" style="width:100%!important;" id="category_id">
                                                <option value="">{{__('label.all_category')}}</option>
                                            </select>
                                            <input type="hidden" name="category_id" id="category_id_hidden">
										    <input type="hidden" name="sub_category_id" id="sub_category_id">
                                        </div>
                                    </div>
                                    <div class="col-md-3 brand_drop">
                                        <div class="form-group">
                                            <label> {{ __('label.brand') }}</label>
                                            <select class="form-control" style="width:100%!important;" name="brand_id" id="brand_id">
                                                <option value=""> {{__('label.all_brand')}}</option>
                                                @foreach ($brand as $key => $value)
                                                <option value="{{ $value->id}}">
                                                    {{ $value->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 seller_drop">
                                        <div class="form-group">
                                            <label> {{ __('label.seller') }}</label>
                                            <select class="form-control" style="width:100%!important;" name="seller_id" id="seller_id">
                                                <option value=""> {{__('label.all_seller')}}</option>
                                                @foreach ($seller as $key => $value)
                                                <option value="{{ $value->id}}">
                                                    {{ $value->user_name }} ({{ $value->full_name }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 selling_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.selling_type')}}<span class="text-danger">*</span></label>
                                            <select name="selling_type" class="form-control" id="selling_type">
                                                <option value="1">{{__('label.weekly')}}</option>
                                                <option value="2">{{__('label.monthly')}}</option>
                                                <option value="3">{{__('label.yearly')}}</option>
                                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 low_price_drop">
                                        <div class="form-group">
                                            <label>{{__('label.low_price_range')}}<span class="text-danger">*</span></label>
                                            <input type="number" value="" name="include_low_price" class="form-control" placeholder="Enter Number Of Content">
                                        </div>
                                    </div>
                                    <div class="col-md-3 high_price_drop">
                                        <div class="form-group">
                                            <label>{{__('label.high_price_range')}}<span class="text-danger">*</span></label>
                                            <input type="number" value="" name="include_high_price" class="form-control" placeholder="Enter Number Of Content">
                                        </div>
                                    </div>
                                    <div class="col-md-3 screen_layout_drop">
                                        <div class="form-group">
                                            <label>{{__('label.screen_layout')}}<span class="text-danger">*</span></label>
                                            <select name="screen_layout" class="form-control" id="screen_layout">
                                                <option value="">{{__('label.select_screen_layout')}}</option>
                                                <option value="category_list">{{__('label.category_list')}}</option>
                                                <option value="category_grid">{{__('label.category_grid')}}</option>
                                                <option value="potrait_item">{{__('label.potrait_item')}}</option>
                                               
                                              
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 order_by_upload_drop">
                                        <div class="form-group">
                                            <label>{{__('label.order_by_upload')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="order_by_upload_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="order_by_upload_yes">{{__('label.asc')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="order_by_upload_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="order_by_upload_no">{{__('label.desc')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 order_by_like_drop">
                                        <div class="form-group">
                                            <label>{{__('label.order_by_like')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_like" id="order_by_like_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="order_by_like_yes">{{__('label.asc')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_like" id="order_by_like_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="order_by_like_no">{{__('label.desc')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 main_category_drop">
                                        <div class="form-group">
                                            <label>{{__('label.category')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="category[]" id="main_category_id" multiple style="width:100%!important;">
                                                <option value="">{{__('label.select_category')}}</option>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-md-12 content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.product')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="content[]" id="content_ids" multiple style="width:100%!important;">
                                                <option value="">{{__('label.select_product')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 view_all_drop">
                                        <div class="form-group">
                                            <label>{{__('label.view_all')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="view_all_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="view_all_yes">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="view_all_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="view_all_no">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 load_more_drop">
                                        <div class="form-group">
                                            <label>{{__('label.load_more')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="load_more" id="load_more_yes" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="load_more_yes">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="load_more" id="load_more_no" class="custom-control-input" value="0" checked>
                                                    <label class="custom-control-label" for="load_more_no">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 no_of_content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.no_of_content')}}<span class="text-danger">*</span></label>
                                            <input type="number" min="1" value="1" name="no_of_content" class="form-control" placeholder="Enter Number Of Content">
                                        </div>
                                    </div>
                                    <div class="col-md-3 no_of_row_drop">
                                        <div class="form-group">
                                            <label>{{__('label.no_of_row')}}<span class="text-danger">*</span></label>
                                            <input type="number" min="1" value="1" name="no_of_row" class="form-control" placeholder="Enter Number Of Row">
                                        </div>
                                    </div>
                                    <div class="col-md-3 background_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.background_type')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="background_type" id="color" class="custom-control-input" value="color" checked>
                                                    <label class="custom-control-label" for="color">{{__('label.color')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="background_type" id="image" class="custom-control-input" value="image">
                                                    <label class="custom-control-label" for="image">{{__('label.image')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 bg_color">
                                        <div class="form-group">
                                            <label>{{__('label.background_color')}}<span class="text-danger">*</span></label>
                                            <div class="input-group colorpicker-component">
                                                <input type="text" id="hexcolor-1" class="form-control hexcolor" value="#000000" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                                <input type="color" id="colorpicker-1" name="bg_color" value="#000000" class="colorpicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 bg_image">
                                        <div class="form-group">
                                            <label>{{__('label.background_image')}}<span class="text-danger">*</span></label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type="file" name="image" class="bg_image" accept=".png, .jpg, .jpeg" hidden="">
                                                <label class="avatar-preview drop-area">
                                                    <img src="{{ asset('assets/imgs/upload_img.png') }}" class="imagePreview">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_section()">{{__('label.save')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form> 
                        </div>        
                    </div>
                    <div class="after-add-more"></div>
                </div>
            </div>

            <!-- EditModal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_section')}}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_content_section" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id" value="">
                                <input type="hidden" name="is_home_screen" id="edit_is_home_screen" value="">
                                <input type="hidden" name="old_bg_image" id="edit_old_bg_image">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="edit_title" class="form-control" placeholder="{{__('label.enter_title')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.short_title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="short_title" id="edit_short_title" class="form-control" placeholder="{{__('label.enter_short_title')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_content_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.content_type')}}<span class="text-danger">*</span></label>
                                            <select name="content_type" class="form-control" id="edit_content_type">
                                                <option value="">{{__('label.select_type')}}</option>
                                                <option value="1">{{__('label.category')}}</option>
                                                <option value="2">{{__('label.product')}}</option>
                                               
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-6 edit_product_content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.product_content')}}</label>
                                            <select name="product_content_type" class="form-control" id="edit_product_content_type">
                                                <option value="">{{__('label.select_type')}}</option>
                                                <option value="1">{{__('label.basic')}}</option>
                                                <option value="2">{{__('label.top_seller')}}</option>
                                                <option value="3">{{__('label.top_selling')}}</option>
                                                <option value="4">{{__('label.top_rating')}}</option>
                                                <option value="5">{{__('label.price_range')}}</option>
                                                <option value="6">{{__('label.intrest_based')}}</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-md-6 edit_section_type">
                                        <div class="form-group">
                                            <label>{{__('label.section_type')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="edit_section_type_dynamic" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="edit_section_type_dynamic">{{__('label.dynamic')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="edit_section_type_manually" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="edit_section_type_manually">{{__('label.manually')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="col-md-6 edit_category_drop">
                                        <div class="form-group">
                                            <label> {{ __('label.category') }}</label>
                                            <select class="form-control" style="width:100%!important;" name="category_id" id="edit_category_id">
                                                <option value=""> {{__('label.all_category')}}</option>
                                                
                                            </select>
                                            <input type="hidden" name="category_id" id="edit_category_id_hidden">
										    <input type="hidden" name="sub_category_id" id="edit_sub_category_id">
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_brand_drop">
                                        <div class="form-group">
                                            <label> {{ __('label.brand') }}</label>
                                            <select class="form-control" style="width:100%!important;" name="brand_id" id="edit_brand_id">
                                                <option value="0"> {{__('label.all_brand')}}</option>
                                                @foreach ($brand as $key => $value)
                                                <option value="{{ $value->id}}">
                                                    {{ $value->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_seller_drop">
                                        <div class="form-group">
                                            <label> {{ __('label.seller') }}</label>
                                            <select class="form-control" style="width:100%!important;" name="seller_id" id="edit_seller_id">
                                                <option value="0"> {{__('label.all_seller')}}</option>
                                                @foreach ($seller as $key => $value)
                                                <option value="{{ $value->id}}">
                                                    {{ $value->user_name }} ({{ $value->full_name }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_selling_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.selling_type')}}<span class="text-danger">*</span></label>
                                            <select name="selling_type" class="form-control" id="edit_selling_type">
                                                <option value="1">{{__('label.weekly')}}</option>
                                                <option value="2">{{__('label.monthly')}}</option>
                                                <option value="3">{{__('label.yearly')}}</option>
                                               
                                            </select>
                                        </div>
                                    </div>     
                                    <div class="col-md-6 edit_low_price_drop">
                                        <div class="form-group">
                                            <label>{{__('label.low_price_range')}}<span class="text-danger">*</span></label>
                                            <input type="number" value="" name="include_low_price" id="edit_include_low_price" class="form-control" placeholder="Enter Number Of Content">
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_high_price_drop">
                                        <div class="form-group">
                                            <label>{{__('label.high_price_range')}}<span class="text-danger">*</span></label>
                                            <input type="number" value="" name="include_high_price" id="edit_include_high_price" class="form-control" placeholder="Enter Number Of Content">
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_screen_layout_drop">
                                        <div class="form-group">
                                            <label>{{__('label.screen_layout')}}<span class="text-danger">*</span></label>
                                            <select name="screen_layout" class="form-control" id="edit_screen_layout">
                                                <option value="">{{__('label.select_screen_layout')}}</option>
                                                <option value="category_list">{{__('label.category_list')}}</option>
                                                <option value="category_grid">{{__('label.category_grid')}}</option>
                                                <option value="potrait_item">{{__('label.potrait_item')}}</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-12 edit_main_category_drop">
                                        <div class="form-group">
                                            <label>{{__('label.category')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="category[]" id="edit_main_category_id" multiple style="width:100%!important;">
                                                <option value="">{{__('label.select_category')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 edit_content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.content')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="content[]" id="edit_content_ids" multiple style="width:100%!important;">
                                                <option value="">{{__('label.select_contents')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 edit_no_of_content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.no_of_content')}}<span class="text-danger">*</span></label>
                                            <input type="number" min="1" name="no_of_content" id="edit_no_of_content" class="form-control" placeholder="Enter Number Of Content">
                                        </div>
                                    </div>
                                    <div class="col-md-3 edit_no_of_row_drop">
                                        <div class="form-group">
                                            <label>{{__('label.no_of_row')}}<span class="text-danger">*</span></label>
                                            <input type="number" min="1" name="no_of_row" id="edit_no_of_row" class="form-control" placeholder="Enter Number Of Row">
                                        </div>
                                    </div>
                                    <div class="col-md-4 edit_order_by_upload_drop">
                                        <div class="form-group">
                                            <label>{{__('label.order_by_upload')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="edit_order_by_upload_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_order_by_upload_yes">{{__('label.asc')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="edit_order_by_upload_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="edit_order_by_upload_no">{{__('label.desc')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 edit_order_by_like_drop">
                                        <div class="form-group">
                                            <label>{{__('label.order_by_like')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_like" id="edit_order_by_like_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_order_by_like_yes">{{__('label.asc')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_like" id="edit_order_by_like_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="edit_order_by_like_no">{{__('label.desc')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 edit_view_all_drop">
                                        <div class="form-group">
                                            <label>{{__('label.view_all')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="edit_view_all_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_view_all_yes">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="edit_view_all_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="edit_view_all_no">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 edit_load_more_drop">
                                        <div class="form-group">
                                            <label>{{__('label.load_more')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="load_more" id="edit_load_more_yes" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="edit_load_more_yes">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="load_more" id="edit_load_more_no" class="custom-control-input" value="0" checked>
                                                    <label class="custom-control-label" for="edit_load_more_no">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 edit_background_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.background_type')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="background_type" id="edit_color" class="custom-control-input" value="color">
                                                    <label class="custom-control-label" for="edit_color">{{__('label.color')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="background_type" id="edit_image" class="custom-control-input" value="image">
                                                    <label class="custom-control-label" for="edit_image">{{__('label.image')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_bg_color">
                                        <div class="form-group">
                                            <label>{{__('label.background_color')}}<span class="text-danger">*</span></label>
                                            <div class="input-group colorpicker-component">
                                                <input type="text" id="hexcolor-2" class="form-control hexcolor" value="#000000" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                                <input type="color" id="colorpicker-2" name="bg_color" value="#000000" class="colorpicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_bg_image">
                                        <div class="form-group">
                                            <label>{{__('label.background_image')}}<span class="text-danger">*</span></label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type="file" name="bg_image" class="imageUploadModel" accept=".png, .jpg, .jpeg" hidden="">
                                                <label class="avatar-preview drop-area">
                                                    <img src="{{ asset('assets/imgs/no_img.png') }}" class="imagePreviewModel">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_section()">{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- sortableModal -->
            <div class="modal fade" id="sortableModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="sortableModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="sortableModalLabel">{{__('label.section_sortable_list')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true" class="text-dark">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="imageListId">
                                
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form enctype="multipart/form-data" id="save_section_sortable">
                                @csrf
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                <div class="w-100 text-center">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_section_sortable()">{{__('label.save')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
<!-- Sortable -->
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    sidebar_down(300);

    $("#category_id").select2();
    $("#brand_id").select2();
    $("#seller_id").select2();
    $("#edit_category_id").select2({ dropdownParent: $('#exampleModal') });
    $("#edit_brand_id").select2({ dropdownParent: $('#exampleModal') });
    $("#edit_seller_id").select2({ dropdownParent: $('#exampleModal') });
    $("#content_ids").select2({ placeholder: "{{__('label.select_product')}}" });
    $("#main_category_id").select2({ placeholder: "{{__('label.select_category')}}" });
    $("#edit_content_ids").select2({ dropdownParent: $('#exampleModal'), placeholder: "{{__('label.select_product')}}" });
    $("#edit_main_category_id").select2({ dropdownParent: $('#exampleModal'), placeholder: "{{__('label.select_category')}}" });

    $(".product_content_drop").hide();
    $(".main_category_drop").hide();
    $(".content_drop").hide();
    $(".section_type").hide();
    $(".category_drop").hide();
    $(".brand_drop").hide();
    $(".seller_drop").hide();
    $(".selling_type_drop").hide();
    $(".low_price_drop").hide();
    $(".high_price_drop").hide();
    $(".screen_layout_drop").hide();
    $(".no_of_content_drop").hide();
    $(".no_of_row_drop").hide();
    $(".order_by_upload_drop").hide();
    $(".order_by_like_drop").hide();
    $(".view_all_drop").hide();
    $(".load_more_drop").hide();
    $(".background_type_drop").hide();
    $(".bg_color").hide();
    $(".bg_image").hide();

    // Color Picker
    $(document).ready(function() {
        // Event handler for color picker input change
        $('.colorpicker').on('input', function() {
            var target = $(this).attr('id').split('-')[1];
            $('#hexcolor-' + target).val(this.value.toUpperCase());
        });

        // Event handler for hex color input change
        $('.hexcolor').on('input', function() {
            var target = $(this).attr('id').split('-')[1];
            const hexPattern = /^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/;
            if (hexPattern.test(this.value)) {
                $('#colorpicker-' + target).val(this.value);
            }
        });
    });

    //Show as Category && Parent categorys
    let categories = <?php echo json_encode($categories); ?>;
    function getIndent(level) {
        return '\u00A0'.repeat(level * 4) + '|--\u00A0';
    }
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
    function initCategoryDropdown(selectId, mainHiddenId, subHiddenId, selectedMain = null, selectedSub = null) {
        const selectElement = document.getElementById(selectId);
        const categoryHidden = document.getElementById(mainHiddenId);
        const subCategoryHidden = document.getElementById(subHiddenId);

        if (!selectElement) return;

        // Clear existing options if reinitializing
        selectElement.innerHTML = '<option value="">Select Category</option>';

        generateCategoryOptions(categories, selectElement);

        // Preselect logic
        if (selectedSub && selectedSub !== '0') {
            selectElement.value = selectedSub;
            categoryHidden.value = selectedMain;
            subCategoryHidden.value = selectedSub;
        } else if (selectedMain) {
            selectElement.value = selectedMain;
            categoryHidden.value = selectedMain;
            subCategoryHidden.value = 0;
        }

        // Event listener for change
        $(selectElement).on('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const selectedValue = selectedOption.value;
            const parentId = selectedOption.getAttribute('data-parent');

            if (parentId) {
                categoryHidden.value = parentId;
                subCategoryHidden.value = selectedValue;
            } else {
                categoryHidden.value = selectedValue;
                subCategoryHidden.value = 0;
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        // For Add Form (no preselection)
        initCategoryDropdown('category_id', 'category_id_hidden', 'sub_category_id');
    });

    // Handle active tab tracking
    var tab = $("ul.tabs li a.active");
    var is_home_screen = tab.data("is_home_screen");
    var top_type_id = 0;

    // Update is_home_screen and top_type_id on tab click
    $('.nav-item a').on('click', function () {
        is_home_screen = $(this).data("is_home_screen");
        top_type_id = $(this).data("id");
    });

    $("#content_type").change(function() {
        var content_type = $(this).children("option:selected").val();
        setContentVisibility(content_type);

        if(content_type == 1){  
            $("input[name='background_type'][value='color']").prop("checked", true);
            toggleBackgroundType();
        }else{
            $("input[name='background_type'][value='color']").prop("checked", true);
        }
        
    });

    function setContentVisibility(content_type ,prefix ="" ,selectedIds ="" ,isEdit = false){
        const hide = (selector) => $(`.${prefix}${selector}`).hide();
        const show = (selector) => $(`.${prefix}${selector}`).show();
        const $layoutSelect = $(`#${prefix}screen_layout`);

        if (!isEdit) {
            $layoutSelect.val("");
            $layoutSelect.find("option").hide(); // hide all options
        }
        $layoutSelect.find("option[value='potrait_item']").hide();
       
        if(content_type ==1){
            Get_Category_Content(prefix ,selectedIds);

            show("main_category_drop");
            hide("product_content_drop");
            show("screen_layout_drop");
            hide("content_drop");
            hide("section_type");
            hide("category_drop");
            hide("brand_drop");
            hide("seller_drop");
            hide("selling_type_drop");
            hide("low_price_drop");
            hide("high_price_drop");
            hide("no_of_content_drop");
            show("no_of_row_drop");
            hide("order_by_upload_drop");
            hide("order_by_like_drop");
            show("view_all_drop");
            show("load_more_drop");
            show("background_type_drop");
            hide("bg_image");

            // Allowed layouts only for content type 1
            $layoutSelect
                .find("option[value=''], option[value='category_list'], option[value='category_grid']")
                .show();

            if (!isEdit) {
                $layoutSelect.val("");
            }

        
        }else if(content_type ==2){
            hide("main_category_drop");
            show("product_content_drop");
            hide("screen_layout_drop");
            hide("load_more_drop");
            hide("background_type_drop");
            hide("view_all_drop");
            hide("no_of_content_drop");
            hide("no_of_row_drop");
            hide("bg_color");
            hide("bg_image");

            const $productType = $(`#${prefix}product_content_type`);
            $productType.val(""); // clear selection
            $productType.find("option").prop("selected", false);

            // Allowed layouts for content type 2
            $layoutSelect
                .find("option[value=''], option[value='potrait_item'], option[value='portrait_item']")
                .show();

            if (!isEdit) {
                $layoutSelect.val("");
            }

        }else{
            hide("main_category_drop");
            hide("product_content_drop");
            hide("screen_layout_drop");
            hide("content_drop");
            hide("section_type");
            hide("category_drop");
            hide("brand_drop");
            hide("seller_drop");
            hide("selling_type_drop");
            hide("low_price_drop");
            hide("high_price_drop");
            hide("no_of_content_drop");
            hide("no_of_row_drop");
            hide("order_by_upload_drop");
            hide("order_by_like_drop");
            hide("view_all_drop");
            hide("load_more_drop");
            hide("background_type_drop");
            hide("bg_color");
            hide("bg_image");
        }
        
    }

    function toggleBackgroundType(prefix = "") {
        
        var background_type = $(`.${prefix}background_type_drop input[name='background_type']:checked`).val();
       
        if (background_type == "color") {
            $(`.${prefix}bg_color`).show();
            $(`.${prefix}bg_image`).hide();
        } else if (background_type =="image") {
            $(`.${prefix}bg_color`).hide();
            $(`.${prefix}bg_image`).show();

            // SHOW preview image only if exists
            let img = $(`.${prefix}imagePreviewModel`).attr("src");

            if (img && img.trim() !== "") {
                $(`.${prefix}imagePreviewModel`).show();
            } else {
                $(`.${prefix}imagePreviewModel`).hide();
            }

        }else{
            $(`.${prefix}bg_color`).show();
            $(`.${prefix}bg_image`).hide();
        }
    }
    
    function handleSectionTypeChange(prefix = "", isEdit = false) {

        // Reset product type selection
        $(`#${prefix}product_content_type`).children().removeAttr("selected");

        // Hide all optional fields
        $(`.${prefix}content_drop, 
           .${prefix}category_drop, 
           .${prefix}brand_drop, 
           .${prefix}seller_drop, 
           .${prefix}selling_type_drop, 
           .${prefix}low_price_drop, 
           .${prefix}high_price_drop, 
           .${prefix}screen_layout_drop, 
           .${prefix}no_of_content_drop, 
           .${prefix}order_by_upload_drop, 
           .${prefix}order_by_like_drop, 
           .${prefix}view_all_drop, 
           .${prefix}load_more_drop, 
           .${prefix}background_type_drop, 
           .${prefix}bg_color, 
           .${prefix}no_of_row_drop`
        ).hide();
            
        // Clear content selection
        $(`#${prefix}content_ids`).empty();
            
        // Fetch selected type
        let section_type = $(`.${prefix}section_type input[name='section_type']:checked`).val();
            
        if (section_type == 1) {
            // Add or Edit mode → load content
            if (isEdit) {
                Get_Edit_Content();
            } else {
                Get_Content();
            }
        }
    }

    $("input[name='section_type']").change(function () {
        handleSectionTypeChange(""); // prefix = ""
    });
    $(".edit_section_type input[name='section_type']").change(function () {
        handleSectionTypeChange("edit_", true); // prefix = "edit_" + edit mode
    });

    // Handle Background Type change
    $(document).on("change", "input[name='background_type']", function () {
        if (!$(this).closest('.edit_background_type_drop').length) {
            toggleBackgroundType();
        }
    });
    $(document).on("change", ".edit_background_type_drop input[name='background_type']", function () {
        toggleBackgroundType("edit_");
    });

    // Handle product content type change
    $("#product_content_type").change(function () {
        var product_content_type = $(this).children("option:selected").val();
        var section_type = $("input[name='section_type']:checked").val();

        // Update visibility based on content type and section type
        setVisibilityBasedOnType(product_content_type, section_type);
    });
    
    function resetBackgroundType(prefix = "") {
        // Always reset to COLOR
        const radioSelector = `.${prefix}background_type_drop input[name='background_type'][value='color']`;

        // Reset and trigger UI update
        $(radioSelector).prop("checked", true).trigger("change");
    }

    // Function to handle UI visibility logic
    function setVisibilityBasedOnType(type, section_type, prefix = "" ,isEdit =false) {
        
        const hide = (selector) => $(`.${prefix}${selector}`).hide();
        const show = (selector) => $(`.${prefix}${selector}`).show();

        const $layoutSelect = $(`#${prefix}screen_layout`);
        
        if (!isEdit) {
            $layoutSelect.val(""); // clear selected only for add
        }
        
        $layoutSelect.find("option").show();

        $layoutSelect.find("option[value='category_grid']").hide();
        $layoutSelect.find("option[value='category_list']").hide();
    
        const showLayoutOptions = (options = []) => {
            options.forEach(val => {
                $layoutSelect.find(`option[value='${val}']`).show();
            }); 
        };
        
        if (section_type == 1) {
            if (type == 1) {
                Get_Content();
                hide("main_category_drop");
                show("content_drop");
                show("section_type");
                show("product_content_drop")
                hide("category_drop");
                hide("brand_drop");
                hide("seller_drop");
                hide("selling_type_drop");
                hide("low_price_drop");
                hide("high_price_drop");
                show("screen_layout_drop");
                show("no_of_content_drop");
                show("no_of_row_drop");
                hide("order_by_upload_drop");
                hide("order_by_like_drop");
                show("view_all_drop");
                show("load_more_drop");
                show("background_type_drop");
                show("bg_color");
            }else if (type == 2 || type == 4 || type == 6) {
                hide("main_category_drop");
                hide("content_drop");
                hide("section_type");
                show("product_content_drop");
                hide("brand_drop");
                hide("seller_drop");
                hide("selling_type_drop");
                hide("low_price_drop");
                hide("high_price_drop");
                show("screen_layout_drop");
                show("no_of_content_drop");
                show("no_of_row_drop");
                hide("order_by_upload_drop");
                hide("order_by_like_drop");
                show("view_all_drop");
                show("load_more_drop");
                show("background_type_drop");
                show("bg_color");
            }else if (type == 3) {
                hide("main_category_drop");
                hide("content_drop");
                hide("section_type");
                show("product_content_drop");
                hide("category_drop");
                hide("brand_drop");
                hide("seller_drop");
                show("selling_type_drop");
                hide("low_price_drop");
                hide("high_price_drop");
                show("screen_layout_drop");
                show("no_of_content_drop");
                show("no_of_row_drop");
                hide("order_by_upload_drop");
                hide("order_by_like_drop");
                show("view_all_drop");
                show("load_more_drop");
                show("background_type_drop");
                show("bg_color");
            }else if (type == 5) {
                hide("main_category_drop");
                hide("content_drop");
                hide("section_type");
                show("product_content_drop");
                hide("category_drop");
                hide("brand_drop");
                hide("seller_drop");
                hide("selling_type_drop");
                show("low_price_drop");
                show("high_price_drop");
                show("screen_layout_drop");
                show("no_of_content_drop");
                show("no_of_row_drop");
                hide("order_by_upload_drop");
                hide("order_by_like_drop");
                show("view_all_drop");
                show("load_more_drop");
                show("background_type_drop");
                show("bg_color");
            }else{
                hide("main_category_drop");
                hide("content_drop");
                hide("section_type");
                hide("category_drop");
                hide("brand_drop");
                hide("seller_drop");
                hide("selling_type_drop");
                hide("low_price_drop");
                hide("high_price_drop");
                hide("screen_layout_drop");
                hide("no_of_content_drop");
                hide("no_of_row_drop");
                hide("order_by_upload_drop");
                hide("order_by_like_drop");
                hide("view_all_drop");
                hide("load_more_drop");
                hide("background_type_drop");
                hide("bg_color");
                hide("bg_image");
            }
        } else {
            if (type == 1) {
                hide("main_category_drop");
                hide("content_drop");
                show("section_type");
                show("product_content_drop");
                show("category_drop");
                show("brand_drop");
                show("seller_drop");
                hide("selling_type_drop");
                hide("low_price_drop");
                hide("high_price_drop");
                show("screen_layout_drop");
                show("no_of_content_drop");
                show("no_of_row_drop");
                show("order_by_upload_drop");
                show("order_by_like_drop");
                show("view_all_drop");
                show("load_more_drop");
                show("background_type_drop");
                show("bg_color");
            } else if (type == 2 || type == 4 || type == 6) {
                hide("main_category_drop");
                hide("content_drop");
                hide("section_type");
                show("product_content_drop");
                hide("category_drop");
                hide("brand_drop");
                hide("seller_drop");
                hide("selling_type_drop");
                hide("low_price_drop");
                hide("high_price_drop");
                show("screen_layout_drop");
                show("no_of_content_drop");
                show("no_of_row_drop");
                hide("order_by_upload_drop");
                hide("order_by_like_drop");
                show("view_all_drop");
                show("load_more_drop");
                show("background_type_drop");
                show("bg_color");
            } else if (type == 3) {
                hide("main_category_drop");
                hide("content_drop");
                hide("section_type");
                show("product_content_drop");
                hide("category_drop");
                hide("brand_drop");
                hide("seller_drop");
                show("selling_type_drop");
                hide("low_price_drop");
                hide("high_price_drop");
                show("screen_layout_drop");
                show("no_of_content_drop");
                show("no_of_row_drop");
                hide("order_by_upload_drop");
                hide("order_by_like_drop");
                show("view_all_drop");
                show("load_more_drop");
                show("background_type_drop");
                show("bg_color");
            } else if (type == 5) {
                hide("main_category_drop");
                hide("content_drop");
                hide("section_type");
                show("product_content_drop");
                hide("category_drop");
                hide("brand_drop");
                hide("seller_drop");
                hide("selling_type_drop");
                show("low_price_drop");
                show("high_price_drop");
                show("screen_layout_drop");
                show("no_of_content_drop");
                show("no_of_row_drop");
                hide("order_by_upload_drop");
                hide("order_by_like_drop");
                show("view_all_drop");
                show("load_more_drop");
                show("background_type_drop");
                show("bg_color");
            }else {

                hide("main_category_drop");
                hide("content_drop");
                hide("section_type")
                hide("category_drop");
                hide("brand_drop");
                hide("seller_drop");
                hide("selling_type_drop");
                hide("low_price_drop");
                hide("high_price_drop");
                hide("screen_layout_drop");
                hide("no_of_content_drop");
                hide("no_of_row_drop");
                hide("order_by_upload_drop");
                hide("order_by_like_drop");
                hide("view_all_drop");
                hide("load_more_drop");
                hide("background_type_drop");
                hide("bg_color");
                hide("bg_image");
            }
        }
    }

    function Get_Content() {
    
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("admin.section.content") }}',
            success: function(resp) {
                $("#content_ids").empty();
                for (var i = 0; i < resp.result.length; i++) {
                    $('#content_ids').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);          
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(errorThrown, textStatus);
            }
        });
    }

    function Get_Category_Content(prefix = "" ,selectedIds) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("admin.section.main.category") }}',
            success: function(resp) {
                const $select = $(`#${prefix}main_category_id`);
                $select.empty();

                // Convert "1,6" string to array: ["1", "6"]
                const selectedIdArray = String(selectedIds).split(',').map(id => id.trim());

                if (resp.result.length > 0) {
                
                    resp.result.forEach((item) => {
                        const prefixIcon = '|--'.repeat(item.level);
                        const isSelected = selectedIdArray.includes(String(item.id)) ? 'selected' : '';
                        $select.append(`<option value="${item.id}" ${isSelected}>${prefixIcon} ${item.name}</option>`);
                    });

                    // Also trigger selection programmatically
                    $select.val(selectedIdArray).trigger('change');

                } else {
                    $select.append(`<option value="">No Category Found</option>`);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(errorThrown, textStatus);
            }
        });
    }

    function renderSectionContent(resp) {
        $('.after-add-more').html('');
        for (var i = 0; i < resp.result.length; i++) {

            let content_type = "-";
            switch(resp.result[i].content_type) {
                case 1: content_type = "{{__('label.category')}}"; break;
                case 2: content_type = "{{__('label.product')}}"; break;
               
            }

            let screen_layout = "-";
            switch(resp.result[i].screen_layout) {
                case "category_list": screen_layout = "{{__('label.category_list')}}"; break;
                case "category_grid": screen_layout = "{{__('label.category_grid')}}"; break;
                case "potrait_item": screen_layout = "{{__('label.potrait_item')}}"; break;
            }

            let data = '<div class="card custom-border-card mt-3">'+
                '<div class="card-header d-flex justify-content-between">'+
                    '<h5>{{__("label.edit_section")}}</h5>';

            if(resp.result[i].status == 1){
                data += '<button class="btn show-btn" id="'+resp.result[i].id+'" onclick="change_status('+resp.result[i].id+')">{{__("label.show")}}</button>';
            } else {
                data += '<button class="btn hide-btn" id="'+resp.result[i].id+'" onclick="change_status('+resp.result[i].id+')">{{__("label.hide")}}</button>';
            }

            data += '</div>'+
                '<div class="card-body">'+
                    '<div class="form-row">'+
                        '<div class="col-md-3">'+
                            '<div class="form-group">'+
                                '<label>{{__("label.title")}}</label>'+
                                '<input type="text" value="'+resp.result[i].title+'" class="form-control" readonly>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<div class="form-group">'+
                                '<label>{{__("label.short_title")}}</label>'+
                                '<input type="text" value="'+resp.result[i].short_title+'" class="form-control" readonly>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<div class="form-group">'+
                                '<label>{{__("label.content_type")}}</label>'+
                                '<input type="text" value="'+content_type+'" class="form-control" readonly>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-md-3">'+
                            '<div class="form-group">'+
                                '<label>{{__("label.screen_layout")}}</label>'+
                                '<input type="text" value="'+screen_layout+'" class="form-control" readonly>'+
                            '</div>'+
                        '</div>';

            if (resp.result[i].section_type == 1) {
                data += '<div class="col-md-12">'+
                            '<div class="form-group">'+
                                '<label>{{__("label.product_list")}}</label>'+
                                '<div class="pt-2 pl-2 pr-2" style="background-color: #e9ecef;border-radius: 10px;">';

                for (var j = 0; j < resp.result[i].content.length; j++) {
                    data += '<p class="btn btn-outline-dark btn-sm mr-2">'+ resp.result[i].content[j] +'</p>';
                }

                data += '</div></div></div>';
            }else if(resp.result[i].content_type ==1){
                data += '<div class="col-md-12">'+
                            '<div class="form-group">'+
                                '<label>{{__("label.category_list")}}</label>'+
                                '<div class="pt-2 pl-2 pr-2" style="background-color: #e9ecef;border-radius: 10px;">';

                for (var j = 0; j < resp.result[i].category.length; j++) {
                    data += '<p class="btn btn-outline-dark btn-sm mr-2">'+ resp.result[i].category[j] +'</p>';
                }

                data += '</div></div></div>';
            }

            data += '</div>'+
                '<div class="border-top pt-3 text-right">'+
                    '<button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-default mw-120" onclick="edit_section('+resp.result[i].id+')">{{__("label.update")}}</button>'+
                    '<button type="button" class="btn btn-cancel mw-120 ml-2" onclick="delete_section('+resp.result[i].id+')">{{__("label.delete")}}</button>'+
                '</div>'+
            '</div>'+
            '</div>';

            $('.after-add-more').append(data);
        }
    }

    if (is_home_screen == 1) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("admin.section.content.data") }}',
            data: {
                is_home_screen: is_home_screen,
                top_type_id: 0,
            },
            success: function(resp) {
                renderSectionContent(resp);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(errorThrown, textStatus);
            }
        });
    }
    
    function Top_Content(is_home_screen, top_type_id) {
        if (document.getElementById("save_content_section")) {
            document.getElementById("save_content_section").reset();
        }

        $(".content_drop,  .section_type, .product_content_drop, .category_drop, .main_category_drop, .brand_drop, .seller_drop, .selling_type_drop, .low_price_drop, .high_price_drop, .screen_layout_drop, .no_of_content_drop, .no_of_row_drop, .order_by_upload_drop, .order_by_like_drop, .view_all_drop , .load_more_drop , .background_type_drop, .bg_color , .bg_image").hide();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("admin.section.content.data") }}',
            data: {
                is_home_screen: is_home_screen,
                top_type_id: top_type_id,
            },
            success: function(resp) {
                renderSectionContent(resp);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(errorThrown, textStatus);
            }
        });
    }

    function save_section(){
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if(Demo_Mode == 1){

            var formData = new FormData($("#save_content_section")[0]);
            formData.append('is_home_screen', is_home_screen);
            formData.append('top_type_id', top_type_id);
               
            $("#dvloader").show();
            $.ajax({
                type:'POST',
                url:'{{ route("admin.section.store") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(resp){
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_content_section', '{{ route("admin.section.index") }}');
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
    // Update Section

    function Get_Edit_Content() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("admin.section.content") }}',
            success: function(resp) {
                $("#edit_content_ids").empty();
                    for (var i = 0; i < resp.result.length; i++) {
                        $('#edit_content_ids').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);          
                    }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(errorThrown, textStatus);
            }
        });
    }

    function edit_section(id) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("admin.section.content.edit") }}',
            data: { id: id },
            success: function (resp) {
                if (!resp.result) return;

                const r = resp.result;
               
                const NO_IMAGE = "{{ asset('assets/imgs/no_img.png') }}";

                // Common field population
                $("#edit_id").val(r.id);
                $("#edit_is_home_screen").val(r.is_home_screen);
                $("#edit_title").val(r.title);
                $("#edit_short_title").val(r.short_title);
                $("#edit_screen_layout").val(r.screen_layout).attr("selected", "selected");
                $('#edit_seller_id').val(r.seller_id).trigger('change');
                $('#edit_brand_id').val(r.brand_id).trigger('change');
                $("#edit_content_type").val(r.content_type).attr("selected", "selected");
                $("#edit_product_content_type").val(r.product_content_type).attr("selected", "selected");
                $("#edit_selling_type").val(r.selling_type).attr("selected", "selected");
                $("#edit_no_of_content").val(r.no_of_content);
                $("#edit_no_of_row").val(r.no_of_row);
                $("#edit_include_low_price").val(r.include_low_price);
                $("#edit_include_high_price").val(r.include_high_price);

                $("#edit_view_all_yes").prop("checked", r.view_all == 1);
                $("#edit_view_all_no").prop("checked", r.view_all == 0);

                $("#edit_load_more_yes").prop("checked", r.load_more == 1);
                $("#edit_load_more_no").prop("checked", r.load_more == 0);

                
                if(r.background_type == 'image'){
                    $(".modal-body .imagePreviewModel").attr("src", r.bg_image);
                    $(".modal-body #edit_old_bg_image").val(r.bg_image);
                   
                }else{
                    $(".modal-body .imagePreviewModel").attr("src", NO_IMAGE);
                    $(".modal-body #hexcolor-2").val(r.bg_color);
                    $(".modal-body #colorpicker-2").val(r.bg_color);
                }
               
              
                // Section type radios
                if (r.section_type == 1) {
                    $("#edit_section_type_dynamic").prop('checked', false);
                    $("#edit_section_type_manually").prop('checked', true);
                } else {
                    $("#edit_section_type_manually").prop('checked', false);
                    $("#edit_section_type_dynamic").prop('checked', true);
                }

                if (r.section_type == 1) {
                    // Manually selected content
                    $("#edit_content_ids").empty();
                    for (let i = 0; i < r.content_data.length; i++) {
                        $('#edit_content_ids').append(`<option value="${r.content_data[i].id}">${r.content_data[i].name}</option>`);
                    }
                    $("#edit_content_ids").val(r.content).trigger("change");
                } else {
                  
                    if (r.order_by_upload == 1) {
                        $("#edit_order_by_upload_yes").prop('checked', true);
                    } else {
                        $("#edit_order_by_upload_no").prop('checked', true);
                    }

                    if (r.order_by_like == 1) {
                        $("#edit_order_by_like_yes").prop('checked', true);
                    } else {
                        $("#edit_order_by_like_no").prop('checked', true);
                    }
                  
                    // Load category/subcategory if available
                    initCategoryDropdown('edit_category_id', 'edit_category_id_hidden', 'edit_sub_category_id', r.category_id, r.sub_category_id);
                }

                if(r.content_type ==1){
                    setContentVisibility(r.content_type, "edit_" , r.category_id ,true);
                    $(`.edit_background_type_drop input[name='background_type'][value='${r.background_type}']`).prop("checked", true).trigger("change");  
                    toggleBackgroundType("edit_");
                    
                }
                if(r.content_type ==2){
                    setVisibilityBasedOnType(r.product_content_type, r.section_type, "edit_" ,true);
                    $(`.edit_background_type_drop input[name='background_type'][value='${r.background_type}']`).prop("checked", true).trigger("change");  
                    toggleBackgroundType("edit_");
                }
             

                // Initial visibility setup
                $("#edit_content_type").off('change').on('change', function () {
                    const type = $(this).val();
                    resetBackgroundType("edit_");
                    setContentVisibility(type, "edit_");

                });
                // Change event binding (unbind before binding to avoid duplicate handlers)
                $("#edit_product_content_type").off('change').on('change', function () {
                    const type = $(this).val();
                    const section_type = $(".edit_section_type input[name='section_type']:checked").val();
                    resetBackgroundType("edit_");
                    setVisibilityBasedOnType(type, section_type, "edit_" ,true);

                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(errorThrown, textStatus);
            }
        });
    }

    function update_section(){

        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if(Demo_Mode == 1){

            $("#dvloader").show();
            var id = $('#edit_id').val();
            var formData = new FormData($("#edit_content_section")[0]);
            formData.append('top_type_id', top_type_id);

            var url = '{{ route("admin.section.update", ":id") }}';
                url = url.replace(':id', id);

            $.ajax({
                headers: {
				    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
			    enctype: 'multipart/form-data',
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    if(resp.status == 200){
                        $('#exampleModal').modal('toggle');
                    }
                    get_responce_message(resp, 'edit_content_section', '{{ route("admin.section.index") }}');
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

    function change_status(id) {

        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if(Demo_Mode == 1){

            $("#dvloader").show();
            var url = `{{ route('admin.section.change_status', '') }}/${id}`;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: url,
                data: {id: id},
                success: function(resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) {
                        if (resp.status_code == 1) {
                            $('#' + id).text('{{ __('label.show') }}').removeClass('hide-btn')
                                .addClass('show-btn');
                        } else {
                            $('#' + id).text('{{ __('label.hide') }}').removeClass('show-btn')
                                .addClass('hide-btn');
                        }
                            toastr.success(resp.success);
                        } else {
                            toastr.error(resp.errors);
                        }
                        
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        } else {
            showError();
        }
    };
    // Delete Section
    function delete_section(id){

        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if(Demo_Mode == 1){
            var result = confirm("{{__('label.delete_section')}}");
            if(result){ 
                $("#dvloader").show();
    
                var url = '{{ route("admin.section.show", ":id") }}';
                    url = url.replace(':id', id);
    
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    url: url,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, '', '{{ route("admin.section.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            }
        } else {
            showError();
        }
    }
    // Sortable Section
    $("#imageListId").sortable({
        update: function(event, ui) {
            getIdsOfImages();
        } //end update
    });
    function getIdsOfImages() {
        var values = [];
        $('.listitemClass').each(function(index) {
            values.push($(this).attr("id")
                .replace("imageNo", ""));
        });
        $('#outputvalues').val(values);
    }
    function sortableBTN(){
        var tab = $("ul.tabs li a.active");
        var is_home_screen = tab.data("is_home_screen");
        var top_type_id = tab.data("id");
        $("#dvloader").show();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("admin.section.content.sortable") }}',
            data: {
                is_home_screen: is_home_screen,
                top_type_id: top_type_id,
            },
            success: function(resp) {
                $("#dvloader").hide();
                $('#imageListId').html('');
                for (var i = 0; i < resp.result.length; i++) {
                    var data = '<div id="'+ resp.result[i].id+'" class="listitemClass mb-2" style="background-color: #e9ecef;border: 1px solid black;cursor: s-resize;">'+
                                '<p class="m-2">'+resp.result[i].title+'</p>'+
                            '</div>';
                    $('#imageListId').append(data);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $("#dvloader").hide();
                toastr.error(errorThrown, textStatus);
            }
        });
    }
    function save_section_sortable() {
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if(Demo_Mode == 1){
            $("#dvloader").show();
            var formData = new FormData($("#save_section_sortable")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.section.content.sortable.save") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_section_sortable', '{{ route("admin.section.index") }}');
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
