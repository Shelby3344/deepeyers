import './CTA.css';

export default function CTA() {
  return (
    <section className="cta">
      <div className="container">
        <div className="cta-content">
          <h2 className="cta-title">
            Pronto para testar sua infraestrutura com <span className="highlight">DeepEyes</span>?
          </h2>
          <p className="cta-subtitle">
            Comece agora e descubra vulnerabilidades antes que os atacantes o façam.
          </p>
          <button className="cta-button">
            <span className="btn-glow"></span>
            <span className="btn-text">Comece agora — é grátis na fase beta</span>
          </button>
        </div>
      </div>
    </section>
  );
}
