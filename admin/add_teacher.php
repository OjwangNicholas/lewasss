<?php
// Handle teacher addition
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $position = trim($_POST['position']);
    $bio = trim($_POST['bio']);
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = 'uploads/' . uniqid('teacher_', true) . '.' . $ext;
        if (!is_dir('uploads')) mkdir('uploads');
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }
    $teacher = [
        'name' => $name,
        'position' => $position,
        'bio' => $bio,
        'photo' => $photo
    ];
    $file = 'teachers.json';
    $teachers = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $teachers[] = $teacher;
    file_put_contents($file, json_encode($teachers, JSON_PRETTY_PRINT));
    header('Location: index.php');
    exit();
}
?>
