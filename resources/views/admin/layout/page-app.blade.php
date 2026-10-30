<!DOCTYPE html>
<html>

<head>
    <!-- Meta Tag -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tab Icon -->
    <link rel="shortcut icon" href="{{ Tab_Icon() }}">

    <!-- Title Tag  -->
    <title>@yield('tab_title') | {{ App_Name() }}</title>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}?v={{ filemtime(public_path('assets/css/style.css')) }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    <!-- base_url -->
    <input type="hidden" value="{{ URL('') }}" id="base_url">
    {{-- <!-- Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <!-- Summer notes -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">

    <!-- Date Time Picker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"> --}}

    <!-- Custom Style -->
    <style>
        /* Select 2 DropDown */
        .select2-container .select2-selection--single {
            border: 1px solid #f5f5f5;
            background: #fdfdfd;
            border-radius: 8px;
            padding: 8px;
            font-size: 14px;
            height: auto !important;
        }

        .select2-container .select2-selection--multiple {
            border: 1px solid #f5f5f5;
            background: #fdfdfd;
            border-radius: 8px;
            padding: 8px;
            font-size: 14px;
            height: auto !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #4e45b8 !important;
        }
    </style>

    <!--Custom Script-->
    <script>
        var globalSiteUrl = '<?php echo $path = url('/'); ?>'
        var serverEnvironment = '<?php echo env('APP_ENV'); ?>'
        var currentRouteName = '<?php echo request()->route()->getName(); ?>'
    </script>
</head>

<body>

    @yield('content')

    <div style="display:none" id="dvloader"><img src="{{ asset('assets/imgs/loading.gif') }}" /></div>
    <!-- Feather Icon -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- Jquery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Datatable -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/js.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <!-- Chunk JS -->
    <script src="{{ asset('/assets/js/plupload.full.min.js') }}"></script>
    <script src="{{ asset('/assets/js/common.js') }}"></script>
   

{{-- 
    <!-- chart -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Data Time Picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
    <!-- Summer notes -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <!-- Sortable -->
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}

    <script>
        // Counter
        $('.counting').each(function() {
            var $this = $(this),
                countTo = $this.attr('data-count');

            countTo = getVal(countTo);

            $(this).prop('Counter', 0).animate({
                countNum: countTo
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) {
                    $(this).text(Math.ceil(now));
                },
                complete: function() {
                    $this.text($this.attr('data-count'));
                }
            });
        });

        function getVal(val) {

            multiplier = val.substr(-1).toLowerCase();

            if (multiplier == "k")
                return parseFloat(val) * 1000;
            else if (multiplier == "m")
                return parseFloat(val) * 1000000;
            else if (multiplier == "b")
                return parseFloat(val) * 1000000000;
            else if (multiplier == "t")
                return parseFloat(val) * 1000000000000;
            else
                return val;
        }

        function get_responce_message(resp, form_name = "", url = "") {
            if (resp.status == '200') {
                toastr.success(resp.success);
                if (form_name != "") {
                    document.getElementById(form_name).reset();
                }
                if (url != "") {
                    setTimeout(function() {
                        window.location.replace(url);
                    }, 500);
                }
            } else {
                var obj = resp.errors;
                if (typeof obj === 'string') {
                    toastr.error(obj);
                } else {
                    $.each(obj, function(i, e) {
                        toastr.error(e);
                    });
                }
            }
        }

        // Toastr MSG Show
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}');
        @elseif (Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
        @endif

        document.querySelectorAll('.image-upload-wrapper').forEach(wrapper => {
            const input = wrapper.querySelector('input[type="file"]');
            const preview = wrapper.querySelector('img');
            const dropArea = wrapper.querySelector('.drop-area');

            // Click-to-upload
            dropArea.addEventListener('click', () => input.click());

            // File select preview
            input.addEventListener('change', function() {
                handleFile(this.files[0]);
            });

            // Drag and drop events
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropArea.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, e => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropArea.classList.remove('dragover');
                });
            });

            // Handle drop
            dropArea.addEventListener('drop', e => {
                const file = e.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    input.files = e.dataTransfer.files; // allow form submission
                    handleFile(file);
                }
            });

            // Reusable preview function
            function handleFile(file) {
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(file);
            }
        });
        
        // Sidebar Scroll Down
        function sidebar_down(height) {
            $(".sidebar").animate({
                scrollTop: height
            });
        }
        // DataTable Defaults
        var dataTableDefaults = {
            dom: "<'top'f>rt<'row'<'col-2'i><'col-1'l><'col-9'p>>",
            searching: false,
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            lengthMenu: [
                [10, 50, 100, 500, -1],
                [10, 50, 100, 500, "All"]
            ],
            language: {
                paginate: {
                    previous: "<i class='fa-solid fa-chevron-left'></i>",
                    next: "<i class='fa-solid fa-chevron-right'></i>"
                }
            }
        };

        function showError() {
            toastr.error("{{ __('label.you_have_no_right_to_add_edit_and_delete') }}");
        }
    </script>

    @yield('pagescript')
</body>

</html>
