# Hospital Management System (HMS) - Laravel 12

A comprehensive multi-role hospital management system built with Laravel 12, Bootstrap 5, and MySQL.

## 📋 Features

### **User Roles:**
- **👨‍💼 Administrator**: Full system control, user management, reports, settings
- **👨‍⚕️ Doctor**: Patient management, appointments, prescriptions, lab requests
- **👩‍⚕️ Nurse**: Patient vital signs, admissions, nursing notes
- **🧑‍💻 Receptionist**: Patient registration, appointment booking, billing
- **🧪 Laboratory Staff**: Test requests, results recording, reports
- **💊 Pharmacist**: Medicine inventory, prescription dispensing, stock management
- **👤 Patient**: View appointments, medical history, profile management

### **Core Modules:**
- Patient Management & Registration
- Doctor & Department Management
- Appointment Scheduling
- Medical Records & Diagnosis
- Prescription Management
- Laboratory Test Management
- Pharmacy & Inventory Management
- Billing & Invoicing
- Admission & Ward Management
- Reports & Analytics Dashboard

## 🚀 Quick Start

### **Prerequisites:**
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer
- XAMPP/WAMP/Laragon (for Windows) or LAMP stack

### **Installation:**

1. **Clone/Copy the project** to your web server directory:
   ```
   C:\xampp\htdocs\hospital-hms\
   ```

2. **Create MySQL Database:**
   ```sql
   CREATE DATABASE hospital_hms;
   ```

3. **Configure Environment:**
   - Copy `.env.example` to `.env`
   - Update database credentials:
   ```
   DB_DATABASE=hospital_hms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Install Dependencies & Setup:**
   ```bash
   composer install
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Create Storage Symlink:**
   ```bash
   php artisan storage:link
   ```

6. **Set Permissions:**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

7. **Start Server:**
   ```bash
   php artisan serve
   ```
   Visit: http://localhost:8000

## 👥 Demo Login Credentials

**Password for all accounts: `password`**

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@hospital.com` | password |
| Doctor | `james.wilson@hospital.com` | password |
| Nurse | `nancy.clark@hospital.com` | password |
| Receptionist | `receptionist@hospital.com` | password |
| Laboratory | `lab@hospital.com` | password |
| Pharmacist | `pharmacist@hospital.com` | password |
| Patient | `patient@hospital.com` | password |

## 📁 Project Structure

```
hospital-hms/
├── app/
│   ├── Http/Controllers/    # Role-based controllers
│   ├── Models/              # Eloquent models
│   ├── Middleware/          # Role middleware
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Faker data seeders
├── resources/
│   ├── views/              # Blade templates
│   │   ├── admin/          # Admin views
│   │   ├── doctor/         # Doctor views
│   │   ├── nurse/          # Nurse views
│   │   └── ...
├── public/                  # Public assets
├── routes/                  # Web routes
└── config/                  # Configuration
```

## 🗄️ Database Schema

Key Tables:
- `users` - Authentication & roles
- `patients` - Patient information
- `doctors` - Doctor profiles
- `appointments` - Appointment scheduling
- `medical_records` - Medical history
- `prescriptions` - Prescriptions
- `lab_requests` - Laboratory requests
- `medicines` - Medicine inventory
- `invoices` - Billing
- `admissions` - Patient admissions

## 🎨 Tech Stack

- **Backend**: Laravel 12 (PHP Framework)
- **Frontend**: Bootstrap 5, Chart.js, Font Awesome
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Authentication
- **Charting**: Chart.js for data visualization
- **Icons**: Font Awesome 6
- **Styling**: Custom CSS with Bootstrap 5

## 🔧 Development

### **Run Seeder to Populate Data:**
```bash
php artisan db:seed
```

### **Create New Migration:**
```bash
php artisan make:migration create_table_name
```

### **Run Migrations:**
```bash
php artisan migrate
php artisan migrate:refresh --seed
```

## 📊 Features in Detail

### **Administrator Dashboard:**
- Real-time statistics and charts
- User management across all roles
- Financial reports and analytics
- System configuration
- Audit logs and monitoring

### **Doctor Portal:**
- Patient appointment management
- Electronic medical records
- Digital prescription writing
- Laboratory test requests
- Diagnosis and treatment plans

### **Nurse Station:**
- Patient vital signs recording
- Admission and discharge management
- Nursing notes and observations
- Bed and ward management
- Medication administration

### **Reception Desk:**
- Patient registration and check-in
- Appointment booking
- Billing and invoice generation
- Patient search and lookup
- Report generation

### **Laboratory Module:**
- Test request management
- Result recording and verification
- Test reports and printouts
- Test statistics and analysis

### **Pharmacy Module:**
- Medicine inventory management
- Prescription dispensing
- Stock alerts and reordering
- Expiry date tracking
- Supplier management

## 🛡️ Security Features

- Role-based access control (RBAC)
- Password hashing with bcrypt
- CSRF protection
- XSS prevention
- SQL injection protection
- Session management
- Secure file uploads

## 📈 Reporting & Analytics

- Patient statistics and demographics
- Financial reports (revenue, expenses)
- Appointment analytics
- Laboratory test statistics
- Medicine usage reports
- Export reports to PDF/Excel

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For issues, feature requests, or questions:
- Create an issue in the repository
- Email: support@hospital-hms.com

---

**Version**: 1.0.0  
**Last Updated**: October 2026  
**Author**: Hospital HMS Development Team
