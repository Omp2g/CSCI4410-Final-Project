import React from "react";
import {
  BrowserRouter as Router,
  Routes,
  Route,
  Navigate,
} from "react-router-dom";
import Register from "./components/Register";
import Login from "./components/Login";
import TeacherDashboard from "./components/TeacherDashboard";
import StudentDashboard from "./components/StudentDashboard";

export default function App() {
  return (
    <Router>
      <Routes>
        {/* Public */}
        <Route path="/" element={<Login />} />
        <Route path="/login" element={<Navigate to="/" replace />} />
        <Route path="/register" element={<Register />} />

        {/* Protected */}
        <Route
          path="/teacher"
          element={
            sessionStorage.getItem("role") === "teacher" ? (
              <TeacherDashboard />
            ) : (
              <Navigate to="/" replace />
            )
          }
        />

        <Route
          path="/student"
          element={
            sessionStorage.getItem("role") === "student" ? (
              <StudentDashboard />
            ) : (
              <Navigate to="/" replace />
            )
          }
        />

        {/* Fallback */}
        <Route path="*" element={<Navigate to="/" replace />} />
      </Routes>
    </Router>
  );
}
