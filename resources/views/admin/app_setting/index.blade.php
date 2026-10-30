@extends('admin.layout.page-app')
@section('page_title', __('label.app_settings'))
@section('tab_title', __('label.app_settings'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.app_settings')}}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.app_settings')}}</li>
                    </ol>
                </div>
            </div>

            <ul class="nav nav-pills custom-tabs inline-tabs" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="app-tab" data-toggle="tab" href="#app" role="tab" aria-controls="app" aria-selected="true">{{__('label.app_settings')}}</a>
                </li>
                @if( env('DEMO_MODE') == 'OFF')
                <li class="nav-item">
                    <a class="nav-link" id="smtp-tab" data-toggle="tab" href="#smtp" role="tab" aria-controls="smtp" aria-selected="false">{{__('label.smtp')}}</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">{{__('label.social_setting')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="onboarding-tab" data-toggle="tab" href="#onboarding" role="tab" aria-controls="onboarding" aria-selected="false">{{__('label.onboarding_screen')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial" role="tab" aria-controls="financial" aria-selected="false">{{__('label.financial_settings')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="delivery-tab" data-toggle="tab" href="#delivery" role="tab" aria-controls="delivery" aria-selected="false">{{__('label.delivery_setting')}}</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.app_settings')}}</h5>
                        <div class="card-body">
                            <form id="app_setting" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="col-md-8">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.app_name')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="app_name" value="{{ $result['app_name'] }}" class="form-control" placeholder="{{__('label.app_name_here')}}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.app_version')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="app_version" value="{{ $result['app_version'] }}" class="form-control" placeholder="{{__('label.app_version_here')}}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.email')}} <span class="text-danger">*</span></label>
                                                <input type="email" name="email" value="{{ $result['email'] }}" class="form-control" placeholder="{{__('label.email_here')}}">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.author')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="author" value="{{ $result['author'] }}" class="form-control" placeholder="{{__('label.author_here')}}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label> {{__('label.contact')}} <span class="text-danger">*</span></label>
                                                <input type="tel" name="contact" inputmode="tel" pattern="[+0-9\s\-\(\)]*" value="{{ $result['contact'] }}" class="form-control" placeholder="{{__('label.contact_here')}}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.website')}}<span class="text-danger">*</span></label>
                                                <input type="url" name="website" value="{{ $result['website'] }}" class="form-control" placeholder="{{__('label.website_here')}}">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.company_name')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="company_name" value="{{ $result['company_name'] }}" class="form-control" placeholder="{{__('label.company_name_here')}}">
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group ml-4">
                                            <label>{{__('label.app_logo')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type="file" name="app_logo" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                                <label class="avatar-preview drop-area">
                                                    <img src="{{$result['app_logo']}}" class="imagePreview">
                                                    <input type="hidden" name="old_app_logo" value="{{$result['app_logo']}}">
                                                </label>
                                            </div>
                                        </div>
                                       
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group ml-4">
                                            <label>{{__('label.company_logo')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type="file" name="company_logo" class="imageUpload" accept=".png, .jpg, .jpeg" hidden="">
                                                <label class="avatar-preview drop-area">
                                                    <img src="{{$result['company_logo']}}" class="imagePreview">
                                                    <input type="hidden" name="old_company_logo" value="{{$result['company_logo']}}">
                                                </label>
                                            </div>
                                        </div>
                                      
                                    </div>
                                  
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>{{__('label.app_description')}}<span class="text-danger">*</span></label>
                                        <textarea name="app_description" rows="4" style="min-height:110px;" class="form-control" placeholder="{{__('label.app_description_here')}}">{{ $result['app_description'] }}</textarea>
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="app_setting()">{{__('label.save')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- API Configrations -->
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.api_configrations')}}</h5>
                    
                        <div class="card-body">
                            <div class="input-group">
                                <div class="col-2">
                                    <label class="pt-3" style="font-size:16px; font-weight:500;">
                                        {{__('label.api_path')}}
                                    </label>
                                </div>
                                <input type="text" readonly
                                       value="{{ url('/') }}/api/customer"
                                       class="form-control"
                                       id="api_path_main">
                    
                                <div class="input-group-text ml-2"
                                     onclick="copyApiPath('api_path_main')">
                                    <i class="fa-solid fa-copy fa-2xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="col-2">
                                    <label class="pt-3" style="font-size:16px; font-weight:500;">
                                        {{__('label.seller_app_api_path')}}
                                    </label>
                                </div>
                                <input type="text" readonly
                                       value="{{ url('/') }}/api/seller/"
                                       class="form-control"
                                       id="api_path_seller">
                    
                                <div class="input-group-text ml-2"
                                     onclick="copyApiPath('api_path_seller')">
                                    <i class="fa-solid fa-copy fa-2xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="col-2">
                                    <label class="pt-3" style="font-size:16px; font-weight:500;">
                                        {{__('label.delivery_app_api_path')}}
                                    </label>
                                </div>
                                <input type="text" readonly
                                       value="{{ url('/') }}/api/deliveryboy/"
                                       class="form-control"
                                       id="api_path_delivery">
                    
                                <div class="input-group-text ml-2"
                                     onclick="copyApiPath('api_path_delivery')">
                                    <i class="fa-solid fa-copy fa-2xl"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                {{-- Purchase Code section temporarily disabled
                                <div class="col-12">
                                    <div class="card custom-border-card">
                                        <h5 class="card-header">{{__('label.purchase_code')}}</h5>
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>{{__('label.purchase_code')}}</label>
                                                    <input type="text" class="form-control" value="{{env('PURCHASE_CODE')}}" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label> {{__('label.envato_name')}}</label>
                                                    <input type="text" class="form-control" value="{{env('BUYER_USERNAME')}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                --}}
                                <div class="col-12">
                                    <div class="card custom-border-card">
                                        <h5 class="card-header">{{ __('label.google_map_key') }}</h5>
                                        <div class="card-body">
                                            <form id="save_google_map">
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <input type="text" name="google_map_api_key" class="form-control" value="{{ $result['google_map_api_key'] }}" placeholder="{{ __('label.key_here') }}">
                                                        <label class="mt-1 ml-1 text-gray">{{ __('label.search_for_better_result') }} <a href="https://console.cloud.google.com/projectselector2/google/maps-apis/overview" target="_blank" class="btn-link">{{ __('label.click_here') }}</a></label>
                                                    </div>
                                                </div>
                                                <div class="border-top pt-3 text-right">
                                                    <button type="button" class="btn btn-default mw-120" onclick="save_google_map_key()">{{ __('label.save') }}</button>
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-6">
                            <!-- Currency Settings -->
                            <div class="card custom-border-card">
                                <h5 class="card-header">{{__('label.currency_settings')}}</h5>
                                <div class="card-body">
                                    <form id="save_currency">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>{{__('label.currency_name')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="currency" class="form-control" value="{{ $result['currency'] }}" placeholder="{{__('label.currency_name_here')}}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label> {{__('label.currency_code')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="currency_code" class="form-control" value="{{ $result['currency_code'] }}" placeholder="{{__('label.currency_code_here')}}">
                                            </div>
                                        </div>
                                        <div class="border-top pt-3 text-right">
                                            <button type="button" class="btn btn-default mw-120" onclick="save_currency()">{{__('label.save')}}</button>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="smtp" role="tabpanel" aria-labelledby="smtp-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.email_setting_smtp')}}</h5>
                        <div class="card-body">
                            <form id="smtp_setting">
                                <input type="hidden" name="id" value="{{ $smtp->id }}">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.is_smtp_active')}}<span class="text-danger">*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="">{{__('label.select_status')}}</option>
                                            <option value="0" {{ $smtp->status == 0  ? 'selected' : ''}}>{{__('label.no')}}</option>
                                            <option value="1" {{ $smtp->status == 1  ? 'selected' : ''}}>{{__('label.yes')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.host')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="host" class="form-control" value="{{ $smtp->host }}" placeholder="{{__('label.host_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.port')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="port" class="form-control" value="{{ $smtp->port }}" placeholder="{{__('label.port_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.protocol')}}<span class="text-danger">*</span></label>
                                        <select name="protocol" class="form-control">
                                            @php $proto = strtolower($smtp->protocol ?? 'tls'); @endphp
                                            <option value="tls" {{ $proto === 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ $proto === 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="starttls" {{ $proto === 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                                            <option value="none" {{ $proto === 'none' ? 'selected' : '' }}>None</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.user_name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="user" class="form-control" value="{{ $smtp->user }}" placeholder="{{__('label.user_name_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.password')}}<span class="text-danger">*</span></label>
                                        <input type="password" name="pass" class="form-control" value="{{ $smtp->pass }}" placeholder="{{__('label.password_here')}}">
                                        <label class="mt-1 text-gray">{{__('label.search_for_better_result')}} <a href="https://support.google.com/mail/answer/185833?hl=en" target="_blank" class="btn-link">{{__('label.click_here')}}</a></label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.from_name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="from_name" class="form-control" value="{{ $smtp->from_name }}" placeholder="{{__('label.from_name_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.from_email')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="from_email" class="form-control" value="{{ $smtp->from_email }}" placeholder="{{__('label.from_email_here')}}">
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="smtp_setting()">{{__('label.save')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                    @if($smtp->status == 1)
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.email_test')}}</h5>
                        <div class="card-body">
                            <form id="email_test">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>{{__('label.email')}}<span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="{{__('label.email_here')}}">
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="email_test()">{{__('label.send_mail')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.social_links')}}</h5>
                        <div class="card-body">
                            <form id="social_link" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="name[]" class="form-control" placeholder="{{__('label.name_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.url')}}<span class="text-danger">*</span></label>
                                        <input type="url" name="url[]" class="form-control" placeholder="{{__('label.url_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.icon')}}<span class="text-danger">*</span></label>
                                        <input type="file" name="image[]" class="form-control import-file social_img" id="social_img" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="old_image[]" value="">
                         
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-file ml-2">
                                            <img src="{{ asset('assets/imgs/upload_img.png') }}" style="height:90px;width: 120px;" class="img-thumbnail" id="link_img_social_img">
                                            <input type="hidden" name="old_step_image[]" value="">
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-1 mt-2">
                                        <div class="flex-grow-1 px-5 d-inline-flex">
                                            <div class="change mr-3 mt-4" id="add_btn">
                                                <a class="btn btn-success add-more text-white" onclick="add_more_link()">+</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @for ($i=0; $i < count($social_link); $i++)
                                    <div class="social_part">
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                                <input type="text" name="name[]" value="{{ $social_link[$i]['name'] }}" class="form-control" placeholder="{{__('label.name_here')}}">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{__('label.url')}}<span class="text-danger">*</span></label>
                                                <input type="url" name="url[]" value="{{ $social_link[$i]['url'] }}" class="form-control" placeholder="{{__('label.url_here')}}">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{__('label.icon')}}<span class="text-danger">*</span></label>
                                                <input type="file" name="image[]" class="form-control import-file social_img" id="social_img_{{$i}}" accept=".png, .jpg, .jpeg">
                                                <input type="hidden" name="old_image[]" value="{{ basename($social_link[$i]['image']) }}">
                                            
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-file ml-2">
                                                    <img src="{{ $social_link[$i]['image'] }}" style="height:90px;width: 120px;" class="img-thumbnail" id="link_img_social_img_{{$i}}">
                                                </div>
                                            </div>
                                   
                                            <div class="col-md-1 mt-2">
                                                <div class="flex-grow-1 px-5 d-inline-flex">
                                                    <div class="change mr-3 mt-4" id="add_btn">
                                                        <a class="btn btn-danger text-white remove_link">-</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                                    
                                <div class="add-more-social-link"></div>

                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="social_link()">{{__('label.save')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="onboarding" role="tabpanel" aria-labelledby="onboarding-tab">
                    <form id="onboarding_screen" autocomplete="off" method="post" enctype="multipart/form-data">
                        <div class="card custom-border-card mt-3">
                            <h5 class="card-header">{{__('label.onboarding_screen')}}</h5>
                            <div class="card-body">
                                <div class="main_screen form-row mt-3 mb-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title[]" class="form-control" placeholder="{{__('label.title_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.description')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="description[]" class="form-control" placeholder="{{__('label.describe_here')}}">
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.image')}}<span class="text-danger">*</span></label>
                                            <input type="file" class="form-control import-file screen-image" name="image[]" accept="image/png, image/jpg, image/jpeg" preview-id="Uploaded-screen-Image">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="custom-file ml-2">
                                                <img src="{{ asset('assets/imgs/upload_img.png') }}" style="height:90px; width: 120px;" class="img-thumbnail" id="Uploaded-screen-Image">
                                                <input type="hidden" name="old_image[]" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        <div class="flex-grow-1 px-5 d-inline-flex">
                                            <div class="change mr-3 mt-4" id="add_btn" title="Add More">
                                                <a class="add_new_screen btn btn-success add-more text-white">+</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @for ($i=0; $i < count($onboarding_screen); $i++)
                                <div class="main_screen form-row mt-3 mb-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title[]" value="{{$onboarding_screen[$i]['title']}}" class="form-control" placeholder="{{__('label.title_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.description')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="description[]" value="{{$onboarding_screen[$i]['description']}}" class="form-control" placeholder="{{__('label.describe_here')}}">
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.image')}}<span class="text-danger">*</span></label>
                                            <input type="file" class="form-control import-file screen-image" name="image[]" accept="image/png, image/jpg, image/jpeg" preview-id="Uploaded-screen-Image{{$i}}">
                                            <input type="hidden" name="old_image[]" value="{{ basename($onboarding_screen[$i]['image']) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="custom-file ml-2">
                                                <img src="{{$onboarding_screen[$i]['image']}}" style="height:90px; width: 120px;" class="img-thumbnail" id="Uploaded-screen-Image{{$i}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        <div class="flex-grow-1 px-5 d-inline-flex">
                                            <div class="change mr-3 mt-4" id="add_btn" title="Add More">
                                                <a class="remove_screen btn btn-danger add-more text-white">-</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endfor

                                <div class="new_screen"></div>
                            </div>
                            <div class="border-top pt-3 text-right">
                                <button type="button" class="btn btn-default mw-120" onclick="onboarding_screen()">{{__('label.save')}}</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                               
                            </div>
                        </div>
                        
                    </form>
                
                </div>
                <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
                    <form id="financial_setting" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-6">
                                <div class="card custom-border-card">
                                    <h5 class="card-header">{{__('label.deliveryboy_commission')}}</h5>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>{{__('label.type')}}</label>
                                                <select name="delivery_boy_commission_type" class="form-control">
                                                  
                                                    <option value="1" {{ $result['delivery_boy_commission_type'] == 1  ? 'selected' : ''}}>{{__('label.percentage')}}</option>
                                                    <option value="2" {{ $result['delivery_boy_commission_type'] == 2  ? 'selected' : ''}}>{{__('label.fixed')}}</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>{{__('label.commission_amount')}}</label>
                                                <input type="number" name="delivery_boy_commission_value" value="{{$result['delivery_boy_commission_value']}}" class="form-control" placeholder="{{__('label.enter_amount')}}">
                                            </div>
                                          
                                        </div>
                                          
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card custom-border-card">
                                    <h5 class="card-header">{{__('label.seller_commission')}}</h5>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>{{__('label.type')}}</label>
                                                <select name="seller_commission_type" class="form-control">
                                                  
                                                    <option value="1" {{ $result['seller_commission_type'] == 1  ? 'selected' : ''}}>{{__('label.percentage')}}</option>
                                                    <option value="2" {{ $result['seller_commission_type'] == 2  ? 'selected' : ''}}>{{__('label.fixed')}}</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>{{__('label.commission_amount')}}</label>
                                                <input type="number" name="seller_commission_value" value="{{$result['seller_commission_value']}}" class="form-control" placeholder="{{__('label.enter_amount')}}">
                                            </div>
                                           
                                        </div>
                                          
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-6">
                                <div class="card custom-border-card">
                                    <h5 class="card-header">{{__('label.settelment_payout')}}</h5>
                                    <div class="card-body">
                                        <div class="form-row">
                                          
                                            <div class="form-group col-md-6">
                                                <label>{{__('label.payout_day')}}</label>
                                                <input type="number" name="settelment_payout_day" value="{{$result['settelment_payout_day']}}" class="form-control" placeholder="{{__('label.day_here')}}">
                                            </div>
                                        </div>
                                          
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="save_financial()">{{__('label.save')}}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>

                   
                </div>
                <div class="tab-pane fade" id="delivery" role="tabpanel" aria-labelledby="delivery-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.delivery_setting')}}</h5>
                        <div class="card-body">
                            <form id="delivery_setting" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('label.delivery_charges')}}</label>
                                            <label class="switch ml-3">
                                                <input type="checkbox" name="is_delivery_charges" id="EnableToogle" {{ $result['is_delivery_charges'] == 'on' ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="DeliveryCharges">
											<div class="form-group">
												<label> {{ __('label.regular_delivery_charges') }}</label>
												<input type="number" name="delivery_charges" value="{{$result['delivery_charges']}}" class="form-control" placeholder="{{__('label.enter_regular_delivery_charges')}}">
											</div>
									</div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('label.handling_charges')}}</label>
                                            <label class="switch ml-3">
                                                <input type="checkbox" name="is_handling_charges" id="EnableHandlingToogle" {{ $result['is_handling_charges'] == 'on' ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="HandlingCharges">
                                        <div class="form-group">
                                            <label> {{ __('label.handling_charges_amount') }}</label>
                                            <input type="number" name="handling_charges" value="{{$result['handling_charges']}}"  class="form-control" placeholder="{{__('label.enter_regular_delivery_charges')}}">
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="update_delivery_setting()">{{__('label.save')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
    <script>
        // Sidebar Scroll Down
        let sidebarHeight = $('.sidebar')[0].scrollHeight;
        sidebar_down(sidebarHeight);

        // API Key
        function copyApiPath(id) {
            const input = document.getElementById(id);
            input.select();
            input.setSelectionRange(0, 99999);

            document.execCommand('copy');

            alert("Copied the API Path: " + input.value);
        }

		// Fast Delivery 
		var Delivery = "{{ $result['is_delivery_charges'] }}";
    	if (Delivery =='on') {
			$('#DeliveryCharges').show(); // Show div when toggle is ON
		  
    	} else {
			$('#DeliveryCharges').hide(); // Hide div when toggle is Off
		
    	}
		var HandlingCharges = "{{ $result['is_handling_charges'] }}";
    	if (HandlingCharges == 'on') {
			$('#HandlingCharges').show(); // Show div when toggle is ON
    	} else {
			$('#HandlingCharges').hide(); // Hide div when toggle is Off
    	}

        $('#EnableToogle').change(function() {	
            if($(this).is(':checked')) {
                $('#DeliveryCharges').show(); // Show div when toggle is ON
            } else {
                $('#DeliveryCharges').hide(); // Hide div when toggle is OFF
            }
    	});

		$('#EnableHandlingToogle').change(function() {	
        	if($(this).is(':checked')) {
        	    $('#HandlingCharges').show(); // Show div when toggle is ON
        	} else {
        	    $('#HandlingCharges').hide(); // Hide div when toggle is OFF
        	}
    	});

        // App Setting
        function app_setting() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#app_setting")[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.appsetting.app") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'app_setting', '{{ route("admin.appsetting.index") }}');
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
       
        // Currency Setting
        function save_currency() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#save_currency")[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.appsetting.currency") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        $("html, body").animate({
                            scrollTop: 0
                        }, "swing");
                        get_responce_message(resp);
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
        function save_google_map_key() {
            var Check_Admin = '<?php echo Demo_Mode(); ?>';
            if (Check_Admin == 1) {

                var formData = new FormData($("#save_google_map")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.appsetting.google_map_key') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_google_map', '{{ route("admin.appsetting.index") }}');
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

        // SMTP
        function smtp_setting() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#smtp_setting")[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.appsetting.smtp") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'smtp_setting', '{{ route("admin.appsetting.index") }}');
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
        // Email Test
        function email_test() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                var formData = new FormData($("#email_test")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.appsetting.emailtest") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'email_test', '{{ route("admin.appsetting.index") }}');
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
        // Commission
        function save_financial() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#financial_setting")[0]);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.appsetting.financial") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'financial_setting', '{{ route("admin.appsetting.index") }}');
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
        function update_delivery_setting() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
            
                $("#dvloader").show();
                var formData = new FormData($("#delivery_setting")[0]);
            
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.appsetting.delivery") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'delivery_setting', '{{ route("admin.appsetting.index") }}');
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
        // Social Link Add-Remove Link Part
        var i = -1;
        function add_more_link() {

            var data = '<div class="social_part">';
            data += '<div class="row">';
            data += '<div class="form-group col-md-2">';
            data += '<label>{{__("label.name")}}<span class="text-danger">*</span></label>';
            data += '<input type="text" name="name[]" class="form-control" placeholder="{{__("label.name_here")}}">';
            data += '</div>';
            data += '<div class="form-group col-md-3">';
            data += '<label>{{__("label.url")}}<span class="text-danger">*</span></label>';
            data += '<input type="url" name="url[]" class="form-control" placeholder="{{__("label.url_here")}}">';
            data += '</div>';
            data += '<div class="form-group col-lg-3">';
            data += '<label>{{__("label.icon")}}<span class="text-danger">*</span></label>';
            data += '<input type="file" name="image[]" class="form-control import-file social_img" id="social_img_' + i + '" accept=".png, .jpg, .jpeg">';
            data += '<input type="hidden" name="old_image[]" value="">';
            data += '<input type="hidden" name="old_storage_type[]" value="">';
            data += '</div>';
            data += '<div class="form-group">';
            data += '<div class="custom-file ml-2">';
            data += '<img src="{{ asset("assets/imgs/upload_img.png") }}" style="height: 90px; width: 120px;" class="img-thumbnail" id="link_img_social_img_' + i + '">';
            data += '</div>';
            data += '</div>';
            data += '<div class="col-md-1 mt-2">';
            data += '<div class="flex-grow-1 px-5 d-inline-flex">';
            data += '<div class="change mr-3 mt-4" id="add_btn">';
            data += '<a class="btn btn-danger add-more text-white remove_link">-</a>';
            data += '</div>';
            data += '</div>';
            data += '</div>';
            data += '</div>';
            data += '</div>';

            $('.add-more-social-link').append(data);
            i--;
            $("html, body").animate({
                scrollTop: $(document).height()
            }, "slow");
        }
        $("body").on("click", ".remove_link", function(e) {
            $(this).parents('.social_part').remove();
        });
        // Multipal Img Show 
        $(document).on('change', '.social_img', function() {
            readURL(this, this.id);
        });
        $(document).on('change', '.on_boarding_img', function() {
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
        let screenIndex = -1;
        $(document).on('change', '.screen-image', function() {
            let reader = new FileReader();
            let previewId = $(this).attr('preview-id');

            reader.readAsDataURL(this.files[0]);
            reader.onload = function(e) {
                $('#' + previewId).attr('src', e.target.result);
            };
        });
        $(document).on('click', '.add_new_screen', function() {
            let newscreen = `
                    <div class="screen form-row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                <input type="text" name="title[]" class="form-control" placeholder="Title Here...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('label.description')}}<span class="text-danger">*</span></label>
                                <input type="text" name="description[]" class="form-control" placeholder="Describe Here...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('label.image')}}<span class="text-danger">*</span></label>
                                <input type="file" class="form-control import-file screen-image" name="image[]" accept="image/png, image/jpg, image/jpeg" preview-id="Uploaded-Image-${screenIndex}">
                                <input type="hidden" name="old_image[]" value="">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="custom-file ml-2">
                                    <img src="{{ asset("assets/imgs/upload_img.png") }}" style="height: 90px; width: 120px;" class="img-thumbnail" id="Uploaded-Image-${screenIndex}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 mt-2">
                            <div class="flex-grow-1 px-5 d-inline-flex">
                                <div class="change mr-3 mt-4" id="add_btn" title="Add More">
                                    <a class="remove_screen btn btn-danger add-more text-white">-</a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            $('.new_screen').append(newscreen);
            screenIndex++;
            $("html, body").animate({
                scrollTop: $(document).height()
            }, "slow");
        });
        // remove screen ot main screen
        $(document).on('click', '.remove_screen', function() {
            $(this).closest('.screen, .main_screen').remove();
        });
        // Social Link Save
        function social_link() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#social_link")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.appsetting.sociallink") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'social_link', '{{ route("admin.appsetting.index") }}');
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
        // OnBoarding Screen Save
        function onboarding_screen() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#onboarding_screen")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.appsetting.onboardingscreen") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'onboarding_screen', '{{ route("admin.appsetting.index") }}');
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