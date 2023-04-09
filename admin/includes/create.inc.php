<?php

include_once "dbh.inc.php";

$pts = [];

$array = $_POST;
unset($array["driver"]);
$driverName = $_POST['driver'];
foreach ($array as $key) {
	$sql = mysqli_query($conn, "SELECT * FROM points WHERE id = $key");
	$row = mysqli_fetch_array($sql);
	$lat = $row['lat'];
	$lon = $row['lon'];
	$street = $row['street'];
	$time = substr($row['worktime'], 0, 2) . ":" . substr($row['worktime'], 2);
	$newdata = ['lat'=>$lat,'lon'=>$lon,'time'=>$time, 'street'=>$street];
	array_push($pts, $newdata);
}

// Функция для проверки, можно ли посетить точку в заданное время
function canVisit($point, $time)
{
    if ($point['time'] <= $time) {
		return True;
	} else {
		return False;
	}
}

function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
{
    $radlat1 = pi() * $lat1 / 180;
    $radlat2 = pi() * $lat2 / 180;
    $theta = $lon1 - $lon2;
    $radtheta = pi() * $theta / 180;
    $dist = sin($radlat1) * sin($radlat2) + cos($radlat1) * cos($radlat2) * cos($radtheta);
    $dist = acos($dist);
    $dist = $dist * 180 / pi();
    $dist = $dist * 60 * 1.1515;
    if ($unit == "K") {
        $dist = $dist * 1.609344;
    } elseif ($unit == "N") {
        $dist = $dist * 0.8684;
    }
    return round($dist, 2);
}

// Вводные данные
$points = $pts;

// Рассчитываем расстояния между всеми парами точек
$distances = [];
foreach ($points as $i => $point1) {
    foreach ($points as $j => $point2) {
        if ($i != $j) {
            $distance = distance($point1['lat'], $point1['lon'], $point2['lat'], $point2['lon']);
            $distances[$i][$j] = $distance;
        }
    }
}

// Находим кратчайший маршрут методом жадного выбора
$visited = [];
$current = 0;
$time = '05:00'; // начинаем с 5:00 утра
while (count($visited) < count($points)) {
    $minDistance = INF;
    $next = null;
    foreach ($distances[$current] as $j => $distance) {
        if (!in_array($j, $visited) && canVisit($points[$j], $time) && $distance < $minDistance) {
        	echo $time . "<br>";
            $minDistance = $distance;
            $next = $j;
        }
    }
    if ($next === null) {
    	echo "НЕТ ДОСТУПНЫХ <br>";
        // нет доступных точек, выбираем любую недоступную точку
        foreach ($points as $j => $point) {
            if (!in_array($j, $visited)) {
                $next = $j;
                break;
            }
        }
    }
    $visited[] = $next;
    $current = $next;
    $secs = strtotime("00:15")-strtotime("00:00");
	$time = date("H:i",strtotime($time)+$secs);
}

// Выводим кратчайший маршрут
$route = [];
foreach ($visited as $i) {
    $route[] = $points[$i];
}

$i = 0;
echo "Driver: " . $driverName . "<br><br>";
foreach ($route as $r) {
	if ($i == 0) {
		$fn = 1;
	} else {
		$fn = 0;
	}
	echo $fn . "- fn<br>";
	echo $i . "- i<br>";
	$rLat = $r['lat'];
	$rLon = $r['lon'];
	$rTime = $r['time'];
	$rStreet = $r['street'];
	mysqli_query($conn, "INSERT INTO routes (userid, lon, lat, ordernum, worktime, fornow, street) VALUES ('$driverName','$rLon','$rLat','$i','$rTime','$fn','$rStreet')");
	$i++;
}

header("location: ../create.php");