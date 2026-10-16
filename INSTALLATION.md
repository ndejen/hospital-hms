# 🏥 Hospital Management System - Installation Guide

## ⚡ Quick Setup (XAMPP/WAMP - Windows)

### **Step 1: Environment Setup**
1. **Install XAMPP/WAMP** from official website
2. **Start Apache & MySQL** services from control panel
3. **Create database** in phpMyAdmin:
   ```sql
   CREATE DATABASE hospital_hms;
   ```

### **Step 2: Project Setup**
1. **Copy project** to `C:\xampp\htdocs\hospital-hms\`
2. **Open Command Prompt** in project folder:
   ```cmd
   cd C:\xampp\htdocs\hospital-hms
   ```

### **Step 3: Configuration**
1. **Copy .env file:**
   ```cmd
   copy .env.example .env
   ```

2. **Edit .env** with your database details:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hospital_hms
   DB_USERNAME=root
   DB_PASSWORD=    # (Leave empty for XAMPP default)
   APP_URL=http://localhost/hospital-hms/public
   ```

### **Step 4: Install Dependencies & Setup**
```cmd
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

### **Step 5: Access Application**
**URL:** http://localhost/hospital-hms/public

## 🐧 Linux/Mac Setup

### **Using Laravel Sail (Docker):**
```bash
curl -s "https://laravel.build/hospital-hms" | bash
cd hospital-hms
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

### **Traditional LAMP:**
```bash
# Install dependencies
composer install

# Set permissions
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Setup
cp .env.example .env
php artisan key:generate

# Update .env with your MySQL credentials
php artisan migrate --seed
php artisan storage:link

# Serve
php artisan serve --host=0.0.0.0 --port=8000
```

## 🔧 Common Issues & Solutions

### **Issue: "No application encryption key has been specified"**
**Solution:**
```bash
php artisan key:generate
```

### **Issue: "SQLSTATE[HY000] [1045] Access denied"**
**Solution:**
1. Check MySQL credentials in `.env`
2. Verify database user has proper permissions
3. For XAMPP: `DB_USERNAME=root`, `DB_PASSWORD=` (empty)

### **Issue: "Class not found"**
**Solution:**
```bash
composer dump-autoload
```

### **Issue: "Permission denied" on storage**
**Solution:**
```bash
# Windows (Run as Administrator in project folder):
icacls storage /grant "Users:(OI)(CI)F"
icacls bootstrap/cache /grant "Users:(OI)(CI)F"

# Linux/Mac:
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### **Issue: "The page isn't redirecting properly"**
**Solution:**
1. Clear browser cache
2. Clear Laravel cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

## 🗃️ Database Management

### **Reset Database (Fresh install):**
```bash
php artisan migrate:fresh --seed
```

### **View Database:**
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select `hospital_hms` database

### **Import/Export:**
**Export:**
```bash
php artisan db:export filename.sql
```

**Import:**
```bash
mysql -u root -p hospital_hms < filename.sql
```

## 🌐 Virtual Host Setup (Optional)

### **Apache Virtual Host:**
Create `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/hospital-hms/public"
    ServerName hms.local
    <Directory "C:/xampp/htdocs/hospital-hms/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Edit `C:\Windows\System32\drivers\etc\hosts`:**
```
127.0.0.1   hms.local
```

## 🔍 Testing & Verification

### **Verify Installation:**
1. Open: http://localhost/hospital-hms/public
2. Login with demo credentials
3. Check all modules are working

### **Test User Roles:**
1. **Admin**: Full access to all modules
2. **Doctor**: Access to medical modules only
3. **Nurse**: Access to patient care modules
4. **Receptionist**: Front desk modules only

## 📊 Post-Installation

### **Check System Requirements:**
```bash
php artisan about
```

### **Set Application URL:**
Edit `.env`:
```
APP_URL=http://your-domain.com
```

### **Enable Debug Mode (Development):**
In `.env`:
```
APP_DEBUG=true
```

## 🚀 Production Deployment

### **Optimize for Production:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **Disable Debug Mode:**
In `.env`:
```
APP_DEBUG=false
APP_ENV=production
```

### **Security:**
1. Generate application key
2. Set strong database passwords
3. Configure HTTPS
4. Set proper file permissions

## 🆘 Troubleshooting

### **Still having issues?**
1. Check Apache/MySQL logs
2. Verify PHP version >= 8.2
3. Ensure all extensions are enabled:
   - OpenSSL
   - PDO
   - Mbstring
   - Tokenizer
   - XML
   - Ctype
   - JSON

4. **Fresh Install Process:**
   ```bash
   # Complete reinstall
   rm -rf vendor node_modules storage/framework/sessions/*
   composer install
   php artisan migrate:fresh --seed
   php artisan cache:clear
   ```

## 📞 Support
- **Documentation**: Refer to README.md
- **Issues**: Check error logs in `storage/logs/laravel.log`
- **Community**: Laravel Discord/Slack channels

---

✅ **Installation Complete!** You can now access the Hospital Management System.

**Default Login:** admin@hospital.com / password
**Access URL:** http://localhost/hospital-hms/public
