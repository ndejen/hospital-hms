@echo off
echo ============================================
echo HOSPITAL HMS - WINDOWS SETUP SCRIPT
echo ============================================
echo.

echo Step 1: Checking if XAMPP is installed...
where xampp_start.exe >nul 2>&1
if %errorlevel% neq 0 (
    echo XAMPP not found in PATH.
    echo Please install XAMPP from: https://www.apachefriends.org/
    echo Or copy project to: C:\xampp\htdocs\hospital-hms\
    pause
    exit /b 1
)

echo Step 2: Starting XAMPP services...
echo Please ensure XAMPP Control Panel is running.
echo Start Apache and MySQL from XAMPP Control Panel.
echo.

echo Step 3: Setting up environment file...
if exist ".env" (
    echo .env already exists. Skipping...
) else (
    copy ".env.example" ".env"
    echo .env file created from .env.example
)

echo Step 4: Installing PHP dependencies...
echo Note: You need Composer installed for this step.
echo Download Composer from: https://getcomposer.org/download/
echo After installing Composer, run: composer install
echo.

echo Step 5: Database setup instructions:
echo 1. Open phpMyAdmin: http://localhost/phpmyadmin
echo 2. Create database: CREATE DATABASE hospital_hms;
echo 3. Update .env file with your database credentials
echo.

echo Step 6: Laravel commands to run AFTER setup:
echo 1. php artisan key:generate
echo 2. php artisan migrate --seed
echo 3. php artisan storage:link
echo 4. php artisan serve
echo.

echo Step 7: Access the application:
echo URL: http://localhost:8000
echo OR copy to XAMPP: C:\xampp\htdocs\hospital-hms\
echo Then access: http://localhost/hospital-hms/public
echo.

echo ============================================
echo DEMO LOGIN CREDENTIALS:
echo ============================================
echo Admin: admin@hospital.com / password
echo Doctor: james.wilson@hospital.com / password
echo Nurse: nancy.clark@hospital.com / password
echo Receptionist: receptionist@hospital.com / password
echo Laboratory: lab@hospital.com / password
echo Pharmacist: pharmacist@hospital.com / password
echo Patient: patient@hospital.com / password
echo ============================================
echo.

echo For detailed instructions, see README.md and INSTALLATION.md
pause
