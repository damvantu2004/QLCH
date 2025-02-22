<?php
    require_once  "../../vendor/autoload.php";
    use PayOS\PayOS;
    class Controll_payment{

        public function create($info){
            $payOSClientId = 'e30322cd-a6d7-4b02-804c-c2d40583c354';
            $payOSApiKey = '5c4aea84-15a9-4a48-94ab-4b4c9fcf6f42';
            $payOSChecksumKey = 'ee17290cdc60c2a68aca1423453287e680d82abbd567cb1ae78026e94fb8c16f';
            $payOS = new PayOS($payOSClientId, $payOSApiKey, $payOSChecksumKey);

            $YOUR_DOMAIN = 'taitam16c.vn';

            $data = [
                "orderCode" => $info['IDDonHang'],
                "amount" => $info['amount'],
                "description" => "Thanh toán đơn hàng",
                'buyerName' => $info['name'],
                'buyerPhone' => $info['phone'],
                "returnUrl" => "http://taitam16c.vn/pages/handle/success.php",
                "cancelUrl" => "http://taitam16c.vn/pages/handle/success.php"

            ];

            $response = $payOS->createPaymentLink($data);
            return $response['checkoutUrl'];
        }

        // public static function returnPayment(){
        //     if($_SERVER['REQUEST_METHOD'] === "GET"){
        //         $vnp_HashSecret = "TALPOXXNXPJYNOGRZMWFZGAWWZUGOFRX";
        //         $vnp_SecureHash = $_GET['vnp_SecureHash'];
        //         $inputData = array();
        //         foreach ($_GET as $key => $value) {
        //             if (substr($key, 0, 4) == "vnp_") {
        //                 $inputData[$key] = $value;
        //             }
        //         }
                
        //         unset($inputData['vnp_SecureHash']);
        //         ksort($inputData);
        //         $i = 0;
        //         $hashData = "";
        //         foreach ($inputData as $key => $value) {
        //             if ($i == 1) {
        //                 $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
        //             } else {
        //                 $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        //                 $i = 1;
        //             }
        //         }
        
        //         $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        //         //Xử lý chuyển hướng
        //         if ($secureHash == $vnp_SecureHash) {
        //             switch($_GET["link"]){
        //                 //Đặt hàng
        //                 case "order_product":
        //                     if ($_GET['vnp_ResponseCode'] == '00') {
        //                         $order = new model_order();
        //                         $update = $order->updatePaymentStatus($_GET["vnp_TxnRef"]);
        //                         if($update){
        //                             header("Location: http://localhost:80/Order?message=successfully");
        //                             exit();
        //                         }else{
        //                             header("Location: http://localhost:80/Order?message=unsuccessfully");
        //                             exit();
        //                         }
        //                     } 
        //                     else {
        //                         header("Location: http://localhost:80/Order?message=unsuccessfully");
        //                         exit();
        //                     }
        //                 //Đăng ký gói tập
        //                 // case "gympack":
        //                 //     if ($_GET['vnp_ResponseCode'] == '00') {
        //                 //         $order = new Model_invoice_pack();
        //                 //         $update = $order->updateInvoiceStatus($_GET["vnp_TxnRef"]);
        //                 //         if($update){
        //                 //             header("Location: http://localhost:3000/GymPack?message=successfully");
        //                 //             exit();
        //                 //         }else{
        //                 //             header("Location: http://localhost:3000/GymPack?message=unsuccessfully");
        //                 //             exit();
        //                 //         }
        //                 //     } 
        //                 //     else {
        //                 //         header("Location: http://localhost:3000/GymPack?message=unsuccessfully");
        //                 //         exit();
        //                 //     }
        //                 // //Thuê PT
        //                 // case "pt":
        //                 //     if ($_GET['vnp_ResponseCode'] == '00') {
        //                 //         $order = new model_invoice_pt();
        //                 //         $update = $order->updateInvoiceStatus($_GET["vnp_TxnRef"]);
        //                 //         if($update){
        //                 //             header("Location: http://localhost:3000/PT?message=successfully");
        //                 //             exit();
        //                 //         }else{
        //                 //             header("Location:http://localhost:3000/PT?message=unsuccessfully");
        //                 //             exit();
        //                 //         }
        //                 //     } 
        //                 //     else {
        //                 //         header("Location:http://localhost:3000/PT?message=unsuccessfully");
        //                 //         exit();
        //                 //     }

        //             }
        //         } else {
        //             switch($_GET["link"]){
        //                 case "order_product":
        //                     header("Location: http://localhost:80/Order?message=unsuccessfully");
        //                     exit();
        //                 // case "gympack":
        //                 //     header("Location: http://localhost:3000/GymPack?message=unsuccessfully");
        //                 //     exit();

        //             }
        //         }
        //     }
        
        // }
    }
