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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    mysqli_query($conn, "UPDATE notes SET title='$title' WHERE id=$id");
    header("Location: dashboard.php");
}
include("../includes/header.php");
?>

<form method="post">
    Title: <input name="title" value="<?= $note['title'] ?>">
    <button>Update</button>
</form>
</body></html>
