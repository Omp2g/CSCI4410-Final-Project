import React from 'react';
import { Link } from 'react-router-dom';
import './Dashboard.css';

function Dashboard() {
  return (
    <div className="dashboard-bg">
      <div className="dashboard-glass-card">
        <h2>Welcome to the Dashboard</h2>
        <p>Select an action below:</p>
        <div className="dashboard-links">
          <Link to="/qr-generator" className="dash-btn">🧾 Generate QR (Teacher)</Link>
          <Link to="/qr-scanner" className="dash-btn">📷 Scan QR (Student)</Link>
        </div>
      </div>
    </div>
  );
}

export default Dashboard;
