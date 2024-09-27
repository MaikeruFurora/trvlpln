let DefaultURL = CoreModel.calendar.attr("data-list").replace("user",CoreModel.calendar.attr("data-id"))
let defaultView  = ($(window).width() <= 600) ? 'basicDay' : 'basicWeek';
CoreModel.calendar.fullCalendar(CoreModel.calendarSettings(DefaultURL,defaultView));
let updateEventsURL = (newURL) => {
    CoreModel.calendar.fullCalendar('removeEventSources');
    CoreModel.calendar.fullCalendar('addEventSource',  {
             url:  newURL,
             type:'POST',
             data: {  
                _token: CoreModel.token
             }
     });
 }
 $(".getBDO").on('click',function(e){
     e.preventDefault();
     // Set active class for the clicked element and change the background color
     $(".getBDO").removeClass('active');
     $(this).addClass('active');

     let url =CoreModel.calendar.attr("data-list").replace("user", $(this).attr("data-id"));
     updateEventsURL(url);
     
 })

CoreModel.calendar.fullCalendar('off', 'eventClick');
CoreModel.calendar.fullCalendar('on', 'eventClick', function(event, jsEvent, view) {
    $("#readOnlyActivity").modal("show");
    $("#readOnlyActivityLabel").text("View Activity");
    $(this).popover('hide');
    let updateUrl = CoreModel.calendar.attr("data-info").replace("param", event.id);

    $.ajax({
        url: updateUrl,
        type: "GET",
        dataType: 'json'
    })
    .done(function(data) {
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                const element = data[key];
                $("." + key).each(function() {
                    if (element === null) {
                        $(this).html(`<strong>${key.toUpperCase()}:</strong> <span class="highlight">N/A</span>`);
                    } else if (moment.isMoment(element) || (typeof element === 'object' && moment(element, moment.ISO_8601, true).isValid())) {
                        $(this).html(`<strong>${key.toUpperCase()}:</strong> <span class="highlight">${moment(element).format('MM/DD/YYYY h:mm')}</span>`);
                    } else {
                        $(this).html(`<strong>${key.toUpperCase()}:</strong> <span class="highlight">${element.toString().toUpperCase()}</span>`);
                    }
                });
            }
        }
        $("#readOnlyActivity").find(".activity_list").html(`<strong>Activity:</strong> <span class="highlight">${data.activity_list_name}</span>`);
    })
    .fail(CoreModel.handleAjaxError)
    .always(function() {
        CoreModel.calendar.fullCalendar('refetchEvents');
    });

 });

//  CoreModel.calendar.fullCalendar('option', 'responsive', {
//      breakpointWidth: 480, // Breakpoint width in pixels for mobile devices
//      dayViewOnBreakpoint: true // Enable day view on the breakpoint
//  });
 
$("button[name=report]").on('click',function(e){
    e.preventDefault()
    $("#reportModal").modal('show')
    $('#date-range-start, #date-range-end').datetimepicker({
        datepicker: true,
        timepicker: false, // Disables time selection
        format: 'Y-m-d',
    });
})


$("#reportDateRangeForm").on('submit',function(e){
    $(this).find("button[type=submit]").text("Please wait...").prop('disabled',true);
    setInterval(() => {
        $(this).find("button[type=submit]").text("Get Report").prop('disabled',false);
    }, 7000);
    e.preventDefault()
    let url_string = $(this).attr("action");
    let start      = $(this).find("input[name='start']").val()
    let end        = $(this).find("input[name='end']").val()
    let _token     = $(this).find("input[name='_token']").val()
    let wrhs       = $(this).find("select[name='wrhs']").val()
    let adsURL     = url_string+"?_token="+_token+"&start="+start+"&end="+end+"&wrhs="+wrhs;

    CoreModel.loadToPrint(adsURL)
})

$("#reportDateRangeExcelForm").on('submit',function(e){
    $(this).find("button[type=submit]").text("Please wait...").prop('disabled',true);
    setInterval(() => {
        $(this).find("button[type=submit]").text("Generate Report").prop('disabled',false);
    }, 7000);
    e.preventDefault()
    let url_string = $(this).attr("action");
    let start      = $(this).find("input[name='start']").val()
    let end        = $(this).find("input[name='end']").val()
    let _token     = $(this).find("input[name='_token']").val()
    let user       = $(this).find("select[name='user']").val()
    let adsURL     = url_string+"?_token="+_token+"&start="+start+"&end="+end+"&user="+user;

    let link = $("<a>", {
        href: adsURL,
        // target: '_blank',
        text: "Download Report"
    });
    $(this).find("button[type=submit]").after(link);
})