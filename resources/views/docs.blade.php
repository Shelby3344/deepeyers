<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentação - DeepEyes</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --text-primary: #ffffff;
            --text-secondary: #a0a0b0;
            --border-color: #2a2a3a;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.7;
        }
        a { color: var(--accent-cyan); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        /* Navbar */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 16px 24px;
            background: rgba(10, 10, 15, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
        }
        .navbar-inner {
            max-width: 1400px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
        }
        .navbar-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .navbar-logo img { width: 32px; height: 32px; }
        .logo-text {
            font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 1.2rem;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .badge { font-size: 0.6rem; padding: 3px 8px; background: var(--accent-purple); border-radius: 4px; color: white; font-weight: 600; }
        .navbar-links { display: flex; gap: 32px; }
        .navbar-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; transition: color 0.2s; }
        .navbar-links a:hover, .navbar-links a.active { color: var(--accent-cyan); }
        .btn-primary {
            padding: 10px 24px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border-radius: 8px; color: var(--bg-primary); font-weight: 600; font-size: 0.9rem; text-decoration: none;
        }
        .btn-primary:hover { text-decoration: none; opacity: 0.9; }

        /* Layout */
        .docs-wrapper { display: flex; padding-top: 70px; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar {
            width: 280px; position: fixed; top: 70px; left: 0; bottom: 0;
            padding: 24px; overflow-y: auto; border-right: 1px solid var(--border-color);
            background: var(--bg-primary);
        }
        .sidebar-group { margin-bottom: 28px; }
        .sidebar-title {
            font-size: 0.7rem; font-weight: 600; color: var(--accent-cyan);
            text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px;
        }
        .sidebar-links { list-style: none; }
        .sidebar-links li { margin-bottom: 4px; }
        .sidebar-links a {
            display: block; padding: 8px 12px; color: var(--text-secondary);
            text-decoration: none; font-size: 0.9rem; border-radius: 6px;
            border-left: 2px solid transparent; transition: all 0.2s;
        }
        .sidebar-links a:hover { background: rgba(0, 212, 255, 0.08); color: var(--text-primary); }
        .sidebar-links a.active { background: rgba(0, 212, 255, 0.1); color: var(--accent-cyan); border-left-color: var(--accent-cyan); }
        
        /* Content */
        .content { flex: 1; margin-left: 280px; padding: 48px 64px; max-width: 920px; }
        .section { margin-bottom: 64px; scroll-margin-top: 100px; }
        .section h1 {
            font-size: 2.4rem; font-weight: 700; margin-bottom: 16px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .section h2 { font-size: 1.5rem; font-weight: 600; margin: 40px 0 16px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); padding-bottom: 10px; }
        .section h3 { font-size: 1.2rem; font-weight: 600; margin: 28px 0 12px; color: var(--text-primary); }
        .section p { color: var(--text-secondary); margin-bottom: 16px; }
        .section ul, .section ol { color: var(--text-secondary); margin: 16px 0; padding-left: 24px; }
        .section li { margin-bottom: 8px; }
        .lead { font-size: 1.15rem; color: var(--text-secondary); margin-bottom: 32px; }

        /* Code blocks */
        .code-block {
            background: var(--bg-secondary); border: 1px solid var(--border-color);
            border-radius: 8px; padding: 16px 20px; margin: 16px 0;
            font-family: 'JetBrains Mono', monospace; font-size: 0.85rem;
            overflow-x: auto; color: var(--accent-green);
        }
        code {
            background: var(--bg-secondary); padding: 2px 6px; border-radius: 4px;
            font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: var(--accent-cyan);
        }

        /* Cards */
        .card {
            background: var(--bg-card); border: 1px solid var(--border-color);
            border-radius: 12px; padding: 24px; margin: 20px 0;
        }
        .card-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 12px; color: var(--text-primary); display: flex; align-items: center; gap: 10px; }
        .card-icon { width: 24px; height: 24px; color: var(--accent-cyan); }

        /* Tips */
        .tip {
            background: rgba(0, 212, 255, 0.08); border-left: 3px solid var(--accent-cyan);
            padding: 16px 20px; border-radius: 0 8px 8px 0; margin: 20px 0;
        }
        .tip-title { font-weight: 600; color: var(--accent-cyan); margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
        .tip p { margin: 0; color: var(--text-secondary); }

        .warning {
            background: rgba(249, 115, 22, 0.08); border-left: 3px solid var(--accent-orange);
            padding: 16px 20px; border-radius: 0 8px 8px 0; margin: 20px 0;
        }
        .warning-title { font-weight: 600; color: var(--accent-orange); margin-bottom: 6px; }
        .warning p { margin: 0; color: var(--text-secondary); }

        /* Steps */
        .steps { counter-reset: step; margin: 24px 0; }
        .step {
            display: flex; gap: 16px; margin-bottom: 20px; padding: 20px;
            background: var(--bg-card); border-radius: 10px; border: 1px solid var(--border-color);
        }
        .step-number {
            counter-increment: step; width: 32px; height: 32px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.9rem; color: var(--bg-primary);
        }
        .step-number::before { content: counter(step); }
        .step-content h4 { font-size: 1rem; font-weight: 600; margin-bottom: 6px; color: var(--text-primary); }
        .step-content p { margin: 0; color: var(--text-secondary); font-size: 0.95rem; }

        /* Mode cards */
        .mode-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 24px 0; }
        .mode-card {
            background: var(--bg-card); border: 1px solid var(--border-color);
            border-radius: 12px; padding: 24px; transition: all 0.3s;
        }
        .mode-card:hover { border-color: var(--accent-cyan); transform: translateY(-2px); }
        .mode-card.pentest { border-top: 3px solid var(--accent-cyan); }
        .mode-card.redteam { border-top: 3px solid var(--accent-orange); }
        .mode-card.fullattack { border-top: 3px solid var(--accent-red); }
        .mode-name { font-size: 1.1rem; font-weight: 600; margin-bottom: 8px; }
        .mode-card.pentest .mode-name { color: var(--accent-cyan); }
        .mode-card.redteam .mode-name { color: var(--accent-orange); }
        .mode-card.fullattack .mode-name { color: var(--accent-red); }
        .mode-desc { color: var(--text-secondary); font-size: 0.9rem; }

        /* Table */
        .table-wrapper { overflow-x: auto; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { background: var(--bg-secondary); color: var(--accent-cyan); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }
        td { color: var(--text-secondary); }
        tr:hover td { background: rgba(0, 212, 255, 0.03); }

        /* Mobile sidebar toggle */
        .mobile-menu-btn {
            display: none; position: fixed; bottom: 24px; right: 24px; z-index: 1001;
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border: none; cursor: pointer; box-shadow: 0 4px 20px rgba(0, 212, 255, 0.3);
        }
        .mobile-menu-btn svg { width: 24px; height: 24px; color: var(--bg-primary); }

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
                <div class="sidebar-title">Ferramentas</div>
                <ul class="sidebar-links">
                    <li><a href="#templates">Templates</a></li>
                    <li><a href="#payloads">Payloads</a></li>
                    <li><a href="#nmap">Nmap Generator</a></li>
                    <li><a href="#wordlists">Wordlists</a></li>
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
                    <div class="card-title">
                        <svg class="card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Principais Características
                    </div>
                    <ul>
                        <li><strong>Sem restrições artificiais</strong> — Respostas diretas sobre técnicas ofensivas</li>
                        <li><strong>3 modos de operação</strong> — Pentest, Red Team e Full Attack</li>
                        <li><strong>Ferramentas integradas</strong> — Templates, payloads, Nmap e wordlists</li>
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
                            <p>Acesse <a href="/chat">/chat</a> e clique em "Criar Conta". Preencha seu email e senha para começar.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Escolha seu modo</h4>
                            <p>Selecione o modo de operação adequado para sua tarefa: Pentest, Red Team ou Full Attack.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Faça sua primeira pergunta</h4>
                            <p>Digite sua pergunta no chat. Seja específico sobre o contexto e objetivo para obter melhores respostas.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number"></div>
                        <div class="step-content">
                            <h4>Explore as ferramentas</h4>
                            <p>Use os botões de ferramentas para acessar templates, payloads, gerador de comandos Nmap e wordlists.</p>
                        </div>
                    </div>
                </div>

                <div class="tip">
                    <div class="tip-title">💡 Dica</div>
                    <p>Novos usuários recebem automaticamente o plano Ghost com acesso a recursos básicos. Faça upgrade para desbloquear todos os modos e ferramentas.</p>
                </div>
            </section>

            <!-- Interface -->
            <section class="section" id="interface">
                <h2>Interface do Chat</h2>
                <p>Conheça os elementos da interface para navegar com eficiência.</p>

                <h3>Barra Lateral (Sidebar)</h3>
                <ul>
                    <li><strong>Nova Conversa</strong> — Inicia um chat limpo</li>
                    <li><strong>Histórico</strong> — Lista de conversas anteriores</li>
                    <li><strong>Seletor de Modo</strong> — Alterna entre Pentest, Red Team e Full Attack</li>
                    <li><strong>Perfil</strong> — Configurações da conta e plano</li>
                </ul>

                <h3>Área de Chat</h3>
                <ul>
                    <li><strong>Mensagens</strong> — Histórico da conversa atual</li>
                    <li><strong>Code Blocks</strong> — Código com syntax highlighting e botão de copiar</li>
                    <li><strong>Input</strong> — Campo para digitar suas perguntas</li>
                </ul>

                <h3>Barra de Ferramentas</h3>
                <p>Acesso rápido às ferramentas integradas através dos botões na parte inferior:</p>
                <ul>
                    <li><code>Templates</code> — Prompts pré-configurados para tarefas comuns</li>
                    <li><code>Payloads</code> — Biblioteca de payloads organizados por categoria</li>
                    <li><code>Nmap</code> — Gerador visual de comandos Nmap</li>
                    <li><code>Wordlists</code> — Acesso a wordlists populares</li>
                </ul>
            </section>

            <!-- Modos de Operação -->
            <section class="section" id="modos">
                <h2>Modos de Operação</h2>
                <p>O DeepEyes oferece três modos distintos, cada um otimizado para diferentes cenários de segurança ofensiva.</p>

                <div class="mode-grid">
                    <div class="mode-card pentest">
                        <div class="mode-name">🔍 Pentest Mode</div>
                        <div class="mode-desc">Focado em testes de penetração metodológicos. Ideal para assessments estruturados com documentação.</div>
                    </div>
                    <div class="mode-card redteam">
                        <div class="mode-name">🎯 Red Team Mode</div>
                        <div class="mode-desc">Simulação de adversários reais. Técnicas avançadas de evasão e persistência.</div>
                    </div>
                    <div class="mode-card fullattack">
                        <div class="mode-name">💀 Full Attack Mode</div>
                        <div class="mode-desc">Modo sem restrições. Acesso completo a todas as técnicas e payloads ofensivos.</div>
                    </div>
                </div>
            </section>

            <!-- Pentest Mode -->
            <section class="section" id="pentest">
                <h2>Pentest Mode</h2>
                <p>O modo Pentest é ideal para testes de penetração profissionais com metodologia estruturada.</p>

                <h3>Quando usar</h3>
                <ul>
                    <li>Assessments de segurança contratados</li>
                    <li>Testes de aplicações web</li>
                    <li>Avaliação de infraestrutura</li>
                    <li>Compliance e auditorias</li>
                </ul>

                <h3>Capacidades</h3>
                <ul>
                    <li>Reconhecimento e enumeração</li>
                    <li>Análise de vulnerabilidades</li>
                    <li>Exploração controlada</li>
                    <li>Geração de relatórios</li>
                    <li>Recomendações de remediação</li>
                </ul>

                <div class="code-block">
# Exemplo de prompt para Pentest Mode
"Preciso fazer um assessment de segurança em uma aplicação web 
em exemplo.com. Quais são os primeiros passos de reconhecimento?"
                </div>
            </section>

            <!-- Red Team Mode -->
            <section class="section" id="redteam">
                <h2>Red Team Mode</h2>
                <p>Simule adversários reais com técnicas avançadas de ataque e evasão.</p>

                <h3>Quando usar</h3>
                <ul>
                    <li>Simulações de APT</li>
                    <li>Testes de detecção do Blue Team</li>
                    <li>Exercícios de Purple Team</li>
                    <li>Avaliação de controles de segurança</li>
                </ul>

                <h3>Capacidades</h3>
                <ul>
                    <li>Técnicas MITRE ATT&CK</li>
                    <li>Evasão de EDR/AV</li>
                    <li>Movimentação lateral</li>
                    <li>Persistência avançada</li>
                    <li>Exfiltração de dados</li>
                    <li>C2 frameworks</li>
                </ul>

                <div class="code-block">
# Exemplo de prompt para Red Team Mode
"Preciso estabelecer persistência em um ambiente Windows 
sem ser detectado pelo Defender. Quais técnicas recomendam?"
                </div>
            </section>

            <!-- Full Attack Mode -->
            <section class="section" id="fullattack">
                <h2>Full Attack Mode</h2>
                <p>Modo sem restrições para profissionais experientes que precisam de acesso completo.</p>

                <div class="warning">
                    <div class="warning-title">⚠️ Atenção</div>
                    <p>Este modo fornece acesso irrestrito a técnicas ofensivas. Use com extrema responsabilidade e apenas em ambientes autorizados.</p>
                </div>

                <h3>Quando usar</h3>
                <ul>
                    <li>Pesquisa de vulnerabilidades</li>
                    <li>Desenvolvimento de exploits</li>
                    <li>CTFs e laboratórios</li>
                    <li>Testes em ambientes isolados</li>
                </ul>

                <h3>Capacidades</h3>
                <ul>
                    <li>Geração de exploits customizados</li>
                    <li>Payloads ofuscados</li>
                    <li>Técnicas de bypass avançadas</li>
                    <li>Zero-day research</li>
                    <li>Malware analysis</li>
                </ul>

                <div class="code-block">
# Exemplo de prompt para Full Attack Mode
"Gere um payload de reverse shell em PowerShell 
com bypass de AMSI e ofuscação básica."
                </div>
            </section>

            <!-- Templates -->
            <section class="section" id="templates">
                <h2>Templates</h2>
                <p>Templates são prompts pré-configurados para tarefas comuns de segurança ofensiva.</p>

                <h3>Como usar</h3>
                <ol>
                    <li>Clique no botão <code>Templates</code> na barra de ferramentas</li>
                    <li>Navegue pelas categorias disponíveis</li>
                    <li>Clique em um template para inserir no chat</li>
                    <li>Personalize os parâmetros conforme necessário</li>
                </ol>

                <h3>Categorias disponíveis</h3>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Categoria</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Reconhecimento</td>
                                <td>OSINT, enumeração de subdomínios, fingerprinting</td>
                            </tr>
                            <tr>
                                <td>Web</td>
                                <td>SQLi, XSS, SSRF, LFI/RFI, autenticação</td>
                            </tr>
                            <tr>
                                <td>Network</td>
                                <td>Scanning, pivoting, MitM</td>
                            </tr>
                            <tr>
                                <td>Windows</td>
                                <td>PrivEsc, AD attacks, Kerberos</td>
                            </tr>
                            <tr>
                                <td>Linux</td>
                                <td>PrivEsc, containers, kernel exploits</td>
                            </tr>
                            <tr>
                                <td>Mobile</td>
                                <td>Android, iOS, API testing</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Payloads -->
            <section class="section" id="payloads">
                <h2>Payloads</h2>
                <p>Biblioteca de payloads prontos para uso, organizados por tipo e plataforma.</p>

                <h3>Como usar</h3>
                <ol>
                    <li>Clique no botão <code>Payloads</code></li>
                    <li>Selecione a categoria desejada</li>
                    <li>Escolha o payload específico</li>
                    <li>Copie e adapte para seu cenário</li>
                </ol>

                <h3>Tipos de Payloads</h3>
                <ul>
                    <li><strong>Reverse Shells</strong> — Bash, Python, PowerShell, PHP, etc.</li>
                    <li><strong>Web Shells</strong> — PHP, ASP, JSP</li>
                    <li><strong>SQLi</strong> — Union, blind, time-based</li>
                    <li><strong>XSS</strong> — Reflected, stored, DOM-based</li>
                    <li><strong>Serialization</strong> — Java, PHP, .NET</li>
                </ul>

                <div class="tip">
                    <div class="tip-title">💡 Dica</div>
                    <p>Sempre adapte os payloads para seu ambiente específico. Substitua IPs, portas e parâmetros conforme necessário.</p>
                </div>
            </section>

            <!-- Nmap Generator -->
            <section class="section" id="nmap">
                <h2>Nmap Generator</h2>
                <p>Gerador visual de comandos Nmap para diferentes cenários de scanning.</p>

                <h3>Como usar</h3>
                <ol>
                    <li>Clique no botão <code>Nmap</code></li>
                    <li>Insira o alvo (IP, range ou hostname)</li>
                    <li>Selecione o tipo de scan desejado</li>
                    <li>Configure opções adicionais</li>
                    <li>Copie o comando gerado</li>
                </ol>

                <h3>Tipos de Scan</h3>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Flag</th>
                                <th>Uso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TCP SYN</td>
                                <td><code>-sS</code></td>
                                <td>Scan rápido e stealth (requer root)</td>
                            </tr>
                            <tr>
                                <td>TCP Connect</td>
                                <td><code>-sT</code></td>
                                <td>Scan completo sem privilégios</td>
                            </tr>
                            <tr>
                                <td>UDP</td>
                                <td><code>-sU</code></td>
                                <td>Descoberta de serviços UDP</td>
                            </tr>
                            <tr>
                                <td>Version</td>
                                <td><code>-sV</code></td>
                                <td>Detecção de versões de serviços</td>
                            </tr>
                            <tr>
                                <td>OS Detection</td>
                                <td><code>-O</code></td>
                                <td>Identificação do sistema operacional</td>
                            </tr>
                            <tr>
                                <td>Aggressive</td>
                                <td><code>-A</code></td>
                                <td>Scan completo (OS, version, scripts)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="code-block">
# Exemplo de comando gerado
nmap -sS -sV -O -p- --script=vuln -oN scan_results.txt 192.168.1.0/24
                </div>
            </section>

            <!-- Wordlists -->
            <section class="section" id="wordlists">
                <h2>Wordlists</h2>
                <p>Acesso rápido às wordlists mais populares para brute force e fuzzing.</p>

                <h3>Wordlists Disponíveis</h3>
                <ul>
                    <li><strong>SecLists</strong> — Coleção completa de wordlists para segurança</li>
                    <li><strong>RockYou</strong> — Senhas vazadas mais comuns</li>
                    <li><strong>Directory Lists</strong> — Para fuzzing de diretórios web</li>
                    <li><strong>Usernames</strong> — Listas de nomes de usuário comuns</li>
                    <li><strong>Subdomains</strong> — Para enumeração de subdomínios</li>
                </ul>

                <h3>Uso com ferramentas</h3>
                <div class="code-block">
# Fuzzing de diretórios com ffuf
ffuf -u https://target.com/FUZZ -w /path/to/wordlist.txt

# Brute force com Hydra
hydra -L users.txt -P passwords.txt ssh://target.com

# Enumeração de subdomínios
gobuster dns -d target.com -w subdomains.txt
                </div>
            </section>

            <!-- Prompts Eficientes -->
            <section class="section" id="prompts">
                <h2>Prompts Eficientes</h2>
                <p>Aprenda a formular perguntas que geram respostas mais úteis e precisas.</p>

                <h3>Estrutura de um bom prompt</h3>
                <ol>
                    <li><strong>Contexto</strong> — Descreva o cenário e ambiente</li>
                    <li><strong>Objetivo</strong> — O que você quer alcançar</li>
                    <li><strong>Restrições</strong> — Limitações ou requisitos específicos</li>
                    <li><strong>Formato</strong> — Como você quer a resposta</li>
                </ol>

                <h3>Exemplos</h3>
                <div class="card">
                    <div class="card-title">❌ Prompt ruim</div>
                    <p style="color: var(--accent-red);">"Como hackear um site?"</p>
                </div>

                <div class="card">
                    <div class="card-title">✅ Prompt bom</div>
                    <p style="color: var(--accent-green);">"Estou fazendo um pentest autorizado em uma aplicação PHP com MySQL. Encontrei um campo de login que parece vulnerável a SQLi. Quais payloads devo testar primeiro para confirmar a vulnerabilidade? Preciso de exemplos para bypass de autenticação."</p>
                </div>

                <div class="tip">
                    <div class="tip-title">💡 Dica</div>
                    <p>Quanto mais contexto você fornecer, mais específica e útil será a resposta. Inclua tecnologias, versões e objetivos claros.</p>
                </div>
            </section>

            <!-- Casos de Uso -->
            <section class="section" id="casos-uso">
                <h2>Casos de Uso</h2>
                <p>Exemplos práticos de como usar o DeepEyes em diferentes cenários.</p>

                <h3>Web Application Pentest</h3>
                <div class="code-block">
"Estou testando uma aplicação web em Laravel 8. Encontrei um endpoint 
/api/users/{id} que retorna dados de usuários. Como posso testar 
por IDOR e quais outros testes de API devo realizar?"
                </div>

                <h3>Active Directory Assessment</h3>
                <div class="code-block">
"Consegui acesso inicial a uma máquina Windows em um domínio AD. 
Tenho credenciais de um usuário comum. Quais são os próximos 
passos para enumerar o domínio e escalar privilégios?"
                </div>

                <h3>Network Penetration Test</h3>
                <div class="code-block">
"Preciso fazer um pentest interno em uma rede /24. Quais ferramentas 
e técnicas devo usar para descoberta de hosts, enumeração de 
serviços e identificação de vulnerabilidades?"
                </div>

                <h3>Cloud Security Assessment</h3>
                <div class="code-block">
"Estou avaliando a segurança de uma infraestrutura AWS. Tenho 
credenciais de um usuário IAM com permissões limitadas. Como 
posso enumerar recursos e identificar misconfigurations?"
                </div>
            </section>

            <!-- Boas Práticas -->
            <section class="section" id="boas-praticas">
                <h2>Boas Práticas</h2>
                <p>Diretrizes para uso ético e eficiente do DeepEyes.</p>

                <h3>Ética e Legalidade</h3>
                <ul>
                    <li>Sempre obtenha autorização por escrito antes de testar</li>
                    <li>Documente todas as atividades realizadas</li>
                    <li>Respeite o escopo definido no contrato</li>
                    <li>Reporte vulnerabilidades de forma responsável</li>
                    <li>Proteja dados sensíveis encontrados durante testes</li>
                </ul>

                <h3>Segurança Operacional</h3>
                <ul>
                    <li>Use VPN ou ambiente isolado para testes</li>
                    <li>Não armazene credenciais em texto claro</li>
                    <li>Limpe artefatos após conclusão dos testes</li>
                    <li>Mantenha logs de todas as atividades</li>
                </ul>

                <h3>Maximizando Resultados</h3>
                <ul>
                    <li>Comece com reconhecimento passivo</li>
                    <li>Documente findings em tempo real</li>
                    <li>Valide vulnerabilidades antes de reportar</li>
                    <li>Use o modo apropriado para cada tarefa</li>
                    <li>Combine ferramentas integradas com prompts customizados</li>
                </ul>

                <div class="tip">
                    <div class="tip-title">💡 Lembre-se</div>
                    <p>O DeepEyes é uma ferramenta poderosa. Com grande poder vem grande responsabilidade. Use sempre de forma ética e legal.</p>
                </div>
            </section>

        </main>
    </div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <script>
        // Toggle mobile sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Scroll spy for sidebar
        const sections = document.querySelectorAll('.section');
        const navLinks = document.querySelectorAll('.sidebar-links a');

        function updateActiveLink() {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 120;
                if (window.scrollY >= sectionTop) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', updateActiveLink);
        updateActiveLink();

        // Smooth scroll for sidebar links
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.scrollIntoView({ behavior: 'smooth' });
                    // Close mobile sidebar
                    document.getElementById('sidebar').classList.remove('open');
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
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>
