<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist de Pentest - DeepEyes</title>
    <link rel="icon" type="image/png" href="/logo.png">
    
    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Critical CSS -->
    <style>html,body{background:#0a0a0f;color:#fff}html:not(.auth-checked) body{visibility:hidden}</style>
    
    <!-- Fontes - carregamento assíncrono -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600&display=swap"></noscript>
    
    <!-- Font Awesome - carregamento assíncrono -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
    
    <script>
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
            --text-primary: #ffffff;
            --text-secondary: #a0a0b0;
            --border-color: #2a2a3a;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; }
        .navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 16px 24px; background: rgba(10, 10, 15, 0.95); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border-color); }
        .navbar-inner { max-width: 1400px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
        .navbar-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .navbar-logo img { width: 32px; height: 32px; }
        .logo-text { font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 1.2rem; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .badge { font-size: 0.6rem; padding: 3px 8px; background: var(--accent-purple); border-radius: 4px; color: white; font-weight: 600; }
        .navbar-links { display: flex; gap: 32px; }
        .navbar-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; }
        .navbar-links a:hover, .navbar-links a.active { color: var(--accent-cyan); }
        .btn-primary { padding: 10px 24px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border-radius: 8px; color: var(--bg-primary); font-weight: 600; font-size: 0.9rem; text-decoration: none; border: none; cursor: pointer; }
        .main { padding: 100px 24px 40px; max-width: 1400px; margin: 0 auto; display: flex; gap: 24px; }
        .sidebar { width: 280px; flex-shrink: 0; }
        .sidebar-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 16px; margin-bottom: 16px; }
        .sidebar-title { font-size: 0.75rem; color: var(--accent-cyan); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .checklist-list { max-height: 400px; overflow-y: auto; }
        .checklist-item-sidebar { padding: 10px 12px; background: var(--bg-secondary); border-radius: 8px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s; border: 1px solid transparent; }
        .checklist-item-sidebar:hover { border-color: var(--accent-cyan); }
        .checklist-item-sidebar.active { border-color: var(--accent-cyan); background: rgba(0, 212, 255, 0.1); }
        .checklist-item-sidebar .title { font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .checklist-item-sidebar .meta { font-size: 0.7rem; color: var(--text-secondary); display: flex; gap: 8px; }
        .new-checklist-btn { width: 100%; padding: 12px; background: var(--bg-secondary); border: 1px dashed var(--border-color); border-radius: 8px; color: var(--text-secondary); cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .new-checklist-btn:hover { border-color: var(--accent-cyan); color: var(--accent-cyan); }
        .content { flex: 1; min-width: 0; }
        .page-header { margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; }
        .page-title { font-size: 1.5rem; font-weight: 700; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .page-subtitle { color: var(--text-secondary); font-size: 0.9rem; }
        .header-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .header-btn { padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; cursor: pointer; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-primary); transition: all 0.2s; display: flex; align-items: center; gap: 6px; }
        .header-btn:hover { border-color: var(--accent-cyan); }
        .header-btn.primary { background: var(--accent-cyan); border-color: var(--accent-cyan); color: var(--bg-primary); }
        .header-btn.share { background: var(--accent-purple); border-color: var(--accent-purple); }
        .header-btn.danger { border-color: var(--accent-red); color: var(--accent-red); }
        .header-btn.danger:hover { background: var(--accent-red); color: white; }
        .type-selector { display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap; }
        .type-btn { padding: 10px 16px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-secondary); font-size: 0.85rem; cursor: pointer; transition: all 0.2s; }
        .type-btn:hover { border-color: var(--accent-cyan); color: var(--text-primary); }
        .type-btn.active { background: var(--accent-cyan); border-color: var(--accent-cyan); color: var(--bg-primary); font-weight: 600; }
        .progress-bar { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 16px; margin-bottom: 24px; }
        .progress-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px; }
        .progress-title { font-weight: 600; font-size: 0.9rem; }
        .progress-stats { display: flex; gap: 16px; font-size: 0.8rem; }
        .stat { display: flex; align-items: center; gap: 6px; }
        .stat-dot { width: 8px; height: 8px; border-radius: 50%; }
        .stat-dot.tested { background: var(--accent-cyan); }
        .stat-dot.vuln { background: var(--accent-red); }
        .stat-dot.ok { background: var(--accent-green); }
        .progress-track { height: 6px; background: var(--bg-secondary); border-radius: 3px; overflow: hidden; display: flex; }
        .progress-fill { height: 100%; transition: width 0.3s; }
        .progress-fill.tested { background: var(--accent-cyan); }
        .progress-fill.vuln { background: var(--accent-red); }
        .progress-fill.ok { background: var(--accent-green); }
        .checklist-section { margin-bottom: 16px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px 8px 0 0; cursor: pointer; }
        .section-title { font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; }
        .section-count { font-size: 0.75rem; color: var(--text-secondary); background: var(--bg-secondary); padding: 2px 8px; border-radius: 4px; }
        .section-toggle { color: var(--text-secondary); transition: transform 0.2s; }
        .section-header.collapsed .section-toggle { transform: rotate(-90deg); }
        .checklist-items { border: 1px solid var(--border-color); border-top: none; border-radius: 0 0 8px 8px; overflow: hidden; }
        .checklist-items.collapsed { display: none; }
        .checklist-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 16px; background: var(--bg-secondary); border-bottom: 1px solid var(--border-color); }
        .checklist-item:last-child { border-bottom: none; }
        .checklist-item:hover { background: var(--bg-card); }
        .item-checkbox { width: 18px; height: 18px; border: 2px solid var(--border-color); border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0; margin-top: 2px; }
        .item-checkbox.tested { background: var(--accent-cyan); border-color: var(--accent-cyan); }
        .item-checkbox.vuln { background: var(--accent-red); border-color: var(--accent-red); }
        .item-checkbox.ok { background: var(--accent-green); border-color: var(--accent-green); }
        .item-checkbox svg { width: 10px; height: 10px; color: white; display: none; }
        .item-checkbox.tested svg, .item-checkbox.vuln svg, .item-checkbox.ok svg { display: block; }
        .item-content { flex: 1; min-width: 0; }
        .item-title { font-size: 0.9rem; margin-bottom: 2px; }
        .item-desc { font-size: 0.75rem; color: var(--text-secondary); }
        .item-actions { display: flex; gap: 4px; flex-shrink: 0; }
        .status-btn { padding: 4px 10px; border-radius: 4px; font-size: 0.7rem; cursor: pointer; border: 1px solid var(--border-color); background: var(--bg-card); color: var(--text-secondary); transition: all 0.2s; }
        .status-btn:hover { border-color: var(--text-secondary); }
        .status-btn.active.tested { background: var(--accent-cyan); border-color: var(--accent-cyan); color: var(--bg-primary); }
        .status-btn.active.vuln { background: var(--accent-red); border-color: var(--accent-red); color: white; }
        .status-btn.active.ok { background: var(--accent-green); border-color: var(--accent-green); color: var(--bg-primary); }
        .item-notes { margin-top: 8px; }
        .item-notes textarea { width: 100%; padding: 8px 10px; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 0.8rem; resize: vertical; min-height: 50px; font-family: 'JetBrains Mono', monospace; }
        .item-notes textarea:focus { outline: none; border-color: var(--accent-cyan); }
        .modal { position: fixed; inset: 0; background: rgba(0,0,0,0.8); display: none; align-items: center; justify-content: center; z-index: 2000; padding: 20px; }
        .modal.active { display: flex; }
        .modal-content { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; max-width: 450px; width: 100%; }
        .modal-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 16px; }
        .modal-input { width: 100%; padding: 12px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 0.9rem; margin-bottom: 12px; }
        .modal-input:focus { outline: none; border-color: var(--accent-cyan); }
        .modal-actions { display: flex; gap: 8px; justify-content: flex-end; }
        .share-link-box { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; margin-bottom: 16px; }
        .share-link-box input { width: 100%; background: transparent; border: none; color: var(--accent-cyan); font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; }
        .share-link-box input:focus { outline: none; }
        .toast { position: fixed; bottom: 24px; right: 24px; background: var(--bg-card); border: 1px solid var(--accent-green); border-radius: 8px; padding: 12px 20px; color: var(--accent-green); font-size: 0.9rem; z-index: 3000; display: none; }
        .toast.error { border-color: var(--accent-red); color: var(--accent-red); }
        .toast.active { display: block; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-secondary); }
        .empty-state i { font-size: 3rem; margin-bottom: 16px; opacity: 0.5; }
        .empty-state p { margin-bottom: 16px; }
        @media (max-width: 900px) { .main { flex-direction: column; } .sidebar { width: 100%; } .navbar-links { display: none; } .item-actions { flex-direction: column; } }
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
        <aside class="sidebar">
            <div class="sidebar-card">
                <div class="sidebar-title"><i class="fas fa-list-check"></i> Meus Checklists</div>
                <div class="checklist-list" id="checklistList"></div>
                <button class="new-checklist-btn" onclick="openNewModal()"><i class="fas fa-plus"></i> Novo Checklist</button>
            </div>
        </aside>
        <div class="content">
            <div id="emptyState" class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <p>Selecione ou crie um checklist para começar</p>
                <button class="btn-primary" onclick="openNewModal()"><i class="fas fa-plus"></i> Criar Checklist</button>
            </div>
            <div id="checklistContent" style="display: none;">
                <div class="page-header">
                    <div>
                        <h1 class="page-title" id="checklistTitle">-</h1>
                        <p class="page-subtitle" id="checklistSubtitle">-</p>
                    </div>
                    <div class="header-actions">
                        <button class="header-btn" onclick="analyzeWithAI()"><i class="fas fa-robot"></i> Analisar com IA</button>
                        <button class="header-btn share" onclick="shareChecklist()"><i class="fas fa-share-nodes"></i> Compartilhar</button>
                        <button class="header-btn" onclick="exportReport()"><i class="fas fa-file-lines"></i> Relatório</button>
                        <button class="header-btn danger" onclick="deleteChecklist()"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="type-selector" id="typeSelector"></div>
                <div class="progress-bar">
                    <div class="progress-header">
                        <span class="progress-title">Progresso</span>
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
            </div>
        </div>
    </main>
    <div class="modal" id="newModal">
        <div class="modal-content">
            <div class="modal-title"><i class="fas fa-plus"></i> Novo Checklist</div>
            <input type="text" class="modal-input" id="newTitle" placeholder="Título do checklist">
            <input type="text" class="modal-input" id="newDomain" placeholder="Domínio alvo (opcional)">
            <select class="modal-input" id="newType">
                <option value="web">Web Application</option>
                <option value="api">API</option>
                <option value="network">Network</option>
                <option value="ad">Active Directory</option>
                <option value="mobile">Mobile</option>
            </select>
            <div class="modal-actions">
                <button class="header-btn" onclick="closeNewModal()">Cancelar</button>
                <button class="header-btn primary" onclick="createChecklist()">Criar</button>
            </div>
        </div>
    </div>
    <div class="modal" id="shareModal">
        <div class="modal-content">
            <div class="modal-title"><i class="fas fa-share-nodes"></i> Compartilhar Checklist</div>
            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 16px;">Gere um link público para compartilhar este checklist (somente leitura).</p>
            <div class="share-link-box" id="shareLinkBox" style="display: none;">
                <input type="text" id="shareLink" readonly onclick="this.select()">
            </div>
            <div class="modal-actions">
                <button class="header-btn" onclick="closeShareModal()">Fechar</button>
                <button class="header-btn" id="copyLinkBtn" style="display: none;" onclick="copyShareLink()"><i class="fas fa-copy"></i> Copiar</button>
                <button class="header-btn primary" id="generateLinkBtn" onclick="generateShareLink()"><i class="fas fa-link"></i> Gerar Link</button>
            </div>
        </div>
    </div>
    <div class="toast" id="toast"></div>
