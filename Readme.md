# Smart Attend

A **Web-Based Attendance Management System** that lets teachers efficiently mark student attendance, track participation trends, and generate reports. Students can view their own attendance history and track compliance.

---

## Table of Contents

1. [Features](#features)  
2. [Tech Stack](#tech-stack)  
3. [Prerequisites](#prerequisites)  
4. [Project Structure](#project-structure)  
5. [Backend Setup (XAMPP)](#backend-setup-xampp)  
6. [Frontend Setup](#frontend-setup)  
7. [Running the App](#running-the-app)  
8. [Usage](#usage)  
9. [JWT Secret & Configuration](#jwt-secret--configuration)  
10. [License](#license)  

---

## Features

- **Authentication**: JWT-based login & logout  
- **Teacher Dashboard**  
  - Create, view & manage **Classes**  
  - Enroll / remove **Students** by email  
  - Add **Sessions** (one per date)  
  - Mark / update attendance (Present / Absent)  
  - Export **CSV** reports (class-wise & student-wise)  
- **Student Dashboard**  
  - View personal **attendance history**  
  - Track **attendance percentage** per class  

---

## Tech Stack

- **Front-End**: React, React Router, CSS (theming via CSS variables & context)  
- **Back-End**: PHP (+ Firebase JWT), MySQL  
- **Tools**: XAMPP (Apache & MySQL), Composer, Create-React-App  

---

## Prerequisites

- **XAMPP** installed & running (Apache + MySQL)  
- **Node.js** & **npm** (v14+)  
- **Composer** (for PHP dependencies)  

---

## Project Structure

smart_attend/ ← XAMPP htdocs folder (PHP API)
├── Database.sql ← SQL schema to import
├── add_class.php
├── add_session.php
├── add_student.php
├── attendance_recent.php
├── attendance_summary.php
├── class_report.php
├── db.php
├── get_classes.php
├── get_enrollments.php
├── get_sessions.php
├── get_session_detail.php
├── get_attendance.php
├── login.php
├── register.php
├── student_attendance.php
├── student_summary.php
├── verify_token.php
└── vendor/ ← Composer dependencies (Firebase JWT)

smart_attend_front/ ← React front-end
├── public/
│ └── index.html
├── src/
│ ├── components/ ← All React components
│ ├── ThemeContext.jsx
│ ├── App.jsx
│ ├── index.js
│ └── index.css, App.css
├── package.json
└── …

Readme.md ← This file

---

## Backend Setup (XAMPP)

1. **Start Apache & MySQL**  
   Open XAMPP control panel and start **Apache** + **MySQL**.

2. **Import Database Schema**  
   - Open `http://localhost/phpmyadmin`  
   - Select “Import”  
   - Choose **Database.sql** (included in the `smart_attend/` folder)  
   - Click **Go**  
   
   This will create the `smart_attend` database and all required tables.

3. **Copy Backend Files**  
   Place the entire `smart_attend/` folder into your XAMPP `htdocs/` directory.

4. **Install Composer Dependencies**  
   In terminal:
   ```bash
   cd /path/to/xampp/htdocs/smart_attend
   composer install
5. Configure JWT Secret
   In login.php, register.php, and verify_token.php replace 'your_secret_key' with a secure, random string.

6. Test API
   Use Postman or your browser to verify endpoints:
   POST http://localhost/smart_attend/login.php
   POST http://localhost/smart_attend/register.php

7. Frontend Setup
   1. Install Dependencies:
      
   cd smart_attend_front
   npm install
   
   2. Start Development Server
    
  npm start
  
  Opens at http://localhost:3000/.


8. Usage
   1. Teacher

      Dashboard → Add Class → Click class →

      Add Students by email

      Add Session (choose date)

      Take Attendance (toggle Present/Absent, Save)

      Download CSV reports

   2. Student

      Dashboard → View Summary (% present/absent per class)

      History → Full session-by-session attendance
