## HƯỚNG DẪN NHANH TRANG ADMIN (DÀNH CHO NGƯỜI KHÔNG BIẾT LẬP TRÌNH)

Mục tiêu: Sau khi đọc file này, bạn có thể **tự thêm/sửa thương hiệu, thêm mã giảm giá, đăng bài blog, kiểm tra click** mà **không cần đụng vào code**.

---

### 1. Đăng nhập & đăng xuất

- **Bước 1 – Mở trang admin**
  - Vào trình duyệt, gõ: `https://ten-mien-cua-ban.com/admin/login`  
  - Hoặc: vào trang chủ → tìm nút **Đăng nhập / Quản trị** nếu có.

- **Bước 2 – Nhập tài khoản**
  - Nhập **Email** và **Mật khẩu** mà chủ website cấp cho bạn.
  - Bấm **Đăng nhập**.

- **Đăng xuất**
  - Góc trên bên phải, bấm vào **avatar / tên của bạn** → chọn **Đăng xuất**.

---

### 2. Bố cục trang admin (Filament)

Sau khi đăng nhập, bạn sẽ thấy:

- **Thanh bên trái**: các menu chính:
  - Bảng điều khiển (nếu có), Người dùng, Thương hiệu, Chiến dịch, Mã giảm giá, Blog, Danh mục, Lượt click, Tài nguyên, …
- **Khu vực chính giữa**: danh sách + form thêm/sửa.
- **Góc phải trên**: **chuông thông báo**, avatar tài khoản.

> Nguyên tắc:  
> - Muốn **xem danh sách** → bấm vào tên mục ở thanh bên trái.  
> - Muốn **thêm mới** → bấm nút **Tạo mới**.  
> - Muốn **sửa** → bấm vào dòng cần sửa trong bảng.

---

### 3. Quy trình cơ bản để tạo 1 trang cửa hàng có mã giảm giá

Quy trình tiêu chuẩn gồm **3 bước**:

1. **Tạo thương hiệu** – ví dụ Shopee, Booking.com.
2. **Tạo chiến dịch** – gắn với thương hiệu + affiliate link.
3. **Thêm mã giảm giá trong chiến dịch**.

Khi làm xong 3 bước, bạn sẽ có **trang landing /visit/{mã}/{slug}** với đầy đủ mã giảm giá.

---

### 4. Tạo & quản lý Thương hiệu

**4.1. Mở danh sách thương hiệu**

- Vào menu trái → bấm **Thương hiệu**.  
- Bạn sẽ thấy bảng liệt kê tất cả thương hiệu (tên + logo).

**4.2. Thêm thương hiệu mới**

1. Bấm nút **Tạo mới** (thường ở góc trên bên phải).
2. Điền các thông tin:
   - **Tên**: tên thương hiệu (ví dụ: “Shopee”, “Booking.com”).
   - **Logo / Ảnh**: bấm **Chọn tệp / Tải lên** → chọn logo từ máy tính.
   - Các ô khác nếu có (website, mô tả ngắn…) → điền nếu bạn hiểu, có thể bỏ trống.
3. Bấm **Lưu**.

**4.3. Sửa thương hiệu**

- Trong danh sách, **bấm vào tên thương hiệu** muốn sửa.
- Thay logo, đổi tên nếu cần → **Lưu**.

---

### 5. Tạo & quản lý Chiến dịch

Chiến dịch là **trang cửa hàng + danh sách mã giảm giá**.

**5.1. Mở danh sách chiến dịch**

- Vào menu trái → **Chiến dịch**.

**5.2. Thêm chiến dịch mới**

1. Bấm **Tạo mới**.
2. Điền các phần quan trọng (tên trường có thể hơi khác nhưng ý giống nhau):
   - **Thương hiệu**: chọn thương hiệu đã tạo ở bước 4.
   - **Tiêu đề**: tiêu đề trang (ví dụ: “Ưu đãi Shopee & mã giảm giá”).
   - **Chú thích / Giới thiệu**: mô tả ngắn, xuất hiện ở phần giới thiệu.
   - **Đường dẫn affiliate**: link affiliate của bạn (lấy từ mạng affiliate).
   - **Loại**: thường chọn `coupon` (nếu không chắc).
   - **Giao diện**: chọn giao diện đẹp đang dùng (ví dụ: `template3`).
   - **Trạng thái**: chọn **active** (hoặc tương đương) để trang hiển thị.
3. Bấm **Lưu**.

> Đến đây: bạn đã có **trang cửa hàng trống** (chưa có mã).  
> Bước tiếp theo: **thêm mã giảm giá vào chiến dịch**.

---

### 6. Thêm mã giảm giá trong chiến dịch

Sau khi **Lưu** chiến dịch xong:

1. Ở màn hình chỉnh chiến dịch, cuộn xuống phần **Mã trong chiến dịch / Mã giảm giá**.
2. Bấm **Thêm mục (+)** để thêm một dòng mã mới.
3. Với mỗi dòng:
   - **Mã**: mã giảm giá (nếu là deal không cần mã, có thể bỏ trống).
   - **Ưu đãi**: tiêu đề ưu đãi (ví dụ: “20% OFF cho người dùng mới”).
   - **Mô tả**: mô tả rõ hơn (không bắt buộc).
   - **Thứ tự**: số thứ tự (1 là trên cùng, 2 là thứ 2,…).
