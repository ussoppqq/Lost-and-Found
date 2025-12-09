# Email Verification System Setup Guide

## Overview
Sistem ini menggunakan Gmail SMTP untuk mengirim kode verifikasi email saat registrasi dan kode pemulihan password saat forgot password.

## Features
1. **Email Verification pada Register**: User akan menerima kode 6 digit via email untuk memverifikasi akun
2. **Password Reset via Email**: User akan menerima kode pemulihan 6 digit via email untuk reset password

## Setup Gmail SMTP

### Step 1: Buat App Password di Gmail

1. Login ke akun Gmail Anda
2. Buka [Google Account Security](https://myaccount.google.com/security)
3. Aktifkan "2-Step Verification" jika belum aktif
4. Setelah 2-Step Verification aktif, buka kembali halaman Security
5. Cari dan klik "App passwords" atau "App Passwords"
6. Pilih app: **Mail**
7. Pilih device: **Windows Computer** atau **Other**
8. Klik **Generate**
9. Copy App Password yang ditampilkan (16 karakter tanpa spasi)

### Step 2: Update .env File

Buka file `.env` dan update konfigurasi email:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com          # Ganti dengan email Gmail Anda
MAIL_PASSWORD=your-app-password              # Ganti dengan App Password dari Step 1
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"    # Ganti dengan email Gmail Anda
MAIL_FROM_NAME="${APP_NAME}"
```

**IMPORTANT**:
- `MAIL_PASSWORD` harus menggunakan **App Password** (16 karakter), BUKAN password Gmail biasa
- Pastikan tidak ada spasi dalam App Password

### Step 3: Clear Config Cache

Setelah update .env, jalankan:

```bash
php artisan config:clear
```

## Database Changes

Migration sudah dibuat dan dijalankan dengan field berikut di table `users`:
- `email_verification_code` - Kode verifikasi 6 digit
- `email_verification_code_expires_at` - Waktu expire kode (10 menit)
- `email_verified_at` - Timestamp saat email ter-verifikasi

## How It Works

### Registration Flow:
1. User mengisi form register dengan email, phone, password, dll
2. Sistem generate kode verifikasi 6 digit
3. Kode dikirim ke email user
4. User memasukkan kode untuk verifikasi
5. Jika kode benar, akun ter-verifikasi

### Forgot Password Flow:
1. User klik "Forgot Password" dan masukkan email
2. Sistem generate kode recovery 6 digit
3. Kode dikirim ke email user
4. User memasukkan kode recovery
5. Jika kode benar, user bisa reset password

## Troubleshooting

### Email tidak terkirim?

1. **Check Gmail Credentials**
   - Pastikan MAIL_USERNAME benar
   - Pastikan MAIL_PASSWORD menggunakan App Password, bukan password biasa

2. **Check 2-Step Verification**
   - Gmail requires 2-Step Verification untuk App Password
   - Aktifkan di [Google Account Security](https://myaccount.google.com/security)

3. **Check Port & Encryption**
   - Port 587 dengan TLS (recommended)
   - Atau Port 465 dengan SSL

4. **Check Logs**
   ```bash
   php artisan log:tail
   ```

5. **Test Email Manually**
   ```bash
   php artisan tinker
   Mail::raw('Test email', function ($message) {
       $message->to('test@example.com')->subject('Test');
   });
   ```

### Kode sudah expired?

- Kode valid selama 10 menit
- User harus request kode baru jika sudah expired

## Security Notes

1. **App Password** harus disimpan dengan aman di file `.env`
2. Jangan commit file `.env` ke git
3. Kode verifikasi akan expired dalam 10 menit
4. Setiap kode hanya bisa digunakan sekali

## Example Email Templates

Email templates tersimpan di:
- `resources/views/emails/email-verification.blade.php` - Template untuk email verification
- `resources/views/emails/password-reset.blade.php` - Template untuk password reset

## Implementation Status

✅ **COMPLETED** - Email verification system has been fully implemented:

### Register Flow (RegisterExtra Component):
1. ✅ User enters phone number and receives OTP via WhatsApp
2. ✅ User enters email address (optional)
3. ✅ Click "Verifikasi Email" button to receive 6-digit code via email
4. ✅ Enter email verification code
5. ✅ Complete registration with verified email

### Forgot Password Flow (ForgotPassword Component):
1. ✅ User enters email address
2. ✅ System sends 6-digit recovery code to email
3. ✅ User enters recovery code
4. ✅ User sets new password
5. ✅ Redirect to login page

### Features Implemented:
- ✅ Email verification during registration (optional)
- ✅ Password reset via email recovery code
- ✅ 10-minute expiry for all codes
- ✅ Professional email templates with branding
- ✅ Multi-step UI with clear progress indicators
- ✅ Comprehensive error handling and validation
- ✅ Session-based code storage for security

## Support

Jika ada masalah atau pertanyaan, silakan hubungi developer team.
