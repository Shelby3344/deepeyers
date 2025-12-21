import { AlertTriangle } from 'lucide-react';
import './Disclaimer.css';

export default function Disclaimer() {
  return (
    <section className="disclaimer">
      <div className="container">
        <div className="disclaimer-box">
          <AlertTriangle size={32} className="disclaimer-icon" />
          <p className="disclaimer-text">
            <strong>Aviso Legal:</strong> Use apenas em ambientes autorizados. 
            O operador é responsável pelo uso ético e legal de todas as ferramentas e técnicas disponibilizadas pelo DeepEyes.
          </p>
        </div>
      </div>
    </section>
  );
}
