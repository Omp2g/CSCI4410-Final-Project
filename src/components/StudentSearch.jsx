// src/components/StudentSearch.jsx
import React, { useState } from 'react';
import './StudentSearch.css';

export default function StudentSearch() {
  const [email, setEmail]     = useState('');
  const [result, setResult]   = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError]     = useState('');

  const handleSearch = async () => {
    setLoading(true);
    setError('');
    setResult(null);

    const token = sessionStorage.getItem('token');
    if (!email) {
      setError('Please enter an email');
      setLoading(false);
      return;
    }

    try {
      const res = await fetch(
        `http://localhost/smart_attend/student_attendance_by_email.php?email=${encodeURIComponent(email)}`,
        { headers: { Authorization: 'Bearer ' + token } }
      );
      if (!res.ok) {
        const body = await res.json().catch(() => ({}));
        throw new Error(body.message || `Server returned ${res.status}`);
      }
      const data = await res.json();
      if (!data.length) {
        setError('No attendance records found for that email');
      } else {
        setResult(data);
      }
    } catch (err) {
      console.error(err);
      setError(err.message || 'Fetch error');
    } finally {
      setLoading(false);
    }
  };

  const downloadReport = () => {
    const token = sessionStorage.getItem('token');
    // Force navigation to your PHP report generator
    window.location = `http://localhost/smart_attend/student_report.php?email=${encodeURIComponent(email)}&token=${token}`;
  };

  return (
    <div className="student-search card">
      <h3>Search Student</h3>

      <div className="search-controls">
        <input
          type="email"
          placeholder="Student email"
          value={email}
          onChange={e => setEmail(e.target.value)}
        />
        <button onClick={handleSearch} disabled={loading}>
          {loading ? 'Searching…' : 'Search'}
        </button>
      </div>
        <br></br>
      {error && <p className="error">{error}</p>}

      {result && (
        <>
          <table className="history-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Course</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              {result.map((r, i) => (
                <tr key={i}>
                  <td>{new Date(r.session_date).toLocaleDateString()}</td>
                  <td>{r.class_name}</td>
                  <td className={`status-${r.status}`}>{r.status}</td>
                </tr>
              ))}
            </tbody>
          </table>
            <br></br>
          <button className="report-btn" onClick={downloadReport}> 📄 Download Report</button>
        </>
      )}
    </div>
  );
}
