# CSCI4410-Final-Project
Web-Based Attendance Management System

## Project Requirements
Your final project must incorporate all the following elements:

- ★ Front-End Development: HTML, CSS, JavaScript, React
- ★ Back-End Development: Server-side logic using PHP
- ★ Database: MySQL for storing user data.
- ★ Authentication: Login/Logout with JWT.
- ★ Final Report: (Maximum 5 pages, Minimum 2 pages)
- ☆ Project Description
- ☆ Project Design Diagram Graph
- ☆ Team Member Task Distribution
- ★ Final Presentation
- ☆ Each team will have 10 minutes to demonstrate their project.
- ★ Unique Feature Requirement:
- ☆ Your website must include at least one feature that was not covered in class.

# Web-Based Attendance Management System

## Overview
This project allows teachers to mark and track student attendance and generate reports, while students can view their attendance history.

## Technologies Used
- **Frontend:** React (HTML, CSS, JavaScript)
- **Backend:** PHP
- **Database:** MySQL
- **Authentication:** JWT (JSON Web Tokens)

## Features
- Login/Logout with JWT
- Role-based access for teachers and students
- Teachers can mark attendance and view reports
- Students can view their attendance history

## Setup Instructions
1. Import the SQL schema into your MySQL database.
2. Update your database credentials in `db.php`.
3. Install PHP dependencies with Composer (Firebase JWT).
4. Start a local server for PHP backend (e.g., XAMPP or built-in server).
5. Run the React app (`npm install && npm start`).
6. Ensure CORS is handled in PHP if frontend and backend are on different ports.

## Folder Structure
```
project/
├── backend/
│   ├── db.php
│   ├── login.php
│   ├── mark_attendance.php
│   ├── get_attendance.php
│   └── verify_token.php
├── frontend/
│   ├── public/
│   ├── src/
│   │   ├── components/
│   │   │   ├── Login.jsx
│   │   │   ├── Dashboard.jsx
│   │   │   └── Attendance.jsx
│   │   └── App.jsx
├── database.sql
└── README.md
