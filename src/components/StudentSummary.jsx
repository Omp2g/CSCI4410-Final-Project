import React, { useState, useEffect } from 'react';
import './StudentSummary.css';

export default function StudentSummary() {
  const [summary, setSummary] = useState([]);

  useEffect(() => {
    const token = sessionStorage.getItem('token');
    fetch('http://localhost/smart_attend/student_summary.php', {
      headers: { 'Authorization': 'Bearer ' + token }
    })
      .then(r => r.json())
      .then(setSummary)
      .catch(console.error);
  }, []);

  return (
    <div className="student-summary">
      {summary.map(c => (
        <div key={c.course} className="summary-card">
          <h4>{c.course}</h4>
          <p>{c.percentage}%</p>
        </div>
      ))}
    </div>
  );
}
