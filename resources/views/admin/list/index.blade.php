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
                <form id="UserForm" action="{{ route('authenticate.actvtylist.store') }}" autocomplete="off">@csrf <input type="hidden" class="form-control" name="id">
               <div class="table-responsive">
                <table id="UserTable" data-url="{{ route('authenticate.actvtylist.list') }}" class="table table-bordered table-sm adjust"  style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:13px">
                    <thead>
                            <tr>
                                <th><input type="text" name="name" class="form-control form-control-sm" required> </th>
                                <th>
                                    <select name="is_disabled" class="custom-select custom-select-sm form-control" required>
                                        <option value=""></option>
                                        <option value="1">No</option>
                                        <option value="0">Yes</option>
                                    </select>
                                <th><input type="color" name="color" class="form-control form-control-sm"></th>
                                <th><input type="text" name="icon" class="form-control form-control-sm" maxlength="30"></th>
                                <th></th>
                                </th>
                                <th width="7%">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block">Save</button>
                                    <button name="cancel" type="button" class="btn btn-warning btn-sm btn-block"> <i class="fas fa-plus-circle"></i> Cancel</button>
                                </th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Active</th>
                                <th>Color</th>
                                <th>Icon</th>
                                <th>Created at</th>
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
        let UserForm  = $("#UserForm") 
        let UserTable = $("#UserTable");
        let UserDataTable = $("#UserTable").DataTable({
            "initComplete": function(settings, json) {
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
            },
            serverSide: true,
            paging:true,
            ajax: {
                url: UserTable.attr("data-url"),
                method: "get"
            },
            columns:[
                { data:'name' },
                { data:'is_disabled',
                    render:function(data){
                        console.log(data.is_disabled);
                        return (data.is_disabled)? 'No' : 'Yes'
                    }
                },
                { data:'color' },
                { data:'icon' },
                { data:'created_at' },
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

        UserForm.find("button[name=cancel]").on('click',function(){
            UserForm[0].reset()
            UserForm.find('input[name=id]').val('')
            $(this).hide()
        }).hide()

        $(document).on('click','button[name=edit]',function(){
            UserForm.find("button[name=cancel]").show()
            let data = UserDataTable.row( $(this).closest('tr') ).data()
            $.each($('#UserForm .form-control'),(ind, value) => {
                if (value.name!="") {
                    UserForm.find("input[name="+value.name+"]").val(data[value.name]);
                    UserForm.find("select[name="+value.name+"]").val(data[value.name]);
                }
                // UserForm.table.find("select[name=description]").append(new Option(data.description, data.description, true, true)).trigger('change');
            });
        })
        
        UserForm.on('submit',function(e){
            e.preventDefault()
            $.ajax({
                url:  UserForm.attr("action"),
                type:'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
            }).done(function(data){
                if (data.msg) {
                    CoreModel.toasMessage(data.msg,"success",'success')
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