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
</head>
<body>
<style type="text/css">
	* {
		overflow-y: visible;
	}
</style>
<div class="bg bg-color-one">
	<div class="container mt-5">
		<div class="row">
			<div class="col-lg-4 profile-bg p-3">
				<div class="item">
					<h1 class="mb-4">Профиль</h1>
				</div>
				<div class="item">
					<img src="http://via.placeholder.com/150x150"><br>
				</div>
				<div class="item">
					<h4 class="mt-3 py-1"><?php echo $_SESSION['username'];?></h4>
				</div>
			</div>
			<div class="col-lg-8 p-3">
				<a class="btn btn-primary w-100 mb-2 py-4" href="work.php"><h1>Вызов</h1></a>
				<a class="btn btn-outline-light w-100 mb-2 py-4" href="loading.php"><h1>Порядок погрузки</h1></a>
				<a class="btn btn-outline-light w-100 mb-2 py-4"><h1>Штрафы</h1></a>
			</div>
		</div>
	</div>
</div>
</body>