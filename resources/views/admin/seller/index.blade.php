@extends('admin.layout.page-app')
@section('page_title', __('label.seller'))
@section('tab_title', __('label.seller'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{ __('label.seller') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('label.seller') }}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.seller.create') }}" class="btn btn-default mw-120"
                        style="margin-top:-14px">{{ __('label.add_seller') }}</a>
                </div>
            </div>
            
            <form action="{{ route('admin.seller.index')}}" method="GET">
                <div class="page-search">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i>
                            </span>
                        </div>
                        <input type="text" name="input_search" value="@if(isset($_GET['input_search'])){{$_GET['input_search']}}@endif" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1" autocomplete="off">
                    </div>
                    <div class="sorting mr-3 w-50">
                        <label>{{__('label.sort_by')}}</label>
                        <select class="form-control" id="input_type" name="input_type">
                            <option value="all" {{ isset($_GET['input_type']) && $_GET['input_type'] == 'all' ? 'selected' : '' }}>
                                {{__('label.all')}}
                            </option>
                            <option value="today" {{ isset($_GET['input_type']) && $_GET['input_type'] == 'today' ? 'selected' : '' }}>
                                {{__('label.today')}}
                            </option>
                            <option value="month" {{ isset($_GET['input_type']) && $_GET['input_type'] == 'month' ? 'selected' : '' }}>
                                {{__('label.month')}}
                            </option>
                            <option value="year" {{ isset($_GET['input_type']) && $_GET['input_type'] == 'year' ? 'selected' : '' }}>
                                {{__('label.year')}}
                            </option>
                        </select>
                    </div>
                    
                    <div class="sorting w-50">
                        <select class="form-control" id="input_login_type" name="input_login_type">
                            <option value="all" {{ isset($_GET['input_login_type']) && $_GET['input_login_type'] == 'all' ? 'selected' : '' }}>
                                {{__('label.all_type')}}
                            </option>
                            <option value="1" {{ isset($_GET['input_login_type']) && $_GET['input_login_type'] == '1' ? 'selected' : '' }}>
                                {{__('label.otp')}}
                            </option>
                            <option value="2" {{ isset($_GET['input_login_type']) && $_GET['input_login_type'] == '2' ? 'selected' : '' }}>
                                {{__('label.google')}}
                            </option>
                            <option value="3" {{ isset($_GET['input_login_type']) && $_GET['input_login_type'] == '3' ? 'selected' : '' }}>
                                {{__('label.apple')}}
                            </option>
                            <option value="4" {{ isset($_GET['input_login_type']) && $_GET['input_login_type'] == '4' ? 'selected' : '' }}>
                                {{__('label.normal')}}
                            </option>
                        </select>
                    </div>
                    
                    <div class="ml-3">
                        <button class="btn btn-default w-100" type="submit">{{__('label.SEARCH')}}</button>
                    </div>
                </div>
            </form> 
            

            <div class="seller-container mt-45">
                @foreach ($data as $key => $value)
                <div class="seller-card">
                    <div class="seller-card-header">
                        <img src="{{$value->image}}" alt="Leslie Alexander">
                    </div>

                    <div class="seller-card-body">
                        <h3>{{$value->full_name}}</h3>
                        <p>{{__('label.total_sales')}}: {{$value->total_sales}}</p>
                        @php
                            $rating = round($value->avg_rating); // Round rating to nearest whole number
                        @endphp

                        <p class="rating">{{__('label.rating')}}: 
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $rating)
                                    ★ <!-- Full Star -->
                                @else
                                    ☆ <!-- Empty Star -->
                                @endif
                            @endfor
                            ({{ number_format($value->avg_rating, 1) }}) <!-- Show exact rating (e.g., 3.8) -->
                        </p>

                        <div class="d-flex justify-content-between mb-0">
                            @if($value->status == 1)
                            <button class="btn btn-sm" id="{{$value->id}}" onclick="change_status('{{ $value->id }}', '{{ $value->status }}')" style="background:#058f00; color:#fff; font-weight:bold; border:none">{{__('label.active')}}</button>
                            @elseif($value->status == 0)
                            <button class="btn btn-sm" id="{{$value->id}}" onclick="change_status('{{ $value->id }}', '{{ $value->status }}')" style="background:#e3000b; color:#fff; font-weight:bold; border:none">{{__('label.inactive')}}</button>
                            @endif
                           
                            <ul class="list-inline icon-list d-flex justify-content-end mb-2"
                                aria-labelledby="dropdownMenuLink">
                               
                                <li class="list-inline-item">
                                    <a class="btn" href="{{route('admin.seller.edit', [$value->id])}}">
                                        <i class="fa-solid fa-pen-to-square fa-xl dot-icon" style="color: #4e45b8;"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item mr-2">
                                    <a class="btn" href="{{route('admin.seller.show', [$value->id])}}"
                                        onclick="return confirm('{{__('label.delete_seller')}}')">
                                        <i class="fa-solid fa-trash-can fa-xl dot-icon" style="color: #4e45b8;"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <a href="{{route('admin.seller.profile', [$value->id])}}">
                            <button style="width: 100%;">{{__('label.view_profile')}}</button>
                        </a>
                        
                    </div>
                </div>
                @endforeach
                   
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div> Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries </div>
                <div class="pb-5"> {{ $data->links() }} </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
<script>
    function change_status(id, status) {
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if (Demo_Mode == 1) {

            $("#dvloader").show();
            var url = "{{route('admin.seller.change_status', '')}}" + "/" + id;
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: id,
                success: function(resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) {
                        if (resp.Status == 1) {
                            $('#' + id).text("{{__('label.active')}}");
                            $('#' + id).css({
                                "background": "#058f00",
                                "color": "white",
                                "font-weight": "bold",
                                "border": "none"
                            });
                        } else {
                            $('#' + id).text("{{__('label.inactive')}}");
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
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.seller-card');

        cards.forEach(card => {
            card.addEventListener('click', function () {
                // Remove 'active' class from all cards
                cards.forEach(c => c.classList.remove('active'));

                // Add 'active' class to the clicked card
                this.classList.add('active');
            });
        });
    });
</script>

  
@endsection
