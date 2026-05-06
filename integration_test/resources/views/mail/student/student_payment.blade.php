<x-mail::message>
    Hello!
    <br>
    <div style="text-align: center">
        <img width="70" src="{{ asset('images/approvalmark.png') }}">
    </div>
    <div >
        <h2 style="color:rgb(35, 33, 33); text-align:center ">Success</h2>
        <p align="center" style="margin-top: 10px; color:rgb(35, 33, 33); text-align:center" id='message'>
           Hello <b>{{ $student->name }}</b>  {{$studentCode->used_coupon ? 'You Used Coupon Code ' : 'Your Payment Done ' }} successfully!
        </p>
    </div>
    @component('mail::button', ['url' => route('studentDashboard')])
        Click here
    @endcomponent
    <br> <br>
    Thanks
    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>
