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
            <td style="width:25%;float:left;">New Institute <br>Enquiry</td>
            <td style="width:50%">
                <center><img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('/upload/main-logo.jpeg'))) }}" style="width:250px;"></center>
            </td>
            <td style="width:20%;float:right;"><span style="color:red">Printed on <br> {{date('d/m/Y h:i:s A')}}</span></td>
        </tr>
    </table>

    <br>
    <table id="customers" style="width:95%;">
        <tr>
            <th>Sr.No.</th>
            <th>Photo</th>
            <th>Name</th>
            <th>Inst.Name & City</th>
            <th>Email & Mobile</th>
            <th>Interested For</th>
            <th>Estd. Year</th>
            <th>Enquiry Date</th>
            <th>Note</th>
        </tr>
        @if(isset($institute) && !empty($institute))
        @foreach ($institute as $institutes)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                @if(file_exists(public_path('/storage/'.$institutes->attachment)))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/storage/'.$institutes->attachment))) }}" style="width:50px;height:50px;border:1px solid #c2c2c2;border-radius:5px;">
                @else
                <img src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('no_image_available.jpg'))) }}" style="width:50px;height:50px;border:1px solid #c2c2c2;border-radius:5px;">
                @endif
            </td>
            <td>{{ $institutes->name }}</td>
            <td>{{ $institutes->institute_name }} <br><span style="color:blue">{{ $institutes->city }}</td>
            <td>{{ $institutes->email }} <br> {{ $institutes->phone }}</td>
            <td>{{ $institutes->interested_for }}</td>
            <td>{{ $institutes->established_year }}</td>
            <td>{{ date('d/m/Y h:i:s A', strtotime($institutes->created_at)) }}</td>
            <td style="width:40px;"></td>

        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="8">
                <center>No data available at this moment...</center>
            </td>
        </tr>
        @endif

    </table>

</body>

</html>