# Phân Tích Chi Tiết Cấu Trúc Dự Án Website Bán Điện Thoại

## I. Tổng quan về cấu trúc tổ chức

Dự án được tổ chức theo mô hình phân tách chức năng rõ ràng, với 11 thư mục chính và 14 file riêng lẻ bên ngoài. Cấu trúc này tuân theo nguyên tắc tách biệt các thành phần theo chức năng, giúp dễ dàng bảo trì và phát triển.

Mô hình tổ chức có thể được phân loại thành 5 nhóm chính:
1. **Thành phần giao diện**: assets/, pages/, fonts/
2. **Thành phần quản trị**: admin/
3. **Thành phần xử lý nghiệp vụ**: mail/, các file xác thực, cập nhật
4. **Thành phần thư viện**: vendor/, carbon/, fpdf/, tfpdf/
5. **Thành phần cấu hình và hỗ trợ**: .git/, .idea/, .qodo/, config_momo.json, composer files, .htaccess

## II. Phân tích chi tiết từng thư mục

### 1. Thư mục `admin/`
Đây là khu vực dành riêng cho quản trị viên, hoạt động như một ứng dụng con (sub-application) trong hệ thống.

**Đặc điểm chính:**
- **Kiến trúc MVC đơn giản**: Phân tách thành modules/, format/, config/
- **Bảo mật riêng biệt**: Có hệ thống đăng nhập và xác thực riêng
- **Dashboard**: Hiển thị thống kê, báo cáo doanh thu, đơn hàng
- **Quản lý chính**: Sản phẩm, đơn hàng, tài khoản, bài viết, tồn kho...

**Tương tác với các thành phần khác:**
- Truy vấn cùng database với phần frontend
- Có thể chia sẻ một số thư viện như format, config với frontend
- Hoạt động độc lập về phiên đăng nhập (session) với frontend người dùng

### 2. Thư mục `assets/`
Là kho lưu trữ tất cả tài nguyên tĩnh cho phần giao diện người dùng.

**Đặc điểm chính:**
- **CSS**: Phân chia thành nhiều file theo chức năng (main.css, layout.css, responsive.css...)
- **JavaScript**: Chứa các file JS xử lý tương tác (main.js, validator.js, toast_message.js...)
- **Images**: Tổ chức theo loại hình ảnh (product, banner, icon...)

**Tương tác với các thành phần khác:**
- Được tải bởi index.php và các file trong pages/
- Các file CSS áp dụng style cho cấu trúc HTML trong pages/base và pages/main
- File JS xử lý form validation, AJAX calls, hiệu ứng UI

### 3. Thư mục `carbon/`
Thư viện chuyên biệt xử lý thời gian và ngày tháng trong PHP.

**Đặc điểm chính:**
- **Định dạng thời gian**: Chuyển đổi và hiển thị ngày tháng theo nhiều định dạng
- **Tính toán**: Thêm/bớt ngày, tháng, so sánh thời gian
- **Múi giờ**: Xử lý chuyển đổi giữa các múi giờ khác nhau

**Sử dụng trong dự án:**
- Xử lý thời gian đặt hàng, giao hàng
- Tính thời hạn sử dụng OTP
- Thống kê theo khoảng thời gian (ngày, tuần, tháng...)

### 4. Thư mục `fonts/`
Chứa các font chữ tùy chỉnh sử dụng trong website.

**Đặc điểm chính:**
- Có thể chứa các định dạng font khác nhau (woff, woff2, ttf...)
- Được tổ chức theo họ font hoặc theo ngôn ngữ

**Tương tác với CSS:**
- Được khai báo và sử dụng thông qua @font-face trong CSS
- Hỗ trợ đa ngôn ngữ và hiển thị nhất quán trên các trình duyệt

### 5. Thư mục `fpdf/` và `tfpdf/`
Hai thư viện hỗ trợ tạo file PDF từ PHP:

