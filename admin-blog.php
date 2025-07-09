<?php
// Simple admin blog manager for blog-posts.json
$blogFile = __DIR__ . '/blog-posts.json';
$posts = file_exists($blogFile) ? json_decode(file_get_contents($blogFile), true) : [];
$editIndex = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$deleteIndex = isset($_GET['delete']) ? (int)$_GET['delete'] : null;

// Handle delete
if ($deleteIndex !== null && isset($posts[$deleteIndex])) {
    array_splice($posts, $deleteIndex, 1);
    file_put_contents($blogFile, json_encode($posts, JSON_PRETTY_PRINT));
    header('Location: admin-blog.php');
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'image' => $_POST['image'],
        'date' => $_POST['date'],
        'author' => $_POST['author'],
        'comments' => (int)$_POST['comments']
    ];
    if (isset($_POST['edit_index']) && $_POST['edit_index'] !== '') {
        $posts[(int)$_POST['edit_index']] = $data;
    } else {
        $posts[] = $data;
    }
    file_put_contents($blogFile, json_encode($posts, JSON_PRETTY_PRINT));
    header('Location: admin-blog.php');
    exit;
}

// Prepare edit data
$editData = $editIndex !== null && isset($posts[$editIndex]) ? $posts[$editIndex] : [
    'title' => '', 'content' => '', 'image' => '', 'date' => date('Y-m-d'), 'author' => '', 'comments' => 0
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Blog Manager</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 2em; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #eee; }
        form { margin-bottom: 2em; }
        input[type=text], input[type=date], textarea { width: 100%; padding: 6px; margin: 4px 0; }
        textarea { height: 60px; }
        .actions a { margin-right: 8px; }
    </style>
</head>
<body>
    <h1>Blog Admin Panel</h1>
    <p><a href="index.php" style="font-weight:bold; color:#007bff;">&larr; Back to Home</a></p>
    <form method="post">
        <input type="hidden" name="edit_index" value="<?php echo $editIndex !== null ? $editIndex : ''; ?>">
        <label>Title:<br><input type="text" name="title" required value="<?php echo htmlspecialchars($editData['title']); ?>"></label><br>
        <label>Content:<br><textarea name="content" required><?php echo htmlspecialchars($editData['content']); ?></textarea></label><br>
        <label>Image URL:<br><input type="text" name="image" required value="<?php echo htmlspecialchars($editData['image']); ?>"></label><br>
        <label>Date:<br><input type="date" name="date" required value="<?php echo htmlspecialchars($editData['date']); ?>"></label><br>
        <label>Author:<br><input type="text" name="author" required value="<?php echo htmlspecialchars($editData['author']); ?>"></label><br>
        <label>Comments:<br><input type="text" name="comments" value="<?php echo (int)$editData['comments']; ?>"></label><br>
        <button type="submit">Save Blog Post</button>
        <?php if ($editIndex !== null): ?>
            <a href="admin-blog.php">Cancel Edit</a>
        <?php endif; ?>
    </form>
    <h2>All Blog Posts</h2>
    <table>
        <tr>
            <th>Title</th><th>Date</th><th>Author</th><th>Image</th><th>Comments</th><th>Actions</th>
        </tr>
        <?php foreach ($posts as $i => $post): ?>
        <tr>
            <td><?php echo htmlspecialchars($post['title']); ?></td>
            <td><?php echo htmlspecialchars($post['date']); ?></td>
            <td><?php echo htmlspecialchars($post['author']); ?></td>
            <td><img src="<?php echo htmlspecialchars($post['image']); ?>" alt="" style="max-width:60px;max-height:40px;"></td>
            <td><?php echo (int)$post['comments']; ?></td>
            <td class="actions">
                <a href="admin-blog.php?edit=<?php echo $i; ?>">Edit</a>
                <a href="admin-blog.php?delete=<?php echo $i; ?>" onclick="return confirm('Delete this post?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
