# Website Bán Điện Thoại

Dự án website bán điện thoại sử dụng PHP và MySQL.

## Giới thiệu

Đây là một website bán hàng (điện thoại) đầy đủ chức năng, bao gồm cả giao diện người dùng và trang quản trị. Website được xây dựng bằng PHP thuần kết hợp với MySQL, tích hợp nhiều tính năng như đăng nhập/đăng ký, quản lý sản phẩm, giỏ hàng, thanh toán, xác thực OTP, và nhiều chức năng khác.

## Cấu trúc dự án

Cấu trúc thư mục chính:

```
BanDienThoai/
├── admin/                  # Khu vực quản trị
│   ├── config/             # Cấu hình kết nối database
│   ├── css/                # CSS cho trang admin
│   ├── format/             # Format dữ liệu
│   ├── js/                 # JavaScript cho admin
│   ├── modules/            # Các module chức năng admin
│   ├── pages/              # Trang admin
│   ├── partials/           # Phần giao diện tái sử dụng
│   ├── vendors/            # Thư viện bên ngoài 
│   ├── index.php           # Trang chính admin
│   └── login.php           # Đăng nhập admin
├── assets/                 # Tài nguyên trang người dùng
│   ├── css/                # CSS
│   ├── js/                 # JavaScript
│   └── images/             # Hình ảnh
├── carbon/                 # Thư viện carbon (xử lý thời gian)
├── fonts/                  # Font chữ
├── fpdf/                   # Thư viện tạo PDF
├── mail/                   # Xử lý gửi email
├── pages/                  # Trang người dùng
│   ├── base/               # Phần giao diện cơ bản (header, footer...)
│   ├── handle/             # Xử lý form, dữ liệu
│   └── main/               # Các trang chính
├── tfpdf/                  # Thư viện tạo PDF hỗ trợ Unicode
├── vendor/                 # Thư viện từ Composer
├── .htaccess               # Cấu hình Apache
├── composer.json           # Cấu hình Composer
├── composer.lock           # Lock file của Composer
├── config_momo.json        # Cấu hình thanh toán MoMo
├── index.php               # Trang chủ website
├── QLCH.sql                # File cấu trúc và dữ liệu database
├── verify_otp.php          # Xác thực OTP khi đăng ký
├── verify-otp-fp.php       # Xác thực OTP khi quên mật khẩu
└── update_pass.php         # Cập nhật mật khẩu
```

## Các chức năng chính

### 1. Phía người dùng (Front-end)
- **Đăng ký và đăng nhập**: Hệ thống đăng ký có xác thực qua email bằng mã OTP
- **Quên mật khẩu**: Khôi phục mật khẩu qua OTP gửi đến email
- **Xem sản phẩm**: Hiển thị danh sách sản phẩm theo danh mục, thương hiệu
- **Tìm kiếm**: Tìm kiếm sản phẩm theo tên, danh mục, giá...
- **Giỏ hàng**: Thêm/xóa sản phẩm, cập nhật số lượng
- **Thanh toán**: Hỗ trợ thanh toán online (MoMo, VNPay) và COD
- **Tài khoản**: Quản lý thông tin cá nhân, lịch sử đơn hàng
- **Đánh giá sản phẩm**: Người dùng có thể đánh giá sản phẩm đã mua
- **Bài viết**: Xem tin tức, bài viết về sản phẩm

### 2. Phía quản trị (Back-end)
- **Dashboard**: Tổng quan về doanh thu, đơn hàng, số lượng sản phẩm
- **Quản lý sản phẩm**: Thêm, sửa, xóa sản phẩm
- **Quản lý danh mục**: Thêm, sửa, xóa danh mục sản phẩm
- **Quản lý thương hiệu**: Thêm, sửa, xóa thương hiệu
- **Quản lý đơn hàng**: Xem, cập nhật trạng thái đơn hàng
- **Quản lý tài khoản**: Thêm, sửa, xóa tài khoản người dùng và admin
- **Quản lý bài viết**: Thêm, sửa, xóa bài viết/tin tức
- **Quản lý tồn kho**: Theo dõi số lượng sản phẩm, nhập/xuất kho
- **Báo cáo thống kê**: Xuất báo cáo doanh thu, sản phẩm bán chạy

## Luồng hoạt động

### 1. Luồng đăng ký tài khoản:
1. Người dùng truy cập trang đăng ký (`index.php?page=register`)
2. Điền thông tin và gửi form
3. Hệ thống gửi mã OTP qua email
4. Người dùng xác nhận OTP (`verify_otp.php`)
5. Tạo tài khoản và chuyển hướng về trang đăng nhập

