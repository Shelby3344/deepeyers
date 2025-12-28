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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap"></noscript>
    
    <!-- Font Awesome - carregamento assíncrono -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
    
    <script>
        if (!localStorage.getItem('token')) {
            window.location.replace('/chat?login=required');
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
            --border-color: rgba(0, 212, 255, 0.15);
        }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg-primary); 
            color: var(--text-primary); 
            min-height: 100vh;
        }
        
        /* Background Effect */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            pointer-events: none;
            z-index: -1;
            background: 
                radial-gradient(ellipse at 20% 20%, rgba(0, 212, 255, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(0, 255, 136, 0.06) 0%, transparent 50%);
        }
        
        /* Navbar */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 16px 24px;
            background: rgba(10, 10, 15, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
        }
        .navbar-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .navbar-logo img { width: 36px; height: 36px; }
        .logo-text {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            font-size: 1.3rem;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .badge {
            font-size: 0.6rem;
            padding: 4px 10px;
            background: linear-gradient(135deg, var(--accent-orange), var(--accent-red));
            border-radius: 6px;
            color: white;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .navbar-links { display: flex; gap: 32px; }
        .navbar-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .navbar-links a:hover, .navbar-links a.active { color: var(--accent-cyan); }
        .btn-primary {
            padding: 10px 24px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border-radius: 10px;
            color: var(--bg-primary);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 212, 255, 0.3);
        }
        
        /* Main Layout */
        .main {
            padding: 100px 24px 40px;
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            gap: 24px;
        }
        
        /* Sidebar */
        .sidebar { width: 300px; flex-shrink: 0; }
        .sidebar-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
        }
        .sidebar-title {
            font-size: 0.75rem;
            color: var(--accent-cyan);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'JetBrains Mono', monospace;
        }
        .checklist-list { max-height: 450px; overflow-y: auto; }
        .checklist-item-sidebar {
            padding: 14px 16px;
            background: var(--bg-secondary);
            border-radius: 12px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid transparent;
        }
        .checklist-item-sidebar:hover {
            border-color: var(--accent-cyan);
            transform: translateX(4px);
        }
        .checklist-item-sidebar.active {
            border-color: var(--accent-cyan);
            background: rgba(0, 212, 255, 0.1);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.1);
        }
        .checklist-item-sidebar .title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .checklist-item-sidebar .meta {
            font-size: 0.7rem;
            color: var(--text-secondary);
            display: flex;
            gap: 12px;
            font-family: 'JetBrains Mono', monospace;
        }
        .checklist-item-sidebar .meta .vuln-count {
            color: var(--accent-red);
            font-weight: 600;
        }
        .new-checklist-btn {
            width: 100%;
            padding: 14px;
            background: transparent;
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
        }
        .new-checklist-btn:hover {
            border-color: var(--accent-green);
            color: var(--accent-green);
            background: rgba(0, 255, 136, 0.05);
        }
        
        /* Content */
        .content { flex: 1; min-width: 0; }
        
        /* Page Header */
        .page-header {
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 20px;
        }
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
        }
        .page-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-family: 'JetBrains Mono', monospace;
        }
        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .header-btn {
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-primary);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .header-btn:hover {
            border-color: var(--accent-cyan);
            transform: translateY(-2px);
        }
        .header-btn.primary {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border: none;
            color: var(--bg-primary);
        }
        .header-btn.share {
            background: var(--accent-purple);
            border-color: var(--accent-purple);
        }
        .header-btn.danger {
            border-color: var(--accent-red);
            color: var(--accent-red);
        }
        .header-btn.danger:hover {
            background: var(--accent-red);
            color: white;
        }
        
        /* Type Selector */
        .type-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }
        .type-btn {
            padding: 12px 20px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .type-btn i { font-size: 1rem; }
        .type-btn:hover {
            border-color: var(--accent-cyan);
            color: var(--text-primary);
        }
        .type-btn.active {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border-color: transparent;
            color: var(--bg-primary);
        }
        
        /* Progress Bar */
        .progress-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 28px;
        }
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .progress-title {
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .progress-title i { color: var(--accent-cyan); }
        .progress-stats { display: flex; gap: 24px; }
        .stat {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .stat-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        .stat-dot.tested { background: var(--accent-cyan); box-shadow: 0 0 10px var(--accent-cyan); }
        .stat-dot.vuln { background: var(--accent-red); box-shadow: 0 0 10px var(--accent-red); }
        .stat-dot.ok { background: var(--accent-green); box-shadow: 0 0 10px var(--accent-green); }
        .stat-value { font-family: 'JetBrains Mono', monospace; font-weight: 700; }
        .progress-track {
            height: 10px;
            background: var(--bg-secondary);
            border-radius: 5px;
            overflow: hidden;
            display: flex;
        }
        .progress-fill {
            height: 100%;
            transition: width 0.5s ease;
        }
        .progress-fill.tested { background: linear-gradient(90deg, var(--accent-cyan), #0ea5e9); }
        .progress-fill.vuln { background: linear-gradient(90deg, var(--accent-red), #dc2626); }
        .progress-fill.ok { background: linear-gradient(90deg, var(--accent-green), #22c55e); }
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
            <a href="/chat" class="btn-primary"><i class="fas fa-robot"></i> Acessar Chat</a>
        </div>
    </nav>
    
    <main class="main">
        <aside class="sidebar">
            <div class="sidebar-card">
                <div class="sidebar-title"><i class="fas fa-list-check"></i> Meus Checklists</div>
                <div class="checklist-list" id="checklistList"></div>
                <button class="new-checklist-btn" onclick="openNewModal()">
                    <i class="fas fa-plus"></i> Novo Checklist
                </button>
            </div>
        </aside>
        
        <div class="content">
            <div id="emptyState" class="empty-state">
                <div class="empty-icon"><i class="fas fa-clipboard-list"></i></div>
                <h3>Nenhum checklist selecionado</h3>
                <p>Crie ou selecione um checklist para começar seu pentest</p>
                <button class="btn-primary" onclick="openNewModal()">
                    <i class="fas fa-plus"></i> Criar Checklist
                </button>
            </div>
            
            <div id="checklistContent" style="display: none;">
                <div class="page-header">
                    <div>
                        <h1 class="page-title" id="checklistTitle">-</h1>
                        <p class="page-subtitle"><i class="fas fa-crosshairs"></i> <span id="checklistSubtitle">-</span></p>
                    </div>
                    <div class="header-actions">
                        <button class="header-btn" onclick="analyzeWithAI()"><i class="fas fa-robot"></i> Analisar com IA</button>
                        <button class="header-btn share" onclick="shareChecklist()"><i class="fas fa-share-nodes"></i> Compartilhar</button>
                        <button class="header-btn" onclick="exportReport()"><i class="fas fa-file-export"></i> Exportar</button>
                        <button class="header-btn danger" onclick="deleteChecklist()"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                
                <div class="type-selector" id="typeSelector"></div>
                
                <div class="progress-card">
                    <div class="progress-header">
                        <span class="progress-title"><i class="fas fa-chart-pie"></i> Progresso do Teste</span>
                        <div class="progress-stats">
                            <span class="stat"><span class="stat-dot tested"></span> <span class="stat-value" id="testedCount">0</span> Testados</span>
                            <span class="stat"><span class="stat-dot vuln"></span> <span class="stat-value" id="vulnCount">0</span> Vulneráveis</span>
                            <span class="stat"><span class="stat-dot ok"></span> <span class="stat-value" id="okCount">0</span> OK</span>
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

    <!-- Modals -->
    <div class="modal" id="newModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> Novo Checklist</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Título do Checklist</label>
                    <input type="text" class="modal-input" id="newTitle" placeholder="Ex: Pentest ACME Corp">
                </div>
                <div class="form-group">
                    <label>Domínio/Alvo</label>
                    <input type="text" class="modal-input" id="newDomain" placeholder="Ex: acme.com">
                </div>
                <div class="form-group">
                    <label>Tipo de Teste</label>
                    <select class="modal-input" id="newType">
                        <option value="web">🌐 Web Application</option>
                        <option value="api">🔌 API</option>
                        <option value="network">🖧 Network</option>
                        <option value="ad">🏢 Active Directory</option>
                        <option value="mobile">📱 Mobile</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button class="header-btn" onclick="closeNewModal()">Cancelar</button>
                <button class="header-btn primary" onclick="createChecklist()"><i class="fas fa-check"></i> Criar</button>
            </div>
        </div>
    </div>
    
    <div class="modal" id="shareModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-share-nodes"></i> Compartilhar Checklist</h3>
            </div>
            <div class="modal-body">
                <p class="modal-desc">Gere um link público para compartilhar este checklist (somente leitura).</p>
                <div class="share-link-box" id="shareLinkBox" style="display: none;">
                    <input type="text" id="shareLink" readonly onclick="this.select()">
                    <button onclick="copyShareLink()" class="copy-btn"><i class="fas fa-copy"></i></button>
                </div>
            </div>
            <div class="modal-actions">
                <button class="header-btn" onclick="closeShareModal()">Fechar</button>
                <button class="header-btn primary" id="generateLinkBtn" onclick="generateShareLink()"><i class="fas fa-link"></i> Gerar Link</button>
            </div>
        </div>
    </div>
    
    <div class="toast" id="toast"></div>
    
    <style>
        /* Checklist Sections */
        .checklist-section { margin-bottom: 20px; }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px 12px 0 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        .section-header:hover { background: rgba(0, 212, 255, 0.05); }
        .section-title {
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(0, 255, 136, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-cyan);
        }
        .section-count {
            font-size: 0.75rem;
            color: var(--text-secondary);
            background: var(--bg-secondary);
            padding: 4px 12px;
            border-radius: 20px;
            font-family: 'JetBrains Mono', monospace;
        }
        .section-toggle {
            color: var(--text-secondary);
            transition: transform 0.3s;
        }
        .section-header.collapsed .section-toggle { transform: rotate(-90deg); }
        
        .checklist-items {
            border: 1px solid var(--border-color);
            border-top: none;
            border-radius: 0 0 12px 12px;
            overflow: hidden;
        }
        .checklist-items.collapsed { display: none; }
        
        .checklist-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px 20px;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s;
        }
        .checklist-item:last-child { border-bottom: none; }
        .checklist-item:hover { background: var(--bg-card); }
        
        .item-checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .item-checkbox:hover { border-color: var(--accent-cyan); }
        .item-checkbox.tested {
            background: var(--accent-cyan);
            border-color: var(--accent-cyan);
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
        }
        .item-checkbox.vuln {
            background: var(--accent-red);
            border-color: var(--accent-red);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
        }
        .item-checkbox.ok {
            background: var(--accent-green);
            border-color: var(--accent-green);
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.4);
        }
        .item-checkbox svg {
            width: 14px;
            height: 14px;
            color: white;
            display: none;
        }
        .item-checkbox.tested svg, .item-checkbox.vuln svg, .item-checkbox.ok svg { display: block; }
        
        .item-content { flex: 1; min-width: 0; }
        .item-title {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .item-desc {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-family: 'JetBrains Mono', monospace;
        }
        .item-notes { margin-top: 12px; }
        .item-notes textarea {
            width: 100%;
            padding: 12px;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.85rem;
            resize: vertical;
            min-height: 60px;
            font-family: 'JetBrains Mono', monospace;
            transition: border-color 0.3s;
        }
        .item-notes textarea:focus {
            outline: none;
            border-color: var(--accent-cyan);
        }
        .item-notes textarea::placeholder { color: var(--text-secondary); }
        
        .item-actions {
            display: flex;
            gap: 6px;
            flex-shrink: 0;
        }
        .status-btn {
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: var(--bg-card);
            color: var(--text-secondary);
            transition: all 0.3s;
        }
        .status-btn:hover { border-color: var(--text-secondary); }
        .status-btn.active.tested {
            background: var(--accent-cyan);
            border-color: var(--accent-cyan);
            color: var(--bg-primary);
        }
        .status-btn.active.vuln {
            background: var(--accent-red);
            border-color: var(--accent-red);
            color: white;
        }
        .status-btn.active.ok {
            background: var(--accent-green);
            border-color: var(--accent-green);
            color: var(--bg-primary);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-secondary);
        }
        .empty-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 255, 136, 0.05));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed var(--border-color);
        }
        .empty-icon i {
            font-size: 2.5rem;
            color: var(--accent-cyan);
            opacity: 0.7;
        }
        .empty-state h3 {
            font-size: 1.3rem;
            color: var(--text-primary);
            margin-bottom: 10px;
        }
        .empty-state p { margin-bottom: 24px; }
        
        /* Modals */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            padding: 20px;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            max-width: 480px;
            width: 100%;
            overflow: hidden;
        }
        .modal-header {
            padding: 24px;
            border-bottom: 1px solid var(--border-color);
        }
        .modal-header h3 {
            font-size: 1.2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .modal-header h3 i { color: var(--accent-cyan); }
        .modal-body { padding: 24px; }
        .modal-desc {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-secondary);
        }
        .modal-input {
            width: 100%;
            padding: 14px 16px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        .modal-input:focus {
            outline: none;
            border-color: var(--accent-cyan);
        }
        .modal-actions {
            padding: 20px 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
        
        .share-link-box {
            display: flex;
            gap: 10px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 4px;
        }
        .share-link-box input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--accent-cyan);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            padding: 10px 12px;
        }
        .share-link-box input:focus { outline: none; }
        .copy-btn {
            padding: 10px 16px;
            background: var(--accent-cyan);
            border: none;
            border-radius: 8px;
            color: var(--bg-primary);
            cursor: pointer;
            transition: all 0.3s;
        }
        .copy-btn:hover { opacity: 0.8; }
        
        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--bg-card);
            border: 1px solid var(--accent-green);
            border-radius: 12px;
            padding: 16px 24px;
            color: var(--accent-green);
            font-size: 0.9rem;
            font-weight: 500;
            z-index: 3000;
            display: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        }
        .toast.error {
            border-color: var(--accent-red);
            color: var(--accent-red);
        }
        .toast.active {
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-secondary); }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--accent-cyan), var(--accent-green));
            border-radius: 4px;
        }
        
        /* Responsive */
        @media (max-width: 900px) {
            .main { flex-direction: column; }
            .sidebar { width: 100%; }
            .navbar-links { display: none; }
            .item-actions { flex-direction: column; }
            .page-title { font-size: 1.5rem; }
            .header-actions { width: 100%; }
            .header-btn { flex: 1; justify-content: center; }
        }
    </style>

