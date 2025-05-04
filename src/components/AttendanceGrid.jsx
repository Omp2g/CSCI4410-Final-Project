// src/components/AttendanceGrid.jsx
import React, { useState, useEffect } from 'react';
import './AttendanceGrid.css';

export default function AttendanceGrid({ sessionId }) {
  const [session, setSession]   = useState(null);
  const [students, setStudents] = useState([]);
  const [records, setRecords]   = useState({});
  const [loading, setLoading]   = useState(true);
  const token                   = sessionStorage.getItem('token');

  useEffect(() => {
    if (!sessionId) {
      setLoading(false);
      return;
    }

    async function loadAll() {
      setLoading(true);
      try {
        // 1) Session detail
        const sessRes = await fetch(
          `http://localhost/smart_attend/get_session_detail.php?sessionId=${sessionId}`,
          { headers: { Authorization: 'Bearer ' + token } }
        );
        if (!sessRes.ok) throw new Error('Could not load session info');
        const sessData = await sessRes.json();

        // 2) Enrolled students
        const studRes = await fetch(
          `http://localhost/smart_attend/get_enrollments.php?classId=${sessData.class_id}`,
          { headers: { Authorization: 'Bearer ' + token } }
        );
        if (!studRes.ok) throw new Error('Could not load students');
        const studList = await studRes.json();

        // 3) Existing attendance (may be empty)
        const attRes = await fetch(
          `http://localhost/smart_attend/get_attendance.php?sessionId=${sessionId}`,
          { headers: { Authorization: 'Bearer ' + token } }
        );
        if (!attRes.ok) throw new Error('Could not load attendance');
        const attList = await attRes.json();
        const attMap = {};
        attList.forEach(a => {
          attMap[a.student_id] = a.status;
        });

        // set all state
        setSession(sessData);
        setStudents(studList);
        setRecords(attMap);
      } catch (err) {
        console.error(err);
        alert(err.message);
      } finally {
        setLoading(false);
      }
    }

    loadAll();
  }, [sessionId, token]);

  const handleChange = (studentId, status) => {
    setRecords(prev => ({ ...prev, [studentId]: status }));
  };

  const handleSave = async () => {
    if (!sessionId) return;

    // build full attendance payload: default to 'present'
    const payload = {
      sessionId: +sessionId,
      records: students.map(s => ({
        student_id: s.id,
        status: records[s.id] || 'present'
      }))
    };

    try {
      const res = await fetch(
        'http://localhost/smart_attend/post_attendance.php',
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Authorization: 'Bearer ' + token
          },
          body: JSON.stringify(payload)
        }
      );
      if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        return alert(err.message || 'Error saving attendance');
      }
      alert('Attendance saved for all students');
    } catch (err) {
      console.error(err);
      alert('Network error saving attendance');
    }
  };

  if (loading) {
    return <p>Loading attendance…</p>;
  }
  if (!sessionId) {
    return <p>Select a session to take attendance.</p>;
  }

  return (
    <div className="attendance-grid card">
      <h3>
        Attendance for {session.class_name} on{' '}
        {new Date(session.session_date).toLocaleDateString()}
      </h3>

      <table className="attendance-table">
        <thead>
          <tr>
            <th>Student</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          {students.map(s => (
            <tr key={s.id}>
              <td>{s.name}</td>
              <td>
                <select
                  value={records[s.id] || 'present'}
                  onChange={e => handleChange(s.id, e.target.value)}
                >
                  <option value="present">Present</option>
                  <option value="absent">Absent</option>
                </select>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      <button className="save-btn" onClick={handleSave}>
        Save Attendance
      </button>
    </div>
  );
}
