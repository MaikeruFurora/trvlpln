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

var defaultView  = ($(window).width() <= 600) ? 'basicDay' : 'basicWeek';
let Activity     = $("#Activity")
let ActivityForm = $("#ActivityForm")
let ActivityDate = $("#ActivityDate")
let DefaultURL = CoreModel.calendar.attr("data-list").replace("user",CoreModel.calendar.attr("data-id"))
CoreModel.calendar.fullCalendar(CoreModel.calendarSettings(DefaultURL,defaultView));

Activity.on('submit', function(e) {
    e.preventDefault();

    // Set UI elements to readonly and show loading spinner
    $("#Activity *").prop("readonly", true);
    Activity.find("button[type=submit]").html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
        url: Activity.attr("action"),
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false
    }).done(function(data) {
        if (data.msg) {
            CoreModel.toasMessage(data.msg, "success", data.icon);
            CoreModel.defaultTime();            
            Activity[0].reset();
        }
    }).fail(CoreModel.handleAjaxError)
    .always(function() {
        // Reset UI elements and form state
        $("#Activity *").prop("readonly", false);
        Activity.find('input[name=id]').val('');
        Activity.find("button[type=submit]").html('Save');
        CoreModel.calendar.fullCalendar('refetchEvents');
    });
});


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
        if (data.msg) {
            CoreModel.toasMessage(data.msg,"success",data.icon)
            CoreModel.defaultTime()
        }
    }).fail(CoreModel.handleAjaxError)
    .always(function(){
        ActivityForm[0].reset()
        Activity.find('input[name=id]').val('')
        $('#viewActivity').modal('hide');
        $("#productTable tbody").find("tr").remove()
        CoreModel.booking = []
        CoreModel.calendar.fullCalendar('refetchEvents');
        
    })
})

Activity.find("input[name=week]").on('click',function(){
    if (Activity.find("input[name=date_from]").val()=="") {
        CoreModel.toasMessage('Please check date & time',"warning",'warning')
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
                    _token: CoreModel.token
                }
            }).done(function(data){
                if (data.msg) {
                   CoreModel.toasMessage(data.msg,"success",data.icon)
                }
                $('#viewActivity').modal('hide');
            }).fail(CoreModel.handleAjaxError)
            .always(function(){
                CoreModel.calendar.fullCalendar('refetchEvents');
            })
        }
        return false

})

$(document).on('click', '.form-check-input', function() {      
    $('input[type="checkbox"]').not(this).prop('checked', false);
});

CoreModel.calendar.fullCalendar('off', 'eventClick');
CoreModel.calendar.fullCalendar('on', 'eventClick', function(event, jsEvent, view) {
    $(this).popover('hide');
    let disablePastAndFuture =  moment(getTime()).format('YYYY-MM-DD')!==moment(event.start).format('YYYY-MM-DD');
    disablePastAndFuture ? ActivityForm.find("button[name=delete]").hide() : ActivityForm.find("button[name=delete]").show()
    $('#viewActivity').modal('show');
    ActivityForm[0].reset()
    $('#viewActivityLabel').text(event.title);
    let updateUrl   = CoreModel.calendar.attr("data-info").replace("param",event.id)
    $.ajax({
        url: updateUrl,
        type: "GET",
        dataType: 'json'
    })
    .done(function(data) {
        // Update CoreModel with fetched data
        CoreModel.booking = data.booking;
    
        // Render table with data
        renderTable(disablePastAndFuture);
    
        // Update form fields with data
        ActivityForm.find("input[name=id]").val(data.id);
        $("#ActivityForm .getInput").each(function() {
            var name = this.name;
            if (name !== 'sttus[]') {
                var $elem = ActivityForm.find("[name=" + name + "]");
                $elem.val(data[name]).prop('readonly', disablePastAndFuture);
            }
        });
    
        // Update checkbox states
        ActivityForm.find("input[type=checkbox]")
            .prop('checked', false)
            .filter(function() { return this.value == data.sttus; })
            .prop('checked', true);
    
        // Show/hide delete button
        ActivityForm.find("button[name=delete]").toggle(!data.isDelete);
    
        // Set form field properties based on disablePastAndFuture
        ActivityForm.find("input[type=checkbox]").prop('disabled', disablePastAndFuture);
        ActivityForm.find("select[name=activity]").val(data.activity_list_id);
        ActivityForm.find("input[name=date_from]").val(moment(data.date_from).format('YYYY-MM-DD')).prop('readonly', false);
        ActivityForm.find("input[name=time_from]").val(moment(data.date_from).format('HH:mm')).prop('readonly', false);
        ActivityForm.find("input[name=time_to]").val(moment(data.date_to).format('HH:mm')).prop('readonly', false);
    
        // Set readonly and disabled properties for booking fields
        ActivityForm.find('input[name=product], input[name=qty], input[name=price]').prop('readonly', disablePastAndFuture);
        ActivityForm.find('button[id=addProduct]').prop('disabled', disablePastAndFuture);
    })
    .fail(CoreModel.handleAjaxError)
    .always(function() {
        $('#calendar').fullCalendar('refetchEvents');
    });
    
 });

 CoreModel.calendar.fullCalendar('off', 'eventDrop');
 CoreModel.calendar.fullCalendar('on', 'eventDrop', function(event, delta, revertFunc) {
    $('.tooltip').hide();
    let id          = event.id;
    let date_from   = moment(event.start).format('YYYY-MM-DD HH:mm:ss')
    let date_to     = moment(event.end).format('YYYY-MM-DD HH:mm:ss')
    let updateUrl   = CoreModel.calendar.attr("data-update").replace("param",id)
        $.ajax({
            url: updateUrl,
            type:"POST",
            dataType:'json',
            data:{ 
                date_from, 
                date_to, 
                _token: CoreModel.token
            },
            success:function(data){
                CoreModel.toasMessage(data.msg,"success",data.icon)
                CoreModel.calendar.fullCalendar('refetchEvents');
            },
            error:function (jqxHR, textStatus, errorThrown) {
                CoreModel.toasMessage(jqxHR.responseJSON.msg,"Error",jqxHR.responseJSON.icon)
                CoreModel.calendar.fullCalendar('refetchEvents');
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