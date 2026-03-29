## HƯỚNG DẪN SỬ DỤNG TRANG ADMIN (DÀNH CHO NGƯỜI KHÔNG RÀNH KỸ THUẬT)

> Gợi ý: Bạn có thể **mở file này trong trình duyệt / Word / Google Docs và xuất ra PDF** để gửi cho người mua website.

---

### 1. Đăng nhập & tổng quan

- **Địa chỉ đăng nhập**
  - Truy cập: `/admin/login` (hoặc bấm nút “Login” nếu có trên giao diện public).
  - Nhập **email + mật khẩu admin** (do chủ site cung cấp).

- **Sau khi đăng nhập**
  - Bạn sẽ thấy **bảng điều khiển (Dashboard)** của Filament:
    - Thanh menu bên trái: các mục **Users, Brands, Campaigns, Coupons, Blogs, Categories, Clicks, Assets…**
    - Góc trên bên phải: **chuông thông báo**, avatar user, menu logout.

- **Đăng xuất**
  - Bấm vào **avatar / tên tài khoản** góc trên bên phải → chọn **Log out**.

---

### 2. Quản lý người dùng (Users)

- **Mục đích**: quản lý các tài khoản đăng nhập admin (hoặc affiliate nếu bạn mở rộng sau này).
- **Cách vào**: Menu trái → **Users**.

- **Thêm user mới**
  1. Bấm nút **Create** (hoặc “+ New User”).
  2. Nhập các trường chính:
     - **Name**: tên hiển thị.
     - **Email**: email dùng để đăng nhập.
     - **Password**: mật khẩu.
     - Tùy cấu hình: có thể có **is_admin**, **avatar**, **code**…
  3. Bấm **Save**.

- **Sửa / khóa user**
  - Trong danh sách, bấm vào **user cần sửa** → chỉnh sửa thông tin → **Save**.
  - Có thể **Deactivate / Delete** tùy action được cung cấp trong danh sách.

---

### 3. Quản lý thương hiệu / cửa hàng (Brands)

- **Mục đích**: mỗi **Brand** tương ứng 1 cửa hàng / website đối tác.
- **Cách vào**: Menu trái → **Brands**.

- **Thêm brand mới**
  1. Bấm **Create**.
  2. Nhập:
     - **Name**: tên thương hiệu (ví dụ: Shopee, Booking.com,…).
     - **Slug / Code** (nếu có): thường được tự sinh, không cần chỉnh nếu bạn không rành.
     - **Image / Logo**: upload logo PNG/JPG (nên nền trong suốt, kích thước vuông).
     - Các field mô tả khác (website, mô tả ngắn… nếu panel có).
  3. Bấm **Save**.

- **Sửa brand**
  - Chọn brand trong danh sách → cập nhật logo, tên, mô tả → **Save**.

---

### 4. Quản lý chiến dịch & landing page (Campaigns)

Đây là phần **quan trọng nhất** – quyết định các trang **/visit/{mã}/{slug}** mà người dùng sẽ thấy.

- **Cách vào**: Menu trái → **Campaigns**.

- **4.1. Cấu trúc 1 Campaign**
  - Gồm các phần chính (tùy config có thể khác chút):
    - **Brand**: chọn thương hiệu đã tạo ở mục Brands.
    - **Title / Subtitle / Intro**: tiêu đề, mô tả ngắn, giới thiệu dùng để hiển thị trên landing (template).
    - **Slug**: dạng `00000/ten-thuong-hieu` (hệ thống thường tự sinh; không nên đổi nếu chưa hiểu).
    - **Type & Template**:
      - `type = coupon` dùng cho trang nhiều mã giảm giá.
      - `template` = `template1`, `template2`, `template3`,… quyết định layout.
    - **Affiliate URL**: link tracking của bạn (ví dụ: đường dẫn từ Impact, CJ, Partnerize,…).
    - **Status**:
      - `active` (hoặc tương tự): chỉ những campaign **active** mới được hiển thị ở môi trường production.
    - **Coupon Items**: danh sách các **coupon / deal** gắn với campaign (xem chi tiết trong phần 5).

