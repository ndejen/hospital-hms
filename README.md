# Hospital Management System (HMS)

A multi-role hospital management system built with Laravel 12, Bootstrap 5, and MySQL.

## Features

- **Administrator:** User management, reports, settings, and system oversight.
- **Doctor:** Patient records, appointments, prescriptions, and laboratory requests.
- **Nurse:** Vital signs, admissions, nursing notes, and bed management.
- **Receptionist:** Patient registration, appointments, and billing.
- **Laboratory staff:** Test requests, results, and reports.
- **Pharmacist:** Medicine inventory and prescription dispensing.
- **Patient:** Appointments, medical history, and profile management.

### Core modules

- Patient registration and management
- Doctors and departments
- Appointment scheduling
- Medical records and diagnosis
- Prescriptions
- Laboratory tests and results
- Pharmacy and inventory
- Billing and invoices
- Admissions, wards, and beds
- Reports and analytics

## Requirements

- PHP 8.2 or higher
- MySQL or MariaDB
- Composer
- XAMPP, WAMP, or another compatible local development environment

## Installation on Windows

1. Place the project in `C:\xampp\htdocs\hospital-hms`.
2. Create a MySQL database named `hospital_hms` using phpMyAdmin.
3. Copy `.env.example` to `.env`.
4. Configure the database settings in `.env`:

   ```dotenv
   DB_DATABASE=hospital_hms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Adjust the username and password to match your local database configuration.

5. Open PowerShell in the project directory and run:

   ```powershell
   composer install
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   php artisan serve
   ```

6. Open `http://127.0.0.1:8000` in your browser.

Run migrations and seeders only after confirming your database configuration. Do not run destructive database commands against a database containing important data without a backup.

## Database

The system may include tables for:

- Users and roles
- Patients and doctors
- Departments and appointments
- Medical records and prescriptions
- Laboratory requests and results
- Medicines and inventory
- Invoices and admissions

The actual tables depend on the migrations included in the project.

## Technology stack

- **Backend:** Laravel 12 and PHP
- **Frontend:** Blade templates, Bootstrap 5, and custom CSS
- **Database:** MySQL or MariaDB
- **Visualization:** Chart.js, if configured
- **Version control:** Git and GitHub

## Development commands

Run database migrations:

```powershell
php artisan migrate
```

Create a migration:

```powershell
php artisan make:migration create_table_name
```

Run seeders:

```powershell
php artisan db:seed
```

## Security

- Keep `.env` and all credentials out of Git.
- Use Laravel's password hashing and authentication facilities.
- Apply authorization checks to role-specific routes and actions.
- Validate inputs and protect sensitive patient information.
- Back up the database and restrict access to production data.

## Contributing

1. Create a feature branch.
2. Make and test your changes.
3. Commit your changes.
4. Push the branch to GitHub.
5. Open a pull request.

## License

Add the appropriate license after deciding how this project may be used and distributed.

**Project:** Hospital Management System  
**Version:** 1.0.0