**Đặc điểm chính:**
- **fpdf/**: Thư viện nguyên bản, hỗ trợ tạo PDF cơ bản
- **tfpdf/**: Phiên bản mở rộng, hỗ trợ thêm Unicode (quan trọng cho tiếng Việt)

**Ứng dụng thực tế:**
- Xuất hóa đơn đặt hàng
- Tạo báo cáo doanh thu, kho hàng
- In phiếu giao hàng, phiếu nhập kho
- Xuất thông tin sản phẩm

### 6. Thư mục `mail/`
Xử lý tất cả các chức năng liên quan đến email trong hệ thống.

**Đặc điểm chính:**
- **Tích hợp PHPMailer**: Sử dụng thư viện gửi mail chuyên nghiệp
- **Template email**: Chứa mẫu HTML cho các loại email khác nhau
- **Cấu hình SMTP**: Thiết lập kết nối tới server email

**Các loại email được gửi:**
- Email xác nhận đăng ký với mã OTP
- Email quên mật khẩu với mã OTP
- Email xác nhận đơn hàng
- Email thông báo trạng thái đơn hàng
- Email khuyến mãi, tin tức

### 7. Thư mục `pages/`
Chứa toàn bộ cấu trúc giao diện frontend, được tổ chức thành 3 phần chính:

**a. pages/base/**
- Chứa các thành phần giao diện cơ bản, tái sử dụng
- Header, footer, sidebar, navigation
- Các thành phần UI chung như form, banner, product card...

**b. pages/main/**
- Chứa nội dung chính cho từng trang cụ thể
- Trang chủ, danh sách sản phẩm, chi tiết sản phẩm
- Giỏ hàng, thanh toán, tài khoản...

**c. pages/handle/**
- Xử lý dữ liệu từ form (đăng nhập, đăng ký, đặt hàng...)
- Gọi đến database để thực hiện các thao tác CRUD
- Điều hướng sau khi xử lý (redirect)

**Tương tác với hệ thống:**
- Nhận dữ liệu từ index.php thông qua tham số GET
- Tương tác với database thông qua config từ admin/config/
- Sử dụng tài nguyên từ assets/ để hiển thị

### 8. Thư mục `vendor/`
Chứa các thư viện PHP bên thứ ba được quản lý qua Composer.

**Đặc điểm chính:**
- Tự động cài đặt dựa trên composer.json
- Cung cấp autoload để tự động nạp các class khi cần
- Thường không chỉnh sửa trực tiếp các file trong này

**Các thư viện có thể bao gồm:**
- PHPMailer: Gửi email
- Payment libraries: Tích hợp cổng thanh toán
- Security libraries: Bảo mật, mã hóa
- Utility libraries: Xử lý ảnh, file, validation...

### 9. Thư mục quản lý phát triển (`.git/`, `.idea/`, `.qodo/`)
Các thư mục này liên quan đến công cụ phát triển, không ảnh hưởng trực tiếp đến hoạt động của website:

**a. .git/**
- Quản lý phiên bản mã nguồn
- Lưu lịch sử commit, branch
- Hỗ trợ làm việc nhóm

**b. .idea/**
- Cấu hình IDE (PhpStorm hoặc các IDE JetBrains)
- Lưu cài đặt dự án, plugins
- Giúp tăng hiệu quả lập trình

**c. .qodo/**
- Có thể là thư mục cấu hình riêng cho dự án
- Hoặc cấu hình công cụ phát triển đặc thù

## III. Phân tích chi tiết các file bên ngoài thư mục

### 1. File `index.php` - Điểm vào chính
Đây là file quan trọng nhất, đóng vai trò điều phối toàn bộ website:

**Chức năng chính:**
- Khởi tạo session cho người dùng
- Tải các file cấu hình cơ bản (config, format)
- Tải giao diện chính thông qua pages/main.php
- Xử lý tham số URL để điều hướng tới các trang phù hợp

**Cấu trúc:**
- Phần HTML cơ bản với các liên kết CSS, JS
- Phần PHP nhúng để tải các file PHP khác
- Xử lý thông báo lỗi, success message

### 2. File xác thực và cập nhật mật khẩu
Ba file này cùng tạo thành một quy trình xử lý:

**a. verify_otp.php (5.1KB)**
- Xác thực OTP khi đăng ký tài khoản mới
- Kiểm tra mã OTP được gửi qua email
- Nếu thành công, tạo tài khoản và chuyển hướng đến trang đăng nhập

**b. verify-otp-fp.php (7.4KB)**
- Xác thực OTP khi quên mật khẩu
- Hoạt động tương tự verify_otp.php nhưng có luồng xử lý khác
- Nếu thành công, chuyển hướng đến update_pass.php

**c. update_pass.php (1.5KB)**
- Cho phép người dùng tạo mật khẩu mới
- Mã hóa mật khẩu trước khi lưu vào database
- Cập nhật mật khẩu và chuyển hướng về trang đăng nhập

**Điểm chung:**
- Tất cả đều kiểm tra session để đảm bảo quy trình đúng
- Có các biện pháp bảo mật như kiểm tra token, giới hạn thời gian
- Tương tác với database để cập nhật thông tin tài khoản

### 3. File `brute_force.html` (5.5KB)
File này liên quan đến biện pháp bảo mật chống tấn công brute force:

**Chức năng:**
- Có thể là trang được hiển thị khi phát hiện nhiều lần đăng nhập sai
- Chứa JavaScript để theo dõi và giới hạn số lần đăng nhập
- Hiển thị thông báo cảnh báo hoặc captcha để xác minh người dùng

**Vai trò bảo mật:**
- Ngăn chặn việc thử nhiều mật khẩu tự động
- Bảo vệ tài khoản người dùng
- Giảm tải cho server khi có tấn công

### 4. File `hashpass.php` (59B)
File nhỏ nhưng quan trọng cho bảo mật:

**Chức năng:**
- Chứa hàm mã hóa mật khẩu (có thể sử dụng bcrypt, Argon2...)
- Được gọi mỗi khi cần lưu hoặc kiểm tra mật khẩu
- Đảm bảo mật khẩu không bao giờ được lưu dưới dạng plain text

**Ứng dụng:**
- Trong quy trình đăng ký
- Trong quy trình đổi mật khẩu
- Trong quy trình đăng nhập (để so sánh)

### 5. File cấu hình Composer
Hai file quản lý thư viện bên ngoài:

**a. composer.json (108B)**
- Liệt kê các thư viện cần thiết và phiên bản
- Cấu hình autoload và các thiết lập khác
- File này do người phát triển chỉnh sửa

**b. composer.lock (23KB)**
- Ghi chính xác phiên bản đã cài đặt của mỗi thư viện
- Đảm bảo môi trường phát triển và triển khai nhất quán
- File này được tạo tự động, không nên chỉnh sửa trực tiếp

### 6. File `config_momo.json` (135B)
Cấu hình tích hợp thanh toán MoMo:

**Nội dung:**
- API key và secret key để kết nối với MoMo
- Thông tin merchant (ID, name)
- URL callback để nhận kết quả thanh toán
- Các thiết lập khác như currency, language...

**Vai trò:**
- Cung cấp thông tin xác thực cho API MoMo
- Cấu hình luồng thanh toán
- Bảo mật thông tin kết nối

### 7. File SQL
Hai file SQL dường như có nội dung tương tự, chứa cấu trúc database:

**a. dbsql (79KB) & b. QLCH.sql (78KB)**
- Định nghĩa các bảng trong database (18 bảng)
- Chứa dữ liệu mẫu để khởi tạo
- Các ràng buộc khóa chính, khóa ngoại

**Bảng chính:**
- `account`: Quản lý tài khoản người dùng và admin
- `product`, `category`, `brand`: Quản lý sản phẩm
- `orders`, `order_detail`: Quản lý đơn hàng
- `inventory`, `inventory_detail`: Quản lý kho
- Các bảng khác như `article`, `comment`, `evaluate`...

### 8. File `.htaccess` (177B)
File cấu hình Apache web server:

**Chức năng:**
- Điều chỉnh URL rewrite (tạo URL thân thiện)
- Thiết lập bảo mật (ngăn truy cập vào một số thư mục)
- Cấu hình redirect, error page
- Cài đặt các header HTTP cần thiết

**Ý nghĩa:**
- Cải thiện UX qua URL dễ đọc
- Tăng cường SEO
- Bảo vệ các file và thư mục nhạy cảm

### 9. File sơ đồ luồng
Hai file SVG mô tả luồng xử lý trong hệ thống:

**a. customer_flow.svg (4.0KB)**
- Mô tả quy trình người dùng: đăng ký, đăng nhập, mua hàng...
- Hiển thị các bước và điều kiện trong mỗi luồng xử lý
- Giúp hiểu cách hệ thống xử lý các tương tác của người dùng

**b. admin_flow.svg (4.3KB)**
- Mô tả quy trình quản trị: quản lý sản phẩm, đơn hàng...
- Hiển thị luồng công việc của admin
- Giúp hiểu cách hệ thống vận hành từ góc độ quản trị

### 10. File `README.md` (16KB)
Tài liệu mô tả tổng thể dự án:

**Nội dung:**
- Giới thiệu về dự án
- Cấu trúc thư mục và file
- Chức năng chính
- Hướng dẫn cài đặt và sử dụng
- Cấu trúc database
- Các công nghệ sử dụng

**Vai trò:**
- Tài liệu tham khảo cho team phát triển
- Hướng dẫn cho người mới tham gia
- Mô tả tổng quan về hệ thống

## IV. Phân tích mối quan hệ giữa các thành phần

### 1. Luồng xử lý chính
Khi một request đến server, luồng xử lý sẽ đi qua các bước:

1. **index.php** nhận request và khởi tạo môi trường
2. **admin/config/config.php** thiết lập kết nối database
3. **pages/main.php** xác định trang cần hiển thị dựa trên tham số URL
4. **pages/base/** và **pages/main/** tạo giao diện 
5. **assets/** cung cấp CSS, JS, hình ảnh cho giao diện
6. Các thư viện từ **vendor/** hỗ trợ các chức năng phụ trợ

### 2. Luồng xử lý đăng ký/đăng nhập
Quy trình đăng ký và xác thực tài khoản:

1. Người dùng điền form đăng ký trong **pages/base/register.php**
2. Dữ liệu gửi tới **pages/handle/** để xử lý
3. Hệ thống tạo OTP và sử dụng **mail/** để gửi email
4. Người dùng nhập OTP vào **verify_otp.php**
5. Nếu thành công, tài khoản được tạo và lưu vào database
6. Trong trường hợp quên mật khẩu, quy trình tương tự nhưng sử dụng **verify-otp-fp.php** và **update_pass.php**

### 3. Luồng xử lý đặt hàng
Quy trình từ chọn sản phẩm đến hoàn tất đơn hàng:

1. Người dùng xem sản phẩm qua **pages/main/products.php** và **pages/main/product_detail.php**
2. Thêm vào giỏ hàng qua **pages/main/cart.php**
3. Tiến hành thanh toán qua **pages/main/checkout.php**
4. Chọn phương thức thanh toán (COD, MoMo, VNPay)
5. Nếu thanh toán online, thông tin từ **config_momo.json** hoặc cấu hình VNPay được sử dụng
6. Đơn hàng được lưu vào database và thông báo gửi qua **mail/**
7. Chuyển đến trang cảm ơn **pages/main/thankiu.php**

### 4. Luồng xử lý quản trị
Quy trình quản lý hệ thống qua admin panel:

1. Admin đăng nhập qua **admin/login.php**
2. Dashboard hiển thị thông qua **admin/index.php**
3. Các module quản lý được tải từ **admin/modules/**
4. Thao tác CRUD trên các đối tượng (sản phẩm, đơn hàng...)
5. Xuất báo cáo có thể sử dụng **fpdf/** hoặc **tfpdf/**

## V. Phân tích bảo mật

### 1. Bảo mật tài khoản
- Mật khẩu được mã hóa qua **hashpass.php**
- Xác thực hai lớp với OTP qua email
- Chống brute force qua **brute_force.html**

### 2. Bảo mật giao dịch
- Sử dụng cổng thanh toán an toàn (MoMo, VNPay)
- Thông tin thanh toán được mã hóa
- Xác thực giao dịch qua callback URL

### 3. Bảo mật hệ thống
- Phân quyền rõ ràng giữa user và admin
- Kiểm tra session và quyền truy cập
- Cấu hình bảo mật qua **.htaccess**

## VI. Kết luận

Dự án được tổ chức theo cấu trúc phân tách chức năng, với sự phân biệt rõ ràng giữa phần frontend (người dùng), backend (quản trị), và các lớp xử lý nghiệp vụ. Mô hình này giúp:

1. **Dễ dàng phát triển**: Các thành phần độc lập, dễ phân công công việc
2. **Bảo trì thuận tiện**: Có thể cập nhật từng phần mà không ảnh hưởng tới toàn bộ hệ thống
3. **Khả năng mở rộng**: Có thể thêm chức năng mới một cách dễ dàng
4. **Tối ưu hóa hiệu suất**: Tải các thành phần cần thiết theo yêu cầu

Dự án sử dụng PHP thuần kết hợp với MySQL, áp dụng các nguyên tắc lập trình hướng thủ tục và một phần hướng đối tượng, phù hợp với quy mô ứng dụng thương mại điện tử tầm trung. 