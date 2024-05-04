const getLocationAndInitMap = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;
            $('#viewActivity').find("input[name=latitude]").val(latitude);
            $('#viewActivity').find("input[name=longitude]").val(longitude);
            console.log(latitude, longitude);
            // Initialize the map centered at user's location
            let map = L.map('map').setView([latitude, longitude], 14.5);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add a marker at user's location
            let marker = L.marker([latitude, longitude]).addTo(map);
            // marker.bindPopup("<b>Your Location</b>").openPopup();
        }, function(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    document.getElementById("location").innerHTML = "User denied the request for Geolocation.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    document.getElementById("location").innerHTML = "Location information is unavailable.";
                    break;
                case error.TIMEOUT:
                    document.getElementById("location").innerHTML = "The request to get user location timed out.";
                    break;
                case error.UNKNOWN_ERROR:
                    document.getElementById("location").innerHTML = "An unknown error occurred.";
                    break;
            }
        });
    } else {
        document.getElementById("location").innerHTML = "Geolocation is not supported by this browser.";
    }
}