
<?php
session_start();
include('../../admin/config/config.php');
require '../../carbon/autoload.php';
require_once('config_vnpay.php');
require_once('control_payment.php');
require '../vendor/autoload.php';  // Added PayOS autoload
use PayOS\PayOS;
use Carbon\Carbon;

// PayOS keys and client setup
$payOSClientId = 'e30322cd-a6d7-4b02-804c-c2d40583c354';
$payOSApiKey = '5c4aea84-15a9-4a48-94ab-4b4c9fcf6f42';
$payOSChecksumKey = 'ee17290cdc60c2a68aca1423453287e680d82abbd567cb1ae78026e94fb8c16f';
$payOS = new PayOS($payOSClientId, $payOSApiKey, $payOSChecksumKey);

if (isset($_POST['redirect'])) {
    // Generate order code and delivery ID
    $order_code = rand(0, 9999);
    $delivery_id = rand(0, 9999);
    $order_date = Carbon::now('Asia/Ho_Chi_Minh');
    $_SESSION['order_code'] = $order_code;

    // Get account and delivery info from session/post
    $account_id = $_SESSION['account_id'];
    $delivery_name = $_POST['delivery_name'];
    $delivery_address = $_POST['delivery_address'];
    $delivery_phone = $_POST['delivery_phone'];
    $delivery_note = $_POST['delivery_note'];
    $order_type = $_POST['order_type'];

    // Assign delivery info to session
    $_SESSION['delivery_id'] = $delivery_id;
    $_SESSION['delivery_name'] = $delivery_name;
    $_SESSION['delivery_address'] = $delivery_address;
    $_SESSION['delivery_phone'] = $delivery_phone;
    $_SESSION['delivery_note'] = $delivery_note;

    $total_amount = 0;
    foreach ($_SESSION['cart'] as $cart_item) {
        $product_id = $cart_item['product_id'];
        $query_get_product = mysqli_query($mysqli, "SELECT * FROM product WHERE product_id = $product_id");
        $product = mysqli_fetch_array($query_get_product);
        $total_amount += $product['product_price'] * $cart_item['quantity'];
    }

    if ($order_type == 5) {
        // PayOS Payment link creation
        $YOUR_DOMAIN = 'http://taitam16c.vn';
        $data = [
            "orderCode" => $order_code,
            "amount" => $total_amount,
            "description" => "Thanh toán đơn hàng",
            "items" => array_map(function($item) use ($mysqli) {
                $product_id = $item['product_id'];
                $query = mysqli_query($mysqli, "SELECT * FROM product WHERE product_id = $product_id");
                $product = mysqli_fetch_array($query);
                return [
                    'name' => $product['product_name'],
                    'price' => $product['product_price'],
                    'quantity' => $item['quantity']
                ];
            }, $_SESSION['cart']),
            "returnUrl" => "$YOUR_DOMAIN/pages/handle/success.php",
            "cancelUrl" => "$YOUR_DOMAIN/pages/handle/cancel.php"
        ];
        $response = $payOS->createPaymentLink($data);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $response['checkoutUrl']);
        exit;
    }
    // Other types of payment can be handled here
}
?>
