<!DOCTYPE html>
<html lang="pt-BR" style="background: #0a0a0f;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - DeepEyes</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="apple-touch-icon" href="/logo.png">
    <meta name="theme-color" content="#0a0a0f">
    <style>html, body { background: #0a0a0f !important; }</style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'de-bg': '#0a0a0f',
                        'de-bg-secondary': '#12121a',
                        'de-bg-tertiary': '#1a1a24',
                        'de-neon': '#00FF88',
                        'de-cyan': '#00D4FF',
                        'de-purple': '#8b5cf6',
                        'de-red': '#EF4444',
                        'de-orange': '#F97316',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: #1a1a24;
            --accent-green: #00ff88;
            --accent-cyan: #00d4ff;
            --accent-purple: #8b5cf6;
            --text-primary: #ffffff;
            --text-secondary: #a0a0b0;
            --border-color: rgba(0, 212, 255, 0.15);
        }
        
        html, body { 
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            min-height: 100vh;
        }
        
        code, pre { font-family: 'JetBrains Mono', monospace; }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: rgba(139, 92, 246, 0.3);
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.1);
        }

        .stat-card {
            background: linear-gradient(135deg, var(--bg-card) 0%, rgba(139, 92, 246, 0.05) 100%);
        }

        .glow-text {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-purple));
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .nav-link {
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--accent-cyan);
        }

        .nav-link.active {
            color: var(--accent-cyan);
            border-bottom: 2px solid var(--accent-cyan);
        }

        .quick-action {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: block;
        }

        .quick-action:hover {
            border-color: var(--accent-purple);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.2);
        }

        .quick-action i {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .activity-bar {
            background: rgba(139, 92, 246, 0.2);
            border-radius: 4px;
            transition: height 0.3s ease;
        }

        .activity-bar:hover {
            background: var(--accent-purple);
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-secondary); }
        ::-webkit-scrollbar-thumb { 
            background: linear-gradient(180deg, var(--accent-cyan), var(--accent-purple)); 
            border-radius: 3px; 
        }
    </style>
