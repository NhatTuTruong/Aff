# Hướng dẫn cài đặt CampAff (Laravel)

Tài liệu này mô tả yêu cầu hệ thống, cài đặt trên máy local, triển khai lên hosting/VPS (kèm cron), các lệnh Artisan thường dùng, import MySQL, cấu hình, lỗi thường gặp và hướng dẫn sử dụng cơ bản.

---

## 1. Yêu cầu hệ thống

### Máy chủ / VPS

| Thành phần | Khuyến nghị |
|------------|-------------|
| **PHP** | **8.3 trở lên** (theo `composer.json`: `^8.3`) |
| **Composer** | 2.x |
| **MySQL / MariaDB** | MySQL 5.7+ hoặc MariaDB 10.3+ (hỗ trợ JSON, InnoDB) |
| **Web server** | **Nginx** hoặc **Apache** (mod_rewrite bật) |
| **Queue (tùy chọn nhưng nên có trên production)** | Driver `database` hoặc `redis` — xem mục Cấu hình |

### Tiện ích PHP cần bật (thường có sẵn)

- `openssl`, `pdo`, `pdo_mysql`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `curl`, `zip`

Kiểm tra nhanh:

```bash
php -v
php -m
```

### Môi trường local (Windows / macOS / Linux)

- PHP + Composer (hoặc **Laragon / XAMPP / Herd** trên Windows, **Homebrew** trên macOS).
- MySQL local hoặc Docker.
- **Git** để clone mã nguồn.

---

## 2. Cài đặt từng bước trên máy local

### Bước 1: Lấy mã nguồn

```bash
git clone <URL-kho-mã> CampAff
cd CampAff
```

### Bước 2: Cài dependency PHP

```bash
composer install
```

Nếu thiếu extension, Composer sẽ báo — cài extension tương ứng rồi chạy lại.

### Bước 3: Tạo file môi trường `.env`

Dự án có thể không kèm `.env.example`. Bạn có thể:

- Sao chép từ bản `.env` mẫu trên máy dev, **hoặc**
- Sao chép file `.env` có sẵn (nếu có) và chỉnh lại cho local.

Tối thiểu cần các khóa sau (xem thêm mục **5. Cấu hình**):

- `APP_NAME`, `APP_ENV=local`, `APP_DEBUG=true`, `APP_URL=http://localhost` (hoặc URL local của bạn)
- `DB_*` trỏ tới database local
- `APP_KEY` — tạo bằng lệnh dưới đây

### Bước 4: Tạo khóa ứng dụng

```bash
php artisan key:generate
```

### Bước 5: Tạo database MySQL

Tạo database trống (ví dụ `campaff`) trong phpMyAdmin hoặc MySQL CLI:

```sql
CREATE DATABASE campaff CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Cập nhật `.env`: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_HOST` (thường `127.0.0.1`).

### Bước 6: Chạy migration (và seeder nếu có)

```bash
php artisan migrate
```

Nếu có seeder mẫu:

```bash
php artisan db:seed
```

### Bước 7: Liên kết storage (ảnh public)

```bash
php artisan storage:link
```

### Bước 8: Quyền thư mục (Linux/macOS)

```bash
chmod -R ug+rwx storage bootstrap/cache
```

Trên Windows thường không cần, trừ khi dùng WSL/Linux.

### Bước 9: Chạy server phát triển

```bash
php artisan serve
```

Truy cập: `http://127.0.0.1:8000` (hoặc cổng đã chỉ định).

**Admin (Filament)** thường tại: `http://127.0.0.1:8000/admin` (đường dẫn có thể khác nếu đã cấu hình panel trong `AdminPanelProvider`).

### Bước 10: Queue (local — nếu dùng import CSV / job nền)

Mặc định `QUEUE_CONNECTION` có thể là `sync` (chạy ngay, không cần worker). Trên production nên dùng `database` hoặc `redis` và chạy worker (mục 3).

---

## 3. Deploy lên hosting / VPS

### 3.1. Chuẩn bị trên server

