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
            width: 99%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 5px;
        }


        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #d2cfce;
            color: black;
        }
    </style>
</head>

<body>
    <table style="width:100%;">
        <tr>
            <td style="width:25%;float:left;">Student List</td>
            <td style="width:50%">
                <center><img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('/upload/main-logo.jpeg'))) }}" style="width:250px;"></center>
            </td>
            <td style="width:20%;float:right;"><span style="color:red">Printed on <br> {{date('d/m/Y h:i:s A')}}</span></td>
        </tr>
    </table>

    <br>
    <table id="customers" style="width:95%;">
        <tr>
            <th>Sr.No</th>
            <th>Student Name</th>
            <th>Email/Mobile</th>
            <th>DOB</th>
            <th>Application Code</th>
            <th>Qualification</th>
            <th>Scholarship Category</th>
            <th>Scholarship Opted For</th>
        </tr>
        @if(isset($students) && !empty($students))
        @foreach($students as $student)
        <tr>
            <?php
            $studCode = $student->latestStudentCode;

            ?>
            <th scope="row">{{$loop->index + 1}}</th>
            <td>{{ $student->name }}<br>
                <span style="color:red">{{ $student->gender }}</span><br>
                <span style="color:red">{{ $student->district?->name }}</span><br>
            </td>
            <td>{{ $student->email }}<br />
                {{ $student->mobile }}<br />
                {{ $student->login_password }}
            </td>
            <td>{{ $student->dob }}</td>
            <td>{{$studCode?->application_code ? $studCode?->application_code : 'NA'}}<br>
                @if(!empty($studCode?->roll_no))
                <span style="color:red;">R.No:{{ $studCode?->roll_no }} </span>
                @endif
            </td>
            <td>{{ $student->qualifications?->name }}</td>
            <td>{{ $student->scholarShipCategory?->name ?? 'N/A' }}</td>
            <td>{{ $student->scholarShipOptedFor?->name ?? 'N/A' }}</td>

        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="9">
                <center>No data available at this moment...</center>
            </td>
        </tr>
        @endif

    </table>

</body>

</html>