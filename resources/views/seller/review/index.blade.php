@extends('seller.layout.page-app')
@section('page_title', __('label.reviews'))
@section('tab_title', __('label.reviews'))
@section('content')
    @include('seller.layout.sidebar')

    <div class="right-content">
        @include('seller.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.reviews') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('label.dashboard') }}</a>
                       
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.reviews') }}
                        </li>
                    </ol>
                </div>
             
            </div>

            <div class="page-search mb-3 mt-3">
                <div class="input-group" title="Search">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                    </div>
                    <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                </div>
                <div class="sorting mr-2 w-50">
                    <label>{{__('label.sort_by')}}</label>
                    <select class="form-control" name="customer_id" id="input_customer_id">
                        <option value="all">{{__('label.all_customer')}}</option>
                        @foreach ($customer as $key => $value)
                            <option value="{{$value->id}}">
                                {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
               
            </div>

            <div class="table-responsive">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th>{{__('label.#')}}</th>
                            <th>{{__('label.customer')}}</th>
                            <th>{{__('label.reviews')}}</th>
                            <th>{{__('label.rating_out_of_5')}}</th>
                            <th>{{__('label.action')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        // Sidebar Scroll Down
        let sidebarHeight = $('.sidebar')[0].scrollHeight;
        sidebar_down(sidebarHeight);
        
        $("#input_customer_id").select2();

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('seller.review.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                        d.input_customer_id = $('#input_customer_id').val();
                       
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    
                    {
                        data: 'customer',
                        name: 'customer',
                        render: function(data) {
                            return data ? data.full_name : "-";
                        }
                    },
                   
                    {
						data: 'review',
						name: 'review',
						render: function(data) {
                            return data ? data : "-" ;
                        }
					},
                    {
                        data: 'rating',
                        name: 'rating',
                        render: function(data) {
                            return data ? '<b class="h5">'+data+'</b>' +'<i class="fa-solid fa-star ml-2 text-warning fa-lg"></i>' : "-";
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_customer_id').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });
        
        function review_change_status(id) {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var url = "{{route('seller.review.show', '')}}" + "/" + id;
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
                                if (resp.status_code == 1) {
                                    $('#' + id).text('{{ __('label.show') }}').removeClass('status-inactive')
                                        .addClass('status-active');
                                } else {
                                    $('#' + id).text('{{ __('label.hide') }}').removeClass('status-active')
                                        .addClass('status-inactive');
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
       
    </script>
@endsection
