let updateEventsURL = (newURL) => {
    $('#calendar').fullCalendar('removeEventSources');
    $('#calendar').fullCalendar('addEventSource',  {
            url:  newURL,
            type:'POST',
            data: {  
            _token
    }});
}
$(".getBDO").on('click',function(e){
    e.preventDefault()
    let url = $('#calendar').attr("data-list").replace("user",$(this).attr("data-id"))
    $(".getBDO").removeClass('active');
    $(this).addClass('active');
    updateEventsURL(url)
    
})
$(".getVisor").on('click',function(e){
    e.preventDefault()
    $(".getBDO").removeClass('active');
    let url = $('#calendar').attr("data-list").replace("user",$(this).attr("data-id"))
    updateEventsURL(url)
})