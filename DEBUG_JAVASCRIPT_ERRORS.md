# راهنمای عیب‌یابی خطاهای JavaScript

## مراحل عیب‌یابی

### 1. بررسی اولیه
ابتدا این فایل‌ها را اجرا کنید:

```
http://localhost/ShadinoV5/test_api.php
http://localhost/ShadinoV5/test_ratings_api.php
http://localhost/ShadinoV5/test_js_api.html
```

### 2. بررسی Console مرورگر
1. F12 را فشار دهید
2. به تب Console بروید
3. خطاهای قرمز را بررسی کنید

### 3. بررسی Network Tab
1. F12 را فشار دهید
2. به تب Network بروید
3. درخواست‌های API را بررسی کنید
4. وضعیت HTTP (200, 404, 500) را چک کنید

## مشکلات رایج و راه‌حل

### مشکل 1: CORS Error
```
Access to fetch at 'api/ratings.php' from origin 'http://localhost' has been blocked by CORS policy
```

**راه‌حل:**
- بررسی CORS headers در `includes/config.php`
- اطمینان از صحیح بودن مسیر API

### مشکل 2: 404 Not Found
```
Failed to load resource: the server responded with a status of 404
```

**راه‌حل:**
- بررسی وجود فایل `api/ratings.php`
- بررسی مسیر فایل
- بررسی تنظیمات سرور

### مشکل 3: 500 Internal Server Error
```
Failed to load resource: the server responded with a status of 500
```

**راه‌حل:**
- بررسی لاگ‌های خطا در `logs/error.log`
- بررسی syntax فایل PHP
- بررسی اتصال دیتابیس

### مشکل 4: JSON Parse Error
```
Unexpected token < in JSON at position 0
```

**راه‌حل:**
- بررسی خروجی API (ممکن است HTML باشد نه JSON)
- بررسی خطاهای PHP
- بررسی Content-Type header

### مشکل 5: Session Error
```
User not authenticated
```

**راه‌حل:**
- بررسی ورود کاربر
- بررسی session
- بررسی `require_auth()` function

## تست‌های پیشنهادی

### تست 1: بررسی فایل API
```bash
php -l api/ratings.php
```

### تست 2: بررسی دیتابیس
```sql
SHOW TABLES LIKE 'user_ratings';
SHOW TABLES LIKE 'user_stats';
```

### تست 3: بررسی دسترسی
```bash
curl -X GET "http://localhost/ShadinoV5/api/ratings.php?user_id=1"
```

### تست 4: بررسی POST
```bash
curl -X POST "http://localhost/ShadinoV5/api/ratings.php" \
  -H "Content-Type: application/json" \
  -d '{"user_id":1,"rating":5,"comment":"test"}'
```

## کدهای خطای رایج

| کد | معنی | راه‌حل |
|----|------|--------|
| 400 | Bad Request | بررسی پارامترهای ورودی |
| 401 | Unauthorized | بررسی احراز هویت |
| 403 | Forbidden | بررسی دسترسی‌ها |
| 404 | Not Found | بررسی مسیر فایل |
| 500 | Internal Server Error | بررسی کد PHP |

## لاگ‌های مفید

### JavaScript Console
```javascript
console.log('Response:', response);
console.error('Error:', error);
```

### PHP Error Log
```php
error_log('Debug: ' . $message);
```

### Network Tab
- Status Code
- Response Headers
- Request Headers
- Response Body

## تماس با پشتیبانی

در صورت بروز مشکل:
1. لاگ‌های خطا را ذخیره کنید
2. تصاویر Console و Network را بگیرید
3. مراحل انجام شده را یادداشت کنید
