<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentação - DeepEyes</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: #1a1a24;
            --accent-cyan: #00d4ff;
            --accent-green: #00ff88;
            --accent-purple: #8b5cf6;
            --accent-orange: #f97316;
            --accent-red: #ef4444;
            --accent-pink: #ec4899;
            --text-primary: #ffffff;
            --text-secondary: #a0a0b0;
            --border-color: #2a2a3a;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-primary); color: var(--text-primary); line-height: 1.7; }
        a { color: var(--accent-cyan); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        /* Navbar */
        .navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 16px 24px; background: rgba(10, 10, 15, 0.95); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-color); }
        .navbar-inner { max-width: 1400px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
        .navbar-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .navbar-logo img { width: 32px; height: 32px; }
        .logo-text { font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 1.2rem; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .badge { font-size: 0.6rem; padding: 3px 8px; background: var(--accent-purple); border-radius: 4px; color: white; font-weight: 600; }
        .navbar-links { display: flex; gap: 32px; }
        .navbar-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; transition: color 0.2s; }
        .navbar-links a:hover, .navbar-links a.active { color: var(--accent-cyan); }
        .btn-primary { padding: 10px 24px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border-radius: 8px; color: var(--bg-primary); font-weight: 600; font-size: 0.9rem; text-decoration: none; }
        .btn-primary:hover { text-decoration: none; opacity: 0.9; }

        /* Layout */
        .docs-wrapper { display: flex; padding-top: 70px; min-height: 100vh; }
        .sidebar { width: 280px; position: fixed; top: 70px; left: 0; bottom: 0; padding: 24px; overflow-y: auto; border-right: 1px solid var(--border-color); background: var(--bg-primary); }
        .sidebar-group { margin-bottom: 28px; }
        .sidebar-title { font-size: 0.7rem; font-weight: 600; color: var(--accent-cyan); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px; }
        .sidebar-links { list-style: none; }
        .sidebar-links li { margin-bottom: 4px; }
        .sidebar-links a { display: block; padding: 8px 12px; color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; border-radius: 6px; border-left: 2px solid transparent; transition: all 0.2s; }
        .sidebar-links a:hover { background: rgba(0, 212, 255, 0.08); color: var(--text-primary); }
        .sidebar-links a.active { background: rgba(0, 212, 255, 0.1); color: var(--accent-cyan); border-left-color: var(--accent-cyan); }
        .content { flex: 1; margin-left: 280px; padding: 48px 64px; max-width: 920px; }
        .section { margin-bottom: 64px; scroll-margin-top: 100px; }
        .section h1 { font-size: 2.4rem; font-weight: 700; margin-bottom: 16px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .section h2 { font-size: 1.5rem; font-weight: 600; margin: 40px 0 16px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 10px; }
        .section h3 { font-size: 1.2rem; font-weight: 600; margin: 28px 0 12px; color: var(--text-primary); }
        .section p { color: var(--text-secondary); margin-bottom: 16px; }
        .section ul, .section ol { color: var(--text-secondary); margin: 16px 0; padding-left: 24px; }
        .section li { margin-bottom: 8px; }
        .lead { font-size: 1.15rem; color: var(--text-secondary); margin-bottom: 32px; }

        /* Code blocks */
        .code-block { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px 20px; margin: 16px 0; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; overflow-x: auto; color: var(--accent-green); }
        code { background: var(--bg-secondary); padding: 2px 6px; border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: var(--accent-cyan); }
        .card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; margin: 20px 0; }
        .card-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 12px; color: var(--text-primary); display: flex; align-items: center; gap: 10px; }
        .tip { background: rgba(0, 212, 255, 0.08); border-left: 3px solid var(--accent-cyan); padding: 16px 20px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .tip-title { font-weight: 600; color: var(--accent-cyan); margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
        .tip p { margin: 0; color: var(--text-secondary); }
        .warning { background: rgba(249, 115, 22, 0.08); border-left: 3px solid var(--accent-orange); padding: 16px 20px; border-radius: 0 8px 8px 0; margin: 20px 0; }
        .warning-title { font-weight: 600; color: var(--accent-orange); margin-bottom: 6px; }
        .warning p { margin: 0; color: var(--text-secondary); }
        .steps { counter-reset: step; margin: 24px 0; }
        .step { display: flex; gap: 16px; margin-bottom: 20px; padding: 20px; background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color); }
        .step-number { counter-increment: step; width: 32px; height: 32px; flex-shrink: 0; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; color: var(--bg-primary); }
        .step-number::before { content: counter(step); }
        .step-content h4 { font-size: 1rem; font-weight: 600; margin-bottom: 6px; color: var(--text-primary); }
        .step-content p { margin: 0; color: var(--text-secondary); font-size: 0.95rem; }
        .mode-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 24px 0; }
        .mode-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; transition: all 0.3s; }
        .mode-card:hover { border-color: var(--accent-cyan); transform: translateY(-2px); }
        .mode-card.pentest { border-top: 3px solid var(--accent-cyan); }
        .mode-card.redteam { border-top: 3px solid var(--accent-orange); }
        .mode-card.fullattack { border-top: 3px solid var(--accent-red); }
        .mode-name { font-size: 1.1rem; font-weight: 600; margin-bottom: 8px; }
        .mode-card.pentest .mode-name { color: var(--accent-cyan); }
        .mode-card.redteam .mode-name { color: var(--accent-orange); }
        .mode-card.fullattack .mode-name { color: var(--accent-red); }
        .mode-desc { color: var(--text-secondary); font-size: 0.9rem; }
        .table-wrapper { overflow-x: auto; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { background: var(--bg-secondary); color: var(--accent-cyan); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        td { color: var(--text-secondary); }
        tr:hover td { background: rgba(0, 212, 255, 0.03); }

        /* Tool cards */
        .tool-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin: 24px 0; }
        .tool-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; transition: all 0.3s; }
        .tool-card:hover { border-color: var(--accent-green); transform: translateY(-2px); }
        .tool-card .icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; font-size: 1.5rem; }
        .tool-card .icon.red { background: rgba(239, 68, 68, 0.15); color: var(--accent-red); }
        .tool-card .icon.orange { background: rgba(249, 115, 22, 0.15); color: var(--accent-orange); }
        .tool-card .icon.cyan { background: rgba(0, 212, 255, 0.15); color: var(--accent-cyan); }
        .tool-card .icon.purple { background: rgba(139, 92, 246, 0.15); color: var(--accent-purple); }
        .tool-card .icon.green { background: rgba(0, 255, 136, 0.15); color: var(--accent-green); }
        .tool-card .icon.pink { background: rgba(236, 72, 153, 0.15); color: var(--accent-pink); }
        .tool-card h4 { font-size: 1.1rem; font-weight: 600; margin-bottom: 8px; color: var(--text-primary); }
        .tool-card p { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 12px; }
        .tool-card .link { color: var(--accent-cyan); font-size: 0.85rem; display: flex; align-items: center; gap: 6px; }
        .tool-card .link:hover { color: var(--accent-green); }

        /* Mobile */
        .mobile-menu-btn { display: none; position: fixed; bottom: 24px; right: 24px; z-index: 1001; width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border: none; cursor: pointer; box-shadow: 0 4px 20px rgba(0, 212, 255, 0.3); }
        .mobile-menu-btn i { font-size: 20px; color: var(--bg-primary); }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; z-index: 999; }
            .sidebar.open { transform: translateX(0); }
            .content { margin-left: 0; padding: 32px 24px; max-width: 100%; }
            .mobile-menu-btn { display: flex; align-items: center; justify-content: center; }
            .navbar-links { display: none; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-logo">
                <img src="/logo.png" alt="DeepEyes">
                <span class="logo-text">DeepEyes</span>
                <span class="badge">DOCS</span>
            </a>
            <div class="navbar-links">
                <a href="/">Home</a>
                <a href="/docs" class="active">Documentação</a>
                <a href="/chat">Chat</a>
            </div>
            <a href="/chat" class="btn-primary">Acessar Chat</a>
        </div>
    </nav>

    <div class="docs-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-group">
                <div class="sidebar-title">Início</div>
                <ul class="sidebar-links">
                    <li><a href="#introducao" class="active">Introdução</a></li>
                    <li><a href="#primeiros-passos">Primeiros Passos</a></li>
                    <li><a href="#interface">Interface</a></li>
                </ul>
            </div>
            <div class="sidebar-group">
                <div class="sidebar-title">Modos de Operação</div>
                <ul class="sidebar-links">
                    <li><a href="#modos">Visão Geral</a></li>
                    <li><a href="#pentest">Pentest Mode</a></li>
                    <li><a href="#redteam">Red Team Mode</a></li>
                    <li><a href="#fullattack">Full Attack Mode</a></li>
                </ul>
            </div>
            <div class="sidebar-group">
                <div class="sidebar-title">🔧 Ferramentas</div>
                <ul class="sidebar-links">
                    <li><a href="#tools-overview">Visão Geral</a></li>
                    <li><a href="#checklist">✓ Checklist</a></li>
                    <li><a href="#scanner">🔍 Scanner</a></li>
                    <li><a href="#reports">📄 Reports</a></li>
                    <li><a href="#terminal">⌨ Terminal no Chat</a></li>
                </ul>
            </div>
            <div class="sidebar-group">
                <div class="sidebar-title">Avançado</div>
                <ul class="sidebar-links">
                    <li><a href="#prompts">Prompts Eficientes</a></li>
                    <li><a href="#casos-uso">Casos de Uso</a></li>
                    <li><a href="#boas-praticas">Boas Práticas</a></li>
                </ul>
            </div>
        </aside>

        <!-- Content -->
        <main class="content">
            <!-- Introdução -->
            <section class="section" id="introducao">
                <h1>Documentação DeepEyes</h1>
                <p class="lead">Guia completo para utilizar a IA de segurança ofensiva mais avançada. Aprenda a maximizar seu potencial em testes de penetração e operações de red team.</p>
                
                <h2>O que é o DeepEyes?</h2>
                <p>DeepEyes é uma inteligência artificial especializada em segurança ofensiva, projetada para auxiliar profissionais de segurança em testes de penetração, operações de red team e pesquisa de vulnerabilidades.</p>
                
                <div class="card">
                    <div class="card-title"><i class="fas fa-shield-halved" style="color: var(--accent-cyan);"></i> Principais Características</div>
                    <ul>
                        <li><strong>Sem restrições artificiais</strong> — Respostas diretas sobre técnicas ofensivas</li>
                        <li><strong>3 modos de operação</strong> — Pentest, Red Team e Full Attack</li>
                        <li><strong>5 ferramentas integradas</strong> — Checklist, Scanner, Reports, Terminal e Export</li>
                        <li><strong>Conhecimento atualizado</strong> — CVEs, técnicas MITRE ATT&CK e mais</li>
                    </ul>
                </div>

                <div class="warning">
                    <div class="warning-title">⚠️ Uso Responsável</div>
                    <p>Esta ferramenta é destinada exclusivamente para profissionais de segurança autorizados. Use apenas em ambientes onde você tem permissão explícita para realizar testes.</p>
                </div>
            </section>

            <!-- Primeiros Passos -->
            <section class="section" id="primeiros-passos">
                <h2>Primeiros Passos</h2>
                <p>Comece a usar o DeepEyes em poucos minutos seguindo estes passos simples.</p>
                <div class="steps">
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Crie sua conta</h4>
                            <p>Acesse <a href="/chat">/chat</a> e clique em "Registrar". Use um email válido e senha forte (mínimo 8 caracteres, maiúscula, número e símbolo).</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Escolha seu modo</h4>
                            <p>Na sidebar, selecione o modo de operação: Pentest (azul), Red Team (laranja) ou Full Attack (vermelho).</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Explore as ferramentas</h4>
                            <p>Use os botões TOOLS na sidebar para acessar Checklist, Scanner, Reports e Terminal.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Interface -->
            <section class="section" id="interface">
                <h2>Interface do Chat</h2>
                <p>Conheça os elementos da interface para navegar com eficiência.</p>
                <h3>Barra Lateral (Sidebar)</h3>
                <ul>
                    <li><strong>ATTACK_MODE</strong> — Selecione entre Pentest, Red Team ou Full Attack</li>
                    <li><strong>NOVA SESSÃO</strong> — Inicia um chat limpo</li>
                    <li><strong>TOOLS</strong> — Acesso rápido às 6 ferramentas integradas</li>
                    <li><strong>SESSIONS</strong> — Histórico de conversas anteriores</li>
                    <li><strong>Perfil</strong> — Configurações da conta e plano</li>
                </ul>
                <h3>Área de Chat</h3>
                <ul>
                    <li><strong>Mensagens</strong> — Histórico da conversa atual com syntax highlighting</li>
                    <li><strong>Code Blocks</strong> — Código com botão de copiar</li>
                    <li><strong>Input</strong> — Campo para digitar suas perguntas</li>
                </ul>
            </section>

            <!-- Modos de Operação -->
            <section class="section" id="modos">
                <h2>Modos de Operação</h2>
                <p>O DeepEyes oferece três modos distintos, cada um otimizado para diferentes cenários.</p>
                <div class="mode-grid">
                    <div class="mode-card pentest">
                        <div class="mode-name">Pentest Mode</div>
                        <div class="mode-desc">Focado em testes de penetração metodológicos. Ideal para assessments estruturados com documentação.</div>
                    </div>
                    <div class="mode-card redteam">
                        <div class="mode-name">Red Team Mode</div>
                        <div class="mode-desc">Simulação de adversários reais. Técnicas avançadas de evasão e persistência.</div>
                    </div>
                    <div class="mode-card fullattack">
                        <div class="mode-name">Full Attack Mode</div>
                        <div class="mode-desc">Modo sem restrições. Acesso completo a todas as técnicas e payloads ofensivos.</div>
                    </div>
                </div>
            </section>

            <!-- Pentest Mode -->
            <section class="section" id="pentest">
                <h2>Pentest Mode</h2>
                <p>O modo Pentest é ideal para testes de penetração profissionais com metodologia estruturada.</p>
                <h3>Capacidades</h3>
                <ul>
                    <li>Reconhecimento e enumeração</li>
                    <li>Análise de vulnerabilidades</li>
                    <li>Exploração controlada</li>
                    <li>Geração de relatórios</li>
                </ul>
                <div class="code-block"># Exemplo de prompt
"Preciso fazer um assessment de segurança em uma aplicação web. 
Quais são os primeiros passos de reconhecimento?"</div>
            </section>

            <!-- Red Team Mode -->
            <section class="section" id="redteam">
                <h2>Red Team Mode</h2>
                <p>Simule adversários reais com técnicas avançadas de ataque e evasão.</p>
                <h3>Capacidades</h3>
                <ul>
                    <li>Técnicas MITRE ATT&CK</li>
                    <li>Evasão de EDR/AV</li>
                    <li>Movimentação lateral</li>
                    <li>Persistência avançada</li>
                    <li>C2 frameworks</li>
                </ul>
                <div class="code-block"># Exemplo de prompt
"Preciso estabelecer persistência em um ambiente Windows 
sem ser detectado pelo Defender. Quais técnicas recomendam?"</div>
            </section>

            <!-- Full Attack Mode -->
            <section class="section" id="fullattack">
                <h2>Full Attack Mode</h2>
                <p>Modo sem restrições para profissionais experientes que precisam de acesso completo.</p>
                <div class="warning">
                    <div class="warning-title">⚠️ Atenção</div>
                    <p>Este modo fornece acesso irrestrito a técnicas ofensivas. Use com extrema responsabilidade.</p>
                </div>
                <h3>Capacidades</h3>
                <ul>
                    <li>Geração de payloads customizados</li>
                    <li>Payloads ofuscados</li>
                    <li>Técnicas de bypass avançadas</li>
                    <li>Scripts completos e funcionais</li>
                </ul>
                <div class="code-block"># Exemplo de prompt
"Gere um payload de reverse shell em PowerShell 
com bypass de AMSI e ofuscação básica."</div>
            </section>

           
            <!-- Checklist -->
            <section class="section" id="checklist">
                <h2>✓ Checklist de Pentest</h2>
                <p>Checklist interativo para garantir cobertura completa durante seus testes.</p>
                
                <h3>Como acessar</h3>
                <p>Clique em <strong>CHECKLIST</strong> na sidebar ou acesse: <a href="/checklist">/checklist</a></p>
                
                <h3>Tipos de Checklist</h3>
                <ul>
                    <li><strong>🌐 Web Application</strong> — OWASP Top 10, autenticação, sessões, input validation</li>
                    <li><strong>🔌 API Security</strong> — REST/GraphQL, autenticação, rate limiting, IDOR</li>
                    <li><strong>🖧 Network</strong> — Scanning, serviços, firewall, segmentação</li>
                    <li><strong>🏢 Active Directory</strong> — Kerberos, GPO, DCSync, Golden Ticket</li>
                    <li><strong>📱 Mobile</strong> — Android/iOS, armazenamento, comunicação, reversing</li>
                </ul>

                <h3>Status dos itens</h3>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>Status</th><th>Cor</th><th>Significado</th></tr></thead>
                        <tbody>
                            <tr><td>Pendente</td><td>⚪ Cinza</td><td>Ainda não testado</td></tr>
                            <tr><td>Testado</td><td>🔵 Azul</td><td>Testado, sem vulnerabilidade</td></tr>
                            <tr><td>Vulnerável</td><td>🔴 Vermelho</td><td>Vulnerabilidade encontrada</td></tr>
                            <tr><td>N/A</td><td>⚫ Escuro</td><td>Não aplicável ao escopo</td></tr>
                        </tbody>
                    </table>
                </div>

                <h3>Exportação</h3>
                <p>Exporte seu checklist como JSON para incluir em relatórios ou continuar depois.</p>
            </section>

            <!-- Scanner -->
            <section class="section" id="scanner">
                <h2>Scanner de Vulnerabilidades</h2>
                <p>Scanner automatizado que analisa alvos em busca de vulnerabilidades comuns.</p>
                
                <h3>Como acessar</h3>
                <p>Clique em <strong>SCANNER</strong> na sidebar ou acesse: <a href="/scanner">/scanner</a></p>
                
                <h3>Como usar</h3>
                <div class="steps">
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Insira o alvo</h4>
                            <p>Digite a URL ou IP do alvo no campo de entrada (ex: https://exemplo.com)</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Inicie o scan</h4>
                            <p>Clique em "Iniciar Scan" e aguarde a análise ser concluída</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Analise os resultados</h4>
                            <p>Revise as vulnerabilidades encontradas organizadas por severidade</p>
                        </div>
                    </div>
                </div>

                <h3>O que é analisado</h3>
                <ul>
                    <li><strong>Security Headers</strong> — X-Frame-Options, CSP, HSTS, X-XSS-Protection</li>
                    <li><strong>SSL/TLS</strong> — Certificado, versão do protocolo, cipher suites</li>
                    <li><strong>Portas</strong> — Scan das portas mais comuns (80, 443, 22, 21, etc.)</li>
                    <li><strong>Tecnologias</strong> — Detecção de CMS, frameworks, servidores</li>
                </ul>

                <h3>Níveis de severidade</h3>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>Nível</th><th>Cor</th><th>Ação recomendada</th></tr></thead>
                        <tbody>
                            <tr><td>Critical</td><td>🔴 Vermelho</td><td>Corrigir imediatamente</td></tr>
                            <tr><td>High</td><td>🟠 Laranja</td><td>Corrigir em até 7 dias</td></tr>
                            <tr><td>Medium</td><td>🟡 Amarelo</td><td>Corrigir em até 30 dias</td></tr>
                            <tr><td>Low</td><td>🔵 Azul</td><td>Corrigir quando possível</td></tr>
                            <tr><td>Info</td><td>⚪ Cinza</td><td>Apenas informativo</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="warning">
                    <div class="warning-title">⚠️ Importante</div>
                    <p>Use o scanner apenas em alvos que você tem autorização para testar. Scanning não autorizado é ilegal.</p>
                </div>
            </section>

            <!-- Reports -->
            <section class="section" id="reports">
                <h2>Gerador de Relatórios</h2>
                <p>Crie relatórios profissionais de pentest com template estruturado.</p>
                
                <h3>Como acessar</h3>
                <p>Clique em <strong>REPORTS</strong> na sidebar ou acesse: <a href="/reports">/reports</a></p>
                
                <h3>Seções do relatório</h3>
                <ul>
                    <li><strong>Informações do Projeto</strong> — Nome, cliente, data, escopo</li>
                    <li><strong>Sumário Executivo</strong> — Resumo para gestão</li>
                    <li><strong>Metodologia</strong> — Abordagem utilizada</li>
                    <li><strong>Findings</strong> — Vulnerabilidades encontradas com severidade</li>
                    <li><strong>Evidências</strong> — Screenshots e provas</li>
                    <li><strong>Recomendações</strong> — Como corrigir cada vulnerabilidade</li>
                </ul>

                <h3>Formatos de exportação</h3>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>Formato</th><th>Uso recomendado</th></tr></thead>
                        <tbody>
                            <tr><td>Markdown</td><td>Edição posterior, versionamento Git</td></tr>
                            <tr><td>HTML</td><td>Visualização no navegador, compartilhamento</td></tr>
                            <tr><td>PDF</td><td>Entrega formal ao cliente</td></tr>
                        </tbody>
                    </table>
                </div>

                <h3>Adicionando Findings</h3>
                <p>Para cada vulnerabilidade encontrada, preencha:</p>
                <ul>
                    <li><strong>Título</strong> — Nome descritivo da vulnerabilidade</li>
                    <li><strong>Severidade</strong> — Critical, High, Medium, Low, Info</li>
                    <li><strong>Descrição</strong> — O que é a vulnerabilidade</li>
                    <li><strong>Impacto</strong> — Consequências se explorada</li>
                    <li><strong>Passos para reproduzir</strong> — Como você encontrou</li>
                    <li><strong>Recomendação</strong> — Como corrigir</li>
                </ul>

                <div class="tip">
                    <div class="tip-title">💡 Dica</div>
                    <p>Use o chat para pedir à IA que escreva descrições e recomendações profissionais para suas findings.</p>
                </div>
            </section>

            <!-- Terminal -->
            <section class="section" id="terminal">
                <h2>Terminal Integrado</h2>
                <p>Execute comandos reais diretamente no chat e a IA analisa os resultados automaticamente para ajudar a identificar vulnerabilidades.</p>
                
                <h3>Como usar no Chat</h3>
                <p>No chat com a IA, digite <code>$</code> seguido do comando. A IA executará o comando no servidor e analisará o resultado.</p>
                
                <div class="code-block">$ whois exemplo.com
$ nmap -sV exemplo.com
$ dig exemplo.com
$ help</div>

                <div class="tip">
                    <div class="tip-title">💡 Dica</div>
                    <p>Digite <code>$ help</code> no chat para ver todos os comandos disponíveis sem enviar para a IA.</p>
                </div>

                <h3>Comandos disponíveis</h3>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>Comando</th><th>Descrição</th><th>Exemplo</th></tr></thead>
                        <tbody>
                            <tr><td><code>whois</code></td><td>Consulta informações WHOIS de domínios</td><td><code>$ whois exemplo.com</code></td></tr>
                            <tr><td><code>dig</code></td><td>Consulta DNS detalhada</td><td><code>$ dig exemplo.com</code></td></tr>
                            <tr><td><code>nslookup</code></td><td>Consulta DNS simples</td><td><code>$ nslookup exemplo.com</code></td></tr>
                            <tr><td><code>host</code></td><td>Resolução de DNS</td><td><code>$ host exemplo.com</code></td></tr>
                            <tr><td><code>ping</code></td><td>Teste de conectividade (4 pacotes)</td><td><code>$ ping exemplo.com</code></td></tr>
                            <tr><td><code>traceroute</code></td><td>Rastreamento de rota</td><td><code>$ traceroute exemplo.com</code></td></tr>
                            <tr><td><code>curl</code></td><td>Requisições HTTP (GET apenas)</td><td><code>$ curl -I exemplo.com</code></td></tr>
                            <tr><td><code>nmap</code></td><td>Scanner de portas</td><td><code>$ nmap -sV exemplo.com</code></td></tr>
                            <tr><td><code>nikto</code></td><td>Scanner de vulnerabilidades web</td><td><code>$ nikto -h exemplo.com</code></td></tr>
                            <tr><td><code>subfinder</code></td><td>Descoberta de subdomínios</td><td><code>$ subfinder -d exemplo.com</code></td></tr>
                        </tbody>
                    </table>
                </div>

                <h3>Como funciona</h3>
                <div class="steps">
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Digite o comando</h4>
                            <p>No chat, digite <code>$ comando alvo</code> (ex: <code>$ nmap -sV exemplo.com</code>)</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Execução no servidor</h4>
                            <p>O comando é executado no servidor com whitelist de segurança e rate limiting</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Resultado no chat</h4>
                            <p>O output do comando aparece formatado no chat</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Análise da IA</h4>
                            <p>A IA analisa automaticamente o resultado e sugere próximos passos para encontrar vulnerabilidades</p>
                        </div>
                    </div>
                </div>

                <h3>Segurança</h3>
                <ul>
                    <li><strong>Whitelist</strong> — Apenas comandos seguros são permitidos</li>
                    <li><strong>Rate Limiting</strong> — 10 comandos/minuto, 60 comandos/hora</li>
                    <li><strong>Logging</strong> — Todos os comandos são auditados</li>
                    <li><strong>Timeout</strong> — Comandos têm limite de tempo para evitar travamentos</li>
                </ul>

                <h3>Terminal Standalone</h3>
                <p>Você também pode acessar o terminal separadamente em: <a href="/terminal">/terminal</a></p>

                <div class="warning">
                    <div class="warning-title">⚠️ Importante</div>
                    <p>Use o terminal apenas em alvos que você tem autorização para testar. O uso indevido é registrado e pode resultar em banimento.</p>
                </div>
            </section>

            <!-- Prompts Eficientes -->
            <section class="section" id="prompts">
                <h2>🎯 Prompts Eficientes</h2>
                <p>Aprenda a escrever prompts que extraem o máximo do DeepEyes.</p>
                
                <h3>Estrutura de um bom prompt</h3>
                <div class="card">
                    <div class="card-title"><i class="fas fa-lightbulb" style="color: var(--accent-cyan);"></i> Fórmula do Prompt Perfeito</div>
                    <p><strong>Contexto</strong> + <strong>Objetivo</strong> + <strong>Restrições</strong> + <strong>Formato desejado</strong></p>
                </div>

                <h3>Exemplos práticos</h3>
                
                <div class="tip">
                    <div class="tip-title">✅ Prompt BOM</div>
                    <p>"Estou fazendo um pentest em uma aplicação Laravel 9. Encontrei um endpoint /api/users/{id} vulnerável a IDOR. Gere um script Python que automatize a enumeração de todos os usuários e extraia emails e senhas hasheadas."</p>
                </div>

                <div class="warning">
                    <div class="warning-title">❌ Prompt RUIM</div>
                    <p>"Como hackear um site?"</p>
                </div>

                <h3>Templates de prompts</h3>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>Cenário</th><th>Template</th></tr></thead>
                        <tbody>
                            <tr>
                                <td>Reconhecimento</td>
                                <td>"Preciso fazer reconhecimento em [alvo]. Quais ferramentas e técnicas usar para [objetivo específico]?"</td>
                            </tr>
                            <tr>
                                <td>Exploração</td>
                                <td>"Encontrei [vulnerabilidade] em [tecnologia/versão]. Gere um payload funcional em [linguagem] que [objetivo]."</td>
                            </tr>
                            <tr>
                                <td>Pós-exploração</td>
                                <td>"Tenho shell em [sistema]. Preciso [escalar privilégios/persistir/mover lateralmente]. Quais técnicas usar?"</td>
                            </tr>
                            <tr>
                                <td>Evasão</td>
                                <td>"Preciso bypassar [AV/EDR/WAF]. O ambiente usa [detalhes]. Gere payload ofuscado que [objetivo]."</td>
                            </tr>
                            <tr>
                                <td>Relatório</td>
                                <td>"Escreva uma descrição profissional para a vulnerabilidade [nome] com impacto [tipo] e recomendação de correção."</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Dicas avançadas</h3>
                <ul>
                    <li><strong>Seja específico</strong> — Mencione versões, tecnologias e contexto</li>
                    <li><strong>Peça código completo</strong> — "Gere um script completo e funcional"</li>
                    <li><strong>Itere</strong> — Refine o resultado pedindo ajustes</li>
                    <li><strong>Use o modo certo</strong> — Full Attack para código ofensivo</li>
                </ul>
            </section>

            <!-- Casos de Uso -->
            <section class="section" id="casos-uso">
                <h2>Como usar?</h2>
                <p>Exemplos reais de como usar o DeepEyes em diferentes cenários.</p>

                <h3>1. Web Application Pentest</h3>
                <div class="card">
                    <div class="card-title"><i class="fas fa-globe" style="color: var(--accent-cyan);"></i> Cenário</div>
                    <p>Você foi contratado para testar uma aplicação e-commerce.</p>
                </div>
                <div class="steps">
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Use o Scanner</h4>
                            <p>Acesse /scanner e faça um scan inicial para identificar headers faltando e tecnologias.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Abra o Checklist</h4>
                            <p>Use o checklist Web Application para garantir cobertura de todos os testes OWASP.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Consulte a IA</h4>
                            <p>Se encontrar uma vulnerabilidade conhecida, peça à IA para ajudar com a exploração.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Peça ajuda à IA</h4>
                            <p>Use o chat para gerar payloads customizados e scripts de automação.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Gere o Relatório</h4>
                            <p>Use /reports para criar um relatório profissional com todas as findings.</p>
                        </div>
                    </div>
                </div>

                <h3>2. Active Directory Assessment</h3>
                <div class="card">
                    <div class="card-title"><i class="fas fa-network-wired" style="color: var(--accent-orange);"></i> Cenário</div>
                    <p>Assessment interno em ambiente corporativo com AD.</p>
                </div>
                <div class="code-block"># Prompts úteis para AD
"Quais são os primeiros passos para enumerar um AD após conseguir credenciais de usuário comum?"

"Gere um script PowerShell para enumerar SPNs e identificar contas vulneráveis a Kerberoasting"

"Tenho hash NTLM de um Domain Admin. Como usar para DCSync sem ser detectado?"</div>

                <h3>3. Red Team Engagement</h3>
                <div class="card">
                    <div class="card-title"><i class="fas fa-user-secret" style="color: var(--accent-red);"></i> Cenário</div>
                    <p>Simulação de adversário com objetivo de acessar dados sensíveis.</p>
                </div>
                <div class="code-block"># Prompts úteis para Red Team
"Preciso de um dropper em C# que baixe e execute payload em memória, bypassando Defender"

"Gere um script de phishing que clone a página de login do Office 365"

"Quais técnicas de persistência são mais difíceis de detectar em Windows 11?"</div>
            </section>

            <!-- Boas Práticas -->
            <section class="section" id="boas-praticas">
                <h2>Boas Práticas</h2>
                <p>Recomendações para usar o DeepEyes de forma ética e eficiente.</p>

                <h3>Ética e Legalidade</h3>
                <div class="warning">
                    <div class="warning-title">⚠️ Sempre obtenha autorização</div>
                    <p>Nunca teste sistemas sem permissão explícita por escrito. Isso é crime em praticamente todos os países.</p>
                </div>
                <ul>
                    <li><strong>Escopo definido</strong> — Tenha clareza sobre o que pode e não pode testar</li>
                    <li><strong>Documentação</strong> — Mantenha registros de todas as atividades</li>
                    <li><strong>Comunicação</strong> — Informe o cliente sobre descobertas críticas imediatamente</li>
                    <li><strong>Confidencialidade</strong> — Proteja os dados encontrados durante os testes</li>
                </ul>

                <h3>Eficiência</h3>
                <ul>
                    <li><strong>Use o modo correto</strong> — Pentest para assessments, Full Attack para payloads avançados</li>
                    <li><strong>Combine ferramentas</strong> — Scanner + Checklist + Chat = cobertura completa</li>
                    <li><strong>Salve sessões</strong> — Suas conversas ficam salvas para referência futura</li>
                    <li><strong>Exporte resultados</strong> — Use Reports para documentar profissionalmente</li>
                </ul>

                <h3>Segurança Operacional</h3>
                <ul>
                    <li><strong>VPN/Proxy</strong> — Proteja sua identidade durante testes</li>
                    <li><strong>VM isolada</strong> — Execute payloads em ambiente controlado</li>
                    <li><strong>Logs</strong> — Mantenha registro de suas atividades</li>
                    <li><strong>Cleanup</strong> — Remova artefatos após os testes</li>
                </ul>

                <div class="tip">
                    <div class="tip-title">💡 Lembre-se</div>
                    <p>O DeepEyes é uma ferramenta poderosa. Com grande poder vem grande responsabilidade. Use para proteger, não para prejudicar.</p>
                </div>
            </section>

            <!-- Footer da documentação -->
            <section class="section" style="text-align: center; padding: 48px 0; border-top: 1px solid var(--border-color);">
                <p style="color: var(--text-secondary); margin-bottom: 16px;">DeepEyes — IA de Segurança Ofensiva</p>
                <p style="font-size: 0.85rem; color: var(--text-secondary);">
                    <a href="/chat">Acessar Chat</a> · 
                    <a href="/scanner">Scanner</a> · 
                    <a href="/terminal">Terminal</a>
                </p>
            </section>
        </main>
    </div>

    <!-- Mobile menu button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars" id="menuIcon"></i>
    </button>

    <script>
        // Toggle sidebar mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const icon = document.getElementById('menuIcon');
            sidebar.classList.toggle('open');
            icon.className = sidebar.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
        }

        // Active link on scroll
        const sections = document.querySelectorAll('.section');
        const navLinks = document.querySelectorAll('.sidebar-links a');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (scrollY >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Smooth scroll
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href');
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
                // Close mobile menu
                if (window.innerWidth <= 1024) {
                    toggleSidebar();
                }
            });
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            if (window.innerWidth <= 1024 && 
                !sidebar.contains(e.target) && 
                !menuBtn.contains(e.target) && 
                sidebar.classList.contains('open')) {
                toggleSidebar();
            }
        });
    </script>
</body>
</html>
