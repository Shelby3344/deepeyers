<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Relatórios - DeepEyes</title>
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
        .navbar-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; }
        .navbar-links a:hover, .navbar-links a.active { color: var(--accent-cyan); }
        .btn-primary { padding: 10px 24px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border-radius: 8px; color: var(--bg-primary); font-weight: 600; text-decoration: none; }

        .main { padding: 100px 24px 40px; max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        @media (max-width: 1024px) { .main { grid-template-columns: 1fr; } }

        .page-header { grid-column: 1 / -1; margin-bottom: 16px; }
        .page-title { font-size: 2rem; font-weight: 700; margin-bottom: 8px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .page-subtitle { color: var(--text-secondary); }

        .card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; }
        .card-title { font-size: 1rem; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .card-title svg { width: 20px; height: 20px; color: var(--accent-cyan); }

        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 8px; }
        .form-input { width: 100%; padding: 12px 16px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); font-size: 0.95rem; }
        .form-input:focus { outline: none; border-color: var(--accent-cyan); }
        .form-textarea { min-height: 100px; resize: vertical; font-family: inherit; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

        /* Findings Section */
        .findings-list { max-height: 400px; overflow-y: auto; }
        .finding-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px; background: var(--bg-secondary); border-radius: 8px; margin-bottom: 8px; }
        .finding-item:last-child { margin-bottom: 0; }
        .finding-severity { width: 8px; height: 8px; border-radius: 50%; margin-top: 6px; flex-shrink: 0; }
        .finding-severity.critical { background: var(--accent-red); }
        .finding-severity.high { background: var(--accent-orange); }
        .finding-severity.medium { background: #eab308; }
        .finding-severity.low { background: var(--accent-green); }
        .finding-content { flex: 1; }
        .finding-title-input { width: 100%; padding: 8px; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 0.9rem; margin-bottom: 8px; }
        .finding-desc-input { width: 100%; padding: 8px; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 0.85rem; min-height: 60px; resize: vertical; }
        .finding-meta { display: flex; gap: 8px; margin-top: 8px; }
        .finding-select { padding: 6px 10px; background: var(--bg-primary); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 0.8rem; }
        .finding-remove { padding: 6px 10px; background: rgba(239, 68, 68, 0.2); border: none; border-radius: 6px; color: var(--accent-red); font-size: 0.8rem; cursor: pointer; }

        .add-finding-btn { width: 100%; padding: 12px; background: var(--bg-secondary); border: 1px dashed var(--border-color); border-radius: 8px; color: var(--text-secondary); cursor: pointer; margin-top: 12px; transition: all 0.2s; }
        .add-finding-btn:hover { border-color: var(--accent-cyan); color: var(--accent-cyan); }

        /* Preview */
        .preview-card { grid-column: 1 / -1; }
        .preview-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .preview-actions { display: flex; gap: 8px; }
        .preview-btn { padding: 10px 20px; border-radius: 8px; font-size: 0.9rem; cursor: pointer; border: 1px solid var(--border-color); background: var(--bg-secondary); color: var(--text-primary); transition: all 0.2s; }
        .preview-btn:hover { border-color: var(--accent-cyan); }
        .preview-btn.primary { background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border: none; color: var(--bg-primary); font-weight: 600; }

        .preview-content { background: var(--bg-secondary); border-radius: 8px; padding: 32px; max-height: 600px; overflow-y: auto; }
        .preview-content h1 { font-size: 1.5rem; margin-bottom: 8px; color: var(--accent-cyan); }
        .preview-content h2 { font-size: 1.2rem; margin: 24px 0 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border-color); }
        .preview-content h3 { font-size: 1rem; margin: 16px 0 8px; color: var(--text-primary); }
        .preview-content p { color: var(--text-secondary); margin-bottom: 12px; line-height: 1.6; }
        .preview-content .meta { font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 24px; }
        .preview-content .finding-box { background: var(--bg-primary); border-left: 3px solid var(--accent-cyan); padding: 16px; margin: 12px 0; border-radius: 0 8px 8px 0; }
        .preview-content .finding-box.critical { border-color: var(--accent-red); }
        .preview-content .finding-box.high { border-color: var(--accent-orange); }
        .preview-content .finding-box.medium { border-color: #eab308; }
        .preview-content .finding-box.low { border-color: var(--accent-green); }
        .preview-content .severity-badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; margin-left: 8px; }
        .preview-content .severity-badge.critical { background: rgba(239, 68, 68, 0.2); color: var(--accent-red); }
        .preview-content .severity-badge.high { background: rgba(249, 115, 22, 0.2); color: var(--accent-orange); }
        .preview-content .severity-badge.medium { background: rgba(234, 179, 8, 0.2); color: #eab308; }
        .preview-content .severity-badge.low { background: rgba(0, 255, 136, 0.2); color: var(--accent-green); }

        /* Logo Upload */
        .logo-upload { display: flex; align-items: center; gap: 16px; }
        .logo-preview { width: 60px; height: 60px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .logo-preview img { max-width: 100%; max-height: 100%; }
        .logo-upload-btn { padding: 10px 16px; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-primary); cursor: pointer; font-size: 0.85rem; }
        .logo-upload-btn:hover { border-color: var(--accent-cyan); }
        .logo-upload input { display: none; }

        @media (max-width: 768px) { .navbar-links { display: none; } }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-logo">
                <img src="/logo.png" alt="DeepEyes">
                <span class="logo-text">DeepEyes</span>
                <span class="badge">REPORTS</span>
            </a>
            <div class="navbar-links">
                <a href="/">Home</a>
                <a href="/scanner">Scanner</a>
                <a href="/reports" class="active">Relatórios</a>
                <a href="/chat">Chat</a>
            </div>
            <a href="/chat" class="btn-primary">Acessar Chat</a>
        </div>
    </nav>

    <main class="main">
        <div class="page-header">
            <h1 class="page-title">📄 Gerador de Relatórios</h1>
            <p class="page-subtitle">Crie relatórios profissionais de pentest em PDF ou Markdown</p>
        </div>

        <!-- Info Card -->
        <div class="card">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                Informações do Relatório
            </div>
            <div class="form-group">
                <label class="form-label">Título do Relatório</label>
                <input type="text" id="reportTitle" class="form-input" placeholder="Relatório de Pentest - Cliente XYZ" oninput="updatePreview()">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Cliente</label>
                    <input type="text" id="clientName" class="form-input" placeholder="Nome do Cliente" oninput="updatePreview()">
                </div>
                <div class="form-group">
                    <label class="form-label">Data do Teste</label>
                    <input type="date" id="testDate" class="form-input" oninput="updatePreview()">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Pentester</label>
                    <input type="text" id="pentesterName" class="form-input" placeholder="Seu Nome" oninput="updatePreview()">
                </div>
                <div class="form-group">
                    <label class="form-label">Escopo</label>
                    <input type="text" id="scope" class="form-input" placeholder="https://exemplo.com, 192.168.1.0/24" oninput="updatePreview()">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Logo do Cliente (opcional)</label>
                <div class="logo-upload">
                    <div class="logo-preview" id="logoPreview">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" opacity="0.3"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </div>
                    <label class="logo-upload-btn">
                        📁 Escolher Imagem
                        <input type="file" id="logoInput" accept="image/*" onchange="handleLogo(this)">
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Sumário Executivo</label>
                <textarea id="execSummary" class="form-input form-textarea" placeholder="Resumo dos principais achados e recomendações..." oninput="updatePreview()"></textarea>
            </div>
        </div>

        <!-- Findings Card -->
        <div class="card">
            <div class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Vulnerabilidades Encontradas
            </div>
            <div class="findings-list" id="findingsList"></div>
            <button class="add-finding-btn" onclick="addFinding()">+ Adicionar Vulnerabilidade</button>
        </div>

        <!-- Preview Card -->
        <div class="card preview-card">
            <div class="preview-header">
                <div class="card-title" style="margin-bottom: 0;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Preview do Relatório
                </div>
                <div class="preview-actions">
                    <button class="preview-btn" onclick="exportMarkdown()">📝 Exportar MD</button>
                    <button class="preview-btn" onclick="exportHTML()">🌐 Exportar HTML</button>
                    <button class="preview-btn primary" onclick="printReport()">🖨️ Imprimir/PDF</button>
                </div>
            </div>
            <div class="preview-content" id="previewContent">
                <p style="color: var(--text-secondary); text-align: center; padding: 40px;">Preencha os campos para ver o preview do relatório</p>
            </div>
        </div>
    </main>

    <script>
        let findings = [];
        let clientLogo = null;

        function addFinding() {
            const id = Date.now();
            findings.push({
                id,
                title: '',
                description: '',
                severity: 'high',
                impact: '',
                recommendation: ''
            });
            renderFindings();
        }

        function removeFinding(id) {
            findings = findings.filter(f => f.id !== id);
            renderFindings();
            updatePreview();
        }

        function updateFinding(id, field, value) {
            const finding = findings.find(f => f.id === id);
            if (finding) {
                finding[field] = value;
                updatePreview();
            }
        }

        function renderFindings() {
            const list = document.getElementById('findingsList');
            list.innerHTML = findings.map(f => `
                <div class="finding-item">
                    <div class="finding-severity ${f.severity}"></div>
                    <div class="finding-content">
                        <input type="text" class="finding-title-input" placeholder="Título da vulnerabilidade" value="${f.title}" onchange="updateFinding(${f.id}, 'title', this.value)">
                        <textarea class="finding-desc-input" placeholder="Descrição detalhada, impacto e evidências..." onchange="updateFinding(${f.id}, 'description', this.value)">${f.description}</textarea>
                        <textarea class="finding-desc-input" placeholder="Recomendação de correção..." style="min-height: 40px;" onchange="updateFinding(${f.id}, 'recommendation', this.value)">${f.recommendation}</textarea>
                        <div class="finding-meta">
                            <select class="finding-select" onchange="updateFinding(${f.id}, 'severity', this.value); this.parentElement.parentElement.parentElement.querySelector('.finding-severity').className = 'finding-severity ' + this.value;">
                                <option value="critical" ${f.severity === 'critical' ? 'selected' : ''}>Crítico</option>
                                <option value="high" ${f.severity === 'high' ? 'selected' : ''}>Alto</option>
                                <option value="medium" ${f.severity === 'medium' ? 'selected' : ''}>Médio</option>
                                <option value="low" ${f.severity === 'low' ? 'selected' : ''}>Baixo</option>
                            </select>
                            <button class="finding-remove" onclick="removeFinding(${f.id})">🗑️ Remover</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function handleLogo(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    clientLogo = e.target.result;
                    document.getElementById('logoPreview').innerHTML = `<img src="${clientLogo}" alt="Logo">`;
                    updatePreview();
                };
                reader.readAsDataURL(file);
            }
        }

        function updatePreview() {
            const title = document.getElementById('reportTitle').value || 'Relatório de Pentest';
            const client = document.getElementById('clientName').value || 'Cliente';
            const date = document.getElementById('testDate').value || new Date().toISOString().split('T')[0];
            const pentester = document.getElementById('pentesterName').value || 'Pentester';
            const scope = document.getElementById('scope').value || 'N/A';
            const summary = document.getElementById('execSummary').value || '';

            const severityOrder = { critical: 0, high: 1, medium: 2, low: 3 };
            const sortedFindings = [...findings].sort((a, b) => severityOrder[a.severity] - severityOrder[b.severity]);

            const criticalCount = findings.filter(f => f.severity === 'critical').length;
            const highCount = findings.filter(f => f.severity === 'high').length;
            const mediumCount = findings.filter(f => f.severity === 'medium').length;
            const lowCount = findings.filter(f => f.severity === 'low').length;

            let html = `
                ${clientLogo ? `<img src="${clientLogo}" alt="Logo" style="max-width: 150px; margin-bottom: 20px;">` : ''}
                <h1>${title}</h1>
                <p class="meta">
                    <strong>Cliente:</strong> ${client}<br>
                    <strong>Data:</strong> ${date}<br>
                    <strong>Pentester:</strong> ${pentester}<br>
                    <strong>Escopo:</strong> ${scope}
                </p>

                <h2>Sumário Executivo</h2>
                <p>${summary || 'Nenhum sumário fornecido.'}</p>

                <h2>Resumo de Vulnerabilidades</h2>
                <p>
                    🔴 Críticas: ${criticalCount} | 
                    🟠 Altas: ${highCount} | 
                    🟡 Médias: ${mediumCount} | 
                    🟢 Baixas: ${lowCount}
                </p>

                <h2>Vulnerabilidades Detalhadas</h2>
            `;

            if (sortedFindings.length === 0) {
                html += '<p>Nenhuma vulnerabilidade adicionada.</p>';
            } else {
                sortedFindings.forEach((f, i) => {
                    html += `
                        <div class="finding-box ${f.severity}">
                            <h3>${i + 1}. ${f.title || 'Sem título'} <span class="severity-badge ${f.severity}">${f.severity}</span></h3>
                            <p><strong>Descrição:</strong> ${f.description || 'N/A'}</p>
                            <p><strong>Recomendação:</strong> ${f.recommendation || 'N/A'}</p>
                        </div>
                    `;
                });
            }

            html += `
                <h2>Conclusão</h2>
                <p>Este relatório apresenta os resultados do teste de penetração realizado no escopo definido. Recomenda-se a correção das vulnerabilidades identificadas, priorizando as de maior severidade.</p>
                <p style="margin-top: 40px; font-size: 0.85rem; color: var(--text-secondary);">
                    Relatório gerado por DeepEyes em ${new Date().toLocaleDateString('pt-BR')}
                </p>
            `;

            document.getElementById('previewContent').innerHTML = html;
        }

        function exportMarkdown() {
            const title = document.getElementById('reportTitle').value || 'Relatório de Pentest';
            const client = document.getElementById('clientName').value || 'Cliente';
            const date = document.getElementById('testDate').value || new Date().toISOString().split('T')[0];
            const pentester = document.getElementById('pentesterName').value || 'Pentester';
            const scope = document.getElementById('scope').value || 'N/A';
            const summary = document.getElementById('execSummary').value || '';

            let md = `# ${title}\n\n`;
            md += `**Cliente:** ${client}  \n`;
            md += `**Data:** ${date}  \n`;
            md += `**Pentester:** ${pentester}  \n`;
            md += `**Escopo:** ${scope}\n\n`;
            md += `---\n\n`;
            md += `## Sumário Executivo\n\n${summary || 'N/A'}\n\n`;
            md += `## Resumo de Vulnerabilidades\n\n`;
            md += `| Severidade | Quantidade |\n|------------|------------|\n`;
            md += `| 🔴 Crítica | ${findings.filter(f => f.severity === 'critical').length} |\n`;
            md += `| 🟠 Alta | ${findings.filter(f => f.severity === 'high').length} |\n`;
            md += `| 🟡 Média | ${findings.filter(f => f.severity === 'medium').length} |\n`;
            md += `| 🟢 Baixa | ${findings.filter(f => f.severity === 'low').length} |\n\n`;
            md += `## Vulnerabilidades Detalhadas\n\n`;

            const severityOrder = { critical: 0, high: 1, medium: 2, low: 3 };
            const sorted = [...findings].sort((a, b) => severityOrder[a.severity] - severityOrder[b.severity]);

            sorted.forEach((f, i) => {
                md += `### ${i + 1}. ${f.title || 'Sem título'} [${f.severity.toUpperCase()}]\n\n`;
                md += `**Descrição:** ${f.description || 'N/A'}\n\n`;
                md += `**Recomendação:** ${f.recommendation || 'N/A'}\n\n`;
                md += `---\n\n`;
            });

            md += `## Conclusão\n\nEste relatório apresenta os resultados do teste de penetração. Recomenda-se a correção das vulnerabilidades identificadas.\n\n`;
            md += `*Relatório gerado por DeepEyes em ${new Date().toLocaleDateString('pt-BR')}*\n`;

            downloadFile(md, `${title.replace(/[^a-z0-9]/gi, '_')}.md`, 'text/markdown');
        }

        function exportHTML() {
            const content = document.getElementById('previewContent').innerHTML;
            const html = `<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>${document.getElementById('reportTitle').value || 'Relatório'}</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; color: #333; }
        h1 { color: #00d4ff; border-bottom: 2px solid #00d4ff; padding-bottom: 10px; }
        h2 { color: #333; margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 8px; }
        h3 { color: #444; }
        .meta { background: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .finding-box { padding: 15px; margin: 15px 0; border-radius: 8px; border-left: 4px solid #ccc; background: #f9f9f9; }
        .finding-box.critical { border-color: #ef4444; background: #fef2f2; }
        .finding-box.high { border-color: #f97316; background: #fff7ed; }
        .finding-box.medium { border-color: #eab308; background: #fefce8; }
        .finding-box.low { border-color: #22c55e; background: #f0fdf4; }
        .severity-badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .severity-badge.critical { background: #fecaca; color: #dc2626; }
        .severity-badge.high { background: #fed7aa; color: #ea580c; }
        .severity-badge.medium { background: #fef08a; color: #ca8a04; }
        .severity-badge.low { background: #bbf7d0; color: #16a34a; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>${content}</body>
</html>`;
            downloadFile(html, `${document.getElementById('reportTitle').value?.replace(/[^a-z0-9]/gi, '_') || 'relatorio'}.html`, 'text/html');
        }

        function printReport() {
            const content = document.getElementById('previewContent').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`<!DOCTYPE html>
<html>
<head>
    <title>${document.getElementById('reportTitle').value || 'Relatório'}</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; color: #333; }
        h1 { color: #0891b2; border-bottom: 2px solid #0891b2; padding-bottom: 10px; }
        h2 { color: #333; margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 8px; }
        h3 { color: #444; }
        .meta { background: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .finding-box { padding: 15px; margin: 15px 0; border-radius: 8px; border-left: 4px solid #ccc; background: #f9f9f9; page-break-inside: avoid; }
        .finding-box.critical { border-color: #dc2626; background: #fef2f2; }
        .finding-box.high { border-color: #ea580c; background: #fff7ed; }
        .finding-box.medium { border-color: #ca8a04; background: #fefce8; }
        .finding-box.low { border-color: #16a34a; background: #f0fdf4; }
        .severity-badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .severity-badge.critical { background: #fecaca; color: #dc2626; }
        .severity-badge.high { background: #fed7aa; color: #ea580c; }
        .severity-badge.medium { background: #fef08a; color: #ca8a04; }
        .severity-badge.low { background: #bbf7d0; color: #16a34a; }
    </style>
</head>
<body>${content}</body>
</html>`);
            printWindow.document.close();
            printWindow.print();
        }

        function downloadFile(content, filename, type) {
            const blob = new Blob([content], { type });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            URL.revokeObjectURL(url);
        }

        // Initialize with one finding
        addFinding();
        document.getElementById('testDate').value = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>
