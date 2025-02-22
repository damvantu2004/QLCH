<?php
session_start();
include('../../admin/config/config.php');
require_once "../../vendor/autoload.php";
require '../../carbon/autoload.php';
use PayOS\PayOS;
use Carbon\Carbon;
use Carbon\CarbonInterval;

$payOS = new PayOS('e30322cd-a6d7-4b02-804c-c2d40583c354', '5c4aea84-15a9-4a48-94ab-4b4c9fcf6f42', 'ee17290cdc60c2a68aca1423453287e680d82abbd567cb1ae78026e94fb8c16f');

$orderCode = $_GET['orderCode'] ?? '';
$payment = $payOS->getPaymentLinkInformation($orderCode);

if ($payment['status'] === "PAID") {
    // Lấy thông tin giao hàng từ session hoặc thiết lập giá trị mặc định nếu cần
    $delivery_id = $_SESSION['delivery_id'] ?? 0;
    $account_id = $_SESSION['account_id'] ?? 0;
    $delivery_name = $_SESSION['delivery_name'] ?? '';
    $delivery_phone = $_SESSION['delivery_phone'] ?? '';
    $delivery_address = $_SESSION['delivery_address'] ?? '';
    $delivery_note = $_SESSION['delivery_note'] ?? '';
    $total_amount = $_SESSION['total_amount'] ?? 0;
    $order_date = Carbon::now('Asia/Ho_Chi_Minh');

    // Thêm địa chỉ giao hàng
    $insert_delivery = "INSERT INTO delivery(delivery_id, account_id, delivery_name, delivery_phone, delivery_address, delivery_note) 
                        VALUE ($delivery_id, $account_id, '$delivery_name', '$delivery_phone', '$delivery_address', '$delivery_note')";
    mysqli_query($mysqli, $insert_delivery);

    // Thêm đơn hàng vào bảng orders
    $insert_order = "INSERT INTO orders(order_code, order_date, account_id, delivery_id, total_amount, order_type, order_status) 
                     VALUE ('$orderCode', '$order_date', $account_id, $delivery_id, $total_amount, 5, 1)";
    $query_insert_order = mysqli_query($mysqli, $insert_order);

    if ($query_insert_order) {
        $quantity_tk = 0;
        // Thêm chi tiết đơn hàng vào bảng order_detail
        foreach ($_SESSION['cart'] as $cart_item) {
            $product_id = $cart_item['product_id'];
            $query_get_product = mysqli_query($mysqli, "SELECT * FROM product WHERE product_id = $product_id");
            $product = mysqli_fetch_array($query_get_product);

            if ($product['product_quantity'] >= $cart_item['product_quantity']) {
                $product_quantity = $cart_item['product_quantity'];
                $quantity = $product['product_quantity'] - $product_quantity;
                $quantity_sales = $product['quantity_sales'] + $product_quantity;
                $product_price = $cart_item['product_price'];
                $product_sale = $cart_item['product_sale'];

                // Cập nhật số lượng cho metric_quantity
                $quantity_tk += $product_quantity; // Cộng dồn số lượng sản phẩm vào metric_quantity

                $insert_order_detail = "INSERT INTO order_detail(order_code, product_id, product_quantity, product_price, product_sale) 
                                        VALUE ('$orderCode', $product_id, $product_quantity, $product_price, $product_sale)";
                mysqli_query($mysqli, $insert_order_detail);
                mysqli_query($mysqli, "UPDATE product SET product_quantity = $quantity, quantity_sales = $quantity_sales WHERE product_id = $product_id");
            }
        }

        // Cập nhật tổng tiền
        $update_total_amount = "UPDATE orders SET total_amount = $total_amount WHERE order_code = '$orderCode'";
        mysqli_query($mysqli, $update_total_amount);


        //Cập nhật trên metrics để thống kê
        $now = $order_date->format('Y-m-d');

        $sql_thongke = "SELECT * FROM metrics WHERE metric_date = '$now'";
        $query_thongke = mysqli_query($mysqli, $sql_thongke);

        if (mysqli_num_rows($query_thongke) == 0) {
            $metric_sales = $total_amount;
            $metric_quantity = $quantity_tk;
            $metric_order = 1;
            $sql_update_metrics = "INSERT INTO metrics (metric_date, metric_order, metric_sales, metric_quantity) 
            VALUE ('$order_date', '$metric_order', '$metric_sales', '$metric_quantity')";
            mysqli_query($mysqli, $sql_update_metrics);
        } elseif (mysqli_num_rows($query_thongke) != 0) {
            while ($row_tk = mysqli_fetch_array($query_thongke)) {
                $metric_sales = $row_tk['metric_sales'] + $total_amount;
                $metric_quantity = $row_tk['metric_quantity'] + $quantity_tk;
                $metric_order = $row_tk['metric_order'] + 1;
                $sql_update_metrics = "UPDATE metrics SET metric_order = '$metric_order', metric_sales = '$metric_sales', metric_quantity = '$metric_quantity' WHERE metric_date = '$now'";
                mysqli_query($mysqli, $sql_update_metrics);
            }
        }

        // Xóa giỏ hàng và chuyển hướng
        unset($_SESSION['cart']);

        unset($_SESSION['order_code']);
        unset($_SESSION['delivery_id']);
        unset($_SESSION['delivery_name']);
        unset($_SESSION['delivery_phone']);
        unset($_SESSION['delivery_address']);
        unset($_SESSION['delivery_note']);

        header('Location:../../index.php?page=my_account&tab=account_order');

        exit;
    }
} else {
    echo "Thanh toán chưa hoàn tất!";
    // Đợi một lúc để người dùng nhìn thấy thông báo, sau đó chuyển hướng
    header("Refresh: 3; url=../../index.php?page=checkout");
    exit();  // Đảm bảo rằng không có mã nào được thực thi sau khi chuyển hướng
}

?>
