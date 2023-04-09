<?php
session_start();
if (!isset($_SESSION['userid'])) {
	header("location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="css/style.css">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GIS_PROJECT</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=39c8f58b-ea57-4886-9b67-ffdd500cd28c&lang=ru_RU" type="text/javascript">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
</head>
<body>
<style type="text/css">
	* {
		overflow: visible;
	}
</style>
<?php 

include_once "includes/dbh.inc.php";

$uid = $_SESSION['userid'];

$sql =  mysqli_query($conn, "SELECT * FROM routes WHERE userid = '$uid' AND fornow = 1");
$rn = mysqli_fetch_array($sql);
if (!isset($rn)) {
	$rn['street'] = "N/A";
	$rn['worktime'] = "N/A";
}

$sql3 = mysqli_query($conn, "SELECT * FROM routes WHERE ordernum=(SELECT min(ordernum) FROM routes)");
$prev = mysqli_fetch_array($sql3);
if ($rn['ordernum'] == 0) {
	$prev['lat'] = "62.065036";
	$prev['lon'] = "129.754402";
} else {
	$sql4 = mysqli_query($conn, "SELECT * FROM routes WHERE ordernum=(SELECT min(ordernum) FROM routes WHERE done = 1)");
	$prev = mysqli_fetch_array($sql4);
}

$sql2 = "SELECT * FROM routes WHERE userid = '$uid' AND fornow != 1 AND done != 1 ORDER BY ordernum ASC";
$rows = mysqli_query($conn, $sql2);
$check = mysqli_fetch_array($rows);
if (!isset($check['userid']) && $rn['street']=="N/A") {
 	echo <<<PDO

		<div class="container-fluid bg-danger text-center py-2" style="position: absolute; height: 40px;">
			В данный момент заказов нет
		</div>

	PDO;
 } 

?>

<div class="bg popup-bg popup d-flex align-items-center justify-content-center" id="popup" style="position: absolute;">
	<div class="container-md bg-color-one rounded-4 p-2 w-50">
		<a class="mt-3 py-1" onclick="closePopup()" href="#"><i class="bi bi-x-circle display-5"></i></a>
		<a class="btn btn-primary mb-4 mt-2 py-3 clr-blue w-100" href="work.php"><h3>Точка закрыта</h3></a>
		<a class="btn btn-primary mb-4 py-3 clr-blue w-100" href="work.php"><h3>Точка не принимает заявку</h3></a>
		<a class="btn btn-primary mb-4 py-3 clr-blue w-100" href="work.php"><h3>Торговой точки нет</h3></a>
		<form action="includes/newp.inc.php" method="post" id="geoForm" name="geoForm">
			<input type="hidden" name="lat" id="lat" value="">
			<input type="hidden" name="lon" id="lon" value="">
		</form>
		<button class="btn btn-success mb-4 py-3 rounded-4 w-100" onclick="getLocation()"><h3>Появилась новая точка</h3></button>
		<a class="btn btn-primary mb-4 py-3 clr-blue w-100" href="work.php"><h3>Точка закрыта</h3></a>
	</div>

</div>

<div class="bg bg-color-one">
	<div class="container py-5">
		<div class="row">
			<div class="col-lg-4">
				<div class="col work-bg p-3 mb-2">
					<div class="item">
						<h2>Текущий адрес</h2>
					</div>
					<div class="container-fluid bg-tile text-dark rounded-4 pt-2 pb-1 mb-1">
						<h5>Улица: <?php echo $rn['street'];?></h5>
						<h5>Режим работы: 
							<?php
									if ($rn['worktime'] == "-1") {
									 	echo "Круглосуточно";
									} else {
										echo substr($rn['worktime'], 0, 2) . ":" . substr($rn['worktime'], 2);
									}
								?>
						</h5>
						<h5 id="routeDist">Расстояние: </h5>
						<?php if ($rn['street']!="N/A") : ?>
							<a href="includes/end.inc.php?id=<?php echo $rn['id'];?>" class="btn btn-outline-dark w-100 mb-2 rounded-pill">Закончить</a>
						<?php endif ?>
					</div>
				</div>

				<div class="col work-bg p-3 pb-1 mb-2">
					<div class="item">
						<h2>Следующие адреса</h2>
					</div>
					<?php
					$i = 0;
					foreach ($rows as $row) : ?>
						<div class="container-fluid bg-tile text-dark rounded-4 pt-2 pb-1 mb-3">
							<h5>Улица: <?php echo $row['street'];?></h5>
							<h5>Режим работы: 
								<?php
									if ($row['worktime'] == "-1") {
									 	echo "Круглосуточно";
									} else {
										echo substr($row['worktime'], 0, 2) . ":" . substr($row['worktime'], 2);
									}
								?>
							</h5>
						</div>
						<?php if (++$i == 4) break; ?>
					<?php endforeach; ?>
				</div>	
			</div>
			<div class="col-lg-8">
				<div class="row mb-2">
					<div class="col d-flex align-items-center">
						<a class="btn btn-primary mb-2 py-3 clr-blue" onclick="openPopup()"><h3>Что-то случилось?</h3></a>
					</div>
					<div class="col d-flex justify-content-end">
						<div class="dropdown">
							<button class="btn work-bg" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
								<a href="" class="d-flex align-items-center text-decoration-none text-light">
									<h3 class="me-2">Профиль</h3>
									<img src="http://via.placeholder.com/80x80" class="rounded-circle">	
								</a>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
								<li><a class="dropdown-item" href="index.php">Личный кабинет</a></li>
								<li><a class="dropdown-item" href="includes/logout.inc.php">Выйти из системы</a></li>
							</ul>
						</div>
					</div> 
				</div>
				<div id="map-container" class="map-container"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    // Функция ymaps.ready() будет вызвана, когда
    // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
    ymaps.ready(init);

    // Построение маршрута.
	// По умолчанию строится автомобильный маршрут.

    function init(){
        // Создание карты.
        var map = new ymaps.Map("map-container", {
            // Координаты центра карты.
            // Порядок по умолчанию: «широта, долгота».
            center: [62.027221, 129.732178],
            // Уровень масштабирования. Допустимые значения:
            // от 0 (весь мир) до 19.
            zoom: 13
        });

        map.controls.remove('geolocationControl');
        map.controls.remove('searchControl');
        map.controls.remove('trafficControl');
        map.controls.remove('typeSelector');
        map.controls.remove('zoomControl');
        map.controls.remove('rulerControl');

        var multiRoute = new ymaps.multiRouter.MultiRoute({   
		    // Точки маршрута. Точки могут быть заданы как координатами, так и адресом. 
		    referencePoints: [
		        [<?php echo $prev['lat'];?>,<?php echo $prev['lon'];?>],
		        [<?php echo $rn['lat'];?>,<?php echo $rn['lon'];?>]
		    ],
		    params: {
		        avoidTrafficJams: true
		    }
		}, {

			routeActiveStrokeWidth: 8,
			routeActiveStrokeStyle: 'solid',
			routeActiveStrokeColor: "#3BB143",

			// Автоматически устанавливать границы карты так,
			// чтобы маршрут был виден целиком.
			boundsAutoApply: true
		});

		multiRoute.model.events.add('requestsuccess', function() {
		    // Получение ссылки на активный маршрут.
		    var activeRoute = multiRoute.getActiveRoute();
		    // Вывод информации о маршруте.
		    var distance = activeRoute.properties.get("distance").text;
		    document.getElementById("routeDist").innerHTML = "Расстояние: " + distance;
		    console.log("Время прохождения: " + activeRoute.properties.get("duration").text);
		    // Для автомобильных маршрутов можно вывести 
		    // информацию о перекрытых участках.
		    if (activeRoute.properties.get("blocked")) {
		        console.log("На маршруте имеются участки с перекрытыми дорогами.");
		    }
		});

        map.geoObjects.add(multiRoute);
    }

    
	</script>
	<script type="text/javascript">
		let popup = document.getElementById('popup');

		function openPopup() {
			popup.classList.add("open-popup");
		}

		function closePopup() {
			popup.classList.remove("open-popup");
		}

	</script>
	<script>
		var x = document.getElementById("demo");

		function getLocation() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showPosition);
			} else { 
				x.innerHTML = "Geolocation is not supported by this browser.";
			}
		}

		function showPosition(position) {
			console.log(position.coords.latitude);
			console.log(position.coords.longitude);

			var lat = document.getElementById("lat");
			var lon = document.getElementById("lon");
			lat.value = position.coords.latitude;
			lon.value = position.coords.longitude;
			document.forms["geoForm"].submit();
		}
</script>
</body>