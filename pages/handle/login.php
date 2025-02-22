<?php
    // session_start();
    // include('../../admin/config/config.php');

    // if (isset($_POST['login'])) {
    //     // Nhận token reCAPTCHA từ form
    //     $recaptcha_response = $_POST['g-recaptcha-response'];

    //     // Kiểm tra nếu token reCAPTCHA không tồn tại hoặc rỗng
    //     if (empty($recaptcha_response)) {
    //         header('Location:../../index.php?page=login&message=recaptcha_error');
    //         exit();  // Dừng chương trình nếu reCAPTCHA không hợp lệ
    //     }

    //     // Thực hiện xác thực reCAPTCHA v2
    //     $recaptcha_secret = '6LfxTWsqAAAAAJOSd1P9i-zVjOIfenGpD4y2oZKO'; // Thay YOUR_SECRET_KEY bằng khoá bí mật
    //     $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        
    //     // Gửi yêu cầu xác thực tới Google reCAPTCHA API
    //     $recaptcha_response_data = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    //     $recaptcha_result = json_decode($recaptcha_response_data, true);
        
    //     // Kiểm tra kết quả xác thực
    //     if ($recaptcha_result['success'] == true) {
    //         // reCAPTCHA hợp lệ - Tiếp tục xử lý đăng nhập
    //         $account_email = $_POST['account_email'];
    //         $account_password = md5($_POST['account_password']);
    //         $sql_account = "SELECT * FROM account WHERE account_email='" . $account_email . "' AND account_password='" . $account_password . "'";
    //         $query_account = mysqli_query($mysqli, $sql_account);
    //         $row = mysqli_fetch_array($query_account);
    //         $count = mysqli_num_rows($query_account);
            
    //         if ($count > 0) {
    //             $_SESSION['account_id'] = $row['account_id'];
    //             $_SESSION['account_email'] = $row['account_email'];
    //             header('Location:../../index.php?page=my_account&tab=account_info&message=success');
    //         } else {
    //             header('Location:../../index.php?page=login&message=error');
    //             echo '<script>alert("Tài khoản hoặc mật khẩu không chính xác, vui lòng nhập lại");</script>';
    //         }
    //     } else {
    //         // Xử lý reCAPTCHA không hợp lệ
    //         header('Location:../../index.php?page=login&message=recaptcha_error');
    //         echo '<script>alert("Vui lòng xác minh rằng bạn không phải là robot");</script>';
    //     }
    // }
session_start();
include('../../admin/config/config.php');

if (isset($_POST['login'])) {
    // Lấy token reCAPTCHA từ form
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Kiểm tra nếu token reCAPTCHA không tồn tại hoặc rỗng
    if (empty($recaptcha_response)) {
        header('Location: ../../index.php?page=login&message=recaptcha_error');
        exit();
    }

    // Thực hiện xác thực reCAPTCHA v2
    $recaptcha_secret = '6LfxTWsqAAAAAJOSd1P9i-zVjOIfenGpD4y2oZKO'; // Thay YOUR_SECRET_KEY bằng khóa bí mật từ Google reCAPTCHA
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';

    // Gửi yêu cầu xác thực tới Google reCAPTCHA API
    $recaptcha_response_data = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha_result = json_decode($recaptcha_response_data, true);

    // Kiểm tra kết quả xác thực của reCAPTCHA
    if ($recaptcha_result['success'] == true) {
        // reCAPTCHA hợp lệ - Tiếp tục xử lý đăng nhập

        // Nhận dữ liệu từ form
        $account_email = $_POST['account_email'];
        $account_password = $_POST['account_password'];

        // Sử dụng prepared statements để ngăn chặn SQL Injection
        $stmt = $mysqli->prepare("SELECT account_id, account_email, account_password FROM account WHERE account_email = ?");
        $stmt->bind_param("s", $account_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Xác thực mật khẩu bằng `password_verify()`
            if (password_verify($account_password, $row['account_password'])) {
                // Đăng nhập thành công
                $_SESSION['account_id'] = $row['account_id'];
                $_SESSION['account_email'] = $row['account_email'];
                header('Location: ../../index.php?page=my_account&tab=account_info&message=success');
                exit();
            } else {
                // Mật khẩu không khớp
                header('Location: ../../index.php?page=login&message=error');
                exit();
            }
        } else {
            // Email không tồn tại
            header('Location: ../../index.php?page=login&message=error');
            exit();
        }
    } else {
        // Xử lý khi reCAPTCHA không hợp lệ
        header('Location: ../../index.php?page=login&message=recaptcha_error');
        exit();
    }
}

?>