# Hướng dẫn cấu hình PayPal để giải quyết lỗi 403

## Vấn đề hiện tại
- Ứng dụng đang bị lỗi 403 khi chuyển sang live environment
- Logs cho thấy vẫn đang dùng sandbox URL

## Cách kiểm tra environment hiện tại
Từ logs của bạn, tôi thấy:
```
"base_url":"https://api-m.sandbox.paypal.com"
"client_id":"AbYD4FO1hvvp0mIDedo5fDPs1SXcrpMcjYQnAY-oyTL8EOvJ_Om8gIOT0AY-ONDI-84381DGPGRkI94Y"
```

## Các bước để chuyển sang live environment:

### 1. Cập nhật file .env
```bash
# Thay đổi từ sandbox sang live
PAYPAL_BASE_URL=https://api-m.paypal.com
PAYPAL_CLIENT_ID=YOUR_LIVE_CLIENT_ID
PAYPAL_CLIENT_SECRET=YOUR_LIVE_CLIENT_SECRET
PAYPAL_RETURN_URL=https://topwriter.co/paypal-success
PAYPAL_CANCEL_URL=https://topwriter.co/paypal-cancel
```

### 2. Lấy live credentials từ PayPal Developer
1. Đăng nhập vào https://developer.paypal.com
2. Chọn ứng dụng của bạn
3. Chuyển từ "Sandbox" sang "Live" 
4. Copy Client ID và Client Secret từ phần Live

### 3. Đảm bảo ứng dụng được approve
- Ứng dụng PayPal phải được PayPal review và approve trước khi có thể nhận thanh toán live
- Kiểm tra status trong PayPal Developer Console

### 4. Cập nhật Webhook URLs
Trong PayPal Developer Console, cập nhật:
- Return URL: `https://topwriter.co/paypal-success`
- Cancel URL: `https://topwriter.co/paypal-cancel`
- Webhook URL (nếu có): `https://topwriter.co/paypal/webhook`

### 5. Clear cache sau khi cập nhật
```bash
php artisan config:cache
php artisan route:cache
```

## Các lỗi 403 phổ biến và cách khắc phục:

### A. Dùng sandbox credentials với live URL
**Triệu chứng**: Client ID bắt đầu với "AbYD..." nhưng base_url là live
**Giải pháp**: Đảm bảo dùng live credentials với live URL

### B. Ứng dụng chưa được approve
**Triệu chứng**: Lỗi 403 với live credentials
**Giải pháp**: Submit ứng dụng để PayPal review và approve

### C. IP không được whitelist
**Triệu chứng**: Lỗi 403 từ server IP cụ thể
**Giải pháp**: Thêm server IP vào whitelist trong PayPal Developer Console

### D. Permissions không đủ
**Triệu chứng**: 403 với message về permissions
**Giải pháp**: Kiểm tra API permissions trong PayPal app settings

## Testing sau khi cấu hình
1. Check logs xem environment có đúng không
2. Test với số tiền nhỏ trước (0.10 USD)
3. Monitor logs để đảm bảo không còn lỗi 403

## Rollback plan
Nếu gặp vấn đề, có thể rollback về sandbox:
```bash
PAYPAL_BASE_URL=https://api-m.sandbox.paypal.com
PAYPAL_CLIENT_ID=YOUR_SANDBOX_CLIENT_ID
PAYPAL_CLIENT_SECRET=YOUR_SANDBOX_CLIENT_SECRET
``` 