4. Thêm bao nhiêu mã tùy ý.
5. Bấm **Lưu** lại chiến dịch.

**Xem kết quả**

- Lấy đường dẫn slug của chiến dịch (thường được hệ thống tạo dạng `00000/ten-thuong-hieu`).
- Mở link dạng:  
  `/visit/00000/ten-thuong-hieu`  
  (hoặc copy URL trực tiếp từ admin nếu có nút xem trước).

---

### 7. Chỉnh sửa hoặc xóa một mã giảm giá

**Cách 1 – Qua chiến dịch (dễ hiểu hơn)**

1. Vào **Chiến dịch** → chọn chiến dịch cần chỉnh.
2. Kéo xuống phần **Mã trong chiến dịch**:
   - Sửa nội dung (Mã/Ưu đãi/Mô tả/Thứ tự).
   - Hoặc bấm icon **thùng rác** để xóa dòng.
3. Bấm **Lưu**.

**Cách 2 – Qua mục Mã giảm giá (nếu bạn quen)**

1. Vào menu trái → **Mã giảm giá**.
2. Tìm theo **mã** hoặc **thương hiệu**.
3. Bấm vào dòng muốn sửa → chỉnh → **Lưu**.

---

### 8. Đăng bài blog để kéo SEO

**8.1. Mở mục Blog**

- Vào menu trái → **Blog**.

**8.2. Thêm bài viết mới**

1. Bấm **Tạo mới**.
2. Nhập:
   - **Tiêu đề**: tiêu đề bài viết (ví dụ: “Cách dùng mã giảm giá Shopee an toàn”).
   - **Nội dung**: nội dung bài viết (dịch vụ này dùng trình soạn thảo kiểu Word).
   - **Ảnh đại diện**: upload ảnh minh họa (nếu có).
3. Bấm **Lưu**.

4. Hoặc Bài viết có thể tự động đăng

**8.3. Bài viết hiển thị ở đâu?**

- Danh sách: `/blog`.
- Chi tiết: `/blog/{slug}`.
- Một số bài mới sẽ xuất hiện trên **trang chủ** phần “Bài blog mới nhất”.

---

### 9. Xem lượt click & hiệu quả chiến dịch

**9.1. Đường dẫn affiliate và chuyển hướng**

- Người dùng bấm vào nút **Nhận mã / Mua ngay** trên landing → đi qua route `/out/{userCode}/{slug}` → sau đó mới tới link affiliate.

**9.2. Xem lượt click trong admin**

1. Vào menu trái → **Lượt click** (tên có thể khác chút, nhưng ý là click).
2. Bảng sẽ cho bạn thấy:
   - Chiến dịch nào được click.
   - Thời gian.
   - Một số thông tin đi kèm (IP, user agent…) tùy hệ thống.

> Gợi ý: dùng phần lọc / tìm kiếm ở đầu bảng để xem **theo từng chiến dịch hoặc thương hiệu**.

---

### 10. Xem thống kê lượt xem trang *(tùy phiên bản)*

- Các trang landing có gắn theo dõi **time_on_page** (thời gian ở trên trang) và **bounce** (thoát nhanh).
- Tùy cấu hình mà admin của bạn có:
  - Màn hình **Bảng điều khiển** hiển thị số lượt xem.
  - Hoặc báo cáo gửi email hằng ngày.

Nếu bạn không thấy phần này, hãy hỏi người cài đặt website cho bạn.

---

### 11. Chỉnh menu đầu trang / cuối trang (nếu đã mở trong admin)

Tùy vào việc dev có đưa phần này vào admin hay chưa:

- Nếu có mục kiểu **Cài đặt / Nội dung trang / Điều hướng**:
  1. Vào mục đó.
  2. Sửa tên menu, URL (Home, Blog, About, Contact,…).
  3. Bấm **Lưu**.

- Nếu **không thấy** mục này:
  - Có thể menu đang được cấu hình cố định trong code.
  - Hãy nhờ dev / người bán website sửa giúp khi cần.

---

### 12. Các lưu ý quan trọng cho người không rành kỹ thuật

1. **Luôn bấm Lưu** sau khi chỉnh bất cứ thứ gì.
2. **Hạn chế xóa** dữ liệu nếu không chắc – tốt nhất sửa nội dung thay vì xóa hẳn.
3. Khi chỉnh **Đường dẫn affiliate**, nên copy chính xác từ mạng affiliate, tránh tự gõ tay.
4. Nếu trang không hiển thị chiến dịch:
   - Kiểm tra lại **Trạng thái** (đã `active` chưa).
   - Kiểm tra thương hiệu đã tồn tại và còn hoạt động.
5. Trước khi nhập số lượng lớn (import CSV), hãy **nhờ dev hoặc chủ website** nếu bạn không quen.

---

### 13. Cách dùng file này hiệu quả

1. In file này ra giấy **hoặc** mở song song trên màn hình.
2. Làm theo đúng **thứ tự**:
   - Tạo thương hiệu → Tạo chiến dịch → Thêm mã giảm giá → Kiểm tra landing.
3. Khi đã quen, bạn chỉ cần nhớ 3 bước đó là quản lý được phần lớn tính năng quan trọng.

Nếu bạn cần, có thể yêu cầu chủ website tạo thêm **video quay màn hình** đi qua các bước trong file này để dễ theo dõi hơn.

