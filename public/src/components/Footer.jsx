import './Footer.css';

export default function Footer() {
  return (
    <footer className="footer">
      <div className="container">
        <div className="footer-content">
          <div className="footer-brand">
            <div className="footer-logo">
              <img src="/image/Design sem nome.png" alt="DeepEyes" className="logo-img" />
              <span className="logo-text">DeepEyes</span>
              <span className="beta-badge">BETA</span>
            </div>
            <p className="footer-tagline">IA de Segurança Ofensiva</p>
          </div>

          <div className="footer-links">
            <a href="#">Termos de Uso</a>
            <a href="#">Documentação</a>
            <a href="#">Contato</a>
            <a href="#">GitHub</a>
          </div>
        </div>

        <div className="footer-bottom">
          <p className="copyright mono">© 2024 DeepEyes. Todos os direitos reservados.</p>
        </div>
      </div>
    </footer>
  );
}
