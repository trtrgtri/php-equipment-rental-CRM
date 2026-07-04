# Equipment Rental CRM

Mini project PHP MVC bảo mật — quản lý **khách thuê thiết bị** và **phiếu thuê** với mã phiếu không trùng.

Biến thể Lab06 Final từ Secure Mini CRM (Lead & Order) sang bài toán cho thuê thiết bị.

## Yêu cầu môi trường

- PHP 8.0+
- MySQL / MariaDB
- Extension PDO MySQL

## Cài đặt

### 1. Clone project

```bash
git clone <your-repo-url>
cd "Equipment Rental CRM"
```

### 2. Tạo database

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql
```

### 3. Cấu hình

Chỉnh `config/database.php` nếu cần:

```php
'host' => '127.0.0.1',
'database' => 'equipment_rental_crm',
'username' => 'root',
'password' => '',
```

Chỉnh `config/app.php`:

- `'debug' => true` — development (hiện lỗi chi tiết)
- `'debug' => false` — production (ẩn SQLSTATE/path)

### 4. Chạy server

```bash
php -S localhost:8000 -t public
```

Truy cập: http://localhost:8000

## Tài khoản demo

| Email             | Mật khẩu | Role  |
| ----------------- | -------- | ----- |
| admin@example.com | 123456   | admin |
| staff@example.com | 123456   | staff |

## Route table

| Method | URL                      | Controller@Action             | Mô tả                          |
| ------ | ------------------------ | ----------------------------- | ------------------------------ |
| GET    | `/`                      | HomeController@index          | Trang chủ / redirect dashboard |
| GET    | `/login`                 | AuthController@login          | Form đăng nhập                 |
| POST   | `/login`                 | AuthController@handleLogin    | Xử lý đăng nhập                |
| POST   | `/logout`                | AuthController@logout         | Đăng xuất                      |
| GET    | `/dashboard`             | DashboardController@index     | Tổng quan (yêu cầu login)      |
| GET    | `/public-renters/create` | PublicRenterController@create | Form công khai đăng ký thuê    |
| POST   | `/public-renters`        | PublicRenterController@store  | Xử lý form công khai + PRG     |
| GET    | `/renters`               | RenterController@index        | Danh sách khách thuê           |
| GET    | `/renters/create`        | RenterController@create       | Form thêm khách thuê           |
| POST   | `/renters/store`         | RenterController@store        | Tạo khách thuê                 |
| GET    | `/renters/edit?id=`      | RenterController@edit         | Form sửa                       |
| POST   | `/renters/update`        | RenterController@update       | Cập nhật                       |
| POST   | `/renters/delete`        | RenterController@delete       | Xóa (POST)                     |
| GET    | `/rentals`               | RentalController@index        | Danh sách phiếu thuê           |
| GET    | `/rentals/create`        | RentalController@create       | Form tạo phiếu thuê            |
| POST   | `/rentals/store`         | RentalController@store        | Tạo phiếu thuê                 |
| GET    | `/rentals/edit?id=`      | RentalController@edit         | Form sửa                       |
| POST   | `/rentals/update`        | RentalController@update       | Cập nhật                       |
| POST   | `/rentals/delete`        | RentalController@delete       | Xóa (POST)                     |
| GET    | `/health`                | HealthController@index        | JSON kiểm tra app/db           |

## Cấu trúc thư mục

```
project/
├── public/index.php          # Front Controller
├── config/app.php, database.php
├── app/Core/                   # Database, Router, helpers
├── app/Controllers/
├── app/Services/
├── app/Repositories/
├── app/Views/
├── database/schema.sql, seed.sql
└── storage/logs/
```

## Mapping từ Lab mẫu

| Lab mẫu (Lead/Order) | Equipment Rental CRM |
| -------------------- | -------------------- |
| leads                | renters (khách thuê) |
| orders               | rentals (phiếu thuê) |
| order_code           | rental_code (unique) |
| unique_lead_email    | unique_renter_email  |
| /leads               | /renters             |
| /orders              | /rentals             |
| web_php_final        | equipment_rental_crm |

## Kiểm tra EXPLAIN

```sql
EXPLAIN SELECT id, name, email, phone, status, created_at
FROM renters
WHERE name LIKE '%Nguyễn%'
ORDER BY created_at DESC
LIMIT 10 OFFSET 0;

