<x-mail.template>
    <x-slot:title>Institute Collaboration request is approved</x-slot:title>

    <p class="text-light">
        Dear, {{ $name }}<br />
        {{ $institute_name }}<br />
        {{ $city }}<br />
    </p>
    <p style="margin-top:15px; font-size:0.88rem;text-decoration: underline;">
        Subject: Your institute collaboration request is approved successfully
    </p>

    <p>
        <strong>Congratulations</strong><br />
        We are happy to inform you that you are just one step away to be a part of us, Your request is approved.
        We are providing a unique code below for your institute/ society/ trust/ school.<br />
    </p>
    <p>
        Your Institute unique code: <b>{{ $code }}</b><br />
        Your login mobile no is <b>{{ $mobile }}</b><br />
        Your registered email id is <b>{{ $email }}</b><br />
    </p>
    <p>
        You may use this unique code which is mandatory to sign up with our portal<br />
        <a href="https://www.careerwithoutbarrier.com/institute/signup">www.careerwithoutbarrier.com/institute/signup</a>
    </p>
    <p>Kindly sign-up with this unique code along with using your e-mail & mobile no used at the time of filling up the
        institute collaboration request form.</p>
    <p>
        Sign-up & login form links given below <br />
        <a
            href="https://www.careerwithoutbarrier.com/institute/signup">www.careerwithoutbarrier.com/institute/signup</a><br />
        <a
            href="https://www.careerwithoutbarrier.com/institute/login">www.careerwithoutbarrier.com/institute/login</a><br />

    </p>
    <p>You may contact if any issue regarding signup/ login </p>

</x-mail.template>