- **4.2. Thêm chiến dịch mới**
  1. Vào **Campaigns** → bấm **Create**.
  2. Chọn **Brand**.
  3. Nhập **Title** (ví dụ: “Shopee Coupons & Promo Codes”).
  4. Nhập **Intro / Subtitle** (nếu có): dùng để hiển thị ở mục “About us” trên landing.
  5. Điền **Affiliate URL** (link tracking).
  6. Chọn **Type** và **Template** (nếu không chắc, dùng template mặc định đang hoạt động tốt – ví dụ `template3`).
  7. Đặt **Status = active** để trang live.
  8. Bấm **Save**.
  9. Sau khi lưu xong, cuộn xuống phần **Coupon Items** để thêm mã giảm giá (bước 5 bên dưới).

- **4.3. Sử dụng template3 (giao diện đẹp giống site coupon lớn)**
  - Trong form campaign, chọn **Template**: `template3` (hoặc tên template mới bạn đang dùng).
  - Hệ thống sẽ render landing với:
    - Banner thương hiệu.
    - Dải coupon 2 cột, ribbon phần trăm/đồng, popup copy code, filter (All / Codes / Deals / Free shipping).

---

### 5. Quản lý coupon / mã giảm giá (CouponItems & Coupons)

Hệ thống có:
- **CouponItems**: danh sách mã thuộc từng **Campaign** (thường nằm trong repeater trong form Campaign).
- **Coupons (resource riêng)**: bảng tổng các mã (tùy app của bạn).

#### 5.1. Thêm mã giảm giá trong Campaign

1. Mở **Campaign** cần chỉnh → tab / phần **Coupon Items**.
2. Bấm nút **Add item / +** để thêm dòng.
3. Mỗi dòng thường có:
   - **Code**: mã giảm giá (có thể để trống nếu là deal không cần code).
   - **Offer**: mô tả ưu đãi (ví dụ: “20% off”, “$10 off $50+”, “Free shipping over $50”).
   - **Description**: mô tả dài hơn (tùy template, có thể hiển thị).
   - **Sort order** (nếu có): số thứ tự hiển thị (1, 2, 3, …). **Số càng nhỏ hiển thị càng trên**.
4. Bấm **Save** campaign.
5. Ra ngoài landing `/visit/{mã}/{slug}` để kiểm tra.

> Lưu ý: Hệ thống đã cấu hình để **tự động sắp xếp theo sort_order**, nếu bỏ trống thì dùng thứ tự tạo.

#### 5.2. Quản lý coupon ở resource “Coupons”

- Vào menu trái → **Coupons**:
  - Có thể xem **tất cả mã** trên hệ thống.
  - Tìm kiếm theo **code / brand / campaign**.
  - Sửa, xóa nhanh từng mã (phần lớn thao tác vẫn nên làm trong Campaign cho dễ hình dung).

---

### 6. Quản lý blog / nội dung SEO (Blogs)

- **Mục đích**: đăng bài viết blog để kéo traffic SEO, giải thích cách sử dụng mã, review cửa hàng.
- **Cách vào**: Menu trái → **Blogs**.

- **Thêm bài viết mới**
  1. Bấm **Create**.
  2. Nhập:
     - **Title**: tiêu đề bài viết.
     - **Slug**: thường tự sinh, kiểu `cach-su-dung-ma-giam-gia-shopee`.
     - **Intro / Body**: nội dung bài viết (Rich Editor).
     - **Featured Image**: ảnh đại diện (nếu có).
  3. Bấm **Save**.
  4. Bài viết sẽ hiển thị ở:
     - Trang **/blog** (danh sách).
     - Trang chi tiết: `/blog/{slug}`.
     - Một số bài mới sẽ hiện trên **trang chủ** ở mục “Latest Blog Posts”.

---

### 7. Quản lý danh mục (Categories) & cấu hình mặc định

- **Categories**
  - Menu trái → **Categories**.
  - Dùng để gắn **Campaign / Brand** vào các nhóm như “Travel”, “Hosting”, “Fashion”… nếu bạn cài mapping.

