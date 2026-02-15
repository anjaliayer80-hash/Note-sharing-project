<?php
include("../includes/db.php");
include("../includes/header.php");

$res = mysqli_query($conn, "SELECT notes.*, users.name FROM notes JOIN users ON notes.user_id = users.id");
?>

<h2>Available Notes</h2>

<div class="notes-grid">
<?php while ($row = mysqli_fetch_assoc($res)): ?>
    <div class="note-card">
        <b><?= htmlspecialchars($row['title']) ?></b>
        <span class="subject">(<?= htmlspecialchars($row['subject']) ?>)</span>
        <p>By: <?= htmlspecialchars($row['name']) ?></p>
        <a class="btn view-btn" href="view.php?id=<?= $row['id'] ?>">View</a>
    </div>
<?php endwhile; ?>
</div>


</body></html>
