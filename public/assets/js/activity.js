const aTime = [
    '08:00','08:20','08:40','09:00','09:20','09:40','10:00','10:20','10:40',
    '11:00','11:20','11:40','12:00','12:20','12:40','13:00','13:20','13:40',
    '14:00','14:20','14:40','15:00','15:20','15:40','16:00','16:20','16:40',
    '17:00','17:20','17:40','18:00'
]

let dateTimeSetting = {
    datepicker: true,
    timepicker: false,
    minDate: moment(),
    format: 'Y-m-d',
    // allowTimes: aTime,
    // formatTime: 'g:i A',
    // format: 'Y-m-d H:i',
    beforeShowDay: function(date) {
        if (date.getDay() === 0) { // if it's Sunday
            return [false, "", "Unavailable on Sundays"]; // mark as unavailable
        } else {
            return [true, ""]; // mark as available
        }
    },
    onSelectDate: function(ct, $i) {
        if (ct.getDay() === 0) { // if selected date is Sunday
            this.setOptions({
                minDate: ct.add(1, 'day') // set minDate to next day, Monday
            });
        }
    },
}


$('input[name="date_from"]').datetimepicker(dateTimeSetting);

$('input[name="time_from"]').on('change', function(){
    let dateFrom = $(this).datetimepicker('getValue');
    if (dateFrom) {
        $('input[name="time_to"]').datetimepicker({
            value: new Date(dateFrom.getTime() + 30 * 60000),
            format: 'h:i A'
        });
    }
});

$('.timepicker').datetimepicker({
    datepicker: false,
    allowTimes: aTime,
    formatTime: 'g:i A',
    format: 'g:i A',
    validateOnBlur: false,
});

var defaultView  = 'agendaWeek';
var defaultView  = ($(window).width() <= 600) ? 'basicDay' : 'agendaWeek';
let Activity     = $("#Activity")
let ActivityForm = $("#ActivityForm")
let ActivityDate = $("#ActivityDate")
let clndr        = $('#calendar')
let DateResched  = ActivityForm.find("#DateResched")
let settings     = (getDataURL) =>{
    return {
        timeZone: 'UTC',
        defaultView:defaultView,//agendaWeek
        aspectRatio: 1.5, // Adjust as needed
        height: 800, // or a specific value like 'auto', 'parent', or a number    
        eventLimit: true,
        eventLimitText: 'more',
        slotDuration: '00:20:00', // Set the slot duration to 20 minute intervals
        scrollTime: '07:00:00', // Set the initial scroll of the calendar to 6 PM
        slotEventOverlap:false,
        eventOverlap:false,
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,basicWeek'
            // right: 'month,basicWeek,agendaDay'
        },
        views: {
            agendaWeek: { // Customize the agendaWeek view
              type: 'agendaWeek', // Use the timeGridWeek view type
              buttonText: 'Agenda Week' // Rename the button text
            },
            basicWeek: { // Customize the basicWeek view
              buttonText: 'Basic Week' // Rename the button text
            },
        },
        minTime: '07:00:00', // Set the minimum time to display (e.g., 8:00 AM)
        maxTime: '20:00:00', // Set the maximum time to display (e.g., 6:00 PM)
        editable: true, 
        hiddenDays: [0],
        allDaySlot: false,
        selectable: true,
        // eventLimit: false, // allow "more" link when too many events
        events: {
                url:  getDataURL,//clndr.attr("data-list"),
                type:'POST',
                data: {  
                _token
                // date_from:
                }
        },
        
        eventRender: function (info,element) {
            element.find('.fc-title').each(function () {
                $(this).insertBefore($(this).prev('.fc-time'));
            });
            $(info.el).css("border-color", "#20232a");
            element.find('.fc-title').css('color', info.textColor).css('font-size','11px').css('font-weight', '800'); // Set text color for each event
            element.find('.fc-time').css('color', info.textColor).css('font-size','11px').css('margin-left','2px')
            // element.append('<div style="position: absolute; top: 0; right: 0; width: 0; height: 0; transform: rotate(270deg); border-left: 10px solid transparent; border-bottom: 10px solid '+info.activityColor+';"></div>');
            if (info.title && info.title.length > 20) {
                element.find('.fc-title').text(info.title.substring(0, 20) + '...'); // Truncate title and add ellipsis if longer than 20 characters
            }
            element.find('.fc-content').prepend('<i style="color:black; position: absolute; top: 1px; right: 1px;" class="'+info.icon+'"></i>');
            element.tooltip({ 
                title: function() {
                    return `<div class="event-tooltip text-left" style="font-size:13px">
                                <p class="mb-1"><b>Client:</b> ${info.title}</p>
                                ${info.osnum?`<p class="mb-1"><b>OS:</b> ${info.osnum}</p>`:''}
                                ${info.note ? `<p class="mb-1"><b>Note:</b> ${info.note.length > 80 ? info.note.substring(0, 80) + '<a href="#" onclick="event.preventDefault(); $(this).parent().text(info.note);">...click to see more</a>' : info.note}</p>` : ''}
                                ${info.sttus?`<p class="mb-1"><b>Status:</b> ${info.sttus}</p>`:''}
                                ${info.activity?`<p class="mb-1"><b>Activity:</b> ${info.activity}</p>`:''}
                                <p class="mb-1"><b>Date:</b> ${info.start.format('MMM DD, YYYY')}</p>
                                <p class="mb-1"><b>Time:</b> ${info.start.format('h:mm A')} - ${info.end ? info.end.format('h:mm A') : 'N/A'}</p>
                            </div>`;
                },
                html: true,
                placement: "top",
                trigger: "hover",
                container: "body"
            });
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
                        toasMessage(jqxHR.responseJSON.msg,"Warning",jqxHR.responseJSON.icon)
                        $('#calendar').fullCalendar('refetchEvents');
                    },
                });
        },

        eventClick: function(event, jsEvent, view) {
            $('.tooltip').hide();
            let disablePastAndFuture =  moment().format('YYYY-MM-DD')!==moment(event.start).format('YYYY-MM-DD');
            disablePastAndFuture ? ActivityForm.find("button[name=delete]").hide() : ActivityForm.find("button[name=delete]").show()
            getLocationAndInitMap()
            document.getElementById('viewActivity').style.display = 'block';
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
                    ActivityForm.find("select[name=activity]").val(data.activity_list.id);
                    ActivityForm.find("input[name=date_from]").val(moment(data.date_from).format('YYYY-MM-DD')).prop('readonly', false);
                    ActivityForm.find("input[name=time_from]").val(moment(data.date_from).format('h:m A')).prop('readonly', false);
                    ActivityForm.find("input[name=time_to]").val(moment(data.date_to).format('h:m A')).prop('readonly', false);
                },
                error:function (jqxHR, textStatus, errorThrown) 
                {
                     toasMessage(jqxHR.responseJSON.msg,"Warning",jqxHR.responseJSON.icon)
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
    // const startTime = Activity.find('input[name="date_from"]').datetimepicker('getValue');
    // const endTime = Activity.find('input[name="time_to"]').datetimepicker('getValue');
    // console.log(startTime,endTime);
    // if (startTime >= endTime) {
    //     toasMessage("Start time must be less than end time", "Error", 'error');
    //     return false;
    // }
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
        }
    }).fail(function(jqxHR, textStatus, errorThrown) {
        Activity.find("button[type=submit]").html('Save');
        $("#Activity *").prop("readonly", false);
        toasMessage(jqxHR.responseJSON.msg, "Warning", jqxHR.responseJSON.icon);
    });
})

