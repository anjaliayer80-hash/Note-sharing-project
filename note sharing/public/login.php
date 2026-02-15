<?php
include("../includes/db.php");
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $pass  = trim($_POST['password']);

    if (empty($email) || empty($pass)) {
        $error = "Please fill in both fields.";
    } else {
        // Fetch user safely
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        if ($user && password_verify($pass, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = strtolower($user['role']);

            // Redirect based on role
            if ($_SESSION['role'] === 'admin') {
                header("Location: ../private/dashboard.php");
            } else {
                header("Location: ../private/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }

        $stmt->close();
    }
}

include("../includes/header.php");
?>

<h2>Login</h2>

<div class="form-container">
    <?php if($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="email">Email:</label>
<input type="email" name="email" autocomplete="off" placeholder="Enter your email" required
       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

        <label for="password">Password:</label>
<input type="password" name="password" autocomplete="new-password" placeholder="Enter your password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
