<?php
session_start();
include('../../admin/config/config.php');
require '../../carbon/autoload.php';
require '../../mail/sendmail.php';
require_once('config_vnpay.php');
require_once('control_payment.php');

use Carbon\Carbon;
use Carbon\CarbonInterval;


if (isset($_POST['redirect'])) {
    // Sinh ma don hang va ma giao hang
    $order_code = rand(0, 9999);
    $delivery_id = rand(0, 9999);
    $order_date = Carbon::now('Asia/Ho_Chi_Minh');

    $_SESSION['order_code'] = $order_code;

    // Lay id tai khoan thong qua session
    $account_id = $_SESSION['account_id'];

    // Lay thong tin thong qua phuong thuc POST
    $delivery_name = $_POST['delivery_name'];
    $delivery_address = $_POST['delivery_address'];
    $delivery_phone = $_POST['delivery_phone'];
    $delivery_note = $_POST['delivery_note'];
    $order_type = $_POST['order_type'];

    // Gan gia thong tin giao hang vao session delivery
    $_SESSION['delivery_id'] = $delivery_id;
    $_SESSION['delivery_name'] = $delivery_name;
    $_SESSION['delivery_address'] = $delivery_address;
    $_SESSION['delivery_phone'] = $delivery_phone;
    $_SESSION['delivery_note'] = $delivery_note;

    $total_amount = 0;
    $validate = 1;
    foreach ($_SESSION['cart'] as $cart_item) {
        $product_id = $cart_item['product_id'];
        $query_get_product = mysqli_query($mysqli, "SELECT * FROM product WHERE product_id = $product_id");
        $product = mysqli_fetch_array($query_get_product);
        if ($product['product_quantity'] < $cart_item['product_quantity']) {
            $validate = 0;
        }
        $total_amount += ($cart_item['product_price'] - ($cart_item['product_price'] / 100 * $cart_item['product_sale'])) * $cart_item['product_quantity'];
    }
    if ($validate == 1) {

        // Gia tri tong tien
        $_SESSION['total_amount'] = $total_amount;

        // neu thanh toan bang tien mat
        if ($order_type == 1) {
            // them dia chi giao hang
            $insert_delivery = "INSERT INTO delivery(delivery_id, account_id, delivery_name, delivery_phone, delivery_address, delivery_note) VALUE ($delivery_id, $account_id, '$delivery_name', '$delivery_phone', '$delivery_address', '$delivery_note')";
            mysqli_query($mysqli, $insert_delivery);

            // insert don hang
            $insert_order = "INSERT INTO orders(order_code, order_date, account_id, delivery_id, total_amount, order_type, order_status) 
            VALUE ($order_code, '" . $order_date . "', $account_id, '" . $delivery_id . "', $total_amount, 1, 0)";

            $query_insert_order = mysqli_query($mysqli, $insert_order);
            if ($query_insert_order) {
                //them chi tiet don hang
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
                        $insert_order_detail = "INSERT INTO order_detail(order_code, product_id, product_quantity, product_price, product_sale) VALUE ('" . $order_code . "', '" . $product_id . "', '" . $product_quantity . "', '" . $product_price . "', '" . $product_sale . "')";
                        mysqli_query($mysqli, $insert_order_detail);
                        mysqli_query($mysqli, "UPDATE product SET product_quantity = $quantity, quantity_sales = $quantity_sales WHERE product_id = $product_id");
                    }
                }
                $update_total_amount = "UPDATE orders SET total_amount = $total_amount WHERE order_code = $order_code";
                $query_total_amount = mysqli_query($mysqli, $update_total_amount);


                //gữi mail thông báo đến cho khách hàng
                $tieude = "Đặt hàng từ website taitam16c.vn thành công!";
                $noidung = '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                            <div style="border: 1px solid #ddd; padding: 20px; max-width: 600px; margin: 20px auto; border-radius: 8px; background-color: #f9f9f9;">
                                <h2 style="text-align: center; color: #4CAF50;">Cảm ơn bạn đã đặt hàng tại Perfume Paradise!</h2>
                                <p>Xin chào quý khách,</p>
                                <p>Chúng tôi rất vui mừng thông báo rằng đơn hàng của bạn với mã <strong style="color: #4CAF50;">'.$order_code.'<?php echo ". $order_code ."; ?></strong> đã được xác nhận thành công.</p>
                                <p>Chúng tôi đang xử lý đơn hàng của bạn và sẽ sớm gửi đến địa chỉ đã cung cấp. Hãy giữ liên lạc để nhận thông báo khi hàng được giao!</p>
                                <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
                                <p>Chúng tôi rất trân trọng sự tin tưởng của bạn khi lựa chọn sản phẩm từ Perfume Paradise.</p>
                                <p>Chúc bạn một ngày vui vẻ và tràn đầy năng lượng!</p>
                                <p style="text-align: center; font-style: italic;">-- Đội ngũ Perfume Paradise</p>
                            </div>
                        </div>
';

                $maildathang = $_SESSION['account_email'];

                $mail= new Mailer();
                $mail->dathangmail($tieude,$noidung,$maildathang);

                unset($_SESSION['cart']);
                header('Location:../../index.php?page=thankiu&order_type=1');
            }
        } elseif ($order_type == 2) {
            header('Location:checkout_momo.php');
        } elseif ($order_type == 3) {
            header('Location:checkout_momo_atm.php');
        } elseif ($order_type == 4) {
            // xu ly toan bang vnpay
            $vnp_TxnRef = $order_code; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = 'Thanh toán đơn hàng Perfume Paradise';
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $total_amount * 100;
            $vnp_Locale = 'vn';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            //Add Params of 2.0.1 Version
            $vnp_ExpireDate = $expire;

            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
                "vnp_ExpireDate" => $vnp_ExpireDate
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            $returnData = array(
                'code' => '00', 'message' => 'success', 'data' => $vnp_Url
            );
            if (isset($_POST['redirect'])) {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                echo json_encode($returnData);
            }
        }elseif ($order_type == 5){
            $vietQr = new Controll_payment();
            $info = [
                'IDDonHang' => $delivery_id, 
                'amount' => $total_amount,    
                'name' => $delivery_name,      
                'phone' => $delivery_phone      
            ];
            $link = $vietQr->create($info);
            header("Location: $link");
        }
    } else {
        header('Location:../../index.php?page=404');
    }
}

//Có 6 loại order_type:1,2,3,4,5,6 
//6: mua hàng trực tiếp tại cửa hàng không mua online!