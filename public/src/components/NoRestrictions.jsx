import { Unlock, ShieldOff, Cpu, Zap } from 'lucide-react';
import './NoRestrictions.css';

const features = [
  {
    icon: Unlock,
    title: "Zero Censura",
    description: "Acesso completo a todas as técnicas e payloads sem filtros."
  },
  {
    icon: ShieldOff,
    title: "Sem Bloqueios",
    description: "A IA responde qualquer pergunta sobre segurança ofensiva."
  },
  {
    icon: Cpu,
    title: "IA Especializada",
    description: "Treinada para operações de Red Team e Pentest."
  },
  {
    icon: Zap,
    title: "Arsenal Completo",
    description: "Exploits, bypasses e evasões disponíveis sem limites."
  }
];

export default function NoRestrictions() {
  return (
    <section className="no-restrictions">
      <div className="container">
        <div className="nr-content">
          <div className="nr-badge mono">
            <Unlock size={14} />
            UNRESTRICTED MODE
          </div>
          
          <h2 className="nr-title">
            IA <span className="highlight">100% Sem Restrições</span>
          </h2>
          
          <p className="nr-subtitle">
            Diferente de outras IAs, o DeepEyes foi construído para profissionais. 
            Sem censura, sem bloqueios, sem limitações.
          </p>

          <div className="nr-grid">
            {features.map((f, i) => (
              <div key={i} className="nr-card">
                <div className="nr-icon">
                  <f.icon size={24} strokeWidth={1.5} />
                </div>
                <h3>{f.title}</h3>
                <p>{f.description}</p>
              </div>
            ))}
          </div>

          <div className="nr-terminal mono">
            <div className="terminal-line">
              <span className="prompt">$</span> 
              <span className="cmd">deepeyes config --restrictions</span>
            </div>
            <div className="terminal-output">
              <p className="success">[+] content_filter: DISABLED</p>
              <p className="success">[+] safety_mode: OFF</p>
              <p className="success">[+] exploit_generation: ENABLED</p>
              <p className="highlight">[!] Status: FULLY UNRESTRICTED</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
