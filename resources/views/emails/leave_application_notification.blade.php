<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['name'] }} New Leave Application</title>
</head>
<body>
    <h1>New Leave Application</h1>
    <p>{{ $data['name'] }} has applied for a leave.</p>
    <p>Leave Type: {{ $data['leave_type'] }}</p>
    <p>From: {{ $data['start_date'] }}</p>
    <p>To: {{ $data['end_date'] }}</p>
    <p>Reason: {{ $data['reason'] }}</p>

    <p>
        <a href="{{url('admin/leave/'.$data['id'].'/edit')}}">Approve/Reject</a>
    </p>
</body>
</html>