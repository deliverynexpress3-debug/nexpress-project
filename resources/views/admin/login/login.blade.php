@extends('admin.layout.page-app')
@section('tab_title', __('label.login'))

@section('content')
<div class="h-100">
    <div class="h-100 no-gutters row">
        <div class="d-none d-lg-block h-100 col-lg-7 col-xl-7"> 
            <?php $view_status = $result['panel_login_page_view']; ?>
            @if($view_status == 1)
                <div class="left-caption">
                    <img src="{{ $result['panel_login_page_bg_image'] }}" class="bg-img" />
                    <div class="caption">
                        <div>
                            <!-- logo -->
                            <h1 style="font-size: 60px; font-weight: bold;" class="primary-color">{{ App_Name() }}</h1>

                            <?php $setting = Setting_Data(); ?>
                            <p class="text">
                                {{String_Cut($setting['app_description'], 200)}}
                            </p>
                        </div>
                    </div>
                </div>
            @elseif ($view_status == 2)
                <div class="left-caption">
                    <div class="caption" style="background-color: {{ $result['panel_login_page_bg_color']; }};">
                        <div>
                            <img src="{{ $result['panel_login_page_image'] }}" class="image-view mb-0 pb-0" height="500px" />
                        </div>
                    </div>
                </div>
            @else
                <div class="left-caption">
                    <img src="{{ Login_Image() }}" class="bg-img" />
                    <div class="caption">
                        <div>
                            <!-- logo -->
                            <h1 style="font-size: 60px; font-weight: bold;" class="primary-color">{{ App_Name() }}</h1>

                            <?php $setting = Setting_Data(); ?>
                            <p class="text">
                                {{String_Cut($setting['app_description'], 200)}}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="h-100 d-flex login-bg justify-content-center align-items-lg-center col-md-12 col-lg-5 col-xl-5">
            <div class="mx-auto col-sm-12 col-md-10 col-xl-8">
                <div class="py-5">

                    <div class="app-logo mb-4 text-center font-weight-bold">
                        <h1 class="primary-color mb-4 d-block d-lg-none">{{ App_Name() }}</h1>
                        <h1 class="primary-color mb-0">{{__('label.login')}}</h1>
                    </div>

                    <h4 class="mb-0 font-weight-bold text-center">
                        <span class="d-block mb-2 text-black">{{__('label.welcome_back_admin')}}</span>
                        <span class="text-black">{{__('label.please_sign_in_to_your_account')}}</span>
                    </h4>

                    @php
                        $emailValue = env('DEMO_MODE') == 'ON' ? 'admin@admin.com' : '';
                        $passwordValue = env('DEMO_MODE') == 'ON' ? 'admin' : '';
                    @endphp
                    <form id="login_form">
                        <div class="form-row mt-4">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label>{{__('label.email')}}</label>
                                    <input name="email" type="email" value="{{ $emailValue }}" placeholder="{{__('label.email_here')}}" class="form-control" autofocus>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label>{{__('label.password')}}</label>
                                    <input name="password" type="password" value="{{ $passwordValue }}" placeholder="{{__('label.password_here')}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-sm-12 text-center">
                                <button class="btn btn-default w-100" onclick="save_login()" type="button">{{__('label.login')}}</button>
                            </div>
                        </div>
                    </form>

                    @if( env('DEMO_MODE') == 'ON')
                        <hr>
                        <h6>
                            {{__('label.if_you_cannot_login_then')}}<a href="{{ route('admin.login') }}" target="_blank" class="btn-link">{{__('label.click_here')}}</a>
                        </h6>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>
   
@endsection

@section('pagescript')
    <script>
        function save_login() {
            $("#dvloader").show();
            var formData = new FormData($("#login_form")[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.save.login") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'login_form', '{{ route("admin.dashboard") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // Press Enter Key & Save Form
        $('#login_form').keypress((e) => { 
            // Enter key corresponds to number 13 
            if (e.which === 13) { 
                save_login();
            } 
        })
    </script>
@endsection