<x-mail::message>
Hi {{$corporate->name}},

Your Institute is successfully Approved. 
Please use this <b>{{$corporate->institude_code}}</b> branch code, to signup so we can go further on your request.
                            
<?php

$encode = base64_encode($corporate->institude_code);

?>
<x-mail::button :url="route('corporateSignup',['branch_code'=>$encode])">
    SignUp Now
</x-mail::button>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
