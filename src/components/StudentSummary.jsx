import React, { useState, useEffect } from 'react';

export default function StudentSummary() {
    const [summary, setSummary] = useState([]);
    const token = sessionStorage.getItem('token');

    useEffect(() => {
        fetch('backend/api/student_summary.php', {
            headers: { Authorization: 'Bearer ' + token }
        })
            .then(r => r.json())
            .then(setSummary);
    }, []);

    return (
        <div className="grid grid-cols-2 gap-4">
            {summary.map(c => (
                <div key={c.course} className="card p-4">
                    <h4 className="font-semibold">{c.course}</h4>
                    <p>Attendance: {c.percentage}%</p>
                </div>
            ))}
        </div>
    );
}