### 2. Luồng mua hàng:
1. Người dùng duyệt sản phẩm trên trang chủ hoặc danh mục
2. Xem chi tiết sản phẩm (`index.php?page=product_detail&product_id=X`)
3. Thêm sản phẩm vào giỏ hàng
4. Xem giỏ hàng và tiến hành thanh toán (`index.php?page=checkout`)
5. Chọn phương thức thanh toán và hoàn tất đơn hàng
6. Xác nhận đơn hàng và chuyển hướng đến trang cảm ơn

### 3. Luồng quản trị:
1. Admin đăng nhập vào trang quản trị (`admin/login.php`)
2. Truy cập Dashboard để xem tổng quan
3. Quản lý sản phẩm, đơn hàng, danh mục... thông qua menu bên trái
4. Xử lý đơn hàng và cập nhật trạng thái
5. Quản lý người dùng và nội dung website

## Cài đặt và triển khai

### Yêu cầu hệ thống
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Apache2 hoặc Nginx
- Composer (để quản lý các thư viện PHP)

### Các bước cài đặt
1. Clone hoặc tải source code về máy:
```bash
git clone [link_repository]
```

2. Cài đặt các thư viện cần thiết bằng Composer:
```bash
composer install
```

3. Import file SQL vào database:
   - Tạo database mới tên `QLCH`
   - Import file `QLCH.sql` vào database

4. Cấu hình kết nối database:
   - Mở file `admin/config/config.php`
   - Cập nhật thông tin kết nối database (host, username, password, database)

5. Cấu hình gửi email (để gửi OTP):
   - Cập nhật thông tin SMTP trong các file xử lý email

6. Cấu hình thanh toán online (nếu sử dụng):
   - Mở file `config_momo.json` để cấu hình thanh toán MoMo

7. Thiết lập quyền truy cập cho các thư mục:
   - Đảm bảo thư mục `assets/images` và các thư mục cần ghi dữ liệu có quyền ghi

### Tài khoản mặc định
- **Admin**: 
  - Email: admin@example.com 
  - Password: admin123
- **User**: 
  - Email: user@example.com
  - Password: user123

## Công nghệ sử dụng

- **Back-end**: PHP, MySQL
- **Front-end**: HTML, CSS, JavaScript, jQuery
- **Thư viện**: 
  - PHPMailer: Gửi email
  - FPDF: Tạo file PDF
  - Carbon: Xử lý thời gian/ngày tháng
- **Thanh toán**: MoMo, VNPay

## Thông tin thêm

- Website hỗ trợ responsive, hiển thị tốt trên các thiết bị di động
- Hệ thống sử dụng hàm băm và salt để bảo mật mật khẩu
- Có tích hợp cơ chế chống brute-force attack

## Cấu trúc Database

Database gồm các bảng chính:
- `account`: Thông tin tài khoản người dùng
- `product`: Thông tin sản phẩm
- `category`: Danh mục sản phẩm
- `brand`: Thương hiệu
- `orders`: Đơn hàng
- `order_detail`: Chi tiết đơn hàng
- `customer`: Thông tin khách hàng
- `inventory`: Quản lý tồn kho
- `article`: Bài viết/tin tức
- `comment`: Bình luận
- `evaluate`: Đánh giá sản phẩm 
- `collection`: Bộ sưu tập sản phẩm
- `delivery`: Thông tin vận chuyển
- `metrics`: Số liệu thống kê hệ thống

### Chi tiết cấu trúc từng bảng

#### 1. Bảng `account` - Quản lý tài khoản
- `account_id`: INT, khóa chính, tự động tăng
- `account_name`: VARCHAR(255), tên người dùng
- `account_password`: VARCHAR(100), mật khẩu đã được mã hóa
- `account_email`: VARCHAR(255), email đăng nhập
- `account_phone`: VARCHAR(20), số điện thoại
- `account_type`: INT, loại tài khoản (0: user, 1: nhân viên, 2: admin)
- `account_status`: INT, trạng thái tài khoản (0: không hoạt động, 1: hoạt động)

#### 2. Bảng `customer` - Thông tin khách hàng
- `customer_id`: INT, khóa chính, tự động tăng
- `account_id`: INT, khóa ngoại liên kết với bảng account
- `customer_name`: VARCHAR(100), tên khách hàng
- `customer_gender`: INT, giới tính
- `customer_email`: VARCHAR(100), email liên hệ
- `customer_phone`: VARCHAR(20), số điện thoại
- `customer_address`: VARCHAR(255), địa chỉ

