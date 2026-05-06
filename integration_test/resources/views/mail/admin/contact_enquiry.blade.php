<x-mail::message>
    Hello!
    <br>
    <div style="text-align: center">
        <img width="70" src="{{ asset('images/approvalmark.png') }}">
    </div>
    <div >
        <h2 style="color:rgb(35, 33, 33); text-align:center ">Success</h2>
        <p align="center" style="margin-top: 10px; color:rgb(35, 33, 33); text-align:center" id='message'>
            New Contact Enquiry {{ $contact->name }}  successfully sent!
        </p>
    </div>
    @component('mail::button', ['url' => route('admin.contactEnquiry')])
        Click To See
    @endcomponent
    <br> <br>
    Thanks
    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>
