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
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="GroupForm" action="{{ route('authenticate.group.store') }}" autocomplete="off">@csrf <input type="hidden" class="form-control" name="id">
               <div class="table-responsive">
                <table id="GroupTable" data-url="{{ route('authenticate.group.list') }}" class="table table-bordered table-sm adjust"  style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:13px">
                    <thead>
                            <tr>
                                <th><input type="text" name="name" class="form-control form-control-sm" required> </th>
                                <th>
                                    <select name="active" class="custom-select custom-select-sm form-control" required>
                                        <option value="1">YES</option>
                                        <option value="0">NO</option>
                                    </select>
                                </th>
                                <th width="7%">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block">Save</button>
                                    <button name="cancel" type="button" class="btn btn-warning btn-sm btn-block"> <i class="fas fa-plus-circle"></i> Cancel</button>
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
        let GroupForm  = $("#GroupForm") 
        let GroupTable = $("#GroupTable");
        let GroupDataTable = $("#GroupTable").DataTable({
            "initComplete": function(settings, json) {
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
            },
            serverSide: true,
            paging:true,
            ajax: {
                url: GroupTable.attr("data-url"),
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

        GroupForm.find("button[name=cancel]").on('click',function(){
            GroupForm[0].reset()
            GroupForm.find('input[name=id]').val('')
            $(this).hide()
        }).hide()

        $(document).on('click','button[name=edit]',function(){
            GroupForm.find("button[name=cancel]").show()
            let data = GroupDataTable.row( $(this).closest('tr') ).data()
            $.each($('#GroupForm .form-control'),(ind, value) => {
                if (value.name!="") {
                    GroupForm.find("input[name="+value.name+"]").val(data[value.name]);
                    GroupForm.find("select[name="+value.name+"]").val(data[value.name]);
                }
                // GroupForm.table.find("select[name=description]").append(new Option(data.description, data.description, true, true)).trigger('change');
            });
        })
        
        GroupForm.on('submit',function(e){
            e.preventDefault()
            $.ajax({
                url:  GroupForm.attr("action"),
                type:'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
            }).done(function(data){
                if (data.msg) {
                    GroupForm[0].reset()
                    GroupForm.find('input[name=id]').val('')
                    CoreModel.toasMessage(data.msg,"success",'success')
                    GroupDataTable.ajax.reload()
                    GroupForm.find("button[name=cancel]").hide()
                }
            }).fail(CoreModel.handleAjaxError)
            .always(function() {
                UserForm[0].reset()
                UserForm.find('input[name=id]').val('')
                UserDataTable.ajax.reload()
                UserForm.find("button[name=cancel]").hide()
            });
        })
    </script>
@endsection