- PHP đúng phiên bản, extension đủ (mục 1).
- Document root trỏ vào thư mục **`public`** của Laravel (không trỏ thẳng vào root project).
- SSL (HTTPS) khuyến nghị cho production.

### 3.2. Triển khai mã nguồn

Ví dụ clone vào `/var/www/campaff`:

```bash
cd /var/www
git clone <URL> campaff
cd campaff
composer install --no-dev --optimize-autoloader
```

Production nên đặt:

```env
APP_ENV=production
APP_DEBUG=false
```

### 3.2.1. Trường hợp **không dùng Git / không chạy Composer trên server** — upload cả mã nguồn (có cả thư mục `vendor`)

Nhiều hosting chỉ có **FTP** hoặc **File Manager** (cPanel, DirectAdmin, v.v.), không có SSH hoặc bạn không muốn cài Composer trên máy chủ. Khi đó thường làm như sau:

1. **Trên máy tính (môi trường đã chạy được project):**
   - Đảm bảo đã `composer install` (production nên dùng `composer install --no-dev --optimize-autoloader` trước khi nén).
   - **Không** đưa lên mạng file `.env` chứa mật khẩu thật nếu bạn chia sẻ file zip — trên server hãy tạo `.env` riêng hoặc sửa sau khi upload.

2. **Đóng gói:** Nén toàn bộ thư mục project (gồm `app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`, `storage`, **`vendor`**, `composer.json`, v.v.). Có thể bỏ qua các thư mục không cần cho chạy production nếu muốn nhẹ (tùy chọn): `.git`, `node_modules` (nếu có và không build trên server), file test — **không** xóa `vendor` nếu server sẽ không chạy `composer install`.

3. **Upload:** Giải nén vào thư mục host (ví dụ `public_html/campaff` hoặc subdomain). **Document root của domain phải trỏ vào thư mục `public`** (nhiều panel có mục “Document root” / “Application root” — chỉnh trỏ tới `.../campaff/public`).

4. **Trên server vẫn cần thực hiện các bước Laravel** (ít nhất một lần). Tùy host:
   - **Có SSH / Terminal trong panel:** chạy các lệnh giống mục 3.3 → 3.5 (`php artisan key:generate` nếu thiếu key, `migrate`, `storage:link`, `config:cache`, v.v.).
   - **Không có SSH:** xem host có **“Run PHP script”**, **“Cron chạy artisan”**, hoặc nhờ nhà cung cấp bật SSH tạm; một số host chỉ cho phép đặt cron với lệnh `php /đường/dẫn/artisan migrate --force` — cần đường dẫn tuyệt đối tới `php` và `artisan`.

5. **Khi đã có sẵn `vendor` trên server:**
   - **Không bắt buộc** chạy `composer install` lại nếu phiên bản PHP server **trùng hoặc tương thích** với lúc bạn cài dependency, và cùng hệ điều hành tương đương (Linux → Linux).  
   - **Nên** chạy lại trên server nếu có Composer: `composer install --no-dev --optimize-autoloader` để đảm bảo autoload và package khớp môi trường Linux.

6. **Rủi ro khi copy `vendor` từ máy khác:**
   - **PHP khác phiên bản** trên server → có thể lỗi khi chạy. Giải pháp: cùng phiên bản PHP hoặc upload **không** kèm `vendor` và chạy `composer install` trên server (cần SSH/Composer).
   - **Windows → Linux:** thường ổn với `vendor`, nhưng đường dẫn và quyền file khác — nhớ `chmod` cho `storage`, `bootstrap/cache` (mục 3.6).
   - Package có **binary** theo OS (hiếm) có thể cần cài lại trên server.

7. **Luôn làm sau khi upload:** cấu hình `.env` cho đúng domain và database, `php artisan storage:link`, phân quyền `storage` / `bootstrap/cache`, bật cron `schedule:run` (mục 3.8), và (nếu dùng queue nền) cấu hình worker.

Tóm lại: **upload kèm `vendor` = bỏ bước Composer trên server**, nhưng **vẫn phải** cấu hình `.env`, chạy **Artisan** cần thiết (migrate, cache, storage link), trỏ web root vào **`public`**, và thiết lập **cron** như các mục dưới.

