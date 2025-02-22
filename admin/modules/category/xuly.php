<?php
include('../../config/config.php');

$data = $_GET['data'];
$category_ids = json_decode($data);
$category_id = $_GET['category_id'];
$category_name = mysqli_real_escape_string($mysqli, $_POST['category_name']);
$category_description = mysqli_real_escape_string($mysqli, $_POST['category_description']);
$category_image = $_FILES['category_image']['name'];
$category_image_tmp = $_FILES['category_image']['tmp_name'];

// Đổi tên file hình ảnh
if ($category_image != '') {
    $file_extension = pathinfo($category_image, PATHINFO_EXTENSION);
    $category_image = time() . '_' . uniqid() . '.' . $file_extension;
}

function isValidImage($tmp_name, $allowed_types = ['image/jpeg', 'image/png', 'image/gif']) {
    $file_type = mime_content_type($tmp_name);
    $image_info = getimagesize($tmp_name);

    // Kiểm tra MIME type và nội dung file
    if (!in_array($file_type, $allowed_types) || $image_info === false) {
        return false;
    }
    return true;
}

// Thêm danh mục
if (isset($_POST['category_add'])) {
    if ($_FILES['category_image']['name'] != '') {
        // Kiểm tra file hình ảnh
        if (!isValidImage($category_image_tmp)) {
            die("Tệp tải lên không phải là hình ảnh hợp lệ!");
        }

        // Di chuyển file hình ảnh vào thư mục uploads
        if (!move_uploaded_file($category_image_tmp, 'uploads/' . $category_image)) {
            die("Không thể tải lên hình ảnh, vui lòng thử lại!");
        }
    } else {
        $category_image = ''; // Không có hình ảnh tải lên
    }

    $sql_add = "INSERT INTO category(category_name, category_description, category_image) 
                VALUE('$category_name', '$category_description', '$category_image')";
    mysqli_query($mysqli, $sql_add);
    header('Location: ../../index.php?action=category&query=category_list');
}

// Chỉnh sửa danh mục
elseif (isset($_POST['category_edit'])) {
    if ($_FILES['category_image']['name'] != '') {
        // Kiểm tra file hình ảnh
        if (!isValidImage($category_image_tmp)) {
            die("Tệp tải lên không phải là hình ảnh hợp lệ!");
        }

        // Di chuyển file hình ảnh vào thư mục uploads
        if (!move_uploaded_file($category_image_tmp, 'uploads/' . $category_image)) {
            die("Không thể tải lên hình ảnh, vui lòng thử lại!");
        }

        // Xóa file hình ảnh cũ
        $sql = "SELECT * FROM category WHERE category_id = '$category_id' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        while ($row = mysqli_fetch_array($query)) {
            if ($row['category_image'] != '' && file_exists('uploads/' . $row['category_image'])) {
                unlink('uploads/' . $row['category_image']);
            }
        }

        // Cập nhật danh mục có hình ảnh
        $sql_update = "UPDATE category SET category_name='$category_name', 
                      category_description='$category_description', 
                      category_image='$category_image'  
                      WHERE category_id='$category_id'";
    } else {
        // Cập nhật danh mục không có hình ảnh
        $sql_update = "UPDATE category SET category_name='$category_name', 
                      category_description='$category_description'  
                      WHERE category_id='$category_id'";
    }

    mysqli_query($mysqli, $sql_update);
    header('Location: ../../index.php?action=category&query=category_list');
}

// Xóa danh mục
else {
    foreach ($category_ids as $id) {
        $sql = "SELECT * FROM category WHERE category_id = '$id' LIMIT 1";
        $query = mysqli_query($mysqli, $sql);
        while ($row = mysqli_fetch_array($query)) {
            if ($row['category_image'] != '' && file_exists('uploads/' . $row['category_image'])) {
                unlink('uploads/' . $row['category_image']);
            }
        }

        $sql_delete = "DELETE FROM category WHERE category_id = '$id'";
        mysqli_query($mysqli, $sql_delete);
    }
    header('Location: ../../index.php?action=category&query=category_list');
}
?>
