<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta charset="UTF-8" />
        <title>{{ isset($title) ? $title : 'CAREER WITHOUT BARRIER' }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">

        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', Arial, sans-serif;
                background: rgb(231 231 231);
            }

            .main-card {
                overflow: hidden;
                max-width: 600px;
                margin: 0 auto;
                margin-top: 80px;
                background: #fff;
                border-radius: 5px;
                box-shadow: 0 2px 16px rgba(180, 196, 222, 0.11);
                box-sizing: border-box;
                /* text-align: center; */
            }

            .content {
                padding: 36px 24px 24px 24px;
            }

            h1 {
                margin: 0 0 14px 0;
                font-size: 2rem;
                font-weight: 400;
                text-align: center;
            }

            .button {
                display: inline-block;
                background: #22cfae;
                color: #fff;
                text-decoration: none;
                font-size: 1.08rem;
                font-weight: 500;
                border-radius: 6px;
                padding: 12px 36px;
                margin-bottom: 22px;
                box-shadow: 0 2px 5px rgba(34, 207, 207, 0.09);
                transition: background 0.2s;
            }

            .link {
                color: #22cfae;
                text-decoration: underline;
            }

            .footer {
                width: 100%;
                padding: 18px 0;
                text-align: center;
                background: #f3f3f3;
            }

            /* text only styles */
            p {
                font-size: 1rem;
                line-height: 1.7;
                color: #222;
            }

            .text-sm {
                font-size: 0.98rem;
            }

            .text-xs {
                font-size: 0.92rem;
            }

            .text-light {
                color: #666 !important;
            }

            .text-low {
                color: #888 !important;
            }


            @media (max-width: 500px) {
                body>div {
                    max-width: 95vw !important;
                    /* padding: 24px 8vw 14px 8vw !important; */
                }
            }
        </style>
    </head>

    <body>

        <div class="main-card">
            <div style="display: flex; justify-content: center; align-items: center; overflow: hidden;">
                <img class="logo" src="{{ url('/website/assets/images/brand/logo.png') }}"
                    style="width: 100%; height: auto; max-width: 350px;" />
            </div>
            <div class="content">
                {!! isset($welcome) ? '<h1>' . $welcome . '</h1>' : '' !!}

                {{ $slot }}
                {{-- <p>
                    Thank you for signing up with <b>TheAdmin!</b> We hope you enjoy your time with us. Check your
                    account
                    and update your profile.
                </p>
                <a class="button" href="#">
                    My Account
                </a>
                <p class="text-light text-sm">
                    If you have any questions, just reply to this email— we're always happy to help out.
                </p> --}}
                <p class="text-low text-xs">
                    Regards:<br />
                    M. Ravee (Founder)<br />
                    SQS Foundation<br />
                    Kanpur
                </p>
                <p class="text-low text-xs">
                    WhatsApp: <a href="tel:+919336171302">9336171302</a> (11am-07pm)<br />
                    E-mail: <a href="mailto:info@careerwithoutbarrier.com">info@careerwithoutbarrier.com</a><br />
                    Website: <a href="https://www.careerwithoutbarrier.com">www.careerwithoutbarrier.com</a>
                </p>
            </div>
            <div class="footer">
                <span class="text-light text-sm">
                    Need more help?<br />
                    <a class="link" href="{{ url('/contact') }}" target="_blank">We're here, ready
                        to talk</a>
                </span>
            </div>
        </div>
        <div class="text-light" style="font-size:12px; text-align: center; margin:10px; font-weight: 700;">Note: This is
            system generated mail so you don't need to reply this e-mail</div>

    </body>

</html>
