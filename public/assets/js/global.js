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

    loadToPrint:(url) =>{
        $("<iframe>")             // create a new iframe element
            .hide()               // make it invisible
            .attr("src", url)     // point the iframe to the page you want to print
            .appendTo("body");    // add iframe to the DOM to cause it to load the page
    },
    
}