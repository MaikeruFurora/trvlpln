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
                         // console.log(key, element);
                         $("."+key).each(function() {
                         if (element === null) {
                             $(this).html(`<strong>${key.toUpperCase()}:</strong> <span class="highlight">N/A</span>`);
                         } else if (moment(element, moment.ISO_8601, true).isValid()) {
                             $(this).html(`<strong>${key.toUpperCase()}:</strong> <span class="highlight">${moment(element).format('LL')}</span>`);
                         } else {
                             $(this).html(`<strong>${key.toUpperCase()}:</strong> <span class="highlight">${element.toUpperCase()}</span>`);
                         }
                     });
                     }
                 }
             
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
 