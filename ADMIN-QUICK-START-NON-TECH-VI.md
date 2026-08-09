## HƯỚNG DẪN SỬ DỤNG ADMIN (DỄ HIỂU NHẤT, THEO TỪNG BƯỚC)

Tài liệu này dành cho người dùng không kỹ thuật.  
Cách đọc đơn giản: mở tab nào ở sidebar thì làm theo đúng mục tương ứng bên dưới.

---

## 1) Bắt đầu nhanh

### Bước 1: Đăng nhập
- Truy cập: `https://ten-mien-cua-ban.com/admin/login`
- Nhập email và mật khẩu được cấp.
- Bấm **Đăng nhập**.

### Bước 2: Nhìn bố cục admin
- **Sidebar bên trái**: các tab chức năng.
- **Phần giữa**: danh sách dữ liệu, nút tạo mới, form chỉnh sửa.
- **Góc phải trên**: tài khoản, thông báo, đăng xuất.

### Bước 3: Quy tắc thao tác chung
- Muốn thêm mới: bấm **Tạo mới / Create**.
- Muốn sửa: bấm vào dòng dữ liệu cần sửa.
- Muốn xóa: bấm biểu tượng thùng rác (nếu có).
- Luôn nhớ bấm **Lưu / Save** sau khi chỉnh.

---

## 2) Hướng dẫn theo từng tab trong sidebar

## Tab: Bảng điều khiển (Dashboard) *(nếu có)*

Dùng để xem nhanh tình hình tổng quát.

### Bạn làm gì ở tab này?
- Xem số liệu tổng: chiến dịch, click, bài viết, v.v.
- Kiểm tra nhanh mục nào đang tăng/giảm.
- Từ dashboard chuyển sang tab cần xử lý chi tiết.

---

## Tab: Người dùng (Users) *(nếu có quyền)*

Dùng để quản lý tài khoản đăng nhập admin.

### Thêm người dùng mới
1. Vào tab **Người dùng**.
2. Bấm **Tạo mới**.
3. Nhập tên, email, mật khẩu, phân quyền cho người dùng.
4. Bấm **Lưu**.

### Sửa hoặc khóa người dùng
1. Tìm người dùng trong danh sách.
2. Bấm vào dòng cần sửa.
3. Cập nhật thông tin hoặc quyền.
4. Bấm **Lưu**.

---

## Tab: Danh mục (Categories)

Dùng để nhóm thương hiệu/chiến dịch theo chủ đề.

### Thêm danh mục
1. Vào tab **Danh mục**.
2. Bấm **Tạo mới**.
3. Nhập tên danh mục.
4. Bấm **Lưu**.

### Sửa danh mục
1. Chọn danh mục cần sửa.
2. Đổi tên hoặc trạng thái.
3. Bấm **Lưu**.

---

## Tab: Thương hiệu (Brands)

Dùng để quản lý thông tin cửa hàng/thương hiệu.

### Thêm thương hiệu mới
1. Vào tab **Thương hiệu**.
2. Bấm **Tạo mới**.
3. Nhập:
   - Tên thương hiệu
   - Logo
   - Domain (nếu có)
   - Danh mục (nếu có)
4. Bấm **Lưu**.

### Sửa thương hiệu
1. Tìm thương hiệu trong danh sách.
2. Bấm vào dòng cần sửa.
3. Cập nhật tên/logo/domain.
4. Bấm **Lưu**.

---

## Tab: Chiến dịch (Campaigns)

Đây là tab quan trọng nhất để tạo trang ưu đãi.

### Tạo chiến dịch mới
1. Vào tab **Chiến dịch**.
2. Bấm **Tạo mới**.
3. Điền các trường quan trọng:
   - **Thương hiệu**
   - **Tiêu đề**
   - **Affiliate URL**
   - **Trạng thái** (`draft`, `active`, `paused`)
   - **Template** (`template1`, `template2`, `template3`, `template4`)
4. Bấm **Lưu**.

### Thêm mã giảm giá trong chiến dịch
1. Mở lại chiến dịch vừa tạo.
2. Kéo xuống phần **Mã giảm giá / Coupon items**.
3. Bấm **Thêm mục (+)**.
4. Nhập mã, ưu đãi, mô tả, thứ tự.
5. Bấm **Lưu**.

### Kiểm tra hiển thị ngoài website
- Mở link public của chiến dịch (dạng `/visit/{userCode}/{slug}`) để kiểm tra thực tế.

-- Có thể import file csv bằng nút "Import CSV", TẢI FILE Mẫu để điền thông tin, lưu lại và import vào

---

## Tab: Mã giảm giá (Coupons)

Dùng để quản lý mã tập trung, không cần vào từng chiến dịch.

### Sửa/xóa mã nhanh
1. Vào tab **Mã giảm giá**.
2. Dùng ô tìm kiếm theo mã, thương hiệu, chiến dịch.
3. Bấm vào dòng mã cần sửa.
4. Cập nhật nội dung rồi **Lưu**.