- **Default categories cho home**
  - Ở code có cấu hình `default_categories.names` và `User::defaultCategoryNames()`.
  - Phần này được dùng để hiển thị **Popular Categories** trên trang chủ.
  - Nếu cần đổi danh sách mặc định, nên nhờ dev chỉnh trong file cấu hình, hoặc ghi chú cho người mua.

---

### 8. Theo dõi click & analytics

- **Click Resource**
  - Menu trái → **Clicks** (tên có thể hơi khác).
  - Xem danh sách các **click** đi ra từ link `/out/{userCode}/{slug}`:
    - Campaign nào, IP, thời gian…

- **Page view & thời gian trên trang**
  - Mỗi landing gọi API `/api/track-page-view/{pageView}` khi người dùng xem trang.
  - Hệ thống lưu:
    - **Thời gian trên trang (time_on_page)**.
    - **Bounce** (thoát nhanh).
  - Thông tin này được dùng cho báo cáo và tối ưu.

- **Báo cáo định kỳ (cron)**
  - Trong `app/Console/Kernel.php` đã cấu hình:
    - `reports:send-campaign-daily`: gửi báo cáo hiệu suất 2 lần/ngày.
    - `blogs:generate-daily --respect-daily-limit`: nếu bật Autopilot blog.
    - `notifications:check-alerts`, `health:check-landing`,…
  - Với người dùng cuối (admin non-tech), chỉ cần biết: **báo cáo có thể được gửi tự động qua email** nếu đã cấu hình mail.

---

### 9. Quản lý nội dung header / footer & trang tĩnh

- **Header / Footer (SiteContent)**
  - Hệ thống có model **`SiteContent`** lưu:
    - **Header nav**: menu trên (Home, Blog, About, Contact,…).
    - **Footer columns**: cột link ở footer (Explore, Legal,…).
  - Nếu admin đã có giao diện để chỉnh (Filament resource hoặc settings), bạn có thể:
    - Vào mục tương ứng → sửa JSON / form → **Save**.
  - Nếu chưa expose ra panel, cần dev hỗ trợ; nhưng với người mua, có thể chấp nhận cấu hình sẵn.

- **Trang pháp lý**
  - Các route:
    - `/about`, `/contact`, `/privacy`, `/affiliate-disclosure`, `/terms`, `/cookie-policy`.
  - Nội dung các trang này là các file Blade trong `resources/views/legal/*.blade.php`.
  - Người không rành kỹ thuật có thể nhờ dev / freelancer chỉnh text đúng với chính sách mới.

---

### 10. Import / Export dữ liệu (CSV)

Nếu trong panel của bạn có các nút **Import / Export** (Filament Exporter/Importer):

- **Export**
  - Vào resource (ví dụ Campaigns / Coupons).
  - Bấm **Export** → chọn định dạng (thường là CSV).
  - File này dùng để backup hoặc chỉnh sửa ngoài Excel rồi import lại.

- **Import**
  - Bấm **Import** → chọn file CSV đúng cấu trúc.
  - Hệ thống sẽ map cột và tạo/sửa records tương ứng.
  - Nếu có lỗi, thường sẽ có phần **Failed rows** để tải về, xem dòng nào lỗi.

> Gợi ý cho người mua: **luôn export backup** trước khi import hàng loạt.

---

### 11. Thông báo trong admin (Notifications)

- **Chuông thông báo**
  - Góc trên bên phải panel có icon **chuông**.
  - Một số lệnh (`test-notification`, cảnh báo báo cáo,…) gửi thông báo vào đây.
  - Admin có thể bấm vào xem chi tiết.

---

### 12. Cách xuất file này ra PDF

1. Mở file `ADMIN-GUIDE-ADMIN-VI.md` trong:
   - VS Code / Cursor → copy vào **Google Docs / Word**.
   - Hoặc dán vào trình soạn thảo Markdown online.
2. Canh lại tiêu đề / logo nếu cần.
3. Từ Google Docs / Word: chọn **File → Download → PDF**.
4. Gửi file PDF đó cho người mua website.

---

Nếu bạn muốn, có thể tạo thêm **phiên bản tiếng Anh** của hướng dẫn này để gửi kèm khi bán cho khách quốc tế.

