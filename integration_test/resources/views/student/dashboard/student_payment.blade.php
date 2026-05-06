@extends('student.layouts.master')
@section('content')

<?php

$appCode = $student->latestStudentCode;

$studentPayment = $student->studentPayment->last();

function calculateDiscountPercentage($discountAmount, $originalAmount = 850)
{
    if ($originalAmount == 0) {
        return 0;
    }
    $discountPercentage = ($discountAmount / $originalAmount) * 100;
    return $discountPercentage;
}

?>

<section class="content admin-1 mt-3">
    <div class="row corporate-cards" style="width: 50%;text-align: center;margin-left: 20%;padding-top:5%;margin-right: auto;">
        <div class="col-md-12 col-12" id="prodiv">
            <div class="card">
                <div class="card-header" style="background-color:#18c968 ; color: white;text-align:center;justify-content:center">
                    <div st>
                        <h6 style="color: #000;">Your Application no is: <b> {{$appCode?->application_code}}</b></h6>
                    </div>


                </div>
                <br>
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="studentTable">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><b>Name</b></td>
                                        <td class="information-txt" colspan="2">{{$student->name}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Mobile</b></td>
                                        <td class="information-txt" colspan="2">{{$student->mobile}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Email</b></td>
                                        <td class="information-txt" colspan="2">{{$student->email}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Refferell Code Provided By</b></td>
                                        <td class="information-txt" colspan="2">{{$appCode?->corporate_name ? $appCode?->corporate_name : 'SQS Foundation, Kanpur'}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Refferell Subscription Code</b></td>
                                        <td class="information-txt" colspan="2">{{$appCode?->coupan_code ?? '-'}}</td>
                                    </tr>


                                    <tr>
                                        <td colspan="2"><b>Fee Amount</b></td>
                                        <td class="information-txt" colspan="2">850 &#8377;</td>
                                    </tr>


                                    @if($appCode?->is_coupan_code_applied)
                                    @if (calculateDiscountPercentage($appCode?->coupan_value) < 60)
                                        <tr>
                                        <td colspan="2"><b>Discount Amount</b></td>
                                        <td class="information-txt" colspan="2">
                                            {{ $appCode?->is_coupan_code_applied ? $appCode?->coupan_value : 'No'}}
                                            @if($appCode?->is_coupan_code_applied) &#8377; @endif
                                        </td>
                                        </tr>
                                        @endif
                                        @endif

                                        @if(($appCode?->is_coupan_code_applied && !$appCode?->is_paid && ($appCode?->fee_amount > 0)))
                                        <tr>
                                            <td colspan="2"><b>
                                                    {{ calculateDiscountPercentage($appCode?->coupan_value) < 60 ? 'Final Payable Amount':'Final online Paid Amount' }}
                                                </b></td>
                                            <td class="information-txt" colspan="2">
                                                {{ $appCode?->fee_amount }} &#8377;
                                            </td>
                                        </tr>
                                        @elseif($appCode?->is_coupan_code_applied && $appCode?->fee_amount <= 0)
                                            <tr>
                                            <td colspan="2"><b>
                                                    {{ calculateDiscountPercentage($appCode?->coupan_value) < 60 ? 'Final Payable Amount':'Final online Paid Amount' }}
                                                </b></td>
                                            <td class="information-txt" colspan="2">
                                                0 &#8377;
                                            </td>
                                            </tr>
                                            @endif
                                            @if( $appCode?->is_paid && $studentPayment?->payment_amount)
                                            <tr>
                                                <td colspan="2"><b>
                                                        {{ calculateDiscountPercentage($appCode?->coupan_value) < 60 ? 'Final Payable Amount':'Final online Paid Amount' }}
                                                    </b></td>
                                                <td class="information-txt" colspan="2">
                                                    {!!$studentPayment?->payment_amount .' &#8377;' ?? 0 !!}
                                                </td>
                                            </tr>
                                            @endif
                                            @if(!$studentPayment && !$appCode?->is_paid && ($appCode?->is_coupan_code_applied ? ($appCode?->fee_amount > 0) : true) )
                                            <tr>
                                                <td class="text-center" colspan="4">
                                                    <button type="button" id="exampleModalCenterButton" class="bg-success btn-lg  btn text-white action-button" data-toggle="modal" data-target="#exampleModalCenter"><b>Pay Now</b></button>
                                                </td>
                                            </tr>
                                            @else
                                            <tr class="dn">
                                                <td colspan="4">
                                                    <button type="button" style="width: 5rem;height: 2rem;" class="btn btn-md btn-info" data-print="modal" onclick="PrintDoc()"> Print <i class="fa fa-print"></i></button>
                                                </td>
                                            </tr>
                                            @endif



                                            @if($studentPayment && $studentPayment->payment_status == 'success' )
                                            <tr>
                                                <table class="table table-response">
                                                    <thead>
                                                        <tr>
                                                            <th>Course Type</th>
                                                            <th>Course</th>
                                                            <th>Institute</th>
                                                            <th>Amount</th>
                                                            <th>Payment Order ID </th>
                                                            <th>Payment Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{$studentPayment->course_type}}</td>
                                                            <td>{{$studentPayment->course_id}}</td>
                                                            <td>{{$studentPayment->institute_id}}</td>
                                                            <td>{{$studentPayment->payment_amount}}</td>
                                                            <td>{{$studentPayment->payment_order_id }}</td>
                                                            <td>{{ucfirst($studentPayment->payment_status)}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </tr>
                                            @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <form id="couponForm" action="{{route('student.paymentCreate')}}" method="get">
            @csrf
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div {{ $appCode?->is_coupan_code_applied ? 'style="margin-left: 16%;"':'' }}>
                            <h6 class="modal-title" id="exampleModalLongTitle"> @if($appCode?->is_coupan_code_applied) Refferel Coupon is Applied Successfully @else Apply Coupon Code: @endif</h6>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center fee_amount-dv">
                            <p id="fee_amount" class="text-sucsess font-weight:20px;" style="margin-left: -45px;margin-bottom:0rem;" readonly>Fee Amount (Rs.) : 850</p>
                            @if($appCode?->is_coupan_code_applied)
                            <p class="text-danger fee_discount_amount" style="margin-left: -20px; margin-bottom:0rem;">Discount Amount (Rs.): -{{$appCode?->coupan_value}}</p>

                            <p id="payable_amount" style="font-weight:700; ">Final Payable Amount (Rs.): {{$appCode?->fee_amount}} </p>
                            @endif

                        </div>
                        <div class="modalLoader" id="reply-loader" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <label for="coupan_code">Coupan code: </label>

                        <div class="input-group">
                            <input type="text" placeholder="Enter coupon code" class="form-control coupon_code-input" name="coupan_code" {{$appCode?->is_coupan_code_applied ? "value=$appCode?->coupan_code".' '.'readonly="true"' : ''}}>
                            <div class="input-group-append">
                                <button type="button" id="applyCoupon" class="btn btn-primary bg-success" style="display:{{$appCode?->is_coupan_code_applied ? 'none' : 'block'}};"">Apply Coupon</button>
                                <button type=" button" id="removeCoupon" class="btn btn-primary text-danger" style="background: #fd0000;color: white !important;border: #f91818;{{$appCode?->is_coupan_code_applied ? 'display:block' : 'display:none'}}">Remove Coupon</button>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-center" id="pay-now-btn-modal" style="display:block;text-align:center;">
                        <?php if ($appCode?->is_coupan_code_applied && ($appCode?->fee_amount) <= 0) : ?>
                            <div style="display:block;text-align:center;">
                                <h6 style="font-weight:700;">Discount Coupon Provided By: {{$appCode?->corporate_name ? $appCode?->corporate_name : 'SQS Foundation, Kanpur'}}</h6>
                                <h6 style="font-weight:700;color:red;">100% Free</h6>
                            </div>
                        <?php elseif ($appCode?->is_coupan_code_applied && ($appCode?->fee_amount) > 0) : ?>
                            <div style="display:block;text-align:center;">
                                <h6 style="font-weight:700;">Discount Coupon Provided By: {{$appCode?->corporate_name ? $appCode?->corporate_name : 'SQS Foundation, Kanpur'}}</h6>
                            </div>
                        <?php endif; ?>
                        @if(!$appCode?->is_paid && ($appCode?->is_coupan_code_applied ? ($appCode?->fee_amount) > 0 : true))
                        {{-- @if(intval($appCode?->fee_amount) > 0) --}}
                        <button type="submit" class="btn btn-primary" wire:ignore>Pay Now</button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#applyCoupon').click(function(event) {
            event.preventDefault();
            $('.modalLoader').show()

            var inputData = $(this).parent().prev().val();

            var $btn = $(this);

            if (inputData == '') {
                error('Please provide Coupon details.');
                $('.modalLoader').toggle()
                return;
            }
            var formData = $('#couponForm').serialize();

            $.ajax({
                type: 'POST',
                url: "{{route('students.couponCodeApply')}}",
                data: formData,
                success: function(response) {
                    $('.modalLoader').hide()
                    if (response.status) {

                        console.log('applied response: ', response)

                        $('#applyCoupon').attr('disabled', true);

                        $('.fee_amount-dv').append().html('<p id="fee_amount" class="text-sucsess font-weight:20px;" style="margin-bottom:0rem; margin-right: 38px;" readonly>Fee Amount (Rs.) : 850</p><p class="text-danger fee_discount_amount" style="margin-left: -13px; margin-bottom:0rem;">Discount Amount (Rs.): -' + response.discount_amount + '</p><p style="margin-left:20px; font-weight:700;margin-bottom:0rem;">Final Payable Amount (Rs.): ' + response.amount + '</p>');


                        $('#exampleModalLongTitle').html('Refferel Coupon is Applied Successfully');
                        $('#exampleModalLongTitle').parent().css('margin-left', '16%');

                        const payNowButtonDiv = document.getElementById('pay-now-btn-modal');
                        if (response.amount > 0) {
                            payNowButtonDiv.innerHTML('<div style="display:block;text-align:center;"><h6 style="font-weight:700;">Discount Coupon Provided By: ' + response.corporate_name ? response.corporate_name : 'SQS Foundation, Kanpur' + '</h6> <button type="submit" class="btn btn-primary ">Pay Now</button></div>');
                        } else {
                            payNowButtonDiv.innerHTML('<div style="display:block;text-align:center;"><h6 style="font-weight:700;">Discount Coupon Provided By: ' + response.corporate_name ? response.corporate_name : 'SQS Foundation, Kanpur' + '</h6><h6 style="font-weight:700;color:red;">100% Free</h6></div>');
                        }
                        $btn.parent().prev().attr('readonly', true);
                        $btn.hide();
                        $('#removeCoupon').show();
                        success('Coupon Code Applied.');
                        // $('#exampleModalCenter').modal('hide');
                        // $('#exampleModalCenter').modal('show');
                        // window.location.href = window.location.pathname + '?modal=open'
                        // console.log(window.location.pathname)
                    }


                    if (!response.status) {
                        error('Coupon Code Invalid.');
                        return;
                    }
                },
                error: function(xhr, status, error) {
                    $('.modalLoader').hide()

                    alert(error);
                }
            });
        });
    });

    $('#removeCoupon').click(function(event) {
        removeCoupon();
    });

    function removeCoupon() {

        event.preventDefault();
        $('.modalLoader').show();
        var coupon_code = $('#removeCoupon').parent().prev().val()
        $.ajax({
            type: 'POST',
            url: "{{ route('students.removeCoupon') }}",
            data: {
                'coupon_code': coupon_code
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('.modalLoader').hide();
                if (response.status) {
                    $('#applyCoupon').text('Apply Coupon');
                    $('#applyCoupon').toggle();
                    $('#applyCoupon').parent().parent().prev().text('Coupon Code:')
                    $('#applyCoupon').attr('disabled', false);
                    $('#fee_amount').html('Fee Amount(Rs.): 850');
                    $('#fee_amount').nextAll('p').remove();
                    $('.coupon_code-input').val('');
                    $('.coupon_code-input').attr('readonly', false);
                    $('#removeCoupon').hide();
                    $('#exampleModalLongTitle').text('Apply Coupon Code:');
                    $('#exampleModalLongTitle').parent().css('margin-left', '0%');

                    const payNowButtonDiv = document.getElementById('pay-now-btn-modal');
                    payNowButtonDiv.innerHTML('<div style="display:block"> <button type="submit" class="btn btn-primary ">Pay Now</button></div>');

                    success(response.message);
                    // window.location.href = window.location.pathname + '?modal=open'
                } else {
                    error(response.message);
                }
            },
            error: function(xhr, status, error) {
                $('.modalLoader').hide();
                alert(error);
            }
        });
    }

    $('.close').on('click', function() {
        location.reload();
    })
</script>

<script>
    function PrintDoc() {
        var toPrint = document.getElementById('prodiv');

        var popupWin = window.open('', '_blank', 'left=100,top=100,width=1100,height=600,tollbar=0,scrollbars=1,status=0,resizable=1');

        popupWin.document.open();

        popupWin.document.write('<html><title>Payment Reciepts</title><head><style>body{font-family:Arial} .noprint{display: none;} .table{width:100%; border-collapse:collapse;}h1{font-size:15pt;} .table tr th, .table tr td{border:1px solid #000; padding:2px 5px; font-size: 8.7pt;} .bsvtbl tbody tr td{border:1px solid #000; padding:8px 5px; font-size: 10pt;} .photo{text-align: center;}.dn{display:none} .photo img {width: 115px;}</style></head><body onload="window.print()">')

        popupWin.document.write(toPrint.innerHTML);

        popupWin.document.close();
    }
</script>
@endsection