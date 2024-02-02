$('.datepicker').datetimepicker({
    minDate: moment(),
    // timeFormat: 'hh:mm a',
    allowTimes:[
    '08:00', '09:00','10:00','11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
    ],
    formatTime:'g:iA',
    format: 'Y-m-d H:i',
    //minTime:moment("g:i")
    
});

var defaultView = 'agendaWeek';
var defaultView = ($(window).width() <= 600) ? 'agendaDay' : 'agendaWeek';
let Activity     = $("#Activity")
let ActivityForm = $("#ActivityForm")
let clndr        = $('#calendar')
let DateResched  = ActivityForm.find("#DateResched")
let settings     = (getDataURL) =>{
    return {
        themeSystem : "jquery-ui",
        defaultView:defaultView,//agendaWeek
        aspectRatio: 1.5, // Adjust as needed
        height: 650, // or a specific value like 'auto', 'parent', or a number    
        eventLimit: false,
        eventLimitText: 'more',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'agendaWeek,agendaDay,'
            // right: 'month,basicWeek,agendaDay'
        },
        minTime: '06:00:00', // Set the minimum time to display (e.g., 8:00 AM)
        maxTime: '19:00:00', // Set the maximum time to display (e.g., 6:00 PM)
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
            // $(info.el).css("border-style", "dashed");
            $(info.el).css("border-color", "#20232a");
            element.find('.fc-title').css('color', info.textColor); // Set text color for each event
            element.find('.fc-title').css('font-weight', 'normal'); // Set text color for each event
            element.find('.fc-time').css('color', info.textColor);
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
                success:function(data)
                {
                    ActivityForm.find("input[name=id]").val(data.id)
                    $.each($("#ActivityForm .getInput"),function(i,val){
                       if(val.name!='sttus[]'){
                            ActivityForm.find("select[name="+val.name+"]").val(data[val.name]).prop('readonly',disablePastAndFuture)
                            ActivityForm.find("input[name="+val.name+"]").val(data[val.name]).prop('readonly',disablePastAndFuture)
                            ActivityForm.find("textarea[name="+val.name+"]").val(data[val.name]).prop('readonly',disablePastAndFuture)
                        }
                    })
                    ActivityForm.find("input[type=checkbox]").each(function(val,i){
                        ActivityForm.find(".sample"+val).prop("checked",($(this).val()==data.sttus))
                    })
                    ActivityForm.find('input[type="checkbox"]').prop('disabled',disablePastAndFuture)
                    ActivityForm.find("select[name=activity]").val(data.activity_list.id)
                },
                error:function (jqxHR, textStatus, errorThrown) 
                {
                     toasMessage(jqxHR.responseJSON.msg,"Error",jqxHR.responseJSON.icon)
                    $('#calendar').fullCalendar('refetchEvents');
                },
            });
        
        },

        loading: function(bool) {
            $('#loading').toggle(bool);
        },

        eventAllow: function(dropLocation, draggedEvent) {
        // Check if the dropped event's start date is in the past
        var currentDate = moment();
        var eventStartDate = moment(draggedEvent.start);

        // Allow the drop only if the event start date is not in the past
        return eventStartDate.isSameOrAfter(currentDate, 'day');
        },

}
}

let DefaultURL = clndr.attr("data-list").replace("user",clndr.attr("data-id"))
clndr.fullCalendar(settings(DefaultURL));



Activity.on('submit',function(e){
    e.preventDefault()
    $.ajax({
        url:  Activity.attr("action"),
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        
        if (data.msg) {
            Activity[0].reset()
            Activity.find('input[name=id]').val('')
            toasMessage(data.msg,"success",data.icon)
            $('#calendar').fullCalendar('refetchEvents');
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(jqxHR.responseJSON.msg,"Error",jqxHR.responseJSON.icon)
    })
})






ActivityForm.on('submit',function(e){

     // Verify that at least one checkbox is checked
     if (ActivityForm.find('input[type="checkbox"]:checked').length === 0) {
        toasMessage("Please select least one option before saving.", "Error", 'error');
        return false;
    }

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

 //colorpicker start
 $('.colorpicker-default').colorpicker({
    format: 'hex'
});
$('.colorpicker-rgba').colorpicker();