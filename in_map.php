<?php
$restaurant = $_GET['restaurant'];
$restaurant = explode('|', $restaurant);

$name = $restaurant[0];
$phone = $restaurant[1];
$street = $restaurant[2];
$city = $restaurant[3];
$state = $restaurant[4];
$lat = $restaurant[5];
$lng = $restaurant[6];

// echo($lat." ----- ".$lng);
?>

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
					<h2><?php echo($name); ?> - Mapa</h2>
				</div>
			</div>
		</section><!-- End Breadcrumbs Section -->

		<section class="inner-page">
			<div class="container-fluid" id="">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6 text-center">
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4sRcVBizhtGkhG-ppy2xVa5MygGgE33Y&callback=initMap&libraries=&v=weekly" async></script>
    <script>
        function initMap() {
            // The location of bussines_location
            const bussines_location = { lat: <?php echo($lat); ?>, lng: <?php echo($lng); ?> };
            // The map, centered at bussines_location
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: bussines_location,
            });
            // The marker, positioned at bussines_location
            const marker = new google.maps.Marker({
                position: bussines_location,
                title: "<?php echo($name); ?>",
                map: map,
            });
        }

		document.addEventListener('DOMContentLoaded', function(){
			//alert("hello world");
            initMap();
		}, false);
	</script>

</body>

</html>