</body>
</html>

<script>
const token = localStorage.getItem('token');
let currentChecklist = null;
let checklists = [];
let itemStates = {};
let itemNotes = {};
let currentType = 'web';
let saveTimeout = null;

const checklistData = {
    web: [
        { section: "Reconhecimento", items: [
            { id: "w1", title: "Enumeração de subdomínios", desc: "Subfinder, Amass, crt.sh" },
            { id: "w2", title: "Descoberta de diretórios", desc: "Gobuster, Feroxbuster" },
            { id: "w3", title: "Identificação de tecnologias", desc: "Wappalyzer, WhatWeb" },
            { id: "w4", title: "Análise de headers HTTP", desc: "Security headers, cookies" },
            { id: "w5", title: "Robots.txt e sitemap.xml", desc: "Arquivos expostos" }
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
            { id: "w15", title: "XXE (XML External Entity)", desc: "File read, SSRF via XXE" }
        ]},
        { section: "Controle de Acesso", items: [
            { id: "w16", title: "IDOR", desc: "Insecure Direct Object Reference" },
            { id: "w17", title: "Privilege Escalation", desc: "Horizontal e vertical" },
            { id: "w18", title: "Path Traversal / LFI", desc: "File inclusion attacks" },
            { id: "w19", title: "SSRF", desc: "Server-Side Request Forgery" }
        ]},
        { section: "Outros", items: [
            { id: "w20", title: "CSRF", desc: "Cross-Site Request Forgery" },
            { id: "w21", title: "File Upload", desc: "Unrestricted file upload" },
            { id: "w22", title: "JWT Attacks", desc: "None algorithm, weak secret" }
        ]}
    ],
    api: [
        { section: "Reconhecimento", items: [
            { id: "a1", title: "Documentação da API", desc: "Swagger, OpenAPI, GraphQL" },
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
            { id: "n8", title: "DNS zone transfer", desc: "AXFR" }
        ]}
    ],
    ad: [
        { section: "Enumeração", items: [
            { id: "ad1", title: "Domain enumeration", desc: "BloodHound, PowerView" },
            { id: "ad2", title: "User enumeration", desc: "Kerbrute, LDAP" },
            { id: "ad3", title: "Group Policy", desc: "GPP passwords" }
        ]},
        { section: "Credential Attacks", items: [
            { id: "ad4", title: "Kerberoasting", desc: "Service account hashes" },
            { id: "ad5", title: "AS-REP Roasting", desc: "No preauth users" },
            { id: "ad6", title: "Password Spraying", desc: "Common passwords" }
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
        ]}
    ]
};

