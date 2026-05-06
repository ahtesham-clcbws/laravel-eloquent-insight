<!-- resources/views/home.blade.php -->
@extends('layouts.website')

@section('title', 'Home Page')


@section('content')

    <style>
        /*
                             * Clean CSS only - No frameworks or libraries.
                             * Using custom class names to avoid collision (e.g., with Bootstrap).
                             */

        :root {
            /* Define main colors from the image */
            --main-red: #d90000;
            --main-blue: #004b8d;
            --main-dark: #333333;
            --main-gray: #6b6b6b;
            --light-blue: #2a8b9f;
        }

        /* Prevent potential bootstrap collision */
        .biz-card-container {
            font-family: Arial, sans-serif;
            /* margin: 40px auto; */
            /* max-width: 450px; */
            padding: 10px;
            background-color: #ffffff;
            text-align: center;
            box-shadow: 0px 5px 10px rgb(0 0 0 / 4%);
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .biz-card-section {
            padding: 10px 0;
            margin: 10px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* --- Header / Logo Section --- */
        /* .biz-card-header {
                    border-bottom: 1px solid #eeeeee;
                } */

        .biz-card-logo-organizer {
            font-family: 'Times New Roman', serif;
            font-style: italic;
            color: var(--main-dark);
            font-size: 0.8em;
            margin-bottom: 5px;
            text-decoration: underline;
        }

        .biz-card-logo-box {
            display: inline-block;
            border: 1px solid var(--main-dark);
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .biz-card-logo-image {
            width: 80px;
            height: auto;
            display: block;
            margin: 0 auto 5px auto;
        }

        .biz-card-logo-text {
            font-size: 0.7em;
            font-weight: bold;
            color: var(--main-dark);
            /* border-top: 1px solid #ccc; */
            padding-top: 5px;
        }

        /* --- Foundation Name Section --- */
        .biz-card-foundation-name {
            font-size: 1.8em;
            font-weight: 900;
            color: var(--main-red);
            margin: 10px 0;
            letter-spacing: 0.5px;
        }

        /* --- Contact Details Section --- */
        .biz-card-contact-item {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            margin-bottom: 5px;
            font-size: 0.9em;
            line-height: 1.5;
            color: var(--main-dark);
        }

        .biz-card-address-item {
            display: flex;
            justify-content: center;
            margin-bottom: 5px;
            font-size: 0.9em;
            line-height: 1.5;
            color: var(--main-dark);
        }

        .biz-card-icon {
            /* Inline SVG styling */
            width: 18px;
            height: 18px;
            margin-right: 8px;
            color: var(--main-gray);
            /* Default icon color */
            flex-shrink: 0;
            position: relative;
            top: 2px;
        }

        /* Specific icon colors based on the image */
        .biz-card-icon.phone-icon {
            color: var(--main-blue);
        }

        .biz-card-icon.whatsapp-icon {
            color: #25D366;
        }

        .biz-card-icon.globe-icon {
            color: var(--main-dark);
        }

        .biz-card-icon.mail-icon {
            color: var(--main-dark);
        }


        .biz-card-address-text {
            /* text-align: left; */
            max-width: 70%;
        }

        .biz-card-phone-section {
            font-weight: bold;
            font-size: 1.1em;
            color: var(--main-dark);
            margin: 10px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        .biz-card-phone-number {
            display: flex;
            align-items: center;
        }

        .biz-card-separator {
            font-size: 1.5em;
            color: var(--main-dark);
        }

        .biz-card-web-section {
            /* border-top: 1px solid #eeeeee; */
            padding-top: 10px;
            margin-top: 10px;
        }

        .biz-card-web-link {
            text-decoration: none;
            color: var(--main-dark);
            display: block;
            margin: 3px 0;
        }

        .biz-card-email-link {
            text-decoration: none;
            color: var(--main-dark);
            font-weight: bold;
        }

        /* --- Footer Section --- */
        .biz-card-footer {
            margin-top: 15px;
            padding-top: 10px;
        }

        .biz-card-footer-partner-label {
            font-family: 'Times New Roman', serif;
            font-style: italic;
            color: var(--light-blue);
            font-size: 1em;
            margin-bottom: 1px;
        }

        .biz-card-footer-company {
            font-size: 1.2em;
            font-weight: 900;
            color: var(--main-blue);
            margin: 5px 0;
        }

        .biz-card-footer-description {
            font-size: 0.9em;
            color: var(--main-red);
            margin-bottom: 0;
        }

        .biz-card-star {
            color: var(--main-red);
            font-size: 0.8em;
            margin: 0 5px;
        }
    </style>

    <body class="conact-page">
        <section>
            <div class="common-banner contact-us-banner">
                <div class="container">
                    <div class="row">
                        <h2>Contact us</h2>
                        <h4><a href="{{ route('home.front') }}">Home > </a> <span>Contact</span></h4>
                        <i class="fly-icon"></i>
                        <div class="comm-ban-im">
                            <img src="{{ asset('website/assets/images/bg-icons/contact-banner.png') }}" alt="img">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="faq comm-p-t-b">
            <div class="container">
                <div class="row tex-center">
                    {{-- <div class="col-12">
                        <h2>Contact</h2>
                    </div> --}}
                    <div class="col-lg-6 col-md-12">
                        <form action="{{ route('home.contactinsert') }}" method="POST" style="height: 100% !important;">
                            @csrf
                            <div class="contact-input" style="height: 100% !important;">
                                <ul>
                                    <li>
                                        <input name="full_name" type="text" value="{{ old('full_name') }}" required
                                            placeholder="Enter Full Name*">
                                        @error('full_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </li>
                                    <li class="mobile-input">
                                        <input class="get-otp-mobile" name="mobile" type="number"
                                            value="{{ old('mobile') }}" required placeholder="Mobile">
                                    </li>

                                    <li style="margin-left: 10px;">
                                        <input name="email" type="text" value="{{ old('email') }}" required
                                            placeholder="Email *">

                                    </li>
                                    @error('mobile')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <li>
                                        <input name="city" type="text" value="{{ old('city') }}" required
                                            placeholder="City/District Name *">
                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </li>
                                    <li>

                                        <select class="form-control" id="reason_contact" name="reason_contact"
                                            value="{{ old('reason_contact') }}"
                                            style="background-color:#f0f4ff;border:0px;" required
                                            onchange="get_other_reason()">
                                            <option value="">Select Reason to Contact</option>
                                            <option value="Student's Application Related Issue">Student's Application
                                                Related Issue</option>
                                            <option value="Student's Admit Card Related Issue">Student's Admit Card
                                                Related Issue</option>
                                            <option value="Student's Result Related Issue">Student's Result Related
                                                Issue</option>
                                            <option value="Institutional Enquiry / Signup">Institutional Enquiry /
                                                Signup</option>
                                            <option value="Institutional Login Issue">Institutional Login Issue
                                            </option>
                                            <option value="Other Issues">Other Issues</option>
                                        </select>
                                        @error('reason_contact')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </li>
                                    <li id="otherdd" style="display:none;">
                                        <input id="subjectres" name="subjectres" type="text"
                                            value="{{ old('subjectres') }}" style="height:40px;background-color:white;"
                                            placeholder="Explain other issue *">
                                        @error('subjectres')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </li>
                                    <li>
                                        <input name="message" type="text" value="{{ old('message') }}"
                                            style="height:80px;" required placeholder="Your message here">
                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </li>
                                    <li style="padding:0px "><input class="sub bg-primary btn-sm" type="submit"
                                            value="Submit" style="width:100px;height:40px;border-radius:5px;padding:0px;">
                                    </li>
                                </ul>

                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6 col-md-12" style="height: 100% !important;">
                        <div class="biz-card-container" style="height: 100% !important;">

                            <!-- Header: Organized By -->
                            <div class="biz-card-header biz-card-section">
                                <p class="biz-card-logo-organizer">Organised By</p>
                                <img class="img-fluid mt-2" src="/logos/logo-square-2.png" style="max-width: 100px;" />
                            </div>

                            <!-- Main Foundation Name -->
                            <h1 class="biz-card-foundation-name">S.Q.S. FOUNDATION</h1>

                            <!-- Address and Contact -->
                            <div class="biz-card-contact-details">

                                <!-- Location/Address -->
                                <div class="biz-card-contact-item biz-card-address-item">
                                    <svg class="biz-card-icon" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                    </svg>
                                    <div class="biz-card-address-text">
                                        A-504, Eldeco Garden Estate, 86/245, Dev Nagar, Raipurwa, Kanpur–208003
                                    </div>
                                </div>

                                <!-- Phone Numbers -->
                                <div class="biz-card-phone-section">
                                    <div class="biz-card-phone-number">
                                        <svg class="biz-card-icon phone-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M20.01 15.38c-1.23 0-2.38-.21-3.41-.58-.33-.11-.74-.03-1.01.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.59l2.2-2.2c.28-.27.35-.67.24-1.01-.37-1.03-.58-2.18-.58-3.41 0-.55-.45-1-1-1H3.49c-.55 0-1 .45-1 1C2.5 13.92 10.08 21.5 18.5 21.5c.55 0 1-.45 1-1v-3.03c0-.55-.45-1-1-1z" />
                                        </svg>
                                        <span>9335171302</span>
                                    </div>
                                    <span class="biz-card-separator">•</span>
                                    <div class="biz-card-phone-number">
                                        <span>9336171302</span>
                                        <svg class="biz-card-icon whatsapp-icon" style="margin-left: 8px; margin-right: 0;"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M19.05 4.95A10 10 0 0 0 12 2C6.48 2 2 6.48 2 12s4.48 10 10 10h1.84l.65-.96c.1-.14.21-.29.35-.45l.95-1.16a1 1 0 0 1 .7-.46h1.56a1 1 0 0 0 .97-.73l.23-.7a1 1 0 0 0-1.01-1.23h-1.55l-1.07 1.3c-.09.1-.19.21-.3.33l-.64.78V12c0-3.31 2.69-6 6-6s6 2.69 6 6v1c0 .55.45 1 1 1s1-.45 1-1V12c0-5.52-4.48-10.05-10-10.05zM12 20a8 8 0 0 1-8-8c0-4.41 3.59-8 8-8s8 3.59 8 8c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4 4 1.79 4 4V12a1 1 0 0 0-2 0v.5a2.5 2.5 0 0 1-5 0V12a2.5 2.5 0 0 1 5 0h2a4.5 4.5 0 0 0-9 0v.5a4.5 4.5 0 0 0 9 0V12h2a6.5 6.5 0 0 1-13 0v-.5a6.5 6.5 0 0 1 13 0z" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="biz-card-web-section">
                                    <!-- Website -->
                                    <a class="biz-card-contact-item biz-card-web-link"
                                        href="http://www.careerwithoutbarrier.com" target="_blank">
                                        <svg class="biz-card-icon globe-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.8-7-7.93 0-.62.08-1.21.21-1.79L11 14v5.93zm5-.72c-1.12-1.35-2.52-2.24-4-2.57V9h.99c2.51 0 4.51-2.01 4.51-4.51-.01-1.06-.35-2.07-.97-2.88.94 1.18 1.54 2.68 1.54 4.39 0 3.86-2.61 7.15-6.23 7.93L15 19.21zM5.53 7.22l.53.53C6.31 6.84 7.08 6.5 7.99 6.5h1V4.07c-2.31.54-4.22 2.22-5.11 4.41l2.65 2.65c.31.31.75.5 1.21.5s.9-.19 1.21-.5l.53-.53V11c0 1.94-1.18 3.65-2.84 4.4l-1.19 1.19C4.19 15.68 4 13.88 4 12c0-2.31.71-4.47 1.92-6.26l1.3-1.3c-.63.92-1.02 1.98-1.02 3.16 0 1.93 1.16 3.65 2.84 4.39.46.23.97.35 1.5.35s1.04-.12 1.5-.35c1.68-.74 2.84-2.46 2.84-4.39 0-1.07-.31-2.06-.85-2.89l-1.25-1.25c.34-.34.69-.67 1.07-.98 1.83 1.26 3.01 3.42 3.01 5.86 0 3.22-2.43 5.95-5.58 6.4v-2.09c0-1.71-1.19-3.21-2.92-3.82l-2.03-2.03c-1.25-1.25-1.95-2.94-1.95-4.71 0-1.89.78-3.6 2.05-4.85l.53-.53C7.43 3.1 9.6 2 12 2c2.4 0 4.57 1.1 5.92 2.85l-.53.53C16.18 4.65 14.88 4 12 4c-1.89 0-3.6.78-4.85 2.05l-.53.53z" />
                                        </svg>
                                        <span>www.careerwithoutbarrier.com</span>
                                    </a>

                                    <!-- Email -->
                                    <a class="biz-card-contact-item biz-card-email-link"
                                        href="mailto:info@careerwithoutbarrier.com" target="_blank">
                                        <svg class="biz-card-icon mail-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                        </svg>
                                        <span>info@careerwithoutbarrier.com</span>
                                    </a>
                                </div>

                            </div>

                            <!-- Footer: Tech Partner -->
                            <div class="biz-card-footer biz-card-section">
                                <p class="biz-card-footer-partner-label">Exam Agency & Tech Partner</p>
                                <p class="biz-card-footer-company">WEBLIES EQUATIONS (PVT.) LTD</p>
                                <p class="biz-card-footer-description">
                                    Education <span class="biz-card-star">★</span> Publication <span
                                        class="biz-card-star">★</span> Consultancy <span class="biz-card-star">★</span>
                                    Tech Solution
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    <script>
        function get_other_reason() {

            var reason_contact = $("#reason_contact").val();
            if (reason_contact == "Other Issues") {
                $("#otherdd").show();
            } else {
                $("#otherdd").hide();

            }

        }
    </script>
