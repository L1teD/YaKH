<?php

require_once 'dbh.inc.php';

function emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat) {
	$result;
	if (empty($name) || empty($email) || empty($username) || empty($pwd) || empty($pwdRepeat)) {
		$result = true;
	} else {
		$result = false;
	}
	return $result;
}

function invalidUid($username) {
	$result;
	if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
		$result = true;
	} else {
		$result = false;
	}
	return $result;
}

function invalidEmail($email) {
	$result;
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$result = true;
	} else {
		$result = false;
	}
	return $result;
}

function pwdMatch($pwd, $pwdRepeat) {
	$result;
	if ($pwd !== $pwdRepeat) {
		$result = true;
	} else {
		$result = false;
	}
	return $result;
}

function uidExists($conn, $username, $email) {
	$sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../signup.php?error=stmtfailed");
		exit();
	}

	mysqli_stmt_bind_param($stmt, "ss", $username, $email);
	mysqli_stmt_execute($stmt);

	$resultData = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($resultData)) {
		return $row;
	} else {
		$result = false;
		return $result;
	}

	mysqli_stmt_close($stmt);
}

function createUser($conn, $name, $email, $username, $pwd, $usrtype) {
	$sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd, usersType) VALUES (?, ?, ?, ?, ?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("location: ../signup.php?error=stmtfailed");
		exit();
	}

	$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

	mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $username, $hashedPwd, $usrtype);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	header("location: ../signup.php?error=none");
	exit();
}

function emptyInputLogin($username, $pwd) {
	$result;
	if (empty($username) || empty($pwd)) {
		$result = true;
	} else {
		$result = false;
	}
	return $result;
}

function loginUser($conn, $username, $pwd) {
	$uidExists = uidExists($conn, $username, $username);

	if ($uidExists === false) {
		header("location: ../login.php?error=wronglogin");
		exit();
	}

	$pwdHashed = $uidExists["usersPwd"];
	$checkPwd = password_verify($pwd, $pwdHashed);

	if ($checkPwd === false) {
		header("location: ../login.php?error=wronglogin");
		exit();		
	} else if ($checkPwd === true) {
		session_start();
		$_SESSION['userid'] = $uidExists["usersId"];
		$_SESSION['useruid'] = $uidExists["usersUid"];
		$_SESSION['username'] = $uidExists["usersName"];
		$_SESSION['isworking'] = $uidExists["isWorking"];
		header("location: ../index.php");
		exit();		
	}

}

function showButtons($conn, $Sid, $Pid) {
	$sql = "SELECT * FROM uservotes WHERE userid = '$Sid' AND postid = '$Pid';";
	$query = mysqli_query($conn, $sql);
	$row = mysqli_fetch_array($query);

	if (empty($row['postid'])) {
		$rowPostId = 0;
	} else {
		$rowPostId = $row['postid'];
	}

	if (empty($row['userid'])) {
		$rowUserId = 0;
	} else {
		$rowUserId = $row['userid'];
	}

	if (empty($row['vote'])) {
		$rowVote = 2;
	} else {
		$rowVote = $row['vote'];
	}

	if ($rowPostId==$Pid && $rowUserId==$Sid && $rowVote==1) {
		echo <<<EOT
			<a href="includes/downvote.inc.php?uid=${Sid}&pid=${Pid}" class="btn btn-danger">-</a> 
		EOT;
	}

	else if ($rowPostId==$Pid && $rowUserId==$Sid && $rowVote=-1) {
		echo <<<EOT
			<a href="includes/upvote.inc.php?uid=${Sid}&pid=${Pid}" class="btn btn-success">+</a>
		EOT;
	} 

	else {
		echo <<<EOT
			<a href="includes/upvote.inc.php?uid=${Sid}&pid=${Pid}" class="btn btn-success">+</a>
			<a href="includes/downvote.inc.php?uid=${Sid}&pid=${Pid}" class="btn btn-danger">-</a>
		EOT;
	}
}

function showHashtags($string) {
	$htag = "#";
	$arr = explode(" ", $string);
	$arrc = count($arr);
	$i = 0;

	while ($i < $arrc) {
		if (substr($arr[$i], 0 ,1) === $htag) {
			$arr[$i] = "<a class='text-decoration-none text-dark' href=search.php?q=".substr($arr[$i],1).">$arr[$i]</a>";
		}

		$i++;
	}

	$string = implode(" ", $arr);
	echo $string;
}