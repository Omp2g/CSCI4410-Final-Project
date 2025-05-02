import React, { useState, useEffect } from 'react';

export default function StudentHistory() {
    const [records, setRecords] = useState([]);
    const token = sessionStorage.getItem('token');

    useEffect(() => {
        fetch('backend/api/student_attendance.php', {
            headers: { Authorization: 'Bearer ' + token }
        })
            .then(r => r.json())
            .then(setRecords);
    }, []);

    return (
        <div className="card p-4">
            <h3 className="mb-4">Attendance History</h3>
            <table className="w-full">
                <thead>
                    <tr><th>Date</th><th>Course</th><th>Status</th></tr>
                </thead>
                <tbody>
                    {records.map(r => (
                        <tr key={r.id}>
                            <td>{new Date(r.date).toLocaleDateString()}</td>
                            <td>{r.course}</td>
                            <td>{r.status}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