### 3.3. `.env` trên server

- Sao chép `.env` từ môi trường an toàn, chỉnh `APP_URL` (domain thật), `DB_*`, mail, queue.
- Chạy:

```bash
php artisan key:generate
```

(chỉ nếu chưa có `APP_KEY` hợp lệ.)

### 3.4. Migration trên server

```bash
php artisan migrate --force
```

(`--force` để xác nhận chạy khi `APP_ENV=production`.)

### 3.5. Tối ưu Laravel (production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Khi cần cập nhật cấu hình sau này:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 3.6. Quyền thư mục (Linux)

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

(Thay `www-data` bằng user/group của web server trên hosting của bạn.)

### 3.7. Queue worker (khuyến nghị)

Nếu `QUEUE_CONNECTION=database` (hoặc `redis`), cần process chạy:

```bash
php artisan queue:work --sleep=3 --tries=3
```

Trên production dùng **Supervisor** hoặc systemd để giữ process luôn chạy và tự khởi động lại.

Trước khi dùng queue `database`, cần có bảng jobs:

```bash
php artisan queue:table
php artisan migrate
```

(nếu project chưa có migration `jobs` — chỉ chạy khi thiếu.)

### 3.8. Cron job — **bắt buộc** cho lịch Laravel

Ứng dụng đăng ký nhiều tác vụ trong `app/Console/Kernel.php` (ví dụ: cảnh báo, kiểm tra landing, báo cáo chiến dịch, tự động blog…). Laravel chỉ chạy khi có **một dòng cron** gọi `schedule:run` mỗi phút.

Mở crontab:

```bash
crontab -e
```

Thêm (thay `/var/www/campaff` bằng đường dẫn thật):

```cron
* * * * * cd /var/www/campaff && php artisan schedule:run >> /dev/null 2>&1
```

User chạy cron nên là user có quyền đọc project và PHP CLI đúng phiên bản (đôi khi cần đường dẫn đầy đủ tới `php`, ví dụ `/usr/bin/php8.1`).

**Lưu ý:** Không có cron thì các lệnh theo lịch (email báo cáo, blog tự động, v.v.) **sẽ không chạy**.

---

## 4. Các lệnh Laravel (Artisan) thường dùng

| Mục đích | Lệnh |
|----------|------|
| Tạo khóa `APP_KEY` | `php artisan key:generate` |
| Chạy migration | `php artisan migrate` |
| Rollback bước trước | `php artisan migrate:rollback` |
| Xóa cache cấu hình | `php artisan config:clear` |
| Cache cấu hình (production) | `php artisan config:cache` |
| Liên kết `storage` | `php artisan storage:link` |
| Liệt kê lệnh | `php artisan list` |
| Tinker (debug) | `php artisan tinker` |
| Chạy lịch một lần (debug) | `php artisan schedule:run` |
| Xem lịch đã đăng ký | `php artisan schedule:list` |
| Queue worker | `php artisan queue:work` |
| Xóa job lỗi (nếu dùng failed table) | `php artisan queue:flush` (tùy phiên bản/cấu hình) |

Lệnh tùy chỉnh của project (ví dụ): `php artisan list | grep -E "blogs|reports|health|landing"` để xem các command nội bộ.

---

## 5. Import database MySQL

### Cách 1: File `.sql` qua dòng lệnh

```bash
mysql -u USER -p -h HOST DATABASE_NAME < backup.sql
```

Ví dụ local:

```bash
mysql -u root -p campaff < campaff_backup.sql
```

### Cách 2: phpMyAdmin

1. Vào phpMyAdmin → chọn database đích (hoặc tạo mới).
2. Tab **Import** → chọn file `.sql` → **Thực hiện**.

### Cách 3: Nén gzip

```bash
gunzip -c backup.sql.gz | mysql -u USER -p DATABASE_NAME
```

### Sau khi import

