<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*Link đki môi trường test: https://sandbox.vnpayment.vn/devreg/
Vào link trên để lấy các thông tin bên dưới ( vnp_TmnCode và vnp_HashSecret)
*/
$vnp_TmnCode = "U9X5UHWS"; //Mã định danh merchant kết nối (Terminal Id)
$vnp_HashSecret = "SZ0JE6UBIKUVNGYWL9P2X1KVME2D9HS3"; //Secret key
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "http://taitam16c.vn/index.php?page=thankiu";
$vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
$apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
//Config input format
//Expire
$startTime = date("YmdHis");
$expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
