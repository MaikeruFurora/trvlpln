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
                <form id="GroupForm" action="{{ route('authenticate.user.group.store') }}" autocomplete="off">@csrf <input type="hidden" class="form-control" name="id">
               <div class="table-responsive">
                <table id="GroupTable" data-url="{{ route('authenticate.user.group.list') }}" data-urlRemove="{{ route('authenticate.user.group.destroy',['param']) }}" class="table table-bordered table-sm adjust"  style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:13px">
                    <thead>
                            <tr>
                                <th>
                                    <select name="handle_group_id" class="custom-select custom-select-sm form-control" required>
                                        <option value=""></option>
                                        @foreach ($handleGroups as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select name="user_id" class="custom-select custom-select-sm form-control" required>
                                        <option value=""></option>
                                        @foreach ($users as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th width="7%">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block">Save</button>
                                    <button name="cancel" type="button" class="btn btn-warning btn-sm btn-block"> <i class="fas fa-plus-circle"></i> Cancel</button>
                                    <button name="remove" type="button" class="btn btn-danger btn-sm btn-block"> <i class="fas fa-minus-circle"></i> Remove</button>
                                </th>
                            </tr>
                            <tr>
                                <th>Group Name</th>
                                <th>User</th>
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
                { data:'handle_group_id',
                    render:function(data, type, row, meta){
                        return row.handle_group_name;
                    }
                },
                { data:'user_id',
                    render:function(data, type, row, meta){
                        return row.user_name;
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
            GroupForm.find("button[name=remove]").hide()
        }).hide()

        GroupForm.find("button[name=remove]").on('click',function(){
            GroupForm[0].reset()
            let id = GroupForm.find('input[name=id]').val()
            let deleteURL = GroupTable.attr("data-urlRemove").replace("param",id)
            GroupForm.find('input[name=id]').val('')
            if (confirm("Are you sure you want delete this record?")) {
            $.ajax({
                url:  deleteURL,
                type:'DELETE',
                data:{
                    _token: CoreModel.token
                }
            }).done(function(data){
                if (data.msg) {
                    CoreModel.toasMessage(data.msg,"success",data.icon)
                    GroupDataTable.ajax.reload()
                }
            }).fail(CoreModel.handleAjaxError)
            .always(function(){
                GroupDataTable.ajax.reload()
            })
        }
        return false
        }).hide()

        $(document).on('click','button[name=edit]',function(){
            GroupForm.find("button[name=cancel]").show()
            GroupForm.find("button[name=remove]").show()
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
                    CoreModel.toasMessage(data.msg,"success",'success')
                    GroupDataTable.ajax.reload()
                }
            }).fail(CoreModel.handleAjaxError)
            .always(function() {
                GroupForm[0].reset()
                GroupForm.find('input[name=id]').val('')
                GroupForm.find("button[name=cancel]").hide()
                GroupForm.find("button[name=remove]").hide()
            });
        })
    </script>
@endsection