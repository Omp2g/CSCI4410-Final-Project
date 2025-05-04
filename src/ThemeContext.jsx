import React, { createContext, useState, useEffect } from 'react';

export const ThemeContext = createContext();

export function ThemeProvider({ children }) {
  const [theme, setTheme] = useState('light');

  // Watch for system preference changes
  useEffect(() => {
    if (window.matchMedia) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
      
      const handleChange = () => {
        // Only auto-switch if user hasn't manually set a preference
        if (!localStorage.getItem('theme')) {
          const newTheme = mediaQuery.matches ? 'dark' : 'light';
          setTheme(newTheme);
          applyTheme(newTheme);
        }
      };
      
      mediaQuery.addEventListener('change', handleChange);
      return () => mediaQuery.removeEventListener('change', handleChange);
    }
  }, []);

  // Initialize theme
  useEffect(() => {
    // Check for system preference or saved preference
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const saved = localStorage.getItem('theme') || (prefersDark ? 'dark' : 'light');
    setTheme(saved);
    applyTheme(saved);
  }, []);

  const applyTheme = (newTheme) => {
    // Apply to root element for CSS variables
    document.documentElement.setAttribute('data-theme', newTheme);
    
    // Apply class to body for broader compatibility
    if (newTheme === 'dark') {
      document.body.classList.add('dark-theme');
      document.documentElement.classList.add('dark-mode');
    } else {
      document.body.classList.remove('dark-theme');
      document.documentElement.classList.remove('dark-mode');
    }
    
    // Store in localStorage
    localStorage.setItem('theme', newTheme);
  };

  const toggle = () => {
    const next = theme === 'light' ? 'dark' : 'light';
    setTheme(next);
    applyTheme(next);
  };

  return (
    <ThemeContext.Provider value={{ theme, toggle }}>
      {children}
    </ThemeContext.Provider>
  );
}