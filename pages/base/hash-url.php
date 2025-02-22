<?php
// Hàm mã hóa ID
function encodeID($id) {
    $key = SECRET_KEY; // Sử dụng khóa 
    return bin2hex(openssl_encrypt($id, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, substr($key, 0, 16)));
}

// Hàm giải mã ID
function decodeID($encoded_id) {
    $key = SECRET_KEY; // Sử dụng khóa 
    
    // Kiểm tra xem chuỗi có phải là mã hex hợp lệ không
    if (strlen($encoded_id) % 2 != 0 || !ctype_xdigit($encoded_id)) {
        return false; // Nếu chuỗi không hợp lệ, trả về false
    }

    // Giải mã ID nếu chuỗi hợp lệ
    $decoded = hex2bin($encoded_id);
    
    // Kiểm tra nếu giải mã không thành công
    if ($decoded === false) {
        return false;
    }

    return openssl_decrypt($decoded, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, substr($key, 0, 16));
}
?>