EXPLAIN SELECT id, rental_code, renter_name, equipment_name, total_amount, status, created_at
FROM rentals
WHERE rental_code LIKE '%RNT%'
ORDER BY created_at DESC
LIMIT 10 OFFSET 0;
```

## Test cases (TC01–TC25)

| Mã   | Thao tác                       | Kết quả mong đợi                  |
| ---- | ------------------------------ | --------------------------------- |
| TC01 | GET /login                     | Form login hiển thị               |
| TC02 | Login sai                      | Lỗi thân thiện, không tạo session |
| TC03 | Login đúng                     | Redirect /dashboard, flash 1 lần  |
| TC04 | /dashboard chưa login          | Redirect /login                   |
| TC05 | Logout                         | Không truy cập dashboard          |
| TC06 | Timeout 30 phút                | Yêu cầu login lại                 |
| TC07 | Public form thiếu field        | Lỗi cạnh field, giữ old input     |
| TC08 | Honeypot có dữ liệu            | Request bị từ chối                |
| TC09 | Public form hợp lệ             | PRG + flash, F5 không trùng       |
| TC10 | Tạo renter thiếu field         | Lỗi validate                      |
| TC11 | Tạo renter hợp lệ              | Redirect /renters + flash         |
| TC12 | Email renter trùng             | Lỗi thân thiện                    |
| TC13 | Edit/update renter             | Form có dữ liệu cũ, update OK     |
| TC14 | Delete renter POST             | Xóa thành công                    |
| TC15 | Tạo rental hợp lệ              | Redirect /rentals + flash         |
| TC16 | rental_code trùng              | Lỗi mã phiếu đã tồn tại           |
| TC17 | /renters?q=Nguyễn              | Search đúng (tiếng Việt có dấu)   |
| TC18 | page=-1 hoặc page=999          | Chuẩn hóa về 1 hoặc totalPages    |
| TC19 | sort=created_at&direction=desc | Sort hợp lệ                       |
| TC20 | sort=id; DROP TABLE...         | Dùng sort mặc định                |
| TC21 | GET /health                    | JSON status                       |
| TC22 | POST /health                   | 405                               |
| TC23 | /unknown                       | 404                               |
| TC24 | debug=false + DB lỗi           | Safe message                      |
| TC25 | EXPLAIN query list             | Index được sử dụng                |

## Tính năng Bonus đã triển khai

Dự án đã được mở rộng thêm các tính năng nâng cao so với yêu cầu gốc của Lab06:

### 1. Soft Delete (Xóa mềm)

- Thêm cột `deleted_at` vào bảng `renters` và `rentals`.
- Thay vì xóa vật lý (`DELETE`), hệ thống thực hiện `UPDATE ... SET deleted_at = NOW()`.
- Các truy vấn danh sách, đếm tổng, phân trang đều thêm điều kiện `WHERE deleted_at IS NULL` để ẩn dữ liệu đã xóa, giúp bảo toàn lịch sử đối soát.

### 2. CSRF Protection (Chống giả mạo request)

- Tự động sinh `csrf_token` bằng `random_bytes()` và lưu vào Session.
- Tất cả form `POST` (Tạo mới, Cập nhật, Xóa, Login) đều có thẻ `<input type="hidden" name="csrf_token">`.
- Controller so khớp token thông qua hàm `hash_equals()` để vô hiệu hóa tấn công CSRF.

### 3. Role-Based Permission (Phân quyền theo vai trò)

- Chỉ tài khoản có `role = admin` mới được thực hiện hành động Xóa (Delete) khách thuê và phiếu thuê.
- Tài khoản `staff` không thấy nút "Xóa" trên giao diện và bị từ chối nếu cố gọi API xóa.

### 4. Seed dữ liệu số lượng lớn

- Script `database/seed_mass.php` tạo 200 khách thuê và 300 phiếu thuê ngẫu nhiên.
- Hỗ trợ kiểm thử hiệu năng phân trang, tìm kiếm và sắp xếp với dữ liệu thực tế.

### 5. Badge màu sắc theo trạng thái

- Mỗi trạng thái (`new`, `contacted`, `approved`, `inactive`, `pending`, `active`, `returned`, `overdue`, `cancelled`) có màu sắc riêng.
- Giúp người dùng dễ dàng nhận biết và phân loại trực quan.

---

## Bảo mật đã triển khai

- PDO prepared statements (ATTR_EMULATE_PREPARES=false)
- Output escape bằng `e()` trong View
- Server-side validation
- PRG pattern sau POST thành công
- Honeypot + rate limit (5s) form công khai
- session_regenerate_id(true) sau login
- Session timeout 30 phút
- Logout sạch (destroy session + cookie)
- Cookie HttpOnly, SameSite=Lax, Secure theo HTTPS
- Sort whitelist chống SQL injection
- Duplicate key handling không lộ SQLSTATE
- Production safe error messages + log vào storage/logs/app.log
