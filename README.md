
# MBC Student Monitoring System 🎓📊

A web-based attendance tracking and student monitoring system designed to help instructors automate their daily attendance using barcode technology, replacing the traditional manual registry books.

## 🚀 Features & User Roles

The system features a secure, multi-level access architecture tailored for three distinct user types:

### 🛡️ Admin Portal
* **System Management:** Full control over the core database.
* **Record Management:** Add, update, and manage student and instructor profiles.
* **Academic Setup:** Create and manage courses and subjects.
* **Faculty Assignment:** Allocate specific instructors to their respective classes and subjects.

### 👨‍🏫 Instructor Dashboard
* **Class Roster:** View classes specifically assigned to them by the admin.
* **Automated Tracking:** Launch the barcode scanning module to record daily attendance instantly.
* **Attendance Management:** View and manage attendance records for their assigned subjects, eliminating manual registry books.

### 🎓 Student Portal
* **Personalized Dashboard:** A secure login for individual students.
* **Attendance Tracking:** View personal attendance history and status across all enrolled subjects to easily keep track of their own standing.

## 🛠️ Built With

* **Backend Framework:** Laravel
* **Frontend:** Tailwind CSS, HTML, JavaScript
* **Database:** MySQL
* **Barcode Library:** [e.g., QuaggaJS, ZXing, or physical scanner integration]

## 🗄️ Database Structure

This project uses Laravel migrations to automatically build the MySQL database schema. 

* **`users`:** Stores credentials and role assignments (Admin, Instructor, Student) for secure system access.
* **`courses` & `subjects`:** Manages the academic catalog and tracks which faculty members are assigned to which classes.
* **`attendance_records`:** The central transaction table that records every successful barcode scan, linking the student ID, the assigned class, and the exact timestamp.

## 📋 Prerequisites

Before you begin, ensure you have the following installed on your machine:
* PHP and Composer
* Node.js and npm
* MySQL Server
* A physical barcode scanner (or webcam if using a browser-based scanner).

## ⚙️ Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/glenn-coder/mbc-student-monitoring-system.git](https://github.com/glenn-coder/mbc-student-monitoring-system.git)
