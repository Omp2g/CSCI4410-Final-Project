// src/components/Header.jsx
import React, { useContext } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { ThemeContext } from '../ThemeContext';
import './Header.css';

export default function Header() {
  const { theme, toggle } = useContext(ThemeContext);
  const role               = localStorage.getItem('userRole');
  const name               = localStorage.getItem('userName') || '';
  const nav                = useNavigate();
  const { pathname }       = useLocation();
  const isActive = p => pathname === p ? 'active' : '';

  const handleLogout = () => {
    sessionStorage.clear();
    localStorage.clear();
    nav('/');
  };

  return (
    <header className={`hdr hdr--${theme}`}>
      <Link to="/dashboard" className="hdr-logo">SmartAttend</Link>

      <nav className="hdr-nav">
        <Link to="/dashboard" className={isActive('/dashboard')}>Home</Link>
        {role === 'teacher'
          ? <Link to="/teacher" className={isActive('/teacher')}>Teacher</Link>
          : <Link to="/student" className={isActive('/student')}>Student</Link>
        }
      </nav>

      <div className="hdr-right">
        <label className="theme-switch">
          <input
            type="checkbox"
            checked={theme === 'dark'}
            onChange={toggle}
          />
          <span className="slider">
            {theme === 'light'
              ? <span className="icon">🌙</span>
              : <span className="icon">☀️</span>
            }
          </span>
        </label>

        <span className="hdr-user">Hi, {name}</span>

        <button
          onClick={handleLogout}
          className="hdr-logout"
          title="Logout"
        >
          ⍈
        </button>
      </div>
    </header>
  );
}

