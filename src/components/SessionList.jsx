import React, { useState, useEffect } from 'react';

export default function SessionList({ onSelect }) {
    const [sessions, setSessions] = useState([]);

    useEffect(() => {
        fetch('backend/api/get_sessions.php', {
            headers: { 'Authorization': 'Bearer ' + sessionStorage.getItem('token') }
        })
            .then(r => r.json())
            .then(setSessions);
    }, []);

    return (
        <aside className="card p-4">
            <h3 className="mb-2">Your Sessions</h3>
            <ul className="space-y-2">
                {sessions.map(s => (
                    <li key={s.id} className="flex justify-between items-center">
                        <button
                            onClick={() => onSelect(s)}
                            className="text-blue-600 hover:underline"
                        >
                            {s.course_name} — {new Date(s.session_date).toLocaleDateString()}
                        </button>
                    </li>
                ))}
            </ul>
        </aside>
    );
}
