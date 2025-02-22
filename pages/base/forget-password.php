<?php
ob_start(); // Bắt đầu output buffering
// Bao gồm file gửi mail
include($_SERVER['DOCUMENT_ROOT'] . '/mail/sendmail.php');

if (isset($_POST['register'])) {
    $account_email = $_POST['account_email'];
    
    // Kiểm tra nếu email đã tồn tại trong cơ sở dữ liệu
    $sql_getacc = "SELECT * FROM account WHERE account_email = '" . $account_email . "'";
    $query_getacc = mysqli_query($mysqli, $sql_getacc);
    $count = mysqli_num_rows($query_getacc);

    if ($count == 1) {
        // Tạo mã OTP ngẫu nhiên
        $otp = rand(100000, 999999);
        
        // Gửi OTP đến email của người dùng
        $mailer = new Mailer();
        $mailer->guiOtp($account_email, $otp);
        
        // Lưu mã OTP vào session để kiểm tra sau này
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $account_email;
        
        // Chuyển hướng đến trang xác nhận OTP
        echo '<script>
                alert("OTP đã được gửi đến email của bạn!");
                window.location.href = "verify-otp-fp.php";
              </script>';
        exit; 
    } else {
        echo '<script>alert("Email không tồn tại trong hệ thống!");</script>';
    }
}
?>
<!-- Phần form lấy lại mật khẩu -->
<section class="register pd-bottom">
    <div class="container">
        <div class="w-100 text-center p-relative">
            <div class="title">LẤY LẠI MẬT KHẨU</div>
            <div class="title-line"></div>
        </div>
        <div class="content">
            <form class="register__form" action="" method="POST">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Email</span>
                        <input class="input-form" onchange="getInputChange();" type="email" name="account_email" placeholder="Nhập vào địa chỉ email" required>
                    </div>
                </div>
                <div class="button">
                    <input type="submit" name="register" value="Gửi OTP">
                </div>
            </form>
        </div>
    </div>
</section>