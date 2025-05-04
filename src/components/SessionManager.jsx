// src/components/SessionManager.jsx
import React, { useState, useEffect } from 'react';
import './SessionManager.css';

export default function SessionManager({ classId, onSelect }) {
  const [sessions, setSessions] = useState([]);
  const [date, setDate]         = useState('');
  const token                   = sessionStorage.getItem('token');

  // load sessions when classId (or token) changes
  useEffect(() => {
    if (!classId) {
      setSessions([]);
      return;
    }
    fetch(`http://localhost/smart_attend/get_sessions.php?classId=${classId}`, {
      headers: { Authorization: 'Bearer ' + token }
    })
      .then(r => {
        if (!r.ok) throw new Error('Failed to load sessions');
        return r.json();
      })
      .then(setSessions)
      .catch(err => {
        console.error(err);
        alert(err.message);
      });
  }, [classId, token]);

  const addSession = async () => {
    if (!date) {
      return alert('Please pick a date');
    }

    try {
      const res = await fetch('http://localhost/smart_attend/add_session.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: 'Bearer ' + token
        },
        // send the key name the PHP expects:
        body: JSON.stringify({ classId, sessionDate: date })
      });

      if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        return alert(err.message || 'Error creating session');
      }

      setDate('');
      // reload the updated sessions list
      const updated = await fetch(
        `http://localhost/smart_attend/get_sessions.php?classId=${classId}`,
        { headers: { Authorization: 'Bearer ' + token } }
      ).then(r => r.json());
      setSessions(updated);

    } catch (err) {
      console.error(err);
      alert('Network error creating session');
    }
  };

  return (
    <aside className="session-manager card">
      <h3>Sessions</h3>
      <ul className="session-list">
        {sessions.map(s => (
          <li key={s.id}>
            <button onClick={() => onSelect(s)}>
              {new Date(s.session_date).toLocaleDateString()}
            </button>
          </li>
        ))}
      </ul>
      <div className="add-section">
        <input
          type="date"
          value={date}
          onChange={e => setDate(e.target.value)}
        />
        <button onClick={addSession}>+ Add Session</button>
      </div>
    </aside>
  );
}


