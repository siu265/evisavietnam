# Báo cáo Soát Bảo Mật - evisavietnam

**Ngày cập nhật:** 2026-01-28

## 1. TÓM TẮT

| Mức độ      | Số lượng | Trạng thái   |
|-------------|----------|--------------|
| Nghiêm trọng | 20+     | Đã xử lý     |
| Cảnh báo    | 4        | Cần hành động |
| Thông tin   | 3        | Hiểu biết    |

---

## 2. MALWARE ĐÃ PHÁT HIỆN VÀ XÓA

### 2.1 Backdoor PHP (RCE - Remote Code Execution)

**Mẫu:** Đoạn mã nhận payload qua POST/REQUEST (tên tham số hex-encoded như `\x73\x79m`, `\x6Bey`, `\x68old\x65\x72`...), giải mã, ghi file tạm, `include` thực thi, xóa file.

**Các file đã làm sạch:**
- `immigro-plugin/redux-framework/ReduxCore/core/required.php`
- `woocommerce/templates/emails/customer-stock-notification-verified.php`
- `woocommerce/includes/tracks/class-wc-site-tracking.php`
- `woocommerce/patterns/testimonials-single.php`
- `woocommerce/patterns/page-coming-soon-minimal-left-image.php`
- `woocommerce/includes/interfaces/class-wc-customer-data-store-interface.php`
- `woocommerce/packages/action-scheduler/classes/ActionScheduler_WPCommentCleaner.php`
- `woocommerce/assets/client/admin/admin-layout/style.asset.php`
- `contact-form-7/includes/contact-form-template.php`
- `woocommerce/includes/admin/meta-boxes/class-wc-meta-box-product-short-description.php`

**Các file độc hại đã xóa hoàn toàn:**
- `woocommerce/src/Internal/RestApi/Routes/V4/OrderNotes/default_links.php` (thay bằng stub)
- `woocommerce/assets/.../product-details-section-description/dbconnect.php`
- `elementor/.../editor-components/editor.components.asset.php` (thay bằng stub)
- `elementor/.../Util/changeProject.php`, `Profiler/Node/JINCSubscription.php`
- `pro-elements/.../export.runner.base.php` (file giả, class thật ở export-runner-base.php)
- `woocommerce/packages/email-editor/.../Tokenizer/xoopsmailer.php`
- `woocommerce/packages/email-editor/.../Sabberworm/CSS/Rule/testcourselib.php`
- `woocommerce/assets/.../product-attributes-field/articleweb.php`
- `woocommerce/vendor/opis/json-schema/.../Keywords/editinputtype.php`
- `wordfence/crypto/.../SecretStream/downloader.php`
- `wordfence/crypto/.../namespaced/Core/adr_vault.php`
- `wordfence/crypto/.../namespaced/Core/.symbol` (dropper)
- `wordfence/crypto/.../Poly1305/.comp` (payload chính)

### 2.2 Payload .comp – Tạo backdoor admin

**File:** `wordfence/.../Poly1305/.comp` (đã xóa)

**Hành vi:**
- Quét toàn ổ đĩa tìm `wp-config.php`
- Đọc cấu hình DB từ wp-config
- Tạo user WordPress: `user_login=root`, `user_email=livewire31@proton.me`, mật khẩu `AdolfHitler88.3123`
- Gán quyền Administrator

Đây chính là nguồn gốc tài khoản **root / livewire31@proton.me** trong bảng Users.

**Hành động bắt buộc:** Xóa user này trong Users hoặc database (xem mục 6).

---

## 3. CẢNH BÁO TỪ WORDFENCE (Cần xác minh)

### 3.1 Theme files "unknown" - FALSE POSITIVE

Các file theme override bị báo sai: `thankyou.php`, `order-received.php`, `footer.php` trong immidox.  
**Khuyến nghị:** Trong Wordfence chọn "Mark as Fixed".

### 3.2 CVE-2023-1463 - Elementor RCE

Cập nhật Elementor lên phiên bản mới nhất.

### 3.3 Slider Path

Gỡ plugin và thay bằng giải pháp khác.

### 3.4 WordPress core bị sửa

Tải lại WordPress core sạch từ wordpress.org và thay thế file bị sửa.

---

## 4. MÃ HỢP LỆ (Không phải malware)

- base64_decode, gzinflate trong Wordfence, Redux – dùng hợp lệ
- $_GET['file'], $_GET['code'] – có sanitize
- eval trong Twig/Elementor – code template engine chuẩn

---

## 5. FILE BẢO VỆ ĐÃ TẠO

- `wp-content/mu-plugins/protect-wp-core.php`
- `protect-core-permissions.sh`
- `define('DISALLOW_FILE_EDIT', true)` trong wp-config.php

---

## 6. HÀNH ĐỘNG CẦN LÀM NGAY

1. **Xóa tài khoản backdoor**  
   Xóa user `root` (email `livewire31@proton.me`) trong WordPress Admin → Users hoặc qua SQL:
   ```sql
   DELETE FROM img_usermeta WHERE user_id IN (SELECT ID FROM img_users WHERE user_email='livewire31@proton.me');
   DELETE FROM img_users WHERE user_email='livewire31@proton.me';
   ```
   *(Thay `img_` bằng table prefix thực tế.)*

2. **Đổi tất cả mật khẩu**  
   Mật khẩu của mọi tài khoản admin.

3. **Tải lại plugin sạch**  
   - WooCommerce  
   - Contact Form 7  
   - Wordfence  

4. **Chạy script bảo vệ**  
   `./protect-core-permissions.sh /path/to/wordpress`

5. **Bật Wordfence / Sucuri**  
   Chạy quét toàn site sau khi dọn malware.
