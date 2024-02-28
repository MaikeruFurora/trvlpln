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
        $(this).find("button[type=submit]").text("Generate Report").prop('disabled',false);
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