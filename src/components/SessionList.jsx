import React, { useState, useEffect } from 'react';

export default function SessionList({ onSelect }) {
  const [sessions, setSessions] = useState([]);

  useEffect(() => {
    fetch('http://localhost/smart_attend/get_sessions.php', {
      headers: { 'Authorization': 'Bearer ' + sessionStorage.getItem('token') }
    })
      .then(r => r.json())
      .then(setSessions)
      .catch(console.error);
  }, []);

  return (
    <aside className="session-list">
      <h3>Your Sessions</h3>
      <ul>
        {sessions.map(s => (
          <li key={s.id}>
            <button onClick={() => onSelect(s)}>
              {s.course_name} — {new Date(s.session_date).toLocaleDateString()}
            </button>
          </li>
        ))}
      </ul>
    </aside>
  );
}
