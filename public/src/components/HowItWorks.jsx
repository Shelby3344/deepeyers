import { Search, Target, Zap } from 'lucide-react';
import './HowItWorks.css';

const modes = [
  {
    icon: Search,
    title: "Pentest Assistido por IA",
    description: "A IA guia você através de testes de penetração, sugerindo vetores de ataque e automatizando reconhecimento.",
    features: ["Reconhecimento automatizado", "Sugestões inteligentes", "Relatórios detalhados"]
  },
  {
    icon: Target,
    title: "Red Team com Simulação Real",
    description: "Simule ataques reais com técnicas avançadas de evasão e persistência em ambientes controlados.",
    features: ["Técnicas de evasão", "Simulação de APT", "Cenários customizados"]
  },
  {
    icon: Zap,
    title: "Modo Full Attack",
    description: "Ofensiva máxima com todas as técnicas disponíveis. Para profissionais experientes em ambientes autorizados.",
    features: ["Exploração automatizada", "Privilege escalation", "Lateral movement"]
  }
];

export default function HowItWorks() {
  return (
    <section id="como-funciona" className="how-it-works">
      <div className="container">
        <div className="section-header">
          <span className="section-tag mono">// COMO FUNCIONA</span>
          <h2 className="section-title">Três modos de operação</h2>
          <p className="section-subtitle">Escolha o nível de automação e agressividade para sua operação</p>
        </div>

        <div className="modes-grid">
          {modes.map((mode, i) => (
            <div key={i} className="mode-card">
              <div className="mode-icon">
                <mode.icon size={40} strokeWidth={1.5} />
              </div>
              <h3 className="mode-title">{mode.title}</h3>
              <p className="mode-description">{mode.description}</p>
              <ul className="mode-features">
                {mode.features.map((f, j) => (
                  <li key={j} className="mono">
                    <span className="check">›</span> {f}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        <div className="terminal-mockup mono">
          <div className="terminal-header">
            <span className="dot red"></span>
            <span className="dot yellow"></span>
            <span className="dot green"></span>
            <span className="terminal-title">deepeyes@lab:~/pentest</span>
          </div>
          <div className="terminal-body">
            <p><span className="prompt">$</span> deepeyes scan --target 192.168.1.0/24</p>
            <p className="output">[*] Iniciando reconhecimento de rede...</p>
            <p className="output">[+] 12 hosts descobertos</p>
            <p className="output">[+] Serviços identificados: SSH, HTTP, SMB, RDP</p>
            <p className="output highlight">[!] Vulnerabilidade crítica encontrada: CVE-2024-XXXX</p>
            <p><span className="prompt">$</span> deepeyes exploit --auto</p>
            <p className="output">[*] Selecionando exploit apropriado...</p>
            <p className="output success">[+] Shell obtido em 192.168.1.105</p>
          </div>
        </div>
      </div>
    </section>
  );
}
