<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <title>Document</title>
    <style>
        @media print {
            @page {
                size: landscape;
            }
        }
        @page {
            margin: 5mm 4mm
        }

        @media print {
            thead {display: table-header-group;} 
            tfoot {display: table-footer-group;}
            
            button {display: none;}
            
            body {margin: 0;}
        }
    </style>
    <script>
        function printPage() {
            
        }
    </script>
</head>
<body onload="window.print();">
    @foreach ($reportData as $key => $item)
        <h6 class="mb-0">{{ $key }}</h6>
        <small class="mb-0">Date: {{ date("F, d Y",strtotime($start)).' - '.date("F, d Y",strtotime($end)) }}</small>
            <ol>
                @foreach ($item as $itemkey => $value)
                <li style="font-size:12px"><p class="mb-0">{{ $itemkey }}</p></li>
                <table class="tabl table-bordered"  width="100%" style="font-size: 11px">
                   @foreach ($value['activity'] as $dd)
                       <tr>
                           <td width="25%">{{ $dd['client'] }}</td>
                           <td>{{ $dd['note'] }}</td>
                           <td width="5%"><em>{{ empty($dd['date']) ? 'NO UPDATE' : date("d-m-Y",strtotime($dd['date'])) }}</em></td>
                           <td width="8%">{{ $dd['osnum'] }}</td>
                       </tr>
                   @endforeach
               </table>
               @endforeach
            </ol>
    @endforeach
</body>
</html>

