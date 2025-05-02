import React from 'react';
import StudentSummary from './StudentSummary';
import StudentHistory from './StudentHistory';

export default function StudentDashboard() {
    return (
        <div className="p-6 space-y-6">
            <h1 className="text-2xl font-bold">Student Dashboard</h1>
            <StudentSummary />
            <StudentHistory />
        </div>
    );
}
