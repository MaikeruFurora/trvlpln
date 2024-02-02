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