<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['name'] }} Leave Application is {{$data['status']}}</title>
</head>
<body>
    <h1>Your Leave Application is {{$data['status']}}</h1>
    <p>Leave Type: {{ $data['leave_type'] }}</p>
    <p>From: {{ $data['start_date'] }}</p>
    <p>To: {{ $data['end_date'] }}</p>
    <p>Reason: {{ $data['reason'] }}</p>
    <p>Re Mark: {{ $data['re_mark'] }}</p>
    
    <p style="margin: 0px;"><b>Best regards,</b></p>
    <p style="margin: 0px;">Jesna Thomas</p>

    <p style="margin: 0px;">Human Resource Department</p>
    <p style="margin: 0px;">Devotion Group</p>

    <p style="margin: 0px;">502, Iris Bay, Opp JW Marriott Hotel<br>
    Business Bay - Dubai - UAE</p>

    <p style="margin: 0px;">careers@devotiongroup.org<br>
	+971-50 542 0177</p>
</body>
</html>