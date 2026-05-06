<html>

<head>

    <link rel="stylesheet" href="{{asset('css/f1font-awesome.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <link rel="shortcut icon" href="{{ asset('website/assets/images/fav-icon.png') }}" type="image/x-icon">
    <script src="{{ asset('website/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('website/assets/js/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <title>Corporate Signup</title>
    <style>
        .toast-top-right-below {
            top: 20px;
            right: 40%;
            height: 100px;
            color: black !important;
        }

        .toast-top-right-below .toast-error {
            min-height: 70px;
            opacity: 5 !important;
            font-size: 20px;
            background-color: #28a745 !important;
        }
    </style>
    @livewireStyles
</head>

<body class="d-flex align-items-center" style="background-color: #4629cc;">
    <div class="container p-4">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col col-xl-10">
                @yield('content')

                @if (isset($slot))
                {{ $slot }}
                @endif
            </div>
        </div>
    </div>
    <script>
        function success(msg) {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "showDuration": "1000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",

            }
            toastr.success(msg);
        }

        function error(msg) {
            toastr.options = {
                "closeButton": false,
                "debug": true,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "showDuration": "1000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",

            }
            toastr.error(msg)
        }

        //       new DataTable('.datatablecl', {
        //     responsive: true
        // });
    </script>

    <script>
        function reload300() {
            setTimeout(() => {
                window.location.reload()
            }, 300);
        }
    </script>
    @livewireScripts
    @stack('custom-scripts')
    <x-message />
</body>

</html>