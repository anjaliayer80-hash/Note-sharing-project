<?php
include("../includes/auth.php");
include("../includes/db.php");
include("../includes/header.php");

$uid = $_SESSION['user_id'];
$role = strtolower($_SESSION['role']); // normalize role to lowercase

// Fetch notes
if ($role === 'admin') {
    $res = mysqli_query($conn, "SELECT * FROM notes ORDER BY id DESC");
} else {
    $res = mysqli_query($conn, "SELECT * FROM notes WHERE user_id=$uid ORDER BY id DESC");
}

// Check for query errors
if (!$res) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<h2>Dashboard</h2>
<a class="upload-btn" href="create.php">+ Upload Note</a>

<?php 
if(mysqli_num_rows($res) == 0){
    echo "<p style='text-align:center; margin-top:30px;'>No notes uploaded yet.</p>";
}
?>

<div class="notes-grid">
<?php while ($n = mysqli_fetch_assoc($res)): 
    $filePath = "../uploads/" . $n['file'];
    $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
?>
    <div class="note-card">
        <div class="note-header">
            <b><?= htmlspecialchars($n['title']) ?></b>
            <span class="subject-badge <?= htmlspecialchars($n['subject']) ?>">
                <?= htmlspecialchars($n['subject']) ?>
            </span>
        </div>

        <p class="note-desc"><?= htmlspecialchars($n['description']) ?></p>

        <?php 
        if(file_exists($filePath)) {
            if(in_array($fileExt, ['jpg','jpeg','png','gif'])) {
                echo "<img class='note-img' src='$filePath'>";
                echo "<a class='btn file-btn' href='$filePath' target='_blank'>View Image</a>";
            } elseif($fileExt == 'pdf') {
                echo "<a class='btn file-btn' href='$filePath' target='_blank'>View PDF</a>";
            } else {
                echo "<a class='btn file-btn' href='$filePath' target='_blank'>Download File</a>";
            }
        } else {
            echo "<span class='file-missing'>File missing!</span>";
        }
        ?>

        <div class="note-actions">
            <a class="btn edit-btn" href="edit.php?id=<?= $n['id'] ?>">✏️ Edit</a>
            <a class="btn delete-btn" href="delete.php?id=<?= $n['id'] ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
        </div>
    </div>
<?php endwhile; ?>
</div>
</body>
</html>
