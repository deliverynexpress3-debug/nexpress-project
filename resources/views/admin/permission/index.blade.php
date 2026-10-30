@extends('admin.layout.page-app')
@section('page_title', __('label.permission_configuration'))
@section('tab_title', __('label.permission_configuration'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">

            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.permission_configuration')}}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.permission_configuration')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card">
                <h5 class="card-header">{{__('label.seller_panel_configuration')}}</h5>
                <div class="card-body">
                    <form id="permission_setting" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.brand_access_control')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="brand_access_yes" name="brand_access" class="custom-control-input" {{ ($result['brand_access'] == 1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="brand_access_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="brand_access_no" name="brand_access" class="custom-control-input" {{ ($result['brand_access'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="brand_access_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.category_access_control')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="category_access_yes" name="category_access" class="custom-control-input" {{ ($result['category_access'] == 1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="category_access_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="category_access_no" name="category_access" class="custom-control-input" {{ ($result['category_access'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="category_access_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.offers_&_promocode_access_control')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="offer_access_yes" name="offer_access" class="custom-control-input" {{ ($result['offer_access'] == 1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="offer_access_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="offer_access_no" name="offer_access" class="custom-control-input" {{ ($result['offer_access'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="offer_access_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.hashtag_access_control')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="hashtag_access_yes" name="hashtag_access" class="custom-control-input" {{ ($result['hashtag_access'] == 1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="hashtag_access_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="hashtag_access_no" name="hashtag_access" class="custom-control-input" {{ ($result['hashtag_access'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="hashtag_access_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.taxes_access_control')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="taxes_access_yes" name="taxes_access" class="custom-control-input" {{ ($result['taxes_access'] == 1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="taxes_access_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="taxes_access_no" name="taxes_access" class="custom-control-input" {{ ($result['taxes_access'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="taxes_access_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.stock_status_access_control')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="stock_status_access_yes" name="stock_status_access" class="custom-control-input" {{ ($result['stock_status_access'] == 1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="stock_status_access_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="stock_status_access_no" name="stock_status_access" class="custom-control-input" {{ ($result['stock_status_access'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="stock_status_access_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.attributes_access_control')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="attributes_access_yes" name="attributes_access" class="custom-control-input" {{ ($result['attributes_access'] == 1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="attributes_access_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="attributes_access_no" name="attributes_access" class="custom-control-input" {{ ($result['attributes_access'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="attributes_access_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.order_status_access')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="order_status_access_yes" name="order_status_access" class="custom-control-input" {{ ($result['order_status_access'] == 1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="order_status_access_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="order_status_access_no" name="order_status_access" class="custom-control-input" {{ ($result['order_status_access'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="order_status_access_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.return_order_status_access')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="return_order_status_access_yes" name="return_order_status_access" class="custom-control-input" {{ ($result['return_order_status_access'] == 1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="return_order_status_access_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="return_order_status_access_no" name="return_order_status_access" class="custom-control-input" {{ ($result['return_order_status_access'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="return_order_status_access_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="save_panel_setting()">{{__('label.save')}}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
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

        function save_panel_setting(){

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var formData = new FormData($("#permission_setting")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("admin.permission.store") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'permission_setting', '{{ route("admin.permission.index") }}');
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