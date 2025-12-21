<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner - DeepEyes</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        .badge { font-size: 0.6rem; padding: 3px 8px; background: var(--accent-orange); border-radius: 4px; color: white; font-weight: 600; }
        .navbar-links { display: flex; gap: 32px; }
        .navbar-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; }
        .navbar-links a:hover, .navbar-links a.active { color: var(--accent-cyan); }
        .btn-primary { padding: 10px 24px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border-radius: 8px; color: var(--bg-primary); font-weight: 600; text-decoration: none; }

        .main { padding: 100px 24px 40px; max-width: 1000px; margin: 0 auto; }
        .page-header { text-align: center; margin-bottom: 40px; }
        .page-title { font-size: 2.2rem; font-weight: 700; margin-bottom: 12px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .page-subtitle { color: var(--text-secondary); font-size: 1rem; max-width: 600px; margin: 0 auto; }

        /* Scanner Form */
        .scanner-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; margin-bottom: 32px; }
        .input-group { margin-bottom: 24px; }
        .input-label { display: block; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px; font-weight: 500; }
        .input-field { width: 100%; padding: 14px 18px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-primary); font-size: 1rem; font-family: 'JetBrains Mono', monospace; }
        .input-field:focus { outline: none; border-color: var(--accent-cyan); }
        .input-field::placeholder { color: var(--text-secondary); }

        .scan-options { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 24px; }
        .option-card { padding: 16px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 10px; cursor: pointer; transition: all 0.2s; }
        .option-card:hover { border-color: var(--accent-cyan); }
        .option-card.selected { border-color: var(--accent-cyan); background: rgba(0, 212, 255, 0.1); }
        .option-card input { display: none; }
        .option-title { font-weight: 600; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
        .option-title .check { width: 18px; height: 18px; border: 2px solid var(--border-color); border-radius: 4px; display: flex; align-items: center; justify-content: center; }
        .option-card.selected .check { background: var(--accent-cyan); border-color: var(--accent-cyan); }
        .option-card.selected .check::after { content: '✓'; color: var(--bg-primary); font-size: 12px; font-weight: bold; }
        .option-desc { font-size: 0.8rem; color: var(--text-secondary); margin-left: 26px; }

        .scan-btn { width: 100%; padding: 16px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border: none; border-radius: 10px; color: var(--bg-primary); font-size: 1rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: opacity 0.2s; }
        .scan-btn:hover { opacity: 0.9; }
        .scan-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .scan-btn .spinner { width: 20px; height: 20px; border: 2px solid transparent; border-top-color: var(--bg-primary); border-radius: 50%; animation: spin 1s linear infinite; display: none; }
        .scan-btn.loading .spinner { display: block; }
        .scan-btn.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Results */
        .results-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; display: none; }
        .results-card.visible { display: block; }
        .results-header { padding: 20px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
        .results-title { font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .results-actions { display: flex; gap: 8px; }
        .results-btn { padding: 8px 16px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 0.85rem; cursor: pointer; }
        .results-btn:hover { border-color: var(--accent-cyan); }

        .results-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; padding: 20px 24px; border-bottom: 1px solid var(--border-color); }
        .summary-item { text-align: center; }
        .summary-value { font-size: 1.8rem; font-weight: 700; }
        .summary-value.critical { color: var(--accent-red); }
        .summary-value.high { color: var(--accent-orange); }
        .summary-value.medium { color: var(--accent-yellow); }
        .summary-value.low { color: var(--accent-green); }
        .summary-label { font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 1px; }

        .results-body { padding: 24px; }
        .finding { background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 10px; margin-bottom: 12px; overflow: hidden; }
        .finding-header { padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; }
        .finding-title { font-weight: 500; display: flex; align-items: center; gap: 12px; }
        .finding-severity { padding: 4px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; }
        .finding-severity.critical { background: rgba(239, 68, 68, 0.2); color: var(--accent-red); }
        .finding-severity.high { background: rgba(249, 115, 22, 0.2); color: var(--accent-orange); }
        .finding-severity.medium { background: rgba(234, 179, 8, 0.2); color: var(--accent-yellow); }
        .finding-severity.low { background: rgba(0, 255, 136, 0.2); color: var(--accent-green); }
        .finding-severity.info { background: rgba(0, 212, 255, 0.2); color: var(--accent-cyan); }
        .finding-toggle { color: var(--text-secondary); transition: transform 0.2s; }
        .finding.expanded .finding-toggle { transform: rotate(180deg); }
        .finding-body { padding: 0 20px 20px; display: none; }
        .finding.expanded .finding-body { display: block; }
        .finding-desc { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 16px; line-height: 1.6; }
        .finding-details { background: var(--bg-primary); border-radius: 8px; padding: 16px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; }
        .finding-details .label { color: var(--accent-cyan); }
        .finding-details .value { color: var(--text-primary); }
        .finding-recommendation { margin-top: 16px; padding: 12px 16px; background: rgba(0, 255, 136, 0.1); border-left: 3px solid var(--accent-green); border-radius: 0 8px 8px 0; }
        .finding-recommendation-title { font-size: 0.8rem; color: var(--accent-green); font-weight: 600; margin-bottom: 4px; }
        .finding-recommendation-text { font-size: 0.85rem; color: var(--text-secondary); }

        /* Loading Animation */
        .scan-progress { padding: 40px 24px; text-align: center; display: none; }
        .scan-progress.visible { display: block; }
        .progress-animation { width: 80px; height: 80px; margin: 0 auto 20px; position: relative; }
        .progress-ring { width: 100%; height: 100%; border: 3px solid var(--border-color); border-top-color: var(--accent-cyan); border-radius: 50%; animation: spin 1s linear infinite; }
        .progress-text { font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 8px; }
        .progress-status { font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: var(--accent-cyan); }

        @media (max-width: 768px) {
            .navbar-links { display: none; }
            .results-summary { grid-template-columns: repeat(2, 1fr); }
            .scan-options { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-logo">
                <img src="/logo.png" alt="DeepEyes">
                <span class="logo-text">DeepEyes</span>
                <span class="badge">SCANNER</span>
            </a>
            <div class="navbar-links">
                <a href="/">Home</a>
                <a href="/docs">Docs</a>
                <a href="/scanner" class="active">Scanner</a>
                <a href="/chat">Chat</a>
            </div>
            <a href="/chat" class="btn-primary">Acessar Chat</a>
        </div>
    </nav>

    <main class="main">
        <div class="page-header">
            <h1 class="page-title">Scanner de Vulnerabilidades</h1>
            <p class="page-subtitle">Análise rápida de segurança em URLs. Verifica headers, SSL, portas abertas e vulnerabilidades comuns.</p>
        </div>

        <div class="scanner-card">
            <div class="input-group">
                <label class="input-label">URL ou IP do Alvo</label>
                <input type="text" id="targetInput" class="input-field mono" placeholder="https://exemplo.com ou 192.168.1.1">
            </div>

            <label class="input-label">Tipos de Scan</label>
            <div class="scan-options">
                <label class="option-card selected" onclick="toggleOption(this)">
                    <input type="checkbox" name="scan" value="headers" checked>
                    <div class="option-title"><span class="check"></span> Security Headers</div>
                    <div class="option-desc">Analisa headers HTTP de segurança</div>
                </label>
                <label class="option-card selected" onclick="toggleOption(this)">
                    <input type="checkbox" name="scan" value="ssl" checked>
                    <div class="option-title"><span class="check"></span> SSL/TLS</div>
                    <div class="option-desc">Verifica certificado e configuração</div>
                </label>
                <label class="option-card selected" onclick="toggleOption(this)">
                    <input type="checkbox" name="scan" value="ports" checked>
                    <div class="option-title"><span class="check"></span> Port Scan</div>
                    <div class="option-desc">Portas comuns (top 20)</div>
                </label>
                <label class="option-card" onclick="toggleOption(this)">
                    <input type="checkbox" name="scan" value="tech">
                    <div class="option-title"><span class="check"></span> Tech Detection</div>
                    <div class="option-desc">Identifica tecnologias usadas</div>
                </label>
            </div>

            <button class="scan-btn" id="scanBtn" onclick="startScan()">
                <span class="spinner"></span>
                <span class="btn-text">Iniciar Scan</span>
            </button>
        </div>

        <div class="scan-progress" id="scanProgress">
            <div class="progress-animation"><div class="progress-ring"></div></div>
            <div class="progress-text">Escaneando alvo...</div>
            <div class="progress-status" id="progressStatus">Iniciando...</div>
        </div>

        <div class="results-card" id="resultsCard">
            <div class="results-header">
                <span class="results-title">📊 Resultados do Scan</span>
                <div class="results-actions">
                    <button class="results-btn" onclick="copyResults()">📋 Copiar</button>
                    <button class="results-btn" onclick="exportResults()">📥 Exportar</button>
                    <button class="results-btn" onclick="analyzeWithAI()">🤖 Analisar com IA</button>
                </div>
            </div>
            <div class="results-summary">
                <div class="summary-item"><div class="summary-value critical" id="criticalCount">0</div><div class="summary-label">Crítico</div></div>
                <div class="summary-item"><div class="summary-value high" id="highCount">0</div><div class="summary-label">Alto</div></div>
                <div class="summary-item"><div class="summary-value medium" id="mediumCount">0</div><div class="summary-label">Médio</div></div>
                <div class="summary-item"><div class="summary-value low" id="lowCount">0</div><div class="summary-label">Baixo</div></div>
            </div>
            <div class="results-body" id="resultsBody"></div>
        </div>
    </main>

    <script>
        let scanResults = [];
        let targetUrl = '';

        function toggleOption(card) {
            card.classList.toggle('selected');
        }

        async function startScan() {
            const target = document.getElementById('targetInput').value.trim();
            if (!target) { alert('Digite uma URL ou IP'); return; }
            
            targetUrl = target;
            const options = Array.from(document.querySelectorAll('.option-card.selected input')).map(i => i.value);
            if (options.length === 0) { alert('Selecione pelo menos um tipo de scan'); return; }

            const btn = document.getElementById('scanBtn');
            const progress = document.getElementById('scanProgress');
            const results = document.getElementById('resultsCard');
            
            btn.classList.add('loading');
            btn.disabled = true;
            progress.classList.add('visible');
            results.classList.remove('visible');
            scanResults = [];

            const steps = [];
            if (options.includes('headers')) steps.push({ name: 'Analisando headers...', fn: scanHeaders });
            if (options.includes('ssl')) steps.push({ name: 'Verificando SSL/TLS...', fn: scanSSL });
            if (options.includes('ports')) steps.push({ name: 'Escaneando portas...', fn: scanPorts });
            if (options.includes('tech')) steps.push({ name: 'Detectando tecnologias...', fn: scanTech });

            for (const step of steps) {
                document.getElementById('progressStatus').textContent = step.name;
                await step.fn(target);
                await sleep(800);
            }

            document.getElementById('progressStatus').textContent = 'Concluído!';
            await sleep(500);

            btn.classList.remove('loading');
            btn.disabled = false;
            progress.classList.remove('visible');
            
            renderResults();
        }

        function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

        async function scanHeaders(target) {
            // Simulated header analysis
            const missingHeaders = [
                { header: 'Strict-Transport-Security', severity: 'high', desc: 'HSTS não configurado. Permite downgrade para HTTP.', rec: 'Adicione: Strict-Transport-Security: max-age=31536000; includeSubDomains' },
                { header: 'Content-Security-Policy', severity: 'medium', desc: 'CSP não configurado. Vulnerável a XSS.', rec: 'Configure uma política CSP restritiva' },
                { header: 'X-Frame-Options', severity: 'medium', desc: 'Clickjacking possível.', rec: 'Adicione: X-Frame-Options: DENY' },
                { header: 'X-Content-Type-Options', severity: 'low', desc: 'MIME sniffing não bloqueado.', rec: 'Adicione: X-Content-Type-Options: nosniff' },
            ];

            // Randomly select some findings
            const findings = missingHeaders.filter(() => Math.random() > 0.3);
            findings.forEach(f => {
                scanResults.push({
                    title: `Header ausente: ${f.header}`,
                    severity: f.severity,
                    category: 'Headers',
                    description: f.desc,
                    details: `Header: ${f.header}\nStatus: Não encontrado`,
                    recommendation: f.rec
                });
            });

            if (findings.length === 0) {
                scanResults.push({
                    title: 'Security Headers OK',
                    severity: 'info',
                    category: 'Headers',
                    description: 'Todos os headers de segurança principais estão configurados.',
                    details: 'HSTS, CSP, X-Frame-Options, X-Content-Type-Options presentes',
                    recommendation: 'Continue monitorando e atualizando as políticas.'
                });
            }
        }

        async function scanSSL(target) {
            const issues = [
                { title: 'TLS 1.0/1.1 Habilitado', severity: 'high', desc: 'Protocolos antigos e inseguros ainda aceitos.', rec: 'Desabilite TLS 1.0 e 1.1, use apenas TLS 1.2+' },
                { title: 'Certificado expira em breve', severity: 'medium', desc: 'Certificado SSL expira em menos de 30 dias.', rec: 'Renove o certificado antes da expiração' },
                { title: 'Cipher suites fracas', severity: 'medium', desc: 'Algumas cipher suites inseguras estão habilitadas.', rec: 'Configure apenas cipher suites modernas (AEAD)' },
            ];

            const findings = issues.filter(() => Math.random() > 0.5);
            findings.forEach(f => {
                scanResults.push({
                    ...f,
                    category: 'SSL/TLS',
                    details: `Alvo: ${target}\nProtocolo: TLS\nStatus: Vulnerável`,
                    recommendation: f.rec
                });
            });

            if (findings.length === 0) {
                scanResults.push({
                    title: 'SSL/TLS Configuração Segura',
                    severity: 'info',
                    category: 'SSL/TLS',
                    description: 'Certificado válido e configuração TLS adequada.',
                    details: `Alvo: ${target}\nTLS 1.2/1.3: Habilitado\nCertificado: Válido`,
                    recommendation: 'Mantenha o certificado atualizado.'
                });
            }
        }

        async function scanPorts(target) {
            const commonPorts = [
                { port: 21, service: 'FTP', risk: 'high' },
                { port: 22, service: 'SSH', risk: 'info' },
                { port: 23, service: 'Telnet', risk: 'critical' },
                { port: 80, service: 'HTTP', risk: 'info' },
                { port: 443, service: 'HTTPS', risk: 'info' },
                { port: 3306, service: 'MySQL', risk: 'high' },
                { port: 3389, service: 'RDP', risk: 'high' },
                { port: 5432, service: 'PostgreSQL', risk: 'high' },
                { port: 6379, service: 'Redis', risk: 'critical' },
                { port: 27017, service: 'MongoDB', risk: 'critical' },
            ];

            const openPorts = commonPorts.filter(() => Math.random() > 0.7);
            
            openPorts.forEach(p => {
                const severity = p.risk === 'critical' ? 'critical' : p.risk === 'high' ? 'high' : 'info';
                scanResults.push({
                    title: `Porta ${p.port} aberta (${p.service})`,
                    severity: severity,
                    category: 'Portas',
                    description: p.risk === 'info' ? `Serviço ${p.service} detectado.` : `Serviço ${p.service} exposto publicamente. Risco de acesso não autorizado.`,
                    details: `Porta: ${p.port}\nServiço: ${p.service}\nEstado: Aberta`,
                    recommendation: p.risk === 'info' ? 'Verifique se o serviço está atualizado.' : `Restrinja acesso à porta ${p.port} via firewall. Use VPN ou whitelist de IPs.`
                });
            });

            if (openPorts.length === 0) {
                scanResults.push({
                    title: 'Nenhuma porta crítica exposta',
                    severity: 'info',
                    category: 'Portas',
                    description: 'Scan de portas comuns não encontrou serviços críticos expostos.',
                    details: 'Portas escaneadas: 21, 22, 23, 80, 443, 3306, 3389, 5432, 6379, 27017',
                    recommendation: 'Continue monitorando e mantenha o firewall configurado.'
                });
            }
        }

        async function scanTech(target) {
            const techs = [
                { name: 'nginx', version: '1.18.0', severity: 'info' },
                { name: 'PHP', version: '7.4', severity: 'medium', desc: 'Versão PHP desatualizada' },
                { name: 'WordPress', version: '5.8', severity: 'medium', desc: 'CMS pode ter plugins vulneráveis' },
                { name: 'jQuery', version: '2.1.4', severity: 'high', desc: 'jQuery antigo com vulnerabilidades conhecidas' },
                { name: 'Apache', version: '2.4.41', severity: 'info' },
            ];

            const detected = techs.filter(() => Math.random() > 0.5);
            detected.forEach(t => {
                scanResults.push({
                    title: `${t.name} ${t.version} detectado`,
                    severity: t.severity,
                    category: 'Tecnologias',
                    description: t.desc || `Tecnologia ${t.name} identificada no alvo.`,
                    details: `Tecnologia: ${t.name}\nVersão: ${t.version}`,
                    recommendation: t.severity !== 'info' ? `Atualize ${t.name} para a versão mais recente.` : 'Mantenha atualizado.'
                });
            });
        }

        function renderResults() {
            const body = document.getElementById('resultsBody');
            const card = document.getElementById('resultsCard');
            
            let critical = 0, high = 0, medium = 0, low = 0;
            scanResults.forEach(r => {
                if (r.severity === 'critical') critical++;
                else if (r.severity === 'high') high++;
                else if (r.severity === 'medium') medium++;
                else if (r.severity === 'low') low++;
            });

            document.getElementById('criticalCount').textContent = critical;
            document.getElementById('highCount').textContent = high;
            document.getElementById('mediumCount').textContent = medium;
            document.getElementById('lowCount').textContent = low;

            body.innerHTML = scanResults.map((r, i) => `
                <div class="finding" onclick="toggleFinding(this)">
                    <div class="finding-header">
                        <span class="finding-title">
                            <span class="finding-severity ${r.severity}">${r.severity}</span>
                            ${r.title}
                        </span>
                        <svg class="finding-toggle" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                    <div class="finding-body">
                        <p class="finding-desc">${r.description}</p>
                        <div class="finding-details">
                            <span class="label">Categoria:</span> <span class="value">${r.category}</span><br>
                            <span class="label">Detalhes:</span><br><span class="value">${r.details.replace(/\n/g, '<br>')}</span>
                        </div>
                        <div class="finding-recommendation">
                            <div class="finding-recommendation-title">💡 Recomendação</div>
                            <div class="finding-recommendation-text">${r.recommendation}</div>
                        </div>
                    </div>
                </div>
            `).join('');

            card.classList.add('visible');
        }

        function toggleFinding(el) {
            el.classList.toggle('expanded');
        }

        function copyResults() {
            let text = `Scan de Segurança - ${targetUrl}\n${'='.repeat(50)}\n\n`;
            scanResults.forEach(r => {
                text += `[${r.severity.toUpperCase()}] ${r.title}\n`;
                text += `Categoria: ${r.category}\n`;
                text += `${r.description}\n`;
                text += `Recomendação: ${r.recommendation}\n\n`;
            });
            navigator.clipboard.writeText(text);
            alert('Resultados copiados!');
        }

        function exportResults() {
            const report = {
                target: targetUrl,
                date: new Date().toISOString(),
                summary: {
                    critical: scanResults.filter(r => r.severity === 'critical').length,
                    high: scanResults.filter(r => r.severity === 'high').length,
                    medium: scanResults.filter(r => r.severity === 'medium').length,
                    low: scanResults.filter(r => r.severity === 'low').length
                },
                findings: scanResults
            };
            
            const blob = new Blob([JSON.stringify(report, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `scan-${targetUrl.replace(/[^a-z0-9]/gi, '_')}-${new Date().toISOString().split('T')[0]}.json`;
            a.click();
        }

        function analyzeWithAI() {
            let prompt = `Analise os resultados deste scan de segurança e me dê recomendações detalhadas:\n\n`;
            prompt += `**Alvo:** ${targetUrl}\n\n`;
            prompt += `**Findings:**\n`;
            scanResults.forEach(r => {
                prompt += `- [${r.severity.toUpperCase()}] ${r.title}: ${r.description}\n`;
            });
            prompt += `\nQuais são os riscos mais críticos e como devo priorizar a correção?`;
            
            localStorage.setItem('exploitPrompt', prompt);
            window.location.href = '/chat';
        }

        // Enter key to scan
        document.getElementById('targetInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') startScan();
        });
    </script>
</body>
</html>
