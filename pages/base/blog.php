<?php
include('secret-url.php');
include('hash-url.php');

// Lấy danh sách bài viết
$sql_article_list = "SELECT * FROM article WHERE article_status = 1 ORDER BY article_id DESC";
$query_article_list = mysqli_query($mysqli, $sql_article_list);

if (!$query_article_list) {
    die("Query failed: " . mysqli_error($mysqli));
} 
?>
<!-- start blog -->
<section class="blog pd-section">
  <div class="container">
    <div class="row">
      <?php
      if (mysqli_num_rows($query_article_list) == 0) {
        echo "<p>No articles found.</p>";
      } else {
        $i = 0;
        while ($row = mysqli_fetch_array($query_article_list)) {
          $i++;
          $image_path = "admin/modules/blog/uploads/" . $row['article_image'];
          if (!file_exists($image_path)) {
              $image_path = "path/to/default-image.jpg"; // Đường dẫn ảnh mặc định
          }
          $article_id_encoded = isset($row['article_id']) ? encodeID($row['article_id']) : '';
      ?>
          <?php if ($i == 1) { ?>
            <div class="col" style="--w-md: 12">
              <div class="blog__item blog__item--large">
                <a href="index.php?page=article&article_id=<?php echo $article_id_encoded; ?>">
                  <div class="blog__image">
                    <img class="w-100 h-100 d-block object-fit-cover" src="<?php echo $image_path; ?>" alt="Article Image" />
                  </div>
                  <div class="blog__content">
                    <h3 class="blog__title h3">
                      <?php echo $row['article_title']; ?>
                    </h3>
                    <span class="blog__date h6 d-block"><?php echo $row['article_date']; ?> - <?php echo $row['article_author']; ?></span>
                    <div class="blog__context h4">
                        <?php echo $row['article_summary']; ?>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          <?php } else { ?>
            <div class="col" style="--w-md: 6;">
              <div class="blog__item h-100">
                <a href="index.php?page=article&article_id=<?php echo $article_id_encoded; ?>">
                  <div class="blog__image">
                    <img class="w-100 h-100 d-block object-fit-cover" src="<?php echo $image_path; ?>" alt="Article Image" />
                  </div>
                  <div class="blog__content">
                    <h3 class="blog__title h3">
                      <?php echo $row['article_title']; ?>
                    </h3>
                    <span class="blog__date h6 d-block"><?php echo $row['article_date']; ?> - <?php echo $row['article_author']; ?></span>
                    <div class="blog__context h4">
                        <?php echo mb_strimwidth($row['article_summary'], 0, 200, "..."); ?>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          <?php } ?>
      <?php
        }
      }
      ?>
    </div>
  </div>
</section>
<!-- end blog -->
