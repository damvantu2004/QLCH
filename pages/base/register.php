<script src="./assets/js/form_register.js"></script>
<section class="register pd-section">
    <div class="container">
        <div class="w-100 text-center p-relative">
            <div class="title">
                <h3 class="heading h3">Đăng ký thành viên</h3>
                <p class="desc">Đăng ký tài khoản ngay để mua hàng tại Paradise Perfume ❤️</p>
            </div>
            <div class="title-line"></div>
        </div>

        <div class="content">
            <form class="register__form" action="pages/handle/register.php" id="form-register" method="POST">
                <div class="user-details">
                    <div class="input-box form-group">
                        <label class="details form-label">Họ Tên</label>
                        <input class="input-form" id="account_name" onchange="getInputChange();" type="text" name="account_name" placeholder="Nhập vào tên của bạn" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Địa chỉ</label>
                        <input class="input-form" id="account_address" onchange="getInputChange();" type="text" name="customer_address" placeholder="Nhập vào địa chỉ của bạn" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Email</label>
                        <input class="input-form" id="account_email" onchange="getInputChange();" type="email" name="account_email" placeholder="Nhập vào địa chỉ email" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Số điện thoại</label>
                        <input class="input-form" id="account_phone" onchange="getInputChange();" type="text" name="account_phone" placeholder="Nhập vào số điện thoại" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Mật khẩu</label>
                        <input class="input-form" id="account_password" onchange="getInputChange();" type="password" name="account_password" placeholder="Nhập vào mật khẩu" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Nhập lại mật khẩu</label>
                        <input class="input-form" id="account_password2" onchange="getInputChange();" type="password" name="account_password_confirn" placeholder="Nhập lại mật khẩu" required>
                        <span class="form-message"></span>
                    </div>
                </div>
                <div class="gender-details">
                    <input type="radio" name="account_gender" value="0" id="dot-1" checked>
                    <input type="radio" name="account_gender" value="1" id="dot-2">
                    <input type="radio" name="account_gender" value="2" id="dot-3">
                    <span class=" form-label">Giới tính:</span>
                    <div class="category">
                        <label for="dot-1">
                            <span class="dot one"></span>
                            <span class="gender">Không xác định</span>
                        </label>
                        <label for="dot-2">
                            <span class="dot two"></span>
                            <span class="gender">Nam</span>
                        </label>
                        <label for="dot-3">
                            <span class="dot three"></span>
                            <span class="gender">Nữ</span>
                        </label>
                    </div>
                </div>
                <div class="button">
                    <input type="submit" name="register" value="Đăng ký">
                </div>
            </form>
            <div class="w-100 text-center">
                <p class="h5">Đã có tài khoản <a class="text-login" href="index.php?page=login">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</section>

<script>
// Định nghĩa các phương thức kiểm tra vào đối tượng Validator
Validator.isUniqueEmail = function(selector, message, checkFunction) {
    return {
        selector: selector,
        test: function(value) {
            if (value.trim() === '') {
                return message;
            }
            // Gọi AJAX để kiểm tra email có tồn tại hay không
            checkFunction(value);
            return '';
        }
    };
};

Validator.isUniquePhoneNumber = function(selector, message, checkFunction) {
    return {
        selector: selector,
        test: function(value) {
            if (value.trim() === '') {
                return message;
            }
            // Gọi AJAX để kiểm tra số điện thoại có tồn tại hay không
            checkFunction(value);
            return '';
        }
    };
};

Validator.isPhoneNumber = function(selector, message, regex) {
    return {
        selector: selector,
        test: function(value) {
            if (value.trim() === '') {
                return message;
            }
            if (!regex.test(value)) {
                return message;
            }
            return '';
        }
    };
};

Validator.isStrongPassword = function(selector, message, regex) {
    return {
        selector: selector,
        test: function(value) {
            if (value.trim() === '') {
                return message;
            }
            if (!regex.test(value)) {
                return message;
            }
            return '';
        }
    };
};
// Tiếp tục với phần cài đặt kiểm tra form như trước...
Validator({
    form: '#form-register',
    errorSelector: '.form-message',
    rules: [
        Validator.isRequired('#account_name', 'Vui lòng nhập tên đầy đủ của bạn'),
        Validator.isRequired('#account_email', 'Vui lòng nhập email của bạn'),
        Validator.isEmail('#account_email', 'Email không hợp lệ'),
        // Kiểm tra email đã tồn tại trong cơ sở dữ liệu
        Validator.isUniqueEmail('#account_email', 'Email đã được sử dụng trước đó', checkEmailExists),
        Validator.isRequired('#account_address', 'Vui lòng nhập địa chỉ của bạn'),
        Validator.isRequired('#account_phone', 'Vui lòng nhập số điện thoại của bạn'),
        // Kiểm tra số điện thoại 10 ký tự bắt đầu từ số 0
        Validator.isPhoneNumber('#account_phone', 'Số điện thoại phải là 10 chữ số bắt đầu bằng 0', /^[0][0-9]{9}$/),
        // Kiểm tra số điện thoại đã tồn tại trong cơ sở dữ liệu
        Validator.isUniquePhoneNumber('#account_phone', 'Số điện thoại đã được sử dụng trước đó', checkPhoneNumberExists),
        Validator.isRequired('#account_password', 'Vui lòng nhập mật khẩu'),
        Validator.minLength('#account_password', 8, 'Mật khẩu phải có ít nhất 8 ký tự'),
        // Kiểm tra mật khẩu mạnh
        Validator.isStrongPassword('#account_password', 'Mật khẩu phải có ít nhất 1 chữ cái hoa, 1 chữ cái thường, 1 số và 1 ký tự đặc biệt', /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/),
        Validator.isRequired('#account_password2', 'Vui lòng nhập lại mật khẩu'),
        Validator.isConfirmed('#account_password2', function() {
            return document.querySelector('#form-register #account_password').value;
        }, 'Mật khẩu nhập lại không khớp')
    ],
    onSubmit: function(data) {
        console.log(data); // In ra dữ liệu để kiểm tra
    }
});

