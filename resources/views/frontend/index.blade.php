<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>J.A.R.V.I.S. — Personal AI Assistant</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Rajdhani:wght@300;400;500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ============================================
           JARVIS - Tony Stark Style AI Interface
           ============================================ */

        :root {
            --jarvis-blue: #00d4ff;
            --jarvis-dark-blue: #0088cc;
            --jarvis-cyan: #00fff2;
            --jarvis-glow: rgba(0, 212, 255, 0.5);
            --jarvis-bg: #0a0e17;
            --jarvis-card: rgba(10, 20, 40, 0.85);
            --jarvis-border: rgba(0, 212, 255, 0.2);
            --jarvis-text: #c8d6e5;
            --jarvis-text-bright: #ffffff;
            --jarvis-success: #00ff88;
            --jarvis-warning: #ffaa00;
            --jarvis-danger: #ff3366;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: var(--jarvis-bg);
            color: var(--jarvis-text);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background Grid */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            animation: gridPulse 4s ease-in-out infinite;
        }

        @keyframes gridPulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }

        /* Floating Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--jarvis-blue);
            border-radius: 50%;
            animation: float 15s infinite linear;
            box-shadow: 0 0 6px var(--jarvis-blue);
        }

        @keyframes float {
            0% { transform: translateY(100vh) translateX(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) translateX(100px); opacity: 0; }
        }

        /* Main Container */
        .jarvis-container {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
        }

        /* Header */
        .jarvis-header {
            text-align: center;
            padding: 30px 0;
            position: relative;
        }

        .jarvis-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .jarvis-logo .ring {
            position: absolute;
            border: 2px solid var(--jarvis-blue);
            border-radius: 50%;
            animation: spin 10s linear infinite;
        }

        .jarvis-logo .ring:nth-child(1) {
            width: 120px;
            height: 120px;
            border-color: var(--jarvis-blue);
        }

        .jarvis-logo .ring:nth-child(2) {
            width: 100px;
            height: 100px;
            border-color: var(--jarvis-cyan);
            animation-direction: reverse;
            animation-duration: 8s;
        }

        .jarvis-logo .ring:nth-child(3) {
            width: 80px;
            height: 80px;
            border-style: dashed;
            animation-duration: 15s;
        }

        .jarvis-logo .core {
            width: 40px;
            height: 40px;
            background: radial-gradient(circle, var(--jarvis-blue) 0%, transparent 70%);
            border-radius: 50%;
            animation: corePulse 2s ease-in-out infinite;
            box-shadow: 0 0 30px var(--jarvis-glow), 0 0 60px var(--jarvis-glow);
        }

        @keyframes spin { 100% { transform: rotate(360deg); } }
        @keyframes corePulse {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 1; }
        }

        .jarvis-title {
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 900;
            letter-spacing: 12px;
            color: var(--jarvis-text-bright);
            text-shadow: 0 0 20px var(--jarvis-glow), 0 0 40px var(--jarvis-glow);
            margin-bottom: 8px;
        }

        .jarvis-subtitle {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.9rem;
            color: var(--jarvis-blue);
            letter-spacing: 4px;
            opacity: 0.8;
        }

        .jarvis-status {
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.8rem;
            color: var(--jarvis-success);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: var(--jarvis-success);
            border-radius: 50%;
            animation: statusBlink 1.5s ease-in-out infinite;
            box-shadow: 0 0 10px var(--jarvis-success);
        }

        @keyframes statusBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Main Grid */
        .jarvis-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        @media (max-width: 900px) {
            .jarvis-grid { grid-template-columns: 1fr; }
            .jarvis-title { font-size: 2rem; letter-spacing: 6px; }
        }

        /* Cards */
        .jarvis-card {
            background: var(--jarvis-card);
            border: 1px solid var(--jarvis-border);
            border-radius: 16px;
            padding: 24px;
            backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .jarvis-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--jarvis-blue), transparent);
            animation: scanline 3s linear infinite;
        }

        @keyframes scanline {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .jarvis-card:hover {
            border-color: var(--jarvis-blue);
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.1);
        }

        .card-title {
            font-family: 'Orbitron', monospace;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--jarvis-blue);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            font-size: 1rem;
        }

        /* Chat Section */
        .chat-card {
            grid-column: 1 / 2;
            grid-row: 1 / 4;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 900px) {
            .chat-card { grid-column: 1; grid-row: auto; min-height: 500px; }
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 500px;
            min-height: 300px;
        }

        .chat-messages::-webkit-scrollbar {
            width: 4px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: rgba(0, 212, 255, 0.05);
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: var(--jarvis-blue);
            border-radius: 4px;
        }

        .chat-msg {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.95rem;
            line-height: 1.5;
            max-width: 85%;
            animation: msgSlide 0.3s ease;
            word-wrap: break-word;
        }

        @keyframes msgSlide {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-msg.jarvis {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.2);
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .chat-msg.jarvis .msg-sender {
            color: var(--jarvis-blue);
            font-family: 'Orbitron', monospace;
            font-size: 0.7rem;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .chat-msg.user {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid rgba(0, 255, 136, 0.2);
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .chat-msg.user .msg-sender {
            color: var(--jarvis-success);
            font-family: 'Orbitron', monospace;
            font-size: 0.7rem;
            letter-spacing: 2px;
            margin-bottom: 4px;
            text-align: right;
        }

        .chat-input-area {
            display: flex;
            gap: 10px;
            margin-top: 12px;
        }

        .chat-input {
            flex: 1;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--jarvis-border);
            border-radius: 12px;
            padding: 14px 18px;
            color: var(--jarvis-text-bright);
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .chat-input:focus {
            border-color: var(--jarvis-blue);
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.2);
        }

        .chat-input::placeholder {
            color: rgba(200, 214, 229, 0.4);
        }

        .chat-btn {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: 1px solid var(--jarvis-border);
            background: rgba(0, 212, 255, 0.1);
            color: var(--jarvis-blue);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-btn:hover {
            background: var(--jarvis-blue);
            color: var(--jarvis-bg);
            box-shadow: 0 0 20px var(--jarvis-glow);
        }

        .chat-btn.voice-active {
            background: var(--jarvis-danger);
            border-color: var(--jarvis-danger);
            color: white;
            animation: voicePulse 1s ease-in-out infinite;
        }

        @keyframes voicePulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 51, 102, 0.5); }
            50% { box-shadow: 0 0 30px rgba(255, 51, 102, 0.8); }
        }

        /* Weather Card */
        .weather-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .weather-icon {
            font-size: 3rem;
            color: var(--jarvis-warning);
            text-shadow: 0 0 20px rgba(255, 170, 0, 0.5);
        }

        .weather-temp {
            font-family: 'Orbitron', monospace;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--jarvis-text-bright);
        }

        .weather-details {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.8rem;
            color: var(--jarvis-text);
            line-height: 1.8;
        }

        .weather-details span {
            color: var(--jarvis-blue);
        }

        /* System Info Card */
        .system-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .sys-item {
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 10px;
            padding: 14px;
            text-align: center;
        }

        .sys-label {
            font-family: 'Orbitron', monospace;
            font-size: 0.65rem;
            color: var(--jarvis-blue);
            letter-spacing: 2px;
            margin-bottom: 6px;
        }

        .sys-value {
            font-family: 'Orbitron', monospace;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--jarvis-text-bright);
        }

        .sys-bar {
            width: 100%;
            height: 4px;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 4px;
            margin-top: 8px;
            overflow: hidden;
        }

        .sys-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease;
        }

        .sys-bar-fill.green { background: var(--jarvis-success); }
        .sys-bar-fill.blue { background: var(--jarvis-blue); }
        .sys-bar-fill.orange { background: var(--jarvis-warning); }
        .sys-bar-fill.red { background: var(--jarvis-danger); }

        /* Search Card */
        .search-input-wrapper {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }

        .search-input {
            flex: 1;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--jarvis-border);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--jarvis-text-bright);
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--jarvis-blue);
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.2);
        }

        .search-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--jarvis-blue) 0%, var(--jarvis-dark-blue) 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-family: 'Orbitron', monospace;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px var(--jarvis-glow);
        }

        .search-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--jarvis-border);
            border-radius: 10px;
            color: var(--jarvis-text);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .search-link:hover {
            border-color: var(--jarvis-blue);
            color: var(--jarvis-blue);
            transform: translateY(-2px);
        }

        .search-link i {
            font-size: 1.1rem;
        }

        /* App Launcher */
        .app-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        @media (max-width: 500px) {
            .app-grid { grid-template-columns: repeat(3, 1fr); }
        }

        .app-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 14px 8px;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--jarvis-border);
            border-radius: 12px;
            color: var(--jarvis-text);
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .app-btn:hover {
            background: rgba(0, 212, 255, 0.15);
            border-color: var(--jarvis-blue);
            color: var(--jarvis-blue);
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.2);
        }

        .app-btn i {
            font-size: 1.4rem;
        }

        /* Time Widget */
        .time-card {
            text-align: center;
        }

        .time-display {
            font-family: 'Orbitron', monospace;
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--jarvis-text-bright);
            text-shadow: 0 0 20px var(--jarvis-glow);
            letter-spacing: 4px;
        }

        .date-display {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.85rem;
            color: var(--jarvis-blue);
            margin-top: 8px;
            letter-spacing: 2px;
        }

        /* Loading Animation */
        .typing-indicator {
            display: inline-flex;
            gap: 4px;
            padding: 8px 0;
        }

        .typing-indicator span {
            width: 6px;
            height: 6px;
            background: var(--jarvis-blue);
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-8px); opacity: 1; }
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .quick-btn {
            padding: 6px 14px;
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.15);
            border-radius: 20px;
            color: var(--jarvis-text);
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quick-btn:hover {
            background: rgba(0, 212, 255, 0.2);
            border-color: var(--jarvis-blue);
            color: var(--jarvis-blue);
        }

        /* Corner Decorations */
        .corner {
            position: fixed;
            width: 60px;
            height: 60px;
            z-index: 2;
            pointer-events: none;
        }

        .corner-tl { top: 10px; left: 10px; border-top: 2px solid var(--jarvis-blue); border-left: 2px solid var(--jarvis-blue); }
        .corner-tr { top: 10px; right: 10px; border-top: 2px solid var(--jarvis-blue); border-right: 2px solid var(--jarvis-blue); }
        .corner-bl { bottom: 10px; left: 10px; border-bottom: 2px solid var(--jarvis-blue); border-left: 2px solid var(--jarvis-blue); }
        .corner-br { bottom: 10px; right: 10px; border-bottom: 2px solid var(--jarvis-blue); border-right: 2px solid var(--jarvis-blue); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--jarvis-bg); }
        ::-webkit-scrollbar-thumb { background: var(--jarvis-dark-blue); border-radius: 3px; }
    </style>