<script>
const token = localStorage.getItem('token');
let currentChecklist = null;
let checklists = [];
let itemStates = {};
let itemNotes = {};
let currentType = 'web';
let saveTimeout = null;

const typeIcons = {
    web: 'fa-globe',
    api: 'fa-plug',
    network: 'fa-network-wired',
    ad: 'fa-building',
    mobile: 'fa-mobile-screen'
};

const checklistData = {
    web: [
        { section: "Reconhecimento", icon: "fa-search", items: [
            { id: "w1", title: "Enumeração de subdomínios", desc: "Subfinder, Amass, crt.sh" },
            { id: "w2", title: "Descoberta de diretórios", desc: "Gobuster, Feroxbuster, Dirsearch" },
            { id: "w3", title: "Identificação de tecnologias", desc: "Wappalyzer, WhatWeb, BuiltWith" },
            { id: "w4", title: "Análise de headers HTTP", desc: "Security headers, cookies, CORS" },
            { id: "w5", title: "Robots.txt e sitemap.xml", desc: "Arquivos expostos, endpoints ocultos" },
            { id: "w6", title: "Google Dorks", desc: "site:, filetype:, inurl:, intitle:" }
        ]},
        { section: "Autenticação", icon: "fa-key", items: [
            { id: "w7", title: "Brute force de login", desc: "Hydra, Burp Intruder, ffuf" },
            { id: "w8", title: "Bypass de autenticação", desc: "SQL injection, default creds, logic flaws" },
            { id: "w9", title: "Enumeração de usuários", desc: "Timing attacks, error messages, forgot password" },
            { id: "w10", title: "Password reset flaws", desc: "Token prediction, host header injection" },
            { id: "w11", title: "Session management", desc: "Session fixation, hijacking, timeout" },
            { id: "w12", title: "2FA/MFA bypass", desc: "Response manipulation, brute force, backup codes" }
        ]},
        { section: "Injeções", icon: "fa-syringe", items: [
            { id: "w13", title: "SQL Injection", desc: "Union, Blind, Time-based, Error-based" },
            { id: "w14", title: "XSS (Cross-Site Scripting)", desc: "Reflected, Stored, DOM-based" },
            { id: "w15", title: "Command Injection", desc: "OS command execution, RCE" },
            { id: "w16", title: "SSTI (Template Injection)", desc: "Jinja2, Twig, Freemarker, Velocity" },
            { id: "w17", title: "XXE (XML External Entity)", desc: "File read, SSRF, DoS via XXE" },
            { id: "w18", title: "LDAP Injection", desc: "Authentication bypass, data exfiltration" }
        ]},
        { section: "Controle de Acesso", icon: "fa-lock-open", items: [
            { id: "w19", title: "IDOR", desc: "Insecure Direct Object Reference" },
            { id: "w20", title: "Privilege Escalation", desc: "Horizontal e vertical" },
            { id: "w21", title: "Path Traversal / LFI", desc: "File inclusion, directory traversal" },
            { id: "w22", title: "SSRF", desc: "Server-Side Request Forgery" },
            { id: "w23", title: "Forced Browsing", desc: "Acesso a recursos não autorizados" }
        ]},
        { section: "Outros", icon: "fa-bug", items: [
            { id: "w24", title: "CSRF", desc: "Cross-Site Request Forgery" },
            { id: "w25", title: "File Upload", desc: "Unrestricted file upload, bypass filters" },
            { id: "w26", title: "JWT Attacks", desc: "None algorithm, weak secret, kid injection" },
            { id: "w27", title: "WebSocket vulnerabilities", desc: "CSWSH, injection, hijacking" },
            { id: "w28", title: "Clickjacking", desc: "UI redressing, frame injection" }
        ]}
    ],
    api: [
        { section: "Reconhecimento", icon: "fa-search", items: [
            { id: "a1", title: "Documentação da API", desc: "Swagger, OpenAPI, GraphQL introspection" },
            { id: "a2", title: "Enumeração de endpoints", desc: "Fuzzing, wordlists, Kiterunner" },
            { id: "a3", title: "Versionamento", desc: "APIs antigas expostas, v1, v2, beta" },
            { id: "a4", title: "Rate limiting", desc: "Bypass, DoS, resource exhaustion" }
        ]},
        { section: "Autenticação", icon: "fa-key", items: [
            { id: "a5", title: "API Key exposure", desc: "Keys em código, logs, headers" },
            { id: "a6", title: "JWT vulnerabilities", desc: "None alg, weak secret, kid injection" },
            { id: "a7", title: "OAuth flaws", desc: "Redirect URI, state param, PKCE bypass" },
            { id: "a8", title: "Bearer token leakage", desc: "Referrer, logs, caching" }
        ]},
        { section: "Autorização", icon: "fa-user-shield", items: [
            { id: "a9", title: "BOLA/IDOR", desc: "Broken Object Level Authorization" },
            { id: "a10", title: "BFLA", desc: "Broken Function Level Authorization" },
            { id: "a11", title: "Mass Assignment", desc: "Parameter pollution, hidden params" },
            { id: "a12", title: "GraphQL attacks", desc: "Batching, deep queries, introspection" }
        ]}
    ],
    network: [
        { section: "Descoberta", icon: "fa-radar", items: [
            { id: "n1", title: "Host discovery", desc: "Nmap, Masscan, ARP scan" },
            { id: "n2", title: "Port scanning", desc: "TCP/UDP scan, SYN, Connect" },
            { id: "n3", title: "Service enumeration", desc: "Version detection, banner grabbing" },
            { id: "n4", title: "OS fingerprinting", desc: "TTL, TCP/IP stack analysis" }
        ]},
        { section: "Serviços", icon: "fa-server", items: [
            { id: "n5", title: "SMB enumeration", desc: "Shares, users, null session, EternalBlue" },
            { id: "n6", title: "FTP anonymous", desc: "Acesso anônimo, upload, bounce attack" },
            { id: "n7", title: "SSH vulnerabilities", desc: "Weak ciphers, keys, user enum" },
            { id: "n8", title: "DNS zone transfer", desc: "AXFR, subdomain takeover" },
            { id: "n9", title: "SNMP enumeration", desc: "Community strings, MIB walking" },
            { id: "n10", title: "RDP vulnerabilities", desc: "BlueKeep, NLA bypass, brute force" }
        ]}
    ],
    ad: [
        { section: "Enumeração", icon: "fa-sitemap", items: [
            { id: "ad1", title: "Domain enumeration", desc: "BloodHound, PowerView, ADRecon" },
            { id: "ad2", title: "User enumeration", desc: "Kerbrute, LDAP, RPC" },
            { id: "ad3", title: "Group Policy", desc: "GPP passwords, GPO abuse" },
            { id: "ad4", title: "Trust relationships", desc: "Forest trusts, SID history" }
        ]},
        { section: "Credential Attacks", icon: "fa-key", items: [
            { id: "ad5", title: "Kerberoasting", desc: "Service account hashes, TGS" },
            { id: "ad6", title: "AS-REP Roasting", desc: "No preauth users" },
            { id: "ad7", title: "Password Spraying", desc: "Common passwords, lockout policy" },
            { id: "ad8", title: "NTLM Relay", desc: "SMB relay, LDAP relay, WebDAV" }
        ]},
        { section: "Privilege Escalation", icon: "fa-arrow-up", items: [
            { id: "ad9", title: "DCSync", desc: "Replicating Directory Changes" },
            { id: "ad10", title: "Golden Ticket", desc: "KRBTGT hash, persistence" },
            { id: "ad11", title: "Silver Ticket", desc: "Service account impersonation" },
            { id: "ad12", title: "ACL Abuse", desc: "WriteDACL, GenericAll, GenericWrite" }
        ]}
    ],
    mobile: [
        { section: "Análise Estática", icon: "fa-file-code", items: [
            { id: "m1", title: "Decompilação", desc: "APKTool, jadx, Hopper, IDA" },
            { id: "m2", title: "Hardcoded secrets", desc: "API keys, credentials, tokens" },
            { id: "m3", title: "Insecure storage", desc: "SharedPrefs, Keychain, SQLite" },
            { id: "m4", title: "Code obfuscation", desc: "ProGuard, R8, string encryption" }
        ]},
        { section: "Análise Dinâmica", icon: "fa-play", items: [
            { id: "m5", title: "SSL Pinning bypass", desc: "Frida, Objection, SSLKillSwitch" },
            { id: "m6", title: "Root/Jailbreak detection", desc: "Bypass checks, Magisk Hide" },
            { id: "m7", title: "Traffic interception", desc: "Burp, mitmproxy, Charles" },
            { id: "m8", title: "Runtime manipulation", desc: "Frida scripts, method hooking" }
        ]}
    ]
};

