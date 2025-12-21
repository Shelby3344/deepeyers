import './SocialProof.css';

const testimonials = [
  {
    text: "DeepEyes mudou completamente nossa abordagem de pentest. A IA identifica vetores que levaríamos dias para encontrar.",
    author: "Carlos M.",
    role: "Red Team Lead @ CyberSec Corp"
  },
  {
    text: "Simulações APT realistas em minutos. Essencial para qualquer equipe de segurança ofensiva.",
    author: "Ana R.",
    role: "Security Researcher"
  },
  {
    text: "A melhor ferramenta de automação de pentest que já usei. O modo Full Attack é impressionante.",
    author: "Pedro S.",
    role: "Pentester Sênior"
  }
];

const logos = [
  "HackTheBox", "TryHackMe", "CTF Brasil", "CyberOps", "RedTeam Labs", "SecForce"
];

export default function SocialProof() {
  return (
    <section className="social-proof">
      <div className="container">
        <p className="trust-text mono">Confiado por hackers éticos em +12 países</p>
        
        <div className="logos-grid">
          {logos.map((logo, i) => (
            <div key={i} className="logo-item mono">{logo}</div>
          ))}
        </div>

        <div className="testimonials-grid">
          {testimonials.map((t, i) => (
            <div key={i} className="testimonial-card">
              <p className="testimonial-text">"{t.text}"</p>
              <div className="testimonial-author">
                <div className="author-avatar">{t.author[0]}</div>
                <div>
                  <p className="author-name">{t.author}</p>
                  <p className="author-role mono">{t.role}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
