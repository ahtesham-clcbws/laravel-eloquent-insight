@extends('administrator.layouts.master')
@section('title')
Student Admit Card
@endsection

@section('content')

<?php

use Illuminate\Support\Carbon;
?>
<div class="container pagecontentbody pt-5 pb-3">
    <div class="main" id="prodiv">
    <link rel="stylesheet" href="{{ asset('student/admin_card.css') }}">

        <center>
            <table style="border-collapse:collapse;margin-left:6.3494pt" cellspacing="0">

                <tr>
                    <td colspan="5" style="text-align: center;">
                        <div class="logo"> <a href="#"><img width="180" src="{{ asset('website/assets/images/brand/logo.png') }}" alt="logo"></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <div class="logo" style="margin-bottom: 40px;"> <a href="#"><img width="120" src="{{ asset('website/assets/images/bottom-menu/sqs-logo.png') }}" alt="img">
                            </a>
                        </div>
                    </td>
                    <td colspan="3">
                        <div style="margin-top: 15px;margin-bottom: 15px;text-align:center">
                            <h3 style="color:#1015cd;font-weight: 900;font-size: large;">CAREER PREP SCHOLARSHIP EXAM</h3>
                            <h5 style="color:#00a64b">Admit Card- 2024 (Phase-1)</h5>
                        </div>
                    </td>
                    <td>
                        <div class="logo" style="    margin-bottom: 40px;"> <a href="#"><img width="120" src="{{ asset('student/test_notes_logo.png') }}" alt="logo"></a>
                        </div>
                    </td>
                </tr>

                <tr style="height:17pt">
                    <td style="width:430pt;" class="tb1" colspan="4" bgcolor="#FFF200">
                        <p class="s9" style="padding-top: 3pt;padding-left: 181pt;text-indent: 0pt;text-align: left;">
                            Candidate &amp; Exam Detail</p>
                    </td>
                    <td style="width:105pt;" class="tb1" bgcolor="#FFF200">
                        <p class="s9" style="padding-top: 3pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">
                            Candidate Photo</p>
                    </td>
                </tr>
                <tr style="height:27pt">
                    <td style="width:93pt;" class="tb1">
                        <p style="padding-top: 1pt;text-indent: 0pt;text-align: left;"><br /></p>
                        <p class="s10 sansita" style="padding-left: 12pt;text-indent: 0pt;text-align: left;">Candidate’s Name</p>
                    </td>
                    <td style="width:129pt;" class="tb1">
                        <p style="padding-top: 1pt;text-indent: 0pt;text-align: left;"><br /></p>
                        <p class="s10 sansita-regular" style="padding-left: 10pt;text-indent: 0pt;text-align: left;">{{ucfirst($student->name)}}</p>
                    </td>
                    <td style="width:75pt;" class="tb1">
                        <p class="s10" style="padding-top: 9pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">Roll No
                        </p>
                    </td>
                    <td style="width:133pt;" class="tb1">
                        <p class="s11" style="padding-top: 9pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                            {{$student->student_roll_number ?? 'NA'}}
                        </p>
                    </td>
                    <td style="width:105pt;" class="tb1" rowspan="5">
                        <p style="text-indent: 0pt;text-align: left;"><br /></p>
                        <p style="padding-left: 13pt;text-indent: 0pt;text-align: left;"><span>
                                <img src="/storage/{{$student->photograph}}" class="img-fluid" width="105" height="139">

                            </span></p>
                    </td>
                </tr>
                <tr style="height:25pt">
                    <td style="width:93pt;" class="tb1">
                        <p class="s10 sansita" style="padding-top: 10pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                            Father’s Name</p>
                    </td>
                    <td style="width:129pt;" class="tb1">
                        <p class="s10 sansita" style="padding-top: 10pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">{{$student->father_name ?? 'NA'}}</p>
                    </td>
                    <td style="width:75pt;" class="tb1">
                        <p class="s10 sansita" style="padding-top: 7pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                            Application No</p>
                    </td>
                    <td style="width:133pt;" class="tb1">
                        <p class="s10 sansita" style="padding-top: 7pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">{{$appCode->application_code ?? 'NA'}}</p>
                    </td>
                </tr>
                <tr style="height:24pt">
                    <td style="width:93pt;" class="tb1">
                        <p class="s10 sansita" style="padding-top: 9pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">Date of Birth</p>
                    </td>
                    <td style="width:129pt;" class="tb1">
                        <p class="s10 sansita" style="padding-top: 9pt;padding-left: 10pt;text-indent: 0pt;text-align: left;">{{Carbon::parse($student->dob)->format('d/m/Y')}} </p>
                    </td>
                    <td style="width:75pt;" class="tb1">
                        <p class="s10 sansita" style="padding-top: 7pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">Exam Category</p>
                    </td>
                    <td style="width:133pt;" class="tb1">
                        <p class="s10 sansita" style="padding-top: 7pt;padding-left: 10pt;text-indent: 0pt;text-align: left;"> {{$student->scholarShipCategory?->name ?? 'NA'}}</p>
                    </td>
                </tr>
                <tr style="height:25pt">
                    <td style="width:93pt;" class="tb1">
                        <p class="s10" style="padding-top: 9pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                            Gender</p>
                    </td>
                    <td style="width:129pt;" class="tb1">
                        <p class="s10" style="padding-top: 9pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">{{$student->gender}}</p>
                    </td>
                    <td style="width:75pt;" class="tb1">
                        <p class="s10" style="padding-top: 8pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                            Preparation For</p>
                    </td>
                    <td style="width:133pt;" class="tb1">
                        <p class="s10" style="padding-top: 8pt;padding-left: 13pt;text-indent: 0pt;text-align: left;">{{$student->scholarShipOptedFor?->name ?? 'NA'}}</p>
                    </td>
                </tr>
                <tr style="height:23pt">
                    <td style="width:93pt;" class="tb1">
                        <p class="s10" style="padding-top: 8pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                            Person with Disability</p>
                    </td>
                    <td style="width:129pt;" class="tb1" colspan="3">
                        <p class="s10" style="padding-top: 8pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">{{$student->disability ?? 'NA'}}
                        </p>
                    </td>
                    <!-- <td style="width:75pt;" class="tb1">
                        <p class="s10" style="padding-top: 8pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                            Specific Category</p>
                    </td>
                    <td style="width:133pt" class="tb1">
                        <p class="s10" style="padding-top: 8pt;padding-left: 14pt;text-indent: 0pt;text-align: left;">VRC/
                            GEP/ EWF/ <span class="s11">CWBP</span>/ CRC/ <span class="s11">GNC</span></p>
                    </td> -->
                </tr>
                <tr style="height:17pt">
                    <td style="width:430pt;" class="tb1" colspan="4" bgcolor="#FFF200">
                        <p class="s9" style="padding-top: 4pt;padding-left: 158pt;text-indent: 0pt;text-align: left;">Test
                            Centre/ Test Date &amp; Test Time Detals</p>
                    </td>
                    <td style="width:105pt;" class="tb1" bgcolor="#FFF200">
                        <p class="s9" style="padding-top: 4pt;padding-left: 1pt;text-indent: 0pt;text-align: center;">
                            Candidate’s Signature</p>
                    </td>
                </tr>
                <tr style="height:24pt">
                    <td style="width:93pt;" class="tb1">
                        <p class="s10" style="padding-top: 6pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">Test
                            Date</p>
                    </td>
                    <td style="width:129pt;" class="tb1">
                        <p class="s10" style="padding-top: 6pt;padding-left: 13pt;text-indent: 0pt;text-align: left;">
                            {{ $appCode?->exam_at ? $appCode?->exam_at->format('jS-m-Y') : '' }}</p>
                    </td>
                    <td style="width:75pt;" class="tb1">
                        <p class="s10" style="padding-top: 6pt;padding-left: 10pt;text-indent: 0pt;text-align: left;">Test
                            Timing</p>
                    </td>
                    <td style="width:133pt;" class="tb1">
                        <p class="s10" style="padding-top: 6pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                        {{ ($appCode?->exam_at && $appCode?->exam_mins) ? getExamTime($appCode?->exam_at, $appCode?->exam_mins) : '' }}</p>
                    </td>
                    <td style="width:105pt;" class="tb1" rowspan="3">
                        <center><img src="/storage/{{$student->signature}}" style="width:140px;margin-top:5px">
                        </center>
                    </td>
                </tr>
                <tr style="height:25pt">
                    <td style="width:93pt;" class="tb1">
                        <p class="s10" style="padding-top: 9pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">Test
                            Centre</p>
                    </td>
                    <td style="width:337pt;" class="tb1" colspan="3">
                        <p class="s10" style="padding-top: 9pt;padding-left: 14pt;text-indent: 0pt;text-align: left;">
                         {{$appCode?->exam_center ? $appCode?->examCenter?->center_name : '' }}</p>
                    </td>
                </tr>
                <tr style="height:25pt">
                    <td style="width:93pt;" class="tb1">
                        <p class="s10" style="padding-top: 10pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                           City/ State</p>
                    </td>
                    <td style="width:129pt;" class="tb1">
                        <p class="s10" style="padding-top: 10pt;padding-left: 12pt;text-indent: 0pt;text-align: left;">
                        @if($appCode?->examCenter)
                        {{ $appCode?->examCenter?->city?->name }} /    {{ $appCode?->examCenter?->state?->name }}
                    @endif</p>
                    </td>
                    <td style="width:75pt;" class="tb1">
                        <p class="s10" style="padding-top: 10pt;padding-left: 14pt;text-indent: 0pt;text-align: left;">
                            {{$appCode?->examCenter?->landmark}}</p>
                    </td>
                    <td style="width:133pt;" class="tb1">
                        <p class="s10" style="padding-top: 10pt;padding-left: 11pt;text-indent: 0pt;text-align: left;">
                        {{$appCode?->examCenter?->address}} {{$appCode?->examCenter?->pincode}}</p>
                    </td>
                </tr>
            </table>
            <p style="padding-top: 3pt;text-indent: 0pt;text-align: left;"><br /></p>
            <table style="border-collapse:collapse;margin-left:6.3494pt" cellspacing="0">
                <tr style="height:17pt">
                    <td style="width:535pt;" class="tb1" colspan="3" bgcolor="#FFF200">
                        <p class="s9" style="padding-top: 3pt;padding-right: 11pt;text-indent: 0pt;text-align: center;">
                            Test Center Authorities Purpose Only</p>
                    </td>
                </tr>
                <tr style="height:43pt">
                    <td style="width:93pt;" class="tb1">
                        <p style="text-indent: 0pt;text-align: left;"><br /></p>
                    </td>
                    <td style="width:337pt;" class="tb1">
                        <p style="padding-top: 5pt;text-indent: 0pt;text-align: left;"><br /></p>
                        <p class="s13" style="padding-left: 8pt;text-indent: 0pt;text-align: center;">Stamp &amp; Signature
                        </p>
                    </td>
                    <td style="width:105pt;" class="tb1">
                        <p style="text-indent: 0pt;text-align: left;"><br /></p>
                    </td>
                </tr>
                <tr style="height:23pt">
                    <td style="width:93pt;" class="tb1">
                        <p class="s10" style="padding-top: 6pt;padding-left: 17pt;text-indent: 0pt;text-align: left;">21st
                            July 2024</p>
                    </td>
                    <td style="width:337pt;" class="tb1">
                        <p class="s10" style="padding-top: 6pt;padding-left: 8pt;text-indent: 0pt;text-align: center;">
                            US Fake Address in New York Generator provide random United States-201006</p>
                    </td>
                    <td style="width:105pt;" class="tb1">
                        <p class="s10" style="padding-top: 5pt;padding-left: 15pt;text-indent: 0pt;text-align: left;">
                            Authorised Signatory</p>
                    </td>
                </tr>
            </table>
            </span></p>
            <p>Test Center Location Or Any Other Exam/ Test Relaated Assistance</p>
            <h3 style="padding-top: 3pt;padding-left: 24pt;text-indent: 0pt;text-align: center;">Kindly Call Or Whatsapp -
                <span class="s14"> </span>9999999999 <span class="s15">Q </span>8998989899
            </h3>
            <p style="padding-top: 4pt;text-indent: 0pt;text-align: left;"><br></p>
            <div class="textbox" style="border:0.5pt solid #231F20;display:block;min-height:81.2pt;width:535.1pt;">
                <p class="s16" style="padding-left: 23pt;text-indent: 0pt;text-align: center;">अित आवयक सचना</p>
                <p class="s17" style="padding-top: 5pt;padding-left: 23pt;text-indent: 0pt;line-height: 123%;text-align: center;">Filler text is text that shares some characteristics of a real written text, but is random or otherwise generated. It may be used to display a sample of fonts, generate text for testing, or to spoof an e-mail spam filter.<br><b>भराव पाठ वह पाठ है जो वास्तविक लिखित पाठ की कुछ विशेषताओं को साझा करता है, लेकिन यादृच्छिक या अन्यथा उत्पन्न होता है। इसका उपयोग फोंट का एक नमूना प्रदर्शित करने, परीक्षण के लिए पाठ उत्पन्न करने या ई-मेल स्पैम फ़िल्टर को धोखा देने के लिए किया जा सकता है</b></p>

            </div>
            <p style="padding-top: 4pt;text-indent: 0pt;text-align: left;"></p>
            <p class="s18" style="padding-left: 24pt;text-indent: 0pt;text-align: center;">Important Instructions For
                Candidates</p>
            <p style="padding-top: 4pt;text-indent: 0pt;"></p>
            <ul class="points">
                <li>Ensure your admit card is printed clearly and legibly.</li>
                <li>Check all personal details such as name, photograph, signature, and exam details for accuracy.</li>
                <li>Verify the exam date, time, and venue mentioned on the admit card.</li>
                <li>Read all instructions on the admit card carefully and follow them during the exam.</li>
                <li>Carry a valid photo ID proof (as mentioned in the instructions) along with the admit card to the exam center.</li>
                <li>Reach the exam center well before the reporting time mentioned on the admit card.</li>
                <li>Do not carry any prohibited items such as electronic gadgets, study material, or valuables to the exam hall.</li>
                <li>Follow the dress code, if any, mentioned on the admit card.</li>
                <li>Do not tamper with or alter any information on the admit card.</li>
                <li>Contact the exam authorities immediately in case of any discrepancies or issues with the admit card.</li>
                <li>Keep the admit card safe even after the exam, as it may be required during further stages of the selection process.</li>
            </ul>
    </div>
    </center>
    <div align="center" class="col-md-12 mt-2">
        <button type="button" style="width: 6rem;height: 2.65rem;" class="btn btn-md btn-info" data-print="modal" onclick="PrintDoc()"> Print<i class="fa fa-print" style="margin-left: 7px;"></i> </button>
        <!-- <i class="fa fa-print"></i>  -->
    </div>
</div>
</div>

<script>
    function PrintDoc() {
        var toPrint = document.getElementById('prodiv');

        var popupWin = window.open('', '_blank', 'left=100,top=100,width=1100,height=600,tollbar=0,scrollbars=1,status=0,resizable=1');

        popupWin.document.open();

        popupWin.document.write('<html><title>Admit Card</title><head><style>body{font-family:Arial} .noprint{display: none;} .table{width:100%; border-collapse:collapse;}h1{font-size:15pt;} .table tr th, .table tr td{border:1px solid #000; padding:2px 5px; font-size: 8.7pt;} .bsvtbl tbody tr td{border:1px solid #000; padding:8px 5px; font-size: 10pt;} .photo{text-align: center;} .photo img {width: 115px;}</style></head><body onload="window.print()">')

        popupWin.document.write(toPrint.innerHTML);

        popupWin.document.close();
    }
</script>
@endsection('content')