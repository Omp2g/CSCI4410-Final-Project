import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate, useLocation } from 'react-router-dom';
import './App.css';
import { ThemeProvider } from './ThemeContext';

import Header               from './components/Header';
import Login                from './components/Login';
import Register             from './components/Register';
import Dashboard            from './components/Dashboard';
import TeacherDashboard     from './components/TeacherDashboard';
import ClassDetailPage      from './components/ClassDetailPage';
import SessionAttendancePage from './components/SessionAttendancePage';
import StudentDashboard     from './components/StudentDashboard';

function AppContent() {
  const token    = sessionStorage.getItem('token');
  const { pathname } = useLocation();
  const isPublic = pathname === '/' || pathname === '/register';

  if (!token && !isPublic) {
    return <Navigate to="/" replace />;
  }

  return (
    <>
      {!isPublic && <Header />}

      <Routes>
        {/* Public */}
        <Route path="/"        element={token ? <Navigate to="/dashboard" /> : <Login />} />
        <Route path="/register" element={token ? <Navigate to="/dashboard" /> : <Register />} />

        {/* Dashboard router */}
        <Route path="/dashboard" element={token ? <Dashboard /> : <Navigate to="/" />} />

        {/* Teacher flows */}
        <Route path="/teacher" element={token ? <TeacherDashboard /> : <Navigate to="/" />} />
        <Route path="/teacher/class/:classId" element={token ? <ClassDetailPage /> : <Navigate to="/" />} />
        <Route path="/teacher/class/:classId/session/:sessionId" element={token ? <SessionAttendancePage /> : <Navigate to="/" />} />

        {/* Student flows */}
        <Route path="/student" element={token ? <StudentDashboard /> : <Navigate to="/" />} />

        {/* Catch-all */}
        <Route path="*" element={<Navigate to={token ? "/dashboard" : "/"} />} />
      </Routes>
    </>
  );
}

export default function App() {
  return (
    <ThemeProvider>
      <Router>
        <AppContent />
      </Router>
    </ThemeProvider>
  );
}

