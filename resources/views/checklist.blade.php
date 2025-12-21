<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist de Pentest - DeepEyes</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>html:not(.auth-checked) body { visibility: hidden; }</style>
    <script>
        // Verificação de autenticação - redireciona se não estiver logado
        if (!localStorage.getItem('token')) {
            window.location.replace('/?login=required');
        } else {
            document.documentElement.classList.add('auth-checked');
        }
    </script>
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
            --accent-yellow: #eab308;
            --text-primary: #ffffff;
            --text-secondary: #a0a0b0;
            --border-color: #2a2a3a;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; }
        .mono { font-family: 'JetBrains Mono', monospace; }

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

        .main { padding: 100px 24px 40px; max-width: 1200px; margin: 0 auto; }
        .page-header { margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; }
        .page-title { font-size: 2rem; font-weight: 700; margin-bottom: 8px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .page-subtitle { color: var(--text-secondary); font-size: 1rem; }
        .header-actions { display: flex; gap: 12px; }
        .header-btn { padding: 10px 20px; border-radius: 8px; font-size: 0.9rem; font-weight: 500; cursor: pointer; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary); transition: all 0.2s; }
        .header-btn:hover { border-color: var(--accent-cyan); }
        .header-btn.primary { background: var(--accent-cyan); border-color: var(--accent-cyan); color: var(--bg-primary); }

        /* Type Selector */
        .type-selector { display: flex; gap: 12px; margin-bottom: 32px; flex-wrap: wrap; }
        .type-btn { padding: 12px 24px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-secondary); font-size: 0.9rem; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px; }
        .type-btn:hover { border-color: var(--accent-cyan); color: var(--text-primary); }
        .type-btn.active { background: var(--accent-cyan); border-color: var(--accent-cyan); color: var(--bg-primary); font-weight: 600; }
        .type-btn svg { width: 18px; height: 18px; }

        /* Progress */
        .progress-bar { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 20px; margin-bottom: 32px; }
        .progress-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
        .progress-title { font-weight: 600; }
        .progress-stats { display: flex; gap: 20px; font-size: 0.85rem; }
        .stat { display: flex; align-items: center; gap: 6px; }
        .stat-dot { width: 8px; height: 8px; border-radius: 50%; }
        .stat-dot.tested { background: var(--accent-cyan); }
        .stat-dot.vuln { background: var(--accent-red); }
        .stat-dot.ok { background: var(--accent-green); }
        .progress-track { height: 8px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden; display: flex; }
        .progress-fill { height: 100%; transition: width 0.3s; }
        .progress-fill.tested { background: var(--accent-cyan); }
        .progress-fill.vuln { background: var(--accent-red); }
        .progress-fill.ok { background: var(--accent-green); }

        /* Checklist */
        .checklist-section { margin-bottom: 24px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px 10px 0 0; cursor: pointer; }
        .section-title { font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .section-count { font-size: 0.8rem; color: var(--text-secondary); background: var(--bg-secondary); padding: 4px 10px; border-radius: 4px; }
        .section-toggle { color: var(--text-secondary); transition: transform 0.2s; }
        .section-header.collapsed .section-toggle { transform: rotate(-90deg); }
        
        .checklist-items { border: 1px solid var(--border-color); border-top: none; border-radius: 0 0 10px 10px; overflow: hidden; }
        .checklist-items.collapsed { display: none; }
        
        .checklist-item { display: flex; align-items: center; gap: 16px; padding: 14px 20px; background: var(--bg-secondary); border-bottom: 1px solid var(--border-color); transition: background 0.2s; }
        .checklist-item:last-child { border-bottom: none; }
        .checklist-item:hover { background: var(--bg-card); }
        
        .item-checkbox { width: 20px; height: 20px; border: 2px solid var(--border-color); border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0; }
        .item-checkbox.tested { background: var(--accent-cyan); border-color: var(--accent-cyan); }
        .item-checkbox.vuln { background: var(--accent-red); border-color: var(--accent-red); }
        .item-checkbox.ok { background: var(--accent-green); border-color: var(--accent-green); }
        .item-checkbox svg { width: 12px; height: 12px; color: white; display: none; }
        .item-checkbox.tested svg, .item-checkbox.vuln svg, .item-checkbox.ok svg { display: block; }
        
        .item-content { flex: 1; }
        .item-title { font-size: 0.95rem; margin-bottom: 2px; }
        .item-desc { font-size: 0.8rem; color: var(--text-secondary); }
        
        .item-status { display: flex; gap: 6px; }
        .status-btn { padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; cursor: pointer; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-secondary); transition: all 0.2s; }
        .status-btn:hover { border-color: var(--text-secondary); }
        .status-btn.active.tested { background: var(--accent-cyan); border-color: var(--accent-cyan); color: var(--bg-primary); }
        .status-btn.active.vuln { background: var(--accent-red); border-color: var(--accent-red); color: white; }
        .status-btn.active.ok { background: var(--accent-green); border-color: var(--accent-green); color: var(--bg-primary); }

        .item-notes { margin-top: 8px; }
        .item-notes textarea { width: 100%; padding: 8px 12px; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 0.85rem; resize: vertical; min-height: 60px; font-family: 'JetBrains Mono', monospace; }
        .item-notes textarea:focus { outline: none; border-color: var(--accent-cyan); }

        @media (max-width: 768px) {
            .navbar-links { display: none; }
            .page-header { flex-direction: column; }
            .type-selector { flex-direction: column; }
            .item-status { flex-direction: column; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-logo">
                <img src="/logo.png" alt="DeepEyes">
                <span class="logo-text">DeepEyes</span>
                <span class="badge">CHECKLIST</span>
            </a>
            <div class="navbar-links">
                <a href="/">Home</a>
                <a href="/docs">Docs</a>
                <a href="/checklist" class="active">Checklist</a>
                <a href="/chat">Chat</a>
            </div>
            <a href="/chat" class="btn-primary">Acessar Chat</a>
        </div>
    </nav>

    <main class="main">
        <div class="page-header">
            <div>
                <h1 class="page-title">Checklist de Pentest</h1>
                <p class="page-subtitle">Acompanhe o progresso do seu teste de penetração</p>
            </div>
            <div class="header-actions">
                <button class="header-btn" onclick="resetChecklist()"><i class="fas fa-rotate-right"></i> Resetar</button>
                <button class="header-btn" onclick="exportChecklist()"><i class="fas fa-download"></i> Exportar</button>
                <button class="header-btn primary" onclick="exportReport()"><i class="fas fa-file-lines"></i> Gerar Relatório</button>
            </div>
        </div>

        <div class="type-selector">
            <button class="type-btn active" data-type="web">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                Web Application
            </button>
            <button class="type-btn" data-type="api">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
                API
            </button>
            <button class="type-btn" data-type="network">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><path d="M6 6h.01M6 18h.01"/></svg>
                Network
            </button>
            <button class="type-btn" data-type="ad">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Active Directory
            </button>
            <button class="type-btn" data-type="mobile">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M12 18h.01"/></svg>
                Mobile
            </button>
        </div>

        <div class="progress-bar">
            <div class="progress-header">
                <span class="progress-title">Progresso Geral</span>
                <div class="progress-stats">
                    <span class="stat"><span class="stat-dot tested"></span> <span id="testedCount">0</span> Testados</span>
                    <span class="stat"><span class="stat-dot vuln"></span> <span id="vulnCount">0</span> Vulneráveis</span>
                    <span class="stat"><span class="stat-dot ok"></span> <span id="okCount">0</span> OK</span>
                </div>
            </div>
            <div class="progress-track">
                <div class="progress-fill tested" id="testedBar" style="width: 0%"></div>
                <div class="progress-fill vuln" id="vulnBar" style="width: 0%"></div>
                <div class="progress-fill ok" id="okBar" style="width: 0%"></div>
            </div>
        </div>

        <div id="checklistContainer"></div>
    </main>

    <script>
        const checklists = {
            web: [
                { section: "Reconhecimento", items: [
                    { id: "w1", title: "Enumeração de subdomínios", desc: "Subfinder, Amass, crt.sh" },
                    { id: "w2", title: "Descoberta de diretórios", desc: "Gobuster, Feroxbuster, Dirsearch" },
                    { id: "w3", title: "Identificação de tecnologias", desc: "Wappalyzer, WhatWeb" },
                    { id: "w4", title: "Análise de headers HTTP", desc: "Security headers, cookies" },
                    { id: "w5", title: "Robots.txt e sitemap.xml", desc: "Arquivos de configuração expostos" }
                ]},
                { section: "Autenticação", items: [
                    { id: "w6", title: "Brute force de login", desc: "Hydra, Burp Intruder" },
                    { id: "w7", title: "Bypass de autenticação", desc: "SQL injection, default creds" },
                    { id: "w8", title: "Enumeração de usuários", desc: "Timing attacks, error messages" },
                    { id: "w9", title: "Password reset flaws", desc: "Token prediction, host header" },
                    { id: "w10", title: "Session management", desc: "Session fixation, hijacking" }
                ]},
                { section: "Injeções", items: [
                    { id: "w11", title: "SQL Injection", desc: "Union, Blind, Time-based" },
                    { id: "w12", title: "XSS (Cross-Site Scripting)", desc: "Reflected, Stored, DOM" },
                    { id: "w13", title: "Command Injection", desc: "OS command execution" },
                    { id: "w14", title: "SSTI (Template Injection)", desc: "Jinja2, Twig, Freemarker" },
                    { id: "w15", title: "XXE (XML External Entity)", desc: "File read, SSRF via XXE" },
                    { id: "w16", title: "LDAP Injection", desc: "Directory service attacks" }
                ]},
                { section: "Controle de Acesso", items: [
                    { id: "w17", title: "IDOR", desc: "Insecure Direct Object Reference" },
                    { id: "w18", title: "Privilege Escalation", desc: "Horizontal e vertical" },
                    { id: "w19", title: "Path Traversal / LFI", desc: "File inclusion attacks" },
                    { id: "w20", title: "SSRF", desc: "Server-Side Request Forgery" }
                ]},
                { section: "Outros", items: [
                    { id: "w21", title: "CSRF", desc: "Cross-Site Request Forgery" },
                    { id: "w22", title: "File Upload", desc: "Unrestricted file upload" },
                    { id: "w23", title: "Deserialization", desc: "Insecure deserialization" },
                    { id: "w24", title: "JWT Attacks", desc: "None algorithm, weak secret" },
                    { id: "w25", title: "WebSocket Security", desc: "CSWSH, injection" }
                ]}
            ],
            api: [
                { section: "Reconhecimento", items: [
                    { id: "a1", title: "Documentação da API", desc: "Swagger, OpenAPI, GraphQL introspection" },
                    { id: "a2", title: "Enumeração de endpoints", desc: "Fuzzing, wordlists" },
                    { id: "a3", title: "Versionamento", desc: "APIs antigas expostas" }
                ]},
                { section: "Autenticação", items: [
                    { id: "a4", title: "API Key exposure", desc: "Keys em código, logs" },
                    { id: "a5", title: "JWT vulnerabilities", desc: "None alg, weak secret" },
                    { id: "a6", title: "OAuth flaws", desc: "Redirect URI, state param" },
                    { id: "a7", title: "Rate limiting", desc: "Brute force protection" }
                ]},
                { section: "Autorização", items: [
                    { id: "a8", title: "BOLA/IDOR", desc: "Broken Object Level Auth" },
                    { id: "a9", title: "BFLA", desc: "Broken Function Level Auth" },
                    { id: "a10", title: "Mass Assignment", desc: "Parameter pollution" }
                ]},
                { section: "Injeções", items: [
                    { id: "a11", title: "SQL Injection", desc: "Via parâmetros da API" },
                    { id: "a12", title: "NoSQL Injection", desc: "MongoDB, CouchDB" },
                    { id: "a13", title: "GraphQL Injection", desc: "Query manipulation" }
                ]}
            ],
            network: [
                { section: "Descoberta", items: [
                    { id: "n1", title: "Host discovery", desc: "Nmap, Masscan" },
                    { id: "n2", title: "Port scanning", desc: "TCP/UDP scan" },
                    { id: "n3", title: "Service enumeration", desc: "Version detection" },
                    { id: "n4", title: "OS fingerprinting", desc: "Sistema operacional" }
                ]},
                { section: "Serviços", items: [
                    { id: "n5", title: "SMB enumeration", desc: "Shares, users, null session" },
                    { id: "n6", title: "FTP anonymous", desc: "Acesso anônimo" },
                    { id: "n7", title: "SSH vulnerabilities", desc: "Weak ciphers, keys" },
                    { id: "n8", title: "SNMP enumeration", desc: "Community strings" },
                    { id: "n9", title: "DNS zone transfer", desc: "AXFR" }
                ]},
                { section: "Ataques", items: [
                    { id: "n10", title: "ARP Spoofing", desc: "MitM na rede local" },
                    { id: "n11", title: "LLMNR/NBT-NS Poisoning", desc: "Responder" },
                    { id: "n12", title: "SMB Relay", desc: "NTLM relay attacks" }
                ]}
            ],
            ad: [
                { section: "Enumeração", items: [
                    { id: "ad1", title: "Domain enumeration", desc: "BloodHound, PowerView" },
                    { id: "ad2", title: "User enumeration", desc: "Kerbrute, LDAP" },
                    { id: "ad3", title: "Group Policy", desc: "GPP passwords" },
                    { id: "ad4", title: "ACL analysis", desc: "Permissões perigosas" }
                ]},
                { section: "Credential Attacks", items: [
                    { id: "ad5", title: "Kerberoasting", desc: "Service account hashes" },
                    { id: "ad6", title: "AS-REP Roasting", desc: "No preauth users" },
                    { id: "ad7", title: "Password Spraying", desc: "Common passwords" },
                    { id: "ad8", title: "LSASS dump", desc: "Mimikatz, comsvcs" }
                ]},
                { section: "Privilege Escalation", items: [
                    { id: "ad9", title: "DCSync", desc: "Replicação de hashes" },
                    { id: "ad10", title: "Golden Ticket", desc: "KRBTGT hash" },
                    { id: "ad11", title: "Silver Ticket", desc: "Service ticket forgery" },
                    { id: "ad12", title: "Delegation abuse", desc: "Constrained/Unconstrained" }
                ]}
            ],
            mobile: [
                { section: "Análise Estática", items: [
                    { id: "m1", title: "Decompilação", desc: "APKTool, jadx, Hopper" },
                    { id: "m2", title: "Hardcoded secrets", desc: "API keys, credentials" },
                    { id: "m3", title: "Insecure storage", desc: "SharedPrefs, Keychain" }
                ]},
                { section: "Análise Dinâmica", items: [
                    { id: "m4", title: "SSL Pinning bypass", desc: "Frida, Objection" },
                    { id: "m5", title: "Root/Jailbreak detection", desc: "Bypass checks" },
                    { id: "m6", title: "Traffic interception", desc: "Burp, mitmproxy" }
                ]},
                { section: "Vulnerabilidades", items: [
                    { id: "m7", title: "Deep link abuse", desc: "Intent/URL scheme" },
                    { id: "m8", title: "WebView vulnerabilities", desc: "JavaScript interface" },
                    { id: "m9", title: "Biometric bypass", desc: "Fingerprint/FaceID" }
                ]}
            ]
        };

        let currentType = 'web';
        let itemStates = JSON.parse(localStorage.getItem('checklistStates') || '{}');
        let itemNotes = JSON.parse(localStorage.getItem('checklistNotes') || '{}');

        function renderChecklist() {
            const container = document.getElementById('checklistContainer');
            const data = checklists[currentType];
            
            container.innerHTML = data.map(section => `
                <div class="checklist-section">
                    <div class="section-header" onclick="toggleSection(this)">
                        <span class="section-title">
                            ${section.section}
                            <span class="section-count">${section.items.length} itens</span>
                        </span>
                        <svg class="section-toggle" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                    <div class="checklist-items">
                        ${section.items.map(item => `
                            <div class="checklist-item">
                                <div class="item-checkbox ${itemStates[item.id] || ''}" onclick="cycleState('${item.id}', this)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                                </div>
                                <div class="item-content">
                                    <div class="item-title">${item.title}</div>
                                    <div class="item-desc">${item.desc}</div>
                                    <div class="item-notes">
                                        <textarea placeholder="Notas..." onchange="saveNote('${item.id}', this.value)">${itemNotes[item.id] || ''}</textarea>
                                    </div>
                                </div>
                                <div class="item-status">
                                    <button class="status-btn ${itemStates[item.id] === 'tested' ? 'active tested' : ''}" onclick="setState('${item.id}', 'tested')">Testado</button>
                                    <button class="status-btn ${itemStates[item.id] === 'vuln' ? 'active vuln' : ''}" onclick="setState('${item.id}', 'vuln')">Vulnerável</button>
                                    <button class="status-btn ${itemStates[item.id] === 'ok' ? 'active ok' : ''}" onclick="setState('${item.id}', 'ok')">OK</button>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `).join('');
            
            updateProgress();
        }

        function toggleSection(header) {
            header.classList.toggle('collapsed');
            header.nextElementSibling.classList.toggle('collapsed');
        }

        function cycleState(id, checkbox) {
            const states = ['', 'tested', 'vuln', 'ok'];
            const current = itemStates[id] || '';
            const nextIndex = (states.indexOf(current) + 1) % states.length;
            setState(id, states[nextIndex]);
        }

        function setState(id, state) {
            if (itemStates[id] === state) {
                delete itemStates[id];
            } else {
                itemStates[id] = state;
            }
            localStorage.setItem('checklistStates', JSON.stringify(itemStates));
            renderChecklist();
        }

        function saveNote(id, note) {
            if (note.trim()) {
                itemNotes[id] = note;
            } else {
                delete itemNotes[id];
            }
            localStorage.setItem('checklistNotes', JSON.stringify(itemNotes));
        }

        function updateProgress() {
            let total = 0, tested = 0, vuln = 0, ok = 0;
            
            Object.values(checklists).forEach(sections => {
                sections.forEach(section => {
                    section.items.forEach(item => {
                        total++;
                        if (itemStates[item.id] === 'tested') tested++;
                        if (itemStates[item.id] === 'vuln') vuln++;
                        if (itemStates[item.id] === 'ok') ok++;
                    });
                });
            });

            document.getElementById('testedCount').textContent = tested;
            document.getElementById('vulnCount').textContent = vuln;
            document.getElementById('okCount').textContent = ok;
            
            const completed = tested + vuln + ok;
            document.getElementById('testedBar').style.width = (tested / total * 100) + '%';
            document.getElementById('vulnBar').style.width = (vuln / total * 100) + '%';
            document.getElementById('okBar').style.width = (ok / total * 100) + '%';
        }

        function resetChecklist() {
            if (confirm('Tem certeza que deseja resetar todo o checklist?')) {
                itemStates = {};
                itemNotes = {};
                localStorage.removeItem('checklistStates');
                localStorage.removeItem('checklistNotes');
                renderChecklist();
            }
        }

        function exportChecklist() {
            const data = { states: itemStates, notes: itemNotes, date: new Date().toISOString() };
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `pentest-checklist-${new Date().toISOString().split('T')[0]}.json`;
            a.click();
        }

        function exportReport() {
            let report = `# Relatório de Pentest\n\nData: ${new Date().toLocaleDateString('pt-BR')}\n\n`;
            
            Object.entries(checklists).forEach(([type, sections]) => {
                report += `## ${type.toUpperCase()}\n\n`;
                sections.forEach(section => {
                    report += `### ${section.section}\n\n`;
                    section.items.forEach(item => {
                        const state = itemStates[item.id];
                        const note = itemNotes[item.id];
                        const status = state === 'vuln' ? '🔴 VULNERÁVEL' : state === 'ok' ? '🟢 OK' : state === 'tested' ? '🔵 TESTADO' : '⚪ NÃO TESTADO';
                        report += `- **${item.title}**: ${status}\n`;
                        if (note) report += `  - Notas: ${note}\n`;
                    });
                    report += '\n';
                });
            });

            const blob = new Blob([report], { type: 'text/markdown' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `pentest-report-${new Date().toISOString().split('T')[0]}.md`;
            a.click();
        }

        // Event listeners
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentType = btn.dataset.type;
                renderChecklist();
            });
        });

        renderChecklist();
    </script>
</body>
</html>
