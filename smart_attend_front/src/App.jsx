import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Register from './components/Register';
import Login from './components/Login';
import Dashboard from './components/Dashboard';
import QRGenerator from './components/QRGenerator';
import QRScanner from './components/QRScanner';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Register />} />
        <Route path="/login" element={<Login />} />
        <Route path="/dashboard" element={<Dashboard />} />
        <Route path="/qr-generator" element={<QRGenerator classId={1} />} />
        <Route path="/qr-scanner" element={<QRScanner />} />
      </Routes>
    </Router>
  );
}

export default App
