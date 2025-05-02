import React, { useState } from 'react';
import SessionList from './SessionList';
import AttendanceGrid from './AttendanceGrid';
import ClassReport from './ClassReport';

export default function TeacherDashboard() {
    const [session, setSession] = useState(null);

    return (
        <div className="p-6 space-y-6">
            <h1 className="text-2xl font-bold">Teacher Dashboard</h1>
            <div className="flex space-x-6">
                <div className="w-1/4">
                    <SessionList onSelect={setSession} />
                </div>
                <div className="flex-1 space-y-6">
                    <AttendanceGrid session={session} />
                    <ClassReport />
                </div>
            </div>
        </div>
    );
}
