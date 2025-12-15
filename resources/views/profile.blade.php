<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Perfil - DeepEyes</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="apple-touch-icon" href="/logo.png">
    <script>
        if (localStorage.getItem('token')) {
            document.documentElement.classList.add('has-token');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
        
        .has-token #authModal { display: none !important; }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: #94a3b8;
            transition: all 0.2s;
        }
        
        .sidebar-link:hover {
            background: rgba(71, 85, 105, 0.3);
            color: white;
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(99, 102, 241, 0.2));
            color: #a855f7;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }
        
        .sidebar-link.active i { color: #a855f7; }
        
        .card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(71, 85, 105, 0.5);
            border-radius: 16px;
        }
        
        .input-field {
            width: 100%;
            background: #1e293b;
            border: 1px solid #475569;
            border-radius: 8px;
            padding: 12px 16px;
            color: white;
            transition: border-color 0.2s;
        }
        
        .input-field:focus { outline: none; border-color: #a855f7; }
        
        .btn-primary {
            background: linear-gradient(135deg, #a855f7, #6366f1);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-primary:hover { opacity: 0.9; }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
        
        .btn-secondary {
            background: #334155;
            color: #94a3b8;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-secondary:hover { background: #475569; color: white; }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            border: 1px solid rgba(239, 68, 68, 0.3);
            transition: all 0.2s;
        }
        
        .btn-danger:hover { background: rgba(239, 68, 68, 0.3); }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }
        
        .notification.success { background: #065f46; border: 1px solid #10b981; color: #6ee7b7; }
        .notification.error { background: #7f1d1d; border: 1px solid #ef4444; color: #fca5a5; }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .avatar-container {
            position: relative;
            width: 80px;
            height: 80px;
            cursor: pointer;
        }
        
        .avatar-ring {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(135deg, #a855f7, #6366f1);
        }
        
        .avatar-inner {
            width: 100%;
            height: 100%;
            background: #1e293b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        .avatar-inner img { width: 100%; height: 100%; object-fit: cover; }
        
        .avatar-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
            border-radius: 50%;
        }
        
        .avatar-container:hover .avatar-overlay { opacity: 1; }
        
        .stat-card {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(71, 85, 105, 0.3);
            border-radius: 12px;
            padding: 20px;
        }
        
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #334155;
        }
        
        .data-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #1e293b;
            font-size: 14px;
        }
        
        .data-table tr:hover td { background: rgba(71, 85, 105, 0.1); }
        
        .badge {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-admin { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        .badge-redteam { background: rgba(249, 115, 22, 0.2); color: #fb923c; }
        .badge-analyst { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .badge-user { background: rgba(107, 114, 128, 0.2); color: #9ca3af; }
        .badge-active { background: rgba(34, 197, 94, 0.2); color: #4ade80; }
        .badge-banned { background: rgba(239, 68, 68, 0.2); color: #f87171; }
        
        .page-content { display: none; }
        .page-content.active { display: block; }
        
        .search-input {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 10px 14px 10px 40px;
            color: white;
            width: 100%;
            max-width: 300px;
        }
        
        .search-input:focus { outline: none; border-color: #a855f7; }
    </style>
</head>
<body class="gradient-bg min-h-screen text-gray-100">
    
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900/80 border-r border-slate-700/50 flex flex-col">
            <!-- Logo -->
            <div class="p-4 border-b border-slate-700/50">
                <a href="/" class="flex items-center gap-3">
                    <img src="/logo.png" alt="DeepEyes" class="h-10 w-10">
                    <div>
                        <span class="text-lg font-bold block">DeepEyes</span>
                        <span class="text-xs text-gray-500">Painel de Controle</span>
                    </div>
                </a>
            </div>
            
            <!-- User Info -->
            <div class="p-4 border-b border-slate-700/50">
                <div class="flex items-center gap-3">
                    <div style="width: 40px; height: 40px;" class="rounded-full overflow-hidden bg-slate-700 flex items-center justify-center">
                        <i id="sidebarAvatarIcon" class="fas fa-user text-sm text-gray-500"></i>
                        <img id="sidebarAvatarImg" src="" alt="" class="w-full h-full object-cover" style="display: none;">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p id="sidebarUserName" class="text-sm font-medium text-white truncate">Carregando...</p>
                        <p id="sidebarUserRole" class="text-xs text-gray-500">-</p>
                    </div>
                </div>
            </div>
            
            <!-- Menu -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 px-3">Minha Conta</p>
                
                <a href="#" class="sidebar-link active" data-page="profile">
                    <i class="fas fa-user w-5"></i>
                    <span>Meu Perfil</span>
                </a>
                <a href="#" class="sidebar-link" data-page="security">
                    <i class="fas fa-shield-halved w-5"></i>
                    <span>Segurança</span>
                </a>
                <a href="#" class="sidebar-link" data-page="usage">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span>Uso & Limites</span>
                </a>
                
                <!-- Admin Menu -->
                <div id="adminMenu" class="hidden">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mt-6 mb-3 px-3">Administração</p>
                    
                    <a href="#" class="sidebar-link" data-page="admin-dashboard">
                        <i class="fas fa-gauge-high w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" class="sidebar-link" data-page="admin-users">
                        <i class="fas fa-users w-5"></i>
                        <span>Usuários</span>
                    </a>
                    <a href="#" class="sidebar-link" data-page="admin-sessions">
                        <i class="fas fa-comments w-5"></i>
                        <span>Sessões</span>
                    </a>
                    <a href="#" class="sidebar-link" data-page="admin-plans">
                        <i class="fas fa-gem w-5"></i>
                        <span>Planos</span>
                    </a>
                    <a href="#" class="sidebar-link" data-page="admin-logs">
                        <i class="fas fa-scroll w-5"></i>
                        <span>Logs</span>
                    </a>
                    <a href="#" class="sidebar-link" data-page="admin-settings">
                        <i class="fas fa-cog w-5"></i>
                        <span>Configurações</span>
                    </a>
                </div>
            </nav>
            
            <!-- Footer -->
            <div class="p-4 border-t border-slate-700/50 space-y-2">
                <a href="/" class="sidebar-link text-sm">
                    <i class="fas fa-arrow-left w-5"></i>
                    <span>Voltar ao Chat</span>
                </a>
                <button onclick="logout()" class="sidebar-link text-sm w-full text-red-400 hover:text-red-300 hover:bg-red-500/10">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Sair</span>
                </button>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="sticky top-0 bg-slate-900/80 backdrop-blur-sm border-b border-slate-700/50 px-8 py-4 z-10">
                <div class="flex items-center justify-between">
                    <h1 id="pageTitle" class="text-xl font-bold">Meu Perfil</h1>
                </div>
            </header>
            
            <div class="p-8">
                <!-- Profile Page -->
                <div id="page-profile" class="page-content active">
                    <div class="card p-6 mb-6">
                        <div class="flex items-center gap-6">
                            <div class="avatar-container" onclick="document.getElementById('avatarInput').click()">
                                <div class="avatar-ring">
                                    <div class="avatar-inner">
                                        <i id="avatarIcon" class="fas fa-user text-2xl text-gray-500"></i>
                                        <img id="avatarImage" src="" alt="Avatar" style="display: none;">
                                        <div class="avatar-overlay">
                                            <i class="fas fa-camera text-white"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="file" id="avatarInput" accept="image/*" class="hidden" onchange="uploadAvatar(this)">
                            
                            <div class="flex-1">
                                <h2 id="profileName" class="text-xl font-bold text-white">-</h2>
                                <p id="profileEmail" class="text-gray-400">-</p>
                                <div class="flex gap-2 mt-2">
                                    <span id="profilePlan" class="badge bg-purple-500/20 text-purple-400">-</span>
                                    <span id="profileRole" class="badge">-</span>
                                </div>
                            </div>
                            
                            <button onclick="deleteAvatar()" class="text-gray-500 hover:text-red-400 transition-colors" title="Remover avatar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-pen text-purple-400"></i>
                            Editar Informações
                        </h3>
                        <form id="profileForm" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Nome</label>
                                    <input type="text" id="inputName" class="input-field" placeholder="Seu nome">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Email</label>
                                    <input type="email" id="inputEmail" class="input-field" placeholder="seu@email.com">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save mr-2"></i>Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Security Page -->
                <div id="page-security" class="page-content">
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-key text-yellow-400"></i>
                            Alterar Senha
                        </h3>
                        <form id="passwordForm" class="space-y-4 max-w-md">
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Senha Atual</label>
                                <input type="password" id="inputCurrentPassword" class="input-field" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Nova Senha</label>
                                <input type="password" id="inputNewPassword" class="input-field" placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Confirmar Nova Senha</label>
                                <input type="password" id="inputConfirmPassword" class="input-field" placeholder="••••••••">
                            </div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-lock mr-2"></i>Alterar Senha
                            </button>
                        </form>
                    </div>
                    
                    <div class="card p-6 mt-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-key text-purple-400"></i>
                            Tokens de API
                        </h3>
                        <p class="text-gray-400 text-sm mb-4">Gerencie seus tokens de acesso à API.</p>
                        <button class="btn-secondary" onclick="showNotification('Funcionalidade em desenvolvimento', 'error')">
                            <i class="fas fa-plus mr-2"></i>Gerar Novo Token
                        </button>
                    </div>
                </div>
                
                <!-- Usage Page -->
                <div id="page-usage" class="page-content">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="stat-card">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                    <i class="fas fa-bolt text-purple-400"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-white" id="statDailyReqs">0</p>
                                    <p class="text-xs text-gray-500">Requisições Hoje</p>
                                </div>
                            </div>
                            <div class="h-1 bg-slate-700 rounded-full overflow-hidden">
                                <div id="usageBar" class="h-full bg-gradient-to-r from-purple-500 to-indigo-500 transition-all" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1" id="usageLimitText">de 0 disponíveis</p>
                        </div>
                        
                        <div class="stat-card">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center">
                                    <i class="fas fa-comments text-green-400"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-white" id="statTotalSessions">0</p>
                                    <p class="text-xs text-gray-500">Sessões Criadas</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                                    <i class="fas fa-gem text-blue-400"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-white" id="statPlanName">-</p>
                                    <p class="text-xs text-gray-500">Plano Atual</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Limites do Plano</h3>
                        <div class="space-y-3" id="planFeatures"></div>
                    </div>
                </div>
                
                <!-- Admin Dashboard -->
                <div id="page-admin-dashboard" class="page-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="stat-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-3xl font-bold text-white" id="adminStatUsers">-</p>
                                    <p class="text-sm text-gray-500">Usuários</p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                                    <i class="fas fa-users text-xl text-purple-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-3xl font-bold text-white" id="adminStatSessions">-</p>
                                    <p class="text-sm text-gray-500">Sessões</p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                                    <i class="fas fa-comments text-xl text-green-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-3xl font-bold text-white" id="adminStatMessages">-</p>
                                    <p class="text-sm text-gray-500">Mensagens</p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                    <i class="fas fa-message text-xl text-blue-400"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-3xl font-bold text-white" id="adminStatToday">-</p>
                                    <p class="text-sm text-gray-500">Reqs Hoje</p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                                    <i class="fas fa-bolt text-xl text-yellow-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Últimos Usuários Registrados</h3>
                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody id="recentUsersTable">
                                    <tr><td colspan="4" class="text-center text-gray-500">Carregando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Users -->
                <div id="page-admin-users" class="page-content">
                    <div class="card p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                            <h3 class="text-lg font-semibold text-white">Gerenciar Usuários</h3>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                    <input type="text" id="searchUsers" class="search-input" placeholder="Buscar usuário...">
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Plano</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTable">
                                    <tr><td colspan="6" class="text-center text-gray-500">Carregando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Sessions -->
                <div id="page-admin-sessions" class="page-content">
                    <div class="card p-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
                            <h3 class="text-lg font-semibold text-white">Todas as Sessões</h3>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                <input type="text" id="searchSessions" class="search-input" placeholder="Buscar sessão...">
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Usuário</th>
                                        <th>Perfil</th>
                                        <th>Mensagens</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="sessionsTable">
                                    <tr><td colspan="6" class="text-center text-gray-500">Carregando...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Plans -->
                <div id="page-admin-plans" class="page-content">
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Planos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="plansGrid"></div>
                    </div>
                </div>
                
                <!-- Admin Logs -->
                <div id="page-admin-logs" class="page-content">
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Logs do Sistema</h3>
                        <div class="bg-slate-950 rounded-lg p-4 font-mono text-sm text-green-400 h-96 overflow-y-auto" id="logsContainer">
                            <p class="text-gray-500">Carregando logs...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Settings -->
                <div id="page-admin-settings" class="page-content">
                    <div class="card p-6 mb-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Configurações do Sistema</h3>
                        <div class="space-y-4 max-w-xl">
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Nome do Sistema</label>
                                <input type="text" class="input-field" value="DeepEyes" disabled>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">API DeepSeek</label>
                                <input type="password" class="input-field" value="••••••••••••••••" disabled>
                                <p class="text-xs text-gray-500 mt-1">Configure no arquivo .env</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card p-6 border-red-500/30">
                        <h3 class="text-lg font-semibold text-red-400 mb-4">Zona de Perigo</h3>
                        <div class="space-y-3">
                            <button class="btn-danger" onclick="clearAllSessions()">
                                <i class="fas fa-trash mr-2"></i>Limpar Todas Sessões
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Avatar Preview Modal -->
    <div id="avatarPreviewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/95" onclick="closeAvatarPreview()">
        <div class="relative">
            <button onclick="closeAvatarPreview()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <img id="avatarPreviewImg" src="" alt="Avatar" class="max-w-[300px] max-h-[300px] rounded-2xl border-4 border-purple-500/50 shadow-2xl">
            <p id="avatarPreviewName" class="text-center text-white font-semibold mt-3"></p>
        </div>
    </div>
    
    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/90">
        <div class="bg-slate-900 rounded-2xl p-6 w-full max-w-md border border-slate-700 mx-4">
            <h3 class="text-lg font-semibold text-white mb-4">Editar Usuário</h3>
            <form id="editUserForm" class="space-y-4">
                <input type="hidden" id="editUserId">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nome</label>
                    <input type="text" id="editUserName" class="input-field">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email</label>
                    <input type="email" id="editUserEmail" class="input-field">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Role</label>
                        <select id="editUserRole" class="input-field">
                            <option value="user">Usuário</option>
                            <option value="analyst">Analista</option>
                            <option value="redteam">Red Team</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Plano</label>
                        <select id="editUserPlan" class="input-field">
                            <option value="">Carregando...</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nova Senha (deixe vazio para não alterar)</label>
                    <input type="password" id="editUserPassword" class="input-field" placeholder="••••••••">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditUserModal()" class="btn-secondary flex-1">Cancelar</button>
                    <button type="submit" class="btn-primary flex-1">Salvar</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Auth Modal -->
    <div id="authModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/95">
        <div class="text-center">
            <img src="/logo.png" alt="DeepEyes" class="h-16 mx-auto mb-4">
            <h2 class="text-xl font-bold text-white mb-2">Acesso Restrito</h2>
            <p class="text-gray-400 mb-6">Faça login para acessar.</p>
            <a href="/" class="btn-primary inline-flex items-center gap-2">
                <i class="fas fa-sign-in-alt"></i>
                Ir para Login
            </a>
        </div>
    </div>

    <script>
        const API_URL = '/api';
        const token = localStorage.getItem('token');
        let currentUser = null;
        let allUsers = [];
        let allSessions = [];
        let allPlans = [];
        
        if (!token) {
            document.getElementById('authModal').classList.remove('hidden');
            document.getElementById('authModal').classList.add('flex');
        } else {
            init();
        }
        
        async function init() {
            await loadProfile();
            setupNavigation();
        }
        
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>${message}`;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }
        
        async function api(endpoint, options = {}) {
            const res = await fetch(`${API_URL}${endpoint}`, {
                ...options,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...options.headers
                }
            });
            
            if (res.status === 401) {
                localStorage.removeItem('token');
                location.reload();
                return;
            }
            
            return res.json();
        }
        
        async function loadProfile() {
            try {
                const data = await api('/profile');
                currentUser = data.user;
                renderProfile(data);
                
                if (currentUser && currentUser.role === 'admin') {
                    document.getElementById('adminMenu').classList.remove('hidden');
                    loadAdminData();
                }
            } catch (err) {
                console.error('Erro ao carregar perfil:', err);
            }
        }
        
        function renderProfile(data) {
            const { user, plan, usage } = data;
            
            document.getElementById('sidebarUserName').textContent = user.name;
            document.getElementById('sidebarUserRole').textContent = getRoleName(user.role);
            
            if (user.avatar) {
                document.getElementById('sidebarAvatarIcon').style.display = 'none';
                document.getElementById('sidebarAvatarImg').src = user.avatar;
                document.getElementById('sidebarAvatarImg').style.display = 'block';
            }
            
            document.getElementById('profileName').textContent = user.name;
            document.getElementById('profileEmail').textContent = user.email;
            document.getElementById('profilePlan').textContent = plan.name;
            document.getElementById('profileRole').textContent = getRoleName(user.role);
            document.getElementById('profileRole').className = `badge badge-${user.role}`;
            
            if (user.avatar) {
                document.getElementById('avatarIcon').style.display = 'none';
                document.getElementById('avatarImage').src = user.avatar;
                document.getElementById('avatarImage').style.display = 'block';
            }
            
            document.getElementById('inputName').value = user.name;
            document.getElementById('inputEmail').value = user.email;
            
            document.getElementById('statDailyReqs').textContent = usage.daily_requests;
            document.getElementById('statPlanName').textContent = plan.name;
            
            const limit = usage.daily_limit === -1 ? 'ilimitado' : usage.daily_limit;
            document.getElementById('usageLimitText').textContent = `de ${limit} disponíveis`;
            
            if (usage.daily_limit > 0) {
                const percent = Math.min((usage.daily_requests / usage.daily_limit) * 100, 100);
                document.getElementById('usageBar').style.width = `${percent}%`;
            }
            
            const features = plan.features || [];
            document.getElementById('planFeatures').innerHTML = features.map(f => `
                <div class="flex items-center gap-2 text-gray-300">
                    <i class="fas fa-check text-green-500"></i>
                    <span>${f}</span>
                </div>
            `).join('');
        }
        
        function getRoleName(role) {
            const roles = { 'user': 'Usuário', 'analyst': 'Analista', 'redteam': 'Red Team', 'admin': 'Administrador' };
            return roles[role] || role;
        }
        
        function setupNavigation() {
            document.querySelectorAll('.sidebar-link[data-page]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    navigateTo(link.dataset.page);
                });
            });
        }
        
        function navigateTo(page) {
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
            document.querySelector(`[data-page="${page}"]`)?.classList.add('active');
            
            document.querySelectorAll('.page-content').forEach(p => p.classList.remove('active'));
            document.getElementById(`page-${page}`)?.classList.add('active');
            
            const titles = {
                'profile': 'Meu Perfil',
                'security': 'Segurança',
                'usage': 'Uso & Limites',
                'admin-dashboard': 'Dashboard Admin',
                'admin-users': 'Gerenciar Usuários',
                'admin-sessions': 'Sessões',
                'admin-plans': 'Planos',
                'admin-logs': 'Logs do Sistema',
                'admin-settings': 'Configurações'
            };
            document.getElementById('pageTitle').textContent = titles[page] || page;
            
            if (page === 'admin-users') loadUsers();
            if (page === 'admin-sessions') loadAllSessions();
            if (page === 'admin-plans') loadPlans();
            if (page === 'admin-logs') loadLogs();
        }
        
        async function loadAdminData() {
            try {
                const [usersRes, sessionsRes] = await Promise.all([
                    api('/admin/users'),
                    api('/admin/sessions')
                ]);
                
                allUsers = usersRes.data || [];
                allSessions = sessionsRes.data || [];
                
                document.getElementById('adminStatUsers').textContent = allUsers.length;
                document.getElementById('adminStatSessions').textContent = allSessions.length;
                
                const totalMsgs = allSessions.reduce((acc, s) => acc + (s.message_count || 0), 0);
                document.getElementById('adminStatMessages').textContent = totalMsgs;
                
                const recent = allUsers.slice(0, 5);
                document.getElementById('recentUsersTable').innerHTML = recent.map(u => `
                    <tr>
                        <td class="text-white">${u.name}</td>
                        <td class="text-gray-400">${u.email}</td>
                        <td><span class="badge badge-${u.role}">${getRoleName(u.role)}</span></td>
                        <td class="text-gray-500 text-sm">${new Date(u.created_at).toLocaleDateString('pt-BR')}</td>
                    </tr>
                `).join('') || '<tr><td colspan="4" class="text-center text-gray-500">Nenhum usuário</td></tr>';
                
            } catch (err) {
                console.error('Erro admin:', err);
            }
        }
        
        async function loadUsers() {
            try {
                const data = await api('/admin/users');
                allUsers = data.data || [];
                renderUsersTable(allUsers);
            } catch (err) {
                console.error('Erro:', err);
            }
        }
        
        function renderUsersTable(users) {
            document.getElementById('usersTable').innerHTML = users.map(u => `
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            ${u.avatar 
                                ? `<img src="${u.avatar}" class="w-10 h-10 rounded-full object-cover cursor-pointer hover:ring-2 hover:ring-purple-500 transition-all" onclick="showAvatarPreview('${u.avatar}', '${u.name}')" title="Clique para ampliar">`
                                : '<div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center"><i class="fas fa-user text-gray-500"></i></div>'
                            }
                            <div>
                                <span class="text-white font-medium block">${u.name}</span>
                                <span class="text-xs text-gray-500">${new Date(u.created_at).toLocaleDateString('pt-BR')}</span>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-400">${u.email}</td>
                    <td><span class="badge badge-${u.role}">${getRoleName(u.role)}</span></td>
                    <td class="text-gray-400">${u.plan?.name || '-'}</td>
                    <td><span class="badge ${u.is_banned ? 'badge-banned' : 'badge-active'}">${u.is_banned ? 'Banido' : 'Ativo'}</span></td>
                    <td>
                        <div class="flex items-center gap-2">
                            <button class="text-gray-400 hover:text-purple-400 transition-colors edit-user-btn" data-userid="${u.id}" title="Editar">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button onclick="toggleBanUser('${u.id}', ${u.is_banned})" class="text-gray-400 hover:text-yellow-400 transition-colors" title="${u.is_banned ? 'Desbanir' : 'Banir'}">
                                <i class="fas fa-${u.is_banned ? 'unlock' : 'ban'}"></i>
                            </button>
                            ${u.role !== 'admin' ? `<button onclick="deleteUser('${u.id}')" class="text-gray-400 hover:text-red-400 transition-colors" title="Excluir">
                                <i class="fas fa-trash"></i>
                            </button>` : ''}
                        </div>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="6" class="text-center text-gray-500">Nenhum usuário</td></tr>';
            
            // Adiciona event listeners para os botões de editar
            document.querySelectorAll('.edit-user-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const userId = this.dataset.userid;
                    await openEditUserModal(userId);
                });
            });
        }
        
        function showAvatarPreview(avatarUrl, userName) {
            document.getElementById('avatarPreviewImg').src = avatarUrl;
            document.getElementById('avatarPreviewName').textContent = userName;
            document.getElementById('avatarPreviewModal').classList.remove('hidden');
            document.getElementById('avatarPreviewModal').classList.add('flex');
        }
        
        function closeAvatarPreview() {
            document.getElementById('avatarPreviewModal').classList.add('hidden');
            document.getElementById('avatarPreviewModal').classList.remove('flex');
        }
        
        document.getElementById('searchUsers')?.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            const filtered = allUsers.filter(u => u.name.toLowerCase().includes(query) || u.email.toLowerCase().includes(query));
            renderUsersTable(filtered);
        });
        
        async function openEditUserModal(userId) {
            const user = allUsers.find(u => u.id === userId);
            if (!user) return;
            
            // Carrega planos se ainda não carregou
            if (allPlans.length === 0) {
                await loadPlansForSelect();
            }
            
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserName').value = user.name;
            document.getElementById('editUserEmail').value = user.email;
            document.getElementById('editUserRole').value = user.role;
            document.getElementById('editUserPlan').value = user.plan?.id || '';
            document.getElementById('editUserPassword').value = '';
            
            document.getElementById('editUserModal').classList.remove('hidden');
            document.getElementById('editUserModal').classList.add('flex');
        }
        
        async function loadPlansForSelect() {
            try {
                const data = await api('/admin/plans');
                allPlans = data.data || [];
                
                const select = document.getElementById('editUserPlan');
                select.innerHTML = allPlans.map(p => `
                    <option value="${p.id}">${p.name}</option>
                `).join('');
            } catch (err) {
                console.error('Erro ao carregar planos:', err);
            }
        }
        
        function closeEditUserModal() {
            document.getElementById('editUserModal').classList.add('hidden');
            document.getElementById('editUserModal').classList.remove('flex');
        }
        
        document.getElementById('editUserForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const userId = document.getElementById('editUserId').value;
            const body = {
                name: document.getElementById('editUserName').value,
                email: document.getElementById('editUserEmail').value,
                role: document.getElementById('editUserRole').value,
                plan_id: document.getElementById('editUserPlan').value
            };
            
            const newPwd = document.getElementById('editUserPassword').value;
            if (newPwd) body.password = newPwd;
            
            try {
                await api(`/admin/users/${userId}`, { method: 'PUT', body: JSON.stringify(body) });
                showNotification('Usuário atualizado!');
                closeEditUserModal();
                loadUsers();
            } catch (err) {
                showNotification('Erro ao atualizar', 'error');
            }
        });
        
        async function toggleBanUser(userId, isBanned) {
            if (!confirm(isBanned ? 'Desbanir este usuário?' : 'Banir este usuário?')) return;
            
            try {
                await api(`/admin/users/${userId}/${isBanned ? 'unban' : 'ban'}`, { method: 'POST' });
                showNotification(isBanned ? 'Usuário desbanido!' : 'Usuário banido!');
                loadUsers();
            } catch (err) {
                showNotification('Erro ao atualizar', 'error');
            }
        }
        
        async function deleteUser(userId) {
            if (!confirm('Tem certeza que deseja excluir este usuário?')) return;
            
            try {
                await api(`/admin/users/${userId}`, { method: 'DELETE' });
                showNotification('Usuário excluído!');
                loadUsers();
            } catch (err) {
                showNotification('Erro ao excluir', 'error');
            }
        }
        
        async function loadAllSessions() {
            try {
                const data = await api('/admin/sessions');
                allSessions = data.data || [];
                renderSessionsTable(allSessions);
            } catch (err) {
                console.error('Erro:', err);
            }
        }
        
        function renderSessionsTable(sessions) {
            document.getElementById('sessionsTable').innerHTML = sessions.map(s => `
                <tr>
                    <td class="text-white">${s.title || 'Sem título'}</td>
                    <td class="text-gray-400">${s.user?.name || '-'}</td>
                    <td class="text-purple-400">${s.profile}</td>
                    <td class="text-gray-400">${s.message_count || 0}</td>
                    <td class="text-gray-500 text-sm">${new Date(s.created_at).toLocaleDateString('pt-BR')}</td>
                    <td>
                        <button onclick="deleteSession('${s.id}')" class="text-gray-400 hover:text-red-400 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="6" class="text-center text-gray-500">Nenhuma sessão</td></tr>';
        }
        
        async function deleteSession(sessionId) {
            if (!confirm('Excluir esta sessão?')) return;
            
            try {
                await api(`/admin/sessions/${sessionId}`, { method: 'DELETE' });
                showNotification('Sessão excluída!');
                loadAllSessions();
            } catch (err) {
                showNotification('Erro ao excluir', 'error');
            }
        }
        
        async function loadPlans() {
            try {
                const data = await api('/admin/plans');
                const plans = data.data || [];
                
                document.getElementById('plansGrid').innerHTML = plans.map(p => `
                    <div class="stat-card">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                <i class="fas fa-gem text-purple-400"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-white">${p.name}</p>
                                <p class="text-sm text-gray-500">${p.users_count || 0} usuários</p>
                            </div>
                        </div>
                        <div class="space-y-1 text-sm text-gray-400">
                            <p>Limite diário: ${p.daily_limit === -1 ? 'Ilimitado' : p.daily_limit}</p>
                            <p>Preço: ${p.price > 0 ? `R$ ${p.price}` : 'Grátis'}</p>
                        </div>
                    </div>
                `).join('');
            } catch (err) {
                console.error('Erro:', err);
            }
        }
        
        function loadLogs() {
            document.getElementById('logsContainer').innerHTML = `
                <p>[${new Date().toLocaleString('pt-BR')}] Sistema iniciado</p>
                <p>[${new Date().toLocaleString('pt-BR')}] API DeepSeek conectada</p>
                <p>[${new Date().toLocaleString('pt-BR')}] ${allUsers.length} usuários registrados</p>
                <p>[${new Date().toLocaleString('pt-BR')}] ${allSessions.length} sessões ativas</p>
                <p class="text-gray-500 mt-4">Logs detalhados disponíveis em storage/logs/</p>
            `;
        }
        
        async function clearAllSessions() {
            if (!confirm('ATENÇÃO: Isso irá excluir TODAS as sessões de TODOS os usuários. Continuar?')) return;
            
            try {
                await api('/admin/sessions/clear', { method: 'DELETE' });
                showNotification('Todas as sessões foram excluídas!');
                loadAllSessions();
                loadAdminData();
            } catch (err) {
                showNotification('Erro ao limpar sessões', 'error');
            }
        }
        
        document.getElementById('profileForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                const res = await fetch(`${API_URL}/profile`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: document.getElementById('inputName').value,
                        email: document.getElementById('inputEmail').value
                    })
                });
                
                if (res.ok) {
                    showNotification('Perfil atualizado!');
                    loadProfile();
                } else {
                    showNotification('Erro ao atualizar', 'error');
                }
            } catch (err) {
                showNotification('Erro ao atualizar', 'error');
            }
        });
        
        document.getElementById('passwordForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const newPwd = document.getElementById('inputNewPassword').value;
            const confirmPwd = document.getElementById('inputConfirmPassword').value;
            
            if (newPwd !== confirmPwd) {
                showNotification('As senhas não coincidem', 'error');
                return;
            }
            
            try {
                const res = await fetch(`${API_URL}/profile`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: document.getElementById('inputCurrentPassword').value,
                        new_password: newPwd,
                        new_password_confirmation: confirmPwd
                    })
                });
                
                if (res.ok) {
                    showNotification('Senha alterada!');
                    document.getElementById('inputCurrentPassword').value = '';
                    document.getElementById('inputNewPassword').value = '';
                    document.getElementById('inputConfirmPassword').value = '';
                } else {
                    const data = await res.json();
                    showNotification(data.message || 'Erro ao alterar senha', 'error');
                }
            } catch (err) {
                showNotification('Erro ao alterar senha', 'error');
            }
        });
        
        async function uploadAvatar(input) {
            if (!input.files?.[0]) return;
            
            const file = input.files[0];
            if (file.size > 2 * 1024 * 1024) {
                showNotification('Imagem deve ter no máximo 2MB', 'error');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('avatarIcon').style.display = 'none';
                document.getElementById('avatarImage').src = e.target.result;
                document.getElementById('avatarImage').style.display = 'block';
            };
            reader.readAsDataURL(file);
            
            const formData = new FormData();
            formData.append('avatar', file);
            
            try {
                const res = await fetch(`${API_URL}/profile/avatar`, {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}` },
                    body: formData
                });
                
                if (res.ok) {
                    showNotification('Avatar atualizado!');
                    loadProfile();
                } else {
                    showNotification('Erro ao enviar avatar', 'error');
                }
            } catch (err) {
                showNotification('Erro ao enviar avatar', 'error');
            }
            
            input.value = '';
        }
        
        async function deleteAvatar() {
            try {
                const res = await fetch(`${API_URL}/profile/avatar`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                if (res.ok) {
                    document.getElementById('avatarImage').style.display = 'none';
                    document.getElementById('avatarIcon').style.display = 'block';
                    showNotification('Avatar removido!');
                }
            } catch (err) {
                showNotification('Erro ao remover avatar', 'error');
            }
        }
        
        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/';
        }
    </script>
</body>
</html>
