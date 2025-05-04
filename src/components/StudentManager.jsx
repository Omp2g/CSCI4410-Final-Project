// src/components/StudentManager.jsx
import React, { useState, useEffect } from 'react';
import './StudentManager.css';

export default function StudentManager({ classId }) {
  const [students, setStudents] = useState([]);
  const [email, setEmail]       = useState('');
  const token                   = sessionStorage.getItem('token');

  // Load students whenever classId changes
  useEffect(() => {
    if (!classId) {
      setStudents([]);
      return;
    }
    (async () => {
      try {
        const res  = await fetch(
          `http://localhost/smart_attend/get_enrollments.php?classId=${classId}`,
          { headers: { Authorization: 'Bearer ' + token } }
        );
        if (!res.ok) throw new Error('Failed to load students');
        const data = await res.json();
        setStudents(data);
      } catch (err) {
        console.error(err);
        alert(err.message);
        setStudents([]);
      }
    })();
  }, [classId, token]);

  const addStudent = async () => {
    if (!email.trim()) return alert('Enter a student email');

    try {
      const res = await fetch('http://localhost/smart_attend/add_enrollment.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: 'Bearer ' + token
        },
        body: JSON.stringify({ classId, email: email.trim() })
      });

      if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        return alert(err.message || 'Error enrolling student');
      }

      // success → clear input & reload list
      setEmail('');
      const updated = await fetch(
        `http://localhost/smart_attend/get_enrollments.php?classId=${classId}`,
        { headers: { Authorization: 'Bearer ' + token } }
      ).then(r => r.json());

      setStudents(updated);
    } catch (err) {
      console.error(err);
      alert('Network error enrolling student');
    }
  };

  const removeStudent = async id => {
    try {
      const res = await fetch('http://localhost/smart_attend/remove_enrollment.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: 'Bearer ' + token
        },
        body: JSON.stringify({ classId, studentId: id })
      });
      if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        return alert(err.message || 'Error removing student');
      }
      setStudents(s => s.filter(x => x.id !== id));
    } catch (err) {
      console.error(err);
      alert('Network error removing student');
    }
  };

  return (
    <div className="student-manager card">
      <h3>Enrolled Students</h3>
      <ul>
        {students.map(s => (
          <li key={s.id}>
            {s.name}
            <button onClick={() => removeStudent(s.id)} className="remove-btn">
              ×
            </button>
          </li>
        ))}
      </ul>
      <div className="add-section">
        <input
          type="email"
          placeholder="Student email"
          value={email}
          onChange={e => setEmail(e.target.value)}
        />
        <button onClick={addStudent}>+ Add Student</button>
      </div>
    </div>
  );
}
