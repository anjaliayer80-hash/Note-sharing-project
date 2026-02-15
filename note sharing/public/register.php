<?php
include("../includes/db.php");

$errors = [];
$name = $email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (empty($name)) $errors[] = "Name required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
    if (strlen($pass) < 4) $errors[] = "Password must be 4+ characters";

    if (empty($errors)) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO users (name,email,password) VALUES ('$name','$email','$hash')");
        header("Location: login.php");
        exit();
    }
}
include("../includes/header.php");
?>

<h2>Register</h2>
<?php foreach ($errors as $e) echo "<p style='color:red'>$e</p>"; ?>

<form method="post">
    Name:  <input id="name" type="text" name="name" autocomplete="off" placeholder="Enter your name">
Email:<input id="email" type="email" name="email" autocomplete="off" placeholder="Enter your email">  
 Password:  <input id="password" type="password" name="password" autocomplete="new-password" placeholder="Enter your password">
    <button type="submit">Register</button>
</form>
</body></html>