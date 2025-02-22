<?php
session_start();
    include('../../admin/config/config.php');
    require '../../carbon/autoload.php';
    
    use Carbon\Carbon;
    
    if (isset($_SESSION['account_id'])) {
        if (isset($_POST['evaluate_add'])) {
            // Lấy và kiểm tra product_id từ URL
            if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
                die('Invalid product ID');
            }
            $product_id = (int)$_GET['product_id']; // Ép kiểu thành số nguyên an toàn
            
            $account_id = (int)$_SESSION['account_id']; // Đảm bảo account_id là số nguyên
            $evaluate_rate = isset($_POST['rate']) ? (int)$_POST['rate'] : 5; // Lấy đánh giá (mặc định là 5)
            
            // Kiểm tra giá trị evaluate_rate nằm trong khoảng 1-5
            if ($evaluate_rate < 1 || $evaluate_rate > 5) {
                die('Invalid rating');
            }
    
            // Xử lý nội dung nhận xét, tránh XSS
            $evaluate_content = htmlspecialchars(trim($_POST['evaluate_content']), ENT_QUOTES, 'UTF-8');
            
            $evaluate_date = Carbon::now('Asia/Ho_Chi_Minh');
    
            // Lấy tên tài khoản từ account_id bằng Prepared Statements
            $stmt_account = $mysqli->prepare("SELECT account_name FROM account WHERE account_id = ? LIMIT 1");
            $stmt_account->bind_param("i", $account_id);
            $stmt_account->execute();
            $result_account = $stmt_account->get_result();
            $account = $result_account->fetch_assoc();
            $stmt_account->close();
    
            if (!$account) {
                die('Account not found');
            }
    
            // Chuẩn bị và thực thi câu lệnh INSERT bằng Prepared Statements
            $stmt_insert = $mysqli->prepare(
                "INSERT INTO evaluate (account_id, product_id, account_name, evaluate_rate, evaluate_content, evaluate_date, evaluate_status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $evaluate_status = 1; // Mặc định trạng thái đánh giá là "đã phê duyệt"
            $stmt_insert->bind_param(
                "iissssi",
                $account_id,
                $product_id,
                $account['account_name'],
                $evaluate_rate,
                $evaluate_content,
                $evaluate_date,
                $evaluate_status
            );
    
            if ($stmt_insert->execute()) {
                $stmt_insert->close();
                header('Location:../../index.php?page=product_detail&product_id=' . $product_id . '&message=accept');
            } else {
                $stmt_insert->close();
                die('Failed to submit review');
            }
        }
    } else {
        header('Location:../../index.php?page=product_detail&product_id=' . $_GET['product_id'] . '&message=warning');
    }
?>