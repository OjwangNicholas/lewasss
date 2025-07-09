<?php
// blog-single.php: Show full blog post by index
$posts = [];
$jsonFile = __DIR__ . '/blog-posts.json';
if (file_exists($jsonFile)) {
  $json = file_get_contents($jsonFile);
  $posts = json_decode($json, true);
}
$index = isset($_GET['id']) ? (int)$_GET['id'] : null;
$post = ($index !== null && isset($posts[$index])) ? $posts[$index] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $post ? htmlspecialchars($post['title']) : 'Blog Post'; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <style>
    .blog-full { max-width: 700px; margin: 2em auto; background: #fff; padding: 2em; border-radius: 8px; box-shadow: 0 2px 8px #eee; }
    .blog-full img { max-width: 100%; border-radius: 6px; margin-bottom: 1em; }
    .meta-date { font-size: 1.1em; color: #888; margin-bottom: 1em; }
    .back-link { display: inline-block; margin-bottom: 1em; color: #007bff; text-decoration: none; }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="blog-full">
  <a href="blog.php" class="back-link">&larr; Back to Blog</a>
  <?php if ($post): ?>
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <div class="meta-date">
      <?php 
        $date = date_create($post['date']);
        echo date_format($date, 'F d, Y');
      ?>
      &nbsp;|&nbsp; By <?php echo htmlspecialchars($post['author']); ?>
    </div>
    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Blog image">
    <p style="white-space: pre-line; font-size:1.1em; line-height:1.7;"> <?php echo htmlspecialchars($post['content']); ?> </p>
  <?php else: ?>
    <p>Blog post not found.</p>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
