
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
let clndr        = $('#calendar')
let DateResched  = ActivityForm.find("#DateResched")
let settings     = (getDataURL) =>{
    return {
        displayEventTime: false,
        themeSystem: 'bootstrap',
        timeZone: 'UTC',
        defaultView:defaultView,//agendaWeek
        aspectRatio: 1.5, // Adjust as needed
        height: 800, // or a specific value like 'auto', 'parent', or a number    
        eventLimit: false,
        // eventLimitText: 'more',
        slotDuration: '00:20:00', // Set the slot duration to 20 minute intervals
        scrollTime: '07:00:00', // Set the initial scroll of the calendar to 6 PM
        slotEventOverlap:false,
        eventOverlap:false,
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,basicWeek'
        },
        views: {
            agendaWeek: { // Customize the agendaWeek view
              type: 'agendaWeek', // Use the timeGridWeek view type
              buttonText: 'Agenda Week' // Rename the button text
            },
            basicWeek: { // Customize the basicWeek view
              buttonText: 'Basic Week' // Rename the button text
            },
            agendaDay:{
                buttonText: 'Day' // Rename the button text
            }
        },
        minTime: '07:00:00', // Set the minimum time to display (e.g., 8:00 AM)
        maxTime: '20:00:00', // Set the maximum time to display (e.g., 6:00 PM)
        editable: true,  // Allow resizing
        eventStartEditable: false,  // Disable dragging
        hiddenDays: [0],
        allDaySlot: false,
        selectable: true,
        events: {
            url:  getDataURL,
            type:'POST',
            data: {  
                _token
            }
        },
        eventRender: function(event, element) {
            element.popover({
                title: event.start.format('h:mma') + ' - ' + event.end.format('h:mma'),
                content: event.title+ (event.note === null ? '' : ' - '+event.note),
                trigger: 'hover',
                placement: 'top',
                container: 'body'
            });

            // Display full event title with time
            element.find('.fc-title').html(event.title);
        },
        businessHours: {
            start: moment().format('HH:mm'), /* Current Hour/Minute 24H format */
            end: '20:00', // 5pm? set to whatever
            dow: [0,1,2,3,4,5,6] // Day of week. If you don't set it, Sat/Sun are gray too
        },
        eventConstraint: {
                start: moment().format('YYYY-MM-DD HH:mm'),
                end: '2100-01-01' // hard coded goodness unfortunately
        },
        select: function(startDate, endDate) {
            //console.log('select', startDate.format(), endDate.format());
        },
        eventResize: function(event){
            $('.tooltip').hide();
            //$.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            //$.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            let id          = event.id
            let date_from   = moment(event.start).format('YYYY-MM-DD HH:mm:ss')
            let date_to     = moment(event.end).format('YYYY-MM-DD HH:mm:ss')
            let updateUrl   = clndr.attr("data-update").replace("param",id)
            $.ajax({
            url: updateUrl,
            type:"POST",
            data:{ date_from, date_to, _token },
                success:function(data){
                    $('#calendar').fullCalendar('refetchEvents');
                    toasMessage(data.msg,"success",'success')
                },
                error:function(error){
                    toasMessage("Not allowed activity","warning",'warning')
                    $('#calendar').fullCalendar('refetchEvents');
                },
            })
        },

        eventContent: function(arg) {
            // Create a custom element to display only the title
            let title = document.createElement('div');
            title.innerHTML = arg.event.title;  // Show only the title
        
            return { domNodes: [title] };  // Return only the title, no details
          },
        
        eventDropStart: function(event, jsEvent, ui, view) {
            // Hide tooltip when resizing starts
            $('.tooltip').hide();
        },
        eventDropStop: function(event, jsEvent, ui, view) {
            // Hide tooltip when resizing starts
            $('.tooltip').hide();
        },
        eventResizeStart: function(event, jsEvent, ui, view) {
            // Hide tooltip when resizing starts
            $('.tooltip').hide();
        },
        eventResizeStop: function(event, jsEvent, ui, view) {
            // Show tooltip again when resizing stops
            $('.tooltip').hide();
        },
        eventDrop: function(event) {
            $('.tooltip').hide();
            let id          = event.id;
            let date_from   = moment(event.start).format('YYYY-MM-DD HH:mm:ss')
            let date_to     = moment(event.end).format('YYYY-MM-DD HH:mm:ss')
            let updateUrl   = clndr.attr("data-update").replace("param",id)
                $.ajax({
                    url: updateUrl,
                    type:"POST",
                    dataType:'json',
                    data:{ date_from, date_to, _token },
                    success:function(data){
                        toasMessage(data.msg,"success",data.icon)
                        $('#calendar').fullCalendar('refetchEvents');
                    },
                    error:function (jqxHR, textStatus, errorThrown) {
                        toasMessage(jqxHR.responseJSON.msg,"Error",jqxHR.responseJSON.icon)
                        $('#calendar').fullCalendar('refetchEvents');
                    },
                });
        },

        eventClick: function(event, jsEvent, view) {
            $('.tooltip').hide();
            let disablePastAndFuture =  moment().format('YYYY-MM-DD')!==moment(event.start).format('YYYY-MM-DD');
            disablePastAndFuture ? ActivityForm.find("button[name=delete]").hide() : ActivityForm.find("button[name=delete]").show()
            $('#viewActivity').modal('show');
            DateResched.hide()
            ActivityForm[0].reset()
            $('#viewActivityLabel').text(event.title);
            let updateUrl   = clndr.attr("data-info").replace("param",event.id)
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
        
        },

        loading: function(isLoading) {
            // Show or hide loading spinner based on loading state
            if (isLoading) {
              $('<div class="loading-spinner"><i class="fa fa-spinner fa-spin"></i> Loading...</div>').appendTo('body');
            } else {
              $('.loading-spinner').remove();
            }
        },

    }
}

let DefaultURL = clndr.attr("data-list").replace("user",clndr.attr("data-id"))
clndr.fullCalendar(settings(DefaultURL));



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
            $('#calendar').fullCalendar('refetchEvents');
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
            $('#calendar').fullCalendar('refetchEvents');
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
                    $('#calendar').fullCalendar('refetchEvents');
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

