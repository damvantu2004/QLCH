<?php
    $sql_category_list = "SELECT * FROM category ORDER BY category_id ASC LIMIT 3";
    $query_category_list = mysqli_query($mysqli, $sql_category_list);
?>
<section class="collage pd-top">
    <div class="container">
        <h2 class="collage__heading heading-3">Danh mục</h2>
        <div class="collage__items d-grid">
            <?php 
            $i = 0;
                while ($row = mysqli_fetch_array($query_category_list)) {
            ?>
                <div class="collage__item d-flex flex-column h-100 <?php if ($i == 0) { echo "collage__item--large"; } else { echo ""; } ?>">

                <div class="collage__image h-100">
                    <?php
                    // Lấy tên của ảnh
                    $category_image = $row['category_image'];

                    // Xác định category_id dựa trên tên ảnh
                    if ($category_image == 'uni.jpeg') {
                        $category_id = 1;
                    } elseif ($category_image == 'category_nam.webp') {
                        $category_id = 2;
                    } elseif ($category_image == 'category_nuu.jpg') {
                        $category_id = 3;
                    } else {
                        $category_id = 0; // Mặc định nếu không có ảnh khớp
                    }
                    ?>
                    <a href="index.php?page=products&category_id=<?php echo $category_id; ?>">
                        <img class="w-100 h-100 d-block object-fit-cover flex-1" src="admin/modules/category/uploads/<?php echo $category_image; ?>" alt="image banner" />
                    </a>
                </div>


                <div class="collage__container">
                    <div class="collage__content d-flex">
                        <a class="align-center" href="index.php?page=products&category_id=<?php echo $row['category_id'] ?>"> <?php echo $row['category_name']; ?> </a>
                        <img src="./assets/images/icon/icon-nextlink.svg" alt="next-link" style="margin-left: 8px" />
                    </div>
                </div>
            </div>
            <?php
                $i++;
                }
            ?>
        </div>
    </div>
</section>