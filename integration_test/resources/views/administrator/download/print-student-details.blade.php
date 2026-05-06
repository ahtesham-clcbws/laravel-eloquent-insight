<!DOCTYPE html>
<html>

    <head>
        <style>
            body {
                font-family: sans-serif;
            }

            #customers {
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            #customers td,
            #customers th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #customers tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            #customers tr:hover {
                background-color: #ddd;
            }

            #customers th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #04AA6D;
                color: white;
            }
        </style>
    </head>

    <body>
        <table style="width:100%;">
            <tr>
                <td style="width:25%;float:left;">Registration Details</td>
                <td style="width:50%">
                    <center><img
                            src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('/upload/main-logo.jpeg'))) }}"
                            style="width:250px;"></center>
                </td>
                <td style="width:20%;float:right;"><span style="color:red">Printed on <br>
                        {{ date('d/m/Y h:i:s A') }}</span></td>
            </tr>
        </table>

        <br>
        <table id="customers" style="width:95%;">


            <tr>
                <td><b>Name</b></td>
                <td class="information-txt" colspan="3">{{ $student->name }}</td>
                <td>
                    <img class="img-fluid"
                        src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('/storage/' . $student->photograph))) }}"
                        style="width: 100px;border: 1px double #dee2e6;padding: 4px;height: 100px;">
                </td>

            </tr>
            <tr>
                <td><b>Mobile</b></td>
                <td class="information-txt" colspan="4">{{ $student->mobile }}</td>
            </tr>
            <tr>
                <td colspan="3"><b>Email ID</b></td>
                <td class="information-txt" colspan="4">{{ $student->email }}</td>
            </tr>

            <tr>
                <td colspan="2">
                    <b>Qualification</b>
                </td>
                <td colspan="3">
                    @if (!empty($student->qualification))
                        @php($qualification = DB::table('board_agency_exam')->where('id', $student->qualification)->first())
                        {{ $qualification->name }}
                    @else
                        NA
                    @endif
                </td>
            </tr>
            <tr>
                <td><b>Addres s</b></td>
                <td class="information-txt" colspan="4">{{ $student->address }}</td>
            </tr>
            <tr>
                <td class="information-txt"><b>City</b></td>
                <td class="information-txt">

                    @if (!empty($student->district_id))
                        @php($dist = DB::table('districts')->where('id', $student->district_id)->first())
                        {{ $dist->name }}
                    @else
                        NA
                    @endif
                    <br>
                    {{ $student->pincode }}
                </td>
                <td><b>State</b></td>
                <td colspan="2">{{ $student->state?->name }}</td>
            </tr>
            <tr>
                <td><b>Scholarship Category</b></td>
                <td class="information-txt">
                    @if (!empty($student->scholarship_category))
                        @php($scholarship_categ = DB::table('education_type')->where('id', $student->scholarship_category)->first())
                        {{ $scholarship_categ->name }}
                    @else
                        NA
                    @endif
                </td>
                <td> <b>Scholarship Opted For</b></td>
                <td class="information-txt" colspan="2"> {{ $student->scholarShipCategory?->name ?? 'N/A' }} </td>
            </tr>
            <tr>
                <td> <b>Scholarship Opted For</b> </td>
                <td class="information-txt"> {{ $student->scholarShipCategory?->name ?? 'N/A' }}</td>
                <td>
                    <b>Choice of Test Centre (A/B)</b>
                </td>
                <td class="information-txt" colspan="2"><b>A:</b> {{ $student->choiceCenterA?->DistrictName }} /
                    @if ($student->choiceCenterB)
                        <b>B:</b> {{ $student->choiceCenterB?->DistrictName }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <b>Father Occupation</b> &nbsp;&nbsp;&nbsp;
                </td>

                <td>{{ $student->father_occupation }} </td>
                <td><b>Mother Occupation: </b> </td>
                <td colspan="2">{{ $student->mother_occupation }}</td>
            </tr>

            <tr>
                <td colspan="2"><b>Status</b></td>
                <td class="bg-success text-center" colspan="3"> <span class="text-white">Active</span> </td>
            </tr>

        </table>

        </table>

    </body>

</html>
