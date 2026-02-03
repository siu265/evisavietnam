# Báo cáo Soát Bảo Mật - evisavietnam

**Ngày:** 2026-01-28

## 1. TÓM TẮT

| Mức độ    | Số lượng | Trạng thái   |
|-----------|----------|--------------|
| Nghiêm trọng | 1      | Đã xử lý     |
| Cảnh báo  | 4        | Cần hành động |
| Thông tin | 3        | Hiểu biết    |

---

## 2. MALWARE ĐÃ PHÁT HIỆN VÀ XÓA

### 2.1 Backdoor trong Redux Framework (ĐÃ XÓA)

**File:** `wp-content/plugins/immigro-plugin/redux-framework/ReduxCore/core/required.php`

**Mô tả:** Đoạn mã độc được chèn vào đầu file, sử dụng tham số POST `symbol` (mã hóa `sym\x62\x6Fl`):
- Nhận payload qua POST
- Giải mã và ghi ra file tạm
- Thực thi qua `include`, sau đó xóa file
- Cho phép thực thi mã tùy ý từ xa (RCE)

**Hành động đã thực hiện:** Đã xóa toàn bộ mã độc, giữ lại nội dung Redux hợp lệ.

---

## 3. CẢNH BÁO TỪ WORDFENCE (Cần xác minh)

### 3.1 Theme files "unknown" - FALSE POSITIVE

Các file sau bị Wordfence đánh dấu "unknown to WordPress" nhưng **là file hợp lệ của theme override**:
- `wp-content/themes/immidox/woocommerce/checkout/thankyou.php`
- `wp-content/themes/immidox/woocommerce/checkout/order-received.php`
- `wp-content/themes/immidox/footer.php`

Wordfence so sánh với checksum WordPress core; theme override không nằm trong core nên bị báo sai.

**Khuyến nghị:** Trong Wordfence, chọn "Mark as Fixed" cho các file trên.

### 3.2 CVE-2023-1463 - Elementor RCE

**File:** `banner_with_left_text.php` (Immigro Elementor widget)

Lỗ hổng nằm trong **Elementor core**, không phải widget tùy chỉnh. Cần cập nhật Elementor lên phiên bản đã vá.

**Khuyến nghị:** Cập nhật plugin Elementor lên phiên bản mới nhất.

### 3.3 Slider Path - Plugin cũ, lỗ hổng

**Khuyến nghị:** Gỡ plugin Slider Path và thay bằng giải pháp khác (slider native của theme hoặc plugin được bảo trì).

### 3.4 WordPress core bị sửa - class-wp-rest-posts-controller.php

**Khuyến nghị:** Tải lại WordPress core sạch, thay thế file `wp-includes/rest-api/endpoints/class-wp-rest-posts-controller.php` bằng bản gốc từ wordpress.org.

---

## 4. MÃ HỢP LỆ (Không phải malware)

- **base64_decode, gzinflate:** Có trong Wordfence, Redux, các plugin chuẩn – dùng cho mục đích hợp lệ (mã hóa, nén).
- **$_GET['file'], $_GET['code']:** Có trong Wordfence (file viewer), Elementor (OAuth), wp-admin – đều có kiểm tra và sanitize.
- **$_REQUEST:** Sử dụng chuẩn trong Merlin, TGM, Redux.

---

## 5. FILE BẢO VỆ ĐÃ TẠO

1. **`wp-content/mu-plugins/protect-wp-core.php`**  
   - Tạo `.htaccess` bảo vệ trong wp-includes, wp-admin  
   - Chặn chỉnh sửa file core qua Theme/Plugin Editor  
   - Cảnh báo khi thư mục core có quyền ghi  

2. **`protect-core-permissions.sh`**  
   - Script chạy qua SSH để đặt chmod 555/444 cho thư mục/file core  
   - Dùng: `./protect-core-permissions.sh /path/to/wordpress`

---

## 6. KHUYẾN NGHỊ BỔ SUNG

1. **wp-config.php:** Thêm `define('DISALLOW_FILE_EDIT', true);` để tắt Theme/Plugin Editor.
2. **Quyền file:** Chạy `protect-core-permissions.sh` trên server production.
3. **Cập nhật:** Nâng cấp WordPress, Elementor và các plugin lên phiên bản mới nhất.
4. **Backup:** Sao lưu định kỳ và kiểm tra file core thường xuyên.
