<x-mail.template>
    <x-slot:title>Contact Email Reply</x-slot:title>

    <p class="text-light">
        Dear, {{ $name }}<br />
        {{ $city }}<br />
    </p>
    <p style="margin-top:15px; font-size:0.88rem;text-decoration: underline;">
        Subject: We received your request.
    </p>

    <p>
        Our dedicated team will contact you within (3) three days. Team will call you to proceed further.
    </p>
    <p>
        Our team will guide/ help you for next step
    </p>
    <p>
        We appreciate if you share your reviews or suggestion to help us better.
    </p>
    <p><b>Please don't think twice to contact us.</b></p>

</x-mail.template>
