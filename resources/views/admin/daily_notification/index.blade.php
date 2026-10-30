@extends('admin.layout.page-app')
@section('page_title',__('label.daily_notification'))
@section('tab_title', __('label.daily_notification'))
@section('content')
@include('admin.layout.sidebar')

<div class="right-content">
    @include('admin.layout.header')

    <div class="body-content">
        <!-- mobile title -->
        <h1 class="page-title-sm"> {{__('label.daily_notification')}} </h1>

        <div class="border-bottom row mb-3">
            <div class="col-sm-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__('label.daily_notification')}}</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 ">
                <div class="col-12 cart-bg">
                    <ul class="nav nav-pills custom-tabs" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-morning-tab" data-toggle="pill" href="#pills-morning" role="tab" aria-controls="pills-morning" aria-selected="true">{{__('label.morning')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-evening-tab" data-toggle="pill" href="#pills-evening" role="tab" aria-controls="pills-evening" aria-selected="false">{{__('label.evening')}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-morning" role="tabpanel" aria-labelledby="pills-morning-tab">
                <!-- Search -->
                <div class="page-search mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                </div>

            
                <div class="table-responsive">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr class="table-bg">
                                <th> {{__('label.#')}} </th>
                                <th> {{__('label.title')}} </th>
                                <th> {{__('label.message')}} </th>
                                <th> {{__('label.action')}} </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-evening" role="tabpanel" aria-labelledby="pills-evening-tab">
                <!-- Search -->
                <div class="page-search mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search_evening" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                </div>

                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable_evening">
                        <thead>
                            <tr class="table-bg">
                                <th> {{__('label.#')}} </th>
                                <th> {{__('label.title')}} </th>
                                <th> {{__('label.message')}} </th>
                                <th> {{__('label.action')}} </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Edit Model -->
        <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_daily_notification')}}</h5>
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="edit_daily_notitification">
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> {{__('label.title')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="edit_title" class="form-control" placeholder="{{__('label.title_here')}}">
                                    </div>
                                    <div class="form-group">
                                        <label> {{__('label.message')}}</label>
                                        <textarea class="form-control" name="message" id="edit_message" placeholder="{{__('label.message_here')}}"></textarea>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" id="edit_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default mw-120" onclick="update_daily_notification()">{{__('label.update')}}</button>
                            <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
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
	sidebar_down(700);

    $(document).ready(function() {

        var table = $('#datatable').DataTable({
            dom: "<'top'f>rt<'row'<'col-1'i><'col-2'l><'col-9'p>>",
            searching: false,
            responsive: true,
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [
                [10, 50, 100, -1],
                [10, 50, 100, 'All']
            ],
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-chevron-left'></i>",
                    next: "<i class='fa-solid fa-chevron-right'></i>"
                }
            },
            ajax: {
                url: '{{route("admin.daily_notification.index")}}',
                data: function(d) {
                    d.input_search = $('#input_search').val();
                    d.type = 1;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                },
                {
                    data: 'title',
                    name: 'title',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return data;
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: 'message',
                    name: 'message',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return data;
                        } else {
                            return "-";
                        }
                    }
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

        var table_evening = $('#datatable_evening').DataTable({
            dom: "<'top'f>rt<'row'<'col-1'i><'col-2'l><'col-9'p>>",
            searching: false,
            responsive: true,
            processing: true,
            autoWidth: false,
            serverSide: true,
            lengthMenu: [
                [10, 50, 100, -1],
                [10, 50, 100, 'All']
            ],
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-chevron-left'></i>",
                    next: "<i class='fa-solid fa-chevron-right'></i>"
                }
            },
            ajax: {
                url: '{{route("admin.daily_notification.index")}}',
                data: function(d) {
                    d.input_search = $('#input_search_evening').val();
                    d.type = 2;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                },
                {
                    data: 'title',
                    name: 'title',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return data;
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: 'message',
                    name: 'message',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return data;
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });
        $('#input_search_evening').keyup(function() {
            table_evening.draw();
        });
    });

    $(document).on("click", ".edit_daily_notification", function() {

        var id = $(this).data('id');
        var title = $(this).data('title');
        var message = $(this).data('message');

        $(".modal-body #edit_id").val(id);
        $(".modal-body #edit_title").val(title);
        $(".modal-body #edit_message").val(message);
    });

    function update_daily_notification() {

        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if (Demo_Mode == 1) {

            $("#dvloader").show();
            var formData = new FormData($("#edit_daily_notitification")[0]);

            var Edit_Id = $("#edit_id").val();
            var url = '{{ route("admin.daily_notification.update", ":id") }}';
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

                    if (resp.status == 200) {

                        $('#EditModel').modal('toggle');
                        get_responce_message(resp, 'edit_daily_notitification', '{{ route("admin.daily_notification.index") }}');
                    } else {

                        get_responce_message(resp);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        } else {
            toastr.error('{{__("label.you_have_no_right_to_add_edit_and_delete")}}');
        }
    }
</script>
@endsection