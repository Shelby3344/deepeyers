<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist Compartilhado - DeepEyes</title>
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
            --accent-red: #ef4444;
            --text-primary: #ffffff;
            --text-secondary: #a0a0b0;
            --border-color: #2a2a3a;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; padding: 40px 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 40px; }
        .logo { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 20px; }
        .logo img { width: 40px; height: 40px; }
        .logo-text { font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 1.5rem; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .badge { font-size: 0.7rem; padding: 4px 10px; background: var(--accent-purple); border-radius: 4px; color: white; font-weight: 600; }
        .title { font-size: 2rem; font-weight: 700; margin-bottom: 8px; }
        .subtitle { color: var(--text-secondary); }
        .readonly-badge { display: inline-block; padding: 6px 12px; background: rgba(139, 92, 246, 0.2); border: 1px solid var(--accent-purple); border-radius: 6px; font-size: 0.8rem; color: var(--accent-purple); margin-top: 16px; }
        .progress-bar { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 20px; margin-bottom: 30px; }
        .progress-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px; }
        .progress-title { font-weight: 600; }
        .progress-stats { display: flex; gap: 20px; font-size: 0.85rem; }
        .stat { display: flex; align-items: center; gap: 6px; }
        .stat-dot { width: 10px; height: 10px; border-radius: 50%; }
        .stat-dot.tested { background: var(--accent-cyan); }
        .stat-dot.vuln { background: var(--accent-red); }
        .stat-dot.ok { background: var(--accent-green); }
        .progress-track { height: 8px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden; display: flex; }
        .progress-fill { height: 100%; }
        .progress-fill.tested { background: var(--accent-cyan); }
        .progress-fill.vuln { background: var(--accent-red); }
        .progress-fill.ok { background: var(--accent-green); }
        .section { margin-bottom: 20px; }
        .section-header { padding: 14px 18px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px 10px 0 0; font-weight: 600; }
        .section-items { border: 1px solid var(--border-color); border-top: none; border-radius: 0 0 10px 10px; }
        .item { display: flex; align-items: flex-start; gap: 14px; padding: 14px 18px; background: var(--bg-secondary); border-bottom: 1px solid var(--border-color); }
        .item:last-child { border-bottom: none; border-radius: 0 0 10px 10px; }
        .item-status { width: 20px; height: 20px; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .item-status.tested { background: var(--accent-cyan); }
        .item-status.vuln { background: var(--accent-red); }
        .item-status.ok { background: var(--accent-green); }
        .item-status svg { width: 12px; height: 12px; color: white; }
        .item-content { flex: 1; }
        .item-title { font-size: 0.95rem; margin-bottom: 4px; }
        .item-desc { font-size: 0.8rem; color: var(--text-secondary); }
        .item-note { margin-top: 8px; padding: 10px; background: var(--bg-primary); border-radius: 6px; font-size: 0.85rem; font-family: 'JetBrains Mono', monospace; color: var(--text-secondary); }
        .cta { text-align: center; margin-top: 40px; padding: 30px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; }
        .cta p { color: var(--text-secondary); margin-bottom: 16px; }
        .cta a { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border-radius: 8px; color: var(--bg-primary); font-weight: 600; text-decoration: none; }
        .error { text-align: center; padding: 60px; }
        .error i { font-size: 4rem; color: var(--accent-red); margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="/logo.png" alt="DeepEyes">
                <span class="logo-text">DeepEyes</span>
                <span class="badge">CHECKLIST</span>
            </div>
            <h1 class="title" id="title">Carregando...</h1>
            <p class="subtitle" id="subtitle"></p>
            <div class="readonly-badge"><i class="fas fa-eye"></i> Somente Leitura</div>
        </div>
        
        <div id="content"></div>
        
        <div class="cta">
            <p>Crie seus próprios checklists de pentest com o DeepEyes</p>
            <a href="/"><i class="fas fa-rocket"></i> Começar Agora</a>
        </div>
    </div>

    <script>
        const token = '{{ $token ?? "" }}';
        
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
                { section: "Reconhecimento", items: [{ id: "a1", title: "Documentação da API", desc: "Swagger, OpenAPI" }, { id: "a2", title: "Enumeração de endpoints", desc: "Fuzzing" }, { id: "a3", title: "Versionamento", desc: "APIs antigas" }]},
                { section: "Autenticação", items: [{ id: "a4", title: "API Key exposure", desc: "Keys em código" }, { id: "a5", title: "JWT vulnerabilities", desc: "None alg" }, { id: "a6", title: "OAuth flaws", desc: "Redirect URI" }, { id: "a7", title: "Rate limiting", desc: "Brute force" }]},
                { section: "Autorização", items: [{ id: "a8", title: "BOLA/IDOR", desc: "Broken Object Level Auth" }, { id: "a9", title: "BFLA", desc: "Broken Function Level Auth" }, { id: "a10", title: "Mass Assignment", desc: "Parameter pollution" }]}
            ],
            network: [
                { section: "Descoberta", items: [{ id: "n1", title: "Host discovery", desc: "Nmap" }, { id: "n2", title: "Port scanning", desc: "TCP/UDP" }, { id: "n3", title: "Service enumeration", desc: "Version" }, { id: "n4", title: "OS fingerprinting", desc: "SO" }]},
                { section: "Serviços", items: [{ id: "n5", title: "SMB enumeration", desc: "Shares" }, { id: "n6", title: "FTP anonymous", desc: "Anônimo" }, { id: "n7", title: "SSH vulnerabilities", desc: "Weak ciphers" }, { id: "n8", title: "DNS zone transfer", desc: "AXFR" }]}
            ],
            ad: [
                { section: "Enumeração", items: [{ id: "ad1", title: "Domain enumeration", desc: "BloodHound" }, { id: "ad2", title: "User enumeration", desc: "Kerbrute" }, { id: "ad3", title: "Group Policy", desc: "GPP" }]},
                { section: "Credential Attacks", items: [{ id: "ad4", title: "Kerberoasting", desc: "Service hashes" }, { id: "ad5", title: "AS-REP Roasting", desc: "No preauth" }, { id: "ad6", title: "Password Spraying", desc: "Common passwords" }]}
            ],
            mobile: [
                { section: "Análise Estática", items: [{ id: "m1", title: "Decompilação", desc: "APKTool" }, { id: "m2", title: "Hardcoded secrets", desc: "API keys" }, { id: "m3", title: "Insecure storage", desc: "SharedPrefs" }]},
                { section: "Análise Dinâmica", items: [{ id: "m4", title: "SSL Pinning bypass", desc: "Frida" }, { id: "m5", title: "Root detection", desc: "Bypass" }, { id: "m6", title: "Traffic interception", desc: "Burp" }]}
            ]
        };

        async function loadChecklist() {
            try {
                const res = await fetch(`/api/checklists/public/${token}`);
                if (!res.ok) throw new Error('Not found');
                const { data } = await res.json();
                
                document.getElementById('title').textContent = data.title;
                document.getElementById('subtitle').textContent = data.target_domain || 'Checklist de Pentest';
                
                renderProgress(data);
                renderChecklist(data);
            } catch (e) {
                document.getElementById('title').textContent = 'Checklist não encontrado';
                document.getElementById('content').innerHTML = '<div class="error"><i class="fas fa-exclamation-triangle"></i><p>Este checklist não existe ou não está mais disponível.</p></div>';
            }
        }

        function renderProgress(data) {
            const { tested, vulnerable, ok, total } = data.progress;
            const all = Object.values(checklistData).flatMap(s => s.flatMap(x => x.items)).length;
            
            let html = `
                <div class="progress-bar">
                    <div class="progress-header">
                        <span class="progress-title">Progresso</span>
                        <div class="progress-stats">
                            <span class="stat"><span class="stat-dot tested"></span> ${tested} Testados</span>
                            <span class="stat"><span class="stat-dot vuln"></span> ${vulnerable} Vulneráveis</span>
                            <span class="stat"><span class="stat-dot ok"></span> ${ok} OK</span>
                        </div>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill tested" style="width: ${tested/all*100}%"></div>
                        <div class="progress-fill vuln" style="width: ${vulnerable/all*100}%"></div>
                        <div class="progress-fill ok" style="width: ${ok/all*100}%"></div>
                    </div>
                </div>
            `;
            document.getElementById('content').innerHTML = html;
        }

        function renderChecklist(data) {
            const type = data.type || 'web';
            const states = data.states || {};
            const notes = data.notes || {};
            const sections = checklistData[type] || [];
            
            let html = document.getElementById('content').innerHTML;
            
            sections.forEach(section => {
                html += `<div class="section"><div class="section-header">${section.section}</div><div class="section-items">`;
                section.items.forEach(item => {
                    const state = states[item.id] || '';
                    const note = notes[item.id] || '';
                    html += `
                        <div class="item">
                            <div class="item-status ${state}">
                                ${state ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg>' : ''}
                            </div>
                            <div class="item-content">
                                <div class="item-title">${item.title}</div>
                                <div class="item-desc">${item.desc}</div>
                                ${note ? `<div class="item-note">${note}</div>` : ''}
                            </div>
                        </div>
                    `;
                });
                html += '</div></div>';
            });
            
            document.getElementById('content').innerHTML = html;
        }

        loadChecklist();
    </script>
</body>
</html>