---

## Tab: Blog

Dùng để đăng bài SEO và nội dung hướng dẫn.

### Đăng bài mới
1. Vào tab **Blog**.
2. Bấm **Tạo mới**.
3. Nhập:
   - Tiêu đề
   - Nội dung
   - Ảnh đại diện (nếu có)
4. Bấm **Lưu**.

### Sửa bài đã đăng
1. Tìm bài viết trong danh sách.
2. Bấm vào bài.
3. Chỉnh sửa rồi **Lưu**.

### Có tính năng đăng bài tự động theo giờ bằng AI hoặc click để tự động tạo 1 bài viết mới
---

## Tab: Lượt click (Clicks)

Dùng để theo dõi hiệu quả affiliate.

### Xem hiệu quả chiến dịch
1. Vào tab **Lượt click**.
2. Dùng bộ lọc theo ngày/campaign/brand.
3. Đọc số click để biết chiến dịch nào hiệu quả.

---

## Tab: Import Status (Trạng thái Import)

Tab này dùng để theo dõi import CSV hàng loạt.

### Xem tiến độ import
1. Vào tab **Import Status**.
2. Kiểm tra:
   - Tổng dòng
   - Đã xử lý
   - Thành công
   - Thất bại
3. Nếu có lỗi:
   - Bấm **Xem lỗi**
   - Bấm **Tải CSV lỗi**
   - Sửa file rồi import lại phần lỗi

### Hủy/Rollback import
- Chỉ dùng khi import sai nhiều hoặc nhầm dữ liệu.
- Cần xác nhận kỹ trước khi bấm vì có thể xóa dữ liệu đã tạo từ lần import đó.

---

## Tab: Tài nguyên / Cài đặt khác (Resources, Settings...) *(nếu có)*

- Tùy từng dự án sẽ có thêm các tab phụ.
- Nguyên tắc vẫn giống nhau: mở tab -> tạo/sửa -> lưu -> kiểm tra ngoài website.

---

## 3) PHẦN QUAN TRỌNG NHẤT: IMPORT CSV TỪNG BƯỚC

## Bước A - Chuẩn bị trước import
- Backup dữ liệu trước khi import lớn.
- File CSV để dạng UTF-8.
- Dòng đầu là tên cột.
- Nên import thử file nhỏ trước (5-20 dòng).

## Bước B - Các cột quan trọng cần có
- Bắt buộc:
  - `brand`
  - `title`
  - `affiliate_url`
  - `status` (`draft`, `active`, `paused`)
- Thường dùng:
  - `category`, `domain`, `slug`, `intro`, `template`
- Cột coupon:
  - `coupon_codes`
  - `coupon_offers`
  - `coupon_descriptions`

Lưu ý:
- `coupon_codes = NO` nghĩa là deal không cần code.
- `coupon_descriptions = NO` hệ thống có thể tự sinh mô tả.

## Bước C - Thao tác import
1. Vào tab **Chiến dịch** (hoặc nơi có nút Import).
2. Bấm **Import**.
3. Chọn file CSV.
4. Map cột đúng tên trường.
5. Xác nhận import.

## Bước D - Kiểm tra sau import
1. Mở tab **Import Status** kiểm tra kết quả.
2. Nếu có dòng lỗi, tải CSV lỗi để sửa.
3. Mở ngẫu nhiên 3-5 chiến dịch vừa import để kiểm tra.
4. Test 1-2 link public để chắc chắn hoạt động.

---

## 4) Checklist vận hành hằng ngày

1. Kiểm tra campaign quan trọng đang `active`.
2. Kiểm tra affiliate URL không sai.
3. Cập nhật coupon mới nếu có.
4. Theo dõi click theo ngày.
5. Nếu import CSV: luôn kiểm tra Import Status ngay sau import.

---

## 5) Lỗi thường gặp và cách xử lý nhanh

- **Không thấy chiến dịch ngoài web**
  - Kiểm tra trạng thái có phải `active` không.
- **Link affiliate không chạy**
  - Kiểm tra lại `affiliate_url` có đúng và đầy đủ `https://`.
- **Import báo lỗi nhiều dòng**
  - Tải CSV lỗi, sửa đúng cột lỗi rồi import lại.
- **Ký tự tiếng Việt bị lỗi**
  - Lưu lại file CSV theo UTF-8 rồi import lại.

---

## 6) Lộ trình học cho người mới

Thứ tự nên học:
1. Danh mục -> Thương hiệu
2. Chiến dịch -> Mã giảm giá
3. Blog
4. Click report
5. Import CSV (thành thạo để làm nhanh số lượng lớn)

Chỉ cần làm tốt **Chiến dịch + Mã giảm giá + Import CSV**, bạn đã xử lý được phần lớn công việc vận hành.
