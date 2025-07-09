
<?php
// teachers_list_admin.php: Show teachers with delete option for admin
// Dynamically resolve path for teachers.json and photo URLs
$baseDir = dirname(__DIR__);
$teachersFile = $baseDir . '/teachers.json';
$teachers = file_exists($teachersFile) ? json_decode(file_get_contents($teachersFile), true) : [];

// Determine photo URL prefix based on where this file is included from
$photoPrefix = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) ? '../' : 'admin/../';
$deleteAction = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) ? 'delete_teacher.php' : 'admin/delete_teacher.php';

if (empty($teachers)) {
    echo '<p>No teachers found.</p>';
} else {
    echo '<div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Photo</th><th>Name</th><th>Position/Subject</th><th>Action</th></tr></thead><tbody>';
    foreach ($teachers as $index => $teacher) {
        echo '<tr>';
        // Use correct photo path for both admin and main site includes
        $photoPath = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false)
            ? '../' . htmlspecialchars($teacher['photo'])
            : htmlspecialchars($teacher['photo']);
        echo '<td><img src="' . $photoPath . '" alt="Photo" style="height:50px;width:50px;object-fit:cover;border-radius:50%;"></td>';
        echo '<td>' . htmlspecialchars($teacher['name']) . '</td>';
        echo '<td>' . htmlspecialchars($teacher['position']) . '</td>';
        echo '<td>';
        $deleteAction = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false)
            ? 'delete_teacher.php'
            : 'admin/delete_teacher.php';
        echo '<form method="POST" action="' . $deleteAction . '" onsubmit="return confirm(\'Are you sure you want to delete this teacher?\');" style="display:inline;">';
        echo '<input type="hidden" name="index" value="' . $index . '">';
        echo '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
}
