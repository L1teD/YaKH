<?php
session_start();
if (!isset($_SESSION['userid'])) {
	header("location: ../login.php");
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
<?php 
include_once "../includes/dbh.inc.php";
$opts = mysqli_query($conn, "SELECT * FROM points");
$drvs = mysqli_query($conn, "SELECT * FROM users");
?>
<div class="bg bg-color-one">
	<div class="container mt-5">
		<a class="btn btn-outline-light" href="index.php">Назад</a>
		<h1 class="py-1">Конструктор маршрута</h1>
		<hr>
		<button onclick='addInput()' class="btn btn-outline-light">Добавить точку</button>
		<button onclick='deleteInput()' class="btn btn-outline-light">Удалить точку</button>
		<form action="includes/create.inc.php" method="post">
			<div class="input-group my-3">
				<div id='input-cont'>
					<!--Input container-->
					<label for="basic-url" class="form-label">Водитель</label>
					<select class="form-select mb-2" name="driver">
						<?php foreach ($drvs as $drv)  : ?>
							<option value="<?php echo $drv['usersId'];?>" class="text-dark"><?php echo $drv['usersName']; ?></option>
						<?php endforeach; ?>
					</select>
					<label for="basic-url" class="form-label mt-3">Точки</label>
					<select class="form-select mb-2"  id="elem1" name="point-1">
						<?php foreach ($opts as $opt)  : ?>
							<option value="<?php echo $opt['id']; ?>" class="text-dark"><?php echo $opt['name']; ?>, [<?php echo $opt['street']; ?>]</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<button class="btn btn-outline-light">Создать маршрут</button>
		</form>
	</div>
</div>

<script>
    const container = document.getElementById('input-cont');

    var number = 1;
    // Call addInput() function on button click
    function addInput(){
        // Get the element
		var elem = document.querySelector('#elem1');
		number++;

		// Create a copy of it
		var clone = elem.cloneNode(true);
		clone.name = "point-" + number;

		container.appendChild(clone);
    }

    function deleteInput() {
    	var cCount = document.getElementById("input-cont").childElementCount;
    	if (cCount > 4) {
    		var select = document.getElementById('input-cont');
			select.removeChild(select.lastChild);
    	} else {
    		alert("У вас одна точка!");
    	}
	}
</script>
</body>
