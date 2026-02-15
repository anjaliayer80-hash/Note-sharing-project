<?php
include("../includes/auth.php");
include("../includes/db.php");

$id = $_GET['id'];
$uid = $_SESSION['user_id'];
$role = $_SESSION['role'];

$res = mysqli_query($conn, "SELECT * FROM notes WHERE id=$id");
$note = mysqli_fetch_assoc($res);

if ($note['user_id'] != $uid && $role != 'admin') {
    die("Access denied");
}

mysqli_query($conn, "DELETE FROM notes WHERE id=$id");
header("Location: dashboard.php");
exit();
