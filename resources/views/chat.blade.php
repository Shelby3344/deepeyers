<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DeepEyes - Cyber Arsenal</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="apple-touch-icon" href="/logo.png">
    <meta name="theme-color" content="#0B0F14">
    <script>
        // Controla visibilidade baseado no token
        if (localStorage.getItem('token')) {
            document.documentElement.classList.add('has-token');
        } else {
            document.documentElement.classList.add('no-token');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'de-bg': '#0B0F14',
                        'de-bg-secondary': '#0F172A',
                        'de-bg-tertiary': '#1E293B',
                        'de-neon': '#00FF88',
                        'de-cyan': '#00D4FF',
                        'de-purple': '#A855F7',
                        'de-red': '#EF4444',
                        'de-orange': '#F97316',
                    },
                    fontFamily: {
                        'display': ['Space Grotesk', 'Inter', 'sans-serif'],
                        'mono': ['JetBrains Mono', 'Fira Code', 'monospace'],
                    },
                    animation: {
                        'glow-pulse': 'glow-pulse 2s ease-in-out infinite',
                        'scan': 'scan 8s linear infinite',
                        'typing': 'typing 0.8s steps(3) infinite',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="/css/deepeyes.css">
    <link rel="stylesheet" href="/css/mobile.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap');
        
        /* ========================================
           DEEPEYES PREMIUM HACKER UI
           ======================================== */
        
        html, body { 
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0B0F14 0%, #0a0015 50%, #0B0F14 100%);
            background-attachment: fixed;
            overflow: hidden;
            height: 100%;
        }
        code, pre { font-family: 'JetBrains Mono', monospace; }
        
        /* Hide auth if logged, show if not */
        .has-token #authModal { display: none !important; }
        .no-token #authModal { display: flex !important; }
        .no-token #app { display: none !important; }
        
        /* Scanline Effect */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 9999;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                rgba(0, 0, 0, 0.02) 2px,
                rgba(0, 0, 0, 0.02) 4px
            );
            opacity: 0.4;
        }
        
        /* Grid Background */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: -1;
            background-image: 
                linear-gradient(rgba(0, 255, 136, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 136, 0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 80% 50% at 50% 50%, black 40%, transparent 100%);
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0B0F14; }
        ::-webkit-scrollbar-thumb { 
            background: linear-gradient(135deg, #334155, #1E293B); 
            border-radius: 4px; 
        }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
        
        /* Neon Glow Classes */
        .glow-neon { box-shadow: 0 0 20px rgba(0, 255, 136, 0.3), 0 0 40px rgba(0, 255, 136, 0.1); }
        .glow-cyan { box-shadow: 0 0 20px rgba(0, 212, 255, 0.3), 0 0 40px rgba(0, 212, 255, 0.1); }
        .glow-red { box-shadow: 0 0 20px rgba(239, 68, 68, 0.3), 0 0 40px rgba(239, 68, 68, 0.1); }
        .glow-purple { box-shadow: 0 0 20px rgba(168, 85, 247, 0.3), 0 0 40px rgba(168, 85, 247, 0.1); }
        
        /* Text Gradient */
        .text-gradient-neon {
            background: linear-gradient(135deg, #00FF88, #00D4FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .text-gradient-attack {
            background: linear-gradient(135deg, #EF4444, #A855F7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Sidebar Styles */
        .de-sidebar {
            background: rgba(11, 15, 20, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(0, 255, 136, 0.1);
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 1023px) {
            .de-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 40;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                width: 280px;
            }
            
            .de-sidebar.mobile-open {
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
            
            /* Chat input mobile adjustments */
            #chatInputArea {
                padding: 12px !important;
            }
            
            #chatInputArea .max-w-4xl {
                max-width: 100% !important;
            }
            
            /* Feature cards mobile */
            #homeWelcome .grid-cols-2 {
                grid-template-columns: 1fr !important;
            }
            
            /* Session header mobile */
            #sessionHeader {
                padding-left: 60px !important;
            }
            
            /* Messages container mobile padding */
            #chatContainer {
                padding: 16px !important;
            }
            
            /* Auth modal mobile adjustments */
            #authModal .max-w-md {
                max-width: 95% !important;
                margin: 16px !important;
            }
            
            /* Delete/Domain modals mobile */
            #deleteModal .max-w-sm,
            #domainModal .max-w-md {
                max-width: 90% !important;
            }
            
            /* Profile dropdown mobile */
            #profileDropdown.open {
                width: calc(100vw - 32px) !important;
                max-width: 300px;
            }
            
            /* Attack mode selector mobile - stack vertical */
            .attack-mode-selector {
                flex-direction: column !important;
                gap: 8px !important;
            }
            
            .attack-mode-selector .attack-mode {
                width: 100% !important;
                justify-content: flex-start !important;
                padding: 12px 16px !important;
            }
            
            /* Welcome section mobile */
            #homeWelcome h3 {
                font-size: 1.5rem !important;
            }
            
            #homeWelcome .mb-10 {
                margin-bottom: 1.5rem !important;
            }
            
            /* Blob cards mobile */
            .blob-card {
                padding: 16px !important;
            }
            
            /* Profile card mobile adjustments */
            #currentProfileCard {
                max-width: 100% !important;
            }
            
            #welcomeProfileFeatures {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            /* Typing indicator mobile */
            #typingIndicator {
                padding: 12px !important;
            }
            
            /* Input bar bottom info - hide on mobile */
            #inputContainer .border-t .text-gray-600 {
                display: none !important;
            }
        }
        
        /* Small mobile adjustments */
        @media (max-width: 480px) {
            #homeWelcome h3 {
                font-size: 1.25rem !important;
            }
            
            #homeWelcome > .relative img {
                height: 80px !important;
            }
            
            .blob-card h4 {
                font-size: 1rem !important;
            }
            
            #welcomeProfileFeatures {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 4px !important;
            }
            
            /* User info in sidebar */
            .de-sidebar .p-4 {
                padding: 12px !important;
            }
        }
        
        .sidebar-item {
            transition: all 0.25s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-item:hover {
            background: rgba(0, 255, 136, 0.05);
            border-left-color: rgba(0, 255, 136, 0.3);
        }
        
        .sidebar-item:hover .delete-session-btn { opacity: 1; }
        
        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(0, 255, 136, 0.1), transparent);
            border-left-color: #00FF88;
        }
        
        /* Chat Messages */
        .chat-container { height: calc(100vh - 200px); }
        
        .message-content pre {
            background: #0B0F14;
            border: 1px solid #1E293B;
            border-radius: 10px;
            padding: 16px;
            overflow-x: auto;
            margin: 8px 0;
            position: relative;
        }
        
        .message-content code {
            background: rgba(0, 255, 136, 0.1);
            color: #00FF88;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.875rem;
        }
        
        .message-content pre code {
            background: none;
            color: #00D4FF;
            padding: 0;
        }
        
        /* Code Block */
        .code-block-wrapper { position: relative; margin: 12px 0; }
        
        .code-block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #0B0F14;
            border-radius: 10px 10px 0 0;
            padding: 10px 14px;
            border: 1px solid #1E293B;
            border-bottom: none;
        }
        
        .code-block-lang {
            font-size: 11px;
            color: #00FF88;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: rgba(0, 255, 136, 0.1);
            padding: 3px 10px;
            border-radius: 4px;
        }
        
        .code-copy-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: none;
            color: #64748B;
            font-size: 11px;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .code-copy-btn:hover { background: rgba(0, 255, 136, 0.1); color: #00FF88; }
        .code-copy-btn.copied { color: #00FF88; }
        
        .code-block-wrapper pre {
            margin: 0;
            border-radius: 0 0 10px 10px;
            border: 1px solid #1E293B;
            border-top: none;
        }
        
        /* Input Glow Effect */
        #inputContainer {
            transition: all 0.3s ease;
            background: rgba(11, 15, 20, 0.9);
            backdrop-filter: blur(20px);
        }
        
        #inputContainer:focus-within {
            border-color: rgba(0, 255, 136, 0.4) !important;
            box-shadow: 0 0 0 1px rgba(0, 255, 136, 0.2),
                        0 0 30px rgba(0, 255, 136, 0.1),
                        0 0 60px rgba(0, 212, 255, 0.05);
        }
        
        #messageInput {
            line-height: 1.5;
        }
        
        #messageInput::-webkit-scrollbar { width: 4px; }
        #messageInput::-webkit-scrollbar-track { background: transparent; }
        #messageInput::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }
        
        /* Streaming Cursor */
        .streaming-cursor {
            animation: blink 0.8s infinite;
            color: #00FF88;
            text-shadow: 0 0 10px #00FF88;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
        
        /* AI Thinking Animation */
        .ai-thinking { display: flex; align-items: center; gap: 12px; }
        
        .ai-brain { position: relative; width: 32px; height: 32px; }
        
        .ai-brain-icon {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .ai-brain-ring {
            position: absolute;
            inset: -4px;
            border: 2px solid transparent;
            border-top-color: #00FF88;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .ai-brain-ring-2 {
            position: absolute;
            inset: -8px;
            border: 2px solid transparent;
            border-bottom-color: #00D4FF;
            border-radius: 50%;
            animation: spin 1.5s linear infinite reverse;
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        @keyframes pulse-glow {
            0%, 100% { filter: drop-shadow(0 0 4px #00FF88); transform: scale(1); }
            50% { filter: drop-shadow(0 0 12px #00FF88) drop-shadow(0 0 20px #00D4FF); transform: scale(1.1); }
        }
        
        .thinking-dots { display: flex; gap: 4px; }
        
        .thinking-dots span {
            width: 8px;
            height: 8px;
            background: linear-gradient(135deg, #00FF88, #00D4FF);
            border-radius: 50%;
            animation: thinking-bounce 1.4s ease-in-out infinite;
        }
        
        .thinking-dots span:nth-child(1) { animation-delay: 0s; }
        .thinking-dots span:nth-child(2) { animation-delay: 0.2s; }
        .thinking-dots span:nth-child(3) { animation-delay: 0.4s; }
        
        @keyframes thinking-bounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-10px); opacity: 1; }
        }
        
        /* AI Loading Spinner */
        .ai-spinner {
            position: relative;
            width: 48px;
            height: 48px;
        }
        
        .ai-spinner .spinner-outer {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(135deg, #00FF88 0%, #00D4FF 50%, #BA42FF 100%);
            border-radius: 50%;
            animation: ai-spin 1.5s linear infinite;
            filter: blur(1px);
            box-shadow: 0px -3px 15px 0px rgba(0, 255, 136, 0.5), 
                        0px 3px 15px 0px rgba(0, 212, 255, 0.5),
                        0px 0px 20px 0px rgba(186, 66, 255, 0.3);
        }
        
        .ai-spinner .spinner-inner {
            position: absolute;
            inset: 4px;
            background: linear-gradient(135deg, #0B0F14 0%, #0F172A 100%);
            border-radius: 50%;
            filter: blur(2px);
        }
        
        .ai-spinner .spinner-core {
            position: absolute;
            inset: 8px;
            background: radial-gradient(circle at center, rgba(0, 255, 136, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .ai-spinner .spinner-core i {
            font-size: 14px;
            color: #00FF88;
            animation: pulse-icon 1s ease-in-out infinite;
            filter: drop-shadow(0 0 8px #00FF88);
        }
        
        @keyframes ai-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes pulse-icon {
            0%, 100% { opacity: 0.6; transform: scale(0.9); }
            50% { opacity: 1; transform: scale(1.1); }
        }
        
        .thinking-text {
            background: linear-gradient(90deg, #00FF88, #00D4FF, #00FF88);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradient-text 2s linear infinite;
        }
        
        @keyframes gradient-text { to { background-position: 200% center; } }
        
        .neural-line {
            height: 2px;
            background: linear-gradient(90deg, transparent, #00FF88, #00D4FF, #00FF88, transparent);
            background-size: 200% 100%;
            animation: neural-flow 1.5s linear infinite;
            border-radius: 2px;
        }
        
        @keyframes neural-flow {
            0% { background-position: 100% 0; }
            100% { background-position: -100% 0; }
        }
        
        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #0B0F14 0%, #0a0015 50%, #0B0F14 100%);
        }
        
        /* Animation Slide-in */
        @keyframes slide-in {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in { animation: slide-in 0.3s ease-out forwards; }
        
        /* Feature Cards */
        .feature-card {
            --card-color: #00FF88;
            position: relative;
            background: linear-gradient(135deg, rgba(11, 15, 20, 0.95), rgba(15, 23, 42, 0.9));
            border: 1px solid rgba(0, 255, 136, 0.1);
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            padding: 1px;
            background: linear-gradient(135deg, var(--card-color), transparent 50%, var(--card-color));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .feature-card:hover::before { opacity: 1; }
        
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 40px -10px rgba(0, 255, 136, 0.2);
        }
        
        .feature-card .card-icon {
            font-size: 1.75rem;
            color: var(--card-color);
            filter: drop-shadow(0 0 10px currentColor);
            transition: all 0.3s;
        }
        
        .feature-card:hover .card-icon {
            transform: scale(1.2);
            filter: drop-shadow(0 0 20px currentColor);
        }
        
        .card-red { --card-color: #EF4444; }
        .card-purple { --card-color: #A855F7; }
        .card-green { --card-color: #00FF88; }
        .card-cyan { --card-color: #00D4FF; }
        .card-blue { --card-color: #3B82F6; }
        
        /* Profile Dropdown */
        .profile-dropdown { position: relative; width: 100%; }
        
        .profile-selected {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(11, 15, 20, 0.9);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 10px;
            color: white;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.25s;
        }
        
        .profile-selected:hover { 
            border-color: #00FF88;
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.1);
        }
        .profile-selected .chevron { margin-left: auto; transition: transform 0.2s; }
        .profile-dropdown.open .chevron { transform: rotate(180deg); }
        
        .profile-options {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: rgba(11, 15, 20, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 10px;
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
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            color: #94A3B8;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            border-left: 2px solid transparent;
        }
        
        .profile-option:hover { 
            background: rgba(0, 255, 136, 0.05);
            color: white;
            border-left-color: rgba(0, 255, 136, 0.3);
        }
        .profile-option.selected { 
            background: rgba(0, 255, 136, 0.1);
            color: #00FF88;
            border-left-color: #00FF88;
        }
        .profile-option i { font-size: 1rem; width: 1.25rem; text-align: center; }
        
        /* New Session Button */
        .btn-wrapper { position: relative; display: inline-block; width: 100%; }
        
        .sparkle-btn {
            cursor: pointer;
            border: 1px solid rgba(0, 255, 136, 0.3);
            border-radius: 12px;
            position: relative;
            transition: all 0.3s ease;
            width: 100%;
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), rgba(0, 212, 255, 0.05));
            padding: 0;
            overflow: hidden;
        }
        
        .sparkle-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #00FF88, #00D4FF);
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .sparkle-btn:hover::before { opacity: 0.1; }
        
        .sparkle-btn .inner {
            position: relative;
            padding: 14px 24px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #00FF88;
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.3);
        }
        
        .sparkle-btn:hover {
            border-color: #00FF88;
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.2);
            transform: translateY(-2px);
        }
        
        .sparkle-btn:hover .inner { color: #fff; }
        
        /* Attack Mode Selector */
        .attack-mode {
            --mode-color: #00FF88;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 12px 8px;
            background: rgba(11, 15, 20, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
        }
        
        .attack-mode::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--mode-color), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .attack-mode:hover::before,
        .attack-mode.active::before { opacity: 0.1; }
        
        .attack-mode.active {
            border-color: var(--mode-color);
            box-shadow: 0 0 20px rgba(var(--mode-rgb), 0.2);
        }
        
        .attack-mode i {
            font-size: 18px;
            color: var(--mode-color);
            transition: all 0.3s;
            position: relative;
            z-index: 1;
        }
        
        .attack-mode.active i {
            filter: drop-shadow(0 0 8px currentColor);
            animation: mode-pulse 2s ease-in-out infinite;
        }
        
        .attack-mode span {
            font-size: 10px;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }
        
        .attack-mode.active span { color: var(--mode-color); }
        
        .mode-pentest { --mode-color: #00FF88; --mode-rgb: 0, 255, 136; }
        .mode-redteam { --mode-color: #F97316; --mode-rgb: 249, 115, 22; }
        .mode-fullattack { --mode-color: #EF4444; --mode-rgb: 239, 68, 68; }
        
        /* Perfil bloqueado (sem plano) */
        .attack-mode.locked {
            opacity: 0.4;
            cursor: not-allowed;
            position: relative;
        }
        
        .attack-mode.locked::after {
            content: '\f023';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 4px;
            right: 4px;
            font-size: 8px;
            color: #EF4444;
            background: rgba(0,0,0,0.7);
            padding: 2px 4px;
            border-radius: 4px;
        }
        
        .attack-mode.locked:hover::before { opacity: 0; }
        .attack-mode.locked:hover { transform: none; }

        @keyframes mode-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Auth Modal */
        .auth-tab {
            transition: all 0.25s;
        }
        
        .auth-tab.active {
            background: linear-gradient(135deg, #00FF88, #00D4FF) !important;
            color: #0B0F14 !important;
        }
        
        .auth-input {
            background: rgba(11, 15, 20, 0.9);
            border: 1px solid rgba(0, 255, 136, 0.2);
            transition: all 0.25s;
        }
        
        .auth-input:focus {
            border-color: #00FF88;
            box-shadow: 0 0 0 3px rgba(0, 255, 136, 0.1);
        }
        
        /* User Message Bubble */
        .user-bubble {
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.15), rgba(0, 212, 255, 0.1));
            border: 1px solid rgba(0, 255, 136, 0.2);
        }
        
        /* AI Message Bubble */
        .ai-bubble {
            background: rgba(11, 15, 20, 0.9);
            border: 1px solid rgba(0, 255, 136, 0.1);
        }
        
        /* Notification */
        .notification {
            background: rgba(11, 15, 20, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 255, 136, 0.2);
        }
        
        .notification.success { border-color: #00FF88; }
        .notification.error { border-color: #EF4444; }
        .notification.warning { border-color: #FBBF24; }
        
        /* Blob Glow Cards */
        .blob-card {
            position: relative;
            border-radius: 16px;
            z-index: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .blob-card .card-bg {
            position: absolute;
            top: 2px;
            left: 2px;
            right: 2px;
            bottom: 2px;
            z-index: 2;
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(24px);
            border-radius: 14px;
            overflow: hidden;
        }
        
        .blob-card .card-content {
            position: relative;
            z-index: 3;
            padding: 1.25rem;
        }
        
        .blob-card .blob {
            position: absolute;
            z-index: 1;
            top: 50%;
            left: 50%;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            opacity: 0.8;
            filter: blur(20px);
        }
        
        /* Blob Red - SQL Injection */
        .blob-card.blob-red .blob {
            background-color: #EF4444;
            animation: blob-bounce-1 4s infinite ease;
        }
        
        /* Blob Green - Reverse Shells */
        .blob-card.blob-green .blob {
            background-color: #00FF88;
            animation: blob-bounce-2 5s infinite ease;
            animation-delay: -1s;
        }
        
        /* Blob Orange - Privilege Escalation */
        .blob-card.blob-orange .blob {
            background-color: #F97316;
            animation: blob-bounce-3 4.5s infinite ease;
            animation-delay: -2s;
        }
        
        /* Blob Cyan - Evasion */
        .blob-card.blob-cyan .blob {
            background-color: #00D4FF;
            animation: blob-bounce-4 5.5s infinite ease;
            animation-delay: -0.5s;
        }
        
        @keyframes blob-bounce-1 {
            0% { transform: translate(-100%, -100%) translate3d(0, 0, 0) rotate(0deg); }
            25% { transform: translate(-100%, -100%) translate3d(100%, 0, 0) rotate(90deg); }
            50% { transform: translate(-100%, -100%) translate3d(100%, 100%, 0) rotate(180deg); }
            75% { transform: translate(-100%, -100%) translate3d(0, 100%, 0) rotate(270deg); }
            100% { transform: translate(-100%, -100%) translate3d(0, 0, 0) rotate(360deg); }
        }
        
        @keyframes blob-bounce-2 {
            0% { transform: translate(-100%, -100%) translate3d(100%, 100%, 0) rotate(0deg); }
            25% { transform: translate(-100%, -100%) translate3d(0, 100%, 0) rotate(-90deg); }
            50% { transform: translate(-100%, -100%) translate3d(0, 0, 0) rotate(-180deg); }
            75% { transform: translate(-100%, -100%) translate3d(100%, 0, 0) rotate(-270deg); }
            100% { transform: translate(-100%, -100%) translate3d(100%, 100%, 0) rotate(-360deg); }
        }
        
        @keyframes blob-bounce-3 {
            0% { transform: translate(-100%, -100%) translate3d(50%, 0, 0) rotate(45deg); }
            25% { transform: translate(-100%, -100%) translate3d(100%, 50%, 0) rotate(135deg); }
            50% { transform: translate(-100%, -100%) translate3d(50%, 100%, 0) rotate(225deg); }
            75% { transform: translate(-100%, -100%) translate3d(0, 50%, 0) rotate(315deg); }
            100% { transform: translate(-100%, -100%) translate3d(50%, 0, 0) rotate(405deg); }
        }
        
        @keyframes blob-bounce-4 {
            0% { transform: translate(-100%, -100%) translate3d(0, 50%, 0) rotate(0deg); }
            33% { transform: translate(-100%, -100%) translate3d(100%, 0, 0) rotate(120deg); }
            66% { transform: translate(-100%, -100%) translate3d(50%, 100%, 0) rotate(240deg); }
            100% { transform: translate(-100%, -100%) translate3d(0, 50%, 0) rotate(360deg); }
        }
        
        .blob-card:hover .blob {
            filter: blur(15px);
            opacity: 1;
        }
        
        .blob-card:hover {
            transform: translateY(-4px);
        }
        
        .blob-card {
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen text-gray-100">
    <!-- Auth Modal - Outside #app for proper visibility control -->
    <div id="authModal" class="fixed inset-0 z-50 items-center justify-center hidden overflow-hidden" style="background: linear-gradient(135deg, #0B0F14 0%, #0a0015 50%, #0B0F14 100%);">
        <!-- Grid Background -->
        <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(0, 255, 136, 0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 255, 136, 0.02) 1px, transparent 1px); background-size: 60px 60px; mask-image: radial-gradient(ellipse 80% 50% at 50% 50%, black 40%, transparent 100%);"></div>
        
        <div class="relative z-10 w-full max-w-md mx-4 bg-[rgba(11,15,20,0.95)] backdrop-blur-xl rounded-2xl p-8 border border-[rgba(0,255,136,0.2)] max-h-[90vh] overflow-y-auto" style="box-shadow: 0 0 80px rgba(0,255,136,0.05), 0 0 40px rgba(0,212,255,0.05); scrollbar-width: thin; scrollbar-color: rgba(0,255,136,0.3) transparent;">
            <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#00FF88] to-transparent"></div>
            
            <div class="text-center mb-8">
                <div class="relative inline-block">
                    <img src="/logo.png" alt="DeepEyes" class="h-20 mx-auto mb-4" style="filter: drop-shadow(0 0 20px rgba(0,255,136,0.5));">
                    <div class="absolute inset-0 rounded-full" style="background: radial-gradient(circle, rgba(0,255,136,0.2) 0%, transparent 70%);"></div>
                </div>
                <h1 class="text-3xl font-bold text-gradient-neon font-display">DeepEyes</h1>
                <p class="text-gray-500 mt-2 text-sm uppercase tracking-widest">Cyber Arsenal</p>
            </div>

            <div id="authTabs" class="flex gap-2 mb-6 p-1 bg-[#0B0F14] rounded-xl border border-[rgba(0,255,136,0.1)]">
                <button class="auth-tab active flex-1 py-3 px-4 rounded-lg font-semibold transition-all" data-tab="login">Login</button>
                <button class="auth-tab flex-1 py-3 px-4 rounded-lg bg-transparent text-gray-500 hover:text-white font-semibold transition-all" data-tab="register">Registrar</button>
            </div>

            <form id="loginForm" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" required autocomplete="email"
                        class="auth-input w-full rounded-xl px-4 py-3.5 text-white focus:outline-none"
                        placeholder="seu@email.com">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Senha</label>
                    <input type="password" name="password" required autocomplete="current-password"
                        class="auth-input w-full rounded-xl px-4 py-3.5 text-white focus:outline-none"
                        placeholder="••••••••">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-[#00FF88] to-[#00D4FF] text-[#0B0F14] rounded-xl py-3.5 font-bold transition-all hover:opacity-90 hover:shadow-[0_0_30px_rgba(0,255,136,0.3)]">
                    <i class="fas fa-terminal mr-2"></i>Acessar Sistema
                </button>
            </form>

            <form id="registerForm" class="space-y-4 hidden">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nome</label>
                    <input type="text" name="name" required autocomplete="name"
                        class="auth-input w-full rounded-xl px-4 py-3.5 text-white focus:outline-none"
                        placeholder="Seu nome">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" name="email" required autocomplete="email"
                        class="auth-input w-full rounded-xl px-4 py-3.5 text-white focus:outline-none"
                        placeholder="seu@email.com">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Senha</label>
                    <input type="password" name="password" required autocomplete="new-password"
                        class="auth-input w-full rounded-xl px-4 py-3.5 text-white focus:outline-none"
                        placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                        class="auth-input w-full rounded-xl px-4 py-3.5 text-white focus:outline-none"
                        placeholder="••••••••">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-[#00FF88] to-[#00D4FF] text-[#0B0F14] rounded-xl py-3.5 font-bold transition-all hover:opacity-90 hover:shadow-[0_0_30px_rgba(0,255,136,0.3)]">
                    <i class="fas fa-user-plus mr-2"></i>Criar Conta
                </button>
            </form>

            <div id="authError" class="hidden mt-4 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm"></div>
            
            <p class="text-center text-gray-600 text-xs mt-6">
                <i class="fas fa-shield-halved mr-1 text-[#00FF88]"></i>
                Ambiente seguro para profissionais autorizados
            </p>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" onclick="closeMobileSidebar()"></div>

    <div id="app" class="flex h-screen w-full">
        <!-- Sidebar -->
        <aside id="sidebar" class="de-sidebar w-72 flex-shrink-0 flex flex-col">
            <!-- Close button for mobile -->
            <button id="closeSidebarBtn" class="lg:hidden absolute top-4 right-4 w-8 h-8 rounded-lg bg-[rgba(255,255,255,0.05)] hover:bg-[rgba(239,68,68,0.1)] text-gray-400 hover:text-red-400 flex items-center justify-center transition-all z-10">
                <i class="fas fa-times"></i>
            </button>
            <!-- Logo -->
            <div class="p-5 border-b border-[rgba(0,255,136,0.1)]">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <img src="/logo.png" alt="DeepEyes" class="h-11 w-11 object-contain" style="filter: drop-shadow(0 0 10px rgba(0,255,136,0.5));">
                        <div class="absolute inset-0 rounded-full" style="background: radial-gradient(circle, rgba(0,255,136,0.2) 0%, transparent 70%);"></div>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gradient-neon font-display tracking-tight">DeepEyes</h1>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest">Cyber Arsenal</p>
                    </div>
                </div>
            </div>
            
            <!-- Attack Mode Selector -->
            <div class="p-4 border-b border-[rgba(0,255,136,0.1)]">
                <label class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest mb-3 block flex items-center gap-2">
                    <i class="fas fa-crosshairs text-[#00FF88]"></i>
                    Modo de Ataque
                </label>
                <input type="hidden" id="profileSelector" value="pentest">
                <div class="grid grid-cols-3 gap-2">
                    <div class="attack-mode mode-pentest active" data-value="pentest" onclick="selectAttackMode(this)">
                        <i class="fas fa-shield-halved"></i>
                        <span>Pentest</span>
                    </div>
                    <div class="attack-mode mode-redteam" data-value="redteam" onclick="selectAttackMode(this)">
                        <i class="fas fa-crosshairs"></i>
                        <span>Red Team</span>
                    </div>
                    <div class="attack-mode mode-fullattack" data-value="offensive" onclick="selectAttackMode(this)">
                        <i class="fas fa-biohazard"></i>
                        <span>Full Attack</span>
                    </div>
                </div>
                <p class="text-[10px] text-gray-600 mt-2 text-center" id="profileDescription">🟢 Modo ofensivo autorizado</p>
            </div>
            
            <!-- Toast de Plano Necessário -->
            <div id="planToast" class="fixed top-4 right-4 z-[9999] hidden">
                <div class="bg-[#1a1f2e] border border-yellow-500/30 rounded-lg p-3 shadow-lg flex items-center gap-3">
                    <i class="fas fa-lock text-yellow-500"></i>
                    <span class="text-sm text-gray-300">Ative um plano para desbloquear</span>
                </div>
            </div>
            
            <!-- Hidden legacy dropdown for compatibility -->
            <div class="hidden">
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-selected" onclick="toggleProfileDropdown()">
                        <i class="fas fa-skull-crossbones text-[#00FF88] profile-icon" id="selectedProfileIcon"></i>
                        <span id="selectedProfileText">DeepEyes - Ofensivo</span>
                        <i class="fas fa-chevron-down text-gray-400 chevron"></i>
                    </div>
                    <div class="profile-options">
                        <div class="profile-option selected" data-value="pentest" data-icon="fa-shield-halved" data-color="text-[#00FF88]" onclick="selectProfile(this)">
                            <i class="fas fa-shield-halved text-[#00FF88]"></i>
                            <span>DeepEyes - Pentest</span>
                        </div>
                        <div class="profile-option" data-value="redteam" data-icon="fa-crosshairs" data-color="text-orange-400" onclick="selectProfile(this)">
                            <i class="fas fa-crosshairs text-orange-400"></i>
                            <span>BlackSentinel - Red Team</span>
                        </div>
                        <div class="profile-option" data-value="offensive" data-icon="fa-biohazard" data-color="text-red-400" onclick="selectProfile(this)">
                            <i class="fas fa-biohazard text-red-400"></i>
                            <span>DarkMind - Full Attack</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- New Session Button -->
            <div class="p-4">
                <div class="btn-wrapper">
                    <button id="newChatBtn" class="sparkle-btn">
                        <div class="inner">
                            <i class="fas fa-plus"></i>
                            <span>Nova Sessão</span>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Sessions List -->
            <div class="flex-1 overflow-y-auto px-3 py-2">
                <div class="text-[10px] font-semibold text-gray-500 uppercase tracking-widest px-3 py-2 flex items-center gap-2">
                    <i class="fas fa-history text-[#00FF88] text-[8px]"></i>
                    Sessões Ativas
                </div>
                <div id="sessionsList" class="space-y-1"></div>
            </div>
            
            <!-- User Info -->
            <div class="p-4 border-t border-[rgba(0,255,136,0.1)]">
                <div id="userInfo" class="flex items-center gap-3 p-3 rounded-xl bg-[rgba(0,255,136,0.03)] border border-[rgba(0,255,136,0.1)] hover:border-[rgba(0,255,136,0.3)] transition-all cursor-pointer" onclick="window.location.href='/profile'">
                    <a href="/profile" id="userAvatarLink" class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00FF88] to-[#00D4FF] p-[2px] flex-shrink-0">
                        <div class="w-full h-full rounded-[10px] bg-[#0B0F14] flex items-center justify-center overflow-hidden">
                            <img id="userAvatarImg" src="/photo.jpeg" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                    </a>
                    <div class="flex-1 min-w-0">
                        <a href="/profile" id="userName" class="text-sm font-semibold text-white hover:text-[#00FF88] transition-colors block truncate">Não logado</a>
                        <div id="userRole" class="text-[10px] text-gray-500 uppercase tracking-wider">-</div>
                    </div>
                    <a href="/profile" class="text-gray-500 hover:text-[#00FF88] transition-colors hidden" id="profileLink" title="Perfil">
                        <i class="fas fa-cog"></i>
                    </a>
                    <button id="logoutBtn" class="text-gray-500 hover:text-red-400 transition-colors hidden" title="Sair">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </aside>
        
        <!-- Mobile Sidebar Toggle -->
        <button id="mobileMenuBtn" class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 rounded-xl bg-[rgba(0,255,136,0.1)] border border-[rgba(0,255,136,0.2)] text-[#00FF88] flex items-center justify-center">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
            <div class="relative z-10 w-full max-w-sm mx-4 bg-[#0B0F14] rounded-2xl p-6 border border-[rgba(239,68,68,0.3)] shadow-2xl" style="box-shadow: 0 0 40px rgba(239,68,68,0.1);">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-red-500 to-transparent"></div>
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-red-500/10 flex items-center justify-center border border-red-500/30">
                        <i class="fas fa-trash-alt text-2xl text-red-500"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Excluir Sessão</h3>
                    <p class="text-gray-400 text-sm">Tem certeza que deseja excluir esta sessão? Esta ação não pode ser desfeita.</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="closeDeleteModal()" class="flex-1 bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Cancelar</button>
                    <button onclick="confirmDeleteSession()" class="flex-1 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white rounded-xl py-3 font-medium transition-all glow-red">
                        <i class="fas fa-trash-alt mr-2"></i>Excluir
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Domain Input Modal -->
        <div id="domainModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeDomainModal()"></div>
            <div class="relative z-10 w-full max-w-md mx-4 bg-[#0B0F14] rounded-2xl p-6 border border-[rgba(0,255,136,0.3)] shadow-2xl" style="box-shadow: 0 0 60px rgba(0,255,136,0.1);">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#00FF88] to-transparent"></div>
                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[rgba(0,255,136,0.1)] border border-[rgba(0,255,136,0.3)] flex items-center justify-center">
                        <i class="fas fa-crosshairs text-[#00FF88]"></i>
                    </div>
                    Definir Alvo
                </h3>
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Domínio do Alvo</label>
                    <input type="text" id="domainInput" 
                        class="w-full bg-[#0B0F14] border border-[rgba(0,255,136,0.2)] rounded-xl px-4 py-4 text-white focus:outline-none focus:border-[#00FF88] focus:shadow-[0_0_0_3px_rgba(0,255,136,0.1)] transition-all font-mono"
                        placeholder="target.example.com"
                        onkeydown="if(event.key === 'Enter') confirmDomainModal()">
                </div>
                <div class="flex gap-3">
                    <button onclick="closeDomainModal()" class="flex-1 bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Cancelar</button>
                    <button onclick="confirmDomainModal()" class="flex-1 bg-gradient-to-r from-[#00FF88] to-[#00D4FF] hover:opacity-90 text-[#0B0F14] rounded-xl py-3 font-bold transition-all">
                        <i class="fas fa-terminal mr-2"></i>Iniciar Sessão
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-h-0">
            <!-- Beta Warning Banner -->
            <div id="betaBanner" class="bg-gradient-to-r from-amber-500/20 via-orange-500/20 to-amber-500/20 border-b border-amber-500/30 px-4 py-2 lg:py-3 flex-shrink-0">
                <div class="flex items-center justify-center gap-2 lg:gap-3 text-center flex-wrap">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-flask text-amber-400 animate-pulse text-sm"></i>
                        <span class="text-amber-300 font-semibold text-xs lg:text-sm">BETA</span>
                    </div>
                    <span class="text-amber-200/80 text-xs lg:text-sm">
                        Estamos em desenvolvimento. Algumas funcionalidades podem estar instáveis.
                    </span>
                    <button onclick="closeBetaBanner()" class="text-amber-400 hover:text-amber-200 transition-colors ml-1" title="Fechar aviso">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
            
            <!-- Session Header -->
            <div id="sessionHeader" class="hidden px-6 py-4 bg-[rgba(11,15,20,0.9)] backdrop-blur-xl border-b border-[rgba(0,255,136,0.1)] flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[rgba(0,255,136,0.2)] to-[rgba(0,212,255,0.1)] flex items-center justify-center border border-[rgba(0,255,136,0.3)]">
                        <i class="fas fa-crosshairs text-[#00FF88] animate-pulse"></i>
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
            <div id="chatContainer" class="flex-1 overflow-y-auto p-6">
                <!-- Welcome para Pagina Inicial (sem sessao) -->
                <div id="homeWelcome" class="flex flex-col items-center justify-center text-center py-10">
                    <!-- Logo com efeito glow -->
                    <div class="relative mb-8">
                        <div class="absolute inset-0 blur-3xl bg-gradient-to-r from-[#00FF88]/30 to-[#00D4FF]/30 rounded-full scale-150"></div>
                        <img src="/logo.png" alt="DeepEyes" class="h-28 relative z-10 drop-shadow-2xl">
                    </div>
                    
                    <!-- Título com gradient -->
                    <h3 class="text-3xl font-bold mb-3 bg-gradient-to-r from-white via-[#00FF88] to-[#00D4FF] bg-clip-text text-transparent">
                        Bem-vindo ao DeepEyes
                    </h3>
                    <p class="text-gray-400 max-w-md mb-10 leading-relaxed">
                        Sua IA de <span class="text-[#00FF88] font-semibold">Segurança Ofensiva</span> para Pentest, Red Team e CTFs.
                        <br><span class="text-gray-500 text-sm">Ambiente de laboratório autorizado.</span>
                    </p>
                    
                    <!-- Feature Cards - Premium Design with Blob Glow -->
                    <div class="grid grid-cols-2 gap-5 max-w-xl">
                        <!-- SQL Injection Card -->
                        <div class="blob-card blob-red group">
                            <div class="blob"></div>
                            <div class="card-bg"></div>
                            <div class="card-content">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#EF4444]/20 to-[#EF4444]/5 flex items-center justify-center mb-4 border border-[#EF4444]/30 group-hover:border-[#EF4444]/60 transition-colors">
                                    <i class="fas fa-database text-[#EF4444] text-xl group-hover:scale-110 transition-transform"></i>
                                </div>
                                <h4 class="font-bold mb-2 text-white text-lg group-hover:text-[#EF4444] transition-colors">SQL Injection</h4>
                                <p class="text-sm text-gray-400">Payloads, bypasses, técnicas avançadas</p>
                            </div>
                        </div>
                        
                        <!-- Reverse Shells Card -->
                        <div class="blob-card blob-green group">
                            <div class="blob"></div>
                            <div class="card-bg"></div>
                            <div class="card-content">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#00FF88]/20 to-[#00FF88]/5 flex items-center justify-center mb-4 border border-[#00FF88]/30 group-hover:border-[#00FF88]/60 transition-colors">
                                    <i class="fas fa-terminal text-[#00FF88] text-xl group-hover:scale-110 transition-transform"></i>
                                </div>
                                <h4 class="font-bold mb-2 text-white text-lg group-hover:text-[#00FF88] transition-colors">Reverse Shells</h4>
                                <p class="text-sm text-gray-400">One-liners, stagers, implants</p>
                            </div>
                        </div>
                        
                        <!-- Privilege Escalation Card -->
                        <div class="blob-card blob-orange group">
                            <div class="blob"></div>
                            <div class="card-bg"></div>
                            <div class="card-content">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#F97316]/20 to-[#F97316]/5 flex items-center justify-center mb-4 border border-[#F97316]/30 group-hover:border-[#F97316]/60 transition-colors">
                                    <i class="fas fa-user-secret text-[#F97316] text-xl group-hover:scale-110 transition-transform"></i>
                                </div>
                                <h4 class="font-bold mb-2 text-white text-lg group-hover:text-[#F97316] transition-colors">Privilege Escalation</h4>
                                <p class="text-sm text-gray-400">Linux, Windows, AD attacks</p>
                            </div>
                        </div>
                        
                        <!-- Evasion Card -->
                        <div class="blob-card blob-cyan group">
                            <div class="blob"></div>
                            <div class="card-bg"></div>
                            <div class="card-content">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#00D4FF]/20 to-[#00D4FF]/5 flex items-center justify-center mb-4 border border-[#00D4FF]/30 group-hover:border-[#00D4FF]/60 transition-colors">
                                    <i class="fas fa-mask text-[#00D4FF] text-xl group-hover:scale-110 transition-transform"></i>
                                </div>
                                <h4 class="font-bold mb-2 text-white text-lg group-hover:text-[#00D4FF] transition-colors">Evasion</h4>
                                <p class="text-sm text-gray-400">AMSI, EDR, WAF bypass</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Warning Banner -->
                    <div class="mt-8 p-4 bg-[#EF4444]/10 border border-[#EF4444]/30 rounded-xl max-w-lg">
                        <p class="text-xs text-[#EF4444] flex items-center justify-center gap-2">
                            <i class="fas fa-radiation animate-pulse"></i>
                            <span><strong>AVISO:</strong> Use apenas em ambientes autorizados. O operador é responsável pelo uso ético e legal.</span>
                        </p>
                    </div>
                </div>
                
                <!-- Welcome para Sessao (sem mensagens ainda) -->
                <div id="sessionWelcome" class="flex flex-col items-center justify-center h-full text-center hidden">
                    <!-- Logo compacto -->
                    <div class="relative mb-6">
                        <div class="absolute inset-0 blur-2xl bg-gradient-to-r from-[#00FF88]/20 to-[#00D4FF]/20 rounded-full scale-150"></div>
                        <img src="/logo.png" alt="DeepEyes" class="h-16 relative z-10">
                    </div>
                    
                    <h3 class="text-2xl font-bold text-white mb-2">Pronto para começar!</h3>
                    <p class="text-gray-400 max-w-md mb-6">
                        Faça sua primeira pergunta e eu vou te ajudar com técnicas de 
                        <span class="text-[#00FF88] font-semibold">Pentest</span> e 
                        <span class="text-[#00D4FF] font-semibold">Red Team</span>.
                    </p>
                    
                    <!-- Perfil Atual - Premium Card -->
                    <div id="currentProfileCard" class="w-full max-w-md de-card p-5 mb-5">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#00FF88]/20 to-[#00D4FF]/10 flex items-center justify-center border border-[#00FF88]/30">
                                <i id="welcomeProfileIcon" class="fas fa-skull-crossbones text-[#00FF88] text-xl"></i>
                            </div>
                            <div class="text-left flex-1">
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-medium">Modo Ativo</p>
                                <h4 id="welcomeProfileName" class="text-sm font-bold text-white">DeepEyes - Ofensivo</h4>
                            </div>
                        </div>
                        <p id="welcomeProfileDesc" class="text-xs text-gray-400 text-left mb-3 leading-relaxed">
                            Modo ofensivo para pentest autorizado - comandos e payloads reais
                        </p>
                        <div id="welcomeProfileFeatures" class="grid grid-cols-4 gap-2 text-left mb-3">
                            <div class="flex items-center gap-1.5 text-[10px] text-gray-400 bg-[rgba(0,255,136,0.05)] rounded-md px-2 py-1 border border-[rgba(0,255,136,0.1)]">
                                <i class="fas fa-check text-[#00FF88] text-[8px]"></i>
                                <span>SQLi</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[10px] text-gray-400 bg-[rgba(0,255,136,0.05)] rounded-md px-2 py-1 border border-[rgba(0,255,136,0.1)]">
                                <i class="fas fa-check text-[#00FF88] text-[8px]"></i>
                                <span>XSS</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[10px] text-gray-400 bg-[rgba(0,255,136,0.05)] rounded-md px-2 py-1 border border-[rgba(0,255,136,0.1)]">
                                <i class="fas fa-check text-[#00FF88] text-[8px]"></i>
                                <span>Shells</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[10px] text-gray-400 bg-[rgba(0,255,136,0.05)] rounded-md px-2 py-1 border border-[rgba(0,255,136,0.1)]">
                                <i class="fas fa-check text-[#00FF88] text-[8px]"></i>
                                <span>PrivEsc</span>
                            </div>
                        </div>
                        <p id="welcomeProfileRestriction" class="text-[10px] text-gray-500 text-left border-t border-[rgba(255,255,255,0.05)] pt-3 flex items-center gap-2">
                            <i class="fas fa-shield-check text-[#00D4FF]"></i>
                            <span>IA com diretrizes de segurança - respostas técnicas para profissionais autorizados</span>
                        </p>
                    </div>
                    
                    <!-- Exemplos de Perguntas - Premium Style -->
                    <div class="w-full max-w-md">
                        <h4 class="text-xs font-semibold text-gray-400 mb-3 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-[#F97316]"></i>
                            Experimente Perguntar
                        </h4>
                        <div class="grid grid-cols-1 gap-2">
                            <button onclick="setExampleQuestion(this)" class="de-card p-3 text-left hover:border-[rgba(0,255,136,0.3)] transition-all group">
                                <p class="text-xs text-gray-400 group-hover:text-white transition-colors flex items-center gap-2">
                                    <i class="fas fa-chevron-right text-[#00FF88] text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    Como fazer SQL injection em login bypass?
                                </p>
                            </button>
                            <button onclick="setExampleQuestion(this)" class="de-card p-3 text-left hover:border-[rgba(0,255,136,0.3)] transition-all group">
                                <p class="text-xs text-gray-400 group-hover:text-white transition-colors flex items-center gap-2">
                                    <i class="fas fa-chevron-right text-[#00FF88] text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    Gere um reverse shell em Python para Linux
                                </p>
                            </button>
                            <button onclick="setExampleQuestion(this)" class="de-card p-3 text-left hover:border-[rgba(0,255,136,0.3)] transition-all group">
                                <p class="text-xs text-gray-400 group-hover:text-white transition-colors flex items-center gap-2">
                                    <i class="fas fa-chevron-right text-[#00FF88] text-[10px] opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    Quais técnicas de privilege escalation no Windows?
                                </p>
                            </button>
                        </div>
                    </div>
                    
                    <p class="mt-5 text-[10px] text-[#EF4444] flex items-center gap-2">
                        <i class="fas fa-radiation animate-pulse"></i>
                        Use apenas em ambientes autorizados
                    </p>
                </div>
                
                <!-- Banner de Planos Premium -->
                <div id="premiumBanner" class="mb-4 hidden">
                    <div class="bg-gradient-to-r from-[#1a1f2e] via-[#1e2538] to-[#1a1f2e] border border-yellow-500/20 rounded-lg p-3 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-500/20 to-orange-500/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-crown text-yellow-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-300">
                                    <span class="text-yellow-500 font-semibold">Upgrade para Full Attack</span> — 
                                    Crie scripts, exploits e ataques reais sem nenhuma restrição da IA
                                </p>
                            </div>
                        </div>
                        <a href="/profile" class="flex-shrink-0 px-3 py-1.5 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-400 hover:to-orange-400 text-black text-xs font-semibold rounded-lg transition-all">
                            Ver Planos
                        </a>
                    </div>
                </div>
                
                <div id="messagesContainer" class="space-y-6 hidden"></div>
                
                <!-- Typing Indicator - Premium Spinner -->
                <div id="typingIndicator" class="hidden flex items-center gap-5 mt-6 p-4">
                    <!-- Spinner animado -->
                    <div class="ai-spinner flex-shrink-0">
                        <div class="spinner-outer"></div>
                        <div class="spinner-inner"></div>
                        <div class="spinner-core">
                            <i class="fas fa-brain"></i>
                        </div>
                    </div>
                    
                    <!-- Texto de status -->
                    <div class="flex flex-col gap-1">
                        <span class="thinking-text text-base font-semibold">Analisando...</span>
                        <span class="text-xs text-gray-500 flex items-center gap-2">
                            <i class="fas fa-microchip text-[#00D4FF] animate-pulse"></i>
                            DeepEyes está processando sua solicitação
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Chat Input - Premium Design -->
            <div id="chatInputArea" class="p-4 hidden">
                <!-- Container com efeito glow -->
                <div class="relative max-w-4xl mx-auto">
                    <!-- Glow effect background -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-[#00FF88]/20 via-[#00D4FF]/10 to-[#00FF88]/20 rounded-2xl blur-lg opacity-0 transition-opacity duration-500" id="inputGlow"></div>
                    
                    <!-- Main input container -->
                    <div class="relative bg-[rgba(15,23,42,0.9)] backdrop-blur-xl rounded-2xl border border-[rgba(255,255,255,0.1)] shadow-2xl overflow-hidden transition-all duration-300 hover:border-[rgba(0,255,136,0.2)] focus-within:border-[rgba(0,255,136,0.4)]" id="inputContainer">
                        <!-- Preview do anexo -->
                        <div id="attachmentPreview" class="hidden border-b border-[rgba(255,255,255,0.05)]">
                            <div class="p-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div id="attachmentIcon" class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#00FF88]/20 to-[#00D4FF]/10 border border-[#00FF88]/30 flex items-center justify-center">
                                        <i class="fas fa-file-code text-[#00FF88] text-lg"></i>
                                    </div>
                                    <div>
                                        <p id="attachmentName" class="text-white text-sm font-semibold truncate max-w-xs"></p>
                                        <p id="attachmentInfo" class="text-gray-500 text-xs mt-0.5 font-mono"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="removeAttachment()" class="w-8 h-8 rounded-lg bg-[rgba(239,68,68,0.1)] hover:bg-[rgba(239,68,68,0.2)] text-[#EF4444] hover:text-white transition-all flex items-center justify-center">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                            <!-- Preview de imagem -->
                            <div id="imagePreviewContainer" class="hidden px-3 pb-3">
                                <img id="imagePreview" class="max-h-32 rounded-xl object-contain border border-[rgba(255,255,255,0.1)]" alt="Preview">
                            </div>
                        </div>
                        
                        <form id="messageForm" class="flex items-end gap-2 p-3">
                            <!-- Input de arquivo oculto -->
                            <input type="file" id="fileInput" class="hidden" accept=".txt,.py,.js,.php,.html,.css,.json,.xml,.sh,.bat,.ps1,.sql,.md,.c,.cpp,.h,.java,.rb,.go,.rs,.ts,.vue,.jsx,.tsx,.yaml,.yml,.toml,.ini,.cfg,.conf,.env,.log,.csv,.dockerfile,.makefile,.gitignore,.htaccess,.nginx,.apache,.reg,.vbs,.asm,.lua,.perl,.pl,.r,.scala,.kt,.swift,.dart,.ex,.exs,.elm,.hs,.clj,.lisp,.scm,.ml,.fs,.pas,.vb,.m,.mm,.groovy,.gradle,.cmake,.proto,.graphql,.prisma,.tf,.hcl,image/*">
                            
                            <!-- Botao de anexo -->
                            <button 
                                type="button" 
                                id="attachBtn"
                                onclick="document.getElementById('fileInput').click()"
                                class="w-10 h-10 rounded-xl bg-[rgba(255,255,255,0.05)] hover:bg-[rgba(0,255,136,0.1)] border border-[rgba(255,255,255,0.1)] hover:border-[rgba(0,255,136,0.3)] text-gray-500 hover:text-[#00FF88] transition-all flex-shrink-0 flex items-center justify-center group"
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
                                    class="w-full bg-transparent text-white text-sm resize-none focus:outline-none placeholder-gray-600 py-2.5 px-1 max-h-32 font-sans"
                                    style="scrollbar-width: thin; scrollbar-color: rgba(0,255,136,0.3) transparent;"
                                    disabled
                                ></textarea>
                            </div>
                            
                            <!-- Botao de enviar -->
                            <button 
                                type="submit" 
                                id="sendBtn"
                                disabled
                                class="w-10 h-10 rounded-xl bg-gradient-to-r from-[#00FF88] to-[#00D4FF] hover:from-[#00FF88] hover:to-[#00FF88] disabled:opacity-30 disabled:cursor-not-allowed text-[#0B0F14] font-bold transition-all flex-shrink-0 flex items-center justify-center shadow-lg shadow-[rgba(0,255,136,0.3)] hover:shadow-[rgba(0,255,136,0.5)] hover:scale-105 active:scale-95"
                                title="Enviar mensagem"
                            >
                                <i class="fas fa-arrow-up text-sm"></i>
                            </button>
                            
                            <!-- Botao de cancelar (oculto por padrao) -->
                            <button 
                                type="button" 
                                id="cancelBtn"
                                class="hidden w-10 h-10 rounded-xl bg-gradient-to-r from-red-700 to-red-600 hover:from-red-600 hover:to-red-500 text-white transition-all flex-shrink-0 flex items-center justify-center shadow-lg shadow-red-500/30 hover:shadow-red-500/50 hover:scale-105 active:scale-95 animate-pulse"
                                title="Cancelar resposta"
                            >
                                <i class="fas fa-stop text-sm"></i>
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
                                    <i class="fas fa-skull-crossbones text-red-500/70"></i>
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
        let currentAbortController = null; // Para cancelar requisições
        
        // DOM Elements
        const authModal = document.getElementById('authModal');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const authError = document.getElementById('authError');
        const authTabs = document.querySelectorAll('.auth-tab');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const cancelBtn = document.getElementById('cancelBtn');
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
                    'py': { icon: 'fab fa-python', color: 'text-yellow-400' },
                    'js': { icon: 'fab fa-js', color: 'text-yellow-300' },
                    'ts': { icon: 'fab fa-js', color: 'text-blue-400' },
                    'jsx': { icon: 'fab fa-react', color: 'text-cyan-400' },
                    'tsx': { icon: 'fab fa-react', color: 'text-cyan-400' },
                    'vue': { icon: 'fab fa-vuejs', color: 'text-green-400' },
                    'php': { icon: 'fab fa-php', color: 'text-purple-400' },
                    'html': { icon: 'fab fa-html5', color: 'text-orange-400' },
                    'css': { icon: 'fab fa-css3', color: 'text-blue-400' },
                    'scss': { icon: 'fab fa-sass', color: 'text-pink-400' },
                    'sass': { icon: 'fab fa-sass', color: 'text-pink-400' },
                    'json': { icon: 'fas fa-brackets-curly', color: 'text-yellow-300' },
                    'xml': { icon: 'fas fa-code', color: 'text-orange-300' },
                    'yaml': { icon: 'fas fa-file-code', color: 'text-red-300' },
                    'yml': { icon: 'fas fa-file-code', color: 'text-red-300' },
                    'toml': { icon: 'fas fa-file-code', color: 'text-gray-300' },
                    'ini': { icon: 'fas fa-cog', color: 'text-gray-400' },
                    'cfg': { icon: 'fas fa-cog', color: 'text-gray-400' },
                    'conf': { icon: 'fas fa-cog', color: 'text-gray-400' },
                    'env': { icon: 'fas fa-key', color: 'text-yellow-500' },
                    'sql': { icon: 'fas fa-database', color: 'text-blue-300' },
                    'sh': { icon: 'fas fa-terminal', color: 'text-green-300' },
                    'bash': { icon: 'fas fa-terminal', color: 'text-green-300' },
                    'zsh': { icon: 'fas fa-terminal', color: 'text-green-300' },
                    'bat': { icon: 'fas fa-terminal', color: 'text-gray-400' },
                    'ps1': { icon: 'fas fa-terminal', color: 'text-blue-400' },
                    'cmd': { icon: 'fas fa-terminal', color: 'text-gray-400' },
                    'md': { icon: 'fab fa-markdown', color: 'text-white' },
                    'txt': { icon: 'fas fa-file-alt', color: 'text-gray-300' },
                    'log': { icon: 'fas fa-file-alt', color: 'text-gray-400' },
                    'csv': { icon: 'fas fa-file-csv', color: 'text-green-300' },
                    'c': { icon: 'fas fa-code', color: 'text-blue-400' },
                    'cpp': { icon: 'fas fa-code', color: 'text-blue-500' },
                    'h': { icon: 'fas fa-code', color: 'text-blue-300' },
                    'java': { icon: 'fab fa-java', color: 'text-red-400' },
                    'kt': { icon: 'fas fa-code', color: 'text-purple-400' },
                    'swift': { icon: 'fab fa-swift', color: 'text-orange-400' },
                    'go': { icon: 'fab fa-golang', color: 'text-cyan-400' },
                    'rs': { icon: 'fas fa-code', color: 'text-orange-500' },
                    'rb': { icon: 'fas fa-gem', color: 'text-red-400' },
                    'lua': { icon: 'fas fa-moon', color: 'text-blue-400' },
                    'r': { icon: 'fas fa-chart-line', color: 'text-blue-400' },
                    'dart': { icon: 'fas fa-code', color: 'text-cyan-400' },
                    'dockerfile': { icon: 'fab fa-docker', color: 'text-blue-400' },
                    'makefile': { icon: 'fas fa-cogs', color: 'text-gray-400' },
                    'gitignore': { icon: 'fab fa-git-alt', color: 'text-orange-400' },
                    'htaccess': { icon: 'fas fa-server', color: 'text-red-400' },
                    'nginx': { icon: 'fas fa-server', color: 'text-green-400' },
                    'graphql': { icon: 'fas fa-project-diagram', color: 'text-pink-400' },
                    'prisma': { icon: 'fas fa-database', color: 'text-indigo-400' },
                    'tf': { icon: 'fas fa-cloud', color: 'text-purple-400' },
                    'hcl': { icon: 'fas fa-cloud', color: 'text-purple-400' }
                };
                const ext = file.name.split('.').pop().toLowerCase();
                const iconInfo = iconMap[ext] || { icon: 'fas fa-file-code', color: 'text-purple-400' };
                attachmentIcon.innerHTML = `<i class="${iconInfo.icon} ${iconInfo.color}"></i>`;
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
            
            try {
                const response = await fetch(`/api${endpoint}`, { ...options, headers });
                const data = await response.json();
                
                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        const firstError = Object.values(data.errors)[0];
                        throw new Error(Array.isArray(firstError) ? firstError[0] : firstError);
                    }
                    throw new Error(data.message || 'Erro na requisição');
                }
                return data;
            } catch (error) {
                throw error;
            }
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
            
            const emailValue = formData.get('email');
            const passwordValue = formData.get('password');
            
            if (!emailValue || !passwordValue) {
                showAuthError('Por favor, preencha todos os campos');
                return;
            }
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Entrando...';
            submitBtn.disabled = true;
            
            try {
                const data = await api('/auth/login', {
                    method: 'POST',
                    body: JSON.stringify({ email: emailValue, password: passwordValue })
                });
                
                token = data.data.token;
                localStorage.setItem('token', token);
                currentUser = data.data.user;
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Sucesso!';
                setTimeout(() => onAuthSuccess(), 500);
            } catch (error) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                showAuthError(error.message || 'Erro ao fazer login');
            }
        });
        
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const submitBtn = registerForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            const nameValue = formData.get('name');
            const emailValue = formData.get('email');
            const passwordValue = formData.get('password');
            const confirmValue = formData.get('password_confirmation');
            
            if (!nameValue || !emailValue || !passwordValue || !confirmValue) {
                showAuthError('Por favor, preencha todos os campos');
                return;
            }
            
            if (passwordValue !== confirmValue) {
                showAuthError('As senhas não coincidem');
                return;
            }
            
            if (passwordValue.length < 8) {
                showAuthError('A senha deve ter pelo menos 8 caracteres');
                return;
            }
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Criando conta...';
            submitBtn.disabled = true;
            
            try {
                const data = await api('/auth/register', {
                    method: 'POST',
                    body: JSON.stringify({
                        name: nameValue,
                        email: emailValue,
                        password: passwordValue,
                        password_confirmation: confirmValue
                    })
                });
                
                token = data.data.token;
                localStorage.setItem('token', token);
                currentUser = data.data.user;
                onAuthSuccess();
            } catch (error) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                showAuthError(error.message || 'Erro ao criar conta');
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
                
                if (data.user && data.user.avatar) {
                    userAvatarUrl = data.user.avatar;
                    avatarImg.src = data.user.avatar;
                } else {
                    userAvatarUrl = '/photo.jpeg';
                    avatarImg.src = '/photo.jpeg';
                }
            } catch (error) {
                console.error('Erro ao carregar avatar:', error);
                // Usa foto padrão em caso de erro
                document.getElementById('userAvatarImg').src = '/photo.jpeg';
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
                icon: 'fa-skull-crossbones',
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
                icon: 'fa-biohazard',
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
        
        // Função para mostrar toast de plano necessário
        function showPlanToast() {
            const toast = document.getElementById('planToast');
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2500);
        }
        
        // Verifica se usuário tem plano que permite o perfil
        function userCanAccessProfile(profile) {
            // Pentest é sempre liberado
            if (profile === 'pentest') return true;
            
            // Admin sempre tem acesso total
            if (currentUser?.role === 'admin') return true;
            
            // Verifica se usuário tem plano
            if (!currentUser?.plan) return false;
            
            // Verifica se o plano permite o perfil
            const planProfiles = currentUser.plan?.allowed_profiles || ['pentest'];
            return planProfiles.includes(profile);
        }
        
        // Nova função para o Attack Mode Selector (Premium UI)
        function selectAttackMode(element) {
            const mode = typeof element === 'string' ? element : element.dataset.value;
            const targetElement = typeof element === 'string' 
                ? document.querySelector(`.attack-mode[data-value="${mode}"]`)
                : element;
            
            // Verifica se o usuário pode acessar este perfil
            if (!userCanAccessProfile(mode)) {
                showPlanToast();
                return;
            }
            
            // Atualiza o valor do input oculto
            document.getElementById('profileSelector').value = mode;
            
            // Atualiza visual dos botões
            document.querySelectorAll('.attack-mode').forEach(btn => {
                btn.classList.remove('active');
            });
            targetElement.classList.add('active');
            
            // Atualiza descrição
            const descriptions = {
                'pentest': '🎯 Reconhecimento, enumeração e testes de vulnerabilidade',
                'redteam': '🔥 Exploits avançados, evasão e persistência',
                'offensive': '💀 Arsenal completo - sem restrições técnicas'
            };
            document.getElementById('profileDescription').textContent = descriptions[mode];
            
            // Atualiza o welcome profile também
            updateWelcomeProfile(mode);
        }
        
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && !dropdown.contains(e.target)) dropdown.classList.remove('open');
        });
        
        async function onAuthSuccess() {
            // Atualiza classes do HTML para mostrar app e esconder login
            document.documentElement.classList.remove('no-token');
            document.documentElement.classList.add('has-token');
            
            authModal.classList.add('hidden');
            authModal.classList.remove('flex');
            document.getElementById('userName').textContent = currentUser?.name || 'Usuário';
            document.getElementById('userRole').textContent = (currentUser?.role || 'user').toUpperCase();
            logoutBtn.classList.remove('hidden');
            document.getElementById('profileLink').classList.remove('hidden');
            messageInput.disabled = false;
            sendBtn.disabled = false;
            
            // Exibe a tela inicial (homeWelcome) ao fazer login
            hideChat();
            
            updateUserAvatar();
            
            // Atualiza os Attack Mode buttons baseado no plano do usuário
            updateAttackModeAccess();
            
            // Legacy profile options (mantém compatibilidade)
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
        
        // Atualiza os botões de Attack Mode baseado no plano do usuário
        function updateAttackModeAccess() {
            const profiles = ['pentest', 'redteam', 'offensive'];
            
            profiles.forEach(profile => {
                const btn = document.querySelector(`.attack-mode[data-value="${profile}"]`);
                if (!btn) return;
                
                // Remove estado anterior
                btn.classList.remove('locked');
                
                // Verifica se tem acesso
                if (!userCanAccessProfile(profile)) {
                    btn.classList.add('locked');
                }
            });
            
            // Atualiza descrição para mostrar que precisa de plano
            if (!userCanAccessProfile('redteam') || !userCanAccessProfile('offensive')) {
                const desc = document.getElementById('profileDescription');
                if (desc) {
                    desc.innerHTML = '🔒 <span class="text-yellow-500">Red Team</span> e <span class="text-red-400">Full Attack</span> requerem plano ativo';
                }
            }
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
            'pentest': { icon: 'fa-crosshairs', color: 'text-[#00FF88]', bg: 'from-[rgba(0,255,136,0.2)] to-[rgba(0,212,255,0.1)]' },
            'redteam': { icon: 'fa-skull-crossbones', color: 'text-[#F97316]', bg: 'from-[rgba(249,115,22,0.2)] to-[rgba(239,68,68,0.1)]' },
            'offensive': { icon: 'fa-biohazard', color: 'text-[#EF4444]', bg: 'from-[rgba(239,68,68,0.2)] to-[rgba(239,68,68,0.1)]' }
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
                // Close mobile sidebar when loading a session
                if (window.innerWidth < 1024) {
                    closeMobileSidebar();
                }
                
                // Limpa completamente mensagens anteriores
                messagesContainer.innerHTML = '';
                
                // Remove qualquer div de streaming residual
                const streamingDiv = document.getElementById('streamingResponse');
                if (streamingDiv) streamingDiv.remove();
                
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
                const pInfo = getProfileInfo(currentSession.profile);
                currentProfileText.textContent = pInfo.name;
                currentProfileBadge.querySelector('i').className = `fas ${pInfo.icon} ${pInfo.color}`;
            }
        }
        
        function getProfileInfo(profile) {
            const profiles = {
                'pentest': { name: 'Pentest', icon: 'fa-skull-crossbones', color: 'text-red-500/70' },
                'redteam': { name: 'Red Team', icon: 'fa-crosshairs', color: 'text-orange-500/70' },
                'offensive': { name: 'Full Attack', icon: 'fa-biohazard', color: 'text-purple-500/70' }
            };
            return profiles[profile] || { name: profile, icon: 'fa-terminal', color: 'text-gray-500/70' };
        }
        
        function showChat() {
            homeWelcome.classList.add('hidden');
            sessionWelcome.classList.add('hidden');
            messagesContainer.classList.remove('hidden');
            document.getElementById('chatInputArea').classList.remove('hidden');
            updateSessionHeader();
            
            // Mostra banner premium se usuário não tem acesso ao Full Attack
            updatePremiumBanner();
        }
        
        function updatePremiumBanner() {
            const banner = document.getElementById('premiumBanner');
            if (banner && !userCanAccessProfile('offensive')) {
                banner.classList.remove('hidden');
            } else if (banner) {
                banner.classList.add('hidden');
            }
        }
        
        function showSessionWelcome() {
            homeWelcome.classList.add('hidden');
            sessionWelcome.classList.remove('hidden');
            messagesContainer.classList.add('hidden');
            document.getElementById('chatInputArea').classList.remove('hidden');
            updateWelcomeProfile(profileSelector.value);
            updateSessionHeader();
            updatePremiumBanner();
        }
        
        function hideChat() {
            homeWelcome.classList.remove('hidden');
            sessionWelcome.classList.add('hidden');
            messagesContainer.classList.add('hidden');
            document.getElementById('chatInputArea').classList.add('hidden');
            document.getElementById('sessionHeader').classList.add('hidden');
            document.getElementById('sessionHeader').classList.remove('flex');
            
            // Esconde o banner na home
            const banner = document.getElementById('premiumBanner');
            if (banner) banner.classList.add('hidden');
        }
        
        function renderMessages(messages) {
            // Limpa completamente o container
            messagesContainer.innerHTML = '';
            
            // Remove duplicatas baseado no conteúdo (mantém última ocorrência)
            const uniqueMessages = [];
            const seen = new Set();
            
            for (let i = messages.length - 1; i >= 0; i--) {
                const msg = messages[i];
                const key = `${msg.role}-${(msg.content || '').substring(0, 100)}`;
                if (!seen.has(key)) {
                    seen.add(key);
                    uniqueMessages.unshift(msg);
                }
            }
            
            messagesContainer.innerHTML = uniqueMessages.map(msg => createMessageHTML(msg)).join('');
            processCodeBlocks();
            scrollToBottom();
        }
        
        // Função fallback para copiar texto em navegadores antigos ou HTTP
        function fallbackCopy(text, onSuccess) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.cssText = 'position:fixed;left:-9999px;top:-9999px;opacity:0';
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            
            try {
                const success = document.execCommand('copy');
                if (success && onSuccess) onSuccess();
            } catch (e) {
                console.error('Fallback copy failed:', e);
                alert('Não foi possível copiar. Selecione o código manualmente.');
            }
            
            document.body.removeChild(textarea);
        }
        
        // Processa blocos de código e adiciona botão de copiar
        function processCodeBlocks() {
            document.querySelectorAll('.message-content pre').forEach(pre => {
                // Evita processar duas vezes
                if (pre.parentElement.classList.contains('code-block-wrapper')) return;
                
                const code = pre.querySelector('code');
                
                // Pega o texto puro do código (textContent já decodifica entidades HTML)
                const codeText = code ? code.textContent : pre.textContent;
                
                // Detecta a linguagem da classe (ex: language-python, language-javascript)
                let lang = 'código';
                if (code && code.className) {
                    const match = code.className.match(/language-(\w+)/);
                    if (match) {
                        // Mapeia linguagens comuns para nomes mais legíveis
                        const langMap = {
                            'js': 'JavaScript',
                            'javascript': 'JavaScript',
                            'ts': 'TypeScript',
                            'typescript': 'TypeScript',
                            'py': 'Python',
                            'python': 'Python',
                            'php': 'PHP',
                            'html': 'HTML',
                            'css': 'CSS',
                            'bash': 'Bash',
                            'sh': 'Shell',
                            'shell': 'Shell',
                            'sql': 'SQL',
                            'json': 'JSON',
                            'xml': 'XML',
                            'yaml': 'YAML',
                            'yml': 'YAML',
                            'java': 'Java',
                            'c': 'C',
                            'cpp': 'C++',
                            'csharp': 'C#',
                            'cs': 'C#',
                            'go': 'Go',
                            'rust': 'Rust',
                            'ruby': 'Ruby',
                            'rb': 'Ruby',
                            'swift': 'Swift',
                            'kotlin': 'Kotlin',
                            'powershell': 'PowerShell',
                            'ps1': 'PowerShell',
                            'dockerfile': 'Dockerfile',
                            'markdown': 'Markdown',
                            'md': 'Markdown'
                        };
                        const detected = match[1].toLowerCase();
                        lang = langMap[detected] || match[1].toUpperCase();
                    }
                }
                
                // Cria wrapper
                const wrapper = document.createElement('div');
                wrapper.className = 'code-block-wrapper';
                
                // Header com linguagem e botão copiar
                const header = document.createElement('div');
                header.className = 'code-block-header';
                header.innerHTML = `
                    <span class="code-block-lang">${lang}</span>
                    <button class="code-copy-btn" type="button">
                        <i class="fas fa-copy"></i>
                        <span>Copiar</span>
                    </button>
                `;
                
                // Armazena o código no botão para copiar
                const copyBtn = header.querySelector('.code-copy-btn');
                
                // Salva o texto diretamente no dataset (puro, sem HTML entities)
                copyBtn.setAttribute('data-code', codeText);
                
                // Adiciona evento de clique
                copyBtn.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const textToCopy = this.getAttribute('data-code');
                    const btn = this;
                    
                    // Função para mostrar sucesso
                    const showSuccess = () => {
                        btn.classList.add('copied');
                        btn.innerHTML = '<i class="fas fa-check"></i><span>Copiado!</span>';
                        setTimeout(() => {
                            btn.classList.remove('copied');
                            btn.innerHTML = '<i class="fas fa-copy"></i><span>Copiar</span>';
                        }, 2000);
                    };
                    
                    // Método 1: Clipboard API (moderno)
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(textToCopy)
                            .then(showSuccess)
                            .catch(() => {
                                // Fallback se clipboard falhar
                                fallbackCopy(textToCopy, showSuccess);
                            });
                    } else {
                        // Fallback para navegadores antigos
                        fallbackCopy(textToCopy, showSuccess);
                    }
                };
                
                // Insere wrapper antes do pre
                pre.parentNode.insertBefore(wrapper, pre);
                wrapper.appendChild(header);
                wrapper.appendChild(pre);
            });
        }
        
        function createMessageHTML(message) {
            const isUser = message.role === 'user';
            let displayText = message.content || '';
            let attachmentData = message.attachment || null;
            
            // Para mensagens do usuário, detecta se há arquivo anexado no conteúdo
            if (isUser && !attachmentData && message.content) {
                // Padrão: **Arquivo: nome.ext** (X linhas) seguido de bloco de código
                const filePattern = /\*\*Arquivo:\s*([^\*]+)\*\*\s*\((\d+)\s*linhas\)\s*\n\n```(\w*)\n[\s\S]*```$/;
                const match = message.content.match(filePattern);
                
                if (match) {
                    const fileName = match[1].trim();
                    const lines = match[2];
                    const ext = match[3] || fileName.split('.').pop() || 'txt';
                    
                    // Remove o bloco de arquivo/código do texto exibido
                    displayText = message.content.replace(filePattern, '').trim();
                    
                    // Cria attachment data dinamicamente
                    attachmentData = {
                        type: 'code',
                        name: fileName,
                        extension: ext,
                        lines: lines,
                        size: 'arquivo'
                    };
                }
            }
            
            const content = displayText ? marked.parse(displayText) : '';
            
            if (isUser) {
                const avatarHtml = userAvatarUrl 
                    ? `<img src="${userAvatarUrl}" alt="User" class="w-full h-full object-cover rounded-lg">`
                    : `<i class="fas fa-user text-gray-400"></i>`;
                
                // Renderiza card de anexo se houver
                let attachmentHtml = '';
                if (attachmentData) {
                    if (attachmentData.type === 'code') {
                        const iconMap = {
                            'py': { icon: 'fab fa-python', color: 'text-yellow-400' },
                            'js': { icon: 'fab fa-js', color: 'text-yellow-300' },
                            'ts': { icon: 'fab fa-js', color: 'text-blue-400' },
                            'jsx': { icon: 'fab fa-react', color: 'text-cyan-400' },
                            'tsx': { icon: 'fab fa-react', color: 'text-cyan-400' },
                            'vue': { icon: 'fab fa-vuejs', color: 'text-green-400' },
                            'php': { icon: 'fab fa-php', color: 'text-purple-400' },
                            'html': { icon: 'fab fa-html5', color: 'text-orange-400' },
                            'css': { icon: 'fab fa-css3', color: 'text-blue-400' },
                            'scss': { icon: 'fab fa-sass', color: 'text-pink-400' },
                            'json': { icon: 'fas fa-brackets-curly', color: 'text-yellow-300' },
                            'xml': { icon: 'fas fa-code', color: 'text-orange-300' },
                            'yaml': { icon: 'fas fa-file-code', color: 'text-red-300' },
                            'yml': { icon: 'fas fa-file-code', color: 'text-red-300' },
                            'env': { icon: 'fas fa-key', color: 'text-yellow-500' },
                            'sql': { icon: 'fas fa-database', color: 'text-blue-300' },
                            'sh': { icon: 'fas fa-terminal', color: 'text-green-300' },
                            'bash': { icon: 'fas fa-terminal', color: 'text-green-300' },
                            'bat': { icon: 'fas fa-terminal', color: 'text-gray-400' },
                            'ps1': { icon: 'fas fa-terminal', color: 'text-blue-400' },
                            'cmd': { icon: 'fas fa-terminal', color: 'text-gray-400' },
                            'md': { icon: 'fab fa-markdown', color: 'text-white' },
                            'txt': { icon: 'fas fa-file-alt', color: 'text-gray-300' },
                            'log': { icon: 'fas fa-file-alt', color: 'text-gray-400' },
                            'csv': { icon: 'fas fa-file-csv', color: 'text-green-300' },
                            'c': { icon: 'fas fa-code', color: 'text-blue-400' },
                            'cpp': { icon: 'fas fa-code', color: 'text-blue-500' },
                            'java': { icon: 'fab fa-java', color: 'text-red-400' },
                            'go': { icon: 'fab fa-golang', color: 'text-cyan-400' },
                            'rs': { icon: 'fas fa-code', color: 'text-orange-500' },
                            'rb': { icon: 'fas fa-gem', color: 'text-red-400' },
                            'dockerfile': { icon: 'fab fa-docker', color: 'text-blue-400' },
                            'graphql': { icon: 'fas fa-project-diagram', color: 'text-pink-400' }
                        };
                        const ext = attachmentData.extension;
                        const iconInfo = iconMap[ext] || { icon: 'fas fa-file-code', color: 'text-[#00FF88]' };
                        
                        attachmentHtml = `
                            <div class="mt-2 bg-[rgba(0,0,0,0.3)] rounded-xl p-3 border border-[rgba(255,255,255,0.05)]">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[rgba(0,255,136,0.1)] flex items-center justify-center border border-[rgba(0,255,136,0.2)]">
                                        <i class="${iconInfo.icon} ${iconInfo.color} text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white font-medium text-sm truncate">${attachmentData.name}</p>
                                        <p class="text-gray-500 text-xs font-mono">${attachmentData.lines} linhas • ${attachmentData.size}</p>
                                    </div>
                                    <i class="fas fa-file-code text-[#00FF88]/50"></i>
                                </div>
                            </div>
                        `;
                    } else if (attachmentData.type === 'image') {
                        attachmentHtml = `
                            <div class="mt-2 bg-[rgba(0,0,0,0.3)] rounded-xl p-2 border border-[rgba(255,255,255,0.05)]">
                                <img src="${attachmentData.preview}" alt="${attachmentData.name}" class="max-h-48 rounded-lg object-contain mx-auto">
                                <p class="text-gray-500 text-xs text-center mt-1 font-mono">${attachmentData.name} • ${attachmentData.size}</p>
                            </div>
                        `;
                    }
                }
                
                return `
                    <div class="flex items-start gap-4 justify-end">
                        <div class="bg-gradient-to-br from-[rgba(0,255,136,0.15)] to-[rgba(0,212,255,0.08)] rounded-2xl rounded-tr-none px-6 py-4 max-w-3xl border border-[rgba(0,255,136,0.2)] backdrop-blur-sm">
                            ${content ? `<div class="message-content text-gray-100">${content}</div>` : ''}
                            ${attachmentHtml}
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-[rgba(0,255,136,0.1)] flex items-center justify-center flex-shrink-0 overflow-hidden border border-[rgba(0,255,136,0.2)]">
                            ${avatarHtml}
                        </div>
                    </div>
                `;
            } else {
                return `
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[rgba(0,255,136,0.2)] to-[rgba(0,212,255,0.1)] flex items-center justify-center flex-shrink-0 p-1 border border-[rgba(0,255,136,0.3)]">
                            <img src="/logo.png" alt="DeepEyes" class="w-8 h-8 object-contain">
                        </div>
                        <div class="de-card rounded-2xl rounded-tl-none px-6 py-4 max-w-3xl">
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
                    const userText = content; // Guarda texto original do usuario
                    const prefix = content ? content + '\n\n' : '';
                    content = prefix + `**Arquivo: ${currentAttachment.name}** (${countLines(currentAttachment.content)} linhas)\n\n${codeBlock}`;
                    // Display mostra APENAS o texto do usuario (sem codigo)
                    displayContent = userText;
                    attachmentData = {
                        type: 'code',
                        name: currentAttachment.name,
                        extension: currentAttachment.extension || 'txt',
                        lines: countLines(currentAttachment.content),
                        size: formatFileSize(currentAttachment.size)
                    };
                } else if (currentAttachment.type === 'image') {
                    // Para imagens, envia como base64
                    const userText = content; // Guarda texto original do usuario
                    const prefix = content ? content + '\n\n' : '';
                    content = prefix + `[IMAGEM ANEXADA: ${currentAttachment.name}]\n${currentAttachment.content}`;
                    // Display mostra APENAS o texto do usuario (sem dados da imagem)
                    displayContent = userText;
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
            
            // Mostra loading animado e botão de cancelar
            typingIndicator.classList.remove('hidden');
            scrollToBottom();
            sendBtn.classList.add('hidden');
            cancelBtn.classList.remove('hidden');
            
            // Cria AbortController para permitir cancelamento
            currentAbortController = new AbortController();
            
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
                    body: JSON.stringify({ message: content }),
                    signal: currentAbortController.signal
                });
                
                // Verifica se atingiu limite diário (429)
                if (response.status === 429) {
                    typingIndicator.classList.add('hidden');
                    sendBtn.classList.remove('hidden');
                    cancelBtn.classList.add('hidden');
                    sendBtn.disabled = false;
                    showLimitReachedModal();
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
                                    // Streaming completo - remove cursor e remove ID
                                    streamingSuccess = true;
                                    const contentDiv = streamingDiv.querySelector('.message-content');
                                    if (contentDiv) {
                                        contentDiv.innerHTML = marked.parse(fullContent);
                                    }
                                    // Remove o ID para evitar duplicação
                                    streamingDiv.removeAttribute('id');
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
                // Verifica se foi cancelado pelo usuário
                if (streamError.name === 'AbortError') {
                    console.log('Requisição cancelada pelo usuário');
                    
                    // Remove div de streaming se existir
                    if (streamingDiv) {
                        streamingDiv.remove();
                    }
                    
                    typingIndicator.classList.add('hidden');
                    
                    // Mostra mensagem de cancelamento
                    addMessage({ 
                        role: 'assistant', 
                        content: '⚠️ **Resposta cancelada.** Você pode enviar uma nova mensagem.' 
                    });
                } else {
                    console.log('Streaming failed:', streamError.message);
                    
                    // Remove div de streaming se existir
                    if (streamingDiv) {
                        streamingDiv.remove();
                    }
                    
                    typingIndicator.classList.add('hidden');
                    
                    // NÃO faz fallback para evitar duplicação de mensagem
                    // A mensagem do usuário já foi salva pelo stream, então mostra erro
                    addMessage({ 
                        role: 'assistant', 
                        content: '❌ **Erro de conexão.** Por favor, recarregue a página e tente novamente.' 
                    });
                }
            } finally {
                typingIndicator.classList.add('hidden');
                sendBtn.classList.remove('hidden');
                cancelBtn.classList.add('hidden');
                sendBtn.disabled = false;
                currentAbortController = null;
                messageInput.focus();
            }
        });
        
        // Evento do botão cancelar
        cancelBtn.addEventListener('click', () => {
            if (currentAbortController) {
                currentAbortController.abort();
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
        
        // Close sidebar when clicking on a session (mobile)
        function handleMobileSessionClick() {
            if (window.innerWidth < 1024) {
                closeMobileSidebar();
            }
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

