<?php
session_start();
include('./admin/config/config.php');

if (isset($_POST['verify_otp'])) {
    $user_otp = $_POST['user_otp'];
    
    // Kiểm tra OTP với giá trị lưu trong session
    if ($user_otp == $_SESSION['otp']) {
        // Mã OTP đúng, tiếp tục thay đổi mật khẩu
        $account_password = password_hash($_POST['account_password'], PASSWORD_DEFAULT);
        $account_email = $_SESSION['otp_email'];

        $sql_forget = "UPDATE account SET account_password = '$account_password' WHERE account_email = '$account_email'";
        $query_forget = mysqli_query($mysqli, $sql_forget);

        if ($query_forget) {
            // Thông báo và chuyển hướng sau 3 giây
            echo '<script>
                    alert("Thay đổi mật khẩu thành công!");
                    setTimeout(function(){
                        window.location.href = "index.php?page=login";
                    }, 0001); // Chuyển hướng sau 3 giây
                  </script>';
        } else {
            echo '<script>alert("Lỗi khi thay đổi mật khẩu. Vui lòng thử lại.");</script>';
        }
    } else {
        echo '<script>alert("Mã OTP không chính xác!");</script>';
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
    <link rel="stylesheet" href="../assets/css/helper.css" />
    <link rel="stylesheet" href="../assets/css/layout.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/responsive.css" />
    <link rel="stylesheet" href="../assets/css/login.css" />
    <link rel="stylesheet" href="../assets/css/toast.css" />
    <!-- end css -->
    <style>
        .verify-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .verify-form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .verify-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-box {
            margin-bottom: 20px;
        }

        .input-box .details {
            display: block;
            margin-bottom: 5px;
        }

        .input-form {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-buttons {
            display: flex;
            justify-content: center;
        }

        .button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
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
<!-- Phần form xác nhận mã OTP -->
    <section class="register pd-bottom">
        <div class="container">
            <div class="w-100 text-center p-relative">
                <div class="title">XÁC NHẬN MÃ OTP</div>
                <div class="title-line"></div>
            </div>
            <div class="content">
                <form class="register__form" action="" method="POST" onsubmit="return validatePassword();">
                    <div class="user-details">
                        <div class="input-box">
                            <span class="details">Mã OTP</span>
                            <input class="input-form" onchange="getInputChange();" type="text" name="user_otp" placeholder="Nhập mã OTP bạn đã nhận" required>
                        </div>
                        <div class="input-box">
                            <span class="details">Mật khẩu mới</span>
                            <input class="input-form" onchange="getInputChange();" type="password" name="account_password" placeholder="Nhập vào mật khẩu mới" required 
                                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$" 
                                   title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, chữ số và ít nhất 1 ký tự đặc biệt.">
                        </div>
                        <div class="input-box">
                            <span class="details">Nhập lại mật khẩu mới</span>
                            <input class="input-form" onchange="getInputChange();" type="password" name="account_password_confirm" placeholder="Nhập lại mật khẩu" required 
                                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$" 
                                   title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, chữ số và ít nhất 1 ký tự đặc biệt.">
                        </div>
                    </div>
                    <div class="error-message" id="password-error" style="color: red; display: none;">Mật khẩu không khớp, vui lòng thử lại!</div>
                    <div class="button">
                        <input type="submit" name="verify_otp" value="Xác nhận OTP và thay đổi mật khẩu">
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Kiểm tra mật khẩu có trùng khớp không
        function validatePassword() {
            var password = document.getElementsByName('account_password')[0].value;
            var confirmPassword = document.getElementsByName('account_password_confirm')[0].value;
            
            // Kiểm tra mật khẩu phải thỏa mãn điều kiện
            var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            
            if (!passwordPattern.test(password)) {
                alert("Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, chữ số và ít nhất 1 ký tự đặc biệt.");
                return false;
            }
            
            if (password !== confirmPassword) {
                // Hiển thị thông báo lỗi nếu mật khẩu không khớp
                document.getElementById('password-error').style.display = 'block';
                return false; // Ngừng gửi form
            } else {
                // Ẩn thông báo lỗi nếu mật khẩu khớp
                document.getElementById('password-error').style.display = 'none';
                return true; // Tiến hành gửi form
            }
        }
    </script>

</body>
</html>

<?php
include('./pages/base/footer.php');  // Bao gồm footer của trang web
?>