ActivityForm.on('submit',function(e){

    console.log(ActivityForm.find("input[name=date_from]").val());

     // Verify that at least one checkbox is checked
    // if (ActivityForm.find('input[type="checkbox"]:checked').length === 0) {
    //     toasMessage("Please select least one option before saving.", "Error", 'error');
    //     return false;
    // }

    let id          = ActivityForm.find("input[name=id]").val()
    let updateUrl   = ActivityForm.attr("action").replace("param",id)
    e.preventDefault()
    $.ajax({
        url:  updateUrl,
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        $('#viewActivity').modal('hide');
        if (data.msg) {
            ActivityForm[0].reset()
            Activity.find('input[name=id]').val('')
            toasMessage(data.msg,"success",data.icon)
            $('#calendar').fullCalendar('refetchEvents');
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(jqxHR.responseJSON.msg,"Error",jqxHR.responseJSON.icon)
    })
})

Activity.find("input[name=week]").on('click',function(){
    if (Activity.find("input[name=date_from]").val()=="") {
        toasMessage('Please check date & time',"Warning",'warning')
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
                toasMessage(jqxHR.responseJSON.msg,"Warning",jqxHR.responseJSON.icon)
            })
        }
        return false

})


ActivityForm.find("input[name=date_from]").on('change',function(){

    let dateFrom = $(this).datetimepicker('getValue');
    if (dateFrom) {
        $('input[name="date_to"]').datetimepicker({
            minDate: moment(),
            value: new Date(dateFrom.getTime() + 30 * 60000),
            format: 'Y-m-d H:i',
            formatTime:'g:i A',
        });
    }

}).datetimepicker(dateTimeSetting)


DateResched.hide()

$('input[type=checkbox]').click(function(){
    // if ($(this).val()=="resched" && $(this).is(":checked")) {
    //     DateResched.show()
    // }else{
    //     DateResched.hide()
    // }
});



$(document).on('click', '.custom-control-input', function() {      
    $('input[type="checkbox"]').not(this).prop('checked', false);
});
