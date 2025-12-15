<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DeepEyes - Pentest Assistant</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="apple-touch-icon" href="/logo.png">
    <script>
        // Esconde login imediatamente se tiver token (evita flash)
        if (localStorage.getItem('token')) {
            document.documentElement.classList.add('has-token');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700&display=swap');
        
        body { font-family: 'Inter', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', monospace; }
        
        /* Esconde login se tem token (evita flash) */
        .has-token #authModal { display: none !important; }
        
        .chat-container {
            height: calc(100vh - 200px);
        }
        
        .message-content pre {
            background: #1e293b;
            border-radius: 8px;
            padding: 16px;
            overflow-x: auto;
            margin: 8px 0;
            position: relative;
        }
        
        .message-content code {
            background: #334155;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.875rem;
        }
        
        .message-content pre code {
            background: none;
            padding: 0;
        }
        
        /* Code Block Container */
        .code-block-wrapper {
            position: relative;
            margin: 12px 0;
        }
        
        .code-block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #0f172a;
            border-radius: 8px 8px 0 0;
            padding: 8px 12px;
            border: 1px solid #334155;
            border-bottom: none;
        }
        
        .code-block-lang {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .code-copy-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 12px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .code-copy-btn:hover {
            background: #334155;
            color: #e2e8f0;
        }
        
        .code-copy-btn.copied {
            color: #4ade80;
        }
        
        .code-block-wrapper pre {
            margin: 0;
            border-radius: 0 0 8px 8px;
            border: 1px solid #334155;
            border-top: none;
        }
        
        /* Modern Input Glow Effect */
        #inputContainer {
            transition: all 0.3s ease;
        }
        
        #inputContainer:focus-within {
            border-color: rgba(147, 51, 234, 0.5) !important;
            box-shadow: 0 0 0 1px rgba(147, 51, 234, 0.3),
                        0 4px 20px rgba(147, 51, 234, 0.15),
                        0 0 40px rgba(220, 38, 38, 0.1);
        }
        
        #inputContainer:focus-within #inputGlow {
            opacity: 1;
        }
        
        #messageInput {
            line-height: 1.5;
        }
        
        #messageInput::-webkit-scrollbar {
            width: 4px;
        }
        
        #messageInput::-webkit-scrollbar-track {
            background: transparent;
        }
        
        #messageInput::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 2px;
        }
        
        /* Cursor de streaming */
        .streaming-cursor {
            animation: blink 1s infinite;
            color: #9333ea;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
        
        /* Loading Animado - DeepEyes */
        .ai-thinking {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .ai-brain {
            position: relative;
            width: 32px;
            height: 32px;
        }
        
        .ai-brain-icon {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .ai-brain-ring {
            position: absolute;
            inset: -4px;
            border: 2px solid transparent;
            border-top-color: #9333ea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .ai-brain-ring-2 {
            position: absolute;
            inset: -8px;
            border: 2px solid transparent;
            border-bottom-color: #dc2626;
            border-radius: 50%;
            animation: spin 1.5s linear infinite reverse;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { 
                filter: drop-shadow(0 0 2px #9333ea);
                transform: scale(1);
            }
            50% { 
                filter: drop-shadow(0 0 8px #9333ea) drop-shadow(0 0 15px #dc2626);
                transform: scale(1.1);
            }
        }
        
        .thinking-dots {
            display: flex;
            gap: 4px;
        }
        
        .thinking-dots span {
            width: 8px;
            height: 8px;
            background: linear-gradient(135deg, #dc2626, #9333ea);
            border-radius: 50%;
            animation: thinking-bounce 1.4s ease-in-out infinite;
        }
        
        .thinking-dots span:nth-child(1) { animation-delay: 0s; }
        .thinking-dots span:nth-child(2) { animation-delay: 0.2s; }
        .thinking-dots span:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes thinking-bounce {
            0%, 60%, 100% {
                transform: translateY(0);
                opacity: 0.4;
            }
            30% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }
        
        .thinking-text {
            background: linear-gradient(90deg, #9333ea, #dc2626, #9333ea);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient-text 2s linear infinite;
        }
        
        @keyframes gradient-text {
            to { background-position: 200% center; }
        }
        
        .neural-line {
            height: 2px;
            background: linear-gradient(90deg, transparent, #9333ea, #dc2626, #9333ea, transparent);
            background-size: 200% 100%;
            animation: neural-flow 1.5s linear infinite;
            border-radius: 2px;
        }
        
        @keyframes neural-flow {
            0% { background-position: 100% 0; }
            100% { background-position: -100% 0; }
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        }
        
        .glow-red {
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
        }
        
        .glow-purple {
            box-shadow: 0 0 20px rgba(147, 51, 234, 0.3);
        }
        
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-item:hover .delete-session-btn {
            opacity: 1;
        }
        
        .sidebar-item.active {
            background: rgba(147, 51, 234, 0.2);
            border-left: 3px solid #9333ea;
        }
        
        @keyframes slide-in {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .animate-slide-in {
            animation: slide-in 0.3s ease-out forwards;
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        .feature-card {
            position: relative;
            border-radius: 12px;
            overflow: visible;
        }
        
        .feature-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border-radius: 12px;
        }
        
        .feature-card-inner::before {
            content: '';
            position: absolute;
            width: 150px;
            height: 300%;
            background: linear-gradient(90deg, transparent, var(--card-color), var(--card-color), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .feature-card:hover .feature-card-inner::before {
            opacity: 1;
            animation: var(--card-animation);
        }
        
        .feature-card-content {
            position: relative;
            width: calc(100% - 3px);
            height: calc(100% - 3px);
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.95) 100%);
            border-radius: 11px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            z-index: 1;
            border: 1px solid rgba(255,255,255,0.05);
            transition: border-color 0.3s ease;
        }
        
        .feature-card:hover .feature-card-content {
            border-color: var(--card-color);
        }
        
        .feature-card .card-icon {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            transition: transform 0.3s ease, filter 0.3s ease;
            filter: drop-shadow(0 0 8px currentColor);
        }
        
        .feature-card:hover .card-icon {
            transform: scale(1.2);
            filter: drop-shadow(0 0 15px currentColor) drop-shadow(0 0 30px currentColor);
        }
        
        .feature-card:hover h4 {
            color: var(--card-color);
        }
        
        @keyframes card-rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes card-rotate-reverse {
            0% { transform: rotate(360deg); }
            100% { transform: rotate(0deg); }
        }
        
        .card-red { --card-color: #ef4444; --card-animation: card-rotate 3s linear infinite; }
        .card-purple { --card-color: #a855f7; --card-animation: card-rotate-reverse 5s linear infinite; }
        .card-green { --card-color: #22c55e; --card-animation: card-rotate 4s linear infinite; }
        .card-blue { --card-color: #3b82f6; --card-animation: card-rotate-reverse 3.5s linear infinite; }
        
        .profile-dropdown { position: relative; width: 100%; }
        
        .profile-selected {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.5rem 0.75rem;
            background: #1e293b;
            border: 1px solid #475569;
            border-radius: 0.5rem;
            color: white;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .profile-selected:hover { border-color: #ef4444; }
        .profile-selected .chevron { margin-left: auto; transition: transform 0.2s; }
        .profile-dropdown.open .chevron { transform: rotate(180deg); }
        
        .profile-options {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: #1e293b;
            border: 1px solid #475569;
            border-radius: 0.5rem;
            overflow: hidden;
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s;
        }
        
        .profile-dropdown.open .profile-options {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .profile-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 0.75rem;
            color: #cbd5e1;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.15s;
        }
        
        .profile-option:hover { background: rgba(239, 68, 68, 0.1); color: white; }
        .profile-option.selected { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .profile-option i { font-size: 1rem; width: 1.25rem; text-align: center; }
        
        .btn-wrapper { position: relative; display: inline-block; width: 100%; }
        
        .sparkle-btn {
            cursor: pointer;
            border: solid 4px #1e1b4b;
            border-top: none;
            border-radius: 20px;
            position: relative;
            box-shadow: 0px 4px 10px #00000062, 0px 10px 40px -10px #000000a6, 0px 12px 45px -15px #00000071;
            transition: all 0.3s ease;
            width: 100%;
            background: transparent;
            padding: 0;
        }
        
        .sparkle-btn .inner {
            padding: 12px 24px;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            border-bottom: solid 3px #080a11;
            border-radius: 16px;
            background: linear-gradient(180deg, #121730, #1e1b4b);
            color: #fff;
            text-shadow: 1px 1px #000, 0 0 9px #0a0e20;
        }
        
        .sparkle-btn .svgs {
            position: relative;
            margin-top: 2px;
            z-index: 10;
        }
        
        .sparkle-btn .svgs > * {
            filter: drop-shadow(0 0 6px #4f46e5) drop-shadow(1px 1px 0px #000);
            animation: sparkle-pulse 2s ease-in-out infinite;
        }
        
        .sparkle-btn .svgs .svg-s {
            position: absolute;
            font-size: 0.6rem;
            left: 14px;
            top: -3px;
            animation: sparkle-pulse 2s ease-in-out infinite 0.3s;
        }
        
        .sparkle-btn .svgs .svg-l {
            font-size: 1rem;
        }
        
        .sparkle-btn .btn-text {
            display: inline-block;
        }
        
        .sparkle-btn .btn-text span {
            display: inline-block;
            animation: text-glow 2s ease-in-out infinite;
        }
        
        .sparkle-btn .btn-text span:nth-child(1) { animation-delay: 0s; }
        .sparkle-btn .btn-text span:nth-child(2) { animation-delay: 0.05s; }
        .sparkle-btn .btn-text span:nth-child(3) { animation-delay: 0.1s; }
        .sparkle-btn .btn-text span:nth-child(4) { animation-delay: 0.15s; }
        .sparkle-btn .btn-text span:nth-child(5) { animation-delay: 0.2s; }
        .sparkle-btn .btn-text span:nth-child(6) { animation-delay: 0.25s; }
        .sparkle-btn .btn-text span:nth-child(7) { animation-delay: 0.3s; }
        .sparkle-btn .btn-text span:nth-child(8) { animation-delay: 0.35s; }
        .sparkle-btn .btn-text span:nth-child(9) { animation-delay: 0.4s; }
        .sparkle-btn .btn-text span:nth-child(10) { animation-delay: 0.45s; }
        .sparkle-btn .btn-text span:nth-child(11) { animation-delay: 0.5s; }
        
        @keyframes sparkle-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.2); }
        }
        
        @keyframes text-glow {
            0%, 100% { 
                color: #fff;
                text-shadow: 1px 1px #000, 0 0 5px #4f46e5;
            }
            50% { 
                color: #c7d2fe;
                text-shadow: 1px 1px #000, 0 0 15px #4f46e5, 0 0 25px #4f46e5;
            }
        }
        
        .sparkle-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0px 6px 15px #1e1b4b80, 0px 15px 50px -10px #0a0e2090;
        }
        
        .sparkle-btn:hover .inner {
            background: linear-gradient(180deg, #1e2545, #252050);
        }
        
        .sparkle-btn:active {
            box-shadow: none;
            transform: translateY(0);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen text-gray-100">
    <div id="app" class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-slate-900/80 border-r border-slate-700 flex flex-col">
            <!-- Logo -->
            <div class="p-4 border-b border-slate-700">
                <div class="flex items-center gap-3">
                    <img src="/logo.png" alt="DeepEyes" class="h-10 w-10 object-contain">
                    <div>
                        <h1 class="text-lg font-bold text-white">DeepEyes</h1>
                        <p class="text-xs text-gray-400">Pentest Assistant</p>
                    </div>
                </div>
            </div>
            
            <!-- Profile Selector -->
            <div class="p-4 border-b border-slate-700">
                <label class="text-xs text-gray-400 uppercase tracking-wider mb-2 block">Perfil de IA</label>
                <input type="hidden" id="profileSelector" value="pentest">
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-selected" onclick="toggleProfileDropdown()">
                        <i class="fas fa-shield-halved text-red-400 profile-icon" id="selectedProfileIcon"></i>
                        <span id="selectedProfileText">DeepEyes - Ofensivo</span>
                        <i class="fas fa-chevron-down text-gray-400 chevron"></i>
                    </div>
                    <div class="profile-options">
                        <div class="profile-option selected" data-value="pentest" data-icon="fa-shield-halved" data-color="text-red-400" onclick="selectProfile(this)">
                            <i class="fas fa-shield-halved text-red-400"></i>
                            <span>DeepEyes - Ofensivo</span>
                        </div>
                        <div class="profile-option" data-value="redteam" data-icon="fa-crosshairs" data-color="text-orange-400" onclick="selectProfile(this)">
                            <i class="fas fa-crosshairs text-orange-400"></i>
                            <span>BlackSentinel - Red Team</span>
                        </div>
                        <div class="profile-option" data-value="offensive" data-icon="fa-skull" data-color="text-purple-400" onclick="selectProfile(this)">
                            <i class="fas fa-skull text-purple-400"></i>
                            <span>GhostOps - Full Attack</span>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2" id="profileDescription">Modo ofensivo para pentest autorizado</p>
            </div>
            
            <!-- New Chat Button -->
            <div class="p-4">
                <div class="btn-wrapper">
                    <button id="newChatBtn" class="sparkle-btn">
                        <div class="inner">
                            <i class="fas fa-plus"></i>
                            <span class="btn-text"><span>N</span><span>o</span><span>v</span><span>a</span><span>&nbsp;</span><span>S</span><span>e</span><span>s</span><span>s</span><span>a</span><span>o</span></span>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Sessions List -->
            <div class="flex-1 overflow-y-auto p-2">
                <div class="text-xs text-gray-400 uppercase tracking-wider px-3 py-2">Sessoes</div>
                <div id="sessionsList" class="space-y-1"></div>
            </div>
            
            <!-- User Info -->
            <div class="p-4 border-t border-slate-700">
                <div id="userInfo" class="flex items-center gap-3">
                    <a href="/profile" id="userAvatarLink" class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center border border-slate-600 hover:border-purple-500 transition-colors overflow-hidden">
                        <img id="userAvatarImg" src="" alt="Avatar" class="w-full h-full object-cover" style="display: none;">
                        <i id="userAvatarIcon" class="fas fa-user text-gray-400"></i>
                    </a>
                    <div class="flex-1">
                        <a href="/profile" id="userName" class="text-sm font-medium hover:text-purple-400 transition-colors">Nao logado</a>
                        <div id="userRole" class="text-xs text-gray-400">-</div>
                    </div>
                    <a href="/profile" class="text-gray-400 hover:text-purple-400 transition-colors hidden" id="profileLink" title="Perfil">
                        <i class="fas fa-cog"></i>
                    </a>
                    <button id="logoutBtn" class="text-gray-400 hover:text-purple-400 transition-colors hidden">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </aside>
        
        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
            <div class="relative z-10 w-full max-w-sm mx-4 bg-slate-900 rounded-xl p-6 border border-slate-700 shadow-2xl">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/20 flex items-center justify-center">
                        <i class="fas fa-trash-alt text-2xl text-red-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Excluir Sessao</h3>
                    <p class="text-gray-400 text-sm">Tem certeza que deseja excluir esta sessao? Esta acao nao pode ser desfeita.</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="flex-1 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg py-2.5 font-medium transition-colors">Cancelar</button>
                    <button onclick="confirmDeleteSession()" class="flex-1 bg-red-600 hover:bg-red-500 text-white rounded-lg py-2.5 font-medium transition-colors">
                        <i class="fas fa-trash-alt mr-2"></i>Excluir
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Domain Input Modal -->
        <div id="domainModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeDomainModal()"></div>
            <div class="relative z-10 w-full max-w-sm mx-4 bg-slate-900 rounded-xl p-6 border border-slate-700 shadow-2xl">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-crosshairs text-red-500"></i>
                    Novo Alvo
                </h3>
                <div class="mb-4">
                    <label class="block text-sm text-gray-400 mb-2">Dominio do Alvo</label>
                    <input type="text" id="domainInput" 
                        class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-red-500 transition-colors"
                        placeholder="exemplo.com.br"
                        onkeydown="if(event.key === 'Enter') confirmDomainModal()">
                </div>
                <div class="flex gap-3">
                    <button onclick="closeDomainModal()" class="flex-1 bg-slate-700 hover:bg-slate-600 text-gray-300 rounded-lg py-2.5 font-medium transition-colors">Cancelar</button>
                    <button onclick="confirmDomainModal()" class="flex-1 bg-red-600 hover:bg-red-500 text-white rounded-lg py-2.5 font-medium transition-colors">Criar Sessao</button>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Auth Modal (hidden by default, shown only if no token) -->
            <div id="authModal" class="fixed inset-0 z-50 items-center justify-center bg-slate-950 hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950"></div>
                <div class="relative z-10 w-full max-w-md mx-4 bg-slate-900 rounded-2xl p-8 border border-slate-800 shadow-2xl">
                    <div class="text-center mb-8">
                        <img src="/logo.png" alt="DeepEyes" class="h-20 mx-auto mb-4">
                        <h1 class="text-2xl font-bold text-white">DeepEyes</h1>
                        <p class="text-gray-400 mt-1">Sistema de IA para Pentest</p>
                    </div>

                    <div id="authTabs" class="flex gap-2 mb-6">
                        <button class="auth-tab active flex-1 py-2 px-4 rounded-lg bg-red-600 text-white font-medium transition-all" data-tab="login">Login</button>
                        <button class="auth-tab flex-1 py-2 px-4 rounded-lg bg-slate-800 text-gray-400 hover:bg-slate-700 transition-all" data-tab="register">Registrar</button>
                    </div>

                    <form id="loginForm" class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Email</label>
                            <input type="email" name="email" required autocomplete="email"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-red-500 transition-colors"
                                placeholder="seu@email.com">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Senha</label>
                            <input type="password" name="password" required autocomplete="current-password"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-red-500 transition-colors"
                                placeholder="********">
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white rounded-lg py-3 font-medium transition-colors">Entrar</button>
                    </form>

                    <form id="registerForm" class="space-y-4 hidden">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nome</label>
                            <input type="text" name="name" required autocomplete="name"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-red-500 transition-colors"
                                placeholder="Seu nome">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Email</label>
                            <input type="email" name="email" required autocomplete="email"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-red-500 transition-colors"
                                placeholder="seu@email.com">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Senha</label>
                            <input type="password" name="password" required autocomplete="new-password"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-red-500 transition-colors"
                                placeholder="********">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Confirmar Senha</label>
                            <input type="password" name="password_confirmation" required autocomplete="new-password"
                                class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-red-500 transition-colors"
                                placeholder="********">
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white rounded-lg py-3 font-medium transition-colors">Criar Conta</button>
                    </form>

                    <div id="authError" class="hidden mt-4 p-3 bg-red-900/50 border border-red-700 rounded-lg text-red-300 text-sm"></div>
                </div>
            </div>
            
            <!-- Session Header com Alvo -->
            <div id="sessionHeader" class="hidden px-6 py-3 bg-slate-900/80 border-b border-slate-700 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-500/20 to-purple-600/20 flex items-center justify-center border border-red-500/30">
                        <i class="fas fa-crosshairs text-red-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">🎯 ALVO</p>
                        <p id="sessionTargetDomain" class="text-sm font-bold text-white">-</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <i class="fas fa-robot text-purple-400"></i>
                    <span>DeepEyes</span>
                </div>
            </div>
            
            <!-- Chat Messages -->
            <div id="chatContainer" class="flex-1 overflow-y-auto p-6 chat-container">
                <!-- Welcome para Pagina Inicial (sem sessao) -->
                <div id="homeWelcome" class="flex flex-col items-center justify-center h-full text-center">
                    <img src="/logo.png" alt="DeepEyes" class="h-24 mb-6">
                    <h3 class="text-2xl font-bold mb-2 text-white">Bem-vindo ao DeepEyes</h3>
                    <p class="text-gray-400 max-w-md mb-8">
                        Sua IA de <strong>Seguranca Ofensiva</strong> para Pentest, Red Team e CTFs.
                        Ambiente de laboratorio autorizado.
                    </p>
                    <div class="grid grid-cols-2 gap-5 max-w-lg">
                        <div class="feature-card card-red">
                            <div class="feature-card-inner">
                                <div class="feature-card-content text-left">
                                    <i class="fas fa-syringe text-red-400 card-icon"></i>
                                    <h4 class="font-semibold mb-1 text-white">SQL Injection</h4>
                                    <p class="text-xs text-gray-400">Payloads, bypasses, tecnicas</p>
                                </div>
                            </div>
                        </div>
                        <div class="feature-card card-purple">
                            <div class="feature-card-inner">
                                <div class="feature-card-content text-left">
                                    <i class="fas fa-terminal text-purple-400 card-icon"></i>
                                    <h4 class="font-semibold mb-1 text-white">Reverse Shells</h4>
                                    <p class="text-xs text-gray-400">One-liners, stagers, implants</p>
                                </div>
                            </div>
                        </div>
                        <div class="feature-card card-green">
                            <div class="feature-card-inner">
                                <div class="feature-card-content text-left">
                                    <i class="fas fa-user-secret text-green-400 card-icon"></i>
                                    <h4 class="font-semibold mb-1 text-white">Privilege Escalation</h4>
                                    <p class="text-xs text-gray-400">Linux, Windows, AD attacks</p>
                                </div>
                            </div>
                        </div>
                        <div class="feature-card card-blue">
                            <div class="feature-card-inner">
                                <div class="feature-card-content text-left">
                                    <i class="fas fa-ghost text-blue-400 card-icon"></i>
                                    <h4 class="font-semibold mb-1 text-white">Evasion</h4>
                                    <p class="text-xs text-gray-400">AMSI, EDR, WAF bypass</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-red-900/30 border border-red-800 rounded-lg max-w-lg">
                        <p class="text-xs text-red-300">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>AVISO:</strong> Use apenas em ambientes autorizados. 
                            O operador e responsavel pelo uso etico e legal.
                        </p>
                    </div>
                </div>
                
                <!-- Welcome para Sessao (sem mensagens ainda) -->
                <div id="sessionWelcome" class="flex flex-col items-center justify-center h-full text-center hidden">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/logo.png" alt="DeepEyes" class="h-10">
                        <h3 class="text-xl font-bold text-white">Sessao Iniciada</h3>
                    </div>
                    
                    <!-- Perfil Atual -->
                    <div id="currentProfileCard" class="w-full max-w-md bg-slate-800/60 border border-slate-700 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3 mb-2">
                            <i id="welcomeProfileIcon" class="fas fa-shield-halved text-red-400 text-2xl"></i>
                            <div class="text-left flex-1">
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider">Perfil Ativo</p>
                                <h4 id="welcomeProfileName" class="text-sm font-bold text-white">DeepEyes - Ofensivo</h4>
                            </div>
                        </div>
                        <p id="welcomeProfileDesc" class="text-xs text-gray-400 text-left mb-2">
                            Modo ofensivo para pentest autorizado - comandos e payloads reais
                        </p>
                        <div id="welcomeProfileFeatures" class="grid grid-cols-4 gap-1 text-left mb-3">
                            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                                <i class="fas fa-check text-green-500 text-[8px]"></i>
                                <span>SQLi</span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                                <i class="fas fa-check text-green-500 text-[8px]"></i>
                                <span>XSS</span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                                <i class="fas fa-check text-green-500 text-[8px]"></i>
                                <span>Shells</span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-gray-400">
                                <i class="fas fa-check text-green-500 text-[8px]"></i>
                                <span>PrivEsc</span>
                            </div>
                        </div>
                        <p id="welcomeProfileRestriction" class="text-[10px] text-gray-500 text-left border-t border-slate-700 pt-2">
                            <i class="fas fa-shield-check mr-1 text-blue-400"></i>
                            IA com diretrizes de seguranca - respostas tecnicas para profissionais autorizados
                        </p>
                    </div>
                    
                    <!-- Exemplos de Perguntas -->
                    <div class="w-full max-w-md">
                        <h4 class="text-xs font-semibold text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-yellow-400"></i>
                            Experimente Perguntar
                        </h4>
                        <div class="grid grid-cols-1 gap-1">
                            <button onclick="setExampleQuestion(this)" class="bg-slate-800/40 border border-slate-700/50 rounded-lg px-3 py-2 text-left hover:bg-slate-700/50 transition-colors group">
                                <p class="text-xs text-gray-300 group-hover:text-white">Como fazer SQL injection em login bypass?</p>
                            </button>
                            <button onclick="setExampleQuestion(this)" class="bg-slate-800/40 border border-slate-700/50 rounded-lg px-3 py-2 text-left hover:bg-slate-700/50 transition-colors group">
                                <p class="text-xs text-gray-300 group-hover:text-white">Gere um reverse shell em Python para Linux</p>
                            </button>
                            <button onclick="setExampleQuestion(this)" class="bg-slate-800/40 border border-slate-700/50 rounded-lg px-3 py-2 text-left hover:bg-slate-700/50 transition-colors group">
                                <p class="text-xs text-gray-300 group-hover:text-white">Quais tecnicas de privilege escalation no Windows?</p>
                            </button>
                        </div>
                    </div>
                    
                    <p class="mt-4 text-[10px] text-red-400">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Use apenas em ambientes autorizados
                    </p>
                </div>
                
                <div id="messagesContainer" class="space-y-6 hidden"></div>
                
                <div id="typingIndicator" class="hidden flex items-start gap-4 mt-6">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500/20 to-purple-600/20 flex items-center justify-center flex-shrink-0 border border-red-500/30 relative overflow-hidden">
                        <div class="ai-brain">
                            <div class="ai-brain-ring"></div>
                            <div class="ai-brain-ring-2"></div>
                            <img src="/logo.png" alt="AI" class="w-8 h-8 object-contain ai-brain-icon">
                        </div>
                    </div>
                    <div class="bg-slate-800/80 backdrop-blur-sm rounded-2xl rounded-tl-none px-5 py-4 border border-slate-700/50">
                        <div class="ai-thinking">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-3">
                                    <span class="thinking-text text-sm font-medium">DeepEyes processando</span>
                                    <div class="thinking-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                                <div class="neural-line w-32"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Chat Input - Modern Design -->
            <div id="chatInputArea" class="p-4 hidden">
                <!-- Container com efeito glow -->
                <div class="relative max-w-4xl mx-auto">
                    <!-- Glow effect background -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-red-500/20 via-purple-500/20 to-red-500/20 rounded-2xl blur-lg opacity-0 group-focus-within:opacity-100 transition-opacity duration-500" id="inputGlow"></div>
                    
                    <!-- Main input container -->
                    <div class="relative bg-slate-800/90 backdrop-blur-xl rounded-2xl border border-slate-600/50 shadow-2xl overflow-hidden transition-all duration-300 hover:border-slate-500/50" id="inputContainer">
                        <!-- Preview do anexo -->
                        <div id="attachmentPreview" class="hidden border-b border-slate-700/50">
                            <div class="p-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div id="attachmentIcon" class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-red-500/20 border border-purple-500/30 flex items-center justify-center">
                                        <i class="fas fa-file-code text-purple-400 text-lg"></i>
                                    </div>
                                    <div>
                                        <p id="attachmentName" class="text-white text-sm font-semibold truncate max-w-xs"></p>
                                        <p id="attachmentInfo" class="text-gray-400 text-xs mt-0.5"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="removeAttachment()" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 transition-all flex items-center justify-center">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                            <!-- Preview de imagem -->
                            <div id="imagePreviewContainer" class="hidden px-3 pb-3">
                                <img id="imagePreview" class="max-h-32 rounded-xl object-contain border border-slate-600/50" alt="Preview">
                            </div>
                        </div>
                        
                        <form id="messageForm" class="flex items-end gap-2 p-3">
                            <!-- Input de arquivo oculto -->
                            <input type="file" id="fileInput" class="hidden" accept=".txt,.py,.js,.php,.html,.css,.json,.xml,.sh,.bat,.ps1,.sql,.md,.c,.cpp,.h,.java,.rb,.go,.rs,.ts,.vue,.jsx,.tsx,image/*">
                            
                            <!-- Botao de anexo -->
                            <button 
                                type="button" 
                                id="attachBtn"
                                onclick="document.getElementById('fileInput').click()"
                                class="w-10 h-10 rounded-xl bg-slate-700/50 hover:bg-slate-600/50 border border-slate-600/50 hover:border-purple-500/50 text-gray-400 hover:text-purple-400 transition-all flex-shrink-0 flex items-center justify-center group"
                                title="Anexar arquivo ou imagem"
                            >
                                <i class="fas fa-plus text-sm group-hover:rotate-90 transition-transform duration-300"></i>
                            </button>
                            
                            <!-- Textarea container -->
                            <div class="flex-1 relative">
                                <textarea 
                                    id="messageInput" 
                                    rows="1"
                                    placeholder="O que você quer hackear hoje?"
                                    class="w-full bg-transparent text-white text-sm resize-none focus:outline-none placeholder-gray-500 py-2.5 px-1 max-h-32"
                                    style="scrollbar-width: thin; scrollbar-color: #475569 transparent;"
                                    disabled
                                ></textarea>
                            </div>
                            
                            <!-- Botao de enviar -->
                            <button 
                                type="submit" 
                                id="sendBtn"
                                disabled
                                class="w-10 h-10 rounded-xl bg-gradient-to-r from-red-600 to-purple-600 hover:from-red-500 hover:to-purple-500 disabled:opacity-30 disabled:cursor-not-allowed text-white transition-all flex-shrink-0 flex items-center justify-center shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 hover:scale-105 active:scale-95"
                                title="Enviar mensagem"
                            >
                                <i class="fas fa-arrow-up text-sm"></i>
                            </button>
                        </form>
                        
                        <!-- Barra inferior com info -->
                        <div class="px-4 py-2 border-t border-slate-700/30 flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-shield-halved text-green-500/70"></i>
                                    Ambiente autorizado
                                </span>
                                <span class="flex items-center gap-1.5" id="currentProfileBadge">
                                    <i class="fas fa-crosshairs text-red-500/70"></i>
                                    <span id="currentProfileText">Pentest</span>
                                </span>
                            </div>
                            <span class="text-gray-600">
                                <kbd class="px-1.5 py-0.5 bg-slate-700/50 rounded text-[10px]">Enter</kbd> enviar
                                <span class="mx-1">•</span>
                                <kbd class="px-1.5 py-0.5 bg-slate-700/50 rounded text-[10px]">Shift+Enter</kbd> nova linha
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // State
        let token = localStorage.getItem('token');
        let currentUser = null;
        let currentSession = null;
        let sessions = [];
        let userAvatarUrl = null;
        let currentAttachment = null; // Para armazenar o anexo atual
        
        // DOM Elements
        const authModal = document.getElementById('authModal');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const authError = document.getElementById('authError');
        const authTabs = document.querySelectorAll('.auth-tab');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const messagesContainer = document.getElementById('messagesContainer');
        const homeWelcome = document.getElementById('homeWelcome');
        const sessionWelcome = document.getElementById('sessionWelcome');
        const typingIndicator = document.getElementById('typingIndicator');
        const sessionsList = document.getElementById('sessionsList');
        const newChatBtn = document.getElementById('newChatBtn');
        const profileSelector = document.getElementById('profileSelector');
        const logoutBtn = document.getElementById('logoutBtn');
        
        // Funcao para preencher exemplo de pergunta
        function setExampleQuestion(btn) {
            const text = btn.querySelector('p').textContent;
            messageInput.value = text;
            messageInput.focus();
        }
        
        // Funcoes para gerenciar anexos
        const fileInput = document.getElementById('fileInput');
        const attachmentPreview = document.getElementById('attachmentPreview');
        const attachmentName = document.getElementById('attachmentName');
        const attachmentInfo = document.getElementById('attachmentInfo');
        const attachmentIcon = document.getElementById('attachmentIcon');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const imagePreview = document.getElementById('imagePreview');
        
        fileInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            
            const isImage = file.type.startsWith('image/');
            const maxSize = isImage ? 10 * 1024 * 1024 : 50 * 1024 * 1024; // 10MB para imagens, 50MB para scripts
            
            if (file.size > maxSize) {
                showNotification(`Arquivo muito grande. Maximo: ${isImage ? '10MB' : '50MB'}`, 'error');
                fileInput.value = '';
                return;
            }
            
            try {
                if (isImage) {
                    // Para imagens, converter para base64
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        currentAttachment = {
                            type: 'image',
                            name: file.name,
                            size: file.size,
                            content: event.target.result
                        };
                        showAttachmentPreview(file, true, event.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Para scripts/texto, ler como texto
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        currentAttachment = {
                            type: 'code',
                            name: file.name,
                            size: file.size,
                            content: event.target.result,
                            extension: file.name.split('.').pop().toLowerCase()
                        };
                        showAttachmentPreview(file, false);
                    };
                    reader.readAsText(file);
                }
            } catch (error) {
                showNotification('Erro ao ler arquivo', 'error');
                fileInput.value = '';
            }
        });
        
        function showAttachmentPreview(file, isImage, imageData = null) {
            attachmentName.textContent = file.name;
            attachmentInfo.textContent = formatFileSize(file.size) + (currentAttachment.type === 'code' ? ` � ${countLines(currentAttachment.content)} linhas` : '');
            
            if (isImage) {
                attachmentIcon.innerHTML = '<i class="fas fa-image text-green-400"></i>';
                imagePreview.src = imageData;
                imagePreviewContainer.classList.remove('hidden');
            } else {
                const iconMap = {
                    'py': 'fa-python text-yellow-400',
                    'js': 'fa-js text-yellow-300',
                    'php': 'fa-php text-purple-400',
                    'html': 'fa-html5 text-orange-400',
                    'css': 'fa-css3 text-blue-400',
                    'json': 'fa-code text-green-400',
                    'sql': 'fa-database text-blue-300',
                    'sh': 'fa-terminal text-green-300',
                    'bat': 'fa-terminal text-gray-400',
                    'ps1': 'fa-terminal text-blue-400',
                    'md': 'fa-markdown text-white',
                    'txt': 'fa-file-alt text-gray-300'
                };
                const ext = file.name.split('.').pop().toLowerCase();
                const iconClass = iconMap[ext] || 'fa-file-code text-purple-400';
                attachmentIcon.innerHTML = `<i class="fab ${iconClass}"></i>`;
                imagePreviewContainer.classList.add('hidden');
            }
            
            attachmentPreview.classList.remove('hidden');
        }
        
        function removeAttachment() {
            currentAttachment = null;
            fileInput.value = '';
            attachmentPreview.classList.add('hidden');
            imagePreviewContainer.classList.add('hidden');
        }
        
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }
        
        function countLines(text) {
            return text.split('\n').length;
        }
        
        // Notification System
        function showNotification(message, type = 'info') {
            const colors = { 'success': 'bg-green-500', 'error': 'bg-red-500', 'warning': 'bg-yellow-500', 'info': 'bg-blue-500' };
            const icons = { 'success': 'fa-check-circle', 'error': 'fa-exclamation-circle', 'warning': 'fa-exclamation-triangle', 'info': 'fa-info-circle' };
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-[100] ${colors[type]} text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 animate-slide-in`;
            notification.innerHTML = `<i class="fas ${icons[type]}"></i><span>${message}</span>`;
            document.body.appendChild(notification);
            setTimeout(() => { notification.classList.add('opacity-0'); setTimeout(() => notification.remove(), 300); }, 3000);
        }
        
        // API Helper
        async function api(endpoint, options = {}) {
            const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json', ...options.headers };
            if (token) headers['Authorization'] = `Bearer ${token}`;
            const response = await fetch(`/api${endpoint}`, { ...options, headers });
            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Erro na requisicao');
            return data;
        }
        
        // Auth Tabs
        authTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabName = tab.dataset.tab;
                authTabs.forEach(t => {
                    t.classList.remove('active', 'bg-red-600', 'text-white');
                    t.classList.add('bg-slate-800', 'text-gray-400');
                });
                tab.classList.add('active', 'bg-red-600', 'text-white');
                tab.classList.remove('bg-slate-800', 'text-gray-400');
                
                if (tabName === 'login') {
                    loginForm.classList.remove('hidden');
                    registerForm.classList.add('hidden');
                } else {
                    loginForm.classList.add('hidden');
                    registerForm.classList.remove('hidden');
                }
                authError.classList.add('hidden');
            });
        });
        
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            const submitBtn = loginForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Entrando...';
            submitBtn.disabled = true;
            
            try {
                const data = await api('/auth/login', {
                    method: 'POST',
                    body: JSON.stringify({ email: formData.get('email'), password: formData.get('password') })
                });
                
                token = data.data.token;
                localStorage.setItem('token', token);
                currentUser = data.data.user;
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Sucesso!';
                setTimeout(() => onAuthSuccess(), 500);
            } catch (error) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                showAuthError(error.message);
            }
        });
        
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            
            try {
                const data = await api('/auth/register', {
                    method: 'POST',
                    body: JSON.stringify({
                        name: formData.get('name'),
                        email: formData.get('email'),
                        password: formData.get('password'),
                        password_confirmation: formData.get('password_confirmation')
                    })
                });
                
                token = data.data.token;
                localStorage.setItem('token', token);
                currentUser = data.data.user;
                onAuthSuccess();
            } catch (error) {
                showAuthError(error.message);
            }
        });
        
        function showAuthError(message) {
            authError.textContent = message;
            authError.classList.remove('hidden');
        }
        
        async function updateUserAvatar() {
            try {
                const data = await api('/profile');
                const avatarImg = document.getElementById('userAvatarImg');
                const avatarIcon = document.getElementById('userAvatarIcon');
                
                if (data.user && data.user.avatar) {
                    userAvatarUrl = data.user.avatar;
                    avatarImg.src = data.user.avatar;
                    avatarImg.style.display = 'block';
                    avatarIcon.style.display = 'none';
                } else {
                    userAvatarUrl = null;
                    avatarImg.style.display = 'none';
                    avatarIcon.style.display = 'block';
                }
            } catch (error) {
                console.error('Erro ao carregar avatar:', error);
            }
        }
        
        const profileDescriptions = {
            'pentest': 'Modo ofensivo para pentest autorizado - comandos e payloads reais',
            'redteam': 'Simula��o de Atacante e adversarios - t�cnicas avan�adas e sofisticadas de Inva��o',
            'offensive': 'Modo irrestrito - exploits, malware, full attack'
        };
        
        const profileInfo = {
            'pentest': {
                name: 'DeepEyes - Ofensivo',
                icon: 'fa-shield-halved',
                color: 'from-red-600 to-red-800',
                iconColor: 'text-red-400',
                badge: { text: 'RESTRITO', class: 'bg-yellow-600/20 text-yellow-400 border-yellow-600/30', icon: 'fa-lock' },
                features: ['SQL Injection', 'XSS Payloads', 'Reverse Shells', 'Privilege Escalation']
            },
            'redteam': {
                name: 'BlackSentinel - Red Team',
                icon: 'fa-crosshairs',
                color: 'from-orange-600 to-orange-800',
                iconColor: 'text-orange-400',
                badge: { text: 'RESTRITO', class: 'bg-yellow-600/20 text-yellow-400 border-yellow-600/30', icon: 'fa-lock' },
                features: ['Simulacao APT', 'Evasao de EDR', 'C2 Frameworks', 'Lateral Movement']
            },
            'offensive': {
                name: 'DarkMind - Irrestrito',
                icon: 'fa-skull',
                color: 'from-purple-600 to-purple-800',
                iconColor: 'text-purple-400',
                badge: { text: 'LIVRE', class: 'bg-green-600/20 text-green-400 border-green-600/30', icon: 'fa-unlock' },
                features: ['Zero Restricoes', 'Exploits Reais', 'Malware Dev', 'Qualquer Pergunta']
            }
        };
        
        function updateWelcomeProfile(profileKey) {
            const info = profileInfo[profileKey];
            if (!info) return;
            
            const iconEl = document.getElementById('welcomeProfileIcon');
            const nameEl = document.getElementById('welcomeProfileName');
            const descEl = document.getElementById('welcomeProfileDesc');
            const featuresEl = document.getElementById('welcomeProfileFeatures');
            const restrictionEl = document.getElementById('welcomeProfileRestriction');
            
            if (iconEl) iconEl.className = `fas ${info.icon} ${info.iconColor} text-2xl`;
            if (nameEl) nameEl.textContent = info.name;
            if (descEl) descEl.textContent = profileDescriptions[profileKey];
            if (featuresEl) {
                featuresEl.innerHTML = info.features.map(f => `
                    <div class="flex items-center gap-1 text-[10px] text-gray-400">
                        <i class="fas fa-check text-green-500 text-[8px]"></i>
                        <span>${f}</span>
                    </div>
                `).join('');
            }
            if (restrictionEl) {
                if (profileKey === 'offensive') {
                    restrictionEl.innerHTML = `<i class="fas fa-unlock mr-1 text-green-400"></i>Modo sem restricoes - todas as respostas tecnicas disponiveis`;
                } else {
                    restrictionEl.innerHTML = `<i class="fas fa-shield-check mr-1 text-blue-400"></i>IA com diretrizes de seguranca - respostas tecnicas para profissionais autorizados`;
                }
            }
        }
        
        function toggleProfileDropdown() {
            document.getElementById('profileDropdown').classList.toggle('open');
        }
        
        function selectProfile(element) {
            const value = element.dataset.value;
            const icon = element.dataset.icon;
            const color = element.dataset.color;
            const text = element.querySelector('span').textContent;
            
            document.getElementById('profileSelector').value = value;
            document.getElementById('selectedProfileIcon').className = `fas ${icon} ${color} profile-icon`;
            document.getElementById('selectedProfileText').textContent = text;
            
            document.querySelectorAll('.profile-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            
            document.getElementById('profileDescription').textContent = profileDescriptions[value];
            document.getElementById('profileDropdown').classList.remove('open');
            
            updateWelcomeProfile(value);
        }
        
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && !dropdown.contains(e.target)) dropdown.classList.remove('open');
        });
        
        async function onAuthSuccess() {
            authModal.classList.add('hidden');
            authModal.classList.remove('flex');
            document.getElementById('userName').textContent = currentUser.name;
            document.getElementById('userRole').textContent = currentUser.role.toUpperCase();
            logoutBtn.classList.remove('hidden');
            document.getElementById('profileLink').classList.remove('hidden');
            messageInput.disabled = false;
            sendBtn.disabled = false;
            
            updateUserAvatar();
            
            const allowedProfiles = {
                'user': ['pentest'],
                'analyst': ['pentest', 'redteam'],
                'redteam': ['pentest', 'redteam', 'offensive'],
                'admin': ['pentest', 'redteam', 'offensive']
            };
            
            const userAllowed = allowedProfiles[currentUser.role] || ['pentest'];
            
            document.querySelectorAll('.profile-option').forEach(option => {
                if (!userAllowed.includes(option.dataset.value)) {
                    option.style.opacity = '0.5';
                    option.style.pointerEvents = 'none';
                    option.innerHTML += ' <i class="fas fa-lock text-gray-500 ml-auto"></i>';
                }
            });
            
            await loadSessions();
        }
        
        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem('token');
            token = null;
            currentUser = null;
            currentSession = null;
            location.reload();
        });
        
        async function loadSessions() {
            try {
                const data = await api('/chat/sessions');
                sessions = data.data;
                renderSessions();
            } catch (error) {
                console.error('Failed to load sessions:', error);
            }
        }
        
        const profileIcons = {
            'pentest': { icon: 'fa-shield-halved', color: 'text-red-400', bg: 'from-red-500/20 to-orange-500/20' },
            'redteam': { icon: 'fa-crosshairs', color: 'text-orange-400', bg: 'from-orange-500/20 to-yellow-500/20' },
            'offensive': { icon: 'fa-skull', color: 'text-purple-400', bg: 'from-purple-500/20 to-pink-500/20' }
        };
        
        function renderSessions() {
            sessionsList.innerHTML = sessions.map(session => {
                let domainDisplay = session.target_domain;
                if (!domainDisplay && session.title) {
                    const match = session.title.match(/^([^\s-]+)/);
                    domainDisplay = match ? match[1] : session.title;
                }
                domainDisplay = domainDisplay || 'Sem dominio';
                
                const profile = profileIcons[session.profile] || profileIcons['pentest'];
                
                return `
                <div class="sidebar-item rounded-lg px-3 py-2 cursor-pointer transition-all ${currentSession?.id === session.id ? 'active' : ''}" data-id="${session.id}">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br ${profile.bg} flex items-center justify-center flex-shrink-0">
                            <i class="fas ${profile.icon} ${profile.color} text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-sm truncate block font-medium text-gray-200">${domainDisplay}</span>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="fas fa-comment-dots"></i>
                                <span>${session.message_count} mensagens</span>
                            </div>
                        </div>
                        <button class="delete-session-btn opacity-0 p-1.5 rounded-lg hover:bg-red-500/20 text-gray-500 hover:text-red-400 transition-all" data-session-id="${session.id}" title="Excluir sessao" onclick="event.stopPropagation(); deleteSession('${session.id}')">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </div>
            `;
            }).join('');
            
            sessionsList.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', (e) => {
                    if (e.target.closest('.delete-session-btn')) return;
                    loadSession(item.dataset.id);
                });
            });
        }
        
        let sessionToDelete = null;
        
        function deleteSession(sessionId) {
            sessionToDelete = sessionId;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            sessionToDelete = null;
        }
        
        async function confirmDeleteSession() {
            if (!sessionToDelete) return;
            
            try {
                const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
                if (token) headers['Authorization'] = `Bearer ${token}`;
                
                const response = await fetch(`/api/chat/sessions/${sessionToDelete}`, { method: 'DELETE', headers });
                
                if (!response.ok) {
                    const data = await response.json();
                    throw new Error(data.message || 'Erro ao excluir');
                }
                
                const deletedId = sessionToDelete;
                sessions = sessions.filter(s => s.id !== deletedId);
                renderSessions();
                
                if (currentSession?.id === deletedId) {
                    currentSession = null;
                    messagesContainer.innerHTML = '';
                    hideChat();
                }
                
                closeDeleteModal();
                showNotification('Sessao excluida com sucesso!', 'success');
            } catch (error) {
                console.error('Erro ao excluir sessao:', error);
                closeDeleteModal();
                showNotification('Erro ao excluir sessao. Tente novamente.', 'error');
            }
        }
        
        function openDomainModal() {
            const modal = document.getElementById('domainModal');
            const input = document.getElementById('domainInput');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            input.value = '';
            setTimeout(() => input.focus(), 100);
        }
        
        function closeDomainModal() {
            const modal = document.getElementById('domainModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        async function confirmDomainModal() {
            const input = document.getElementById('domainInput');
            const targetDomain = input.value.trim();
            
            if (!targetDomain) {
                input.classList.add('border-red-500');
                input.focus();
                return;
            }
            
            closeDomainModal();
            
            const title = `${targetDomain} - ${new Date().toLocaleDateString('pt-BR')}`;
            
            try {
                const data = await api('/chat/sessions', {
                    method: 'POST',
                    body: JSON.stringify({ title, target_domain: targetDomain, profile: profileSelector.value })
                });
                
                currentSession = data.data;
                sessions.unshift(data.data);
                renderSessions();
                showChat();
            } catch (error) {
                console.error('Failed to create session:', error);
            }
        }
        
        newChatBtn.addEventListener('click', () => openDomainModal());
        
        async function loadSession(sessionId) {
            try {
                const data = await api(`/chat/sessions/${sessionId}`);
                currentSession = data.data.session;
                renderSessions();
                updateSessionHeader();
                
                if (data.data.messages && data.data.messages.length > 0) {
                    showChat();
                    renderMessages(data.data.messages);
                } else {
                    showSessionWelcome();
                }
            } catch (error) {
                console.error('Failed to load session:', error);
            }
        }
        
        function updateSessionHeader() {
            const sessionHeader = document.getElementById('sessionHeader');
            const sessionTargetDomain = document.getElementById('sessionTargetDomain');
            const currentProfileText = document.getElementById('currentProfileText');
            const currentProfileBadge = document.getElementById('currentProfileBadge');
            
            if (currentSession && currentSession.target_domain) {
                sessionTargetDomain.textContent = currentSession.target_domain;
                sessionHeader.classList.remove('hidden');
                sessionHeader.classList.add('flex');
            } else {
                sessionHeader.classList.add('hidden');
                sessionHeader.classList.remove('flex');
            }
            
            // Atualiza o perfil na barra inferior
            if (currentSession && currentSession.profile) {
                const profileInfo = getProfileInfo(currentSession.profile);
                currentProfileText.textContent = profileInfo.name;
                currentProfileBadge.querySelector('i').className = `fas ${profileInfo.icon} ${profileInfo.color}`;
            }
        }
        
        function getProfileInfo(profile) {
            const profiles = {
                'pentest': { name: 'Pentest', icon: 'fa-crosshairs', color: 'text-red-500/70' },
                'recon': { name: 'Recon & OSINT', icon: 'fa-binoculars', color: 'text-blue-500/70' },
                'web': { name: 'Web Hacking', icon: 'fa-globe', color: 'text-purple-500/70' },
                'network': { name: 'Network', icon: 'fa-network-wired', color: 'text-green-500/70' },
                'mobile': { name: 'Mobile', icon: 'fa-mobile-alt', color: 'text-yellow-500/70' },
                'malware': { name: 'Malware', icon: 'fa-bug', color: 'text-orange-500/70' },
                'social': { name: 'Social Eng.', icon: 'fa-users', color: 'text-pink-500/70' },
                'cloud': { name: 'Cloud', icon: 'fa-cloud', color: 'text-cyan-500/70' },
                'forensics': { name: 'Forensics', icon: 'fa-microscope', color: 'text-indigo-500/70' },
                'exploit': { name: 'Exploit Dev', icon: 'fa-bomb', color: 'text-rose-500/70' }
            };
            return profiles[profile] || { name: profile, icon: 'fa-terminal', color: 'text-gray-500/70' };
        }
        
        function showChat() {
            homeWelcome.classList.add('hidden');
            sessionWelcome.classList.add('hidden');
            messagesContainer.classList.remove('hidden');
            document.getElementById('chatInputArea').classList.remove('hidden');
            updateSessionHeader();
        }
        
        function showSessionWelcome() {
            homeWelcome.classList.add('hidden');
            sessionWelcome.classList.remove('hidden');
            messagesContainer.classList.add('hidden');
            document.getElementById('chatInputArea').classList.remove('hidden');
            updateWelcomeProfile(profileSelector.value);
            updateSessionHeader();
        }
        
        function hideChat() {
            homeWelcome.classList.remove('hidden');
            sessionWelcome.classList.add('hidden');
            messagesContainer.classList.add('hidden');
            document.getElementById('chatInputArea').classList.add('hidden');
            document.getElementById('sessionHeader').classList.add('hidden');
            document.getElementById('sessionHeader').classList.remove('flex');
        }
        
        function renderMessages(messages) {
            messagesContainer.innerHTML = messages.map(msg => createMessageHTML(msg)).join('');
            processCodeBlocks();
            scrollToBottom();
        }
        
        // Processa blocos de código e adiciona botão de copiar
        function processCodeBlocks() {
            document.querySelectorAll('.message-content pre').forEach(pre => {
                // Evita processar duas vezes
                if (pre.parentElement.classList.contains('code-block-wrapper')) return;
                
                const code = pre.querySelector('code');
                const codeText = code ? code.textContent : pre.textContent;
                
                // Detecta a linguagem da classe (ex: language-python)
                let lang = 'código';
                if (code && code.className) {
                    const match = code.className.match(/language-(\w+)/);
                    if (match) lang = match[1];
                }
                
                // Cria wrapper
                const wrapper = document.createElement('div');
                wrapper.className = 'code-block-wrapper';
                
                // Header com linguagem e botão copiar
                const header = document.createElement('div');
                header.className = 'code-block-header';
                header.innerHTML = `
                    <span class="code-block-lang">${lang}</span>
                    <button class="code-copy-btn" onclick="copyCodeBlock(this)">
                        <i class="fas fa-copy"></i>
                        <span>Copiar</span>
                    </button>
                `;
                
                // Armazena o código no botão para copiar
                header.querySelector('.code-copy-btn').dataset.code = codeText;
                
                // Insere wrapper antes do pre
                pre.parentNode.insertBefore(wrapper, pre);
                wrapper.appendChild(header);
                wrapper.appendChild(pre);
            });
        }
        
        // Função para copiar código
        function copyCodeBlock(btn) {
            const code = btn.dataset.code;
            navigator.clipboard.writeText(code).then(() => {
                btn.classList.add('copied');
                btn.innerHTML = '<i class="fas fa-check"></i><span>Copiado!</span>';
                
                setTimeout(() => {
                    btn.classList.remove('copied');
                    btn.innerHTML = '<i class="fas fa-copy"></i><span>Copiar</span>';
                }, 2000);
            }).catch(err => {
                console.error('Erro ao copiar:', err);
            });
        }
        
        function createMessageHTML(message) {
            const isUser = message.role === 'user';
            const content = message.content ? marked.parse(message.content) : '';
            
            if (isUser) {
                const avatarHtml = userAvatarUrl 
                    ? `<img src="${userAvatarUrl}" alt="User" class="w-full h-full object-cover rounded-lg">`
                    : `<i class="fas fa-user text-gray-400"></i>`;
                
                // Renderiza card de anexo se houver
                let attachmentHtml = '';
                if (message.attachment) {
                    if (message.attachment.type === 'code') {
                        const iconMap = {
                            'py': { icon: 'fab fa-python', color: 'text-yellow-400' },
                            'js': { icon: 'fab fa-js', color: 'text-yellow-300' },
                            'php': { icon: 'fab fa-php', color: 'text-purple-400' },
                            'html': { icon: 'fab fa-html5', color: 'text-orange-400' },
                            'css': { icon: 'fab fa-css3', color: 'text-blue-400' },
                            'json': { icon: 'fas fa-code', color: 'text-green-400' },
                            'sql': { icon: 'fas fa-database', color: 'text-blue-300' },
                            'sh': { icon: 'fas fa-terminal', color: 'text-green-300' },
                            'bat': { icon: 'fas fa-terminal', color: 'text-gray-400' },
                            'ps1': { icon: 'fas fa-terminal', color: 'text-blue-400' },
                            'md': { icon: 'fab fa-markdown', color: 'text-white' },
                            'txt': { icon: 'fas fa-file-alt', color: 'text-gray-300' }
                        };
                        const ext = message.attachment.extension;
                        const iconInfo = iconMap[ext] || { icon: 'fas fa-file-code', color: 'text-purple-400' };
                        
                        attachmentHtml = `
                            <div class="mt-2 bg-slate-800/50 rounded-lg p-3 border border-slate-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-slate-700 flex items-center justify-center">
                                        <i class="${iconInfo.icon} ${iconInfo.color} text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white font-medium text-sm truncate">${message.attachment.name}</p>
                                        <p class="text-gray-400 text-xs">${message.attachment.lines} linhas • ${message.attachment.size}</p>
                                    </div>
                                    <i class="fas fa-file-code text-gray-500"></i>
                                </div>
                            </div>
                        `;
                    } else if (message.attachment.type === 'image') {
                        attachmentHtml = `
                            <div class="mt-2 bg-slate-800/50 rounded-lg p-2 border border-slate-600">
                                <img src="${message.attachment.preview}" alt="${message.attachment.name}" class="max-h-48 rounded-lg object-contain mx-auto">
                                <p class="text-gray-400 text-xs text-center mt-1">${message.attachment.name} • ${message.attachment.size}</p>
                            </div>
                        `;
                    }
                }
                
                return `
                    <div class="flex items-start gap-4 justify-end">
                        <div class="bg-gradient-to-r from-red-600 to-purple-600 rounded-2xl rounded-tr-none px-6 py-4 max-w-3xl">
                            ${content ? `<div class="message-content text-white">${content}</div>` : ''}
                            ${attachmentHtml}
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-slate-700 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            ${avatarHtml}
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500/20 to-purple-600/20 flex items-center justify-center flex-shrink-0 p-1 border border-red-500/30">
                            <img src="/logo.png" alt="DeepEyes" class="w-8 h-8 object-contain">
                        </div>
                        <div class="bg-slate-800 rounded-2xl rounded-tl-none px-6 py-4 max-w-3xl border border-slate-700">
                            <div class="message-content prose prose-invert max-w-none">${content}</div>
                        </div>
                    </div>
                `;
            }
        }
        
        function addMessage(message) {
            messagesContainer.insertAdjacentHTML('beforeend', createMessageHTML(message));
            scrollToBottom();
        }
        
        function scrollToBottom() {
            const container = document.getElementById('chatContainer');
            container.scrollTop = container.scrollHeight;
        }
        
        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const hasText = messageInput.value.trim();
            const hasAttachment = currentAttachment !== null;
            
            if (!currentSession || (!hasText && !hasAttachment)) return;
            
            // Monta a mensagem com o anexo se houver
            let content = messageInput.value.trim();
            let displayContent = content;
            let attachmentData = null;
            
            if (hasAttachment) {
                if (currentAttachment.type === 'code') {
                    // Para codigo/scripts, inclui o conteudo completo para a IA
                    const codeBlock = '```' + (currentAttachment.extension || '') + '\n' + currentAttachment.content + '\n```';
                    const prefix = content ? content + '\n\n' : '';
                    content = prefix + `**Arquivo: ${currentAttachment.name}** (${countLines(currentAttachment.content)} linhas)\n\n${codeBlock}`;
                    // Display mostra apenas card do arquivo
                    displayContent = content ? content : '';
                    attachmentData = {
                        type: 'code',
                        name: currentAttachment.name,
                        extension: currentAttachment.extension || 'txt',
                        lines: countLines(currentAttachment.content),
                        size: formatFileSize(currentAttachment.size)
                    };
                } else if (currentAttachment.type === 'image') {
                    // Para imagens, envia como base64
                    const prefix = content ? content + '\n\n' : '';
                    content = prefix + `[IMAGEM ANEXADA: ${currentAttachment.name}]\n${currentAttachment.content}`;
                    displayContent = content ? content : '';
                    attachmentData = {
                        type: 'image',
                        name: currentAttachment.name,
                        size: formatFileSize(currentAttachment.size),
                        preview: currentAttachment.content
                    };
                }
            }
            
            messageInput.value = '';
            removeAttachment(); // Limpa o anexo apos enviar
            
            // Esconde o welcome da sessao e mostra o chat
            sessionWelcome.classList.add('hidden');
            messagesContainer.classList.remove('hidden');
            
            addMessage({ role: 'user', content: displayContent, attachment: attachmentData });
            
            // Mostra loading animado
            typingIndicator.classList.remove('hidden');
            scrollToBottom();
            sendBtn.disabled = true;
            
            // Tenta streaming primeiro, fallback para normal
            let streamingSuccess = false;
            let streamingDiv = null;
            
            try {
                // Tenta usar streaming para resposta mais rapida
                const response = await fetch(`/api/chat/sessions/${currentSession.id}/messages/stream`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/event-stream',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ message: content })
                });
                
                // Verifica se atingiu limite diário (429)
                if (response.status === 429) {
                    typingIndicator.classList.add('hidden');
                    showLimitReachedModal();
                    sendBtn.disabled = false;
                    return;
                }
                
                if (!response.ok) {
                    throw new Error('Streaming failed');
                }
                
                // Esconde o loading e cria div de streaming
                typingIndicator.classList.add('hidden');
                streamingDiv = document.createElement('div');
                streamingDiv.id = 'streamingResponse';
                streamingDiv.innerHTML = createMessageHTML({ role: 'assistant', content: '<span class="streaming-cursor">▊</span>' });
                messagesContainer.appendChild(streamingDiv);
                scrollToBottom();
                
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let fullContent = '';
                
                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;
                    
                    const chunk = decoder.decode(value);
                    const lines = chunk.split('\n');
                    
                    for (const line of lines) {
                        if (line.startsWith('data: ')) {
                            try {
                                const data = JSON.parse(line.slice(6));
                                if (data.content) {
                                    fullContent += data.content;
                                    // Atualiza o conteudo em tempo real
                                    const contentDiv = streamingDiv.querySelector('.message-content');
                                    if (contentDiv) {
                                        contentDiv.innerHTML = marked.parse(fullContent) + '<span class="streaming-cursor">▊</span>';
                                    }
                                    scrollToBottom();
                                } else if (data.done) {
                                    // Streaming completo - remove cursor
                                    streamingSuccess = true;
                                    const contentDiv = streamingDiv.querySelector('.message-content');
                                    if (contentDiv) {
                                        contentDiv.innerHTML = marked.parse(fullContent);
                                    }
                                    // Processa blocos de código para adicionar botão copiar
                                    processCodeBlocks();
                                } else if (data.error) {
                                    throw new Error(data.error);
                                }
                            } catch (parseError) {
                                // Ignora erros de parse em linhas vazias
                            }
                        }
                    }
                }
                
            } catch (streamError) {
                console.log('Streaming failed, trying normal method:', streamError.message);
                
                // Remove div de streaming se existir
                if (streamingDiv) {
                    streamingDiv.remove();
                }
                
                // Mostra loading novamente
                typingIndicator.classList.remove('hidden');
                scrollToBottom();
                
                // Fallback para metodo normal
                try {
                    const data = await api(`/chat/sessions/${currentSession.id}/messages`, {
                        method: 'POST',
                        body: JSON.stringify({ message: content })
                    });
                    
                    typingIndicator.classList.add('hidden');
                    addMessage(data.data.message);
                    processCodeBlocks();
                    streamingSuccess = true;
                    
                } catch (normalError) {
                    typingIndicator.classList.add('hidden');
                    
                    // Verifica se é erro de limite diário
                    if (normalError.message && normalError.message.includes('daily')) {
                        showLimitReachedModal();
                    } else {
                        addMessage({ role: 'assistant', content: `❌ **Erro:** ${normalError.message}` });
                    }
                }
            } finally {
                typingIndicator.classList.add('hidden');
                sendBtn.disabled = false;
                messageInput.focus();
            }
        });
        
        // Modal de limite atingido
        function showLimitReachedModal() {
            const existingModal = document.getElementById('limitReachedModal');
            if (existingModal) existingModal.remove();
            
            const modal = document.createElement('div');
            modal.id = 'limitReachedModal';
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95';
            modal.innerHTML = `
                <div class="bg-slate-900 rounded-2xl p-8 max-w-md text-center border border-red-500/30 mx-4">
                    <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-2">Limite Diário Atingido</h2>
                    <p class="text-gray-400 mb-6">Você atingiu o limite de requisições do seu plano. Faça upgrade para continuar usando.</p>
                    <div class="flex gap-3 justify-center">
                        <button onclick="document.getElementById('limitReachedModal').remove()" class="px-6 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-colors">
                            Fechar
                        </button>
                        <a href="/profile" class="px-6 py-2 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-lg hover:opacity-90 transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-gem"></i> Ver Planos
                        </a>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 150) + 'px';
        });
        
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                messageForm.dispatchEvent(new Event('submit'));
            }
        });
        
        async function init() {
            if (token) {
                try {
                    const data = await api('/auth/me');
                    currentUser = data.data;
                    onAuthSuccess();
                } catch (error) {
                    localStorage.removeItem('token');
                    token = null;
                    showAuthModal();
                }
            } else {
                showAuthModal();
            }
        }
        
        function showAuthModal() {
            authModal.classList.remove('hidden');
            authModal.classList.add('flex');
        }
        
        init();
    </script>
</body>
</html>

