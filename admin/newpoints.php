<?php
session_start();
if (!isset($_SESSION['userid'])) {
	header("location: login.php");
}

include_once "includes/dbh.inc.php";
$rows = mysqli_query($conn, "SELECT * FROM newpoints");
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
</head>
<body>
<style type="text/css">
	* {
		overflow-y: visible;
	}
</style>
<div class="bg bg-color-one">
	<div class="container mt-5">
		<a class="btn btn-outline-light mb-2" href="index.php">Назад</a>
		<?php foreach ($rows as $row) : ?>
			<div class="work-bg px-3 pt-3 mb-2">
				<p>Широта: <?php echo $row['lat']; ?></p>
				<p>Долгота: <?php echo $row['lon']; ?></p>
				<p>Водитель: <?php echo $row['userid']; ?></p>
			</div>
		<?php endforeach; ?>
	</div>
</div>
</body>