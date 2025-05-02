// src/components/Register.jsx
import React, { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import "./Register.css";

export default function Register() {
  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    role: "student",
  });
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleChange = (e) => {
    setForm((f) => ({ ...f, [e.target.name]: e.target.value }));
  };

  const handleRegister = async () => {
    setLoading(true);
    try {
      const res = await fetch("http://localhost/smart_attend/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(form),
      });
      const data = await res.json();
      alert(data.message);
      if (data.message === "Registration successful") {
        navigate("/");
      }
    } catch {
      alert("Server error. Please try again.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="register-bg">
      <div className="register-glass-card">
        <h2>Create Account</h2>
        <input
          name="name"
          placeholder="Full Name"
          value={form.name}
          onChange={handleChange}
          required
        />
        <input
          name="email"
          type="email"
          placeholder="Email Address"
          value={form.email}
          onChange={handleChange}
          required
        />
        <input
          name="password"
          type="password"
          placeholder="Password"
          value={form.password}
          onChange={handleChange}
          required
        />
        <select name="role" value={form.role} onChange={handleChange}>
          <option value="student">Student</option>
          <option value="teacher">Teacher</option>
        </select>
        <button onClick={handleRegister} disabled={loading}>
          {loading ? "Registering…" : "Register"}
        </button>
        <p className="auth-toggle">
          Already have an account? <Link to="/">Login here</Link>
        </p>
      </div>
    </div>
  );
}