async function api(endpoint, options = {}) {
    const res = await fetch(`/api${endpoint}`, {
        ...options,
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', ...options.headers }
    });
    if (res.status === 401) { localStorage.removeItem('token'); window.location.replace('/chat?login=required'); }
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Erro');
    return data;
}

function showToast(msg, isError = false) {
    const toast = document.getElementById('toast');
    toast.innerHTML = `<i class="fas ${isError ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i> ${msg}`;
    toast.className = 'toast active' + (isError ? ' error' : '');
    setTimeout(() => toast.classList.remove('active'), 3000);
}

async function loadChecklists() {
    try {
        const data = await api('/checklists');
        checklists = data.data || [];
        renderChecklistList();
    } catch (e) { console.error(e); }
}

function renderChecklistList() {
    const list = document.getElementById('checklistList');
    if (checklists.length === 0) {
        list.innerHTML = '<p style="color: var(--text-secondary); font-size: 0.85rem; text-align: center; padding: 30px;">Nenhum checklist criado</p>';
        return;
    }
    list.innerHTML = checklists.map(c => `
        <div class="checklist-item-sidebar ${currentChecklist?.id === c.id ? 'active' : ''}" onclick="loadChecklist('${c.id}')">
            <div class="title"><i class="fas ${typeIcons[c.type] || 'fa-list'}" style="color: var(--accent-cyan); margin-right: 8px;"></i>${c.title}</div>
            <div class="meta">
                <span>${c.type.toUpperCase()}</span>
                <span class="vuln-count">${c.progress?.vulnerable || 0} vuln</span>
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
    } catch (e) { showToast('Erro ao carregar checklist', true); }
}

function renderTypeSelector() {
    const types = ['web', 'api', 'network', 'ad', 'mobile'];
    const names = { web: 'Web App', api: 'API', network: 'Network', ad: 'Active Directory', mobile: 'Mobile' };
    document.getElementById('typeSelector').innerHTML = types.map(t => 
        `<button class="type-btn ${currentType === t ? 'active' : ''}" onclick="changeType('${t}')">
            <i class="fas ${typeIcons[t]}"></i> ${names[t]}
        </button>`
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
                <span class="section-title">
                    <span class="section-icon"><i class="fas ${section.icon}"></i></span>
                    ${section.section}
                    <span class="section-count">${section.items.length} itens</span>
                </span>
                <i class="fas fa-chevron-down section-toggle"></i>
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
                                <textarea placeholder="Adicionar notas, comandos, evidências..." onchange="saveNote('${item.id}', this.value)">${itemNotes[item.id] || ''}</textarea>
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
    const data = checklistData[currentType] || [];
    let total = 0, tested = 0, vuln = 0, ok = 0;
    data.forEach(s => {
        s.items.forEach(item => {
            total++;
            if (itemStates[item.id] === 'tested') tested++;
            if (itemStates[item.id] === 'vuln') vuln++;
            if (itemStates[item.id] === 'ok') ok++;
        });
    });
    document.getElementById('testedCount').textContent = tested;
    document.getElementById('vulnCount').textContent = vuln;
    document.getElementById('okCount').textContent = ok;
    const pct = total > 0 ? 100 / total : 0;
    document.getElementById('testedBar').style.width = (tested * pct) + '%';
    document.getElementById('vulnBar').style.width = (vuln * pct) + '%';
    document.getElementById('okBar').style.width = (ok * pct) + '%';
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
        showToast('Checklist criado com sucesso!');
    } catch (e) { showToast('Erro ao criar checklist', true); }
}

async function deleteChecklist() {
    if (!currentChecklist || !confirm('Tem certeza que deseja excluir este checklist?')) return;
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
        document.getElementById('shareLinkBox').style.display = 'flex';
        document.getElementById('generateLinkBtn').style.display = 'none';
    } else {
        document.getElementById('shareLinkBox').style.display = 'none';
        document.getElementById('generateLinkBtn').style.display = 'block';
    }
}
function closeShareModal() { document.getElementById('shareModal').classList.remove('active'); }

async function generateShareLink() {
    try {
        const data = await api(`/checklists/${currentChecklist.id}/share`, { method: 'POST' });
        currentChecklist.share_url = data.data.share_url;
        document.getElementById('shareLink').value = data.data.share_url;
        document.getElementById('shareLinkBox').style.display = 'flex';
        document.getElementById('generateLinkBtn').style.display = 'none';
        showToast('Link gerado com sucesso!');
    } catch (e) { showToast('Erro ao gerar link', true); }
}

function copyShareLink() {
    navigator.clipboard.writeText(document.getElementById('shareLink').value);
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
    
    localStorage.setItem('checklistAnalysis', JSON.stringify({ prompt: report, timestamp: new Date().toISOString() }));
    window.location.href = '/chat?analyze=checklist';
}

function exportReport() {
    if (!currentChecklist) return;
    let report = `# Relatório de Pentest - ${currentChecklist.title}\n\n`;
    report += `**Data:** ${new Date().toLocaleDateString('pt-BR')}\n`;
    report += `**Alvo:** ${currentChecklist.target_domain || 'Não definido'}\n`;
    report += `**Tipo:** ${currentType.toUpperCase()}\n\n---\n\n`;
    
    const data = checklistData[currentType] || [];
    data.forEach(section => {
        report += `## ${section.section}\n\n`;
        section.items.forEach(item => {
            const state = itemStates[item.id];
            const note = itemNotes[item.id];
            const status = state === 'vuln' ? '🔴 VULNERÁVEL' : state === 'ok' ? '🟢 OK' : state === 'tested' ? '🔵 TESTADO' : '⚪ NÃO TESTADO';
            report += `### ${item.title}\n`;
            report += `- **Status:** ${status}\n`;
            report += `- **Descrição:** ${item.desc}\n`;
            if (note) report += `- **Notas:** ${note}\n`;
            report += '\n';
        });
    });
    
    const blob = new Blob([report], { type: 'text/markdown' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = `pentest-report-${currentChecklist.title.replace(/\s+/g, '-')}-${new Date().toISOString().split('T')[0]}.md`;
    a.click();
    showToast('Relatório exportado!');
}

// Init
loadChecklists();
</script>
</body>
</html>
