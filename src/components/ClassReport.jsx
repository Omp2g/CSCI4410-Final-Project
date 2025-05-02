import React, { useState } from 'react';

export default function ClassReport() {
    const [from, setFrom] = useState('');
    const [to, setTo] = useState('');
    const classId = sessionStorage.getItem('classId');

    const download = () => {
        const token = sessionStorage.getItem('token');
        window.location = `backend/api/class_report.php?classId=${classId}&from=${from}&to=${to}&token=${token}`;
    };

    return (
        <div className="card p-4">
            <h3 className="mb-4">Class Report</h3>
            <div className="flex space-x-2 mb-4">
                <input type="date" value={from} onChange={e => setFrom(e.target.value)} />
                <input type="date" value={to} onChange={e => setTo(e.target.value)} />
                <button
                    onClick={download}
                    className="bg-blue-600 text-white px-4 rounded"
                >
                    Download CSV
                </button>
            </div>
        </div>
    );
}
