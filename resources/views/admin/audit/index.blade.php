@extends('layout.app')
@section('css')
 <!-- DataTables -->
 <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
 <!-- Responsive datatable examples -->
 <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
               <div class="table-responsive">
                <table cellpadding="0" cellspacing="0" id="datatable" data-url="{{ route('authenticate.audit.list') }}" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:11px">
                    <thead>
                        <tr>
                            <th>ID(s)</th>
                            <th>Referece No</th>
                            <th>User Trigger</th>
                            <th>Event</th>
                            <th>Date & Time</th>
                            <th>IP Address</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Url</th>
                            <th>User Agent</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
               </div>
            </div>
        </div>
    </div>
    @endsection
    
    @section('js')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script>
        let tbl = $("#datatable")
        let tableOpenAmount = tbl.DataTable({
            "serverSide": true,
            // pageLength: 5,
            paging:true,
            "ajax": {
                url: tbl.attr('data-url'), 
                method: "get"
            },
            order: [[0, 'desc']],
            columns:[
                {
                    data: "id",
                    target: 0,
                    visible: false,
                    searchable: false
                },
                { 
                orderable:false,
                data:'auditable_id'
            },
            { 
                orderable:false,
                data:'name'
            },
            { 
                orderable:false,
                data:'event'
            },
            { 
                orderable:false,
                data:'created_at'
            },
            { 
                orderable:false,
                data:'ip_address'
            },
            { 
                orderable:false,
                data:'old_values'
            },
            { 
                orderable:false,
                data:'new_values'
            },
            { 
                orderable:false,
                data:'url'
            },
            { 
                orderable:false,
                data:'user_agent'
            },
            ]
        });
        $("button[name=refreshTable]").on('click',function(){
            tableOpenAmount.ajax.reload()
        })
    </script>
@endsection