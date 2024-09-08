const toasMessage = (heading,text,icon) =>{
    $.toast({
        heading,text,icon,
        loader: true,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'top-right',
        icon:icon.toLowerCase()
    })
}

let _token = $("meta[name='_token']").attr("content")


const CoreModel = {
    booking:[],
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
    getTimePlusMinutes: (addMinutes, baseTime) => {
        const now = baseTime ? new Date(`1970-01-01T${baseTime}:00`) : new Date();
        now.setMinutes(now.getMinutes() + addMinutes);
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    },
    
}

