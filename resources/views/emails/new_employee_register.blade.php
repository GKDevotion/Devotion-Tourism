<!DOCTYPE html>
<html>
<head>
    <title>Welcome to {{$data['name']}} Complete Your Registration</title>
</head>
<body>
    <h1>Dear, {{ $data['name'] }}</h1>

    <p>Welcome to {{ $data['company_name'] }}! We are excited to have you join our team and look forward to working together. To get started, please complete your employee registration by following the link below:</p>

    <p>Complete Your Registration <a href="{{$data['register_link']}}" title="{{$data['name']}}">here</a></p>

    This link will guide you through the necessary steps to complete your onboarding process, including providing your personal details and setting up your account.

    <b>Please complete your registration by your end.</b> If you have any questions or need assistance, feel free to reach out to [Contact Person or HR Department] at [+971 50 542 0177 or careers@devotiongroup.org].

    Once again, welcome aboard! We're thrilled to have you as part of the team.

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