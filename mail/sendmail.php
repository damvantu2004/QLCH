<?php 
include "PHPMailer/src/Exception.php";
include "PHPMailer/src/PHPMailer.php";
include "PHPMailer/src/SMTP.php";
// include "PHPMailer/src/OAuth.php";
include "PHPMailer/src/POP3.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    public function dathangmail($tieude, $noidung, $maildathang) {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'nguyenduytaiskt359@gmail.com';     // SMTP username
            $mail->Password = 'zrkkuieulhoqxgww';                 // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
            $mail->Port = 587;                                    // TCP port to connect to

            //Charset for Vietnamese
            $mail->CharSet = 'UTF-8';                             // Set character encoding to UTF-8

            //Recipients
            $mail->setFrom('nguyenduytaiskt359@gmail.com', 'Cửa hàng Perfume Paradise!');

            //Người nhận (khách hàng)
            $mail->addAddress($maildathang, 'Khách hàng');
        
            $mail->addCC('nguyenduytaiskt359@gmail.com', 'lưu lại lịch sử mail đã gữi khách hàng!');  //thuy5102001@gmail.com

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $tieude;
            $mail->Body    = $noidung;

            $mail->send();
            echo 'Message has been sent';

        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

	public function guiOtp($email, $otp) {
		$tieude = 'Mã OTP của bạn';
		$noidung = "Chào bạn,<br><br>Đây là mã OTP của bạn để hoàn tất đăng ký: <b>$otp</b><br><br>Chúc bạn một ngày tốt lành!";
	
		$mail = new PHPMailer(true);
		try {
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'nguyenduytaiskt359@gmail.com';
			$mail->Password = 'zrkkuieulhoqxgww';
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port = 587;
	
			$mail->CharSet = 'UTF-8';
	
			$mail->setFrom('nguyenduytaiskt359@gmail.com', 'Cửa hàng Perfume Paradise!');
			$mail->addAddress($email);
	
			$mail->isHTML(true);
			$mail->Subject = $tieude;
			$mail->Body = $noidung;
	
			$mail->send();
		} catch (Exception $e) {
			echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		}
	}
	
}
?>