async function api(endpoint, options = {}) {
    const res = await fetch(`/api${endpoint}`, {
        ...options,
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', ...options.headers }
    });
    if (res.status === 401) { localStorage.removeItem('token'); window.location.replace('/?login=required'); }
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Erro');
    return data;
}

function showToast(msg, isError = false) {
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.className = 'toast active' + (isError ? ' error' : '');
    setTimeout(() => toast.classList.remove('active'), 3000);
}

async function loadChecklists() {
    try {
        const data = await api('/checklists');
        checklists = data.data || [];
        renderChecklistList();
    } catch (e) {
        console.error(e);
    }
}

function renderChecklistList() {
    const list = document.getElementById('checklistList');
    if (checklists.length === 0) {
        list.innerHTML = '<p style="color: var(--text-secondary); font-size: 0.8rem; text-align: center; padding: 20px;">Nenhum checklist</p>';
        return;
    }
    list.innerHTML = checklists.map(c => `
        <div class="checklist-item-sidebar ${currentChecklist?.id === c.id ? 'active' : ''}" onclick="loadChecklist('${c.id}')">
            <div class="title">${c.title}</div>
            <div class="meta">
                <span>${c.type.toUpperCase()}</span>
                <span>${c.progress.vulnerable} vuln</span>
            </div>
        </div>
    `).join('');
}

