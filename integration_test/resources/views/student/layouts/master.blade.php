<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            {{ isset($title) && !empty(trim($title)) ? $title . ' | ' . config('app.name', 'Carrier Without Barrier') : config('app.name', 'Carrier Without Barrier') }}
        </title>
        <link type="image/x-icon" href="{{ asset('website/assets/images/fav-icon.png') }}" rel="shortcut icon">

        <link href="{{ asset('student/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/f1font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('student/css/icheck-bootstrap.min.css') }}" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

        <link href="{{ asset('student/css/all.min.css') }}" rel="stylesheet">
        <!-- <link href="css/tempusdominus-bootstrap-4.min.css" rel="stylesheet"> -->
        <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css" rel="stylesheet">
        <!-- <link href="css/adminlte.min.css" rel="stylesheet"> -->
        <link href="{{ asset('student/css/OverlayScrollbars.min.css') }}" rel="stylesheet">


        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Asap+Condensed:wght@400;500;600;700&amp;display=swap"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Quattrocento+Sans:wght@400;700&amp;display=swap"
            rel="stylesheet">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link href="{{ asset('css/js_latest_toastr.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
        <script src="{{ asset('js/common.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

        <style>
            .toast-top-center-below {
                top: 20px;
                right: 35%;
                height: 100px;
            }

            .toast-top-center-below .toast-error {
                min-height: 70px;
                opacity: 5 !important;
                font-size: 20px;
                width: 340px !important;
            }

            .studentImage {
                aspect-ratio: 1 / 1;
                overflow: hidden;
            }

            .studentImage img {
                object-fit: cover;
                object-position: center;
                width: -webkit-fill-available;
                height: -webkit-fill-available;
            }

            @media print {
                .no-print {
                    display: none !important;
                }
            }
        </style>

        @livewireStyles

    </head>

    <body style="background-color: #f4f6f9;">
        <div class="toggled" id="wrapper" style="height: -webkit-fill-available;">
            <div class="overlay"></div>

            @include('student.layouts.sidebar')
            @include('student.layouts.student_menubar')

            <!-- Page Content -->
            <div class="content-wrapper" style=" background-color: #f4f6f9; height: -webkit-fill-available;">
                <!-- Put "@" before include -->
                <!-- include('student.layouts.content_header') -->
                @yield('content')
                @if (isset($slot))
                    {{ $slot }}
                @endif

            </div>

            <!-- /#wrapper -->
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
            </script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
            </script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
            </script>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="{{ asset('student/index.js') }}"></script>
            <script src="{{ asset('js/js_latest_toastr.min.js') }}"></script>

            <script>
                function success(msg) {
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-center-below",
                        "preventDuplicates": false,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "3000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    toastr.success(msg);
                }

                function error(msg) {
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-center-below",
                        "preventDuplicates": false,
                        "showDuration": "300",
                        "hideDuration": "2000",
                        "timeOut": "3000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    }
                    toastr.error(msg)
                }
            </script>
            @stack('custom-scripts')
            <x-message />

            @livewireScripts
    </body>

</html>
