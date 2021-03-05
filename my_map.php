<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<!-- Favicons -->
	<link href="assets/img/favicon.png" rel="icon">
	<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,600,600i,700,700i|Satisfy|Comic+Neue:300,300i,400,400i,700,700i" rel="stylesheet">
	<!-- Vendor CSS Files -->
	<link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
	<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
	<!-- Template Main CSS File -->
	<link href="assets/css/style.css" rel="stylesheet">

	<title>MELP</title>
	<link rel="stylesheet" href="css/my_styles.css">
</head>

<body>

	<?php include_once('top_menu.php'); ?>

	<main id="main">
		<!-- ======= Breadcrumbs Section ======= -->
		<section class="breadcrumbs">
			<div class="container">
				<div class="d-flex justify-content-between align-items-center">
					<h2>Locales</h2>
				</div>
			</div>
		</section><!-- End Breadcrumbs Section -->

		<section class="inner-page">
			<div class="container-fluid" id="">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6 text-center">
                        <div class="row">

                            <div class="alert alert-warning" role="alert" id="alert_data" style="display: none;">
                                <a class='close-alert' onclick='closeAlertData();'>
                                    <strong>Restaurantes locales</strong>
                                    <br>
                                    Promedio: ${avg}
                                    <br>
                                    Desviacion estandar: ${desv}
                                </a>
                            </div>

                            <div class="col-8">
                                <input class="form-control" type="number" id="radius" placeholder="Radio" value="100">
                            </div>
                            <div class="col-4">
                                <button type="button" class="btn btn-primary btn-block" onclick="getRestaurantsInRadius();">Buscar Restaurantes</button>
                            </div>
                        </div>
                        <br>
                        <div id="map"></div>
                    </div>
                    <div class="col-3"></div>
                </div>
			</div>
		</section>
	</main><!-- End #main -->

	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

	<!-- Vendor JS Files -->
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
	<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="assets/vendor/php-email-form/validate.js"></script>
	<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
	<!-- Template Main JS File -->
	<script src="assets/js/main.js"></script>

	<!-- My scripts -->
	<script src="js/my_scripts.js"></script>
    <div id="fb-root"></div>
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v10.0" nonce="NFScJfO0"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4sRcVBizhtGkhG-ppy2xVa5MygGgE33Y&callback=initMap&libraries=&v=weekly&sensor=false" async></script>
    <script>
        var my_location = {};
        var map = null;
        var marker = null;
        var circles = [];

        function getRestaurantsInRadius(){
            circles.forEach((circle) => {
                circle.setMap(null);
            });
            circles = [];

            var radius = document.getElementById('radius');
            if(radius.value === ""){
                radius = 0;
            }else{
                radius = parseInt(radius.value);
            }

            var sunCircle = {
                strokeColor: "pink",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "lightpink",
                fillOpacity: 0.35,
                map: map,
                center: { lat: marker.position.lat(), lng: marker.position.lng() },
                radius: radius * 2
            };

            cityCircle = new google.maps.Circle(sunCircle)
            cityCircle.bindTo('center', marker, 'position');
            circles.push(cityCircle);

            fetch('http://localhost:8080/melp/ws/getRestaurants.php')
			.then(function(response) {
				return response.text();
			})
			.then(function(data) {
                data = JSON.parse(data);
                let ratings = [];

                for(let i=0; i<data.length; i++){
                    let rest_location = data[i]['address']['location'];
                    let distance = calculateDistance(marker.position.lat(), marker.position.lng(), rest_location['lat'], rest_location['lng']);
                    distance = distance * 10000;

                    if(distance <= radius){
                        //console.log(data[i]['name']);
                        ratings.push(data[i]['rating']);

                        marker = new google.maps.Marker({
                            position: { lat: rest_location['lat'], lng: rest_location['lng'] },
                            title: data[i]['name'] + "\nRaiting: " + data[i]['rating'],
                            icon: {
                                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                                strokeColor: "darkgreen",
                                scale: 3
                            },
                            map: map,
                        });
                    }
                }
                //console.log(ratings);

                let avg = 0;
                for(let i=0; i<ratings.length; i++){
                    avg += ratings[i];
                }
                avg = avg / ratings.length;
                avg = avg.toFixed(2);
                //console.log(avg);

                let desv = [];
                for(let i=0; i<ratings.length; i++){
                    let ds = (ratings[i] - avg) * (ratings[i] - avg);
                    desv.push(ds);
                }
                desv = desv.reduce((a, b) => a + b, 0);
                desv = desv / ratings.length;
                desv = Math.sqrt(desv);
                desv = desv.toFixed(2);
                //console.log(desv);

                let alert_data = document.getElementById('alert_data');
                alert_data.innerHTML = "";
                alert_data.innerHTML = `<a class='close-alert' onclick='closeAlertData();'>
                                            <strong>Restaurantes locales</strong>
                                            <br>
                                            Promedio: ${avg}
                                            <br>
                                            Desviacion estandar: ${desv}
                                        </a>`;
                alert_data.style.display = 'block';
			})
			.catch(function(err) {
				console.error(err);
			});
        }

        function initMap() {
            my_location = { lat: LATITUDE, lng: LONGITUDE };
            
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: my_location,
            });

            marker = new google.maps.Marker({
                position: my_location,
                title: "Estoy aqui!",
                map: map,
            });
        }

		document.addEventListener('DOMContentLoaded', function(){
            getLocation();
            alert("CARGANDO MAPA");
            initMap();
		}, false);
	</script>
</body>

</html>