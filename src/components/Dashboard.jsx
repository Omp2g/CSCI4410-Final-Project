import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

export default function Dashboard() {
  const role = localStorage.getItem('userRole');
  const nav  = useNavigate();

  useEffect(() => {
    nav(role === 'teacher' ? '/teacher' : '/student');
  }, [role, nav]);

  return null;
}
