<!DOCTYPE html>
<html>
<head>
    <title>Client Meeting Reminder Notification</title>
</head>
<body>
    <h1>Dear, {{ $data['name'] }}</h1>
    {!!$data['table']!!}
    <p>Check for more details <a href="{{$data['link']}}" title="{{$data['name']}}">here</a></p>
</body>
</html>