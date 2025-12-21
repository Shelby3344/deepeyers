import { ChevronRight } from 'lucide-react';
import './Hero.css';

export default function Hero() {
  return (
    <section className="hero">
      <div className="hero-content">
        <img src="/image/Design sem nome.png" alt="DeepEyes" className="hero-logo" />
        
        <div className="hero-badge mono">
          <span className="pulse"></span>
          Sistema Ativo — Modo BETA
        </div>
        
        <h1 className="hero-title">
          Bem-vindo ao <span className="highlight">DeepEyes</span>
          <br />
          <span className="subtitle">sua IA de segurança ofensiva</span>
        </h1>
        
        <p className="hero-description">
          Pentest. Red Team. Simulações APT. Tudo em um laboratório controlado.
        </p>
        
        <div className="hero-actions">
          <button className="btn-primary">
            <span className="btn-glow"></span>
            <span className="btn-text">
              <ChevronRight size={18} />
              Entrar no Laboratório
            </span>
          </button>
          <button className="btn-secondary mono">
            Ver Documentação
          </button>
        </div>

        <div className="hero-terminal mono">
          <div className="terminal-header">
            <span className="dot red"></span>
            <span className="dot yellow"></span>
            <span className="dot green"></span>
            <span className="terminal-title">deepeyes@lab:~</span>
          </div>
          <div className="terminal-body">
            <p><span className="prompt">$</span> deepeyes --init --mode=pentest</p>
            <p className="output">[+] Conectando ao DeepEyes Core...</p>
            <p className="output">[+] Carregando módulos de ataque...</p>
            <p className="output">[+] IA pronta para operação</p>
            <p><span className="prompt">$</span> <span className="cursor">_</span></p>
          </div>
        </div>
      </div>
    </section>
  );
}
