// src/components/ClassManager.jsx
import React, { useState, useEffect } from 'react';
import './ClassManager.css';

export default function ClassManager({ onSelect }) {
  const [classes, setClasses] = useState([]);
  const [name, setName]       = useState('');
  const token                 = sessionStorage.getItem('token');

  useEffect(() => {
    // inline the fetch so there's no missing-deps warning
    async function load() {
      try {
        const res = await fetch('http://localhost/smart_attend/get_classes.php', {
          headers: { Authorization: 'Bearer ' + token }
        });
        if (!res.ok) throw new Error('Failed to load classes');
        setClasses(await res.json());
      } catch (err) {
        console.error(err);
        alert('Error fetching classes');
      }
    }
    load();
  }, [token]);

  const addClass = async () => {
    if (!name.trim()) {
      return alert('Please enter a class name');
    }

    try {
      const res = await fetch('http://localhost/smart_attend/add_class.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: 'Bearer ' + token
        },
        body: JSON.stringify({ name: name.trim() })
      });

      if (res.status === 401) {
        return alert('Unauthorized. Please log in again.');
      }
      if (!res.ok) {
        const err = await res.json();
        return alert(err.message || 'Error adding class');
      }

      setName('');
      // reload list
      const updated = await fetch('http://localhost/smart_attend/get_classes.php', {
        headers: { Authorization: 'Bearer ' + token }
      }).then(r => r.json());
      setClasses(updated);
    } catch (err) {
      console.error(err);
      alert('Server error when adding class');
    }
  };

  return (
    <div className="class-manager card">
      <h3>Your Classes</h3>
      <ul className="class-list">
        {classes.map(c => (
          <li key={c.id}>
            <button
              className="class-item-btn"
              onClick={() => onSelect(c)}
            >
              {c.name}
            </button>
          </li>
        ))}
      </ul>
      <div className="add-section">
        <input
          type="text"
          placeholder="New class name"
          value={name}
          onChange={e => setName(e.target.value)}
        />
        <button onClick={addClass}>+ Add Class</button>
      </div>
    </div>
  );
}
