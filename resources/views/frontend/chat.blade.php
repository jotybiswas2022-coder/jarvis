<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>J.A.R.V.I.S. — Chat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --j-blue: #00d4ff;
            --j-blue-dark: #0066aa;
            --j-cyan: #00fff2;
            --j-glow: rgba(0, 212, 255, 0.4);
            --j-bg: #050810;
            --j-card: rgba(8, 15, 35, 0.8);
            --j-border: rgba(0, 212, 255, 0.12);
            --j-border-hover: rgba(0, 212, 255, 0.3);
            --j-text: #8899b0;
            --j-text-bright: #f0f4ff;
            --j-text-dim: #4a5568;
            --j-success: #00ff88;
            --j-purple: #a855f7;
            --j-pink: #ec4899;
            --radius: 20px;
            --radius-sm: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--j-bg);
            color: var(--j-text);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Background */
        .bg-grid {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(0,212,255,0.02) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(0,212,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px; z-index: 0;
        }

        /* Top Bar */
        .topbar {
            position: relative; z-index: 10;
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 30px;
            background: rgba(5, 8, 16, 0.9);
            border-bottom: 1px solid var(--j-border);
            backdrop-filter: blur(20px);
        }

        .topbar-left {
            display: flex; align-items: center; gap: 16px;
        }

        .back-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 16px;
            background: rgba(0, 212, 255, 0.06);
            border: 1px solid var(--j-border);
            border-radius: 10px;
            color: var(--j-text);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            border-color: var(--j-blue);
            color: var(--j-blue);
        }

        .topbar-brand {
            display: flex; align-items: center; gap: 10px;
        }

        .topbar-logo {
            width: 32px; height: 32px;
            position: relative;
            display: flex; align-items: center; justify-content: center;
        }

        .topbar-logo .arc-ring {
            position: absolute; border-radius: 50%; border: 1px solid transparent;
        }
        .topbar-logo .ar1 { width: 32px; height: 32px; border-top-color: var(--j-blue); border-bottom-color: var(--j-blue); animation: spin 3s linear infinite; }
        .topbar-logo .ar2 { width: 24px; height: 24px; border-left-color: var(--j-cyan); border-right-color: var(--j-cyan); animation: spin 2.5s linear infinite reverse; }
        .topbar-logo .arc-core {
            width: 8px; height: 8px;
            background: radial-gradient(circle, #fff 0%, var(--j-blue) 40%, transparent 70%);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--j-blue), 0 0 16px var(--j-glow);
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        .topbar-name {
            font-family: 'Josefin Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--j-text-bright);
            letter-spacing: 3px;
        }

        .topbar-status {
            display: flex; align-items: center; gap: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: var(--j-success);
            letter-spacing: 1px;
        }

        .topbar-status .dot {
            width: 6px; height: 6px;
            background: var(--j-success);
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
            box-shadow: 0 0 6px var(--j-success);
        }

        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        /* Main Chat Layout */
        .chat-main {
            flex: 1;
            display: flex;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* Chat Area */
        .chat-area {
            flex: 0 0 70%;
            display: flex;
            flex-direction: column;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .chat-messages::-webkit-scrollbar { width: 4px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: var(--j-blue-dark); border-radius: 4px; }

        .msg {
            max-width: 70%;
            padding: 16px 20px;
            border-radius: 16px;
            font-size: 0.95rem;
            line-height: 1.7;
            animation: msgIn 0.3s ease;
        }

        @keyframes msgIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .msg.jarvis {
            background: rgba(0, 212, 255, 0.06);
            border: 1px solid rgba(0, 212, 255, 0.1);
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .msg.jarvis .sender {
            color: var(--j-blue);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .msg.user {
            background: rgba(168, 85, 247, 0.08);
            border: 1px solid rgba(168, 85, 247, 0.12);
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .msg.user .sender {
            color: var(--j-purple);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 2px;
            margin-bottom: 8px;
            text-align: right;
        }

        .typing-ind {
            display: inline-flex; gap: 4px; padding: 8px 0;
        }
        .typing-ind span {
            width: 6px; height: 6px;
            background: var(--j-blue);
            border-radius: 50%;
            animation: typeDot 1.4s infinite ease-in-out;
        }
        .typing-ind span:nth-child(2) { animation-delay: 0.2s; }
        .typing-ind span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typeDot {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.3; }
            30% { transform: translateY(-6px); opacity: 1; }
        }

        /* Quick Actions */
        .quick-bar {
            display: flex; gap: 8px; flex-wrap: wrap;
            padding: 0 40px 16px;
        }

        .q-btn {
            padding: 8px 16px;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 20px;
            color: var(--j-text);
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .q-btn:hover {
            background: rgba(0, 212, 255, 0.12);
            border-color: var(--j-blue);
            color: var(--j-blue);
        }

        /* Input Area */
        .input-area {
            padding: 20px 40px 30px;
            background: rgba(5, 8, 16, 0.8);
            border-top: 1px solid var(--j-border);
            backdrop-filter: blur(20px);
        }

        .input-row {
            display: flex; gap: 12px; align-items: center;
        }

        .chat-input {
            flex: 1;
            background: rgba(0, 212, 255, 0.04);
            border: 1.5px solid var(--j-border);
            border-radius: 14px;
            padding: 16px 22px;
            color: var(--j-text-bright);
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .chat-input:focus {
            border-color: var(--j-blue);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.1);
        }

        .chat-input::placeholder { color: var(--j-text-dim); }

        .icon-btn {
            width: 52px; height: 52px;
            border-radius: 14px;
            border: 1.5px solid var(--j-border);
            background: rgba(0, 212, 255, 0.06);
            color: var(--j-blue);
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex; align-items: center; justify-content: center;
        }

        .icon-btn:hover {
            background: var(--j-blue);
            color: var(--j-bg);
            box-shadow: 0 0 20px var(--j-glow);
        }

        .icon-btn.active {
            background: #ff3366;
            border-color: #ff3366;
            color: white;
            animation: voicePulse 1s ease-in-out infinite;
        }

        @keyframes voicePulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 51, 102, 0.4); }
            50% { box-shadow: 0 0 25px rgba(255, 51, 102, 0.7); }
        }

        /* Side Panel */
        .side-panel {
            flex: 0 0 30%;
            background: rgba(5, 8, 16, 0.9);
            border-left: 1px solid var(--j-border);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow-y: auto;
        }

        @media (max-width: 900px) {
            .side-panel { display: none; }
            .chat-messages { padding: 20px; }
            .quick-bar { padding: 0 20px 12px; }
            .input-area { padding: 16px 20px 24px; }
        }

        .side-card {
            background: var(--j-card);
            border: 1px solid var(--j-border);
            border-radius: var(--radius);
            padding: 20px;
        }

        .side-card-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }

        .side-card-label {
            display: flex; align-items: center; gap: 8px;
        }

        .side-card-label i { color: var(--j-blue); font-size: 0.85rem; }

        .side-card-label span {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: var(--j-blue);
            letter-spacing: 2px;
        }

        .side-badge {
            padding: 3px 10px;
            background: rgba(0, 255, 136, 0.08);
            border: 1px solid rgba(0, 255, 136, 0.15);
            border-radius: 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.55rem;
            color: var(--j-success);
            letter-spacing: 1px;
        }

        .sys-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
        }

        .sys-item {
            background: rgba(0, 212, 255, 0.03);
            border: 1px solid rgba(0, 212, 255, 0.06);
            border-radius: 10px;
            padding: 12px;
            text-align: center;
        }

        .sys-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.55rem;
            color: var(--j-blue);
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .sys-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--j-text-bright);
        }

        .sys-bar {
            width: 100%; height: 3px;
            background: rgba(0, 212, 255, 0.08);
            border-radius: 3px;
            margin-top: 6px;
        }

        .sys-bar-fill {
            height: 100%; border-radius: 3px;
            transition: width 1s ease;
        }

        .sys-bar-fill.green { background: var(--j-success); }
        .sys-bar-fill.blue { background: var(--j-blue); }
        .sys-bar-fill.orange { background: #ffaa00; }
        .sys-bar-fill.red { background: #ff3366; }

        .weather-row { display: flex; align-items: center; gap: 14px; }

        .weather-icon-wrap {
            font-size: 2rem;
            color: #ffaa00;
            text-shadow: 0 0 12px rgba(255, 170, 0, 0.4);
        }

        .weather-temp {
            font-family: 'Josefin Sans', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--j-text-bright);
        }

        .weather-meta {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: var(--j-text);
            line-height: 1.8;
        }

        .weather-meta span { color: var(--j-blue); }

        /* Search in side panel */
        .search-row {
            display: flex; gap: 8px; margin-bottom: 12px;
        }

        .search-input {
            flex: 1;
            background: rgba(0, 212, 255, 0.04);
            border: 1px solid var(--j-border);
            border-radius: 10px;
            padding: 10px 14px;
            color: var(--j-text-bright);
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus { border-color: var(--j-blue); }
        .search-input::placeholder { color: var(--j-text-dim); }

        .search-go {
            padding: 10px 16px;
            background: linear-gradient(135deg, var(--j-blue), var(--j-blue-dark));
            border: none; border-radius: 10px;
            color: white;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem; font-weight: 600;
            letter-spacing: 1px; cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-go:hover { box-shadow: 0 4px 15px var(--j-glow); }

        .search-links { display: flex; gap: 6px; flex-wrap: wrap; }

        .s-link {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 12px;
            background: rgba(0, 212, 255, 0.04);
            border: 1px solid var(--j-border);
            border-radius: 8px;
            color: var(--j-text); text-decoration: none;
            font-size: 0.7rem; transition: all 0.3s ease;
        }

        .s-link:hover { border-color: var(--j-blue); color: var(--j-blue); }

        /* Apps in side panel */
        .apps-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px;
        }

        .app-item {
            display: flex; flex-direction: column; align-items: center; gap: 4px;
            padding: 10px 4px;
            background: rgba(0, 212, 255, 0.03);
            border: 1px solid var(--j-border);
            border-radius: 8px;
            color: var(--j-text); cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.55rem; font-weight: 500;
            letter-spacing: 0.5px; text-transform: uppercase;
        }

        .app-item:hover {
            background: rgba(0, 212, 255, 0.1);
            border-color: var(--j-blue); color: var(--j-blue);
            transform: translateY(-2px);
        }

        .app-item i { font-size: 1rem; }
    </style>
</head>
<body>

<div class="bg-grid"></div>

<!-- Top Bar -->
<div class="topbar">
    <div class="topbar-left">
        <a href="/" class="back-btn">
            <i class="fas fa-arrow-left"></i> Home
        </a>
        <div class="topbar-brand">
            <div class="topbar-logo">
                <div class="arc-ring ar1"></div>
                <div class="arc-ring ar2"></div>
                <div class="arc-core"></div>
            </div>
            <span class="topbar-name">J.A.R.V.I.S.</span>
        </div>
    </div>
    <div class="topbar-status">
        <div class="dot"></div>
        <span id="statusText">ALL SYSTEMS ONLINE</span>
    </div>
</div>

<!-- Main Chat -->
<div class="chat-main">
    <div class="chat-area">
        <div class="chat-messages" id="chatMessages">
            <div class="msg jarvis">
                <div class="sender">J.A.R.V.I.S.</div>
                Good day, sir. I am JARVIS, your personal AI assistant. All systems are online and operational. How may I be of service?
            </div>
        </div>

        <div class="quick-bar">
            <button class="q-btn" onclick="sendQuick('What time is it?')">🕐 Time</button>
            <button class="q-btn" onclick="sendQuick('Tell me a joke')">😄 Joke</button>
            <button class="q-btn" onclick="sendQuick('What can you do?')">❓ Help</button>
            <button class="q-btn" onclick="sendQuick('How are you?')">👋 Status</button>
            <button class="q-btn" onclick="sendQuick('Who created you?')">🛠️ Creator</button>
            <button class="q-btn" onclick="sendQuick('Tell me about Iron Man')">🦾 Iron Man</button>
        </div>

        <div class="input-area">
            <div class="input-row">
                <input type="text" class="chat-input" id="chatInput" placeholder="Type your command, sir..." autocomplete="off">
                <button class="icon-btn" id="voiceBtn" onclick="toggleVoice()" title="Voice Command">
                    <i class="fas fa-microphone"></i>
                </button>
                <button class="icon-btn" onclick="sendMessage()" title="Send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Side Panel -->
    <div class="side-panel">
        <div class="side-card">
            <div class="side-card-header">
                <div class="side-card-label">
                    <i class="fas fa-microchip"></i>
                    <span>SYSTEM</span>
                </div>
                <div class="side-badge">LIVE</div>
            </div>
            <div class="sys-grid">
                <div class="sys-item">
                    <div class="sys-label">CPU</div>
                    <div class="sys-val" id="sysCpu">--</div>
                </div>
                <div class="sys-item">
                    <div class="sys-label">MEMORY</div>
                    <div class="sys-val" id="sysMemory">--</div>
                    <div class="sys-bar"><div class="sys-bar-fill green" id="memBar" style="width:0%"></div></div>
                </div>
                <div class="sys-item">
                    <div class="sys-label">DISK</div>
                    <div class="sys-val" id="sysDisk">--</div>
                    <div class="sys-bar"><div class="sys-bar-fill blue" id="diskBar" style="width:0%"></div></div>
                </div>
                <div class="sys-item">
                    <div class="sys-label">HOST</div>
                    <div class="sys-val" id="sysUptime" style="font-size:0.7rem;">--</div>
                </div>
            </div>
        </div>

        <div class="side-card">
            <div class="side-card-header">
                <div class="side-card-label">
                    <i class="fas fa-cloud-sun"></i>
                    <span>WEATHER</span>
                </div>
                <div class="side-badge" id="weatherCity">--</div>
            </div>
            <div class="weather-row" id="weatherContent">
                <div class="weather-icon-wrap"><i class="fas fa-cloud"></i></div>
                <div>
                    <div class="weather-temp">--°C</div>
                    <div class="weather-meta">Loading...</div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="side-card">
            <div class="side-card-header">
                <div class="side-card-label">
                    <i class="fas fa-search"></i>
                    <span>SEARCH</span>
                </div>
            </div>
            <div class="search-row">
                <input type="text" class="search-input" id="searchInput" placeholder="Search the web...">
                <button class="search-go" onclick="performSearch()">GO</button>
            </div>
            <div class="search-links" id="searchLinks"></div>
        </div>

        <!-- Launcher -->
        <div class="side-card">
            <div class="side-card-header">
                <div class="side-card-label">
                    <i class="fas fa-rocket"></i>
                    <span>LAUNCHER</span>
                </div>
            </div>
            <div class="apps-grid">
                <button class="app-item" onclick="openApp('chrome')"><i class="fab fa-chrome"></i> Chrome</button>
                <button class="app-item" onclick="openApp('vscode')"><i class="fas fa-code"></i> VS Code</button>
                <button class="app-item" onclick="openApp('terminal')"><i class="fas fa-terminal"></i> Terminal</button>
                <button class="app-item" onclick="openApp('notepad')"><i class="fas fa-file-alt"></i> Notepad</button>
                <button class="app-item" onclick="openApp('calculator')"><i class="fas fa-calculator"></i> Calc</button>
                <button class="app-item" onclick="openApp('explorer')"><i class="fas fa-folder"></i> Files</button>
                <button class="app-item" onclick="openApp('spotify')"><i class="fab fa-spotify"></i> Spotify</button>
                <button class="app-item" onclick="openApp('discord')"><i class="fab fa-discord"></i> Discord</button>
            </div>
        </div>
    </div>
</div>

<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let chatHistory = [];

    // ========== CHAT ==========
    function addMsg(text, type) {
        const m = document.getElementById('chatMessages');
        const d = document.createElement('div');
        d.className = `msg ${type}`;
        d.innerHTML = type === 'jarvis'
            ? `<div class="sender">J.A.R.V.I.S.</div>${text}`
            : `<div class="sender">YOU</div>${text}`;
        m.appendChild(d);
        m.scrollTop = m.scrollHeight;
    }

    function addTyping() {
        const m = document.getElementById('chatMessages');
        const d = document.createElement('div');
        d.className = 'msg jarvis';
        d.id = 'typing';
        d.innerHTML = `<div class="sender">J.A.R.V.I.S.</div><div class="typing-ind"><span></span><span></span><span></span></div>`;
        m.appendChild(d);
        m.scrollTop = m.scrollHeight;
    }

    function removeTyping() {
        const el = document.getElementById('typing');
        if (el) el.remove();
    }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const msg = input.value.trim();
        if (!msg) return;
        addMsg(msg, 'user');
        input.value = '';
        addTyping();

        // Store user message in local history
        chatHistory.push({ role: 'user', content: msg });

        try {
            const r = await fetch('/api/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ message: msg, history: chatHistory.slice(-20) })
            });
            const d = await r.json();
            removeTyping();
            const reply = d.success ? d.reply : 'Malfunction detected, sir.';
            addMsg(reply, 'jarvis');

            // Store AI reply in local history
            chatHistory.push({ role: 'assistant', content: reply });
        } catch (e) {
            removeTyping();
            addMsg('Connection error, sir.', 'jarvis');
        }
    }

    function sendQuick(t) { document.getElementById('chatInput').value = t; sendMessage(); }
    document.getElementById('chatInput').addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });

    // ========== VOICE ==========
    let rec = null, listening = false;
    function toggleVoice() { listening ? stopVoice() : startVoice(); }

    function startVoice() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            addMsg('Voice not supported. Use Chrome.', 'jarvis'); return;
        }
        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        rec = new SR();
        rec.continuous = false;
        rec.interimResults = false;
        rec.lang = 'en-US';
        rec.onstart = () => { listening = true; document.getElementById('voiceBtn').classList.add('active'); document.getElementById('statusText').textContent = 'LISTENING...'; };
        rec.onresult = (e) => { document.getElementById('chatInput').value = e.results[0][0].transcript; sendMessage(); };
        rec.onerror = () => { stopVoice(); addMsg('Could not hear you, sir.', 'jarvis'); };
        rec.onend = () => stopVoice();
        rec.start();
    }

    function stopVoice() {
        listening = false;
        document.getElementById('voiceBtn').classList.remove('active');
        document.getElementById('statusText').textContent = 'ALL SYSTEMS ONLINE';
        if (rec) { rec.stop(); rec = null; }
    }

    // ========== WEATHER ==========
    async function loadWeather(city = 'Dhaka') {
        try {
            const r = await fetch('/api/weather', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ city })
            });
            const d = await r.json();
            if (d.success) {
                const icons = { '01d':'fa-sun','01n':'fa-moon','02d':'fa-cloud-sun','02n':'fa-cloud-moon','03d':'fa-cloud','03n':'fa-cloud','09d':'fa-cloud-rain','09n':'fa-cloud-rain','10d':'fa-cloud-sun-rain','11d':'fa-bolt','13d':'fa-snowflake','50d':'fa-smog' };
                document.getElementById('weatherCity').textContent = d.city.toUpperCase();
                document.getElementById('weatherContent').innerHTML = `
                    <div class="weather-icon-wrap"><i class="fas ${icons[d.icon]||'fa-cloud'}"></i></div>
                    <div>
                        <div class="weather-temp">${d.temp}°C</div>
                        <div class="weather-meta">
                            <span>${d.city}, ${d.country}</span><br>
                            Feels ${d.feels_like}°C · ${d.humidity}%<br>${d.description}
                        </div>
                    </div>`;
            }
        } catch (e) {}
    }

    // ========== SYSTEM INFO ==========
    async function loadSystemInfo() {
        try {
            const r = await fetch('/api/system-info', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
            const d = await r.json();
            if (d.success) {
                const i = d.data;
                document.getElementById('sysCpu').textContent = 'PHP ' + i.php_version;
                document.getElementById('sysMemory').textContent = i.memory.percent + '%';
                document.getElementById('memBar').style.width = i.memory.percent + '%';
                document.getElementById('sysDisk').textContent = i.disk.percent + '%';
                document.getElementById('diskBar').style.width = i.disk.percent + '%';
                document.getElementById('sysUptime').textContent = i.hostname;
                document.getElementById('memBar').className = 'sys-bar-fill ' + (i.memory.percent > 80 ? 'red' : i.memory.percent > 60 ? 'orange' : 'green');
                document.getElementById('diskBar').className = 'sys-bar-fill ' + (i.disk.percent > 90 ? 'red' : i.disk.percent > 70 ? 'orange' : 'blue');
            }
        } catch (e) {}
    }

    // ========== SEARCH ==========
    async function performSearch() {
        const q = document.getElementById('searchInput').value.trim();
        if (!q) return;
        try {
            const r = await fetch('/api/search', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ query: q })
            });
            const d = await r.json();
            if (d.success) {
                document.getElementById('searchLinks').innerHTML = `
                    <a href="${d.google_url}" target="_blank" class="s-link"><i class="fab fa-google"></i> Google</a>
                    <a href="${d.youtube_url}" target="_blank" class="s-link"><i class="fab fa-youtube"></i> YouTube</a>
                    <a href="${d.github_url}" target="_blank" class="s-link"><i class="fab fa-github"></i> GitHub</a>`;
            }
        } catch (e) {}
    }

    document.getElementById('searchInput').addEventListener('keypress', e => { if (e.key === 'Enter') performSearch(); });

    // ========== APP LAUNCHER ==========
    async function openApp(name) {
        addMsg(`Opening ${name}...`, 'user');
        try {
            const r = await fetch('/api/open-app', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ app: name })
            });
            const d = await r.json();
            addMsg(d.message, 'jarvis');
        } catch (e) {
            addMsg('Unable to launch app.', 'jarvis');
        }
    }

    // ========== INIT ==========
    document.addEventListener('DOMContentLoaded', () => {
        loadWeather();
        loadSystemInfo();
        setInterval(loadSystemInfo, 30000);
        document.getElementById('chatInput').focus();
    });
</script>

</body>
</html>
