import React from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import StudentManager  from './StudentManager';
import SessionManager  from './SessionManager';
import './ClassDetailPage.css';

export default function ClassDetailPage() {
  const { classId } = useParams();
  const navigate     = useNavigate();

  return (
    <div className="class-detail-grid">
      <StudentManager classId={classId} />
      <SessionManager
        classId={classId}
        onSelect={s => navigate(`/teacher/class/${classId}/session/${s.id}`)}
      />
    </div>
  );
}
