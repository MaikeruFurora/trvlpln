const aTime = [
    '08:00','08:20','08:40','09:00','09:20','09:40','10:00','10:20','10:40',
    '11:00','11:20','11:40','12:00','12:20','12:40','13:00','13:20','13:40',
    '14:00','14:20','14:40','15:00','15:20','15:40','16:00','16:20','16:40',
    '17:00','17:20','17:40','18:00'
]

$('.datepicker').datetimepicker({
    minDate: moment(),
    // timeFormat: 'hh:mm a',
    allowTimes:aTime,
    formatTime:'g:i A',
    format: 'Y-m-d H:i',
    //minTime:moment("g:i")

}).on('change', function(){
    let dateFrom = $(this).datetimepicker('getValue');
    if (dateFrom) {
        $('input[name="time_to"]').datetimepicker({
            value: new Date(dateFrom.getTime() + 30 * 60000),
            format: 'h:i A'
        });
    }
});
$('.timepicker').datetimepicker({
    datepicker:false,
    allowTimes:aTime,
    formatTime:'g:i A',
    format: 'H:i',

});


// $('.timepicker').timepicker({
    // 'timeFormat': 'h:i A',
    // 'minTime': '07:00 AM',
    // 'maxTime': '06:00 PM'
// });

var defaultView = 'agendaWeek';
var defaultView = ($(window).width() <= 600) ? 'agendaDay' : 'agendaWeek';
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
        height: 730, // or a specific value like 'auto', 'parent', or a number    
        eventLimit: false,
        eventLimitText: 'more',
        slotDuration: '00:20:00', // Set the slot duration to 20 minute intervals
        scrollTime: '06:00:00', // Set the initial scroll of the calendar to 6 PM
        slotEventOverlap:false,
        eventOverlap:false,
        header: {
            left: 'prev,next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,'
            // right: 'month,basicWeek,agendaDay'
        },
        minTime: '07:00:00', // Set the minimum time to display (e.g., 8:00 AM)
        maxTime: '18:00:00', // Set the maximum time to display (e.g., 6:00 PM)
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
                }
        },
        
        eventRender: function (info,element) {
            $(info.el).css("border-color", "#20232a");
            element.find('.fc-title').css('color', info.textColor); // Set text color for each event
            element.find('.fc-title').css('font-weight', '900'); // Set text color for each event
            element.find('.fc-time').css('color', info.textColor)
            element.tooltip({ 
                title: function() {
                    return `<div class="event-tooltip text-left">
                                <p><b>Client:</b> ${info.title}</p>
                                <p><b>Date</b>: ${info.start.format('YYYY-MM-DD')}</p>
                                ${info.osnum?`<p><b>OS:</b> ${info.osnum}</p>`:''}
                                ${info.note?`<p><b>Note:</b> ${info.note}</p>`:''}
                                ${info.sttus?`<p><b>Status:</b> ${info.sttus}</p>`:''}
                                <p><b>Time:</b> ${info.start.format('H:mm A')} - ${info.end ? info.end.format('H:mm A') : 'N/A'}</p>
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
            end: '19:00', // 5pm? set to whatever
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
        eventDrop: function(event) {
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
            let disablePastAndFuture =  moment().format('YYYY-MM-DD')!==moment(event.start).format('YYYY-MM-DD');
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

                    ActivityForm.find('input[type=checkbox]').prop('disabled', disablePastAndFuture);
                    ActivityForm.find("select[name=activity]").val(data.activity_list.id);
                    ActivityForm.find("input[name=date_from]").val(data.date_from).prop('readonly', false);
                    ActivityForm.find("input[name=date_to]").val(data.date_to).prop('readonly', false);
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
    // const startTime = Activity.find('input[name="date_from"]').datetimepicker('getValue');
    // const endTime = Activity.find('input[name="time_to"]').datetimepicker('getValue');
    // console.log(startTime,endTime);
    // if (startTime >= endTime) {
    //     toasMessage("Start time must be less than end time", "Error", 'error');
    //     return false;
    // }

    $.ajax({
        url: Activity.attr("action"),
        type: 'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data) {
        if (data.msg) {
            Activity[0].reset();
            Activity.find('input[name=id]').val('');
            toasMessage(data.msg, "success", data.icon);
            $('#calendar').fullCalendar('refetchEvents');
        }
    }).fail(function(jqxHR, textStatus, errorThrown) {
        toasMessage(jqxHR.responseJSON.msg, "Error", jqxHR.responseJSON.icon);
    });
})

ActivityForm.on('submit',function(e){

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
                toasMessage(jqxHR.responseJSON.msg,"Error",jqxHR.responseJSON.icon)
            })
        }
        return false

})


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
