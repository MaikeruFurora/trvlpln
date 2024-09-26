<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <title>Report (Date Range)</title>
    <style>
        @media print {
            @page {
                size: landscape;
                size: 13in 8.5in;
            }
        }
        @page {
            margin: 6mm 6mm
        }

        @media print {
            thead {display: table-header-group;} 
            tfoot {display: table-footer-group;}
            button {display: none;}
            body {margin: 0;}
        }
        table.table-bordered > thead > tr > th {
            border: 1px solid black;
        }
        table.table-bordered > tbody > tr > td {
            border: 1px solid black;
        }
        table.table-bordered > tfoot > tr > th {
            border: 1px solid black;
        }
        @media print {
            table.table-bordered > thead > tr > th {
                border: 1px solid black;
            }
            table.table-bordered > tbody > tr > td {
                border: 1px solid black;
            }
            .table thead tr th, .table tbody tr td {
                font-size: 13px !important;
                padding: 2px;
            }
            table.table-bordered > tfoot > tr > th {
                border: 1px solid black;
            }
        }

        .adjust tr th {
            padding: 5px !important;
        }
    </style>
</head>
<body onload="window.print();">

    <h6 class="mb-0">{{ $user->name }}</h6>
    <p>Date: {{ date('m/d/Y', strtotime($monday)) }} - {{ date('m/d/Y', strtotime($saturday)) }}</p>

    <table class="table table-bordered table-sm adjust">
        <thead>
            <tr>
                @for ($i = 0; $i < 6; $i++)
                    <?php
                        $date = strtotime("+$i day", strtotime($monday));
                    ?>
                    <th width="16%">{{ ucfirst(strftime('%A', $date)) }} ({{ date('m/d', $date) }})</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            <tr>
                @for ($i = 0; $i < 6; $i++)
                    <?php
                        $dayName = ucfirst(strftime('%A', strtotime("+$i day", strtotime($monday))));
                    ?>
                    <td>
                        @if (isset($activities[$dayName]) && $activities[$dayName]->count())
                            <ol style="margin-left: -20px" type="1">
                                @foreach ($activities[$dayName] as $activity)
                                    <li>{{ $activity->client }}</li>
                                    
                                @endforeach
                            </ol>
                        @else
                            No activities
                        @endif
                    </td>
                @endfor
            </tr>
        </tbody>
        <tfoot>
            <tr>
                @for ($i = 0; $i < 6; $i++)
                    <?php
                        $dayName = ucfirst(strftime('%A', strtotime("+$i day", strtotime($monday))));
                        $activityCount = isset($activities[$dayName]) ? $activities[$dayName]->count() : 0;
                    ?>
                    <th style="font-size: 13px">Count: {{ $activityCount }}</th>
                @endfor
            </tr>
        </tfoot>
    </table>

</body>
</html>
