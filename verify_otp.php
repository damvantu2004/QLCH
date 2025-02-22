<?php
session_start();
include('./admin/config/config.php');

// Kiểm tra nếu không có thông tin tạm thời hoặc OTP, chuyển hướng về trang đăng ký
if (!isset($_SESSION['otp']) || !isset($_SESSION['temp_register'])) {
    header('Location: index.php?page=register');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];

    // Kiểm tra mã OTP
    if ($entered_otp == $_SESSION['otp']) {
        // Mã OTP đúng, lưu thông tin vào cơ sở dữ liệu
        $register_data = $_SESSION['temp_register'];
        $account_name = $register_data['account_name'];
        $customer_address = $register_data['customer_address'];
        $account_phone = $register_data['account_phone'];
        $account_password = $register_data['account_password'];
        $account_gender = $register_data['account_gender'];
        $account_email = $_SESSION['email'];

        // Lưu vào bảng account
        $sql_register = "INSERT INTO account(account_name, account_email, account_password, account_type) 
                         VALUES('$account_name', '$account_email', '$account_password', 0)";
        $query_register = mysqli_query($mysqli, $sql_register);

        // Lấy account_id vừa tạo
        $account_id = mysqli_insert_id($mysqli);

        // Lưu vào bảng customer
        $sql_customer = "INSERT INTO customer(account_id, customer_name, customer_gender, customer_email, customer_phone, customer_address) 
                         VALUES('$account_id', '$account_name', '$account_gender', '$account_email', '$account_phone', '$customer_address')";
        $query_customer = mysqli_query($mysqli, $sql_customer);

        // Xóa session tạm thời
        unset($_SESSION['otp']);
        unset($_SESSION['temp_register']);

        // Chuyển hướng về trang đăng nhập
        header('Location: index.php?page=login&message=success');
        exit();
    } else {
        // Mã OTP sai
        $error = "Mã OTP không chính xác. Vui lòng thử lại!";
    }
}
?>

<?php
// Đảm bảo file verify_otp.php nằm trong cùng thư mục hoặc theo đúng đường dẫn của các file khác như header.php
include('./pages/base/header.php');  // Bao gồm header của trang web
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP</title>
    <!-- start css -->
    <link rel="stylesheet" href="./assets/css/helper.css" />
    <link rel="stylesheet" href="./assets/css/layout.css" />
    <link rel="stylesheet" href="./assets/css/main.css" />
    <link rel="stylesheet" href="./assets/css/responsive.css" />
    <link rel="stylesheet" href="./assets/css/login.css">
    <link rel="stylesheet" href="./assets/css/toast.css">
    <!-- end css -->
    <style>
        /* Căn giữa nội dung form */
        .verify-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Đảm bảo form luôn chiếm đầy màn hình */
            padding: 20px;
            background-color: #f8f9fa;
        }

        .verify-form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px; /* Giới hạn chiều rộng của form */
        }

        .verify-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-buttons {
            display: flex;
            justify-content: space-between;
        }

        /* Nút Xác nhận và Quay lại */
        .button {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-form">
            <h2>Xác nhận mã OTP</h2>
            <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
            <form action="verify_otp.php" method="POST">
                <label for="otp">Nhập mã OTP đã gửi vào email:</label>
                <input type="text" name="otp" id="otp" required style="width: 100%; padding: 8px; margin-bottom: 20px;">

                <div class="form-buttons">
                    <input type="submit" name="verify_otp" value="Xác nhận" class="button">
                    <a href="index.php?page=register" class="button">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
include('./pages/base/footer.php');  // Bao gồm footer của trang web
?>