<?php
session_start();
include('../../admin/config/config.php');
include('../../mail/sendmail.php'); // Bổ sung đường dẫn tới sendmail.php

if (isset($_POST['check_email'])) {
    $email = $_POST['check_email'];
    $sql_check_email = "SELECT * FROM account WHERE account_email = '$email'";
    $query_check_email = mysqli_query($mysqli, $sql_check_email);
    if (mysqli_num_rows($query_check_email) > 0) {
        echo 'email_exists'; // Email đã tồn tại
    } else {
        echo 'email_not_exists'; // Email chưa tồn tại
    }
    exit;
}

if (isset($_POST['check_phone'])) {
    $phone = $_POST['check_phone'];
    $sql_check_phone = "SELECT * FROM customer WHERE customer_phone = '$phone'";
    $query_check_phone = mysqli_query($mysqli, $sql_check_phone);
    if (mysqli_num_rows($query_check_phone) > 0) {
        echo 'phone_exists'; // Số điện thoại đã tồn tại
    } else {
        echo 'phone_not_exists'; // Số điện thoại chưa tồn tại
    }
    exit;
}

// Xử lý đăng ký nếu không phải là yêu cầu AJAX
if (isset($_POST['register'])) {
    $account_name = $_POST['account_name'];
    $customer_address = $_POST['customer_address'];
    $account_phone = $_POST['account_phone'];
    $account_email = $_POST['account_email'];
    $account_password = password_hash($_POST['account_password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
    if (isset($_POST['account_gender'])) {
        $account_gender = $_POST['account_gender'];
    } else {
        $account_gender = 0;
    }

    $sql_getacc = "SELECT * FROM account WHERE account_email = '" . $account_email . "'";
    $query_getacc = mysqli_query($mysqli, $sql_getacc);
    $count = mysqli_num_rows($query_getacc);

    if ($count == 0) {
        // Tạo mã OTP
        $otp = rand(100000, 999999);

        // Lưu OTP và email vào session
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $account_email;

        // Gửi OTP qua email
        $mailer = new Mailer();
        $mailer->guiOtp($account_email, $otp);

        // Lưu thông tin đăng ký tạm thời (sẽ xác nhận OTP trước khi lưu vào DB)
        $_SESSION['temp_register'] = [
            'account_name' => $account_name,
            'customer_address' => $customer_address,
            'account_phone' => $account_phone,
            'account_password' => $account_password,
            'account_gender' => $account_gender
        ];

        // Chuyển hướng tới trang xác nhận OTP
        header('Location: ../../verify_otp.php');
        exit();
    } else {
        echo '<script>alert("Email đã sử dụng vui lòng nhập lại email khác");</script>';
        header('Location: ../../index.php?page=register&message=error');
    }
}
?>

