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

