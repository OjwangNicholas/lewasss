<?php
// Simple admin dashboard for managing teachers
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Lewa Secondary School</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .dashboard-container { max-width: 900px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .header { margin-bottom: 30px; }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="header d-flex justify-content-between align-items-center">
        <h2>Admin Dashboard</h2>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
    <div class="mb-4">
        <a href="../admin-blog.php" class="btn btn-success">Go to Blog Management</a>
        <span class="text-muted ml-2">(Add, edit, or delete blog posts)</span>
    </div>
    <h4>Add New Teacher</h4>
    <form action="add_teacher.php" method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group col-md-6">
                <label for="position">Position/Subject</label>
                <input type="text" class="form-control" id="position" name="position" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="photo">Photo</label>
                <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Teacher</button>
    </form>
    <h4>Current Teachers</h4>
    <div id="teachers-list">
    <?php include 'teachers_list.php'; ?>
    </div>
</div>
</body>
</html>
