<?php
session_start();
include_once "../includes/dbh.inc.php";

$id = $_GET['id'];
$uid = $_SESSION['userid'];
mysqli_query($conn, "UPDATE routes SET done = 1, fornow = 0 WHERE id=$id");
mysqli_query($conn, "UPDATE routes SET fornow = 1 WHERE ordernum=(SELECT min(ordernum) FROM routes WHERE done != 1) AND done!=1 AND userid=$uid");
header("location: ../work.php");