#!/bin/bash
# Script bảo vệ thư mục WordPress Core - chặn chỉnh sửa và thêm file mới
# Chạy qua SSH với quyền root hoặc user sở hữu file
# Usage: ./protect-core-permissions.sh [path-to-wordpress]

WP_ROOT="${1:-.}"
if [ ! -d "$WP_ROOT/wp-includes" ] || [ ! -d "$WP_ROOT/wp-admin" ]; then
    echo "Lỗi: Không tìm thấy thư mục WordPress. Chạy: $0 /path/to/wordpress"
    exit 1
fi

echo "=== Bảo vệ WordPress Core tại: $WP_ROOT ==="

# Đặt quyền thư mục: 755 (r-x cho others = không ghi)
# Đặt quyền file: 644 (r-- cho others = không ghi)
find "$WP_ROOT/wp-includes" -type d -exec chmod 555 {} \;
find "$WP_ROOT/wp-includes" -type f -exec chmod 444 {} \;
echo "✓ wp-includes"

find "$WP_ROOT/wp-admin" -type d -exec chmod 555 {} \;
find "$WP_ROOT/wp-admin" -type f -exec chmod 444 {} \;
echo "✓ wp-admin"

# Root - giữ 1 số file cần ghi (wp-config thường cần)
# index.php, wp-*.php không cần ghi
for f in "$WP_ROOT"/*.php; do
    [ -f "$f" ] && chmod 444 "$f"
done
echo "✓ Root PHP files"

# Nếu dùng Linux và có chattr (immutable)
if command -v chattr &> /dev/null; then
    echo ""
    echo "Chạy lệnh sau với root để khóa hoàn toàn (tùy chọn):"
    echo "  sudo find $WP_ROOT/wp-includes -type f -exec chattr +i {} \\;"
    echo "  sudo find $WP_ROOT/wp-admin -type f -exec chattr +i {} \\;"
    echo "Lưu ý: chattr +i sẽ chặn CẢ cập nhật WordPress qua admin. Chỉ dùng khi cần."
fi

echo ""
echo "Hoàn tất. Thư mục core đã được bảo vệ."
