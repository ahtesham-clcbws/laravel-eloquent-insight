<x-mail::message>
Hi {{$corporate->name}},
<br>
<br>
 {{$corporate->message}}
       <br>                     
 Please Click On The Below <b>Button</b> to Login.
 <br>
<x-mail::button :url="route('corporatelogin')">
Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
