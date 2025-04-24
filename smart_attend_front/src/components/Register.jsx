import React, { useState } from 'react';
import './Register.css';

function Register() {
  const [form, setForm] = useState({
    name: '',
    email: '',
    password: '',
    role: 'student',
  });

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleRegister = async () => {
    const res = await fetch('http://localhost/smart_attend/register.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form),
    });
    const data = await res.json();
    alert(data.message);
  };

  return (
    <div className="register-bg">
      <div className="register-glass-card">
        <h2>Create Account</h2>
        <input
          name="name"
          placeholder="Full Name"
          onChange={handleChange}
        />
        <input
          name="email"
          placeholder="Email Address"
          type="email"
          onChange={handleChange}
        />
        <input
          name="password"
          type="password"
          placeholder="Password"
          onChange={handleChange}
        />
        <select name="role" onChange={handleChange}>
          <option value="student">Student</option>
          <option value="teacher">Teacher</option>
        </select>
        <button onClick={handleRegister}>Register</button>
      </div>
    </div>
  );
}

export default Register;
