<?php
$file = '../teachers.json';
$teachers = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
if (empty($teachers)) {
    echo '<div class="alert alert-secondary">No teachers added yet.</div>';
} else {
    echo '<div class="row">';
    foreach ($teachers as $index => $teacher) {
        echo '<div class="col-md-4 mb-3">';
        echo '<div class="card h-100">';
        if (!empty($teacher['photo'])) {
            echo '<img src="' . htmlspecialchars($teacher['photo']) . '" class="card-img-top" style="height:220px;object-fit:cover;">';
        }
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($teacher['name']) . '</h5>';
        echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($teacher['position']) . '</h6>';
        echo '<p class="card-text">' . htmlspecialchars($teacher['bio']) . '</p>';
        // Add delete button (admin only, or always if this is for admin)
        echo '<form method="POST" action="delete_teacher.php" onsubmit="return confirm(\'Are you sure you want to delete this teacher?\');" style="margin-top:10px;">';
        echo '<input type="hidden" name="index" value="' . $index . '">';
        echo '<button type="submit" class="btn btn-danger btn-sm w-100">Delete</button>';
        echo '</form>';
        echo '</div></div></div>';
    }
    echo '</div>';
}
?>
