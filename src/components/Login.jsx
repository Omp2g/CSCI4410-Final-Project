// src/components/Login.jsx
import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import './Login.css';

export default function Login() {
  const [email, setEmail]       = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  const handleLogin = async () => {
    try {
      const res = await fetch('http://localhost/smart_attend/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      });
      const data = await res.json();

      if (data.token) {
        // Store token so protected routes mount immediately
        sessionStorage.setItem('token', data.token);
        // Persist role & name for subsequent dashboards
        localStorage.setItem('userRole', data.role);
        localStorage.setItem('userName', data.name);
        // Go to dashboard in one step
        navigate('/dashboard');
      } else {
        alert('Login failed: ' + (data.message || 'Invalid credentials'));
      }
    } catch (err) {
      console.error(err);
      alert('Server error. Please try again.');
    }
  };

  return (
    <div className="login-bg">
      <div className="login-card">
        <h2>Smart Attend</h2>

        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={e => setEmail(e.target.value)}
          required
        />

        <input
          type="password"
          placeholder="Password"
          value={password}
          onChange={e => setPassword(e.target.value)}
          required
        />

        <button type="button" onClick={handleLogin}>
          Login
        </button>

        <p className="auth-toggle">
          Don't have an account? <Link to="/register">Register here</Link>
        </p>
      </div>
    </div>
  );
}
