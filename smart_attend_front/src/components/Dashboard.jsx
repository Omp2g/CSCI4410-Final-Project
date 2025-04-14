
import React from 'react';
import { Link } from 'react-router-dom';

function Dashboard() {
  return (
    <div>
      <h2>Dashboard</h2>
      <Link to="/qr-generator">Generate QR (Teacher)</Link><br />
      <Link to="/qr-scanner">Scan QR (Student)</Link>
    </div>
  );
}

export default Dashboard;
