<?php
include("../includes/db.php");
include("../includes/header.php");

// Check if 'id' exists and is a valid integer
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("<p style='color:red; text-align:center;'>Invalid note ID.</p>");
}

// Fetch the note safely
$stmt = $conn->prepare("SELECT * FROM notes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$note = $res->fetch_assoc();
$stmt->close();

// Check if note exists
if (!$note) {
    die("<p style='color:red; text-align:center;'>Note not found.</p>");
}
?>

<div class="view-container">
    <span class="subject-badge <?= htmlspecialchars($note['subject']) ?>"><?= htmlspecialchars($note['subject']) ?></span>

    <h2><?= htmlspecialchars($note['title']) ?></h2>
    <p><?= nl2br(htmlspecialchars($note['description'])) ?></p>
    <a class="btn file-btn" href="../uploads/<?= htmlspecialchars($note['file']) ?>" target="_blank">Download File</a>
</div>
</body></html>
