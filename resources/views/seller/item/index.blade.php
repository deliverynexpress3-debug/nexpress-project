@extends('seller.layout.page-app')
@section('page_title', __('label.product'))
@section('tab_title', __('label.product'))
@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.product') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('label.dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> {{ __('label.product') }}</li>
                       
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('seller.item.create') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{ __('label.add_product') }}</a>
                </div>
            </div>
            <!-- Notes -->
            <div class="alert alert-secondary">
               <i class="fa-solid fa-bullhorn fa-xl"></i>
               <strong class="mx-2">{{__('label.notes')}} :</strong>{{__('label.items_notes')}}
            </div>
            <form class="mb-3" action="{{ route('seller.item.index') }}" method="GET">
                <div class="page-search">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i>
                            </span>
                        </div>
                        <input type="text" name="input_search"
                            value="@if(isset($_GET['input_search'])){{ $_GET['input_search']}}@endif"
                            class="form-control" placeholder="{{ __('label.search')}}" aria-label="Search"
                            aria-describedby="basic-addon1" autocomplete="off">
                    </div>


                    <div class="">
                        <button class="btn btn-default w-100" type="submit">{{ __('label.search') }}</button>
                    </div>
                </div>
                <div class="page-search mb-4">
                    <div class="sorting mr-3 w-25">
                        <label>{{__('label.sort_by')}}</label>
                        <select class="form-control" name="input_status" id="input_status">
                            <option value="all" {{ isset($_GET['input_status']) && $_GET['input_status'] == 'all' ? 'selected' : '' }}>{{__('label.all_type')}}</option>
                            <option value="1" {{ isset($_GET['input_status']) && $_GET['input_status'] == '1' ? 'selected' : '' }}>{{__('label.physical')}}</option>
                            <option value="2" {{ isset($_GET['input_status']) && $_GET['input_status'] == '2' ? 'selected' : '' }}>{{__('label.digital')}}</option>
                            
                        </select>
                    </div>
                    
                    <div class="sorting mr-3 w-50" >
                        <select class="form-control" name="input_brand_id" id="input_brand_id">
                            <option value="all">{{__('label.all_brand')}}</option>
                            @foreach ($brand as $key => $value)
                                <option value="{{ $value->id }}" {{ isset($_GET['input_brand_id']) && $_GET['input_brand_id'] == $value->id ? 'selected' : '' }}>
                                    {{ $value->name }}
                                </option>
                            @endforeach
                           
                        </select>
                    </div>
                    
                   
                </div>

            </form>
    
            
            <div class="profile-products-grid">
                @foreach ($data as $key => $value)
                <div class="profile-product-card">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#imageModal-{{$value->id}}">
                        <img src="{{$value->portrait_img}}" alt="{{$value->name}}" />
                    </a>
                    
                    <div class="action-buttons">
                        <a class="detail-btn btn me-2" href="{{route('seller.item.detail', [$value->id])}}" title="{{__('label.detail')}}">
                            <i class="fa-solid fa-eye"></i>
                           
                        </a>
                        <a class="edit-btn btn me-2" href="{{route('seller.item.edit', [$value->id])}}" title="{{__('label.edit')}}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a class="delete-btn btn" href="{{route('seller.item.show', [$value->id])}}" title="{{__('label.delete')}}" onclick="return confirm('{{__('label.delete_product')}}')">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                        
                    </div>
                    <p class="profile-product-name">{{$value->name}}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        @if($value->is_approved ==1)
                            @if($value->status == 1)
                                <button class="btn btn-sm" id="{{$value->id}}" onclick="change_status('{{ $value->id }}', '{{ $value->status }}')" style="background:#058f00; color:#fff; font-weight:bold; border:none">{{__('label.available')}}</button>
                            @elseif($value->status == 0)
                                <button class="btn btn-sm" id="{{$value->id}}" onclick="change_status('{{ $value->id }}', '{{ $value->status }}')" style="background:#e3000b; color:#fff; font-weight:bold; border:none">{{__('label.unavailable')}}</button>
                            @endif
                        @else
                            @if($value->status == 1)
                                <button class="btn btn-sm" id="{{$value->id}}" style="background:#058f00; color:#fff; font-weight:bold; border:none">{{__('label.available')}}</button>
                            @elseif($value->status == 0)
                                <button class="btn btn-sm" id="{{$value->id}}" style="background:#e3000b; color:#fff; font-weight:bold; border:none">{{__('label.unavailable')}}</button>
                            @endif
                        @endif
                           
                        <p class="profile-product-price mb-0">${{$value->price}}</p>
                    </div>
                
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
               <div> Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries </div>
                <div class="pb-5"> {{ $data->links() }} </div>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
<script>
    
    $('#input_industries_id, #input_brand_id').select2();

    function change_status(id, status) {
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if (Demo_Mode == 1) {

            $("#dvloader").show();
            var url = "{{route('seller.item.change_status', '')}}" + "/" + id;
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
                            $('#' + id).text("{{__('label.available')}}");
                            $('#' + id).css({
                                "background": "#058f00",
                                "color": "white",
                                "font-weight": "bold",
                                "border": "none"
                            });
                        } else {
                            $('#' + id).text("{{__('label.unavailable')}}");
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
        const cards = document.querySelectorAll('.profile-product-card');
        
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
