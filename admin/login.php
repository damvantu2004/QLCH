<?php
    session_start();
    include('config/config.php');
    
    // Tạo và lưu trữ CSRF token nếu chưa có
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if (isset($_POST['login'])) {
        // Kiểm tra CSRF token
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die('<script>alert("Yêu cầu không hợp lệ.");</script>');
        }

        // Lấy thông tin form
        $account_email = $_POST['account_email'];
        $account_password = $_POST['account_password'];

        // Lấy reCAPTCHA response từ form
        $recaptcha_response = $_POST['g-recaptcha-response'];

        // Kiểm tra reCAPTCHA
        $recaptcha_secret = '6LfxTWsqAAAAAJOSd1P9i-zVjOIfenGpD4y2oZKO';
        $recaptcha_verify_url = "https://www.google.com/recaptcha/api/siteverify";
        $response = file_get_contents($recaptcha_verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
        $response_keys = json_decode($response, true);

        // Nếu reCAPTCHA không hợp lệ
        if (intval($response_keys["success"]) !== 1) {
            echo '<script>alert("Vui lòng xác nhận rằng bạn không phải là robot.");</script>';
        } else {
            // Bảo vệ dữ liệu khỏi SQL Injection bằng Prepared Statements
            $stmt = $mysqli->prepare("SELECT * FROM account WHERE account_email = ? AND (account_type = 1 OR account_type = 2)");
            $stmt->bind_param("s", $account_email); // s cho email
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            // Kiểm tra tài khoản và mật khẩu
            if ($row && password_verify($account_password, $row['account_password'])) {
                // Lưu thông tin vào session
                $_SESSION['login'] = $row['account_email'];
                $_SESSION['account_id_admin'] = $row['account_id'];
                $_SESSION['account_name'] = $row['account_name'];
                $_SESSION['account_type'] = $row['account_type'];

                // Chuyển hướng đến trang admin chính
                header('Location:index.php');
                exit();
            } else {
                echo '<script>alert("Tài khoản hoặc mật khẩu không chính xác, vui lòng nhập lại");</script>';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="css/login.css">
  <title>Login Admin</title>
  <link rel="shortcut icon" href="../assets/images/icon/paradise.png"/>
</head>
<body>
    <section class="login">
        <div class="form-box">
            <div class="form-value">
                <form action="" autocomplete="on" method="POST">
                    <h2>Login</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="account_email" required>
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="account_password" required>
                        <label for="">Password</label>
                    </div>
                    <!-- Thêm Google reCAPTCHA -->
                    <div class="g-recaptcha" data-sitekey="6LfxTWsqAAAAAHz75FkiJKKg-kqhlEQzBMrKA-Yh"></div>
                    
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button type="submit" name="login">Log in</button>
                </form>
                <!-- Chèn script reCAPTCHA -->
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
