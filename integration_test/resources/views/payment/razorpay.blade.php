<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Razorpay Payment Gateway Integration</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="shortcut icon" href="{{ asset('website/assets/images/fav-icon.png') }}" type="image/x-icon">
</head>

<body>
    <?php
$feeAmount = $studentFee->is_coupan_code_applied ? $studentFee->fee_amount : 850;

    ?>
    <div id="app">
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                Razorpay Payment Gateway Integration <br>
                                Do not press the back button until the payment is successful.
                            </div>
                            <div class="card-body text-center">
                                <form action="{{ route('razorpay.payment.store') }}" method="POST" id="razorpay-form">
                                    @csrf
                                    <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ config('services.razorpay.key') }}"
                                        data-amount="{{ $feeAmount }}00" data-currency="INR" data-buttontext="Pay {{ $feeAmount }} INR"
                                        data-name="{{ config('app.name', 'Career Without Barrier') }}" data-description="Razorpay"
                                        data-image="{{ asset('website/assets/images/fav-icon.png') }}" data-prefill.name="{{ $student->name }}"
                                        data-prefill.email="{{ $student->email }}" data-prefill.contact="{{ $student->mobile }}"
                                        data-theme.color="#F3F3F3"></script>
                                </form>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    // Auto-click the Razorpay payment button on page load
                                    $('.razorpay-payment-button').click();
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>