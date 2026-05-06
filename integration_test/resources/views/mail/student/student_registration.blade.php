<x-mail::message>
    Hello  {{ $student->name }},
    <br>
    <div style="text-align: center">
        <img width="70" src="{{ asset('images/approvalmark.png') }}">
    </div>
    <div >
        <h2 style="color:rgb(35, 33, 33); text-align:center ">Success</h2>
        <p align="center" style="margin-top: 10px; color:rgb(35, 33, 33); text-align:center" id='message'>
            You are Registered successfully Registered!
        </p>
    </div>
    @component('mail::button', ['url' => route('student.dashboard')])
        Click here
    @endcomponent
    <br> <br>
    Thanks
    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>
