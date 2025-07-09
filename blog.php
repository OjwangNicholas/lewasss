<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Lewa Secondary Schoo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>
  <body>
   <!-- Start of header -->
    <?php include 'header.php'; ?>
   <!-- END nav -->

    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/class4.jpg');">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
          <div class="col-md-9 ftco-animate text-center">
            <h1 class="mb-2 bread">Our Blog</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Blog <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>

  <section class="ftco-section bg-light">
      <div class="container">
        <div class="row">
        <?php
        // Load blog posts from JSON file
        $posts = [];
        $jsonFile = __DIR__ . '/blog-posts.json';
        if (file_exists($jsonFile)) {
          $json = file_get_contents($jsonFile);
          $posts = json_decode($json, true);
        }
        foreach ($posts as $i => $post) {
          $date = date_create($post['date']);
          $day = date_format($date, 'd');
          $month = date_format($date, 'F');
          $year = date_format($date, 'Y');
        ?>
        <div class="col-md-6 col-lg-4 ftco-animate">
          <div class="blog-entry">
            <a href="blog-single.php?id=<?php echo $i; ?>" class="block-20 d-flex align-items-end" style="background-image: url('<?php echo htmlspecialchars($post['image']); ?>');">
              <div class="meta-date text-center p-2">
                <span class="day"><?php echo $day; ?></span>
                <span class="mos"><?php echo $month; ?></span>
                <span class="yr"><?php echo $year; ?></span>
              </div>
            </a>
            <div class="text bg-white p-4">
              <h3 class="heading"><a href="blog-single.php?id=<?php echo $i; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
              <p>
                <?php
                  $lines = preg_split('/\r\n|\r|\n/', $post['content']);
                  $preview = array_slice($lines, 0, 3);
                  echo htmlspecialchars(implode("\n", $preview));
                  if (count($lines) > 3) echo '...';
                ?>
              </p>
              <div class="d-flex align-items-center mt-4">
                <p class="mb-0"><a href="blog-single.php?id=<?php echo $i; ?>" class="btn btn-secondary">Read More <span class="ion-ios-arrow-round-forward"></span></a></p>
                <p class="ml-auto mb-0">
                  <a href="#" class="mr-2"><?php echo htmlspecialchars($post['author']); ?></a>
                  <a href="#" class="meta-chat"><span class="icon-chat"></span> <?php echo (int)$post['comments']; ?></a>
                </p>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
        </div>
      </div>
    </section>
    <!-- Start of footer -->
    <?php include 'footer.php'; ?>
    <!-- END footer -->
  </body>
</html>