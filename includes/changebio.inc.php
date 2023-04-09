<?php
session_start();
require_once 'dbh.inc.php';

$id = $_SESSION['userid'];
$about = $_POST['about'];

$sql = "UPDATE users SET usersDesc = '$about' WHERE usersId = '$id';";
mysqli_query($conn, $sql);

header("location: ../profile.php?id=$id");
exit();