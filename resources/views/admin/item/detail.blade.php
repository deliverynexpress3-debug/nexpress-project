@extends('admin.layout.page-app')
@section('page_title', __('label.view_product'))
@section('tab_title', __('label.view_product'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')
      
        <style>
            .hidden {
                display: none;
            }
        </style>
        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.view_product') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.item.index') }}">{{ __('label.product') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.view_product') }}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.item.index') }}" class="btn btn-default mw-120"
                        style="margin-top:-14px">{{ __('label.back') }}</a>
                </div>

            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card custom-border-card">
                        <div class="product-container">
                            <div class="image-gallery">
                                @foreach ($other_img as $index => $value)
                                    <img src="{{ $value['image'] }}" alt="Thumbnail {{ $index + 1 }}"
                                        class="image-item {{ $index >= 6 ? 'hidden' : '' }}">
                                @endforeach
                                @if (count($other_img) > 3)
                                    <a id="loadMoreBtn" class="mt-2">
                                        <span class="status-badge badge-info mr-1">{{__('label.load_more')}}</span>
                                    </a>
                                    <a id="loadLessBtn" class="mt-2 hidden">
                                        <span class="status-badge badge-info mr-1">{{__('label.load_less')}}</span>
                                    </a>
                                @endif
                            </div>

                            <div class="product-image">
                                <img src="{{ $data['portrait_img'] }}" alt="Round Papasan Chair">
                            </div>
                            <div class="product-details">

                                <div class="product-title">{{ $data['name'] }}</div>
                                <div class="product-price">{{ $data['price'] }}</div>
                                <div class="product-description">
                                    {{ $data['sort_description'] }}
                                </div>
                                <div class="product-info">
                                    <div><strong>{{__('label.category')}}:</strong> <span>{{ $data->category->name }}</span></div>
                                    <div><strong>{{__('label.brand')}}:</strong> <span>{{ $data->brand->name }}</span></div>
                                    @if ($data['product_type'] == 1)
                                        <div><strong>{{__('label.product_type')}}:</strong> <span>{{ __('label.physical') }}</span></div>
                                    @else
                                        <div><strong>{{__('label.product_type')}}:</strong> <span>{{ __('label.digital') }}</span></div>
                                    @endif

                                    <div><strong>{{__('label.tag')}}:</strong> <span> {{ $TagName }}</span></div>
                                    @if ($data['tax_include_price'] == 1)
                                        <div><strong>{{ __('label.tax_include_price') }}:</strong><span
                                                class="product status-badge badge-dark">{{__('label.yes')}}</span></div>
                                        <div class="mb-3"><strong>{{__('label.tax')}}:</strong> <span>{{ $Taxname }}</span></div>
                                    @else
                                        <div class="mb-3"><strong>{{ __('label.tax_include_price') }}:</strong> <span
                                                class="product status-badge badge-dark">{{__('label.no')}}</span></div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div><strong>{{ __('label.total_allowed_quantity') }}:</strong>
                                                <span><strong>{{ $data->total_allowed_quantity }}</strong></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div><strong>{{ __('label.minimum_order_quantity') }}:</strong>
                                                <span><strong>{{ $data->minimum_order_quantity }}</strong></span></div>

                                        </div>
                                        <div class="col-md-4">
                                            <div><strong>{{ __('label.minimum_free_order') }}:</strong>
                                                <span><strong>{{ $data->minimum_free_order_quantity }}</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            @if ($data['is_cod'] == 1)
                                                <div><strong>{{ __('label.is_cod_allowed') }}:</strong><span
                                                        class="product status-badge badge-dark">{{__('label.yes')}}</span></div>
                                            @else
                                                <div><strong>{{ __('label.is_cod_allowed') }}:</strong> <span
                                                        class="product status-badge badge-dark">{{__('label.no')}}</span></div>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            @if ($data['is_cancelable'] == 1)
                                                <div><strong>{{ __('label.is_cancelable') }}:</strong><span
                                                        class="product status-badge badge-dark">{{__('label.yes')}}</span></div>
                                            @else
                                                <div><strong>{{ __('label.is_cancelable') }}:</strong> <span
                                                        class="product status-badge badge-dark">{{__('label.no')}}</span></div>
                                            @endif
                                        </div>
                                        <div class="col-md-4">

                                            @if ($data['is_returnable'] == 1)
                                                <div><strong>{{ __('label.is_returnable') }}:</strong><span
                                                        class="product status-badge badge-dark">{{__('label.yes')}}</span></div>
                                            @else
                                                <div><strong>{{ __('label.is_returnable') }}:</strong> <span
                                                        class="product status-badge badge-dark">{{__('label.no')}}</span></div>
                                            @endif

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card custom-border-card">
                        <ul class="nav nav-pills custom-tabs inline-tabs" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="basicInfo-tab" data-toggle="tab" href="#basicInfo"
                                    role="tab" aria-controls="basicInfo" aria-selected="true">{{__('label.product_description')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="attributes-tab" data-toggle="tab" href="#attributesInfo"
                                    role="tab" aria-controls="attributesInfo" aria-selected="false">{{__('label.attributes')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="quantity-delivery-tab" data-toggle="tab"
                                    href="#quantitydeliveryInfo" role="tab" aria-controls="quantitydeliveryInfo"
                                    aria-selected="false">{{__('label.product_faq')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="taxes-stock-tab" data-toggle="tab" href="#taxesstockInfo"
                                    role="tab" aria-controls="taxesstockInfo" aria-selected="false">{{__('label.product_review')}}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- First Tab (Product Description) - Active by default -->
                            <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basicInfo-tab">
                                <div class="product-description">
                                    <?php echo htmlspecialchars_decode($data->long_description); ?>
                                </div>
                            </div>

                            <!-- Second Tab (Attributes) -->
                            <div class="tab-pane fade" id="attributesInfo" role="tabpanel" aria-labelledby="attributes-tab">
                                <div class="attributes-section">
                                    @if (count($p_attributes) == 0) 
                                    <div>
                                        <h5 class="text-center">
                                            <li class="breadcrumb-item active" aria-current="page">{{__('label.attributes_text')}}</li>
                                        </h5>    
                                    </div>
                                    @else
                                    <div class="table-responsive table">
                                        <table class="table table-striped text-center table-bordered" id="datatable">
                                            <thead>
                                                <tr style="background: #F9FAFF;">
                                                    
                                                    <th> {{__('label.icon')}} </th>
                                                    <th> {{__('label.name')}} </th>
                                                    <th> {{__('label.option_value')}} </th>
                                                   
                                                </tr>
                                            </thead>
                                          
                                            <tbody>
                                             
                                                @foreach($p_attributes as $value)
                                                <tr>
                                                    <td> <a href="{{ $value->attributes->icon ?? '#' }}" target="_blank" title="Watch">
                                                        <img src="{{ $value->attributes->icon ?? '#' }}" class="img-thumbnail" style="height:55px; width:55px">
                                                    </a>
                                                    </td>
                                              
                                                    
                                                    <td>{{ $value->attributes->title }}</td>
                                                
                                                
                                                    @if($value->field_type ==6)
                                                    <td>
                                                        <a href="{{ $value->option ?? '#' }}" target="_blank" title="Watch">
                                                            <img src="{{ $value->option ?? '#' }}" class="img-thumbnail" style="height:55px; width:55px">
                                                        </a>
                                                    </td>
                                                    @else
                                                    <td>{{ $value->option }}</td>
                                                    @endif
                                                   
                                                
                                                </tr>
                                                @endforeach
                                               
                                            </tbody>
                                         
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Third Tab (Product FAQ) -->
                            <div class="tab-pane fade" id="quantitydeliveryInfo" role="tabpanel" aria-labelledby="quantity-delivery-tab">
                                <div class="product-faq">
                                    @if (count($faq) == 0)
                                    <div>
                                        <h5 class="text-center">
                                            <li class="breadcrumb-item active" aria-current="page">{{__('label.faq_text')}}</li>
                                        </h5>
                                    </div>
                                    @else
                                    @foreach($faq as $value)
                                        <div class="faq-item-section">
                                            <div class="faq-item">
                                                <div class="faq-question">
                                                  
                                                    <h4>{{__('label.question')}} {{$loop->iteration}}: {{$value->question}}</h4>
                                                </div>
                                                <div class="faq-answer">
                                                    <p>{{$value->answer}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @endif
                                    
                                </div>
                            </div>

                            <!-- Fourth Tab (Product Reviews) -->
                            <div class="tab-pane fade" id="taxesstockInfo" role="tabpanel"
                                aria-labelledby="taxes-stock-tab">
                                <div class="product-reviews">
                                    @if (count($review) == 0)
                                        <div>
                                            <h5 class="text-center">
                                                <li class="breadcrumb-item active" aria-current="page">{{__('label.review_text')}}</li>
                                            </h5>
                                        </div>
                                    @else
                                        @foreach ($review as $value)
                                            <div class="review-card">
                                                <div class="review-header">
                                                    <div class="reviewer-info">
                                                        <img src="{{ $value->customer->image }}" alt="Reviewer"
                                                            class="reviewer-img">
                                                        <div>
                                                            <h4 class="reviewer-name">{{ $value->customer->full_name }}
                                                            </h4>
                                                            <p class="review-date">{{ $value->review_date }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="review-rating">
                                                        <div class="stars">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $value->rating)
                                                                    <!-- Filled star for rating -->
                                                                    <span class="star">&#9733;</span>
                                                                @else
                                                                    <!-- Inactive star for rating -->
                                                                    <span class="star inactive">&#9733;</span>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review-body">
                                                    <p>
                                                        {{ $value->review }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    
    <script>
        sidebar_down(300);
        document.addEventListener('DOMContentLoaded', function () {

            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const loadLessBtn = document.getElementById('loadLessBtn');

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function () {
                    const hiddenImages = document.querySelectorAll('.image-item.hidden');
                    hiddenImages.forEach(img => img.classList.remove('hidden'));
                
                    loadMoreBtn.style.display = 'none';
                    if (loadLessBtn) loadLessBtn.style.display = 'inline-block';
                });
            }

            if (loadLessBtn) {
                loadLessBtn.addEventListener('click', function () {
                    const allImages = document.querySelectorAll('.image-item');
                    allImages.forEach((img, index) => {
                        if (index >= 6) img.classList.add('hidden');
                    });
                
                    loadMoreBtn.style.display = 'inline-block';
                    loadLessBtn.style.display = 'none';
                });
            }

        });
      
    </script>
@endsection
