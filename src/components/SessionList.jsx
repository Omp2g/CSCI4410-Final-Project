import React, { useState, useEffect } from 'react';

export default function SessionList({ onSelect }) {
  const [sessions, setSessions] = useState([]);
  const token = sessionStorage.getItem('token');

  useEffect(() => {
    fetch('http://localhost/smart_attend/get_sessions.php', {
      headers: { Authorization: 'Bearer ' + token }
    })
      .then(r => {
        if (!r.ok) throw new Error('Failed to load sessions');
        return r.json();
      })
      .then(setSessions)
      .catch(console.error);
  }, [token]);

  return (
    <aside className="session-list card">
      <h3>Your Sessions</h3>
      <ul>
        {sessions.map(s => (
          <li key={s.id}>
            <button onClick={() => onSelect(s)}>
              {s.course_name} —{' '}
              {new Date(s.session_date + 'T00:00').toLocaleDateString()}
            </button>
          </li>
        ))}
      </ul>
    </aside>
  );
}
