<?php
// Kết nối với cơ sở dữ liệu
$mysqli = new mysqli("localhost", "root", "", "QLCH");

// Kiểm tra kết nối
if ($mysqli->connect_error) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}

// Lấy tất cả tài khoản từ cơ sở dữ liệu
$sql = "SELECT account_id, account_password FROM account";
$result = mysqli_query($mysqli, $sql);

if ($result) {
    // Duyệt qua tất cả các tài khoản
    while ($row = mysqli_fetch_array($result)) {
        $account_id = $row['account_id'];
        $account_password = $row['account_password'];

        // Kiểm tra nếu mật khẩu là MD5 (làm giả định rằng tất cả mật khẩu cũ đều là MD5)
        if (strlen($account_password) == 32) {
            // Chuyển mật khẩu MD5 sang password_hash
            $new_password_hash = password_hash($account_password, PASSWORD_BCRYPT);

            // Cập nhật lại mật khẩu vào cơ sở dữ liệu
            $update_sql = "UPDATE account SET account_password = '$new_password_hash' WHERE account_id = $account_id";
            if (!mysqli_query($mysqli, $update_sql)) {
                echo "Lỗi cập nhật mật khẩu cho tài khoản ID: $account_id\n";
            }
        }
    }
    echo "Cập nhật mật khẩu thành công.";
} else {
    echo "Lỗi truy vấn cơ sở dữ liệu: " . mysqli_error($mysqli);
}

// Đóng kết nối
mysqli_close($mysqli);
?>