function checkEmailExists(email, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'pages/handle/register.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === 'email_exists') {
                showError('#account_email', 'Email đã được sử dụng trước đó');
            } else {
                removeError('#account_email');
            }
        }
    };
    xhr.send('check_email=' + encodeURIComponent(email));
}

function checkPhoneNumberExists(phoneNumber, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'pages/handle/register.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === 'phone_exists') {
                showError('#account_phone', 'Số điện thoại đã được sử dụng trước đó');
            } else {
                removeError('#account_phone');
            }
        }
    };
    xhr.send('check_phone=' + encodeURIComponent(phoneNumber));
}

// Hàm hiển thị thông báo lỗi 
function showError(selector, message) {
    const formMessage = document.querySelector(selector).nextElementSibling;
    formMessage.innerText = message;
    formMessage.style.color = 'red';  // Đặt màu chữ thành đỏ
    formMessage.style.display = 'block';  // Hiển thị thông báo lỗi
}

// Hàm xóa thông báo lỗi
function removeError(selector) {
    const formMessage = document.querySelector(selector).nextElementSibling;
    formMessage.innerText = '';
    formMessage.style.display = 'none';  // Ẩn thông báo lỗi
}

</script>
























<!-- <script src="./assets/js/form_register.js"></script>
<section class="register pd-section">
    <div class="container">
        <div class="w-100 text-center p-relative">
            <div class="title">
                <h3 class="heading h3">Thành viên đăng ký</h3>
                <p class="desc">Đăng ký tài khoản ngay để mua hàng tại TheGioiDiDong!</p>
            </div>
            <div class="title-line"></div> -->

            <!-- Thông báo success hoặc error -->
            <?php
            // if (isset($_GET['message'])) {
            //     $message = $_GET['message'];

            //     if ($message == 'success') {
            //         echo '<div style="margin-top: 20px; padding: 10px; background-color: #d4edda; color: #155724; border-radius: 5px; text-align: center;">Đăng ký thành công! Vui lòng đăng nhập.</div>';
            //     } elseif ($message == 'error') {
            //         echo '<div style="margin-top: 20px; padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 5px; text-align: center;">Email đã đăng ký, vui lòng nhập email khác!</div>';
            //     }
            // }
            ?>

        <!-- </div>

        <div class="content">
            <form class="register__form" action="pages/handle/register.php" id="form-register" method="POST">
                <div class="user-details">
                    <div class="input-box form-group">
                        <label class="details form-label">Họ Tên</label>
                        <input class="input-form" id="account_name" onchange="getInputChange();" type="text" name="account_name" placeholder="Nhập vào tên của bạn" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Địa chỉ</label>
                        <input class="input-form" id="account_address" onchange="getInputChange();" type="text" name="customer_address" placeholder="Nhập vào địa chỉ của bạn" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Email</label>
                        <input class="input-form" id="account_email" onchange="getInputChange();" type="email" name="account_email" placeholder="Nhập vào địa chỉ email" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Số điện thoại</label>
                        <input class="input-form" id="account_phone" onchange="getInputChange();" type="number" name="account_phone" placeholder="Nhập vào số điện thoại" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Mật khẩu</label>
                        <input class="input-form" id="account_password" onchange="getInputChange();" type="password" name="account_password" placeholder="Nhập vào mật khẩu" required>
                        <span class="form-message"></span>
                    </div>
                    <div class="input-box form-group">
                        <label class="details form-label">Nhập lại mật khẩu</label>
                        <input class="input-form" id="account_password2" onchange="getInputChange();" type="password" name="account_password_confirn" placeholder="Nhập lại mật khẩu" required>
                        <span class="form-message"></span>
                    </div>
                </div>
                <div class="gender-details">
                    <input type="radio" name="account_gender" value="0" id="dot-1" checked>
                    <input type="radio" name="account_gender" value="1" id="dot-2">
                    <input type="radio" name="account_gender" value="2" id="dot-3">
                    <span class=" form-label">Giới tính:</span>
                    <div class="category">
                        <label for="dot-1">
                            <span class="dot one"></span>
                            <span class="gender">Không xác định</span>
                        </label>
                        <label for="dot-2">
                            <span class="dot two"></span>
                            <span class="gender">Nam</span>
                        </label>
                        <label for="dot-3">
                            <span class="dot three"></span>
                            <span class="gender">Nữ</span>
                        </label>
                    </div>
                </div>
                <div class="button">
                    <input type="submit" name="register" value="Đăng ký">
                </div>
            </form>
            <div class="w-100 text-center">
                <p class="h5">Đã có tài khoản <a class="text-login" href="index.php?page=login">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</section>
<script>
    Validator({
        form: '#form-register',
        errorSelector: '.form-message',
        rules: [
            Validator.isRequired('#account_name', 'vui lòng nhập tên đầy đủ của bạn'),
            Validator.isRequired('#account_email'),
            Validator.isRequired('#account_address'),
            Validator.isRequired('#account_phone', 'vui lòng nhập số'),
            Validator.isEmail('#account_email'),
            Validator.isRequired('#account_password'),
            Validator.minLength('#account_password', 6),
            Validator.isRequired('#account_password2'),
            Validator.isConfirmed('#account_password2', function() {
                return document.querySelector('#account_password').value;
            })
        ],
        onSubmit: function(data) {
            console.log(data);
        }
    })
</script> -->
