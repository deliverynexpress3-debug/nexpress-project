@extends('admin.layout.page-app')
@section('page_title', __('label.return_reason'))
@section('tab_title', __('label.return_reason'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{ __('label.return_reason') }} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('label.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ __('label.return_reason') }}
                        </li>
                    </ol>
                </div>

            </div>

            <!-- Add Return Reason -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{ __('label.add_return_reason') }}</h5>
                <div class="card-body">
                    <form id="return_reason" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">

                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>{{__('label.type')}}</label>
                                            <select class="form-control" name="type" id="type">
                                                <option value="1">{{__('label.return_order')}}</option>
                                                <option value="2">{{__('label.cancel_order')}}</option>
                                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> {{ __('label.reason') }}<span class="text-danger">*</span></label>
                                            <input type="text" name="reason" class="form-control" placeholder="{{ __('label.enter_reason') }}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="time_period">
                                        <div class="form-group">
                                            <label> {{ __('label.return_period') }}<span
                                                    class="text-danger">*</span></label>
                                                
                                                <div class="input-group mb-2">
                                                    <input type="number" name="return_period" class="form-control" placeholder="{{__('label.enter_day')}}">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">{{__('label.day_return')}}</div>
                                                    </div>
                                                   
                                                </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>

                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120"
                                onclick="save_return_reason()">{{ __('label.save') }}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search && Table -->
            <div class="card custom-border-card mt-3">
                <div class="page-search mb-3">
                    <div class="input-group" title="Search">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i
                                    class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control"
                            placeholder="{{ __('label.search') }}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                   
                </div>
                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th> {{ __('label.#') }} </th>
                                <th> {{ __('label.type') }} </th>
                                <th> {{ __('label.reason') }} </th>
                                <th> {{ __('label.status') }} </th>
                                <th> {{ __('label.action') }} </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- EditModel -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('label.edit_return_reason') }}</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_return_reason" autocomplete="off">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{__('label.type')}}</label>
                                                    <select class="form-control" name="type" id="edit_type">
                                                        <option value="1">{{__('label.return_order')}}</option>
                                                        <option value="2">{{__('label.cancel_order')}}</option>
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="edit_time_period">
                                                <div class="form-group">
                                                    <label> {{ __('label.return_period') }}<span class="text-danger">*</span></label>
                                                        
                                                        <div class="input-group mb-2">
                                                            <input type="number" name="return_period" id="edit_return_period" class="form-control" placeholder="{{__('label.enter_day')}}">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">{{__('label.day_return')}}</div>
                                                            </div>
                                                           
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ __('label.reason') }}<span class="text-danger">*</span></label>
                                                    <input type="text" name="reason" id="edit_reason" class="form-control" placeholder="{{ __('label.enter_reason') }}">
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="edit_id">
                               
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_return_reason()">{{ __('label.update') }}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{ __('label.close') }}</button>
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
    <script>
        sidebar_down(550);
        $('#type').on('change', function () {

            var Type = $(this).val();
            if(Type == 1){
                $('#time_period').show();
            } else if(Type ==2 ) {
                $('#time_period').hide();
            }
        });
        
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.reason.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                       
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: function (data) {
                            if (data == 1) {
                                return '<strong>Order Return</strong>';
                            } else if (data == 2) {
                                return '<strong>Order Cancel</strong>';
                            } else if (data == 3) {
                                return '<strong>Calling Customer</strong>';
                            } else {
                                return '-';
                            }
                        }
                    }, 
                    {
                        data: 'reason',
                        name: 'reason',
                        render: function (data, type, full, meta) {
                        
                            let html = '';
                        
                            // Reason (Top)
                            if (data) {
                                html += `<div>${data}</div>`;
                            } else {
                                html += `<div>-</div>`;
                            }
                        
                            // Return Period (Bottom)
                            if (full.type == 1 && full.return_period) {
                                html += `
                                    <div class="" style="font-size:14px;">
                                        <strong>{{ __('label.return_period') }}</strong>:
                                        (${full.return_period} {{ __('label.day_return') }})
                                    </div>
                                `;
                            }
                        
                            return html;
                        }
                    },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
           
            $('#input_search').keyup(function() {
                table.draw();
            });

        });

        function save_return_reason() {
            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {
                $("#dvloader").show();
                var formData = new FormData($("#return_reason")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.reason.store') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'return_reason', '{{ route('admin.reason.index') }}');
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

        $(document).on("click", ".edit_return_reason", function() {
            var id = $(this).data('id');
            var reason = $(this).data('reason');
            var return_period = $(this).data('return_period');
            var type = $(this).data('type');
           
            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_reason").val(reason);
            $(".modal-body #edit_return_period").val(return_period);
            $('.modal-body #edit_type').val(type).attr("selected", "selected");
   
            if(type ==1){
                $('#edit_time_period').show();
            }else if(type ==2) {
                $('#edit_time_period').hide();
            }

            $('#edit_type').on('change', function () {

                var Type = $(this).val();
                if(Type == 1){
                    $('#edit_time_period').show();
                } else if(Type ==2) {
                    $('#edit_time_period').hide();
                }
            });
        });

        function update_return_reason() {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var formData = new FormData($("#edit_return_reason")[0]);

                var Edit_Id = $("#edit_id").val();
                var url = '{{ route('admin.reason.update', ':id') }}';
                url = url.replace(':id', Edit_Id);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        $('#EditModel').modal('toggle');
                        get_responce_message(resp, 'edit_return_reason', "{{ route('admin.reason.index') }}");
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

        function change_status(id) {

            var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if (Demo_Mode == 1) {

                $("#dvloader").show();
                var url = `{{ route('admin.reason.show', '') }}/${id}`;

                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(resp) {
                        $("#dvloader").hide();

                        if (resp.status == 200) {
                            if (resp.status_code == 1) {
                                $('#' + id).text('{{__("label.show")}}').removeClass('hide-btn').addClass('show-btn');
                            } else {
                                $('#' + id).text('{{__("label.hide")}}').removeClass('show-btn').addClass('hide-btn');
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
