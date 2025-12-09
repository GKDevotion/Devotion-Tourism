<!DOCTYPE html>
<html>
<head>
    <title>{{date('d-m-Y')}} Upcoming Meeting Reminders</title>
</head>
<body>
    <p>Below is a summary of your today upcoming meetings:</p>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f4f4f4;">Unique ID</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f4f4f4;">Client Name</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f4f4f4;">Meeting Title</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f4f4f4;">Time</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f4f4f4;">Type</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f4f4f4;">Remarks</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f4f4f4;">Discussion About</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f4f4f4;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $statusArr = [
                'De-Active',
                'Active',
                'Hold',
                'On Going',
                'Complete'
            ];
            ?>
            @foreach( $data as $ar )
                <tr>
                    <td>{{$ar->client->unique_id}}</td>
                    <td>{{ $ar->client->first_name.' '.$ar->client->last_name }}</td>
                    <td>{{$ar->title}}</td>
                    <td>{{formatDate("H:i", $ar->follow_up_date )}}</td>
                    <td>{{$ar->communication_type->name}}</td>
                    <td>{{$ar->description}}</td>
                    <td>{{$ar->follow_up_detail}}</td>
                    <td>{{$statusArr[$ar->status]}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Ensure your availability and prepare accordingly.</p>
</body>
</html>