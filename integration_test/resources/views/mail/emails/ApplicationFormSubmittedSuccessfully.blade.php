<x-mail.template>
    <x-slot:title>Application Form Submitted Successfully</x-slot:title>

    <p class="text-light">
        Dear, {{ $name }}<br />
        {{ $city }}<br />
    </p>
    <p style="margin-top:15px; font-size:0.88rem;text-decoration: underline;">
        Subject: Your Application form is submitted successfully.
    </p>
    <p>
        Your Application no. is <b>{{ $application_no }}</b><br />
        Your login mobile no is <b>{{ $mobile }}</b><br />
        Your registered email id is <b>{{ $email }}</b><br />
    </p>
    <p>
        You may get all the information, Information brochure, Registration form, Payment receipt, admit card, answer
        key, result & Scholarship Claim form to visit/login <a
            href="www.careerwithoutbarrier.com">www.careerwithoutbarrier.com</a><br />
    </p>
    <p>
        You are welcome to share your reviews about us or contact for any issue.
    </p>
    <p>
        We are ready to serve you.
    </p>
    <p>
        We appreciate if you share your reviews/ suggestion to help us better.
    </p>
    <p><b>Please don't think twice to contact us.</b></p>

</x-mail.template>
