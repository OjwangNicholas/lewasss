<?php
// delete_teacher.php: Remove a teacher by index from teachers.json
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $teachersFile = '../teachers.json';
    $teachers = file_exists($teachersFile) ? json_decode(file_get_contents($teachersFile), true) : [];
    $index = (int)$_POST['index'];
    if (isset($teachers[$index])) {
        // Optionally delete the photo file
        $photoPath = '../' . $teachers[$index]['photo'];
        if (file_exists($photoPath)) {
            @unlink($photoPath);
        }
        array_splice($teachers, $index, 1);
        file_put_contents($teachersFile, json_encode($teachers, JSON_PRETTY_PRINT));
    }
}
// Redirect back to referring page, or index.php as fallback
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header('Location: ' . $redirect);
exit();
