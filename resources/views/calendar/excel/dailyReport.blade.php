 
<table style="table-layout: fixed; width: 100%;">
    <tr>
        <td width="50px" style="font-size:10px">Name</td>
        <td width="300px" style="font-size:10px">{{ $user->name }}</td>
    </tr>
    <tr>
        <td width="30px" style="font-size:10px">Date</td>
        <td width="300px" style="font-size:10px">{{ $date->format('m/d/Y') }}</td>
    </tr>
    <tr>
        <td colspan="5"></td>
    </tr>
    <tr>
        <td width="50px" style="border: 1px solid black;background-color: #c2c2c2; font-weight:bold font-size:10px">No.</td>
        <td width="300px" style="border: 1px solid black;background-color: #c2c2c2; font-weight:bold font-size:10px">Client/Task</td>
        <td width="100px" style="border: 1px solid black;background-color: #c2c2c2; font-weight:bold font-size:10px">Activity</td>
        <td width="600px" style="border: 1px solid black;background-color: #c2c2c2; font-weight:bold font-size:10px">Note/Remarks</td>
        <td width="400px" style="border: 1px solid black;background-color: #c2c2c2; font-weight:bold font-size:10px">Booking</td>
    </tr>
        @if ($activities->count())
            @foreach ($activities as $key => $activity)
            <tr>
                <td width="50px" style="border: 1px solid black; word-wrap: break-word; font-size:10px">{{ ++$key }}</td>
                <td width="300px" style="border: 1px solid black; font-size:10px">{{ $activity->client }}</td>
                <td width="100px" style="border: 1px solid black; font-size:10px">{{ $activity->activity_list->name }}</td>
                <td width="600px" style="border: 1px solid black; word-wrap: break-word; font-size:10px">{{ $activity->note ?? 'No remarks' }}</td>
                <td width="400px" style="border: 1px solid black; word-wrap: break-word; font-size:10px">
                    @if ($activity->bookings->count())
                        <ol style="margin: 0; padding-left: 15px;">
                            @foreach ($activity->bookings as $booking)
                                <li style="font-size: 13px; word-wrap: break-word; overflow-wrap: break-word;">{{ $booking->product->name ?? $booking->free_type }} (Qty: {{ $booking->qty }}) Price: {{ $booking->price }}</li>
                            @endforeach
                        </ol>
                    @else 
                    -
                    @endif
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3" class="text-center"><span class="text-muted">No activities</span></td>
            </tr>
        @endif 
</table>
