import { Database, Terminal, TrendingUp, ShieldOff, Skull, KeyRound } from 'lucide-react';
import './Features.css';

const features = [
  {
    icon: Database,
    title: "SQL Injection",
    description: "Detecção e exploração automatizada de vulnerabilidades SQLi em aplicações web."
  },
  {
    icon: Terminal,
    title: "Reverse Shells",
    description: "Geração de payloads customizados para múltiplas plataformas e linguagens."
  },
  {
    icon: TrendingUp,
    title: "Privilege Escalation",
    description: "Técnicas avançadas de escalação de privilégios em Windows e Linux."
  },
  {
    icon: ShieldOff,
    title: "Evasão de EDR/AMSI/WAF",
    description: "Bypass de soluções de segurança com técnicas de ofuscação e evasão."
  },
  {
    icon: Skull,
    title: "Simulação APT",
    description: "Frameworks C2 integrados para simulações de ameaças persistentes avançadas."
  },
  {
    icon: KeyRound,
    title: "Password Attacks",
    description: "Ataques de força bruta, spray e cracking com wordlists inteligentes."
  }
];

export default function Features() {
  return (
    <section id="recursos" className="features">
      <div className="container">
        <div className="section-header">
          <span className="section-tag mono">// RECURSOS DA IA</span>
          <h2 className="section-title">Arsenal completo de técnicas</h2>
          <p className="section-subtitle">Todas as ferramentas que você precisa para testes de segurança ofensiva</p>
        </div>

        <div className="features-grid">
          {features.map((f, i) => (
            <div key={i} className="feature-card">
              <div className="feature-icon">
                <f.icon size={32} strokeWidth={1.5} />
              </div>
              <h3 className="feature-title">{f.title}</h3>
              <p className="feature-description">{f.description}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
