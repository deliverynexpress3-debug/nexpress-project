@extends('admin.layout.page-app')
@section('page_title', __('label.sliders'))
@section('tab_title', __('label.sliders'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.sliders') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('label.sliders') }}</li>
                    </ol>
                </div>

            </div>
            <ul class="tabs nav nav-pills custom-tabs inline-tabs" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="app-tab" onclick="Top_Content('1', '0')" data-is_home_screen="1" href="#app" role="tab" data-toggle="tab" aria-controls="app" aria-selected="true"> {{ __('label.home') }}</a>
                </li>
                @for ($i = 0; $i < count($type); $i++)
                    <li class="nav-item">
                        <a class="nav-link" id="{{ $type[$i]['name'] }}-tab" onclick="Top_Content('2','{{ $type[$i]['id'] }}')" data-is_home_screen="2" data-id="{{ $type[$i]['id'] }}" data-toggle="tab" href="#{{ $type[$i]['name'] }}" role="tab" aria-controls="{{ $type[$i]['name'] }}" aria-selected="true">
                            {{ $type[$i]['name'] }}
                        </a>
                    </li>
                @endfor
            </ul>
            
            
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{ __('label.add_banner') }}</h5>
                       
                            <div class="card-body">
                                <form id="save_banner">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-row">
                                        <div class="col-md-2 mr-3 content_type">
                                            <div class="form-group mt-4">
                                                <label>{{ __('label.banner_type') }}</label>
                                                <select class="form-control" name="content_type" id="content_type">
                                                    <option value="" selected>{{ __('label.select_banner_type') }}
                                                    </option>
                                                    <option value="1">{{ __('label.offers_type') }}</option>
                                                    <option value="2">{{ __('label.producttype') }}</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mt-4">
                                                <label>{{ __('label.content') }}</label>
                                                <select class="form-control" name="content_id" id="content_id"
                                                    style="width:100%!important;">
                                                    <option value="" selected disabled>
                                                        {{ __('label.select_content') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="after-add-more"></div>

                            </div>
                       
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
   
    <script>
        sidebar_down(300);

        $(document).ready(function () {
            $("#content_id").select2();

            var Tab = $("ul.tabs li a.active");
            var Is_home_screen = Tab.data("is_home_screen");
            var TopTypeId = Is_home_screen == 1 ? 0 : (ActiveTab.data("id") ?? 0);

            // On tab click update variables
            $('.nav-item a').on('click', function () {

            $('.nav-item a').removeClass('active');
                $(this).addClass('active');

                Is_home_screen = $(this).data("is_home_screen");

                // If Home tab → TopTypeId = 0
                if (Is_home_screen == 1) {
                    TopTypeId = 0;
                } else {
                    TopTypeId = $(this).data("id") ?? 0;
                }


            });
           

            // On content_type change
            $("#content_type").on('change', function () {
                let content_type = $(this).val();

                $("#content_id").empty().append('<option value="" selected disabled>{{__('label.select_content')}}</option>');

                if (content_type == 1 || content_type == 2) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ route('admin.slider.typebased') }}',
                        data: {
                            content_type: content_type,
                            is_home_screen: Is_home_screen,
                            top_type_id: TopTypeId,
                        },
                        success: function (resp) {
                            resp.result.forEach(item => {
                                let name = content_type == 1 ? item.title : item.name;
                                $('#content_id').append(`<option value="${item.id}">${name}</option>`);
                            });
                        },
                        error: function (xhr, status, error) {
                            toastr.error(error, status);
                        }
                    });
                }
            });

            // Save selected content
            $('#content_id').on('change', function () {
                let Demo_Mode = '{{ Demo_Mode() }}';
                if (Demo_Mode == 1) {
                    let Content_Type = $('#content_type').val();
                    let Content_Id = $(this).val();
                
                    $("#dvloader").show();
                
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ route('admin.slider.store') }}',
                        data: {
                            is_home_screen: Is_home_screen,
                            content_type: Content_Type,
                            content_id: Content_Id,
                            top_type_id: TopTypeId,
                        },
                        success: function (resp) {
                            $("#dvloader").hide();
                            get_responce_message(resp, 'save_banner', '{{ route('admin.slider.index') }}');
                        },
                        error: function (xhr, status, error) {
                            $("#dvloader").hide();
                            toastr.error(error, status);
                        }
                    });
                } else {
                    showError();
                }
            });

            // Initial load for default active tab
            if (Is_home_screen == 1) {
                loadBannerList(Is_home_screen, TopTypeId);
            }
        });

        // Load banner list for selected tab
        function Top_Content(is_home_screen, top_type_id) {
            $("#content_type").val('');
            $("#content_id").empty().append('<option value="" selected disabled>{{__('label.select_content')}}</option>');
            $(".after-add-more .row").remove();
        
            loadBannerList(is_home_screen, top_type_id);
        }

        // Shared logic to load banner list
        function loadBannerList(is_home_screen, top_type_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: {
                    is_home_screen: is_home_screen,
                    top_type_id: top_type_id
                },
                url: '{{ route('admin.slider.bannerlist') }}',
                success: function (resp) {
                    $('.after-add-more').empty();

                    if (resp.result.length > 0) {
                        $('.after-add-more').append(`
                            <div class="form-group row mb-0 pb-0">
                                <div class="col-md-2"><label>{{__('label.banner_type')}}</label></div>
                                <div class="col-md-4"><label>{{__('label.content')}}</label></div>
                            </div>
                        `);
                    }

                    resp.result.forEach(item => {
                    
                        let content_type = item.content_type == 1 ? "Offers" : item.content_type == 2 ? "Product" : "";
                        let title = item.content_type == 1 ? item.offers.title : item.item.name;
                        let image = item.content_type == 1 ? item.offers.image : item.item.portrait_img;

                        $('.after-add-more').append(`
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" value="${content_type}" readonly />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="${title}" readonly />
                                </div>
                                <div class="col-md-1 ml-5">
                                    <img src="${image}" height="50" width="50" />
                                </div>
                                <div class="col-md-1">
                                    <a onclick="DeleteBanner(${item.id})" class="btn btn-danger" title="Delete">
                                        <i class="fa-solid fa-trash-can fa-xl"></i>
                                    </a>
                                </div>
                            </div>
                        `);
                    });
                },
                error: function (xhr, status, errorThrown) {
                    toastr.error(errorThrown, status);
                }
            });
        }

        // Delete Banner
        function DeleteBanner(id) {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                var url = "{{ route('admin.slider.destroy', '') }}" + "/" + id;
                $("#dvloader").show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'DELETE',
                    url: url,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_banner', '{{ route('admin.slider.index') }}');
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
