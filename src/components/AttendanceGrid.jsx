import React, { useState, useEffect } from 'react';

export default function AttendanceGrid({ session }) {
    const [students, setStudents] = useState([]);
    const [records, setRecords] = useState({});

    useEffect(() => {
        if (!session) return;
        const token = sessionStorage.getItem('token');

        fetch(`backend/api/get_students.php?sessionId=${session.id}`, {
            headers: { Authorization: 'Bearer ' + token }
        })
            .then(r => r.json())
            .then(setStudents);

        fetch(`backend/api/get_attendance.php?sessionId=${session.id}`, {
            headers: { Authorization: 'Bearer ' + token }
        })
            .then(r => r.json())
            .then(data => {
                const map = {};
                data.forEach(r => { map[r.student_id] = r.status; });
                setRecords(map);
            });
    }, [session]);

    const handleChange = (sid, st) =>
        setRecords(prev => ({ ...prev, [sid]: st }));

    const handleSave = () => {
        const token = sessionStorage.getItem('token');
        const payload = {
            sessionId: session.id,
            records: Object.entries(records).map(([sid, status]) => ({
                student_id: +sid,
                status
            }))
        };
        fetch('backend/api/post_attendance.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Authorization: 'Bearer ' + token
            },
            body: JSON.stringify(payload)
        });
    };

    if (!session)
        return <p>Select a session to take attendance.</p>;

    return (
        <div className="card p-4">
            <h3 className="mb-4">
                Attendance for {session.course_name} on{' '}
                {new Date(session.session_date).toLocaleDateString()}
            </h3>
            <table className="w-full mb-4">
                <thead>
                    <tr><th>Student</th><th>Status</th></tr>
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
            <button
                className="py-2 px-4 bg-green-600 text-white rounded"
                onClick={handleSave}
            >
                Save
            </button>
        </div>
    );
}
