@echo off
echo =================================================================
echo HOSPITAL HMS - COMPLETE INSTALLATION GUIDE FOR WINDOWS
echo =================================================================
echo.
echo This script will guide you through installing everything you need.
echo.
echo PREREQUISITES CHECK:
echo =================================================================

echo Checking if PHP is installed...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP NOT FOUND in PATH
    echo.
    echo SOLUTION 1 (Recommended): Install XAMPP
    echo Download from: https://www.apachefriends.org/
    echo Install to: C:\xampp
    echo.
    echo SOLUTION 2: Install PHP manually
    echo Download from: https://windows.php.net/download/
    echo Extract to: C:\php
    echo Add C:\php to PATH environment variable
    echo.
) else (
    echo ✅ PHP is installed
    php --version | findstr "PHP"
)

echo.
echo Checking if Composer is installed...
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ COMPOSER NOT FOUND in PATH
    echo.
    echo Download Composer from: https://getcomposer.org/download/
    echo Run Composer-Setup.exe
    echo During installation:
    echo 1. Choose "Install for all users"
    echo 2. Check "Add to PATH"
    echo 3. Use PHP path: C:\xampp\php\php.exe (if using XAMPP)
    echo.
) else (
    echo ✅ Composer is installed
    composer --version | findstr "Composer"
)

echo.
echo =================================================================
echo OPTION A: USING XAMPP (Easiest)
echo =================================================================
echo.
echo 1. Download and install XAMPP from: https://www.apachefriends.org/
echo 2. Open XAMPP Control Panel
echo 3. Start Apache and MySQL services
echo 4. Copy this project folder to: C:\xampp\htdocs\hospital-hms
echo.
echo OPTION B: USING CURRENT LOCATION
echo =================================================================
echo.
echo 1. Install PHP and Composer (see above)
echo 2. Follow these commands:
echo    cd %~dp0
echo    copy .env.example .env
echo    composer install
echo    php artisan key:generate
echo    php artisan migrate --seed
echo    php artisan storage:link
echo    php artisan serve
echo.
echo =================================================================
echo QUICK TEST (Without PHP/Composer)
echo =================================================================
echo.
echo You can immediately view the interface design:
echo 1. Open this URL in your browser:
echo    file:///C:/Users/EXIT%20EXAM/Desktop/hospital-management/hospital-hms/public/
echo 2. This shows the login page UI (backend won't work)
echo.
echo =================================================================
echo TROUBLESHOOTING COMMON ERRORS
echo =================================================================
echo.
echo ERROR: "composer not recognized"
echo SOLUTION: Install Composer (see above) or use:
echo    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
echo    php composer-setup.php
echo    php composer.phar install
echo.
echo ERROR: "php not recognized"
echo SOLUTION: Install XAMPP or add PHP to PATH
echo.
echo ERROR: "Cannot find path"
echo SOLUTION: Run commands from correct directory:
echo    cd "C:\Users\EXIT EXAM\Desktop\hospital-management\hospital-hms"
echo.
echo =================================================================
echo DEMO CREDENTIALS (password: "password")
echo =================================================================
echo.
echo Admin:        admin@hospital.com
echo Doctor:       james.wilson@hospital.com  
echo Nurse:        nancy.clark@hospital.com
echo Receptionist: receptionist@hospital.com
echo Laboratory:   lab@hospital.com
echo Pharmacist:   pharmacist@hospital.com
echo Patient:      patient@hospital.com
echo.
echo =================================================================
echo NEXT STEPS AFTER INSTALLATION
echo =================================================================
echo.
echo 1. Open browser to: http://localhost:8000
echo 2. OR if using XAMPP: http://localhost/hospital-hms/public
echo 3. Login with demo credentials above
echo 4. Explore all 7 user dashboards
echo.
echo =================================================================
echo For detailed instructions, see:
echo - README.md (Features overview)
echo - INSTALLATION.md (Detailed setup)
echo - QUICK-START-GUIDE.txt (Quick reference)
echo =================================================================
pause