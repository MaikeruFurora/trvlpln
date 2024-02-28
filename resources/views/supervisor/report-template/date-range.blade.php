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
 
    @php 
        $i=$total=$count=0;
    @endphp
   @if ($wrhs=="all")
        @foreach ($reportData as $key => $item)
            <p style="font-size: 11px" class="mb-0">{{ strtoupper($item->name) }}</p>
            <p style="font-size: 11px" class="mb-0">DATE: {{ date("m/d/Y",strtotime($start)) }}</p>
            @forelse ($item->activities->groupBy('activity_list.name') as $key => $value)
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
                {{--  --}}
               
            @empty
                No Data
            @endforelse
            <div class="col-3">
            <table class="mb-3" style="font-size: 12px">
                <tr>
                    <td width="15%">ACTIVITY</td>
                    <td width="10%" class="text-center">COUNT</td>
                </tr>
                @forelse ($item->activities->groupBy('activity_list.name') as $key => $value)
                <tr>
                    <td>{{strtoupper($key)}}</td>
                    <td class="text-center">
                        @php 
                            $count+=count($value);
                            $total+=$count;
                            echo $count;
                        @endphp
                    </td>
                </tr>
                @empty
                @endforelse
                <tr>
                    <td>TOTAL</td>
                    <td class="text-center">{{ $total }}</td>
                </tr>
            </table>
            </div>
        @endforeach
        
    @else
    <p style="font-size: 11px" class="mb-0">{{ strtoupper($user->name) }}</p>
    <p style="font-size: 11px" class="mb-0">DATE: {{ date("m/d/Y",strtotime($start)) }}</p>
        @forelse ($reportData->groupBy('activity_list.name') as $key => $value)
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
                            <td width="20%">{{ $data->client }}</td>
                            <td width="65%">{{ $data->note }}</td>
                            <td width="8%" class="text-center">{{ empty($data->osnum) ? '-' : strtoupper($data->osnum) }}</td>
                            <td width="6%" class="text-center" style="font-size: 9px">{{ empty($data->sttus) ? 'NO.UPDATE' : strtoupper($data->sttus) }}</td>
                        </tr>
                    @endforeach  
                </tbody>     
            </table>    
        @empty
            No Data
        @endforelse
       <div class="col-3">
        <table class="adjust" style="font-size: 12px">
            <tr>
                <td width="15%">ACTIVITY</td>
                <td width="10%" class="text-center">COUNT</td>
            </tr>
            @forelse ($reportData->groupBy('activity_list.name') as $key => $value)
            <tr>
                <td>{{strtoupper($key)}}</td>
                <td class="text-center">
                    @php 
                        $count+=count($value);
                        $total+=$count;
                        echo $count;
                    @endphp
                </td>
            </tr>
            @empty
            @endforelse
            <tr>
                <td>TOTAL</td>
                <td class="text-center">{{ $total }}</td>
            </tr>
        </table>
       </div>
    @endif

  
</body>
</html>

