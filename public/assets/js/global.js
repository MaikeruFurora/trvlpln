const CoreModel = {
    currentDateTime:null,
    token: $("meta[name='_token']").attr("content"), //token
    calendar: $("#calendar"),
    booking:[],
    toasMessage : (heading,text,icon) =>{
        $.toast({
            heading,text,icon,
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600',  // To change the background
            position: 'top-right',
            icon:icon.toLowerCase()
        })
    },
    loadToPrint:(url) =>{
        $("<iframe>")             // create a new iframe element
            .hide()               // make it invisible
            .attr("src", url)     // point the iframe to the page you want to print
            .appendTo("body");    // add iframe to the DOM to cause it to load the page
        },
        defaultTime: () => {
            // Get current time
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');  // Ensure 2 digits
            const minutes = String(now.getMinutes()).padStart(2, '0');  // Ensure 2 digits
            
            // Set default value of time_from to current time
            const timeFromInput = document.getElementById('time_from');
            timeFromInput.value = `${hours}:${minutes}`;
            
            // Automatically set time_to 20 minutes after time_from
            const timeToInput = document.getElementById('time_to');
            timeToInput.value = CoreModel.getTimePlusMinutes(20);  // Adds 20 minutes to the current time
            
            // Update time_to when time_from is changed
            timeFromInput.addEventListener('change', function() {
                const timeFrom = this.value;
            timeToInput.value = CoreModel.getTimePlusMinutes(20, timeFrom);
        });
    },
    // Fetching time from a public time API
    fetchTime: () => {
        return fetch('https://worldtimeapi.org/api/timezone/Etc/UTC')
            .then(response => response.json())
            .then(data => {
                let currentTime = moment(data.utc_datetime);
                return currentTime;  // Return the moment object with the fetched time
            })
            .catch(error => {
                console.error('Error fetching time:', error);
                return null;  // Return null or handle the error properly
            });
    },
    getTimePlusMinutes: (addMinutes, baseTime) => {
        const now = baseTime ? new Date(`1970-01-01T${baseTime}:00`) : new Date();
        now.setMinutes(now.getMinutes() + addMinutes);
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    },
    // Function to add minutes to a given time
    addMinutesToTime: (timeString, minutesToAdd) => {
        const [hours, minutes] = timeString.split(':').map(Number);
        const date = new Date();
        date.setHours(hours, minutes, 0, 0);
        date.setMinutes(date.getMinutes() + minutesToAdd);
        const newHours = date.getHours().toString().padStart(2, '0');
        const newMinutes = date.getMinutes().toString().padStart(2, '0');
        return `${newHours}:${newMinutes}`;
    },
    dateTimeSetting :{
        datepicker: true,
        timepicker: false,
        minDate: moment(),
        format: 'Y-m-d',
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
    },
    calendarSettings:(getDataURL,defaultView = 'basicWeek') => {
        return {
            displayEventTime: false,
            themeSystem: 'bootstrap',
            timeZone: 'UTC',
            defaultView:defaultView,//agendaWeek
            aspectRatio: 1.5, // Adjust as needed
            height: 750, // or a specific value like 'auto', 'parent', or a number    
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
                    buttonText: 'Today', // Rename the button text
                }
            },
            minTime: '07:00:00', // Set the minimum time to display (e.g., 8:00 AM)
            maxTime: '20:00:00', // Set the maximum time to display (e.g., 6:00 PM)
            editable: true,  // Disable event resizing
            // eventStartEditable: false,  // Disable dragging
            // droppable: false,  // Disable dragging external items
            hiddenDays: [0],
            allDaySlot: false,
            selectable: true,
            events: {
                url:  getDataURL,
                type:'POST',
                data: {  
                    _token: CoreModel.token
                }
            },
            businessHours: {
                start: moment().format('HH:mm'), /* Current Hour/Minute 24H format */
                end: '20:00', // 5pm? set to whatever
                dow: [0,1,2,3,4,5,6] // Day of week. If you don't set it, Sat/Sun are gray too
            },
            eventDropStart: function(event, jsEvent, ui, view) {
                // Hide and destroy any visible popover to prevent stacking
                $('.popover').popover('hide');
                $('.popover').remove(); // Completely remove the popover elements
            },
            eventDropStop: function(event, jsEvent, ui, view) {
                // Hide and destroy any visible popover after dragging ends
                $('.popover').popover('hide');
                $('.popover').remove(); // Completely remove the popover elements
            },
            eventRender: function(event, element) {
                if ($(window).width() > 768) { // if not mobile screen
                    // First, destroy any existing popover for this element (just in case)
                    element.popover({
                        trigger: 'manual',
                        placement: 'top',
                        container: 'body',
                        animation: false,
                        delay: { show: 500, hide: 10 },
                        title: 'Details',
                        content: event.title+ (event.note === null ? '' : ' - '+event.note),
                    });
                    
                    // Ensure the cursor is set to pointer
                    element.css('cursor', 'pointer');
                    
                    // Display full event title with time
                    element.find('.fc-title').html(event.title);
                    
                    // Store the popover in event data to access it later
                    event.popover = element.popover();
                    
                    // Add delay before showing the popover
                    element.on('mouseenter', function () {
                        event.popover = setTimeout(function () {
                            element.popover('show');
                        }, 500);
                    });

                    // Clean up when leaving
                    element.on('mouseleave', function () {
                        clearTimeout(event.popover);
                        element.popover('hide');
                    });
                }

                // Add icon to the title
                let icon = document.createElement('i');
                icon.className = event.icon ?? 'fas fa-user-circle';
                icon.style.marginRight = '5px'; // Add space between icon and title
                icon.style.marginLeft = '5px'; // Add space between icon and title
                element.find('.fc-title').prepend(icon);
                element.find('.fc-title').css('display', 'flex');
                element.find('.fc-title').css('align-items', 'center'); // Align icon and title vertically
                element.find('.fc-title').css('justify-content', 'flex-start'); // Align icon and title to the left
            }, 
            eventContent: function(arg) {
                $('.popover').popover('hide');
                $('.popover').remove();
                // Create a custom element to display only the title
                let title = document.createElement('div');
                title.innerHTML = arg.event.title;  // Show only the title
            
                return { domNodes: [title] };  // Return only the title, no details

              },
        }
    },
    handleAjaxError:(jqxHR) =>{
        if (jqxHR.status === 419 || jqxHR.status === 401) {
            alert('Your session has expired. You will be redirected to the login page.');
            window.location.href = '/'; // Replace with your login page URL
        } else {
            CoreModel.toasMessage(jqxHR.responseJSON.msg, "warning", jqxHR.responseJSON.icon);
        }
    }
}

const getTime = async () => {
    let currentTime = await CoreModel.fetchTime();
    return currentTime
    // Do something with currentTime
}

console.log('current date',moment(getTime()).format('YYYY-MM-DD'));




window.onload = function() {
    CoreModel.defaultTime()

    let overlay = document.getElementById('overlay');

    // Show the overlay
    overlay.style.display = 'block';

    // Hide the overlay after 2 seconds
    setTimeout(function() {
        overlay.style.display = 'none';
    }, 1000);
};

$('.datepicker').datetimepicker(CoreModel.dateTimeSetting);