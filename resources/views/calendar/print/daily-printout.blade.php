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
    <title>Daily Printout</title>
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
            body { margin: 0; font-family: 'Poppins', sans-serif; }
        }

        table.table-bordered > thead > tr > th, 
        table.table-bordered > tbody > tr > td {
            border: 1px solid #e0e0e0;
            padding: 8px 12px;
            font-size: 14px;
        }

        th {
            background-color: #f1f3f5;
            font-weight: bold;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            vertical-align: top;
            text-align: left;
        }

        ol {
            padding-left: 15px;
            margin-bottom: 0;
        }

        li {
            font-size: 14px;
            margin-bottom: 5px;
        }

        h6 {
            margin: 0 0 5px 0;
            font-weight: bold;
            font-size: 14px;
        }

        p {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .table-summary th, .table-summary td {
            font-size: 13px;
            background-color: #f9f9f9;
            border: none;
        }

        @media print {
            .table th, .table td {
                background-color: #f7f7f7;
                -webkit-print-color-adjust: exact;
            }
        }
        .adjust tr td, .adjust tr th {
            padding: 5px !important;

        }
    </style>
</head>
<body onload="window.print();">

    <h6>{{ $user->name }}</h6>
    <p class="mb-0">Beat Plan Daily Report</p>
    <p class="mb-0">Date: {{ date("m/d/Y") }}</p>

    <!-- Activities Table -->
    <table class="table table-sm table-bordered mt-1 adjust" >
        <thead>
            <tr>
                <th class="text-left" width="25%">Client/Task</th>
                <th class="text-left">Note/Remarks</th>
                <th class="text-left" width="30%">Booking</th>
            </tr>
        </thead>
        <tbody>
            @if ($activities->count())
                @foreach ($activities as $activity)
                <tr>
                    <td style=" font-size: 13px">{{ $activity->client }}</td>
                    <td style=" font-size: 13px">{{ $activity->note ?? 'No remarks' }}</td>
                    <td style=" font-size: 13px">
                        @if ($activity->bookings->count())
                            <ol>
                                @foreach ($activity->bookings as $booking)
                                    <li style=" font-size: 13px">{{ $booking->product->name ?? $booking->free_type }} (Qty: {{ $booking->qty }}) Price: {{ $booking->price }}</li>
                                @endforeach
                            </ol>
                        @else
                            <span class="text-muted">No bookings</span>
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

    <!-- Summary Table -->
    <div class="col-3 mt-1">
        <h6>Activity Summary</h6>
        <table class="table table-sm table-summary">
            <thead>
                <tr>
                    <th class="text-left">Activity</th>
                    <th class="text-left">Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->count }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-left">Total</th>
                    <th class="text-left">{{ $categories->sum('count') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    
</body>
</html>
