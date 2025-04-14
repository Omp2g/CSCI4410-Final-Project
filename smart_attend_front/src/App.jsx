import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';

import Login from './components/Login';
import Register from './components/Register';
import Dashboard from './components/Dashboard';
import QRGenerator from './components/QRGenerator';
import QRScanner from './components/QRScanner';

function App() {
  return (
    <Router>
      <div>
        <h1>Smart Attend</h1>
        <Routes>
          <Route path="/" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/dashboard" element={<Dashboard />} />
          <Route path="/qr-generator" element={<QRGenerator classId={1} />} />
          <Route path="/qr-scanner" element={<QRScanner />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