</head>
<body class="text-white">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-de-bg/90 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="/" class="flex items-center gap-2">
                        <img src="/logo.png" alt="DeepEyes" class="w-8 h-8">
                        <span class="font-bold text-lg glow-text">DeepEyes</span>
                    </a>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="/dashboard" class="nav-link active text-sm font-medium pb-1">Dashboard</a>
                        <a href="/chat" class="nav-link text-gray-400 text-sm font-medium pb-1">Chat</a>
                        <a href="/terminal" class="nav-link text-gray-400 text-sm font-medium pb-1">Terminal</a>
                        <a href="/checklist" class="nav-link text-gray-400 text-sm font-medium pb-1">Checklist</a>
                        <a href="/scanner" class="nav-link text-gray-400 text-sm font-medium pb-1">Scanner</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/profile" class="flex items-center gap-2 text-gray-400 hover:text-white transition">
                        <i class="fas fa-user-circle text-xl"></i>
                        <span id="userName" class="hidden sm:inline text-sm">Perfil</span>
                    </a>
                    <button onclick="logout()" class="text-gray-400 hover:text-red-400 transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 pb-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold mb-2">
                Bem-vindo, <span id="userNameHeader" class="glow-text">Usuário</span>
            </h1>
            <p class="text-gray-400">Aqui está um resumo da sua atividade na plataforma.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card card p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-gray-400 text-sm">Sessões</span>
                    <i class="fas fa-comments text-de-cyan opacity-50"></i>
                </div>
                <div class="text-3xl font-bold" id="statSessions">-</div>
                <div class="text-xs text-gray-500 mt-1">Total de conversas</div>
            </div>
            <div class="stat-card card p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-gray-400 text-sm">Mensagens</span>
                    <i class="fas fa-message text-de-purple opacity-50"></i>
                </div>
                <div class="text-3xl font-bold" id="statMessages">-</div>
                <div class="text-xs text-gray-500 mt-1">Interações com IA</div>
            </div>
            <div class="stat-card card p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-gray-400 text-sm">Checklists</span>
                    <i class="fas fa-list-check text-de-neon opacity-50"></i>
                </div>
                <div class="text-3xl font-bold" id="statChecklists">-</div>
                <div class="text-xs text-gray-500 mt-1">Auditorias salvas</div>
            </div>
            <div class="stat-card card p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-gray-400 text-sm">Uso Diário</span>
                    <i class="fas fa-bolt text-de-orange opacity-50"></i>
                </div>
                <div class="text-3xl font-bold"><span id="statUsed">-</span><span class="text-lg text-gray-500">/<span id="statLimit">-</span></span></div>
                <div class="w-full bg-gray-700/30 rounded-full h-2 mt-2">
                    <div class="progress-bar h-2" id="usageBar" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-bolt text-de-cyan"></i> Ações Rápidas
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                <a href="/chat" class="quick-action">
                    <i class="fas fa-plus text-de-cyan"></i>
                    <span class="text-sm text-gray-300">Nova Sessão</span>
                </a>
                <a href="/terminal" class="quick-action">
                    <i class="fas fa-terminal text-de-neon"></i>
                    <span class="text-sm text-gray-300">Terminal</span>
                </a>
                <a href="/checklist" class="quick-action">
                    <i class="fas fa-clipboard-check text-de-purple"></i>
                    <span class="text-sm text-gray-300">Checklist</span>
                </a>
                <a href="/scanner" class="quick-action">
                    <i class="fas fa-radar text-de-orange"></i>
                    <span class="text-sm text-gray-300">Scanner</span>
                </a>
                <a href="/reports" class="quick-action">
                    <i class="fas fa-file-alt text-blue-400"></i>
                    <span class="text-sm text-gray-300">Relatórios</span>
                </a>
                <a href="/profile" class="quick-action">
                    <i class="fas fa-cog text-gray-400"></i>
                    <span class="text-sm text-gray-300">Configurações</span>
                </a>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Activity Chart -->
            <div class="lg:col-span-2 card p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-de-purple"></i> Atividade Semanal
                </h2>
                <div class="flex items-end justify-between h-40 gap-2" id="activityChart">
                    <!-- Bars will be inserted here -->
                </div>
            </div>

            <!-- Plan Info -->
            <div class="card p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-crown text-yellow-400"></i> Seu Plano
                </h2>
                <div class="text-center py-4">
                    <div class="text-2xl font-bold glow-text mb-2" id="planName">-</div>
                    <div class="text-gray-400 text-sm mb-4">
                        <span id="planRemaining">-</span> requisições restantes hoje
                    </div>
                    <a href="/#pricing" class="inline-block px-6 py-2 bg-gradient-to-r from-de-purple to-de-cyan text-white rounded-lg font-medium hover:opacity-90 transition">
                        <i class="fas fa-arrow-up mr-2"></i>Fazer Upgrade
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Recent Sessions -->
            <div class="card p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-de-cyan"></i> Sessões Recentes
                </h2>
                <div id="recentSessions" class="space-y-3">
                    <div class="text-gray-500 text-sm text-center py-4">Carregando...</div>
                </div>
                <a href="/chat" class="block text-center text-de-cyan text-sm mt-4 hover:underline">
                    Ver todas as sessões →
                </a>
            </div>

            <!-- Recent Checklists -->
            <div class="card p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-de-neon"></i> Checklists Recentes
                </h2>
                <div id="recentChecklists" class="space-y-3">
                    <div class="text-gray-500 text-sm text-center py-4">Carregando...</div>
                </div>
                <a href="/checklist" class="block text-center text-de-neon text-sm mt-4 hover:underline">
                    Ver todos os checklists →
                </a>
            </div>
        </div>
    </main>

    <script>
        const API_BASE = '/api';
        let token = localStorage.getItem('token');

        // Redirect if not logged in
        if (!token) {
            window.location.href = '/';
        }

        async function api(endpoint, options = {}) {
            const response = await fetch(`${API_BASE}${endpoint}`, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    ...options.headers,
                },
            });
            
            if (response.status === 401) {
                localStorage.removeItem('token');
                window.location.href = '/';
                return;
            }
            
            return response.json();
        }

        async function loadDashboard() {
            try {
                // Load user info
                const userRes = await api('/auth/me');
                if (userRes.data) {
                    document.getElementById('userName').textContent = userRes.data.name;
                    document.getElementById('userNameHeader').textContent = userRes.data.name;
                }

                // Load dashboard stats
                const statsRes = await api('/dashboard/stats');
                if (statsRes) {
                    // Stats
                    document.getElementById('statSessions').textContent = statsRes.stats.total_sessions;
                    document.getElementById('statMessages').textContent = statsRes.stats.total_messages;
                    document.getElementById('statChecklists').textContent = statsRes.stats.total_checklists;
                    
                    // Usage
                    document.getElementById('statUsed').textContent = statsRes.daily_usage.used;
                    document.getElementById('statLimit').textContent = statsRes.daily_usage.limit;
                    document.getElementById('usageBar').style.width = `${statsRes.daily_usage.percentage}%`;
                    
                    // Plan
                    document.getElementById('planName').textContent = statsRes.plan.name;
                    document.getElementById('planRemaining').textContent = statsRes.daily_usage.remaining;

                    // Activity Chart
                    renderActivityChart(statsRes.weekly_activity);

                    // Recent Sessions
                    renderRecentSessions(statsRes.recent_sessions);

                    // Recent Checklists
                    renderRecentChecklists(statsRes.recent_checklists);
                }
            } catch (error) {
                console.error('Error loading dashboard:', error);
            }
        }

        function renderActivityChart(data) {
            const container = document.getElementById('activityChart');
            const maxCount = Math.max(...data.map(d => d.count), 1);
            
            container.innerHTML = data.map(d => {
                const height = (d.count / maxCount) * 100;
                return `
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full flex items-end justify-center" style="height: 120px;">
                            <div class="activity-bar w-full max-w-[40px]" style="height: ${Math.max(height, 5)}%;" title="${d.count} mensagens"></div>
                        </div>
                        <span class="text-xs text-gray-500">${d.date}</span>
                    </div>
                `;
            }).join('');
        }

        function renderRecentSessions(sessions) {
            const container = document.getElementById('recentSessions');
            
            if (!sessions || sessions.length === 0) {
                container.innerHTML = `
                    <div class="text-gray-500 text-sm text-center py-4">
                        Nenhuma sessão ainda. <a href="/chat" class="text-de-cyan hover:underline">Criar primeira sessão</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = sessions.map(s => `
                <a href="/chat?session=${s.id}" class="block p-3 bg-de-bg-secondary rounded-lg hover:bg-de-bg-tertiary transition">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-sm truncate">${s.title || 'Sessão sem título'}</div>
                            <div class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 bg-de-purple/20 text-de-purple rounded text-xs">${s.profile || 'pentest'}</span>
                                ${s.target_domain ? `<span class="truncate">${s.target_domain}</span>` : ''}
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 ml-2"></i>
                    </div>
                </a>
            `).join('');
        }

        function renderRecentChecklists(checklists) {
            const container = document.getElementById('recentChecklists');
            
            if (!checklists || checklists.length === 0) {
                container.innerHTML = `
                    <div class="text-gray-500 text-sm text-center py-4">
                        Nenhum checklist ainda. <a href="/checklist" class="text-de-neon hover:underline">Criar primeiro checklist</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = checklists.map(c => `
                <a href="/checklist?id=${c.id}" class="block p-3 bg-de-bg-secondary rounded-lg hover:bg-de-bg-tertiary transition">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-sm truncate">${c.title}</div>
                            <div class="text-xs text-gray-500 mt-1">${c.updated_at}</div>
                        </div>
                        <div class="flex items-center gap-2 ml-2">
                            <div class="text-xs text-de-neon">${c.progress}%</div>
                            <div class="w-16 bg-gray-700/30 rounded-full h-1.5">
                                <div class="progress-bar h-1.5" style="width: ${c.progress}%"></div>
                            </div>
                        </div>
                    </div>
                </a>
            `).join('');
        }

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/';
        }

        // Load dashboard on page load
        loadDashboard();
    </script>
</body>
</html>
