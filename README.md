# Jewellery Management System

A professional, feature-rich web application designed for jewellery shops to manage pledges, customers, accountants, and reporting with ease. Built with a modular PHP architecture and modern frontend technologies.

## 🚀 Key Features

- **Dual Dashboards**: Dedicated interfaces for Admins and Users.
- **Master Management**: Full CRUD operations for Accountants, Customers, and System Users.
- **Pledge Tracking**: Comprehensive system to track pledges and releases with GST support.
- **SMS Notifications**: Automated and manual SMS alerts integrated with Twilio.
- **Analytics & Visualization**: Interactive bar, pie, and line charts using Chart.js.
- **Inventory Mapping**: Integrated Leaflet.js maps for location-based tracking.
- **Advanced Reporting**: Export data to Excel/PDF and print records directly from the browser.
- **Secure Auth**: Session-based authentication and password management.

## 🛠️ Tech Stack

- **Backend**: PHP 8.x, MySQL
- **Frontend**: HTML5, CSS3, JavaScript (jQuery, Bootstrap 5)
- **Charts**: [Chart.js](https://www.chartjs.org/)
- **Maps**: [Leaflet.js](https://leafletjs.com/)
- **SMS**: [Twilio PHP SDK](https://github.com/twilio/twilio-php)
- **Dependency Management**: Composer

## 📂 Project Structure

```text
Jewellery/
├── assets/              # CSS, JS, and Images
├── auth/               # Access control and login
├── handlers/           # Backend form & AJAX processors
├── includes/           # Shared components (DB, Sidebar)
├── modules/            # core features grouped by domain
│   ├── accountants/
│   ├── customers/
│   ├── pledges/
│   ├── reports/
│   └── users/
├── admindashboard.php  # Main entry for admins
└── userdashboard.php   # Main entry for users
```

## ⚙️ Setup & Installation

1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/sathishsk6822/Jewellery_Management.git
    ```
2.  **Server Setup**: Move the folder to your web server directory (e.g., `C:/xampp/htdocs/`).
3.  **Database**:
    - Import the provided `.sql` file into your MySQL database (e.g., via phpMyAdmin).
    - Update `includes/dbconnect.php` with your database credentials.
4.  **Dependencies**:
    - Run `composer install` to set up the Twilio SDK and other libraries.
5.  **Access**: Navigate to `http://localhost/Jewellery/auth/login.php` in your browser.

## 🔒 Security Note
The Twilio credentials in `modules/sms/send_sms.php` are currently placeholders. Please update them with your own `Account SID` and `Auth Token` to enable SMS functionality.

---
*Created for efficient Jewellery management.*
