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
<div class="bg bg-color-one d-flex align-items-center" id="first">
	<div class="container login">
	
			<div class="item">
				<img src="img/logo.png" style="height: 200px; width: 200px;">
			</div>
			<div class="item">
				<h1 class="text-center">Приветствуем в системе<br>ЯХК</h1>
			</div>
			<div class="item">
				<a class="btn btn-outline-warning rounded-pill" href="#second">Продолжить</a>
			</div>

	</div>
</div>

<div class="bg bg-color-two d-flex align-items-center" id="second">
	<div class="container">
	
			<div class="item">
				<h1>Войдите в систему</h1>
			</div>
			<div class="item">
				<form action="includes/login.inc.php" method="post">
					<div class="form-group d-flex flex-column justify-content-center">
					    <input type="username" name="uid" class="form-control rounded-pill text-light" placeholder="Ваш логин">
					    <br>
					    <input type="password" name="pwd" class="form-control rounded-pill text-light" placeholder="Ваш пароль">
					    <br>
					    <button name="submit" type="submit" class="btn btn-outline-warning rounded-pill" href="#first">Войти</button>
					</div>
				</form>
			</div>
	</div>
</div>


</body>

</html>