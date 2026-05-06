<x-mail::message>
    Hello!
    <br>
    <div style="text-align: center">
        <img src="{{ asset('images/approvalmark.png') }}" width="70">
    </div>
    <div>
        <h2 style="color:rgb(35, 33, 33); text-align: center;">Student Registration Successful</h2>
        <br />
        <p id='message' style="margin-top: 10px; color:rgb(35, 33, 33); text-align:center" align="center">
            Student's Name: <b>{{ $student->name }}</b><br />
            Mobile Number: <b>{{ $student->mobile }}</b>.
        </p>
        <br />
        @component('mail::button', ['url' => route('admin.student', [$student->id])])
            Click here to view
        @endcomponent
    </div>
    <br> <br>
    Thanks
    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>
