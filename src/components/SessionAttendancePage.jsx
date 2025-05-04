import React from 'react';
import { useParams } from 'react-router-dom';
import AttendanceGrid from './AttendanceGrid';
import './SessionAttendancePage.css';

export default function SessionAttendancePage() {
  const { sessionId } = useParams();
  return (
    <div className="session-page">
      <AttendanceGrid sessionId={sessionId} />
    </div>
  );
}
