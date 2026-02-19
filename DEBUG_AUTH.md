# Hướng dẫn Debug Authentication Issue

## Vấn đề
Sau khi đăng nhập thành công, bị redirect 302 về lại trang login.

## Các bước test

### 1. Test đăng nhập và xem logs
1. Xóa file log cũ: `storage/logs/laravel.log`
2. Đăng nhập vào `/admin/login`
3. Xem logs trong `storage/logs/laravel.log` để kiểm tra:
   - LoginResponse có được gọi không
   - Session có được regenerate không
   - Authenticate middleware có được gọi không
   - canAccessPanel có được gọi không

### 2. Test authentication status
Sau khi đăng nhập, truy cập: `/test-auth` (cần đăng nhập trước)
- Route này sẽ hiển thị JSON với thông tin authentication
- Kiểm tra xem `auth_check` có là `true` không
- Kiểm tra `session_id` có tồn tại không

### 3. Kiểm tra database sessions
Nếu dùng `SESSION_DRIVER=database`:
```sql
SELECT * FROM sessions ORDER BY last_activity DESC LIMIT 5;
```
- Kiểm tra xem có session mới được tạo sau khi login không
- Kiểm tra `user_id` có được set đúng không

### 4. Kiểm tra session storage
Nếu dùng `SESSION_DRIVER=file`:
- Kiểm tra thư mục `storage/framework/sessions`
- Đảm bảo có quyền ghi vào thư mục này

## Các điểm cần kiểm tra trong logs

### LoginResponse logs:
- `LoginResponse: User authenticated` - User có được authenticate không
- `LoginResponse: After regenerate` - Session có được regenerate không
- `LoginResponse: Redirecting to` - URL redirect có đúng không

### Authenticate Middleware logs:
- `Authenticate Middleware: Checking authentication` - Middleware có được gọi không
- `auth_check` - User có authenticated không
- `session_id` - Session ID có tồn tại không

### User::canAccessPanel logs:
- `User::canAccessPanel called` - Method có được gọi không
- `can_access` - Kết quả có là `true` không

## Giải pháp có thể

### Nếu session không được lưu:
- Kiểm tra quyền ghi vào `storage/framework/sessions` (nếu dùng file driver)
- Kiểm tra database connection (nếu dùng database driver)
- Kiểm tra `SESSION_LIFETIME` trong `.env`

### Nếu canAccessPanel trả về false:
- Kiểm tra xem `auth('web')->id()` có khớp với `$this->id` không
- Có thể cần đợi session được persist trước khi kiểm tra

### Nếu middleware redirect về login:
- Kiểm tra xem session có được share giữa các requests không
- Có thể cần đảm bảo cookie được set đúng domain/path
