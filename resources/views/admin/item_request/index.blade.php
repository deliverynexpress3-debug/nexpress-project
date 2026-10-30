@extends('admin.layout.page-app')
@section('page_title', __('label.product_request'))
@section('tab_title', __('label.product_request'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.product_request')}}</h1>

            <div class="border-bottom row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.product_request')}}</li>
                    </ol>
                </div>
               
            </div>

            <!-- Search -->
            <div class="page-search mb-3">
                <div class="input-group" title="Search">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                    </div>
                    <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                </div>
                    
            </div>
            <div class="page-search mb-3">
                <div class="sorting w-50 mr-3">
                    <label>{{ __('label.sort_by') }}</label>
                    <select class="form-control" id="input_status_type">
                        <option value="all">{{__('label.all_type')}}</option>
                        <option value="1">{{__('label.physical')}}</option>
                        <option value="2">{{__('label.digital')}}</option>
                      
                       
                    </select>
                </div>
                <div class="sorting w-50 mr-3">
                    <select class="form-control" name="seller_id" id="input_seller_id">
                        <option value="all">{{ __('label.all_seller') }}</option>
                        @foreach ($seller as $key => $value)
                            <option value="{{ $value->id }}">
                                {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sorting w-50">
                    <select class="form-control" name="brand_id" id="input_brand_id">
                        <option value="all">{{ __('label.all_brand') }}</option>
                        @foreach ($brand as $key => $value)
                            <option value="{{ $value->id }}">
                                {{ $value->name }}
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
                            <th>{{__('label.product')}}</th>
                            <th>{{__('label.seller')}}</th>
                            <th>{{__('label.info')}}</th>
                            <th>{{__('label.price')}}</th>
                            <th>{{__('label.view')}}</th>
                            <th>{{__('label.action')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal fade" id="ApprovedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content decision-modal-content" id="approvedModal">
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close"> 
                            <span aria-hidden="true">&times;</span>
                        </button>  
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="decision-icon">
                                <img src="{{ asset('assets/imgs/right.png') }}" alt="Decision" class="decision-icon-img">
                            </div>
                            <h2 class="decision-success-text">{{__('label.approved_item_text')}}</h2>
                            <p class="modal-style" style="font: bold;">{{__('label.approved_item_description')}}</p>
        
                            <a href="javascript:void(0);" onclick="update_status(1)" class="badge badge-success mt-1">{{__('label.approved')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="RejectedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content decision-modal-content" id="rejectedModal">
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close"> 
                            <span aria-hidden="true">&times;</span>
                        </button>  
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="decision-icon">
                                <img src="{{ asset('assets/imgs/wrong.png') }}" alt="Decision" class="decision-icon-img">
                            </div>
                            <h2 class="decision-error-text">{{__('label.rejected_text')}}</h2>
                            <p class="modal-style" style="font: bold;">{{__('label.rejected_item_description')}}</p>
        
                            <a href="javascript:void(0);" onclick="update_status(0)" class="badge badge-danger mt-1">{{__('label.disapproved')}}</a>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
@endsection

@section('pagescript')

    <script>
        sidebar_down(350);
        $("#input_seller_id ,#input_brand_id").select2();
        
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.request-item.index') }}",
                    data: function(d) {
                        d.input_status_type = $('#input_status_type').val();
                        d.input_seller_id = $('#input_seller_id').val();
                        d.input_brand_id = $('#input_brand_id').val();
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                   
                    {
                        data: 'portrait_img',
                        name: 'portrait_img',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let itemName = row.name ? row.name : "-";
                            return `
                                <div class="d-flex align-items-center">
                                    <a href="${data}" target="_blank">
                                        <img src="${data}" class="img-thumbnail" style="height:55px; width:60px; object-fit:cover; border-radius: 8px;">
                                    </a>
                                    <span class="ml-3 font-weight-bold" style="white-space: nowrap;">${itemName}</span>
                                </div>
                                `;
                        }
                    },
                    
					{
						data: 'seller.image',
						name: 'seller.image',
                        render: function(data, type, row) {
                            let Uname = row.seller.user_name ? row.seller.user_name : "-";
                            let Fname = row.seller.full_name ? row.seller.full_name : "-";
                            return `
                                <div class="d-flex align-items-center">
                                     <a href="${data}" target="_blank">
                                        <img src="${data}" class="rounded-circle" style="height:60px; width:60px; object-fit:cover; border-radius: 8px;">
                                    </a>
                                    <div class="ml-3">
                                        <span style="white-space: nowrap; display: block; font-weight: bold;">${Uname}</span>
                                        <span style="white-space: nowrap; display: block; color: gray;">${Fname}</span>
                                    </div>
                                </div>
                                `;
                        }
						
					},
                    {
                        data: 'info',
                        name: 'info',
                        render: function(data, type, row) {
                            return `<div style="text-align: left;">${row.category?.name || ''}<br><span style="font-size: 14px; font-weight: 600;">${row.brand?.name || ''}</span>`;
                        }
                    },
                    
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                   
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                       
                    },
                  
                    { data: 'action', name: 'action', width: '10%', orderable: false, searchable: false },
                ],
                
            });
            $('#input_status_type, #input_seller_id ,#input_brand_id').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });
        $(document).on("click", ".approvedModal", function() {
            var id = $(this).data('id');
            $(".modal-body #edit_id").val(id);

        });
        $(document).on("click", ".rejectedModal", function() {
            var id = $(this).data('id');
            $(".modal-body #edit_id").val(id);

        });
        function update_status(status) {
        
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var Edit_Id = $("#edit_id").val();
           
                var url = '{{ route("admin.request-item.show" , ":id") }}';
                url = url.replace(':id', Edit_Id);
                
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    url: url,
                    data: {
                        status: status
                    }, 
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, '', '{{ route("admin.request-item.index") }}');
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