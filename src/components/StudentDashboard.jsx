import React, { useContext, useState, useEffect } from 'react';
import { ThemeContext } from '../ThemeContext';
import './StudentDashboard.css';

export default function StudentDashboard() {
  const name  = localStorage.getItem('userName') || 'Student';
  const { theme } = useContext(ThemeContext);
  const [summary, setSummary] = useState([]);
  const [history, setHistory] = useState([]);
  const token = sessionStorage.getItem('token');

  useEffect(() => {
    // load summary
    fetch('http://localhost/smart_attend/student_summary.php', {
      headers: { Authorization: 'Bearer ' + token }
    })
      .then(r => r.json())
      .then(setSummary)
      .catch(console.error);

    // load history
    fetch('http://localhost/smart_attend/student_attendance.php', {
      headers: { Authorization: 'Bearer ' + token }
    })
      .then(r => r.json())
      .then(setHistory)
      .catch(console.error);
  }, [token]);

  return (
    <div className={`dash dash--${theme}`}>
      <header className="dashboard-header">
        <h2>Welcome, {name}</h2>
        <p className="dashboard-subtitle">Your Attendance Overview</p>
      </header>

      <div className="dashboard-content">
        {/* Left panel: summary */}
        <section className="panel left-panel">
          <h3>Attendance Summary</h3>
          <div className="stats">
            {summary.length > 0 ? (
              summary.map(c => (
                <div key={c.course} className="stat">
                  <div className="stat-value">{c.percentage}%</div>
                  <div className="stat-label">{c.course}</div>
                  <div className="stat-bar">
                    <div
                      className="stat-fill"
                      style={{ width: `${c.percentage}%` }}
                    />
                  </div>
                </div>
              ))
            ) : (
              <p className="no-data">No data yet.</p>
            )}
          </div>
        </section>

        {/* Right panel: history */}
        <section className="panel right-panel">
          <h3>Attendance History</h3>
          {history.length > 0 ? (
            <table className="history-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Course</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                {history.map(r => (
                  <tr key={r.id}>
                    <td>{new Date(r.date).toLocaleDateString()}</td>
                    <td>{r.course}</td>
                    <td className={`status-${r.status}`}>
                      {r.status.charAt(0).toUpperCase() + r.status.slice(1)}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          ) : (
            <p className="no-data">No records yet.</p>
          )}
        </section>
      </div>
    </div>
  );
}
