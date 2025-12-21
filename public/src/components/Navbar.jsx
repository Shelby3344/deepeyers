import { useState } from 'react';
import { ChevronRight, Menu, X } from 'lucide-react';
import './Navbar.css';

export default function Navbar() {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <nav className="navbar">
      <div className="navbar-container">
        <a href="#" className="navbar-logo">
          <span className="logo-text">DeepEyes</span>
          <span className="beta-badge">BETA</span>
        </a>

        <div className={`navbar-links ${isOpen ? 'active' : ''}`}>
          <a href="#como-funciona">Como Funciona</a>
          <a href="#recursos">Recursos</a>
          <a href="#demo">Demo</a>
          <a href="#faq">FAQ</a>
          <a href="https://docs.deepeyes.ai" target="_blank" rel="noopener noreferrer">Docs</a>
        </div>

        <div className="navbar-actions">
          <button className="btn-enter">
            <ChevronRight size={16} />
            Entrar no Lab
          </button>
        </div>

        <button className="navbar-toggle" onClick={() => setIsOpen(!isOpen)}>
          {isOpen ? <X size={20} /> : <Menu size={20} />}
        </button>
      </div>
    </nav>
  );
}