async function loadChecklist(id) {
    try {
        const data = await api(`/checklists/${id}`);
        currentChecklist = data.data;
        itemStates = currentChecklist.states || {};
        itemNotes = currentChecklist.notes || {};
        currentType = currentChecklist.type;
        
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('checklistContent').style.display = 'block';
        document.getElementById('checklistTitle').textContent = currentChecklist.title;
        document.getElementById('checklistSubtitle').textContent = currentChecklist.target_domain || 'Sem domínio definido';
        
        renderTypeSelector();
        renderChecklist();
        renderChecklistList();
    } catch (e) {
        showToast('Erro ao carregar checklist', true);
    }
}

function renderTypeSelector() {
    const types = ['web', 'api', 'network', 'ad', 'mobile'];
    const names = { web: 'Web', api: 'API', network: 'Network', ad: 'AD', mobile: 'Mobile' };
    document.getElementById('typeSelector').innerHTML = types.map(t => 
        `<button class="type-btn ${currentType === t ? 'active' : ''}" onclick="changeType('${t}')">${names[t]}</button>`
    ).join('');
}

function changeType(type) {
    currentType = type;
    renderTypeSelector();
    renderChecklist();
    saveChecklist();
}

function renderChecklist() {
    const container = document.getElementById('checklistContainer');
    const data = checklistData[currentType] || [];
    
    container.innerHTML = data.map(section => `
        <div class="checklist-section">
            <div class="section-header" onclick="toggleSection(this)">
                <span class="section-title">${section.section} <span class="section-count">${section.items.length}</span></span>
                <svg class="section-toggle" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="checklist-items">
                ${section.items.map(item => `
                    <div class="checklist-item">
                        <div class="item-checkbox ${itemStates[item.id] || ''}" onclick="cycleState('${item.id}')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>
                        </div>
                        <div class="item-content">
                            <div class="item-title">${item.title}</div>
                            <div class="item-desc">${item.desc}</div>
                            <div class="item-notes">
                                <textarea placeholder="Notas..." onchange="saveNote('${item.id}', this.value)">${itemNotes[item.id] || ''}</textarea>
                            </div>
                        </div>
                        <div class="item-actions">
                            <button class="status-btn ${itemStates[item.id] === 'tested' ? 'active tested' : ''}" onclick="setState('${item.id}', 'tested')">Testado</button>
                            <button class="status-btn ${itemStates[item.id] === 'vuln' ? 'active vuln' : ''}" onclick="setState('${item.id}', 'vuln')">Vuln</button>
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

function cycleState(id) {
    const states = ['', 'tested', 'vuln', 'ok'];
    const current = itemStates[id] || '';
    const next = states[(states.indexOf(current) + 1) % states.length];
    setState(id, next);
}

function setState(id, state) {
    if (itemStates[id] === state) delete itemStates[id];
    else itemStates[id] = state;
    renderChecklist();
    saveChecklist();
}

function saveNote(id, note) {
    if (note.trim()) itemNotes[id] = note;
    else delete itemNotes[id];
    saveChecklist();
}

function updateProgress() {
    let total = 0, tested = 0, vuln = 0, ok = 0;
    Object.values(checklistData).forEach(sections => {
        sections.forEach(s => {
            s.items.forEach(item => {
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
    document.getElementById('testedBar').style.width = (tested / total * 100) + '%';
    document.getElementById('vulnBar').style.width = (vuln / total * 100) + '%';
    document.getElementById('okBar').style.width = (ok / total * 100) + '%';
}

async function saveChecklist() {
    if (!currentChecklist) return;
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(async () => {
        try {
            await api(`/checklists/${currentChecklist.id}`, {
                method: 'PUT',
                body: JSON.stringify({ states: itemStates, notes: itemNotes, type: currentType })
            });
        } catch (e) { console.error('Erro ao salvar:', e); }
    }, 500);
}

function openNewModal() { document.getElementById('newModal').classList.add('active'); document.getElementById('newTitle').focus(); }
function closeNewModal() { document.getElementById('newModal').classList.remove('active'); }

async function createChecklist() {
    const title = document.getElementById('newTitle').value.trim();
    const domain = document.getElementById('newDomain').value.trim();
    const type = document.getElementById('newType').value;
    if (!title) { showToast('Digite um título', true); return; }
    try {
        const data = await api('/checklists', { method: 'POST', body: JSON.stringify({ title, target_domain: domain, type }) });
        closeNewModal();
        document.getElementById('newTitle').value = '';
        document.getElementById('newDomain').value = '';
        await loadChecklists();
        loadChecklist(data.data.id);
        showToast('Checklist criado!');
    } catch (e) { showToast('Erro ao criar', true); }
}

async function deleteChecklist() {
    if (!currentChecklist || !confirm('Excluir este checklist?')) return;
    try {
        await api(`/checklists/${currentChecklist.id}`, { method: 'DELETE' });
        currentChecklist = null;
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('checklistContent').style.display = 'none';
        await loadChecklists();
        showToast('Checklist excluído');
    } catch (e) { showToast('Erro ao excluir', true); }
}

function shareChecklist() {
    if (!currentChecklist) return;
    document.getElementById('shareModal').classList.add('active');
    if (currentChecklist.share_url) {
        document.getElementById('shareLink').value = currentChecklist.share_url;
        document.getElementById('shareLinkBox').style.display = 'block';
        document.getElementById('copyLinkBtn').style.display = 'block';
        document.getElementById('generateLinkBtn').style.display = 'none';
    } else {
        document.getElementById('shareLinkBox').style.display = 'none';
        document.getElementById('copyLinkBtn').style.display = 'none';
        document.getElementById('generateLinkBtn').style.display = 'block';
    }
}
function closeShareModal() { document.getElementById('shareModal').classList.remove('active'); }

async function generateShareLink() {
    try {
        const data = await api(`/checklists/${currentChecklist.id}/share`, { method: 'POST' });
        currentChecklist.share_url = data.data.share_url;
        document.getElementById('shareLink').value = data.data.share_url;
        document.getElementById('shareLinkBox').style.display = 'block';
        document.getElementById('copyLinkBtn').style.display = 'block';
        document.getElementById('generateLinkBtn').style.display = 'none';
        showToast('Link gerado!');
    } catch (e) { showToast('Erro ao gerar link', true); }
}

function copyShareLink() {
    const input = document.getElementById('shareLink');
    input.select();
    document.execCommand('copy');
    showToast('Link copiado!');
}

function analyzeWithAI() {
    if (!currentChecklist) return;
    let report = `Analise este checklist de pentest e sugira próximos passos:\n\n`;
    report += `**Alvo:** ${currentChecklist.target_domain || 'Não definido'}\n`;
    report += `**Tipo:** ${currentType.toUpperCase()}\n\n`;
    
    const data = checklistData[currentType] || [];
    data.forEach(section => {
        report += `### ${section.section}\n`;
        section.items.forEach(item => {
            const state = itemStates[item.id];
            const note = itemNotes[item.id];
            const status = state === 'vuln' ? '🔴 VULNERÁVEL' : state === 'ok' ? '🟢 OK' : state === 'tested' ? '🔵 TESTADO' : '⚪ NÃO TESTADO';
            report += `- ${item.title}: ${status}\n`;
            if (note) report += `  Notas: ${note}\n`;
        });
        report += '\n';
    });
    report += '\nIdentifique vulnerabilidades críticas e sugira comandos/técnicas para exploração.';
    
    localStorage.setItem('terminalAnalysis', JSON.stringify({ prompt: report, timestamp: new Date().toISOString() }));
    window.location.href = '/chat?analyze=terminal';
}

function exportReport() {
    if (!currentChecklist) return;
    let report = `# Relatório de Pentest - ${currentChecklist.title}\n\n`;
    report += `**Data:** ${new Date().toLocaleDateString('pt-BR')}\n`;
    report += `**Alvo:** ${currentChecklist.target_domain || 'Não definido'}\n\n`;
    
    Object.entries(checklistData).forEach(([type, sections]) => {
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
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = `pentest-report-${currentChecklist.title.replace(/\s+/g, '-')}.md`;
    a.click();
    showToast('Relatório exportado!');
}

// Init
loadChecklists();
</script>
