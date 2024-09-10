
window.onload = function() {
    CoreModel.defaultTime()
};

// Get references to the time input fields
 const timeFrom = document.getElementById('time_from');
 const timeTo = document.getElementById('time_to');

 // Event listener for time_from input change
 timeFrom.addEventListener('change', function() {
     const timeFromValue = timeFrom.value;
     if (timeFromValue) {
         // Set time_to to 20 minutes after time_from
         timeTo.value = CoreModel.addMinutesToTime(timeFromValue, 20);
     }
 });

var defaultView  = 'basicWeek';
var defaultView  = ($(window).width() <= 600) ? 'basicDay' : 'basicWeek';
let Activity     = $("#Activity")
let ActivityForm = $("#ActivityForm")
let ActivityDate = $("#ActivityDate")
let DateResched  = ActivityForm.find("#DateResched")
let DefaultURL = CoreModel.calendar.attr("data-list").replace("user",CoreModel.calendar.attr("data-id"))
CoreModel.calendar.fullCalendar(CoreModel.calendarSettings(DefaultURL,defaultView));

Activity.on('submit',function(e){
    e.preventDefault();
    $("#Activity *").prop("readonly", true);
    $.ajax({
        url: Activity.attr("action"),
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        beforeSend:function(){
            Activity.find("button[type=submit]").html('<i class="fa fa-spinner fa-spin"></i>');
        }
    }).done(function(data) {
        if (data.msg) {
            $("#Activity *").prop("readonly", false);
            Activity[0].reset();
            Activity.find('input[name=id]').val('');
            Activity.find("button[type=submit]").html('Save');
            toasMessage(data.msg, "success", data.icon);
            CoreModel.calendar.fullCalendar('refetchEvents');
            CoreModel.defaultTime()
        }
    }).fail(function(jqxHR, textStatus, errorThrown) {
        Activity.find("button[type=submit]").html('Save');
        $("#Activity *").prop("readonly", false);
        toasMessage(jqxHR.responseJSON.msg, "warning", jqxHR.responseJSON.icon);
    });
})

ActivityForm.on('submit',function(e){
    let id          = ActivityForm.find("input[name=id]").val()
    let updateUrl   = ActivityForm.attr("action").replace("param",id)
    const formData = new FormData(ActivityForm[0])
    formData.append('booking',JSON.stringify(CoreModel.booking))
    e.preventDefault()
    $.ajax({
        url:  updateUrl,
        type:'POST',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        $('#viewActivity').modal('hide');
        if (data.msg) {
            ActivityForm[0].reset()
            Activity.find('input[name=id]').val('')
            toasMessage(data.msg,"success",data.icon)
            $("#productTable tbody").find("tr").remove()
            CoreModel.booking = []
            CoreModel.calendar.fullCalendar('refetchEvents');
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(jqxHR.responseJSON.msg,"warning",jqxHR.responseJSON.icon)
    })
})

Activity.find("input[name=week]").on('click',function(){
    if (Activity.find("input[name=date_from]").val()=="") {
        toasMessage('Please check date & time',"warning",'warning')
        $(this).prop("checked",false)
    }
})

Activity.find("input[name=date_from]").on('focusOut',function(){
    if (Activity.find("input[name=date_from]").val()=="") {
        $(this).prop("checked",false)
    }
})


ActivityForm.find("button[name=delete]").on('click',function(){
    let id          = ActivityForm.find("input[name=id]").val()
    let updateUrl   = $(this).attr("data-delete").replace("param",id)

        if (confirm("Are you sure you want delete this activity?")) {
            $.ajax({
                url:  updateUrl,
                type:'DELETE',
                data:{
                    _token
                }
            }).done(function(data){
                if (data.msg) {
                    toasMessage(data.msg,"success",data.icon)
                    CoreModel.calendar.fullCalendar('refetchEvents');
                }
                $('#viewActivity').modal('hide');
            }).fail(function (jqxHR, textStatus, errorThrown) {
                toasMessage(jqxHR.responseJSON.msg,"warning",jqxHR.responseJSON.icon)
            })
        }
        return false

})

$(document).on('click', '.custom-control-input', function() {      
    $('input[type="checkbox"]').not(this).prop('checked', false);
});

CoreModel.calendar.fullCalendar('off', 'eventClick');
CoreModel.calendar.fullCalendar('on', 'eventClick', function(event, jsEvent, view) {
    $('.tooltip').hide();
    let disablePastAndFuture =  moment().format('YYYY-MM-DD')!==moment(event.start).format('YYYY-MM-DD');
    disablePastAndFuture ? ActivityForm.find("button[name=delete]").hide() : ActivityForm.find("button[name=delete]").show()
    $('#viewActivity').modal('show');
    DateResched.hide()
    ActivityForm[0].reset()
    $('#viewActivityLabel').text(event.title);
    let updateUrl   = CoreModel.calendar.attr("data-info").replace("param",event.id)
    $.ajax({
        url: updateUrl,
        type:"GET",
        dataType:'json',
        success: function(data) {
            CoreModel.booking = data.booking;
            renderTable(disablePastAndFuture)
            ActivityForm.find("input[name=id]").val(data.id);
            $("#ActivityForm .getInput").each(function() {
                var name = this.name;
                if(name !== 'sttus[]') {
                    var $elem = ActivityForm.find("[name=" + name + "]");
                    
                    $elem.val(data[name]).prop('readonly', disablePastAndFuture);
                }
            });
            
            ActivityForm.find("input[type=checkbox]").prop('checked', false).filter(function() {
                return this.value == data.sttus;
            }).prop('checked', true);
            (data.isDelete) ? ActivityForm.find("button[name=delete]").hide() : ActivityForm.find("button[name=delete]").show();
            ActivityForm.find('input[type=checkbox]').prop('disabled', disablePastAndFuture);
            ActivityForm.find("select[name=activity]").val(data.activity_list_id);
            ActivityForm.find("input[name=date_from]").val(moment(data.date_from).format('YYYY-MM-DD')).prop('readonly', false);
            ActivityForm.find("input[name=time_from]").val(moment(data.date_from).format('HH:mm')).prop('readonly', false);
            ActivityForm.find("input[name=time_to]").val(moment(data.date_to).format('HH:mm')).prop('readonly', false);
            //booking field and button
            ActivityForm.find('input[name=product]').prop('readonly', disablePastAndFuture);
            ActivityForm.find('input[name=qty]').prop('readonly', disablePastAndFuture);
            ActivityForm.find('input[name=price]').prop('readonly', disablePastAndFuture);
            ActivityForm.find('button[id=addProduct]').prop('disabled', disablePastAndFuture);
            
        },
        error:function (jqxHR, textStatus, errorThrown) 
        {
             toasMessage(jqxHR.responseJSON.msg,"Error",jqxHR.responseJSON.icon)
            $('#calendar').fullCalendar('refetchEvents');
        },
    });
 });


 $(document).on('click', '.dropdown-item', function(e) {
    e.preventDefault();

    // Get the href attribute from the clicked dropdown item
    let url_string = $(this).data("url");

    // Call CoreModel to load the report
    CoreModel.loadToPrint(url_string);
});