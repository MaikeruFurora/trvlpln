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
            border: .01px solid black;
        }
        table.table-bordered > tbody > tr > td {
            border: .01px solid black;
        }
            @media print {
                table.table-bordered > thead > tr > th {
                    border: .01px solid black;
                }
                table.table-bordered > tbody > tr > td {
                    border: .01px solid black;
                }
                table td {
                    font-weight: 640;
                }
                ol > li > p {
                    font-weight: bold;
                }
                small {
                    font-weight: bold;
                }
                h6{
                    font-weight: bold;
                }
                p {
                    font-weight: bold;
                }
                .table thead tr td,.table tbody tr td{
                    border-width: .01px !important;
                    border-style: solid !important;
                    border-color: rgb(69, 69, 69) !important;
                    font-size: 10.5px !important;
                    background-color: red;
                    padding:0px;
                    -webkit-print-color-adjust:exact ;
                }
                .table thead tr td,.table thead tr th{
                    border-width: .01px !important;
                    border-style: solid !important;
                    border-color: rgb(69, 69, 69) !important;
                    font-size: 10.5px !important;
                    background-color: red;
                    padding:0px;
                    -webkit-print-color-adjust:exact ;
                }
            }

            .adjust tr td, .adjust tr th{
                padding: 1px 5px !important;
                margin: 0 !important;
            }
    </style>
</head>
{{-- --}}
<body  onload="window.print();">
    @if ($wrhs=="all")
            {{--  --}}
            @foreach ($reportData as $key => $item)
                    <h6 class="mb-0">{{ strtoupper($key) }}</h6>
                    <p class="mb-0">Date: {{ date("F, d Y",strtotime($start)).' - '.date("F, d Y",strtotime($end)) }}</p>
                        <ol>
                            @foreach ($item as $itemkey => $value)
                            <li style="font-size:15px"><p class="mb-1">{{  strtoupper($itemkey) }}</p></li>
                            <table class="table adjust table-bordered mb-3"  width="100%" style="font-size: 15px">
                            @foreach ($value['activity']->sortBy('date_from') as $dd)
                                    <tr>
                                        <td width="4%" class="text-center">{{ date("m/d/Y",strtotime($dd['date_from'])) }}</td>
                                        <td width="15%">{{ ucwords(strtolower($dd['client'])) }}</td>
                                        {{-- <td width="4%" class="text-center">
                                            @php
                                                $dateFrom = new DateTime($dd['date_from']);
                                                $dateTo = new DateTime($dd['date_to']);
                                                $interval = $dateFrom->diff($dateTo);
                                                $hours = $interval->h + ($interval->days * 24);
                                                $mins = $interval->i;
                                            @endphp
                                            @unless($hours == 0 && $mins == 0)
                                                {{ $hours > 0 ? $hours.'h' : '' }} {{ $mins > 0 ? $mins.'m' : '' }}
                                            @endunless
                                        </td> --}}
                                        <td width="40%">{{ empty($dd['note']) ? '-' : strtoupper($dd['note']) }}</td>
                                        <td width="6%" class="text-center"><em>{{ empty($dd['sttus']) ? 'NO UPDATE' : strtoupper($dd['sttus']) }}</em></td>
                                        <td width="5%" class="text-center">{{ $dd['osnum'] }}</td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <th>TOTAL: {{ $value['activity_count'] }}</th>
                                    </tr>
                            </table>
                        @endforeach
                        <table width="40%" style="font-size: 14px">
                            @php
                                $grandTotal = 0;
                            @endphp
                            @foreach ($item as $itemkey => $value)
                                <tr>
                                    <th width="60%">{{ strtoupper($itemkey) }}</th>
                                    <th width="10%">{{ $value['activity_count'] }}</th>
                                </tr>
                                @php
                                    $grandTotal += $value['activity_count'];
                                @endphp
                            @endforeach
                            <tr>
                                <th colspan="2"><hr></th>
                            </tr>
                            <tr>
                                <th width="60%">GRAND TOTAL</th>
                                <th>{{ $grandTotal }}</th>
                            </tr>
                        </table>
                        </ol>
                @endforeach
            {{--  --}}
    @else
        @foreach ($reportData as $key => $item)
            @php
             $i=0;   
            @endphp
            {{-- <ol> --}}
                <p style="font-size: 11px" class="mb-0">{{ strtoupper($item->name) }}</p>
                <p style="font-size: 11px" class="mb-0">DATE: {{ date("m/d/Y",strtotime($start)) }}</p>
                @forelse ($item->activities->groupBy('activity_list.name') as $key => $value)
                    {{-- <li style="font-size: 11px"><p class="mb-0" >{{$key}}</p></li> --}}
                    <table class="table adjust table-bordered mb-2">
                       <thead>
                        <tr>
                            <th colspan="5">{{ ++$i; }}. {{strtoupper($key)}}</th>
                        </tr>
                        <tr>
                            <th>CLIENT/TASK</th>
                            <th>NOTE</th>
                            <th class="text-center">OSNUM</th>
                            <th class="text-center">STATUS</th>
                        </tr>
                       </thead>
                       <tbody>
                            @foreach ($value as $key => $data)
                                <tr>
                                    <td width="33%">{{ $data->client }}</td>
                                    <td width="52%">{{ $data->note }}</td>
                                    <td width="8%" class="text-center">{{ empty($data->osnum) ? '-' : strtoupper($data->osnum) }}</td>
                                    <td width="6%" class="text-center" style="font-size: 9px">{{ empty($data->sttus) ? 'NO.UPDATE' : strtoupper($data->sttus) }}</td>
                                </tr>
                            @endforeach  
                        </tbody>     
                    </table>    
                @empty
                    No Data
                @endforelse
            {{-- </ol> --}}
        @endforeach
    @endif
  
</body>
</html>

