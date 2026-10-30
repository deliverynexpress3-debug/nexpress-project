@extends('admin.layout.page-app')
@section('page_title', __('label.notification_configuration'))
@section('tab_title', __('label.notification_configuration'))
@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">

            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.notification_configuration')}}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.notification_configuration')}}</li>
                    </ol>
                </div>
                
            </div>
            <div class="col-8 mb-2">
                <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" class="custom-control-input" id="notificationToggle" {{ $main_status == 1 ? 'checked' : ''}} autofocus>
                    <label class="custom-control-label h5 font-weight-bold" for="notificationToggle">{{__('label.do_you_want_to_disable_all_configurations')}}</label>
                </div>
            </div>
            
           
            <div class="table-responsive table" id="dataTable-container"> 
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th class="text-left"> {{ __('label.type') }} </th>
                            <th> {{ __('label.notification') }} </th>
                            <th> {{__('label.mail')}} </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
               
            </div>
            @if($main_status ==1)
            <div>
                <button id="saveButton" class="btn btn-default mw-120" >{{ __('label.save') }}</button>
            </div>
            @endif
           
        </div>
    </div>
@endsection

@section('pagescript')
<script>
    // Sidebar Scroll Down
    let sidebarHeight = $('.sidebar')[0].scrollHeight;
    sidebar_down(sidebarHeight);

    $(document).ready(function() {
        var table = $('#datatable').DataTable({
            ...dataTableDefaults,
            lengthMenu: [
                    [50, 100, 500, -1],
                    [50, 100, 500, "All"]
                ],
            ajax: {
                url: "{{ route('admin.integration-notification.index') }}",
                
            },
            columns: [
                {
                    data: 'type',
                    name: 'type',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, full, meta) {
                        if (!data) return "-";

                            let base = `<div class="primary-color h5" style="text-align: left;">${data}`;
                          
                            base += `</div>`;
                            return base;
                    }
                },
               
                {
                    data: 'send_notification',
                    render: function(data, type, row) {
                        var disabled = (row.type == 'register' || row.type == 'login' || row.type == 'change-password' || row.type =='wallets' || row.type =='withdrawal-process') ? 'disabled' : '';
                        return `<input type="checkbox" name="notification[]" class="notification_checkbox custom-checkbox" data-id="${row.id}" ${data == 1 ? 'checked' : ''} ${disabled}>`;
                    },
                    orderable: false
                },
                {
                    data: 'send_mail',
                    render: function(data, type, row) {
                        var disabled = (row.type == 'profile-update'|| row.type =='account-delete' || row.type =='new-product-arrival' || row.type =='new-special-offers'||row.type =='new-added-categories'|| row.type =='wishlist-alert' || row.type =='cart-abandonment-reminder' || row.type == 'reviews' || row.type == 'orders-reminders' || row.type == 'product-stock-alert' || row.type == 'resend-otp') ? 'disabled' : '';
                        return `<input type="checkbox" name="email[]" class="email_checkbox custom-checkbox" data-id="${row.id}" ${data == 1 ? 'checked' : ''} ${disabled}>`;
                    },
                    orderable: false
                },
                
                ],
            
        });
    });

    var mainstatus = "<?php echo $main_status; ?>";
    if(mainstatus ==1){
        $('#dataTable-container').show();
    }else{
        $('#dataTable-container').hide();
    }

    function SaveNotification(type, status) {
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if(Demo_Mode == 1){
            let entries = [];
            $('#datatable tbody tr').each(function () {
                let entryId = $(this).find('.notification_checkbox').data('id');
                let notification = $(this).find('.notification_checkbox').is(':checked') ? 1 : 0;
                let email = $(this).find('.email_checkbox').is(':checked') ? 1 : 0;

                entries.push({
                    id: entryId,
                    notification: notification,
                    email: email,
                });
            });

            $.ajax({
                url: '{{ route('admin.integration-notification.save') }}',
                type: 'POST',
                data: {
                    entries: entries, 
                    type: type, 
                    status: status,
                    _token: '{{ csrf_token() }}' 
                },
                beforeSend: function () {
                    $("#dvloader").show(); 
                },
                success: function (resp) {
                    $("#dvloader").hide(); 
                    get_responce_message(resp, '', '{{ route("admin.integration-notification.index") }}'); 
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide(); 
                    toastr.error(errorThrown, textStatus); 
                }
            });
        }else {
            showError();
        }
    }

    $('#saveButton').on('click', function () {
        SaveNotification("", 2); // Call the function when Save button is clicked
    });

    // Notification toggle handler
    $('#notificationToggle').change(function () {
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
        if(Demo_Mode == 1){
            if (this.checked) {
            // If the checkbox is checked, show the DataTable and enable notifications
            $('#dataTable-container').show();
            SaveNotification("all", 1); // Enable all notifications
            } else {
                // If unchecked, hide the DataTable and disable notifications
                $('#dataTable-container').hide();
                SaveNotification("all", 0); // Disable all notifications
            }
        }else{
            showError();
        }
       
    });

    function change_status(id, status) {
        var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                $("#dvloader").show();
                var url = "{{route('admin.integration-notification.show', '')}}" + "/" + id;
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
                            if (resp.Status_Code == 1) {

                                $('#' + id).text('Enable');
                                $('#' + id).css({
                                    "background": "#058f00",
                                    "font-weight":"bold",
                                    "color": "white",
                                    "border": "none",
                                    "outline": "none",
                                    "padding": "5px 15px",
                                    "border-radius": "5px",
                                    "cursor": "pointer",
                                });
                            } else {

                                $('#' + id).text('Disable');
                                $('#' + id).css({
                                    "background": "#e3000b",
                                    "color": "white",
                                    "border": "none",
                                    "outline": "none",
                                    "padding": "5px 20px",
                                    "border-radius": "5px",
                                    "cursor": "pointer",
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
</script>
   
@endsection