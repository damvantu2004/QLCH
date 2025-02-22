<?php
    if (isset($_POST['customer_add'])) {
        // Lấy dữ liệu từ form và bảo vệ XSS bằng htmlspecialchars
        $customer_name = htmlspecialchars($_POST['customer_name'], ENT_QUOTES, 'UTF-8');
        $customer_phone = htmlspecialchars($_POST['customer_phone'], ENT_QUOTES, 'UTF-8');
        $customer_email = htmlspecialchars($_POST['customer_email'], ENT_QUOTES, 'UTF-8');
        $customer_address = htmlspecialchars($_POST['customer_address'], ENT_QUOTES, 'UTF-8');

        // Bảo vệ SQL Injection bằng Prepared Statements
        $sql_insert_customer = $mysqli->prepare("INSERT INTO customer (customer_name, customer_email, customer_phone, customer_address) VALUES (?, ?, ?, ?)");
        $sql_insert_customer->bind_param("ssss", $customer_name, $customer_email, $customer_phone, $customer_address);
        $query_insert_customer = $sql_insert_customer->execute();

        if ($query_insert_customer) {
            // Có thể thêm thông báo hoặc chuyển hướng nếu cần
        } else {
            echo '<script>alert("Đã có lỗi xảy ra, vui lòng thử lại.");</script>';
        }
    }
?>

<!-- start contact -->
<section class="contact pd-top">
    <div class="container">
        <div class="contact__container">
            <h1 class="contact__heading h2">Liên hệ</h1>
            <div class="contact__infomation">
                <p>12 Nguyễn Văn Bảo, Phường 4, Gò Vấp, Hồ Chí Minh ,700000</p>
                <p>Tel : (+84) 83 3979 789</p>
                <p>Fax : (+84) 08 2979 999</p>
                <p>Email : paradise@gmail.com</p>
                <h3 class="contact__title h4">Thời gian làm việc</h3>
                <p>Thứ 2 – Thứ 6: 09:00 am – 09:30pm</p>
                <p>Thứ 7: 10:00am – 11:00pm</p>
                <p>Chủ nhật: 08:00am – 10:00pm</p>
            </div>
            <div class="contact__form pd-section">
                <form action="" method="POST">
                    <div class="row contact__input--double">
                        <div class="col" style="--w-lg: 6">
                            <div class="contact__input">
                                <input class="w-100 btn" type="text" name="customer_name" placeholder="Tên khách hàng" />
                            </div>
                        </div>
                        <div class="col" style="--w-lg: 6;">
                            <div class="contact__input">
                                <input class="w-100 btn" type="email" name="customer_email" placeholder="Email" />
                            </div>
                        </div>
                        <div class="col" style="--w-lg: 12;">
                            <div class="contact__input">
                                <input class="w-100 btn" type="text" name="customer_phone" placeholder="Số điện thoại" />
                            </div>
                        </div>
                        <div class="col" style="--w-lg: 12;">
                            <div class="contact__textarea w-100 h-100">
                                <textarea class="w-100 h-100 btn" name="customer_address" id="" cols="30" rows="10" placeholder="Địa chỉ"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button class="btn contact__btn" name="customer_add" type="submit">
                                Gửi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- end contact -->
