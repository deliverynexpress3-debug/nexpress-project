@extends('admin.layout.page-app')
@section('page_title', __('label.notification_setting'))
@section('tab_title', __('label.notification_setting'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.notification_setting') }}</h1>

            <div class="border-bottom row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('label.notification_setting') }}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center">
                    <a href="{{ route('admin.notification.index') }}" class="btn btn-default mw-120"
                        style="margin-top:-14px">{{ __('label.notification') }}</a>
                </div>
            </div>

            <form name="notification-setting" id="notification-setting" autocomplete="off">
                <div class="card custom-border-card mt-3">
                    <h5 class="card-header">{{__('label.customer_app')}}</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.one_signal_app_id')}}<span class="text-danger">*</span></label>
                                    <input name="c_onesignal_app_id" type="text" class="form-control" value="{{ $result['c_onesignal_app_id'] }}" placeholder="{{__('label.id_here')}}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.one_signal_reset_key')}}<span class="text-danger">*</span></label>
                                    <input name="c_onesignal_rest_key" type="text" class="form-control" value="{{ $result['c_onesignal_rest_key'] }}" placeholder="{{__('label.key_here')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="card custom-border-card mt-3">
                    <h5 class="card-header">{{__('label.seller_app')}}</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.one_signal_app_id')}}<span class="text-danger">*</span></label>
                                    <input name="s_onesignal_app_id" type="text" class="form-control" value="{{ $result['s_onesignal_app_id'] }}" placeholder="{{__('label.id_here')}}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.one_signal_reset_key')}}<span class="text-danger">*</span></label>
                                    <input name="s_onesignal_rest_key" type="text" class="form-control" value="{{ $result['s_onesignal_rest_key'] }}" placeholder="{{__('label.key_here')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="card custom-border-card mt-3">
                    <h5 class="card-header">{{__('label.delivery_app')}}</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.one_signal_app_id')}}<span class="text-danger">*</span></label>
                                    <input name="d_onesignal_app_id" type="text" class="form-control" value="{{ $result['d_onesignal_app_id'] }}" placeholder="{{__('label.id_here')}}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.one_signal_reset_key')}}<span class="text-danger">*</span></label>
                                    <input name="d_onesignal_rest_key" type="text" class="form-control" value="{{ $result['d_onesignal_rest_key'] }}" placeholder="{{__('label.key_here')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class=" text-right">
                    <button type="button" class="btn btn-default mw-120"
                        onclick="notification_setting()">{{ __('label.save') }}</button>
                    <a href="{{ route('admin.notification.index') }}"
                        class="btn btn-cancel mw-120 ml-2">{{ __('label.cancel') }}</a>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </form>
          
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
		sidebar_down(700);

        function notification_setting() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#notification-setting")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.notification.settingsave') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'notification-setting',
                        '{{ route('admin.notification.index') }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            } else {
                toastr.error('{{ __('label.you_have_no_right_to_add_edit_and_delete') }}');
            }
        }
    </script>
@endsection
