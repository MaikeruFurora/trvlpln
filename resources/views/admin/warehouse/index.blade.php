@extends('layout.app')
@section('css')
 <!-- DataTables -->
 <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
 <!-- Responsive datatable examples -->
 <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
 <style>
  
.adjust tr td{
    padding: 6px 10px !important;
    margin: 0 !important;
}
</style>
@endsection
@section('content')
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-body">
                <form id="WarehouseForm" action="{{ route('authenticate.warehouse.store') }}" autocomplete="off">@csrf <input type="hidden" class="form-control" name="id">
               <div class="table-responsive">
                <table id="WarehouseTable" data-url="{{ route('authenticate.warehouse.list') }}" class="table table-bordered table-sm adjust"  style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:13px">
                    <thead>
                            <tr>
                                <th><input type="text" name="name" class="form-control form-control-sm" required> </th>
                                <th>
                                    <select name="group" class="custom-select custom-select-sm form-control" required>
                                        <option value="1">YES</option>
                                        <option value="0">NO</option>
                                    </select>
                                </th>
                                <th width="7%">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block">Save</button>
                                    <button name="cancel" type="button" class="btn btn-warning btn-sm btn-block"> <i class="fas fa-plus-circle"></i> Cancel
                                    </button>
                                </th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                    </thead>
                    <tbody></tbody>
                </table>
               </div>
            </form>
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
        let WarehouseForm  = $("#WarehouseForm") 
        let WarehouseTable = $("#WarehouseTable");
        let WarehouseDataTable = $("#WarehouseTable").DataTable({
            "initComplete": function(settings, json) {
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
            },
            serverSide: true,
            paging:true,
            ajax: {
                url: WarehouseTable.attr("data-url"),
                method: "get"
            },
            columns:[
                { data:'name' },
                { data:'active',
                    render:function(data){
                        return data? 'YES' : 'NO'
                    }
                },
                { 
                    data:null,
                    render:function(data){
                        return `<button
                        name="edit"
                        value="${data.id}"
                        type="button"
                        class="btn btn-outline-secondary btn-sm btn-block">
                        <i class="fas fa-plus-circle"></i> Edit
                        </button>
                        `
                    }
                },
            ]
        })

        WarehouseForm.find("button[name=cancel]").on('click',function(){
            WarehouseForm[0].reset()
            WarehouseForm.find('input[name=id]').val('')
            $(this).hide()
        }).hide()

        $(document).on('click','button[name=edit]',function(){
            WarehouseForm.find("button[name=cancel]").show()
            let data = WarehouseDataTable.row( $(this).closest('tr') ).data()
            $.each($('#WarehouseForm .form-control'),(ind, value) => {
                if (value.name!="") {
                    WarehouseForm.find("input[name="+value.name+"]").val(data[value.name]);
                    WarehouseForm.find("select[name="+value.name+"]").val(data[value.name]);
                }
                // WarehouseForm.table.find("select[name=description]").append(new Option(data.description, data.description, true, true)).trigger('change');
            });
        })
        
        WarehouseForm.on('submit',function(e){
            e.preventDefault()
            $.ajax({
                url:  WarehouseForm.attr("action"),
                type:'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
            }).done(function(data){
                if (data.msg) {
                    WarehouseForm[0].reset()
                    WarehouseForm.find('input[name=id]').val('')
                    toasMessage(data.msg,"success",'success')
                    WarehouseDataTable.ajax.reload()
                    WarehouseForm.find("button[name=cancel]").hide()
                }
            }).fail(function (jqxHR, textStatus, errorThrown) {
                toasMessage("Error","Error",'error')
            })
        })
    </script>
@endsection