<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal - DeepEyes</title>
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
                <a href="/exploits">Exploits</a>
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
        let commandHistory = JSON.parse(localStorage.getItem('terminalHistory') || '[]');
        let historyIndex = -1;

        const commands = {
            help: () => ({
                type: 'info',
                output: `Comandos disponíveis:
  help          - Mostra esta ajuda
  clear         - Limpa o terminal
  nmap          - Scanner de portas (simulado)
  whois         - Consulta WHOIS
  dig           - Consulta DNS
  gobuster      - Fuzzing de diretórios
  nikto         - Scanner de vulnerabilidades web
  sqlmap        - Teste de SQL Injection
  nc            - Netcat
  curl          - Requisições HTTP
  ping          - Teste de conectividade
  traceroute    - Rastreamento de rota
  history       - Mostra histórico de comandos
  export        - Exporta sessão

Dica: Use 'ask [pergunta]' para consultar a IA`
            }),
            clear: () => {
                document.getElementById('terminalOutput').innerHTML = '';
                return null;
            },
            history: () => ({
                type: 'output',
                output: commandHistory.slice(-20).map((c, i) => `  ${i + 1}  ${c.cmd}`).join('\n') || 'Histórico vazio'
            }),
            nmap: (args) => simulateNmap(args),
            whois: (args) => simulateWhois(args),
            dig: (args) => simulateDig(args),
            ping: (args) => simulatePing(args),
            curl: (args) => simulateCurl(args),
            gobuster: (args) => simulateGobuster(args),
            nikto: (args) => simulateNikto(args),
            sqlmap: (args) => simulateSqlmap(args),
            nc: (args) => simulateNc(args),
            traceroute: (args) => simulateTraceroute(args),
            ask: (args) => askAICommand(args),
            echo: (args) => ({ type: 'output', output: args }),
            whoami: () => ({ type: 'output', output: 'root' }),
            pwd: () => ({ type: 'output', output: '/root/pentest' }),
            ls: () => ({ type: 'output', output: 'exploits/  payloads/  scripts/  wordlists/  notes.txt  targets.txt' }),
            id: () => ({ type: 'output', output: 'uid=0(root) gid=0(root) groups=0(root)' }),
            uname: () => ({ type: 'output', output: 'Linux deepeyes-lab 5.15.0-kali1 #1 SMP x86_64 GNU/Linux' }),
            date: () => ({ type: 'output', output: new Date().toString() }),
            uptime: () => ({ type: 'output', output: ' 12:34:56 up 42 days, 13:37,  1 user,  load average: 0.00, 0.01, 0.05' }),
        };

        // Simulated command outputs
        function simulateNmap(args) {
            const target = args.split(' ').pop() || 'target.com';
            return {
                type: 'output',
                output: `Starting Nmap 7.94 ( https://nmap.org )
Nmap scan report for ${target}
Host is up (0.023s latency).

PORT     STATE SERVICE     VERSION
22/tcp   open  ssh         OpenSSH 8.9p1
80/tcp   open  http        nginx 1.18.0
443/tcp  open  ssl/http    nginx 1.18.0
3306/tcp open  mysql       MySQL 8.0.32

Service detection performed. 4 services scanned.
Nmap done: 1 IP address (1 host up) scanned in 12.45 seconds`
            };
        }

        function simulateWhois(args) {
            const domain = args.split(' ').pop() || 'example.com';
            return {
                type: 'output',
                output: `Domain Name: ${domain.toUpperCase()}
Registry Domain ID: 123456789_DOMAIN
Registrar: Example Registrar, Inc.
Creation Date: 2020-01-15T00:00:00Z
Updated Date: 2024-01-15T00:00:00Z
Registrar Registration Expiration Date: 2025-01-15T00:00:00Z
Registrant Organization: Example Corp
Registrant Country: US
Name Server: NS1.EXAMPLE.COM
Name Server: NS2.EXAMPLE.COM`
            };
        }

        function simulateDig(args) {
            const domain = args.split(' ').pop() || 'example.com';
            return {
                type: 'output',
                output: `; <<>> DiG 9.18.12 <<>> ${domain}
;; ANSWER SECTION:
${domain}.        300    IN    A    93.184.216.34
${domain}.        300    IN    A    93.184.216.35

;; Query time: 23 msec
;; SERVER: 8.8.8.8#53(8.8.8.8)
;; WHEN: ${new Date().toUTCString()}
;; MSG SIZE  rcvd: 56`
            };
        }

        function simulatePing(args) {
            const target = args.split(' ').pop() || '8.8.8.8';
            return {
                type: 'output',
                output: `PING ${target} (${target}) 56(84) bytes of data.
64 bytes from ${target}: icmp_seq=1 ttl=117 time=12.3 ms
64 bytes from ${target}: icmp_seq=2 ttl=117 time=11.8 ms
64 bytes from ${target}: icmp_seq=3 ttl=117 time=12.1 ms
64 bytes from ${target}: icmp_seq=4 ttl=117 time=11.9 ms

--- ${target} ping statistics ---
4 packets transmitted, 4 received, 0% packet loss, time 3004ms
rtt min/avg/max/mdev = 11.8/12.0/12.3/0.2 ms`
            };
        }

        function simulateCurl(args) {
            return {
                type: 'output',
                output: `HTTP/1.1 200 OK
Server: nginx/1.18.0
Date: ${new Date().toUTCString()}
Content-Type: text/html; charset=UTF-8
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
Strict-Transport-Security: max-age=31536000

<!DOCTYPE html>
<html>
<head><title>Example</title></head>
<body>...</body>
</html>`
            };
        }

        function simulateGobuster(args) {
            return {
                type: 'output',
                output: `===============================================================
Gobuster v3.5
===============================================================
[+] Url:            http://target.com
[+] Threads:        10
[+] Wordlist:       /usr/share/wordlists/common.txt
===============================================================
/admin                (Status: 302) [Size: 0]
/api                  (Status: 200) [Size: 1234]
/backup               (Status: 403) [Size: 278]
/config               (Status: 403) [Size: 278]
/css                  (Status: 301) [Size: 178]
/images               (Status: 301) [Size: 178]
/js                   (Status: 301) [Size: 178]
/login                (Status: 200) [Size: 4521]
/robots.txt           (Status: 200) [Size: 68]
/uploads              (Status: 403) [Size: 278]
===============================================================
Finished
===============================================================`
            };
        }

        function simulateNikto(args) {
            return {
                type: 'output',
                output: `- Nikto v2.5.0
---------------------------------------------------------------------------
+ Target IP:          93.184.216.34
+ Target Hostname:    target.com
+ Target Port:        80
+ Start Time:         ${new Date().toISOString()}
---------------------------------------------------------------------------
+ Server: nginx/1.18.0
+ /: The X-Content-Type-Options header is not set.
+ /admin/: Admin login page found.
+ /backup/: Backup directory found.
+ /config.php.bak: Config backup file found.
+ /phpinfo.php: PHP info file found.
+ OSVDB-3092: /sitemap.xml: Sitemap found.
+ 7915 requests: 0 error(s) and 6 item(s) reported
+ End Time:           ${new Date().toISOString()}
---------------------------------------------------------------------------`
            };
        }

        function simulateSqlmap(args) {
            return {
                type: 'output',
                output: `        ___
       __H__
 ___ ___[']_____ ___ ___  {1.7.2#stable}
|_ -| . ["]     | .'| . |
|___|_  [']_|_|_|__,|  _|
      |_|V...       |_|

[*] starting @ ${new Date().toTimeString().split(' ')[0]}

[INFO] testing connection to the target URL
[INFO] testing if the target URL is stable
[INFO] target URL is stable
[INFO] testing if GET parameter 'id' is dynamic
[INFO] GET parameter 'id' appears to be dynamic
[INFO] heuristic (basic) test shows that GET parameter 'id' might be injectable
[INFO] testing for SQL injection on GET parameter 'id'
[INFO] testing 'AND boolean-based blind'
[INFO] GET parameter 'id' is 'AND boolean-based blind' injectable
[INFO] testing 'MySQL >= 5.0 AND error-based'
[INFO] GET parameter 'id' is 'MySQL >= 5.0 AND error-based' injectable

[*] Parameter: id (GET)
    Type: boolean-based blind
    Title: AND boolean-based blind
    Payload: id=1 AND 1=1

    Type: error-based
    Title: MySQL >= 5.0 AND error-based
    Payload: id=1 AND (SELECT 1 FROM(SELECT COUNT(*),CONCAT(0x7171,0x7171)a FROM INFORMATION_SCHEMA.TABLES GROUP BY a)b)

[INFO] the back-end DBMS is MySQL
back-end DBMS: MySQL >= 5.0`
            };
        }

        function simulateNc(args) {
            if (args.includes('-lvnp')) {
                return {
                    type: 'warning',
                    output: `listening on [any] 4444 ...
[Aguardando conexão reversa...]
(Use Ctrl+C para cancelar)`
                };
            }
            return { type: 'output', output: 'Connection established.' };
        }

        function simulateTraceroute(args) {
            const target = args.split(' ').pop() || '8.8.8.8';
            return {
                type: 'output',
                output: `traceroute to ${target}, 30 hops max, 60 byte packets
 1  gateway (192.168.1.1)  1.234 ms  1.123 ms  1.089 ms
 2  10.0.0.1 (10.0.0.1)  5.432 ms  5.321 ms  5.234 ms
 3  72.14.215.85 (72.14.215.85)  12.345 ms  12.234 ms  12.123 ms
 4  108.170.252.129 (108.170.252.129)  13.456 ms  13.345 ms  13.234 ms
 5  ${target} (${target})  14.567 ms  14.456 ms  14.345 ms`
            };
        }

        function askAICommand(question) {
            if (!question.trim()) {
                return { type: 'error', output: 'Uso: ask [sua pergunta]' };
            }
            localStorage.setItem('exploitPrompt', question);
            window.location.href = '/chat';
            return null;
        }

        // Execute command
        async function executeCommand(input) {
            const output = document.getElementById('terminalOutput');
            const trimmed = input.trim();
            
            if (!trimmed) return;

            // Add command to output
            const cmdLine = document.createElement('div');
            cmdLine.className = 'terminal-line command';
            cmdLine.textContent = trimmed;
            output.appendChild(cmdLine);

            // Save to history
            commandHistory.push({ cmd: trimmed, time: new Date().toISOString() });
            localStorage.setItem('terminalHistory', JSON.stringify(commandHistory.slice(-100)));
            historyIndex = commandHistory.length;
            updateHistoryList();

            // Parse command
            const parts = trimmed.split(' ');
            const cmd = parts[0].toLowerCase();
            const args = parts.slice(1).join(' ');

            // Execute
            let result;
            if (commands[cmd]) {
                result = await commands[cmd](args);
            } else {
                result = { type: 'error', output: `Command not found: ${cmd}. Type 'help' for available commands.` };
            }

            // Show output
            if (result) {
                const outLine = document.createElement('div');
                outLine.className = `terminal-line ${result.type}`;
                outLine.textContent = result.output;
                output.appendChild(outLine);
            }

            // Scroll to bottom
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
                // Simple tab completion
                const val = e.target.value;
                const cmds = Object.keys(commands);
                const match = cmds.find(c => c.startsWith(val));
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
