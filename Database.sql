-- Create the database
CREATE DATABASE IF NOT EXISTS smart_attend;
USE smart_attend;

-- Users: all accounts (teachers, students, admins)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)                NOT NULL,
  email VARCHAR(150)               NOT NULL UNIQUE,
  password_hash VARCHAR(255)       NOT NULL,
  role ENUM('teacher','student','admin') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Classes: each course is owned by one teacher
CREATE TABLE classes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)    NOT NULL,
  teacher_id INT       NOT NULL,
  FOREIGN KEY (teacher_id) REFERENCES users(id)
);

-- Sessions: one meeting per class per date (enforced unique)
CREATE TABLE sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  class_id INT           NOT NULL,
  session_date DATE      NOT NULL,
  UNIQUE KEY uq_class_date (class_id, session_date),
  FOREIGN KEY (class_id) REFERENCES classes(id)
);

-- Enrollments: link students to classes (many-to-many)
CREATE TABLE enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT         NOT NULL,
  class_id   INT         NOT NULL,
  FOREIGN KEY (student_id) REFERENCES users(id),
  FOREIGN KEY (class_id)   REFERENCES classes(id)
);

-- Attendance: one record per student per session
CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id INT         NOT NULL,
  student_id INT         NOT NULL,
  status ENUM('present','absent') DEFAULT 'present',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
               ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE(session_id, student_id),
  FOREIGN KEY (session_id) REFERENCES sessions(id),
  FOREIGN KEY (student_id) REFERENCES users(id)
);