#### 3. Bảng `category` - Danh mục sản phẩm
- `category_id`: INT, khóa chính, tự động tăng
- `category_name`: VARCHAR(100), tên danh mục
- `category_description`: TEXT, mô tả danh mục
- `category_image`: VARCHAR(100), hình ảnh danh mục

#### 4. Bảng `brand` - Thương hiệu
- `brand_id`: INT, khóa chính, tự động tăng
- `brand_name`: VARCHAR(50), tên thương hiệu

#### 5. Bảng `product` - Sản phẩm
- `product_id`: INT, khóa chính, tự động tăng
- `category_id`: INT, khóa ngoại liên kết với bảng category
- `brand_id`: INT, khóa ngoại liên kết với bảng brand
- `product_name`: VARCHAR(255), tên sản phẩm
- `product_description`: TEXT, mô tả sản phẩm
- `product_price`: DECIMAL(10,0), giá sản phẩm
- `product_sale`: DECIMAL(10,0), giá khuyến mãi
- `product_quantity`: INT, số lượng trong kho
- `product_image`: VARCHAR(100), hình ảnh chính
- `product_status`: INT, trạng thái (0: ẩn, 1: hiển thị)
- `product_view`: INT, số lượt xem
- `product_sold`: INT, số lượng đã bán

#### 6. Bảng `capacity` - Dung lượng sản phẩm
- `capacity_id`: INT, khóa chính, tự động tăng
- `capacity_name`: VARCHAR(50), tên dung lượng (ví dụ: 10ml, 20ml...)

#### 7. Bảng `orders` - Đơn hàng
- `order_id`: INT, khóa chính, tự động tăng
- `customer_id`: INT, khóa ngoại liên kết với bảng customer
- `order_code`: VARCHAR(10), mã đơn hàng
- `order_date`: DATETIME, ngày đặt hàng
- `order_status`: INT, trạng thái đơn hàng
- `order_payment`: INT, phương thức thanh toán
- `order_total`: DECIMAL(10,0), tổng tiền
- `order_note`: TEXT, ghi chú đơn hàng
- `order_address`: VARCHAR(255), địa chỉ giao hàng
- `order_phone`: VARCHAR(20), số điện thoại giao hàng

#### 8. Bảng `order_detail` - Chi tiết đơn hàng
- `order_detail_id`: INT, khóa chính, tự động tăng
- `order_id`: INT, khóa ngoại liên kết với bảng orders
- `product_id`: INT, khóa ngoại liên kết với bảng product
- `quantity`: INT, số lượng sản phẩm
- `price`: DECIMAL(10,0), giá sản phẩm tại thời điểm mua

#### 9. Bảng `inventory` - Quản lý kho
- `inventory_id`: INT, khóa chính, tự động tăng
- `inventory_code`: VARCHAR(10), mã phiếu nhập kho
- `inventory_date`: DATETIME, ngày nhập kho
- `inventory_note`: TEXT, ghi chú
- `inventory_status`: INT, trạng thái
- `inventory_staff`: VARCHAR(100), nhân viên nhập kho

#### 10. Bảng `inventory_detail` - Chi tiết nhập kho
- `inventory_detail_id`: INT, khóa chính, tự động tăng
- `inventory_id`: INT, khóa ngoại liên kết với bảng inventory
- `product_id`: INT, khóa ngoại liên kết với bảng product
- `import_quantity`: INT, số lượng nhập
- `import_price`: DECIMAL(10,0), giá nhập
- `inventory_date`: DATETIME, ngày nhập

#### 11. Bảng `article` - Bài viết
- `article_id`: INT, khóa chính, tự động tăng
- `article_author`: VARCHAR(100), tác giả
- `article_title`: VARCHAR(255), tiêu đề
- `article_summary`: TEXT, tóm tắt
- `article_content`: TEXT, nội dung
- `article_image`: VARCHAR(100), hình ảnh
- `article_date`: DATE, ngày đăng
- `article_status`: INT, trạng thái

#### 12. Bảng `comment` - Bình luận
- `comment_id`: INT, khóa chính, tự động tăng
- `article_id`: INT, khóa ngoại liên kết với bảng article
- `comment_name`: VARCHAR(50), tên người bình luận
- `comment_email`: VARCHAR(50), email người bình luận
- `comment_content`: TEXT, nội dung bình luận
- `comment_date`: DATE, ngày bình luận
- `comment_status`: INT, trạng thái bình luận

#### 13. Bảng `evaluate` - Đánh giá sản phẩm
- `evaluate_id`: INT, khóa chính, tự động tăng
- `product_id`: INT, khóa ngoại liên kết với bảng product
- `account_id`: INT, khóa ngoại liên kết với bảng account
- `evaluate_content`: TEXT, nội dung đánh giá
- `evaluate_date`: DATETIME, ngày đánh giá
- `evaluate_star`: INT, số sao đánh giá

