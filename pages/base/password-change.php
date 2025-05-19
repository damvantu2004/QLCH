<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Chỉ gọi session_start() nếu chưa có session nào được khởi tạo
}

// Kết nối tới cơ sở dữ liệu
$mysqli = new mysqli("localhost", "root", "", "QLCH");
if ($mysqli->connect_error) {
    die("Kết nối thất bại: " . $mysqli->connect_error);
}

// Sinh mã CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Tạo token ngẫu nhiên
}

$error_message = '';  // Biến để lưu thông báo lỗi

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $account_email = $_POST['account_email'];
    $account_password = $_POST['account_password'];  // Mật khẩu từ form
    $sql_account = "SELECT * FROM account WHERE account_email='" . $account_email . "'";
    $query_account = mysqli_query($mysqli, $sql_account);
    $row = mysqli_fetch_array($query_account);
    $count = mysqli_num_rows($query_account);

    if ($count > 0 && password_verify($account_password, $row['account_password'])) {  // Kiểm tra mật khẩu với password_verify
        $_SESSION['account_id'] = $row['account_id'];
        $_SESSION['account_email'] = $row['account_email'];
        echo '<script>alert("Đăng nhập thành công");</script>';
    } else {
        echo '<script>alert("Tài khoản hoặc mật khẩu không chính xác, vui lòng nhập lại");</script>';
    }
}

// Xử lý đổi mật khẩu
if (isset($_POST['password_change'])) {
    // Kiểm tra CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo '<p style="color:red; text-align: center;">Yêu cầu không hợp lệ do lỗi bảo mật</p>';
        exit; // Dừng xử lý nếu token không hợp lệ
    }

    $account_email = $_SESSION['account_email'];
    $password_old = $_POST['password_old'];
    $password_new = $_POST['password_new'];
    $password_new_confirm = $_POST['password_new_confirm']; // Nhập lại mật khẩu mới

    // Kiểm tra mật khẩu mới và mật khẩu nhập lại có khớp hay không
    if ($password_new !== $password_new_confirm) {
        $error_message = 'Mật khẩu mới và mật khẩu nhập lại không khớp';
    } else {
        $sql = "SELECT * FROM account WHERE account_email='" . $account_email . "'";
        $query = mysqli_query($mysqli, $sql);
        $row = mysqli_fetch_array($query);
        $count = mysqli_num_rows($query);

        if ($count > 0 && password_verify($password_old, $row['account_password'])) {  // Kiểm tra mật khẩu cũ với password_verify
            $password_new_hash = password_hash($password_new, PASSWORD_BCRYPT);  // Băm mật khẩu mới với password_hash
            $sql_update = mysqli_query($mysqli, "UPDATE account SET account_password = '$password_new_hash' WHERE account_email = '$account_email'");
            echo '<p style="color:green; text-align: center;">Mật khẩu đã được thay đổi</p>';
        } else {
            echo '<p style="color:red; text-align: center;">Mật khẩu cũ không đúng, vui lòng nhập lại</p>';
        }
    }
}
?>

<section class="login pd-section">
    <div class="form-box">
        <div class="form-value">
            <form action="" autocomplete="on" method="POST">
                <h2 class="login-title">Thay đổi mật khẩu</h2>
                
                <!-- Trường CSRF token ẩn -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password_old" required>
                    <label for="">Mật khẩu cũ</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password_new" required>
                    <label for="">Mật khẩu mới</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password_new_confirm" required>
                    <label for="">Nhập lại mật khẩu mới</label> 
                </div>

                <?php if ($error_message): ?>
                    <p style="color:red; text-align: center;"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <button type="submit" name="password_change">Thay đổi</button>
            </form>
        </div>
    </div>
</section>
















<!-- code tài 
<?php
// if (isset($_POST['login'])) {
//     $account_email = $_POST['account_email'];
//     $account_password = md5($_POST['account_password']);
//     $sql_account = "SELECT * FROM account WHERE account_email='" . $account_email . "' AND account_password='" . $account_password . "' ";
//     $query_account = mysqli_query($mysqli, $sql_account);
//     $row = mysqli_fetch_array($query_account);
//     $count = mysqli_num_rows($query_account);
//     if ($count > 0) {
//         $_SESSION['account_id'] = $row['account_id'];
//         $_SESSION['account_email'] = $row['account_email'];
//         echo '<script>alert("Đăng nhập thành công");</script>';
//     } else {
//         echo '<script>alert("Tài khoản hoặc mật khẩu không chính xác, vui lòng nhập lại");</script>';
//     }
// }
?>

 <section class="login pd-section">
    <div class="form-box">
        <div class="form-value">
            <form action="" autocomplete="on" method="POST">
                <h2 class="login-title">Thay đổi mật khẩu</h2> -->

                <?php
                // if (isset($_POST['password_change'])) {
                //     $account_email = $_SESSION['account_email'];
                //     $password_old = md5($_POST['password_old']);
                //     $password_new = md5($_POST['password_new']);
                //     $password_new_confirm = md5($_POST['password_new_confirm']); // Lấy mật khẩu nhập lại

                //     // Kiểm tra mật khẩu mới và nhập lại mật khẩu có giống nhau không
                //     if ($password_new !== $password_new_confirm) {
                //         echo '<p style="color:red; text-align: center;">Mật khẩu mới và nhập lại mật khẩu không khớp!</p>';
                //     } else {
                //         // Kiểm tra mật khẩu cũ
                //         $sql = "SELECT * FROM account WHERE account_email='" . $account_email . "' AND account_password='" . $password_old . "' ";
                //         $query = mysqli_query($mysqli, $sql);
                //         $row = mysqli_fetch_array($query);
                //         $count = mysqli_num_rows($query);
                //         if ($count > 0) {
                //             // Cập nhật mật khẩu mới
                //             $sql_update = mysqli_query($mysqli, "UPDATE account SET account_password = '$password_new' WHERE account_email = '$account_email'");
                //             if ($sql_update) {
                //                 echo '<p style="color:green; text-align: center;">Mật khẩu đã được thay đổi</p>';
                //             } else {
                //                 echo '<p style="color:red; text-align: center;">Lỗi khi thay đổi mật khẩu. Vui lòng thử lại.</p>';
                //             }
                //         } else {
                //             echo '<p style="color:red; text-align: center;">Mật khẩu cũ không đúng, vui lòng nhập lại</p>';
                //         }
                //     }
                // }
                ?>

                <!-- <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password_old" required>
                    <label for="">Mật khẩu cũ</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password_new" required>
                    <label for="">Mật khẩu mới</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input type="password" name="password_new_confirm" required>
                    <label for="">Nhập lại mật khẩu mới</label>
                </div>

                <button type="submit" name="password_change">Thay đổi</button> -->

                <!-- Back Button -->
                <!-- <button type="button" style="margin-top: 10px;" onclick="window.location.href='index.php?page=my_account&tab=account_info';">Quay lại</button>
            </form>
        </div>
    </div>
</section> 
-->
