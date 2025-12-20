<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Perfil - DeepEyes</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="apple-touch-icon" href="/logo.png">
    <script>
        if (localStorage.getItem('token')) {
            document.documentElement.classList.add('has-token');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        de: {
                            dark: '#0B0F14',
                            darker: '#070A0E',
                            card: '#0F172A',
                            border: 'rgba(255,255,255,0.08)',
                            green: '#00FF88',
                            cyan: '#00D4FF',
                            orange: '#F97316',
                            red: '#EF4444',
                        }
                    },
                    fontFamily: {
                        'grotesk': ['Space Grotesk', 'sans-serif'],
                        'mono': ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/deepeyes.css">
    <style>
        body { 
            font-family: 'Inter', sans-serif;
            background: #0B0F14;
        }
        
        .de-gradient-bg {
            background: 
                radial-gradient(ellipse at top left, rgba(0, 255, 136, 0.03) 0%, transparent 50%),
                radial-gradient(ellipse at bottom right, rgba(0, 212, 255, 0.03) 0%, transparent 50%),
                linear-gradient(180deg, #0B0F14 0%, #070A0E 100%);
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0B0F14; }
        ::-webkit-scrollbar-thumb { background: rgba(0, 255, 136, 0.3); border-radius: 3px; }
        
        .has-token #authModal { display: none !important; }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: #6B7280;
            transition: all 0.3s;
            border: 1px solid transparent;
        }
        
        .sidebar-link:hover {
            background: rgba(0, 255, 136, 0.05);
            color: white;
            border-color: rgba(0, 255, 136, 0.1);
        }
        
        .sidebar-link.active {
            background: rgba(0, 255, 136, 0.1);
            color: #00FF88;
            border: 1px solid rgba(0, 255, 136, 0.3);
        }
        
        .sidebar-link.active i { color: #00FF88; }
        
        .card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            backdrop-blur: 10px;
        }
        
        .input-field {
            width: 100%;
            background: rgba(11, 15, 20, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        .input-field:focus { 
            outline: none; 
            border-color: rgba(0, 255, 136, 0.5);
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #00FF88, #00D4FF);
            color: #0B0F14;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-primary:hover { 
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0, 255, 136, 0.3);
        }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #9CA3AF;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-secondary:hover { 
            background: rgba(255, 255, 255, 0.1); 
            color: white;
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            border: 1px solid rgba(239, 68, 68, 0.3);
            transition: all 0.3s;
        }
        
        .btn-danger:hover { 
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .notification.success { 
            background: rgba(0, 255, 136, 0.1); 
            border: 1px solid rgba(0, 255, 136, 0.3); 
            color: #00FF88; 
        }
        .notification.error { 
            background: rgba(239, 68, 68, 0.1); 
            border: 1px solid rgba(239, 68, 68, 0.3); 
            color: #EF4444; 
        }
        
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
            background: linear-gradient(135deg, #00FF88, #00D4FF);
        }
        
        .avatar-inner {
            width: 100%;
            height: 100%;
            background: #0B0F14;
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
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
            border-radius: 50%;
        }
        
        .avatar-container:hover .avatar-overlay { opacity: 1; }
        
        .stat-card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 20px;
            backdrop-filter: blur(10px);
        }
        
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .data-table td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            font-size: 14px;
        }
        
        .data-table tr:hover td { background: rgba(0, 255, 136, 0.02); }
        
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
        
        /* Mobile Responsive Styles */
        @media (max-width: 1023px) {
            .flex.h-screen > aside {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 40;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .flex.h-screen > aside.mobile-open {
                transform: translateX(0);
            }
            
            #mobileOverlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(4px);
                z-index: 35;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease-in-out;
            }
            
            #mobileOverlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            /* Main content takes full width */
            .flex.h-screen > main {
                width: 100%;
                margin-left: 0;
            }
            
            /* Header adjustments */
            .flex.h-screen > main > header {
                padding-left: 60px !important;
            }
            
            /* Card grids stack on mobile */
            .grid.grid-cols-3 {
                grid-template-columns: 1fr !important;
            }
            
            .grid.grid-cols-4 {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            /* Table scroll */
            .overflow-x-auto {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .data-table {
                min-width: 600px;
            }
            
            /* Modal adjustments */
            .fixed.inset-0 > .max-w-lg,
            .fixed.inset-0 > .max-w-md {
                max-width: 95% !important;
                margin: 16px !important;
            }
        }
        
        @media (max-width: 640px) {
            .grid.grid-cols-4 {
                grid-template-columns: 1fr !important;
            }
            
            /* Stat cards */
            .stat-card {
                padding: 16px !important;
            }
            
            /* Header title */
            .flex.h-screen > main > header h1 {
                font-size: 1.25rem !important;
            }
            
            /* Content padding */
            .flex.h-screen > main > .p-8 {
                padding: 16px !important;
            }
        }
    </style>
</head>
<body class="de-gradient-bg min-h-screen text-gray-100">
    
    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn" class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 rounded-xl bg-[rgba(0,255,136,0.1)] border border-[rgba(0,255,136,0.2)] text-[#00FF88] flex items-center justify-center">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Mobile Overlay -->
    <div id="mobileOverlay" onclick="closeMobileSidebar()"></div>
    
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-[rgba(11,15,20,0.95)] border-r border-[rgba(255,255,255,0.08)] flex flex-col backdrop-blur-xl">
            <!-- Close button for mobile -->
            <button id="closeSidebarBtn" class="lg:hidden absolute top-4 right-4 w-8 h-8 rounded-lg bg-[rgba(255,255,255,0.05)] hover:bg-[rgba(239,68,68,0.1)] text-gray-400 hover:text-red-400 flex items-center justify-center transition-all z-10">
                <i class="fas fa-times"></i>
            </button>
            <!-- Logo -->
            <div class="p-4 border-b border-[rgba(255,255,255,0.08)]">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="relative">
                        <div class="absolute inset-0 blur-lg bg-[#00FF88]/20 rounded-full scale-110 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <img src="/logo.png" alt="DeepEyes" class="h-10 w-10 relative z-10">
                    </div>
                    <div>
                        <span class="text-lg font-bold block bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">DeepEyes</span>
                        <span class="text-xs text-gray-600 font-mono">PAINEL DE CONTROLE</span>
                    </div>
                </a>
            </div>
            
            <!-- User Info -->
            <div class="p-4 border-b border-[rgba(255,255,255,0.08)]">
                <div class="flex items-center gap-3">
                    <div style="width: 40px; height: 40px;" class="rounded-xl overflow-hidden bg-gradient-to-br from-[rgba(0,255,136,0.2)] to-[rgba(0,212,255,0.1)] flex items-center justify-center border border-[rgba(0,255,136,0.2)]">
                        <i id="sidebarAvatarIcon" class="fas fa-user text-sm text-[#00FF88]"></i>
                        <img id="sidebarAvatarImg" src="" alt="" class="w-full h-full object-cover" style="display: none;">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p id="sidebarUserName" class="text-sm font-medium text-white truncate">Carregando...</p>
                        <p id="sidebarUserRole" class="text-xs text-[#00FF88] font-mono uppercase tracking-wider">-</p>
                    </div>
                </div>
            </div>
            
            <!-- Menu -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <p class="text-[10px] font-semibold text-gray-600 uppercase tracking-widest mb-3 px-3">Minha Conta</p>
                
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
                    <p class="text-[10px] font-semibold text-gray-600 uppercase tracking-widest mt-6 mb-3 px-3">Administração</p>
                    
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
            <div class="p-4 border-t border-[rgba(255,255,255,0.08)] space-y-2">
                <a href="/" class="sidebar-link text-sm">
                    <i class="fas fa-arrow-left w-5"></i>
                    <span>Voltar ao Chat</span>
                </a>
                <button onclick="logout()" class="sidebar-link text-sm w-full text-[#EF4444] hover:text-white hover:bg-[rgba(239,68,68,0.1)]">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Sair</span>
                </button>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Beta Warning Banner -->
            <div id="betaBanner" class="bg-gradient-to-r from-amber-500/20 via-orange-500/20 to-amber-500/20 border-b border-amber-500/30 px-4 py-3">
                <div class="flex items-center justify-center gap-3 text-center">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-flask text-amber-400 animate-pulse"></i>
                        <span class="text-amber-300 font-semibold text-sm">VERSÃO BETA</span>
                    </div>
                    <span class="text-amber-200/80 text-sm">
                        Estamos em fase de desenvolvimento. Algumas funcionalidades podem estar instáveis.
                    </span>
                    <button onclick="closeBetaBanner()" class="ml-2 text-amber-400 hover:text-amber-200 transition-colors" title="Fechar aviso">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Header -->
            <header class="sticky top-0 bg-[rgba(11,15,20,0.9)] backdrop-blur-xl border-b border-[rgba(255,255,255,0.08)] px-8 py-4 z-10">
                <div class="flex items-center justify-between">
                    <h1 id="pageTitle" class="text-xl font-bold bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">Meu Perfil</h1>
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
                                        <i id="avatarIcon" class="fas fa-user text-2xl text-[#00FF88]"></i>
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
                    <!-- Header com info do plano -->
                    <div class="card p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500/20 to-indigo-500/20 flex items-center justify-center border border-purple-500/30">
                                    <i class="fas fa-gem text-2xl text-purple-400"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Plano Atual</p>
                                    <h2 id="usagePlanName" class="text-xl font-bold text-white">-</h2>
                                </div>
                            </div>
                            <div id="planBadge" class="px-4 py-2 rounded-xl bg-purple-500/20 border border-purple-500/30">
                                <span id="planStatus" class="text-purple-400 font-semibold">Ativo</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cards de uso -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <!-- Requisições Hoje -->
                        <div class="stat-card">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#00FF88]/20 to-[#00D4FF]/20 flex items-center justify-center border border-[#00FF88]/30">
                                    <i class="fas fa-bolt text-xl text-[#00FF88]"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">Requisições Hoje</p>
                                    <p class="text-2xl font-bold text-white">
                                        <span id="statDailyReqs">0</span>
                                        <span class="text-gray-500 text-lg">/</span>
                                        <span id="statDailyLimit" class="text-gray-400 text-lg">5</span>
                                    </p>
                                </div>
                            </div>
                            <div class="h-2 bg-slate-700/50 rounded-full overflow-hidden">
                                <div id="usageBar" class="h-full bg-gradient-to-r from-[#00FF88] to-[#00D4FF] transition-all duration-500" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                <span id="usageLimitText">Restam 5 requisições</span>
                            </p>
                        </div>
                        
                        <!-- Requisições Restantes -->
                        <div class="stat-card">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center border border-cyan-500/30">
                                    <i class="fas fa-battery-three-quarters text-xl text-cyan-400"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">Restantes</p>
                                    <p class="text-2xl font-bold text-white" id="statRemaining">5</p>
                                </div>
                            </div>
                            <p id="remainingStatus" class="text-xs text-green-400 mt-3 flex items-center gap-1">
                                <i class="fas fa-check-circle"></i>
                                Créditos disponíveis
                            </p>
                        </div>
                        
                        <!-- Sessões Criadas -->
                        <div class="stat-card">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500/20 to-red-500/20 flex items-center justify-center border border-orange-500/30">
                                    <i class="fas fa-comments text-xl text-orange-400"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">Sessões</p>
                                    <p class="text-2xl font-bold text-white" id="statTotalSessions">0</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">Total de sessões criadas</p>
                        </div>
                    </div>
                    
                    <!-- Aviso para usuários do plano gratuito -->
                    <div id="freeUserWarning" class="hidden mb-6">
                        <div class="card p-5 bg-gradient-to-r from-yellow-500/10 to-orange-500/10 border-yellow-500/30">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-yellow-500/20 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-crown text-yellow-400"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-yellow-400 mb-1">Faça upgrade para mais requisições!</h4>
                                    <p class="text-sm text-gray-400">Com o plano Pro, você tem <strong class="text-white">100 requisições/dia</strong> e acesso a modos avançados de ataque.</p>
                                </div>
                                <button class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-lg font-semibold hover:opacity-90 transition-opacity text-sm">
                                    <i class="fas fa-rocket mr-2"></i>Upgrade
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Features do plano -->
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-list-check text-[#00FF88]"></i>
                            Recursos do Plano
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="planFeatures"></div>
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
                            <div class="flex flex-col sm:flex-row gap-3">
                                <!-- Filtro por Usuário -->
                                <div class="relative">
                                    <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                    <select id="filterUserSessions" class="search-input pl-10 pr-8 appearance-none cursor-pointer" style="min-width: 180px;">
                                        <option value="">Todos os usuários</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none"></i>
                                </div>
                                <!-- Busca -->
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                    <input type="text" id="searchSessions" class="search-input" placeholder="Buscar sessão...">
                                </div>
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
    
    <!-- View Session Chat Modal -->
    <div id="viewSessionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/95 p-4">
        <div class="bg-slate-900 rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col border border-slate-700 overflow-hidden">
            <!-- Header -->
            <div class="p-4 border-b border-slate-700 flex items-center justify-between bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00FF88]/20 to-[#00D4FF]/10 flex items-center justify-center border border-[#00FF88]/30">
                        <i class="fas fa-comments text-[#00FF88]"></i>
                    </div>
                    <div>
                        <h3 id="viewSessionTitle" class="text-lg font-semibold text-white">Sessão de Chat</h3>
                        <p id="viewSessionMeta" class="text-xs text-gray-500"></p>
                    </div>
                </div>
                <button onclick="closeViewSessionModal()" class="w-10 h-10 rounded-lg bg-slate-700 hover:bg-slate-600 text-gray-400 hover:text-white flex items-center justify-center transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Session Info -->
            <div id="viewSessionInfo" class="px-4 py-3 bg-slate-800/30 border-b border-slate-700/50 flex flex-wrap gap-4 text-sm">
                <span class="flex items-center gap-2 text-gray-400">
                    <i class="fas fa-user text-purple-400"></i>
                    <span id="viewSessionUser">-</span>
                </span>
                <span class="flex items-center gap-2 text-gray-400">
                    <i class="fas fa-crosshairs text-cyan-400"></i>
                    <span id="viewSessionTarget">-</span>
                </span>
                <span class="flex items-center gap-2 text-gray-400">
                    <i class="fas fa-shield-halved text-orange-400"></i>
                    <span id="viewSessionProfile">-</span>
                </span>
                <span class="flex items-center gap-2 text-gray-400">
                    <i class="fas fa-calendar text-green-400"></i>
                    <span id="viewSessionDate">-</span>
                </span>
            </div>
            
            <!-- Chat Messages -->
            <div id="viewSessionMessages" class="flex-1 overflow-y-auto p-4 space-y-4" style="min-height: 300px;">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Carregando mensagens...</p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-4 border-t border-slate-700 bg-slate-800/30">
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span id="viewSessionCount">0 mensagens</span>
                    <button onclick="closeViewSessionModal()" class="btn-secondary px-4 py-2">
                        <i class="fas fa-times mr-2"></i>Fechar
                    </button>
                </div>
            </div>
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
        
        // Mobile Sidebar Toggle Functions
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        function openMobileSidebar() {
            sidebar.classList.add('mobile-open');
            mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-open');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileSidebar);
        }
        
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', closeMobileSidebar);
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
            document.getElementById('statDailyLimit').textContent = usage.daily_limit === -1 ? '∞' : usage.daily_limit;
            document.getElementById('statRemaining').textContent = usage.remaining === 9999 ? '∞' : usage.remaining;
            document.getElementById('usagePlanName').textContent = plan.name;
            
            // Texto de limite
            if (usage.daily_limit === -1) {
                document.getElementById('usageLimitText').textContent = 'Requisições ilimitadas';
            } else {
                const remaining = usage.remaining;
                if (remaining <= 0) {
                    document.getElementById('usageLimitText').innerHTML = '<span class="text-red-400">Limite atingido! Tente amanhã.</span>';
                    document.getElementById('remainingStatus').innerHTML = '<i class="fas fa-times-circle"></i> Limite atingido';
                    document.getElementById('remainingStatus').className = 'text-xs text-red-400 mt-3 flex items-center gap-1';
                } else if (remaining <= 2) {
                    document.getElementById('usageLimitText').textContent = `Apenas ${remaining} requisição(s) restante(s)!`;
                    document.getElementById('usageLimitText').classList.add('text-yellow-400');
                    document.getElementById('remainingStatus').innerHTML = '<i class="fas fa-exclamation-triangle"></i> Poucos créditos';
                    document.getElementById('remainingStatus').className = 'text-xs text-yellow-400 mt-3 flex items-center gap-1';
                } else {
                    document.getElementById('usageLimitText').textContent = `Restam ${remaining} requisições`;
                }
            }
            
            // Barra de progresso
            if (usage.daily_limit > 0) {
                const percent = Math.min((usage.daily_requests / usage.daily_limit) * 100, 100);
                document.getElementById('usageBar').style.width = `${percent}%`;
                
                // Mudar cor se estiver quase no limite
                if (percent >= 80) {
                    document.getElementById('usageBar').className = 'h-full bg-gradient-to-r from-red-500 to-orange-500 transition-all duration-500';
                } else if (percent >= 50) {
                    document.getElementById('usageBar').className = 'h-full bg-gradient-to-r from-yellow-500 to-orange-500 transition-all duration-500';
                }
            } else if (usage.daily_limit === -1) {
                document.getElementById('usageBar').style.width = '100%';
                document.getElementById('usageBar').className = 'h-full bg-gradient-to-r from-purple-500 to-indigo-500 transition-all duration-500';
            }
            
            // Mostrar aviso para usuários gratuitos
            if (plan.slug === 'free' || plan.price === 'Grátis') {
                document.getElementById('freeUserWarning').classList.remove('hidden');
            }
            
            // Features do plano
            const features = plan.features || [];
            document.getElementById('planFeatures').innerHTML = features.map(f => `
                <div class="flex items-center gap-3 p-3 bg-slate-800/50 rounded-lg border border-slate-700/50">
                    <i class="fas fa-check-circle text-[#00FF88]"></i>
                    <span class="text-gray-300">${f}</span>
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
                    // Close mobile sidebar when navigating
                    if (window.innerWidth < 1024) {
                        closeMobileSidebar();
                    }
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
                // Buscar stats separadamente (cache de 30s) para resposta mais rápida
                const [usersRes, sessionsRes, statsRes] = await Promise.all([
                    api('/admin/users'),
                    api('/admin/sessions'),
                    api('/admin/stats')
                ]);
                
                allUsers = usersRes.data || [];
                allSessions = sessionsRes.data || [];
                
                // Usar dados das estatísticas (mais precisos e com cache)
                document.getElementById('adminStatUsers').textContent = statsRes.users || allUsers.length;
                document.getElementById('adminStatSessions').textContent = statsRes.sessions || allSessions.length;
                document.getElementById('adminStatMessages').textContent = statsRes.messages || 0;
                
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
            const user = allUsers.find(u => String(u.id) === String(userId));
            if (!user) {
                console.error('Usuário não encontrado:', userId);
                showNotification('Usuário não encontrado', 'error');
                return;
            }
            
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
            const roleValue = document.getElementById('editUserRole').value;
            
            const body = {
                name: document.getElementById('editUserName').value,
                email: document.getElementById('editUserEmail').value,
                role: roleValue,
                plan_id: document.getElementById('editUserPlan').value
            };
            
            const newPwd = document.getElementById('editUserPassword').value;
            if (newPwd) body.password = newPwd;
            
            try {
                const result = await api(`/admin/users/${userId}`, { method: 'PUT', body: JSON.stringify(body) });
                showNotification('Usuário atualizado! Role: ' + (result.user?.role || roleValue));
                closeEditUserModal();
                loadUsers();
            } catch (err) {
                showNotification('Erro ao atualizar: ' + err.message, 'error');
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
                populateUserFilter(allSessions);
            } catch (err) {
                console.error('Erro:', err);
            }
        }
        
        // Popula o filtro de usuários
        function populateUserFilter(sessions) {
            const users = new Map();
            sessions.forEach(s => {
                if (s.user && s.user.id) {
                    users.set(s.user.id, s.user.name);
                }
            });
            
            const select = document.getElementById('filterUserSessions');
            if (select) {
                const currentValue = select.value;
                select.innerHTML = '<option value="">Todos os usuários</option>' + 
                    Array.from(users.entries()).map(([id, name]) => 
                        `<option value="${id}">${name}</option>`
                    ).join('');
                select.value = currentValue;
            }
        }
        
        // Filtro por usuário
        document.getElementById('filterUserSessions')?.addEventListener('change', function() {
            filterAndRenderSessions();
        });
        
        // Busca por título
        document.getElementById('searchSessions')?.addEventListener('input', function() {
            filterAndRenderSessions();
        });
        
        function filterAndRenderSessions() {
            const userId = document.getElementById('filterUserSessions')?.value || '';
            const searchTerm = document.getElementById('searchSessions')?.value.toLowerCase() || '';
            
            let filtered = allSessions;
            
            if (userId) {
                filtered = filtered.filter(s => s.user && String(s.user.id) === String(userId));
            }
            
            if (searchTerm) {
                filtered = filtered.filter(s => 
                    (s.title || '').toLowerCase().includes(searchTerm) ||
                    (s.user?.name || '').toLowerCase().includes(searchTerm) ||
                    (s.profile || '').toLowerCase().includes(searchTerm)
                );
            }
            
            renderSessionsTable(filtered);
        }
        
        function renderSessionsTable(sessions) {
            document.getElementById('sessionsTable').innerHTML = sessions.map(s => `
                <tr class="hover:bg-slate-800/30 transition-colors cursor-pointer" onclick="viewSession('${s.id}')">
                    <td class="text-white">${s.title || 'Sem título'}</td>
                    <td class="text-gray-400">${s.user?.name || '-'}</td>
                    <td>
                        <span class="px-2 py-1 rounded-md text-xs font-medium ${getProfileBadgeClass(s.profile)}">${s.profile}</span>
                    </td>
                    <td class="text-gray-400">${s.message_count || 0}</td>
                    <td class="text-gray-500 text-sm">${new Date(s.created_at).toLocaleDateString('pt-BR')}</td>
                    <td class="flex gap-2">
                        <button onclick="event.stopPropagation(); viewSession('${s.id}')" class="text-gray-400 hover:text-cyan-400 transition-colors" title="Ver chat">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="event.stopPropagation(); deleteSession('${s.id}')" class="text-gray-400 hover:text-red-400 transition-colors" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('') || '<tr><td colspan="6" class="text-center text-gray-500">Nenhuma sessão encontrada</td></tr>';
        }
        
        function getProfileBadgeClass(profile) {
            const classes = {
                'pentest': 'bg-green-500/20 text-green-400',
                'redteam': 'bg-orange-500/20 text-orange-400',
                'fullattack': 'bg-red-500/20 text-red-400',
                'offensive': 'bg-purple-500/20 text-purple-400'
            };
            return classes[profile] || 'bg-gray-500/20 text-gray-400';
        }
        
        // Visualizar sessão com chat completo
        async function viewSession(sessionId) {
            const modal = document.getElementById('viewSessionModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Reset content
            document.getElementById('viewSessionMessages').innerHTML = `
                <div class="flex items-center justify-center h-full">
                    <div class="text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                        <p>Carregando mensagens...</p>
                    </div>
                </div>
            `;
            
            try {
                const data = await api(`/admin/sessions/${sessionId}/view`);
                const session = data.data.session;
                const messages = data.data.messages || [];
                
                // Update header
                document.getElementById('viewSessionTitle').textContent = session.title || 'Sem título';
                document.getElementById('viewSessionMeta').textContent = `ID: ${session.id}`;
                
                // Update info
                document.getElementById('viewSessionUser').textContent = session.user ? `${session.user.name} (${session.user.email})` : 'Anônimo';
                document.getElementById('viewSessionTarget').textContent = session.target_domain || 'Não definido';
                document.getElementById('viewSessionProfile').textContent = session.profile || 'Padrão';
                document.getElementById('viewSessionDate').textContent = new Date(session.created_at).toLocaleString('pt-BR');
                document.getElementById('viewSessionCount').textContent = `${messages.length} mensagens`;
                
                // Render messages
                if (messages.length === 0) {
                    document.getElementById('viewSessionMessages').innerHTML = `
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center text-gray-500">
                                <i class="fas fa-comments text-4xl mb-3 opacity-50"></i>
                                <p>Nenhuma mensagem nesta sessão</p>
                            </div>
                        </div>
                    `;
                } else {
                    document.getElementById('viewSessionMessages').innerHTML = messages.map(msg => `
                        <div class="flex ${msg.role === 'user' ? 'justify-end' : 'justify-start'}">
                            <div class="max-w-[80%] ${msg.role === 'user' 
                                ? 'bg-gradient-to-br from-[#00FF88]/20 to-[#00D4FF]/10 border-[#00FF88]/30' 
                                : 'bg-slate-800/80 border-slate-700/50'} 
                                border rounded-2xl p-4 ${msg.role === 'user' ? 'rounded-tr-sm' : 'rounded-tl-sm'}">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas ${msg.role === 'user' ? 'fa-user text-[#00FF88]' : 'fa-robot text-purple-400'} text-sm"></i>
                                    <span class="text-xs font-medium ${msg.role === 'user' ? 'text-[#00FF88]' : 'text-purple-400'}">
                                        ${msg.role === 'user' ? 'Usuário' : 'DeepEyes AI'}
                                    </span>
                                    <span class="text-xs text-gray-500 ml-auto">
                                        ${new Date(msg.created_at).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-200 whitespace-pre-wrap break-words chat-content">${escapeHtml(msg.content)}</div>
                            </div>
                        </div>
                    `).join('');
                    
                    // Scroll to bottom
                    const container = document.getElementById('viewSessionMessages');
                    container.scrollTop = container.scrollHeight;
                }
            } catch (err) {
                console.error('Erro ao carregar sessão:', err);
                document.getElementById('viewSessionMessages').innerHTML = `
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center text-red-400">
                            <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                            <p>Erro ao carregar mensagens</p>
                            <p class="text-sm text-gray-500 mt-1">${err.message}</p>
                        </div>
                    </div>
                `;
            }
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function closeViewSessionModal() {
            const modal = document.getElementById('viewSessionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        // Beta banner
        function closeBetaBanner() {
            const banner = document.getElementById('betaBanner');
            if (banner) {
                banner.style.transition = 'all 0.3s ease';
                banner.style.opacity = '0';
                banner.style.maxHeight = '0';
                banner.style.padding = '0';
                setTimeout(() => banner.remove(), 300);
                sessionStorage.setItem('betaBannerClosed', 'true');
            }
        }
        
        // Check if banner was closed this session
        if (sessionStorage.getItem('betaBannerClosed') === 'true') {
            document.getElementById('betaBanner')?.remove();
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
<?php /**PATH C:\Users\zucks\Desktop\iaforpentester\resources\views/profile.blade.php ENDPATH**/ ?>