<?php
session_start();
require_once 'dbh.inc.php';

$uname = $_SESSION['useruid'];
$pid = $_GET['id'];
$comment = $_POST['comment'];

$sql = "INSERT INTO comments (pageid, name, comment) VALUES ('$pid', '$uname', '$comment');";
mysqli_query($conn, $sql);

header("location: ../cardpage.php?id=$pid");
exit();