// use PayOS\PayOS;

// class Controll_payment {
//     private $payOSClientId = 'e30322cd-a6d7-4b02-804c-c2d40583c354';
//     private $payOSApiKey = '5c4aea84-15a9-4a48-94ab-4b4c9fcf6f42';
//     private $payOSChecksumKey = 'ee17290cdc60c2a68aca1423453287e680d82abbd567cb1ae78026e94fb8c16f';
//     private $payOS;

//     public function __construct() {
//         $this->payOS = new PayOS($this->payOSClientId, $this->payOSApiKey, $this->payOSChecksumKey);
//     }

//     public function create($id, $giatien, $tenkhachhang = null, $sdt = null) {
//         $YOUR_DOMAIN = 'taitam16c.vn';
        
//         // Dữ liệu để gửi đến PayOS
//         $data = [
//             "orderCode" => $id, // Mã đơn hàng
//             "amount" => $giatien, // Số tiền
//             "description" => "Thanh toán đơn hàng",
//             'buyerName' => $tenkhachhang,
//             'buyerPhone' => $sdt,
//             "returnUrl" => $YOUR_DOMAIN . "?message=success", // URL thành công
//             "cancelUrl" => $YOUR_DOMAIN . "?message=canceled"  // URL hủy
//         ];

//         // Tạo link thanh toán
//         $response = $this->payOS->createPaymentL2002ink($data);

//         // Kiểm tra phản hồi từ PayOS
//         if (isset($response['checkoutUrl'])) {
//             header('Location: ' . $response['checkoutUrl']); // Chuyển hướng đến trang thanh toán
//             exit();
//         } else {
//             echo "Có lỗi xảy ra khi tạo link thanh toán.";
//             exit();
//         }
//     }

//     public static function returnPayment() {
//         if ($_SERVER['REQUEST_METHOD'] === "GET") {
//             $vnp_HashSecret = "TALPOXXNXPJYNOGRZMWFZGAWWZUGOFRX";
//             $vnp_SecureHash = $_GET['vnp_SecureHash'];
//             $inputData = array();
//             foreach ($_GET as $key => $value) {
//                 if (substr($key, 0, 4) == "vnp_") {
//                     $inputData[$key] = $value;
//                 }
//             }

//             unset($inputData['vnp_SecureHash']);
//             ksort($inputData);
//             $i = 0;
//             $hashData = "";
//             foreach ($inputData as $key => $value) {
//                 if ($i == 1) {
//                     $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
//                 } else {
//                     $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
//                     $i = 1;
//                 }
//             }

//             $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

//             // Xử lý chuyển hướng
//             if ($secureHash == $vnp_SecureHash) {
//                 switch ($_GET["link"]) {
//                     case "order_product":
//                         if ($_GET['vnp_ResponseCode'] == '00') {
//                             $order = new model_order();
//                             $update = $order->updatePaymentStatus($_GET["vnp_TxnRef"]);
//                             if ($update) {
//                                 header("Location: http://localhost:80/index.php?page=checkout?message=successfully");
//                                 exit();
//                             } else {
//                                 header("Location: http://localhost:80/index.php?page=checkout?message=unsuccessfully");
//                                 exit();
//                             }
//                         } else {
//                             header("Location: http://localhost:80/index.php?page=checkout?message=unsuccessfully");
//                             exit();
//                         }
//                 }
//             } else {
//                 header("Location: http://localhost:80/index.php?page=checkout?message=unsuccessfully");
//                 exit();
//             }
//         }
//     }
// }
// ?>
