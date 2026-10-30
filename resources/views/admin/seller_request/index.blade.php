@extends('admin.layout.page-app')
@section('page_title', __('label.seller_request'))
@section('tab_title', __('label.seller_request'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.seller_request')}}</h1>

            <div class="border-bottom row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.seller_request')}}</li>
                    </ol>
                </div>
               
            </div>
                
            <div class="table-responsive mt-3">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th>{{__('label.#')}}</th>
                            <th>{{__('label.seller')}}</th>
                            <th>{{__('label.store')}}</th>
                            <th>{{__('label.bank_name')}}</th>
                            <th>{{__('label.account_holder')}}</th>
                            <th>{{__('label.account_no')}}</th>
                            <th>{{__('label.bank_code')}}</th>
                            <th>{{__('label.bank_proof')}}</th>
                            <th>{{__('label.date')}}</th>
                            <th>{{__('label.action')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        
            <div class="modal fade" id="decisionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content decision-modal-content" id="decisionModalContent">
                        <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close"> 
                            <span aria-hidden="true">&times;</span>
                        </button>  
                        <div class="decision-modal-body">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="icon">
                                <img src="" alt="Decision" id="modal_icon" class="decision-icon-img">
                            </div>
                            <h2 id="modal_title"></h2>
                            <p class="modal-style" style="font: bold;" id="modal_message"></p>
            
                            <!-- Button that triggers JavaScript action based on context -->
                            <a href="javascript:void(0);" id="modal_button" class="badge mt-1"></a>
                        </div>
                    </div>
                </div>
            </div>
         
        </div>
    </div>
@endsection

@section('pagescript')
<script>
    $(document).ready(function() {
        var table = $('#datatable').DataTable({
            ...dataTableDefaults,
            ajax: {
                url: "{{ route('admin.become-seller.index') }}",
                data: function(d) {
                    d.input_search = $('#input_search').val();
                    d.input_type = $('#input_type').val();
                },
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'customer.image',
                    name: 'customer.image',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        let Uname = row.customer.user_name ? row.customer.user_name : "-";
                        let Fname = row.customer.full_name ? row.customer.full_name : "-";
                        return `
                            <div class="d-flex align-items-center">
                                <a href="${data}" target="_blank">
                                    <img src="${data}" class="rounded-circle" style="height:55px; width:55px; object-fit:cover; border-radius: 8px;">
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
                    data: 'store_logo',
                    name: 'store_logo',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, full, meta) {
                        
                        let logo = row.store_logo ? "<a href='" + row.store_logo + "' target='_blank'><img src='" + row.store_logo + "' class='img-thumbnail mr-2' style='height:55px; width:55px'></a>" : '';
                        let name = row.store_name ? row.store_name : 'N/A';
                        return "<div class='d-flex align-items-center'>" + logo + "<span>" + name + "</span></div>";
                    
                    },
                },
                {
                    data: 'bank_name',
                    name: 'bank_name',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return data;
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: 'bank_holder_name',
                    name: 'bank_holder_name',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return data;
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: 'account_no',
                    name: 'account_no',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return data;
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: 'ifsc_code',
                    name: 'ifsc_code',
                    render: function(data, type, full, meta) {
                        if (data) {
                            return data;
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    data: 'bank_proof',
                    name: 'bank_proof',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        return "<a href='" + data +
                            "' target='_blank' title='Watch'><img src='" + data +
                            "' class='img-thumbnail' style='height:55px; width:55px'></a>";
                    },
                },
                {
                    data: 'register_date',
                    name: 'register_date'
                },
                
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        $('#input_type').change(function() {
            table.draw();
        });
        $('#input_search').keyup(function() {
            table.draw();
        });

    });

    $(document).on("click", ".openModal", function() {
        var id = $(this).data('id');
        var action = $(this).data('action');
       
        $(".decision-modal-body #edit_id").val(id);
        $("#modal_id").val(id);

        if (action === "approved") {
            $("#modal_icon").attr("src", "{{ asset('assets/imgs/right.png') }}");
            $("#modal_title").text("{{__('label.approved_text')}}").removeClass("decision-error-text").addClass("decision-success-text");
            $("#modal_message").text("{{__('label.approved_description')}}");
            $("#modal_button").text("{{__('label.approved')}}").removeClass("badge-danger").addClass("badge-success");
            $("#modal_button").attr("onclick", "change_status(1);"); 
        } else if (action === "rejected") {
            $("#modal_icon").attr("src", "{{ asset('assets/imgs/wrong.png') }}");
            $("#modal_title").text("{{__('label.rejected_text')}}").removeClass("decision-success-text").addClass("decision-error-text");
            $("#modal_message").text("{{__('label.rejected_description')}}");
            $("#modal_button").text("{{__('label.rejected')}}").removeClass("badge-success").addClass("badge-danger");
            $("#modal_button").attr("onclick", "change_status(0);"); // Set the button to trigger the rejected action
        }

    });
       
    function change_status(status) {
        
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if (Demo_Mode == 1) {

            $("#dvloader").show();
            var Edit_Id = $("#edit_id").val();
            
            var url = '{{ route("admin.become-seller.show" , ":id") }}';
            url = url.replace(':id', Edit_Id);
                
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: url,
                data:{
                    status:status
                },
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, '', '{{ route("admin.become-seller.index") }}');
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