#### 14. Bảng `momo` - Thanh toán MoMo
- `id_momo`: INT, khóa chính, tự động tăng
- `partner_code`: VARCHAR(50), mã đối tác
- `order_id`: INT, mã đơn hàng
- `amount`: VARCHAR(50), số tiền
- `order_info`: VARCHAR(100), thông tin đơn hàng
- `order_type`: VARCHAR(50), loại đơn hàng
- `trans_id`: VARCHAR(100), mã giao dịch
- `pay_type`: VARCHAR(50), phương thức thanh toán
- `status_momo`: INT, trạng thái thanh toán

#### 15. Bảng `vnpay` - Thanh toán VNPay
- `id_vnpay`: INT, khóa chính, tự động tăng
- `vnp_amount`: VARCHAR(50), số tiền
- `vnp_bankcode`: VARCHAR(50), mã ngân hàng
- `vnp_banktranno`: VARCHAR(50), mã giao dịch ngân hàng
- `vnp_cardtype`: VARCHAR(50), loại thẻ
- `vnp_orderinfo`: VARCHAR(100), thông tin đơn hàng
- `vnp_paydate`: VARCHAR(50), ngày thanh toán
- `vnp_tmncode`: VARCHAR(50), mã đối tác
- `vnp_transactionno`: VARCHAR(50), mã giao dịch VNPay
- `status_vnpay`: INT, trạng thái thanh toán

#### 16. Bảng `collection` - Bộ sưu tập sản phẩm
- `collection_id`: INT, khóa chính, tự động tăng
- `collection_name`: VARCHAR(100), tên bộ sưu tập
- `collection_keyword`: VARCHAR(100), từ khóa tìm kiếm
- `collection_image`: VARCHAR(100), hình ảnh đại diện
- `collection_description`: VARCHAR(255), mô tả bộ sưu tập
- `collection_order`: INT, thứ tự hiển thị
- `collection_type`: INT, loại bộ sưu tập

#### 17. Bảng `delivery` - Thông tin vận chuyển
- `delivery_id`: INT, khóa chính, tự động tăng
- `delivery_name`: VARCHAR(100), tên phương thức vận chuyển
- `delivery_address`: VARCHAR(255), địa chỉ giao hàng
- `delivery_phone`: VARCHAR(20), số điện thoại liên hệ
- `delivery_note`: TEXT, ghi chú vận chuyển
- `delivery_status`: INT, trạng thái vận chuyển
- `order_id`: INT, khóa ngoại liên kết với bảng orders

#### 18. Bảng `metrics` - Số liệu thống kê hệ thống
- `metrics_id`: INT, khóa chính, tự động tăng
- `metrics_visit`: INT, số lượt truy cập
- `metrics_order`: INT, số đơn hàng
- `metrics_sale`: DECIMAL(10,0), doanh số
- `metrics_date`: DATE, ngày ghi nhận
- `metrics_online`: INT, số người dùng trực tuyến
- `metrics_product_view`: INT, lượt xem sản phẩm

### Mối quan hệ chính
1. `account` (1) - (1) `customer`: Một tài khoản có thể có một thông tin khách hàng
2. `customer` (1) - (n) `orders`: Một khách hàng có thể có nhiều đơn hàng
3. `orders` (1) - (n) `order_detail`: Một đơn hàng có nhiều chi tiết đơn hàng
4. `product` (1) - (n) `order_detail`: Một sản phẩm có thể xuất hiện trong nhiều chi tiết đơn hàng
5. `category` (1) - (n) `product`: Một danh mục có nhiều sản phẩm
6. `brand` (1) - (n) `product`: Một thương hiệu có nhiều sản phẩm
7. `product` (1) - (n) `evaluate`: Một sản phẩm có nhiều đánh giá
8. `account` (1) - (n) `evaluate`: Một tài khoản có thể đánh giá nhiều sản phẩm
9. `article` (1) - (n) `comment`: Một bài viết có nhiều bình luận
10. `inventory` (1) - (n) `inventory_detail`: Một phiếu nhập kho có nhiều chi tiết
11. `product` (1) - (n) `inventory_detail`: Một sản phẩm có thể xuất hiện trong nhiều chi tiết nhập kho
12. `orders` (1) - (1) `delivery`: Một đơn hàng có một thông tin vận chuyển
13. `collection` (1) - (n) `product`: Một bộ sưu tập có thể chứa nhiều sản phẩm 