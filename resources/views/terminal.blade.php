<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal - DeepEyes</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>html:not(.auth-checked) body { visibility: hidden; }</style>
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

        .terminal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px; }
        .terminal-title { font-size: 1.2rem; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .terminal-title i { color: var(--accent-green); }
        .terminal-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .terminal-btn { padding: 8px 16px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 6px; color: var(--text-primary); font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .terminal-btn:hover { border-color: var(--accent-cyan); background: var(--bg-secondary); }
        .terminal-btn i { font-size: 14px; }

        .rate-limit-info { font-size: 0.75rem; color: var(--text-secondary); display: flex; align-items: center; gap: 8px; }
        .rate-limit-info i { color: var(--accent-orange); }

        .terminal-container { flex: 1; display: flex; gap: 16px; min-height: 500px; }

        .terminal-window { flex: 1; background: var(--bg-secondary); border: 1px solid var(--border-color); border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; }
        .terminal-bar { padding: 12px 16px; background: var(--bg-card); border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 8px; }
        .terminal-dot { width: 12px; height: 12px; border-radius: 50%; }
        .terminal-dot.red { background: #ef4444; }
        .terminal-dot.yellow { background: #eab308; }
        .terminal-dot.green { background: #22c55e; }
        .terminal-path { margin-left: 12px; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; color: var(--text-secondary); }
        .terminal-status { margin-left: auto; font-size: 0.75rem; color: var(--accent-green); display: flex; align-items: center; gap: 6px; }
        .terminal-status.busy { color: var(--accent-orange); }
        .terminal-status i { font-size: 10px; }

        .terminal-output { flex: 1; padding: 16px; overflow-y: auto; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; line-height: 1.6; }
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
        .terminal-input:disabled { opacity: 0.5; }

        .terminal-sidebar { width: 300px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; }
        .sidebar-header { padding: 16px; border-bottom: 1px solid var(--border-color); font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; }
        .sidebar-header i { color: var(--accent-cyan); }
        .sidebar-content { flex: 1; overflow-y: auto; }
        .sidebar-section { padding: 12px 16px; border-bottom: 1px solid var(--border-color); }
        .sidebar-section-title { font-size: 0.7rem; color: var(--accent-cyan); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
        .command-item { padding: 8px 12px; background: var(--bg-secondary); border-radius: 6px; margin-bottom: 6px; cursor: pointer; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--text-secondary); transition: all 0.2s; display: flex; align-items: center; justify-content: space-between; }
        .command-item:hover { background: var(--bg-primary); color: var(--accent-cyan); }
        .command-item.not-installed { opacity: 0.5; }
        .command-item .status-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--accent-green); }
        .command-item.not-installed .status-dot { background: var(--accent-red); }

        .history-item { padding: 8px 12px; border-radius: 6px; margin-bottom: 4px; cursor: pointer; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--text-secondary); display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
        .history-item:hover { background: var(--bg-secondary); color: var(--text-primary); }
        .history-item i { font-size: 10px; color: var(--accent-purple); }

        .loading-spinner { display: inline-block; width: 12px; height: 12px; border: 2px solid var(--accent-cyan); border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

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
                <i class="fas fa-terminal"></i>
                Terminal de Pentest
            </div>
            <div class="rate-limit-info">
                <i class="fas fa-gauge-high"></i>
                <span id="rateLimitInfo">10 cmd/min • 60 cmd/hora</span>
            </div>
            <div class="terminal-actions">
                <button class="terminal-btn" onclick="clearTerminal()">
                    <i class="fas fa-trash-can"></i> Limpar
                </button>
                <button class="terminal-btn" onclick="exportHistory()">
                    <i class="fas fa-download"></i> Exportar
                </button>
                <button class="terminal-btn" onclick="askAI()">
                    <i class="fas fa-robot"></i> Analisar com IA
                </button>
            </div>
        </div>

        <div class="terminal-container">
            <div class="terminal-window">
                <div class="terminal-bar">
                    <span class="terminal-dot red"></span>
                    <span class="terminal-dot yellow"></span>
                    <span class="terminal-dot green"></span>
                    <span class="terminal-path">deepeyes@server:~</span>
                    <span class="terminal-status" id="terminalStatus">
                        <i class="fas fa-circle"></i> Pronto
                    </span>
                </div>
                <div class="terminal-output" id="terminalOutput">
                    <div class="terminal-line info">╔═══════════════════════════════════════════════════════════════════╗</div>
                    <div class="terminal-line info">║  DeepEyes Terminal v2.0 - Comandos executados no servidor         ║</div>
                    <div class="terminal-line info">║  Digite 'help' para ver comandos • 'commands' para ver status     ║</div>
                    <div class="terminal-line info">╚═══════════════════════════════════════════════════════════════════╝</div>
                    <div class="terminal-line output"></div>
                </div>
                <div class="terminal-input-line">
                    <span class="terminal-prompt">$</span>
                    <input type="text" class="terminal-input" id="terminalInput" placeholder="Digite um comando..." autofocus>
                </div>
            </div>

            <div class="terminal-sidebar">
                <div class="sidebar-header"><i class="fas fa-bolt"></i> Comandos Rápidos</div>
                <div class="sidebar-content">
                    <div class="sidebar-section">
                        <div class="sidebar-section-title"><i class="fas fa-search"></i> Reconhecimento</div>
                        <div class="command-item" onclick="runCommand('whois example.com')">
                            <span>whois example.com</span>
                            <span class="status-dot"></span>
                        </div>
                        <div class="command-item" onclick="runCommand('dig example.com')">
                            <span>dig example.com</span>
                            <span class="status-dot"></span>
                        </div>
                        <div class="command-item" onclick="runCommand('nslookup example.com')">
                            <span>nslookup example.com</span>
                            <span class="status-dot"></span>
                        </div>
                        <div class="command-item" onclick="runCommand('host example.com')">
                            <span>host example.com</span>
                            <span class="status-dot"></span>
                        </div>
                    </div>
                    <div class="sidebar-section">
                        <div class="sidebar-section-title"><i class="fas fa-network-wired"></i> Rede</div>
                        <div class="command-item" onclick="runCommand('ping -c 4 example.com')">
                            <span>ping -c 4 example.com</span>
                            <span class="status-dot"></span>
                        </div>
                        <div class="command-item" onclick="runCommand('traceroute example.com')">
                            <span>traceroute example.com</span>
                            <span class="status-dot"></span>
                        </div>
                        <div class="command-item" onclick="runCommand('curl -I https://example.com')">
                            <span>curl -I https://example.com</span>
                            <span class="status-dot"></span>
                        </div>
                    </div>
                    <div class="sidebar-section">
                        <div class="sidebar-section-title"><i class="fas fa-radar"></i> Scanner</div>
                        <div class="command-item" onclick="runCommand('nmap -sV example.com')">
                            <span>nmap -sV example.com</span>
                            <span class="status-dot"></span>
                        </div>
                        <div class="command-item" onclick="runCommand('subfinder -d example.com')">
                            <span>subfinder -d example.com</span>
                            <span class="status-dot"></span>
                        </div>
                    </div>
                    <div class="sidebar-section">
                        <div class="sidebar-section-title"><i class="fas fa-clock-rotate-left"></i> Histórico</div>
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
        let isExecuting = false;
        let availableCommands = {};

        // Carrega lista de comandos disponíveis
        async function loadCommands() {
            try {
                const response = await fetch('/api/terminal/commands', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.success) {
                    availableCommands = data.commands;
                    if (data.rate_limit) {
                        document.getElementById('rateLimitInfo').textContent = 
                            `${data.rate_limit.per_minute} cmd/min • ${data.rate_limit.per_hour} cmd/hora`;
                    }
                }
            } catch (e) {
                console.error('Erro ao carregar comandos:', e);
            }
        }

        // Comandos locais
        const localCommands = {
            help: () => ({
                type: 'info',
                output: `Comandos disponíveis (executados no servidor):

  RECONHECIMENTO DNS:
    whois <domain>       - Consulta informações WHOIS
    dig <domain>         - Consulta DNS detalhada
    nslookup <domain>    - Consulta DNS simples
    host <domain>        - Resolução de DNS

  REDE:
    ping <host>          - Teste de conectividade (4 pacotes)
    traceroute <host>    - Rastreamento de rota

  HTTP/WEB:
    curl -I <url>        - Headers HTTP
    curl <url>           - Conteúdo da página

  SCANNER (se instalado):
    nmap <target>        - Scanner de portas
    nikto -h <url>       - Scanner de vulnerabilidades
    gobuster <args>      - Fuzzing de diretórios
    wpscan <args>        - Scanner WordPress
    subfinder -d <dom>   - Descoberta de subdomínios

  TERMINAL:
    clear                - Limpa o terminal
    history              - Mostra histórico
    commands             - Lista comandos e status
    help                 - Mostra esta ajuda

⚠️  Rate limit: 10 comandos/minuto, 60 comandos/hora
🔒  Todos os comandos são logados para auditoria`
            }),
            clear: () => {
                document.getElementById('terminalOutput').innerHTML = '';
                return null;
            },
            history: () => ({
                type: 'output',
                output: commandHistory.slice(-20).map((c, i) => `  ${i + 1}  ${c.cmd}`).join('\n') || 'Histórico vazio'
            }),
            commands: () => {
                let output = 'Status dos comandos:\n\n';
                for (const [cmd, info] of Object.entries(availableCommands)) {
                    const status = info.installed ? '✓' : '✗';
                    const color = info.installed ? '' : ' (não instalado)';
                    output += `  ${status} ${cmd.padEnd(12)} - ${info.description}${color}\n`;
                }
                return { type: 'info', output };
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

                if (response.status === 401) {
                    localStorage.removeItem('token');
                    window.location.replace('/?login=required');
                    return { type: 'error', output: 'Sessão expirada. Redirecionando...' };
                }

                if (response.status === 429) {
                    const data = await response.json();
                    return { type: 'warning', output: data.output || 'Rate limit excedido. Aguarde.' };
                }

                const data = await response.json();
                return {
                    type: data.type || 'output',
                    output: data.output || 'Sem resposta'
                };
            } catch (error) {
                return { type: 'error', output: `Erro de conexão: ${error.message}` };
            }
        }

        // Atualiza status do terminal
        function setTerminalStatus(busy, text) {
            const status = document.getElementById('terminalStatus');
            const input = document.getElementById('terminalInput');
            isExecuting = busy;
            input.disabled = busy;
            
            if (busy) {
                status.className = 'terminal-status busy';
                status.innerHTML = `<span class="loading-spinner"></span> ${text || 'Executando...'}`;
            } else {
                status.className = 'terminal-status';
                status.innerHTML = '<i class="fas fa-circle"></i> Pronto';
                input.focus();
            }
        }

        // Executa comando
        async function executeCommand(input) {
            const output = document.getElementById('terminalOutput');
            const trimmed = input.trim();
            
            if (!trimmed || isExecuting) return;

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

            // Executa
            let result;
            if (localCommands[cmd]) {
                result = localCommands[cmd]();
            } else {
                setTerminalStatus(true, 'Executando...');
                result = await executeRemoteCommand(trimmed);
                setTerminalStatus(false);
            }

            // Mostra output
            if (result) {
                const outLine = document.createElement('div');
                outLine.className = `terminal-line ${result.type}`;
                outLine.style.whiteSpace = 'pre-wrap';
                outLine.textContent = result.output;
                output.appendChild(outLine);
            }

            // Linha vazia após output
            const spacer = document.createElement('div');
            spacer.className = 'terminal-line output';
            spacer.innerHTML = '&nbsp;';
            output.appendChild(spacer);

            output.scrollTop = output.scrollHeight;
        }

        function runCommand(cmd) {
            const input = document.getElementById('terminalInput');
            input.value = cmd;
            input.focus();
        }

        function clearTerminal() {
            document.getElementById('terminalOutput').innerHTML = '';
        }

        function exportHistory() {
            let text = `DeepEyes Terminal Session\n`;
            text += `Date: ${new Date().toISOString()}\n`;
            text += `${'='.repeat(60)}\n\n`;
            
            document.querySelectorAll('.terminal-line').forEach(line => {
                if (line.classList.contains('command')) {
                    text += `$ ${line.textContent}\n`;
                } else if (line.textContent.trim()) {
                    text += `${line.textContent}\n`;
                }
            });

            const blob = new Blob([text], { type: 'text/plain' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = `terminal-${new Date().toISOString().split('T')[0]}.txt`;
            a.click();
        }

        function askAI() {
            let context = 'Analise os resultados do terminal:\n\n';
            document.querySelectorAll('.terminal-line').forEach(line => {
                if (line.classList.contains('command')) {
                    context += `$ ${line.textContent}\n`;
                } else if (line.textContent.trim()) {
                    context += `${line.textContent}\n`;
                }
            });
            context += '\n\nIdentifique vulnerabilidades e sugira próximos passos.';
            
            localStorage.setItem('exploitPrompt', context);
            window.location.href = '/chat';
        }

        function updateHistoryList() {
            const list = document.getElementById('historyList');
            const recent = commandHistory.slice(-5).reverse();
            list.innerHTML = recent.map(h => `
                <div class="history-item" onclick="runCommand('${h.cmd.replace(/'/g, "\\'")}')">
                    <i class="fas fa-chevron-right"></i>
                    <span>${h.cmd.length > 22 ? h.cmd.substring(0, 22) + '...' : h.cmd}</span>
                </div>
            `).join('') || '<div style="padding: 8px 12px; color: var(--text-secondary); font-size: 0.75rem;">Nenhum comando</div>';
        }

        // Event listeners
        document.getElementById('terminalInput').addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !isExecuting) {
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
                const allCmds = [...Object.keys(localCommands), ...Object.keys(availableCommands)];
                const match = allCmds.find(c => c.startsWith(val));
                if (match) e.target.value = match + ' ';
            } else if (e.key === 'l' && e.ctrlKey) {
                e.preventDefault();
                clearTerminal();
            }
        });

        // Initialize
        loadCommands();
        updateHistoryList();
    </script>
</body>
</html>