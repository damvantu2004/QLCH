<?php
session_start();
include('../../admin/config/config.php');

if (isset($_POST['login'])) {
    // Nhận dữ liệu từ form
    $account_email = $_POST['account_email'];
    $account_password = $_POST['account_password'];

    // Sử dụng prepared statements để chống SQL Injection
    $stmt = $mysqli->prepare("SELECT account_id, account_email, account_password FROM account WHERE account_email = ?");
    $stmt->bind_param("s", $account_email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra kết quả
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // So sánh mật khẩu đã mã hoá
        if (password_verify($account_password, $row['account_password'])) {
            // Đăng nhập thành công
            $_SESSION['account_id'] = $row['account_id'];
            $_SESSION['account_email'] = $row['account_email'];
            header('Location: ../../index.php?page=my_account&tab=account_info&message=success');
            exit();
        } else {
            // Mật khẩu không đúng
            header('Location: ../../index.php?page=login&message=error');
            exit();
        }
    } else {
        // Email không tồn tại
        header('Location: ../../index.php?page=login&message=error');
        exit();
    }
}
?>
