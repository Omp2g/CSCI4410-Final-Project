import React from 'react';
import { useNavigate } from 'react-router-dom';
import ClassManager  from './ClassManager';
import StudentSearch from './StudentSearch';
import './TeacherDashboard.css';

export default function TeacherDashboard() {
  const navigate = useNavigate();
  return (
    <div className="teacher-dashboard-grid">
      <ClassManager onSelect={c => navigate(`/teacher/class/${c.id}`)} />
      <StudentSearch />
    </div>
  );
}

