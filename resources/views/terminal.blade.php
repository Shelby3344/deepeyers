<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal - DeepEyes</title>
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
        body { font-family: 'Inter', sans-serif; background: var(--bg-primary); color: var(--text-primary); min-height: 100vh; display: flex; flex-direction: column; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        .navbar { padding: 16px 24px; background: rgba(10, 10, 15, 0.95); border-bottom: 1px solid var(--border-color); }
        .navbar-inner { max-width: 1400px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; }
        .navbar-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .navbar-logo img { width: 32px; height: 32px; }
        .logo-text { font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 1.2rem; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .badge { font-size: 0.6rem; padding: 3px 8px; background: var(--accent-green); border-radius: 4px; color: var(--bg-primary); font-weight: 600; }
        .navbar-links { display: flex; gap: 32px; }
        .navbar-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; }
        .navbar-links a:hover, .navbar-links a.active { color: var(--accent-cyan); }
        .btn-primary { padding: 10px 24px; background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green)); border-radius: 8px; color: var(--bg-primary); font-weight: 600; text-decoration: none; }

        .main { flex: 1; display: flex; flex-direction: column; padding: 24px; max-width: 1400px; margin: 0 auto; width: 100%; }

        /* Terminal Header */
        .terminal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .terminal-title { font-size: 1.2rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .terminal-title svg { width: 24px; height: 24px; color: var(--accent-green); }
        .terminal-actions { display: flex; gap: 8px; }
        .terminal-btn { padding: 8px 16px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px; }
        .terminal-btn:hover { border-color: var(--accent-cyan); }
        .terminal-btn svg { width: 16px; height: 16px; }

        /* Terminal Container */
        .terminal-container { flex: 1; display: flex; gap: 16px; min-height: 0; }

        /* Terminal Window */
        .terminal-window { flex: 1; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; }
        .terminal-bar { padding: 12px 16px; background: var(--bg-card); border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 8px; }
        .terminal-dot { width: 12px; height: 12px; border-radius: 50%; }
        .terminal-dot.red { background: #ef4444; }
        .terminal-dot.yellow { background: #eab308; }
        .terminal-dot.green { background: #22c55e; }
        .terminal-path { margin-left: 12px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: var(--text-secondary); }

        .terminal-output { flex: 1; padding: 16px; overflow-y: auto; font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; line-height: 1.6; }
        .terminal-line { margin-bottom: 4px; white-space: pre-wrap; word-break: break-all; }
        .terminal-line.command { color: var(--accent-cyan); }
        .terminal-line.command::before { content: '$ '; color: var(--accent-green); }
        .terminal-line.output { color: var(--text-secondary); }
        .terminal-line.error { color: var(--accent-red); }
        .terminal-line.success { color: var(--accent-green); }
        .terminal-line.info { color: var(--accent-purple); }
        .terminal-line.warning { color: var(--accent-orange); }

        .terminal-input-line { display: flex; align-items: center; padding: 12px 16px; background: var(--bg-card); border-top: 1px solid var(--border-color); }
        .terminal-prompt { color: var(--accent-green); font-family: 'JetBrains Mono', monospace; margin-right: 8px; }
        .terminal-input { flex: 1; background: transparent; border: none; color: var(--text-primary); font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; outline: none; }
        .terminal-input::placeholder { color: var(--text-secondary); }

        /* Sidebar */
        .terminal-sidebar { width: 280px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; }
        .sidebar-header { padding: 16px; border-bottom: 1px solid var(--border-color); font-weight: 600; font-size: 0.9rem; }
        .sidebar-content { flex: 1; overflow-y: auto; }
        .sidebar-section { padding: 12px 16px; border-bottom: 1px solid var(--border-color); }
        .sidebar-section-title { font-size: 0.75rem; color: var(--accent-cyan); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .command-item { padding: 8px 12px; background: var(--bg-secondary); border-radius: 6px; margin-bottom: 6px; cursor: pointer; font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: var(--text-secondary); transition: all 0.2s; }
        .command-item:hover { background: var(--bg-primary); color: var(--accent-cyan); }
        .command-item:last-child { margin-bottom: 0; }

        /* History */
        .history-item { padding: 8px 12px; border-radius: 6px; margin-bottom: 4px; cursor: pointer; font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: var(--text-secondary); display: flex; align-items: center; gap: 8px; }
        .history-item:hover { background: var(--bg-secondary); color: var(--text-primary); }
        .history-time { font-size: 0.7rem; color: var(--text-secondary); }

        @media (max-width: 1024px) {
            .terminal-sidebar { display: none; }
            .navbar-links { display: none; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-logo">
                <img src="/logo.png" alt="DeepEyes">
                <span class="logo-text">DeepEyes</span>
                <span class="badge">TERMINAL</span>
            </a>
            <div class="navbar-links">
                <a href="/">Home</a>
                <a href="/scanner">Scanner</a>
                <a href="/terminal" class="active">Terminal</a>
                <a href="/chat">Chat</a>
            </div>
            <a href="/chat" class="btn-primary">Acessar Chat</a>
        </div>
    </nav>

    <main class="main">
        <div class="terminal-header">
            <div class="terminal-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
                Terminal Interativo
            </div>
            <div class="terminal-actions">
                <button class="terminal-btn" onclick="clearTerminal()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                    Limpar
                </button>
                <button class="terminal-btn" onclick="exportHistory()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    Exportar
                </button>
                <button class="terminal-btn" onclick="askAI()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/></svg>
                    Perguntar à IA
                </button>
            </div>
        </div>

        <div class="terminal-container">
            <div class="terminal-window">
                <div class="terminal-bar">
                    <span class="terminal-dot red"></span>
                    <span class="terminal-dot yellow"></span>
                    <span class="terminal-dot green"></span>
                    <span class="terminal-path">deepeyes@lab:~</span>
                </div>
                <div class="terminal-output" id="terminalOutput">
                    <div class="terminal-line info">╔══════════════════════════════════════════════════════════════╗</div>
                    <div class="terminal-line info">║  DeepEyes Terminal v1.0 - Ambiente de Pentest Simulado       ║</div>
                    <div class="terminal-line info">║  Digite 'help' para ver comandos disponíveis                 ║</div>
                    <div class="terminal-line info">╚══════════════════════════════════════════════════════════════╝</div>
                    <div class="terminal-line output"></div>
                </div>
                <div class="terminal-input-line">
                    <span class="terminal-prompt">$</span>
                    <input type="text" class="terminal-input" id="terminalInput" placeholder="Digite um comando..." autofocus>
                </div>
            </div>

            <div class="terminal-sidebar">
                <div class="sidebar-header">⚡ Comandos Rápidos</div>
                <div class="sidebar-content">
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">Reconhecimento</div>
                        <div class="command-item" onclick="runCommand('nmap -sV target.com')">nmap -sV target.com</div>
                        <div class="command-item" onclick="runCommand('whois target.com')">whois target.com</div>
                        <div class="command-item" onclick="runCommand('dig target.com')">dig target.com</div>
                        <div class="command-item" onclick="runCommand('subfinder -d target.com')">subfinder -d target.com</div>
                    </div>
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">Web</div>
                        <div class="command-item" onclick="runCommand('gobuster dir -u http://target.com -w common.txt')">gobuster dir</div>
                        <div class="command-item" onclick="runCommand('nikto -h http://target.com')">nikto -h target</div>
                        <div class="command-item" onclick="runCommand('sqlmap -u \"http://target.com?id=1\"')">sqlmap</div>
                        <div class="command-item" onclick="runCommand('wpscan --url http://target.com')">wpscan</div>
                    </div>
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">Exploitation</div>
                        <div class="command-item" onclick="runCommand('msfconsole')">msfconsole</div>
                        <div class="command-item" onclick="runCommand('nc -lvnp 4444')">nc listener</div>
                        <div class="command-item" onclick="runCommand('python3 -c \"import pty;pty.spawn(\\\"/bin/bash\\\")\"')">pty spawn</div>
                    </div>
                    <div class="sidebar-section">
                        <div class="sidebar-section-title">Histórico</div>
                        <div id="historyList"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const token = localStorage.getItem('token');
        let commandHistory = JSON.parse(localStorage.getItem('terminalHistory') || '[]');
        let historyIndex = -1;

        // Comandos locais (não precisam de API)
        const localCommands = {
            help: () => ({
                type: 'info',
                output: `Comandos disponíveis (executados no servidor):

  RECONHECIMENTO:
    whois <domain>      - Consulta WHOIS
    dig <domain>        - Consulta DNS
    nslookup <domain>   - Consulta DNS
    host <domain>       - Consulta DNS
    ping <host>         - Teste de conectividade
    traceroute <host>   - Rastreamento de rota

  WEB/HTTP:
    curl <url>          - Requisições HTTP
    nmap <target>       - Scanner de portas
    nikto -h <url>      - Scanner de vulnerabilidades
    gobuster <args>     - Fuzzing de diretórios
    wpscan <args>       - Scanner WordPress

  UTILITÁRIOS:
    clear               - Limpa o terminal
    history             - Mostra histórico
    export              - Exporta sessão
    ask <pergunta>      - Consulta a IA

⚠️  Comandos são executados no servidor com whitelist de segurança.`
            }),
            clear: () => {
                document.getElementById('terminalOutput').innerHTML = '';
                return null;
            },
            history: () => ({
                type: 'output',
                output: commandHistory.slice(-20).map((c, i) => `  ${i + 1}  ${c.cmd}`).join('\n') || 'Histórico vazio'
            }),
            ask: (args) => {
                if (!args.trim()) {
                    return { type: 'error', output: 'Uso: ask [sua pergunta]' };
                }
                localStorage.setItem('exploitPrompt', args);
                window.location.href = '/chat';
                return null;
            },
            export: () => {
                exportHistory();
                return { type: 'success', output: 'Sessão exportada!' };
            }
        };

        // Executa comando via API
        async function executeRemoteCommand(command) {
            try {
                const response = await fetch('/api/terminal/execute', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ command })
                });

                const data = await response.json();
                
                if (response.status === 401) {
                    localStorage.removeItem('token');
                    window.location.replace('/?login=required');
                    return { type: 'error', output: 'Sessão expirada. Redirecionando...' };
                }

                return {
                    type: data.type || 'output',
                    output: data.output || 'Sem resposta'
                };
            } catch (error) {
                return {
                    type: 'error',
                    output: `Erro de conexão: ${error.message}`
                };
            }
        }

        // Executa comando
        async function executeCommand(input) {
            const output = document.getElementById('terminalOutput');
            const trimmed = input.trim();
            
            if (!trimmed) return;

            // Adiciona comando ao output
            const cmdLine = document.createElement('div');
            cmdLine.className = 'terminal-line command';
            cmdLine.textContent = trimmed;
            output.appendChild(cmdLine);

            // Salva no histórico
            commandHistory.push({ cmd: trimmed, time: new Date().toISOString() });
            localStorage.setItem('terminalHistory', JSON.stringify(commandHistory.slice(-100)));
            historyIndex = commandHistory.length;
            updateHistoryList();

            // Parse do comando
            const parts = trimmed.split(' ');
            const cmd = parts[0].toLowerCase();
            const args = parts.slice(1).join(' ');

            // Mostra loading
            const loadingLine = document.createElement('div');
            loadingLine.className = 'terminal-line output';
            loadingLine.textContent = '⏳ Executando...';
            loadingLine.id = 'loading-line';
            output.appendChild(loadingLine);
            output.scrollTop = output.scrollHeight;

            // Executa
            let result;
            if (localCommands[cmd]) {
                result = await localCommands[cmd](args);
            } else {
                // Executa via API
                result = await executeRemoteCommand(trimmed);
            }

            // Remove loading
            const loading = document.getElementById('loading-line');
            if (loading) loading.remove();

            // Mostra output
            if (result) {
                const outLine = document.createElement('div');
                outLine.className = `terminal-line ${result.type}`;
                outLine.style.whiteSpace = 'pre-wrap';
                outLine.textContent = result.output;
                output.appendChild(outLine);
            }

            // Scroll para baixo
            output.scrollTop = output.scrollHeight;
        }

        function runCommand(cmd) {
            document.getElementById('terminalInput').value = cmd;
            document.getElementById('terminalInput').focus();
        }

        function clearTerminal() {
            document.getElementById('terminalOutput').innerHTML = '';
        }

        function exportHistory() {
            let text = `DeepEyes Terminal Session\n`;
            text += `Date: ${new Date().toISOString()}\n`;
            text += `${'='.repeat(50)}\n\n`;
            
            const lines = document.querySelectorAll('.terminal-line');
            lines.forEach(line => {
                if (line.classList.contains('command')) {
                    text += `$ ${line.textContent}\n`;
                } else {
                    text += `${line.textContent}\n`;
                }
            });

            const blob = new Blob([text], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `terminal-session-${new Date().toISOString().split('T')[0]}.txt`;
            a.click();
        }

        function askAI() {
            const lines = document.querySelectorAll('.terminal-line');
            let context = 'Contexto do terminal:\n\n';
            lines.forEach(line => {
                if (line.classList.contains('command')) {
                    context += `$ ${line.textContent}\n`;
                } else if (line.textContent.trim()) {
                    context += `${line.textContent}\n`;
                }
            });
            context += '\n\nMe ajude a analisar esses resultados e sugerir próximos passos.';
            
            localStorage.setItem('exploitPrompt', context);
            window.location.href = '/chat';
        }

        function updateHistoryList() {
            const list = document.getElementById('historyList');
            const recent = commandHistory.slice(-5).reverse();
            list.innerHTML = recent.map(h => `
                <div class="history-item" onclick="runCommand('${h.cmd.replace(/'/g, "\\'")}')">
                    <span>${h.cmd.length > 25 ? h.cmd.substring(0, 25) + '...' : h.cmd}</span>
                </div>
            `).join('') || '<div style="padding: 8px 12px; color: var(--text-secondary); font-size: 0.8rem;">Nenhum comando</div>';
        }

        // Event listeners
        document.getElementById('terminalInput').addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                executeCommand(e.target.value);
                e.target.value = '';
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (historyIndex > 0) {
                    historyIndex--;
                    e.target.value = commandHistory[historyIndex]?.cmd || '';
                }
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (historyIndex < commandHistory.length - 1) {
                    historyIndex++;
                    e.target.value = commandHistory[historyIndex]?.cmd || '';
                } else {
                    historyIndex = commandHistory.length;
                    e.target.value = '';
                }
            } else if (e.key === 'Tab') {
                e.preventDefault();
                const val = e.target.value.toLowerCase();
                const allCmds = [...Object.keys(localCommands), 'whois', 'dig', 'nslookup', 'host', 'ping', 'traceroute', 'curl', 'nmap', 'nikto', 'gobuster', 'wpscan', 'subfinder'];
                const match = allCmds.find(c => c.startsWith(val));
                if (match) e.target.value = match + ' ';
            } else if (e.key === 'l' && e.ctrlKey) {
                e.preventDefault();
                clearTerminal();
            }
        });

        // Initialize
        updateHistoryList();
    </script>
</body>
</html>
