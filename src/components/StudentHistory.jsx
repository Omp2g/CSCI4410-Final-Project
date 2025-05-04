import React, { useState, useEffect } from 'react';
import './StudentHistory.css';

export default function StudentHistory() {
  const [records, setRecords] = useState([]);

  useEffect(() => {
    const token = sessionStorage.getItem('token');
    fetch('http://localhost/smart_attend/student_attendance.php', {
      headers: { 'Authorization': 'Bearer ' + token }
    })
      .then(r => r.json())
      .then(setRecords)
      .catch(console.error);
  }, []);

  return (
    <div className="student-history">
      <h3>Attendance History</h3>
      <table>
        <thead>
          <tr><th>Date</th><th>Course</th><th>Status</th></tr>
        </thead>
        <tbody>
          {records.map(r => (
            <tr key={r.id}>
              <td>{new Date(r.date).toLocaleDateString()}</td>
              <td>{r.course}</td>
              <td>{r.status}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