</head>
<body>

<!-- Particles -->
<div class="particles" id="particles"></div>

<!-- Corner Decorations -->
<div class="corner corner-tl"></div>
<div class="corner corner-tr"></div>
<div class="corner corner-bl"></div>
<div class="corner corner-br"></div>

<div class="jarvis-container">

    <!-- Header -->
    <header class="jarvis-header">
        <div class="jarvis-logo">
            <div class="ring"></div>
            <div class="ring"></div>
            <div class="ring"></div>
            <div class="core"></div>
        </div>
        <h1 class="jarvis-title">J.A.R.V.I.S.</h1>
        <p class="jarvis-subtitle">Just A Rather Very Intelligent System</p>
        <div class="jarvis-status">
            <div class="status-dot"></div>
            <span id="statusText">ALL SYSTEMS OPERATIONAL</span>
        </div>
    </header>

    <!-- Time Widget -->
    <div style="text-align:center; margin-bottom:20px;">
        <div class="jarvis-card time-card" style="display:inline-block; min-width:300px;">
            <div class="time-display" id="currentTime">00:00:00</div>
            <div class="date-display" id="currentDate">Loading...</div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="jarvis-grid">

        <!-- Chat Section -->
        <div class="jarvis-card chat-card">
            <div class="card-title">
                <i class="fas fa-comments"></i>
                COMMUNICATION CHANNEL
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="chat-msg jarvis">
                    <div class="msg-sender">J.A.R.V.I.S.</div>
                    Good day, sir. I am JARVIS, your personal AI assistant. All systems are online and operational. How may I be of service?
                </div>
            </div>
            <div class="quick-actions">
                <button class="quick-btn" onclick="sendQuick('What time is it?')">🕐 Time</button>
                <button class="quick-btn" onclick="sendQuick('Tell me a joke')">😄 Joke</button>
                <button class="quick-btn" onclick="sendQuick('What can you do?')">❓ Help</button>
                <button class="quick-btn" onclick="sendQuick('How are you?')">👋 Status</button>
                <button class="quick-btn" onclick="loadWeather('Dhaka')">🌤 Weather</button>
            </div>
            <div class="chat-input-area">
                <input type="text" class="chat-input" id="chatInput" placeholder="Speak or type your command, sir..." autocomplete="off">
                <button class="chat-btn" id="voiceBtn" onclick="toggleVoice()" title="Voice Command">
                    <i class="fas fa-microphone"></i>
                </button>
                <button class="chat-btn" onclick="sendMessage()" title="Send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <!-- Weather Card -->
        <div class="jarvis-card">
            <div class="card-title">
                <i class="fas fa-cloud-sun"></i>
                WEATHER STATION
            </div>
            <div class="weather-content" id="weatherContent">
                <div>
                    <div class="weather-icon"><i class="fas fa-cloud"></i></div>
                </div>
                <div>
                    <div class="weather-temp">--°C</div>
                    <div class="weather-details">
                        Loading weather data...
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info Card -->
        <div class="jarvis-card">
            <div class="card-title">
                <i class="fas fa-microchip"></i>
                SYSTEM MONITOR
            </div>
            <div class="system-grid" id="systemInfo">
                <div class="sys-item">
                    <div class="sys-label">CPU</div>
                    <div class="sys-value" id="sysCpu">--</div>
                </div>
                <div class="sys-item">
                    <div class="sys-label">MEMORY</div>
                    <div class="sys-value" id="sysMemory">--</div>
                    <div class="sys-bar"><div class="sys-bar-fill green" id="memBar" style="width:0%"></div></div>
                </div>
                <div class="sys-item">
                    <div class="sys-label">DISK</div>
                    <div class="sys-value" id="sysDisk">--</div>
                    <div class="sys-bar"><div class="sys-bar-fill blue" id="diskBar" style="width:0%"></div></div>
                </div>
                <div class="sys-item">
                    <div class="sys-label">UPTIME</div>
                    <div class="sys-value" id="sysUptime">--</div>
                </div>
            </div>
        </div>

        <!-- Search Card -->
        <div class="jarvis-card">
            <div class="card-title">
                <i class="fas fa-search"></i>
                WEB SEARCH
            </div>
            <div class="search-input-wrapper">
                <input type="text" class="search-input" id="searchInput" placeholder="Search the web, sir...">
                <button class="search-btn" onclick="performSearch()">SEARCH</button>
            </div>
            <div class="search-links" id="searchLinks"></div>
        </div>

        <!-- App Launcher -->
        <div class="jarvis-card">
            <div class="card-title">
                <i class="fas fa-rocket"></i>
                APPLICATION LAUNCHER
            </div>
            <div class="app-grid">
                <button class="app-btn" onclick="openApp('chrome')">
                    <i class="fab fa-chrome"></i>
                    Chrome
                </button>
                <button class="app-btn" onclick="openApp('vscode')">
                    <i class="fas fa-code"></i>
                    VS Code
                </button>
                <button class="app-btn" onclick="openApp('terminal')">
                    <i class="fas fa-terminal"></i>
                    Terminal
                </button>
                <button class="app-btn" onclick="openApp('notepad')">
                    <i class="fas fa-file-alt"></i>
                    Notepad
                </button>
                <button class="app-btn" onclick="openApp('calculator')">
                    <i class="fas fa-calculator"></i>
                    Calculator
                </button>
                <button class="app-btn" onclick="openApp('explorer')">
                    <i class="fas fa-folder"></i>
                    Explorer
                </button>
                <button class="app-btn" onclick="openApp('spotify')">
                    <i class="fab fa-spotify"></i>
                    Spotify
                </button>
                <button class="app-btn" onclick="openApp('discord')">
                    <i class="fab fa-discord"></i>
                    Discord
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    // ============================================
    // JARVIS - JavaScript Engine
    // ============================================

    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ========== PARTICLES ==========
    (function createParticles() {
        const container = document.getElementById('particles');
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = (Math.random() * 15 + 10) + 's';
            particle.style.animationDelay = (Math.random() * 10) + 's';
            particle.style.width = particle.style.height = (Math.random() * 3 + 1) + 'px';
            container.appendChild(particle);
        }
    })();

    // ========== CLOCK ==========
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('en-US', { hour12: false });
        const date = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('currentTime').textContent = time;
        document.getElementById('currentDate').textContent = date.toUpperCase();
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ========== CHAT ==========
    function addMessage(text, type) {
        const messages = document.getElementById('chatMessages');
        const div = document.createElement('div');
        div.className = `chat-msg ${type}`;

        if (type === 'jarvis') {
            div.innerHTML = `<div class="msg-sender">J.A.R.V.I.S.</div>${text}`;
        } else {
            div.innerHTML = `<div class="msg-sender">YOU</div>${text}`;
        }

        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function addTypingIndicator() {
        const messages = document.getElementById('chatMessages');
        const div = document.createElement('div');
        div.className = 'chat-msg jarvis';
        div.id = 'typingIndicator';
        div.innerHTML = `
            <div class="msg-sender">J.A.R.V.I.S.</div>
            <div class="typing-indicator">
                <span></span><span></span><span></span>
            </div>
        `;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function removeTypingIndicator() {
        const el = document.getElementById('typingIndicator');
        if (el) el.remove();
    }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;

        addMessage(message, 'user');
        input.value = '';
        addTypingIndicator();

        try {
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            removeTypingIndicator();

            if (data.success) {
                addMessage(data.reply, 'jarvis');
            } else {
                addMessage('I apologize, sir. I seem to be experiencing a malfunction.', 'jarvis');
            }
        } catch (error) {
            removeTypingIndicator();
            addMessage('Connection error, sir. Please check your network.', 'jarvis');
        }
    }

    function sendQuick(text) {
        document.getElementById('chatInput').value = text;
        sendMessage();
    }

    document.getElementById('chatInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });

    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') performSearch();
    });

    // ========== VOICE COMMAND ==========
    let recognition = null;
    let isListening = false;

    function toggleVoice() {
        if (isListening) {
            stopVoice();
        } else {
            startVoice();
        }
    }

    function startVoice() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            addMessage('Voice recognition is not supported in this browser, sir. Please use Chrome.', 'jarvis');
            return;
        }

        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SpeechRecognition();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';

        recognition.onstart = function() {
            isListening = true;
            document.getElementById('voiceBtn').classList.add('voice-active');
            document.getElementById('statusText').textContent = 'LISTENING...';
        };

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            document.getElementById('chatInput').value = transcript;
            sendMessage();
        };

        recognition.onerror = function() {
            stopVoice();
            addMessage('I could not hear you clearly, sir. Please try again.', 'jarvis');
        };

        recognition.onend = function() {
            stopVoice();
        };

        recognition.start();
    }

    function stopVoice() {
        isListening = false;
        document.getElementById('voiceBtn').classList.remove('voice-active');
        document.getElementById('statusText').textContent = 'ALL SYSTEMS OPERATIONAL';
        if (recognition) {
            recognition.stop();
            recognition = null;
        }
    }

    // ========== WEATHER ==========
    async function loadWeather(city = 'Dhaka') {
        try {
            const response = await fetch('/api/weather', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ city })
            });

            const data = await response.json();
            if (data.success) {
                const iconMap = {
                    '01d': 'fa-sun', '01n': 'fa-moon',
                    '02d': 'fa-cloud-sun', '02n': 'fa-cloud-moon',
                    '03d': 'fa-cloud', '03n': 'fa-cloud',
                    '04d': 'fa-cloud', '04n': 'fa-cloud',
                    '09d': 'fa-cloud-rain', '09n': 'fa-cloud-rain',
                    '10d': 'fa-cloud-sun-rain', '10n': 'fa-cloud-moon-rain',
                    '11d': 'fa-bolt', '11n': 'fa-bolt',
                    '13d': 'fa-snowflake', '13n': 'fa-snowflake',
                    '50d': 'fa-smog', '50n': 'fa-smog',
                };

                const icon = iconMap[data.icon] || 'fa-cloud';

                document.getElementById('weatherContent').innerHTML = `
                    <div>
                        <div class="weather-icon"><i class="fas ${icon}"></i></div>
                    </div>
                    <div>
                        <div class="weather-temp">${data.temp}°C</div>
                        <div class="weather-details">
                            <strong>${data.city}, ${data.country}</strong><br>
                            <span>Feels like:</span> ${data.feels_like}°C<br>
                            <span>Humidity:</span> ${data.humidity}%<br>
                            <span>Wind:</span> ${data.wind_speed} m/s<br>
                            <span>${data.description}</span>
                        </div>
                    </div>
                `;
            }
        } catch (error) {
            document.getElementById('weatherContent').innerHTML = '<div style="color: var(--jarvis-danger);">Unable to fetch weather data.</div>';
        }
    }

    // ========== SYSTEM INFO ==========
    async function loadSystemInfo() {
        try {
            const response = await fetch('/api/system-info', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                }
            });

            const data = await response.json();
            if (data.success) {
                const info = data.data;
                document.getElementById('sysCpu').textContent = info.php_version;
                document.getElementById('sysMemory').textContent = `${info.memory.percent}%`;
                document.getElementById('memBar').style.width = info.memory.percent + '%';
                document.getElementById('sysDisk').textContent = `${info.disk.percent}%`;
                document.getElementById('diskBar').style.width = info.disk.percent + '%';
                document.getElementById('sysUptime').textContent = info.hostname;

                // Color code bars
                const memBar = document.getElementById('memBar');
                memBar.className = 'sys-bar-fill ' + (info.memory.percent > 80 ? 'red' : info.memory.percent > 60 ? 'orange' : 'green');

                const diskBar = document.getElementById('diskBar');
                diskBar.className = 'sys-bar-fill ' + (info.disk.percent > 90 ? 'red' : info.disk.percent > 70 ? 'orange' : 'blue');
            }
        } catch (error) {
            console.error('System info error:', error);
        }
    }

    // ========== WEB SEARCH ==========
    async function performSearch() {
        const query = document.getElementById('searchInput').value.trim();
        if (!query) return;

        try {
            const response = await fetch('/api/search', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ query })
            });

            const data = await response.json();
            if (data.success) {
                document.getElementById('searchLinks').innerHTML = `
                    <a href="${data.google_url}" target="_blank" class="search-link">
                        <i class="fab fa-google"></i> Google
                    </a>
                    <a href="${data.youtube_url}" target="_blank" class="search-link">
                        <i class="fab fa-youtube"></i> YouTube
                    </a>
                    <a href="${data.github_url}" target="_blank" class="search-link">
                        <i class="fab fa-github"></i> GitHub
                    </a>
                `;
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    // ========== APP LAUNCHER ==========
    async function openApp(appName) {
        addMessage(`Opening ${appName}...`, 'user');

        try {
            const response = await fetch('/api/open-app', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ app: appName })
            });

            const data = await response.json();
            addMessage(data.message, 'jarvis');
        } catch (error) {
            addMessage('Unable to launch application, sir.', 'jarvis');
        }
    }

    // ========== INIT ==========
    document.addEventListener('DOMContentLoaded', function() {
        loadWeather();
        loadSystemInfo();

        // Refresh system info every 30 seconds
        setInterval(loadSystemInfo, 30000);
    });
</script>

</body>
</html>
