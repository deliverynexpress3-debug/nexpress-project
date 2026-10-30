@extends('admin.layout.page-app')
@section('page_title', __('label.seller_profile'))
@section('tab_title', __('label.seller_profile'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')
         <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.seller_profile') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        <li class="breadcrumb-item"><a href="{{ route('admin.seller.index') }}">{{ __('label.seller') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.seller_profile') }}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.seller.index') }}" class="btn btn-default mw-120"
                        style="margin-top:-14px">{{ __('label.back') }}</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card custom-border-card">

                        <!-- Header section with background -->
                        <div class="header">
                            <div class="profile-header-bg"></div>
                            <div class="profile-header-content">
                                <div class="profile-store-logo">
                                    <img src="{{ $info['store_logo'] }}" alt="{{$info['store_name']}}">
                                </div>
                                <div class="profile-store-details">
                                    <h1>{{ $info['store_name'] }}</h1>
                                    <p>{{ $info['store_address'] }}</p>
                                </div>
                               
                            </div>
                            <a href="{{route('admin.seller.review' ,$seller_id)}}">
                                <button class="btn btn-xl mt-5 review-btn"> <i class="fa fa-star mr-2"></i>{{__('label.reviews')}}</button>
                            </a>
                           
                        </div>

                        <!-- Content section -->
                        <div class="profile-content">
                          
                            <div class="profile-stats">
                                <div class="profile-stat-box">
                                    <p>{{ __('label.total_product') }}: <strong>{{ $TotalProduct ?? 0 }}</strong></p>
                                    <p>{{ __('label.total_sales') }}: <strong>{{$result['total_sales']}}</strong></p>
                                    <p>{{ __('label.avg_rating') }} :
                                        <strong>
                                            <!-- Display rating -->
                                            <span class="profile-rating">
                                                {{ number_format($result['avg_rating'], 1) }}
                                                @for ($i = 1; $i <= floor($result['avg_rating']); $i++)
                                                <i class="fas fa-star"></i>
                                                @endfor
                                                @if ($result['avg_rating'] - floor($result['avg_rating']) > 0)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @endif
                                                @for ($i = ceil($result['avg_rating']); $i < 5; $i++)
                                                    <i class="far fa-star"></i>
                                                @endfor
                                               
                                            </span>
                                        </strong>
                                    </p>

                                </div>
                            </div>

                            <div class="profile-contacts">

                                <h3>{{ __('label.contacts') }}</h3>
                                <!-- Profile Information Section -->
                                <div class="profile-info">

                                    <!-- Contact Information Section -->
                                    <div class="contact-details">
                                        <p>{{ __('label.seller') }}: {{ $result['full_name'] }}</p>
                                        <p>{{ __('label.email') }}: {{ $result['email'] }}</p>
                                        <p>{{ __('label.mobile_number') }}: {{ $result['country_code'] }}
                                            {{ $result['mobile_number'] }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{ __('label.all_item') }}</h5>
                        <form class="mb-4" action="{{ route('admin.seller.profile', $seller_id) }}" method="GET">
                            <div class="page-search">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="input_search"
                                        value="@if (isset($_GET['input_search'])) {{ $_GET['input_search'] }} @endif"
                                        class="form-control" placeholder="{{ __('label.search') }}"
                                        aria-label="Search" aria-describedby="basic-addon1" autocomplete="off">
                                </div>
    
                                <div class="sorting mr-3 w-50">
                                    <label>{{__('label.sort_by')}}</label>
                                    <select class="form-control" name="input_brand_id" id="input_brand_id">
                                        <option value="all">{{ __('label.all_brand') }}</option>
                                        @foreach ($brand as $key => $value)
                                            <option value="{{ $value->id }}"
                                                {{ isset($_GET['input_brand_id']) && $_GET['input_brand_id'] == $value->id ? 'selected' : '' }}>
                                                {{ $value->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
    
                                <div class="">
                                    <button class="btn btn-default w-100" type="submit">{{ __('label.SEARCH') }}</button>
                                </div>
                            </div>
                        </form>

                        <div class="profile-products-grid">
                            @foreach ($product as $key => $value)
                                <div class="profile-product-card">
                                    <a href="javascript:void(0);" data-bs-toggle="modal"
                                        data-bs-target="#imageModal-{{ $value->id }}">
                                        <img src="{{ $value->portrait_img }}" alt="{{ $value->name }}" />
                                    </a>

                                    <div class="action-buttons">
                                        <a class="detail-btn btn me-2" href="{{route('admin.item.detail', [$value->id])}}" title="Details">
                                            <i class="fa-solid fa-eye"></i>
                                           
                                        </a>
                                        <a class="edit-btn btn me-2" href="{{ route('admin.item.edit', [$value->id]) }}"
                                            title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a class="delete-btn btn"
                                            href="{{ route('admin.seller.product.destroy', [$value->id]) }}" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                    <p class="profile-product-name">{{ $value->name }}</p>
                                    <div class="d-flex justify-content-between align-items-center">

                                        @if ($value->status == 1)
                                            <button class="btn btn-sm" id="{{ $value->id }}"
                                                onclick="change_status('{{ $value->id }}', '{{ $value->status }}')"
                                                style="background:#058f00; color:#fff; font-weight:bold; border:none">{{ __('label.available') }}</button>
                                        @elseif($value->status == 0)
                                            <button class="btn btn-sm" id="{{ $value->id }}"
                                                onclick="change_status('{{ $value->id }}', '{{ $value->status }}')"
                                                style="background:#e3000b; color:#fff; font-weight:bold; border:none">{{ __('label.unavailable') }}</button>
                                        @endif

                                        <p class="profile-product-price mb-0">${{ $value->price }}</p>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div> Showing {{ $product->firstItem() }} to {{ $product->lastItem() }} of total
                                {{ $product->total() }} entries </div>
                            <div class="pb-5"> {{ $product->links() }} </div>
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
        $('#input_industries_id, #input_brand_id, #input_seller_id').select2();

        function change_status(id, status) {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var url = "{{ route('admin.seller.product.show', '') }}" + "/" + id;

                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: id,
                    success: function(resp) {
                        $("#dvloader").hide();
                        if (resp.status == 200) {
                            if (resp.Status == 1) {
                                $('#' + id).text("{{ __('label.available') }}");
                                $('#' + id).css({
                                    "background": "#058f00",
                                    "color": "white",
                                    "font-weight": "bold",
                                    "border": "none"
                                });
                            } else {
                                $('#' + id).text("{{ __('label.unavailable') }}");
                                $('#' + id).css({
                                    "background": "#e3000b",
                                    "color": "white",
                                    "font-weight": "bold",
                                    "border": "none"
                                });
                            }
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

        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.profile-product-card');

            cards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove 'active' class from all cards
                    cards.forEach(c => c.classList.remove('active'));

                    // Add 'active' class to the clicked card
                    this.classList.add('active');
                });
            });
        });
    </script>
@endsection
