@extends('admin.layout.page-app')
@section('page_title', __('label.deliveryboy_request'))
@section('tab_title', __('label.deliveryboy_request'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')
        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.deliveryboy_request')}}</h1>

            <div class="border-bottom row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.deliveryboy_request')}}</li>
                    </ol>
                </div>
               
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th>{{__('label.#')}}</th>  
                            <th>{{__('label.image')}}</th>
                            <th>{{__('label.user_name')}}</th>
                            <th>{{__('label.full_name')}}</th>
                            <th>{{__('label.email')}}</th>
                            <th>{{__('label.mobile')}}</th>
                            <th>{{__('label.name')}}</th>
                            <th>{{__('label.contact')}}</th>
                            <th>{{__('label.register_date')}}</th>
                            <th>{{__('label.type')}}</th>
                            <th>{{__('label.login_type_opt_google_apple_normal')}}</th>
                            <th>{{__('label.action')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        
            <!-- EditModel -->
            <div class="modal fade" id="ApprovedModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.approved_request') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="approved_request" autocomplete="off">
                            <div class="modal-body">
                              <div class="form-row">
                                  <div class="col-md-12">
                                      <div class="form-row">
                                         
                                          <div class="col-md-12">
                                              <div class="form-group">
                                                  <label>{{__('label.serviceable_zones')}}<span class="text-danger">*</span></label>
                                                  <select class="form-control" style="width:100%!important;" name="zone_id[]" multiple id="edit_zone_id">
                                                      @foreach ($zone as $key => $value)
                                                      <option value="{{ $value->id }}">
                                                          {{ $value->zone_name }}
                                                      </option>
                                                      @endforeach
                                                  </select>
                                              </div>
                                          </div>
                                          
                                      </div>
                                  </div>
                                 
                              </div>
                                <input type="hidden" name="id" id="edit_id">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120"
                                    onclick="approved_status()">{{ __('label.approved') }}</button>
                                <button type="button" class="btn btn-cancel mw-120"
                                    data-dismiss="modal">{{ __('label.close') }}</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
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
    sidebar_down(700);
    $("#edit_zone_id").select2({placeholder: "{{__('label.select_zone')}}"});

    $(document).ready(function() {
        var table = $('#datatable').DataTable({
            ...dataTableDefaults,
            ajax: {
                url: "{{ route('admin.request-deliveryboy.index') }}",
                
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                
                {
						data: 'image',
						name: 'image',
						orderable: false,
						searchable: false,
						render: function(data) {
                            return `<a href='${data}' target='_blank'>
                                        <img src='${data}' class='rounded-circle' style='height:60px; width:60px'>
                                    </a>`;
						},
					},
                    {
						data: 'user_name',
						name: 'user_name',
                        orderable: false,
                        searchable: false,
                        visible: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
						
					},
					{
						data: 'full_name',
						name: 'full_name',
                        orderable: false,
                        searchable: false,
                        visible: false,
						render: function(data) {
                            return data ? data : "-";
                        }
					},
                    {
                        data: 'email',
                        name: 'email',
                        orderable: false,
                        searchable: false,
                        visible: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'mobile_number',
                        name: 'mobile_number',
                        orderable: false,
                        searchable: false,
                        visible: false,
                        render: function(data, type, full) {
                            return data ? (full.country_code ? `${full.country_code} ${data}` : data) : "-";
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `<div style="text-align: center;">${row.full_name || ''} <br><span style="font-size: 14px; font-weight: 600;">${row.user_name || ''}</span>`;
                        }
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(data, type, row) {
                            return `<div style="text-align: center;"> ${row.country_code || ''} ${row.mobile_number || ''}<br><span style="font-size: 14px; font-weight: 600;">${row.email || ''}</span>`;
                        }
                    },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'type',
                    name: 'type',
                    render: function(data) {
                        if (data == 1) {
                            return "<i class='fa-solid fa-mobile-screen-button fa-3x'></i>";
                        } else if (data == 2) {
                            return "<i class='fa-brands fa-google fa-3x'></i>";
                        } else if (data == 3) {
                            return "<i class='fa-brands fa-apple fa-3x'></i>";
                        } else if (data == 4) {
                            return "<i class='fa-solid fa-lock fa-3x'></i>";
                        } else {
                            return "-";
                        }
                    }
                    },
                    {
                        data: 'type',
                        name: 'Login Type',
                        orderable: false,
                        searchable: false,
                        visible: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    
    });

    $(document).on('click', '.approvedModal', function () {
        let id = $(this).data('id');
        $('#edit_id').val(id);
    });

    function approved_status() {

        let id = $('#edit_id').val();
        let zone_id = $('#edit_zone_id').val(); // multiple zones
        let status = 1; // Approved


        $("#dvloader").show();

        $.ajax({
            type: "GET",
            url: "{{ route('admin.request-deliveryboy.show','') }}/" + id,
            data: {
                id: id,
                status: status,
                zone_id: zone_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (resp) {
                $("#dvloader").hide();
                $('#ApprovedModal').modal('hide');
                get_responce_message(resp, '', '{{ route("admin.request-deliveryboy.index") }}');
            },
            error: function (xhr) {
                $("#dvloader").hide();
                toastr.error(errorThrown, textStatus);
            }
        });
    }

    function change_status(id, status) {

        var Demo_Mode = '<?php echo Demo_Mode(); ?>';

        if (Demo_Mode == 1) {
        
            $("#dvloader").show();
        
            $.ajax({
                type: "GET",
                url: "{{ route('admin.request-deliveryboy.show','') }}/" + id,
                data: {
                    id: id,
                    status: status
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, '', '{{ route("admin.request-deliveryboy.index") }}');
                },
                error: function (xhr) {
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