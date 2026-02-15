<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $subject = trim($_POST['subject']);
    $desc = trim($_POST['description']);
    $file = $_FILES['file'];

    // allowed extensions
    $allowed = ['pdf','jpg','png'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Validation
    if (empty($title)) $errors[] = "Title is required";
    if (!in_array($ext, $allowed)) $errors[] = "Only PDF, JPG, PNG files allowed";
    if ($file['size'] > 2 * 1024 * 1024) $errors[] = "File too large (max 2MB)";

    if (empty($errors)) {
        $fname = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $file['name']);

        if (!is_dir("../uploads")) mkdir("../uploads", 0777, true);

        if (move_uploaded_file($file['tmp_name'], "../uploads/$fname")) {
            $uid = $_SESSION['user_id'];
            $stmt = $conn->prepare("INSERT INTO notes (user_id, title, subject, description, file) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $uid, $title, $subject, $desc, $fname);
            if ($stmt->execute()) {
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Failed to upload file";
        }
    }
}
?>

<div class="form-container">
    <h2>Upload Note</h2>

    <?php 
    foreach ($errors as $e) {
        echo "<p class='error'>$e</p>";
    }
    ?>

    <form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input id="title" type="text" name="title" value="<?= htmlspecialchars($title ?? '') ?>" placeholder="Enter note title">

        <label for="subject">Subject:</label>
        <select id="subject" name="subject">
            <option <?= (isset($subject) && $subject=="Math") ? "selected" : "" ?>>Math</option>
            <option <?= (isset($subject) && $subject=="Science") ? "selected" : "" ?>>Science</option>
            <option <?= (isset($subject) && $subject=="Programming") ? "selected" : "" ?>>Programming</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description" placeholder="Enter note description"><?= htmlspecialchars($desc ?? '') ?></textarea>

        <label for="file">File:</label>
        <input id="file" type="file" name="file">

        <button type="submit">Upload</button>
    </form>
</div>

</body>
</html>
