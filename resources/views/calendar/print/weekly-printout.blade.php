<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <title>Weekly Printout</title>
    <style>
        @media print {
            @page {
                size: landscape;
                size: 13in 8.5in;
                margin: 6mm 6mm;
            }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
            button { display: none; }
            body { margin: 0; font-family: 'poppins', sans-serif; }
        }

        table.table-bordered > thead > tr > th, 
        table.table-bordered > tbody > tr > td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            font-size: 14px;
        }

        th {
            background-color: #f7f7f7;
            font-weight: bold;
            text-align: center;
        }

        td {
            vertical-align: top;
            text-align: left;
        }

        ol {
            padding-left: 15px;
        }

        li {
            font-size: 14px;
            margin-bottom: 5px;
        }

        h6 {
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        @media print {
            .table th, .table td {
                background-color: #f7f7f7;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body onload="window.print();">

    <h6>{{ $user->name }}</h6>
    <p style="font-size: 11px" class="mb-0">Beat Plan Weekly Report</p>
    <p style="font-size: 11px" class="mb-0">Date: {{ date("m/d/Y", strtotime($monday)) }} - {{ date("m/d/Y", strtotime($saturday)) }}</p>

    @for ($i = 0; $i < 6; $i++)
        <?php 
            $date = strtotime("+$i day", strtotime($monday)); 
            $dayName = ucfirst(strftime("%A", $date));
        ?>
        <h6 style="font-size: 13px" class="mt-4">{{ $dayName }} ({{ date("m/d", $date) }})</h6>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-left" width="20%">Client/Task</th>
                    <th class="text-left">Note/Remarks</th>
                    <th class="text-left" width="30%">Booking</th>
                </tr>
            </thead>
            <tbody>
                @if (isset($activities[$dayName]) && $activities[$dayName]->count())
                    @foreach ($activities[$dayName] as $activity)
                    <tr>
                        <td>{{ $activity->client }}</td>
                        <td>{{ $activity->note ?? 'No remarks' }}</td>
                        <td>
                            @if (isset($activity->bookings))
                                <ol style="padding: 8px; margin: 0">
                                    @foreach ($activity->bookings as $booking)
                                        <li style="padding: 0;margin: 0 ">{{ $booking->product->name ?? $booking->free_type }} (Qty:{{ $booking->qty }})  Price: {{ $booking->price }}</li>
                                    @endforeach
                                </ol>
                            @else
                                
                            @endif
                            
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="text-center"><span class="text-muted">No activities</span></td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endfor
    
</body>
</html>
