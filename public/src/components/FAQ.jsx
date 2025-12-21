import { useState } from 'react';
import './FAQ.css';

const faqs = [
  {
    question: "O que é o DeepEyes?",
    answer: "DeepEyes é uma plataforma de IA especializada em segurança ofensiva. Ela auxilia profissionais de segurança em testes de penetração, simulações de Red Team e análises de vulnerabilidades em ambientes controlados."
  },
  {
    question: "Posso usar em produção?",
    answer: "O DeepEyes deve ser usado APENAS em ambientes autorizados, como laboratórios de teste, CTFs ou infraestruturas onde você tem permissão explícita para realizar testes de segurança. Nunca use em sistemas de produção sem autorização."
  },
  {
    question: "A IA simula ataques reais?",
    answer: "Sim, o DeepEyes utiliza técnicas reais de ataque, incluindo exploits conhecidos, técnicas de evasão e frameworks C2. Todas as simulações são baseadas em TTPs (Táticas, Técnicas e Procedimentos) documentados."
  },
  {
    question: "É seguro/legal usar?",
    answer: "O uso é legal quando realizado em ambientes autorizados. O operador é totalmente responsável por garantir que possui autorização para realizar testes. O DeepEyes é uma ferramenta educacional e profissional para hackers éticos."
  },
  {
    question: "Quais técnicas ela domina?",
    answer: "SQL Injection, XSS, CSRF, Reverse Shells, Privilege Escalation (Windows/Linux), Bypass de EDR/AMSI/WAF, Password Attacks, Lateral Movement, Persistence, Exfiltration, e integração com frameworks C2 como Cobalt Strike e Metasploit."
  }
];

export default function FAQ() {
  const [openIndex, setOpenIndex] = useState(null);

  return (
    <section id="faq" className="faq">
      <div className="container">
        <div className="section-header">
          <span className="section-tag mono">// FAQ</span>
          <h2 className="section-title">Perguntas Frequentes</h2>
        </div>

        <div className="faq-list">
          {faqs.map((faq, i) => (
            <div 
              key={i} 
              className={`faq-item ${openIndex === i ? 'open' : ''}`}
              onClick={() => setOpenIndex(openIndex === i ? null : i)}
            >
              <div className="faq-question">
                <span>{faq.question}</span>
                <span className="faq-icon">{openIndex === i ? '−' : '+'}</span>
              </div>
              <div className="faq-answer">
                <p>{faq.answer}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
