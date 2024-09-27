<table>
    <tr>
        <td width="300px" style="font-size:10px">Name :{{$user->name}}</td> 
    </tr>
    <tr>
        <td  style="font-size:10px">Beat Plan Weekly Report</td> 
    </tr>
    <tr>
        <td width="300px" style="font-size:10px">Date: {{ date("m/d/Y", strtotime($monday)) }} - {{ date("m/d/Y", strtotime($saturday)) }}</td> 
    </tr>
</table> 
@for ($i = 0; $i < 6; $i++)
    <?php 
        $date = strtotime("+$i day", strtotime($monday)); 
        $dayName = ucfirst(strftime("%A", $date));
    ?>
    <table > 
            @if ($i!==0)
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                </tr>
            @endif
            <tr>
                <td style="border: 1px solid black; font-size:10px;background-color: #c2c2c2; font-weight:bold" colspan="3">{{ $dayName }} ({{ date("m/d", $date) }})</td>
            </tr>
            <tr>
                <td width="300px" style="border: 1px solid black; font-size:10px">Client/Title</td>
                <td width="500px" style="border: 1px solid black; font-size:10px">Note/Remarks</td>
                <td width="500px" style="border: 1px solid black; font-size:10px">Booking</td>
            </tr> 
            @if (isset($activities[$dayName]) && $activities[$dayName]->count())
                @foreach ($activities[$dayName] as $activity)
                <tr>
                    <td  style="font-size:10px; word-wrap: break-word; border: 1px solid black ">{{ $activity->client }}</td>
                    <td  style="font-size:10px; word-wrap: break-word; border: 1px solid black ">{{ $activity->note ?? 'No remarks' }}</td>
                    <td  style="font-size:10px; word-wrap: break-word; border: 1px solid black ">
                        @if (isset($activity->bookings))
                            <ol style="padding: 8px; margin: 0">
                                @foreach ($activity->bookings as $booking)
                                    <li style="padding: 0;margin: 0 ">{{ $booking->product->name ?? $booking->free_type }} (Qty:{{ $booking->qty }})  Price: {{ $booking->price }}</li>
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
                    <td style="font-size:10px; text-align:center; border: 1px solid black " colspan="3"> No activities </td>
                </tr>
            @endif 
    </table>
@endfor