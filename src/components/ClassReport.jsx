// src/components/ClassReport.jsx
import React, { useState } from 'react';
import './ClassReport.css';

export default function ClassReport({ classId }) {
  const [from, setFrom] = useState('');
  const [to,   setTo]   = useState('');
  const token = sessionStorage.getItem('token');

  const download = () => {
    window.location.href = `http://localhost/smart_attend/class_report.php`
      + `?classId=${classId}&from=${from}&to=${to}&token=${token}`;
  };

  return (
    <div className="class-report card">
      <h3>Download Class Report</h3>
      <div className="report-controls">
        <input
          type="date"
          value={from}
          onChange={e => setFrom(e.target.value)}
        />
        <input
          type="date"
          value={to}
          onChange={e => setTo(e.target.value)}
        />
        <button onClick={download}>Download CSV</button>
      </div>
    </div>
  );
}

