var LATITUDE = 0;
var LONGITUDE = 0;

function shareOnFacebook(url){
    window.open(
        'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url),
        'facebook-share-dialog',
        'width=626,height=436');
    return false;
}

function showOnMap(string_data){
    window.location.href = "in_map.php?restaurant=" + string_data;
    //window.open("in_map.php?restaurant=" + string_data);
    //console.log(string_data)
}

function getTemplate(restaurant){
    let contact = restaurant['contact'];
    let address = restaurant['address'];
    let location = address['location'];
    let string_data = "";
    string_data += restaurant['name'] + "|" + contact['phone'] + "|" + address['street'] + "|" + address['city'] + "|" + address['state'] + "|" 
                    + location['lat'] + "|" + location['lng'];
    string_data = encodeURI(string_data);

    let template = 
    `<div class='card my-card-class'>
        <div class='card-body'>
            <input type='hidden' id='restaurant_id' value='${restaurant['id']}'>
            <h5 class='card-title'>${restaurant['name']}</h5>
            <a target='blank' href='${contact['site']}' class='card-link'>Web</a>
            <a href='mailto:${contact['email']}' class='card-link'>Correo</a>
            <p class='my-card-text'>${contact['phone']}</p>
            <p class='my-card-text'>${address['street']}</p>
            <p class='my-card-text'>${address['city']}</p>
            <p class='my-card-text'>${address['state']}</p>
            <p class='my-card-text'>Rating: ${restaurant['rating']}</p>
        </div>

        <div class='row' style='margin-bottom: 10px;'>
            <div class='col-12 text-center'>
                <a class='btn my-button btn-map' onclick='showOnMap(\"${string_data}\")'>
                    <i class='bx bi-map'></i> Mapa
                </a>
            </div>
        </div>

    </div>`;
    return template;
}

function loadCards(restaurants){
    restaurants = JSON.parse(restaurants);
    let container = document.getElementById('container');
    container.innerHTML = "";

    for(let i=0; i<restaurants.length; i++){
        let template = getTemplate(restaurants[i]);
        container.innerHTML += template;
    }
}

function showPosition(position) {
    //alert(position.coords.latitude + " ----- " + position.coords.longitude);
    LATITUDE = position.coords.latitude;
    LONGITUDE = position.coords.longitude;
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        alert("Geolocation is not supported by this browser.");
    }
}

function calculateDistance(lat1, long1, lat2, long2){
    //radians
    lat1 = (lat1 * 2.0 * Math.PI) / 60.0 / 360.0;
    long1 = (long1 * 2.0 * Math.PI) / 60.0 / 360.0;
    lat2 = (lat2 * 2.0 * Math.PI) / 60.0 / 360.0;
    long2 = (long2 * 2.0 * Math.PI) / 60.0 / 360.0;
    // use to different earth axis length
    var a = 6378137.0;
    var b = 6356752.3142;
    var f = (a-b) / a;
    var e = 2.0*f - f*f;

    var beta = (a / Math.sqrt( 1.0 - e * Math.sin( lat1 ) * Math.sin( lat1 )));
    var cos = Math.cos( lat1 );
    var x = beta * cos * Math.cos( long1 );
    var y = beta * cos * Math.sin( long1 );
    var z = beta * ( 1 - e ) * Math.sin( lat1 );

    beta = ( a / Math.sqrt( 1.0 -  e * Math.sin( lat2 ) * Math.sin( lat2 )));
    cos = Math.cos( lat2 );
    x -= (beta * cos * Math.cos( long2 ));
    y -= (beta * cos * Math.sin( long2 ));
    z -= (beta * (1 - e) * Math.sin( lat2 ));

    return Math.abs((Math.sqrt( (x*x) + (y*y) + (z*z) )/1000));  
}

function closeAlertData(){
    let alert_data = document.getElementById('alert_data');
    alert_data.style.display = 'none';
}