- Kiểm tra `.env` trùng database vừa import.
- Chạy (nếu cần đồng bộ cấu trúc mới hơn mã nguồn):

```bash
php artisan migrate --force
```

**Cảnh báo:** Backup trước khi migrate trên database đã có dữ liệu thật.

---

## 6. Cấu hình (tóm tắt `.env`)

| Nhóm | Biến gợi ý |
|------|------------|
| Ứng dụng | `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_URL`, `APP_KEY` |
| Database | `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` |
| Queue | `QUEUE_CONNECTION` — `sync` (đơn giản), `database` hoặc `redis` (production + worker) |
| Cache / session | `CACHE_DRIVER`, `SESSION_DRIVER` (file/redis tùy host) |
| Mail | `MAIL_MAILER`, `MAIL_HOST`, … (báo cáo / thông báo email nếu có) |

Chi tiết bổ sung nằm trong `config/*.php`; sau khi sửa `.env` trên production nên chạy `php artisan config:cache`.

---

## 7. Lỗi thường gặp

### 7.1. `No application encryption key has been specified`

- Chạy: `php artisan key:generate`
- Đảm bảo file `.env` tồn tại và có `APP_KEY=`

### 7.2. `500 Internal Server Error` sau khi deploy

- Bật tạm `APP_DEBUG=true` để xem lỗi (chỉ môi trường tin cậy), hoặc xem `storage/logs/laravel.log`.
- Kiểm tra quyền `storage`, `bootstrap/cache`.
- Đảm bảo document root là thư mục `public`.

### 7.3. `SQLSTATE[HY000] [1045] Access denied`

- Sai user/mật khẩu MySQL hoặc host (`DB_HOST` — một số host dùng `127.0.0.1` thay vì `localhost`).

### 7.4. Ảnh / file upload không hiển thị

- Chạy `php artisan storage:link`.
- Kiểm tra quyền ghi `storage/app/public`.

### 7.5. Import CSV / job chạy mãi không xong

- Trên production cần **queue worker** (`queue:work`) nếu không dùng `sync`.
- Kiểm tra `QUEUE_CONNECTION` và bảng `jobs` (nếu dùng driver `database`).

### 7.6. Cron / lịch không chạy

- Xác nhận crontab có dòng `* * * * * ... schedule:run`.
- Chạy tay: `php artisan schedule:run` và `php artisan schedule:list`.

### 7.7. Lỗi quyền trên Linux

- `chmod` / `chown` cho `storage` và `bootstrap/cache` như mục 3.6.

---

## 8. Cách sử dụng (tổng quan)

1. **Trang công khai:** Truy cập URL gốc (theo `APP_URL`) — trang chủ, landing chiến dịch, blog, v.v.
2. **Trang quản trị (Filament):** Thường `/admin` — quản lý chiến dịch, thương hiệu, mã giảm giá, thống kê, khách hàng, cài đặt…
3. **Tài khoản:** Tạo user trong admin (hoặc seeder); phân quyền admin thường qua cột/trường admin trên bảng users (theo mã nguồn hiện tại).
4. **Sau khi sửa `.env` hoặc deploy:** `config:cache`, `route:cache`, `view:cache` (production); khi debug thì `config:clear`.
5. **Backup định kỳ:** Database (mysqldump) + thư mục `storage/app` nếu có file quan trọng.

Tài liệu chi tiết chức năng admin có thể tham khảo các file hướng dẫn khác trong project (ví dụ `ADMIN-GUIDE-ADMIN-VI.md`, `ADMIN-QUICK-START-NON-TECH-VI.md` nếu có).

---

## Phụ lục: Cron gợi ý (một dòng)

```cron
* * * * * cd /đường/dẫn/tới/CampAff && php artisan schedule:run >> /dev/null 2>&1
```

Kết hợp **Supervisor** (hoặc tương đương) cho `php artisan queue:work` nếu dùng queue nền.

---

*Tài liệu tham khảo phiên bản Laravel 10, PHP 8.3+, Filament 3. Điều chỉnh đường dẫn và tên database cho đúng môi trường của bạn.*
