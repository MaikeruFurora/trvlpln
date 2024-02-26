let updateEventsURL = (newURL) => {
    clndr.fullCalendar('removeEventSources');
    clndr.fullCalendar('addEventSource',  {
             url:  newURL,
             type:'POST',
             data: {  
                 _token
             }
     });
 }
 $(".getBDO").on('click',function(e){
     e.preventDefault();
     // Set active class for the clicked element and change the background color
     $(".getBDO").removeClass('active');
     $(this).addClass('active');

     let url =clndr.attr("data-list").replace("user", $(this).attr("data-id"));
     updateEventsURL(url);
     
 })

clndr.fullCalendar('off', 'eventClick');
clndr.fullCalendar('on', 'eventClick', function(event, jsEvent, view) {
     $("#readOnlyActivity").modal("show");
     $("#readOnlyActivityLabel").text("View Activity");
     let updateUrl  = clndr.attr("data-info").replace("param",event.id)
         $.ajax({
             url: updateUrl,
             type:"GET",
             dataType:'json',
             success:function(data){
                 for (const key in data) {
                     if (data.hasOwnProperty(key)) { // This check is to ensure that the key belongs to the object itself and not its prototype chain
                         const element = data[key];
                         $("."+key).each(function() {
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
                 $("#readOnlyActivity").find(".activity_list").html(`<strong>Activity:</strong> <span class="highlight">${data.activity_list.name}</span>`);
             },
             error:function (jqxHR, textStatus, errorThrown){
                  toasMessage(jqxHR.responseJSON.msg,"Error",jqxHR.responseJSON.icon)
                clndr.fullCalendar('refetchEvents');
             },
         });
 });

//  clndr.fullCalendar('option', 'responsive', {
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
    e.preventDefault()
    let url_string = $(this).attr("action");
    let start      = $(this).find("input[name='start']").val()
    let end        = $(this).find("input[name='end']").val()
    let _token     = $(this).find("input[name='_token']").val()
    let adsURL     = url_string+"?_token="+_token+"&start="+start+"&end="+end;

    CoreModel.loadToPrint(adsURL)
})