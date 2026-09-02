<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>J.A.R.V.I.S. — Chat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Belanosima:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --j-blue: #00d4ff;
            --j-blue-dark: #0066aa;
            --j-cyan: #00fff2;
            --j-glow: rgba(0, 212, 255, 0.4);
            --j-bg: #050810;
            --j-bg-light: #0a0f1e;
            --j-card: rgba(8, 15, 35, 0.6);
            --j-card-solid: rgba(10, 18, 40, 0.85);
            --j-border: rgba(0, 212, 255, 0.08);
            --j-border-hover: rgba(0, 212, 255, 0.25);
            --j-text: #7a8ba3;
            --j-text-bright: #eef2ff;
            --j-text-dim: #3d4a5c;
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

        /* ===== BACKGROUND ===== */
        .bg-grid {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background:
                linear-gradient(rgba(0,212,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,212,255,0.015) 1px, transparent 1px);
            background-size: 80px 80px;
            z-index: 0;
        }

        .bg-glow {
            position: fixed; top: -30%; left: -10%; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.04) 0%, transparent 70%);
            border-radius: 50%; z-index: 0; pointer-events: none;
            animation: bgFloat 20s ease-in-out infinite;
        }

        .bg-glow-2 {
            position: fixed; bottom: -20%; right: -5%; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.03) 0%, transparent 70%);
            border-radius: 50%; z-index: 0; pointer-events: none;
            animation: bgFloat 25s ease-in-out infinite reverse;
        }

        @keyframes bgFloat {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(40px, -30px); }
        }

        /* ===== TOPBAR ===== */
        .topbar {
            position: relative; z-index: 10;
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 32px;
            background: rgba(5, 8, 16, 0.75);
            border-bottom: 1px solid var(--j-border);
            backdrop-filter: blur(30px) saturate(1.2);
            -webkit-backdrop-filter: blur(30px) saturate(1.2);
        }

        .topbar-left {
            display: flex; align-items: center; gap: 20px;
        }

        .back-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 18px;
            background: rgba(0, 212, 255, 0.04);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 10px;
            color: var(--j-text);
            text-decoration: none;
            font-family: 'Belanosima', sans-serif;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .back-btn:hover {
            border-color: var(--j-blue);
            color: var(--j-blue);
            background: rgba(0, 212, 255, 0.08);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.1);
        }

        .topbar-brand {
            display: flex; align-items: center; gap: 12px;
        }

        .topbar-logo {
            width: 34px; height: 34px;
            position: relative;
            display: flex; align-items: center; justify-content: center;
        }

        .topbar-logo .arc-ring {
            position: absolute; border-radius: 50%; border: 1px solid transparent;
        }
        .topbar-logo .ar1 { width: 34px; height: 34px; border-top-color: var(--j-blue); border-bottom-color: var(--j-blue); animation: spin 3s linear infinite; }
        .topbar-logo .ar2 { width: 26px; height: 26px; border-left-color: var(--j-cyan); border-right-color: var(--j-cyan); animation: spin 2.5s linear infinite reverse; }
        .topbar-logo .arc-core {
            width: 10px; height: 10px;
            background: radial-gradient(circle, #fff 0%, var(--j-blue) 40%, transparent 70%);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--j-blue), 0 0 20px var(--j-glow);
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        .topbar-name {
            font-family: 'Belanosima', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--j-text-bright);
            letter-spacing: 4px;
        }

        .topbar-status {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 14px;
            background: rgba(0, 255, 136, 0.04);
            border: 1px solid rgba(0, 255, 136, 0.1);
            border-radius: 20px;
        }

        .topbar-status .dot {
            width: 7px; height: 7px;
            background: var(--j-success);
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
            box-shadow: 0 0 8px var(--j-success);
        }

        .topbar-status span {
            font-family: 'Belanosima', sans-serif;
            font-size: 0.6rem;
            color: var(--j-success);
            letter-spacing: 1.5px;
        }

        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        /* ===== MAIN LAYOUT ===== */
        .chat-main {
            flex: 1;
            display: flex;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* ===== CHAT AREA ===== */
        .chat-area {
            flex: 0 0 80%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .chat-messages::-webkit-scrollbar { width: 3px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: rgba(0, 212, 255, 0.15); border-radius: 3px; }
        .chat-messages::-webkit-scrollbar-thumb:hover { background: rgba(0, 212, 255, 0.3); }

        /* Messages */
        .msg {
            max-width: 72%;
            padding: 18px 22px;
            border-radius: 20px;
            font-size: 0.92rem;
            font-family: 'Belanosima', sans-serif;
            line-height: 1.75;
            animation: msgSlide 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        @keyframes msgSlide {
            from { opacity: 0; transform: translateY(16px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .msg.jarvis {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.05) 0%, rgba(0, 212, 255, 0.02) 100%);
            border: 1px solid rgba(0, 212, 255, 0.08);
            align-self: flex-start;
            border-bottom-left-radius: 6px;
            backdrop-filter: blur(10px);
        }

        .msg.jarvis:hover {
            border-color: rgba(0, 212, 255, 0.15);
            box-shadow: 0 4px 24px rgba(0, 212, 255, 0.05);
        }

        .msg.jarvis .sender {
            display: flex; align-items: center; gap: 6px;
            color: var(--j-blue);
            font-family: 'Belanosima', sans-serif;
            font-size: 0.58rem;
            letter-spacing: 2.5px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .msg.jarvis .sender::before {
            content: '';
            width: 6px; height: 6px;
            background: var(--j-blue);
            border-radius: 50%;
            box-shadow: 0 0 6px var(--j-blue);
        }

        .msg.user {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(168, 85, 247, 0.04) 100%);
            border: 1px solid rgba(168, 85, 247, 0.12);
            align-self: flex-end;
            border-bottom-right-radius: 6px;
            backdrop-filter: blur(10px);
        }

        .msg.user:hover {
            border-color: rgba(168, 85, 247, 0.2);
            box-shadow: 0 4px 24px rgba(168, 85, 247, 0.05);
        }

        .msg.user .sender {
            display: flex; align-items: center; justify-content: flex-end; gap: 6px;
            color: var(--j-purple);
            font-family: 'Belanosima', sans-serif;
            font-size: 0.58rem;
            letter-spacing: 2.5px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .msg.user .sender::after {
            content: '';
            width: 6px; height: 6px;
            background: var(--j-purple);
            border-radius: 50%;
            box-shadow: 0 0 6px var(--j-purple);
        }

        /* Timestamp */
        .msg-time {
            font-family: 'Belanosima', sans-serif;
            font-size: 0.55rem;
            color: var(--j-text-dim);
            margin-top: 8px;
            letter-spacing: 0.5px;
        }

        .msg.user .msg-time { text-align: right; }

        /* Markdown Rendering */
        .msg strong, .msg b { color: var(--j-text-bright); font-weight: 600; }
        .msg em, .msg i { color: var(--j-text); font-style: italic; }
        .msg code {
            background: rgba(0, 212, 255, 0.08);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 5px;
            padding: 2px 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.82em;
            color: var(--j-cyan);
        }
        .msg pre {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--j-border);
            border-radius: 10px;
            padding: 14px;
            margin: 10px 0;
            overflow-x: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            line-height: 1.6;
        }
        .msg pre code {
            background: transparent;
            border: none;
            padding: 0;
            color: var(--j-text-bright);
        }
        .msg ul, .msg ol {
            margin: 8px 0;
            padding-left: 20px;
        }
        .msg li {
            margin: 4px 0;
            line-height: 1.7;
        }
        .msg li::marker { color: var(--j-blue); }
        .msg table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 0.82rem;
        }
        .msg th {
            background: rgba(0, 212, 255, 0.06);
            border: 1px solid rgba(0, 212, 255, 0.1);
            padding: 8px 12px;
            text-align: left;
            font-weight: 600;
            color: var(--j-blue);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 1px;
        }
        .msg td {
            border: 1px solid rgba(0, 212, 255, 0.06);
            padding: 8px 12px;
            color: var(--j-text);
        }
        .msg tr:hover td {
            background: rgba(0, 212, 255, 0.02);
        }
        .msg hr {
            border: none;
            border-top: 1px solid var(--j-border);
            margin: 12px 0;
        }
        .msg blockquote {
            border-left: 3px solid var(--j-blue);
            margin: 10px 0;
            padding: 8px 16px;
            background: rgba(0, 212, 255, 0.03);
            border-radius: 0 8px 8px 0;
            color: var(--j-text);
        }

        /* Typing indicator */
        .typing-ind {
            display: inline-flex; gap: 5px; padding: 10px 0 4px;
        }
        .typing-ind span {
            width: 7px; height: 7px;
            background: var(--j-blue);
            border-radius: 50%;
            animation: typeDot 1.4s infinite ease-in-out;
            box-shadow: 0 0 4px rgba(0, 212, 255, 0.3);
        }
        .typing-ind span:nth-child(2) { animation-delay: 0.2s; }
        .typing-ind span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typeDot {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.3; }
            30% { transform: translateY(-8px); opacity: 1; }
        }

        /* ===== QUICK ACTIONS ===== */
        .quick-bar {
            display: flex; gap: 8px; flex-wrap: wrap;
            padding: 0 40px 16px;
        }

        .q-btn {
            padding: 9px 18px;
            background: rgba(0, 212, 255, 0.03);
            border: 1px solid rgba(0, 212, 255, 0.08);
            border-radius: 24px;
            color: var(--j-text);
            font-family: 'Belanosima', sans-serif;
            font-size: 0.78rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .q-btn:hover {
            background: rgba(0, 212, 255, 0.1);
            border-color: rgba(0, 212, 255, 0.25);
            color: var(--j-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 212, 255, 0.1);
        }

        /* ===== INPUT AREA ===== */
        .input-area {
            padding: 20px 40px 28px;
            background: rgba(5, 8, 16, 0.6);
            border-top: 1px solid var(--j-border);
            backdrop-filter: blur(30px) saturate(1.2);
            -webkit-backdrop-filter: blur(30px) saturate(1.2);
        }

        .input-row {
            display: flex; gap: 12px; align-items: center;
            background: rgba(0, 212, 255, 0.03);
            border: 1.5px solid rgba(0, 212, 255, 0.08);
            border-radius: 18px;
            padding: 6px 6px 6px 22px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-row:focus-within {
            border-color: rgba(0, 212, 255, 0.25);
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.08), inset 0 0 20px rgba(0, 212, 255, 0.02);
        }

        .chat-input {
            flex: 1;
            background: transparent;
            border: none;
            padding: 14px 8px;
            color: var(--j-text-bright);
            font-family: 'Belanosima', sans-serif;
            font-size: 0.95rem;
            outline: none;
        }

        .chat-input::placeholder { color: var(--j-text-dim); }

        .icon-btn {
            width: 48px; height: 48px;
            border-radius: 14px;
            border: 1px solid transparent;
            background: transparent;
            color: var(--j-text-dim);
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .icon-btn:hover {
            background: rgba(0, 212, 255, 0.08);
            color: var(--j-blue);
            border-color: rgba(0, 212, 255, 0.15);
        }

        .icon-btn.send-btn {
            background: linear-gradient(135deg, var(--j-blue) 0%, var(--j-blue-dark) 100%);
            color: white;
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 212, 255, 0.25);
        }

        .icon-btn.send-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 24px rgba(0, 212, 255, 0.35);
        }

        .icon-btn.send-btn:active {
            transform: scale(0.95);
        }

        .icon-btn.active {
            background: rgba(255, 51, 102, 0.15);
            border-color: rgba(255, 51, 102, 0.3);
            color: #ff3366;
            animation: voicePulse 1.2s ease-in-out infinite;
        }

        @keyframes voicePulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 51, 102, 0.2); }
            50% { box-shadow: 0 0 25px rgba(255, 51, 102, 0.4); }
        }

        /* ===== SIDE PANEL ===== */
        .side-panel {
            flex: 0 0 20%;
            background: linear-gradient(180deg, rgba(4, 8, 20, 0.95) 0%, rgba(2, 5, 14, 1) 100%);
            border-left: 1px solid rgba(0, 212, 255, 0.06);
            padding: 10px 8px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            overflow: hidden;
            backdrop-filter: blur(24px) saturate(1.2);
            -webkit-backdrop-filter: blur(24px) saturate(1.2);
        }

        .side-panel::-webkit-scrollbar { width: 3px; }
        .side-panel::-webkit-scrollbar-track { background: transparent; }
        .side-panel::-webkit-scrollbar-thumb { background: rgba(0, 212, 255, 0.1); border-radius: 3px; }

        @media (max-width: 900px) {
            .side-panel { display: none; }
            .chat-messages { padding: 20px; }
            .quick-bar { padding: 0 20px 12px; }
            .input-area { padding: 16px 20px 24px; }
        }

        .side-card {
            background: linear-gradient(145deg, rgba(10, 18, 42, 0.9) 0%, rgba(6, 10, 28, 0.95) 100%);
            border: 1px solid rgba(0, 212, 255, 0.08);
            border-radius: 14px;
            padding: 10px 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow:
                0 4px 16px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.04),
                inset 0 -1px 0 rgba(0, 0, 0, 0.3),
                inset 2px 0 6px rgba(0, 0, 0, 0.1),
                inset -2px 0 6px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .side-card::before {
            content: '';
            position: absolute;
            top: 0; left: 10%; right: 10%;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.3), transparent);
            z-index: 1;
        }

        .side-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 40px;
            background: linear-gradient(180deg, rgba(0, 212, 255, 0.03) 0%, transparent 100%);
            border-radius: 14px 14px 0 0;
            pointer-events: none;
        }

        .side-card:hover {
            border-color: rgba(0, 212, 255, 0.18);
            box-shadow:
                0 8px 28px rgba(0, 0, 0, 0.6),
                0 0 25px rgba(0, 212, 255, 0.04),
                inset 0 1px 0 rgba(255, 255, 255, 0.05),
                inset 0 -1px 0 rgba(0, 0, 0, 0.3);
            transform: translateY(-1px);
        }

        .side-panel .side-card:nth-child(1) { flex: 2.2; }
        .side-panel .side-card:nth-child(2) { flex: 1; }
        .side-panel .side-card:nth-child(3) { flex: 0.6; }
        .side-panel .side-card:nth-child(4) { flex: 1.2; }

        .side-card-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid rgba(0, 212, 255, 0.06);
            position: relative;
            z-index: 1;
        }

        .side-card-label {
            display: flex; align-items: center; gap: 7px;
        }

        .side-card-label i {
            color: var(--j-blue);
            font-size: 0.55rem;
            width: 22px; height: 22px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1) 0%, rgba(0, 212, 255, 0.03) 100%);
            border-radius: 7px;
            border: 1px solid rgba(0, 212, 255, 0.12);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.03), 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .side-card-label span {
            font-family: 'Belanosima', sans-serif;
            font-size: 0.5rem;
            color: var(--j-blue);
            letter-spacing: 2.5px;
            font-weight: 600;
        }

        .side-badge {
            padding: 2px 8px;
            background: linear-gradient(135deg, rgba(0, 255, 136, 0.08) 0%, rgba(0, 255, 136, 0.02) 100%);
            border: 1px solid rgba(0, 255, 136, 0.15);
            border-radius: 20px;
            font-family: 'Belanosima', sans-serif;
            font-size: 0.45rem;
            color: var(--j-success);
            letter-spacing: 1.5px;
            box-shadow: 0 0 8px rgba(0, 255, 136, 0.05);
        }

        /* System Grid */
        .sys-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 6px;
            position: relative;
            z-index: 1;
        }

        .sys-item {
            background: linear-gradient(145deg, rgba(0, 212, 255, 0.04) 0%, rgba(0, 212, 255, 0.01) 100%);
            border: 1px solid rgba(0, 212, 255, 0.07);
            border-radius: 10px;
            padding: 8px 6px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.03),
                inset 0 -1px 0 rgba(0, 0, 0, 0.15);
        }

        .sys-item:hover {
            background: linear-gradient(145deg, rgba(0, 212, 255, 0.08) 0%, rgba(0, 212, 255, 0.02) 100%);
            border-color: rgba(0, 212, 255, 0.15);
            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.4),
                0 0 12px rgba(0, 212, 255, 0.03),
                inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .sys-label {
            font-family: 'Belanosima', sans-serif;
            font-size: 0.42rem;
            color: var(--j-blue);
            letter-spacing: 2px;
            margin-bottom: 3px;
            opacity: 0.8;
        }

        .sys-val {
            font-family: 'Belanosima', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--j-text-bright);
        }

        .sys-bar {
            width: 100%; height: 3px;
            background: rgba(0, 212, 255, 0.06);
            border-radius: 3px;
            margin-top: 5px;
            overflow: hidden;
            overflow: hidden;
        }

        .sys-bar-fill {
            height: 100%; border-radius: 3px;
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sys-bar-fill.green { background: linear-gradient(90deg, rgba(0, 255, 136, 0.6), var(--j-success)); }
        .sys-bar-fill.blue { background: linear-gradient(90deg, rgba(0, 212, 255, 0.6), var(--j-blue)); }
        .sys-bar-fill.orange { background: linear-gradient(90deg, rgba(255, 170, 0, 0.6), #ffaa00); }
        .sys-bar-fill.red { background: linear-gradient(90deg, rgba(255, 51, 102, 0.6), #ff3366); }

        /* Weather */
        .weather-row {
            display: flex; align-items: center; gap: 12px;
            position: relative;
            z-index: 1;
        }

        .weather-icon-wrap {
            font-size: 1.4rem;
            color: #ffaa00;
            text-shadow: 0 0 12px rgba(255, 170, 0, 0.3);
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(145deg, rgba(255, 170, 0, 0.12) 0%, rgba(255, 170, 0, 0.03) 100%);
            border-radius: 12px;
            border: 1px solid rgba(255, 170, 0, 0.15);
            box-shadow:
                0 3px 10px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.04),
                inset 0 -1px 0 rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }

        .weather-temp {
            font-family: 'Belanosima', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--j-text-bright);
        }

        .weather-meta {
            font-family: 'Belanosima', sans-serif;
            font-size: 0.48rem;
            color: var(--j-text);
            line-height: 1.7;
        }

        .weather-meta span { color: var(--j-blue); }

        /* Search */
        .search-row {
            display: flex; gap: 6px;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .search-input {
            flex: 1;
            background: linear-gradient(145deg, rgba(0, 212, 255, 0.04) 0%, rgba(0, 212, 255, 0.01) 100%);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 8px;
            padding: 7px 10px;
            color: var(--j-text-bright);
            font-family: 'Belanosima', sans-serif;
            font-size: 0.65rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus { border-color: var(--j-blue); box-shadow: 0 0 12px rgba(0, 212, 255, 0.08); }
        .search-input::placeholder { color: var(--j-text-dim); }

        .search-go {
            padding: 7px 14px;
            background: linear-gradient(135deg, var(--j-blue), var(--j-blue-dark));
            border: none; border-radius: 8px;
            color: white;
            font-family: 'Belanosima', sans-serif;
            font-size: 0.55rem; font-weight: 600;
            letter-spacing: 1px; cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 212, 255, 0.25);
        }

        .search-go:hover { box-shadow: 0 6px 20px rgba(0, 212, 255, 0.3); transform: translateY(-1px); }

        .search-links { display: flex; gap: 6px; flex-wrap: wrap; }

        .s-link {
            display: flex; align-items: center; gap: 5px;
            padding: 5px 10px;
            background: linear-gradient(145deg, rgba(0, 212, 255, 0.04) 0%, rgba(0, 212, 255, 0.01) 100%);
            border: 1px solid rgba(0, 212, 255, 0.08);
            border-radius: 8px;
            color: var(--j-text); text-decoration: none;
            font-family: 'Belanosima', sans-serif;
            font-size: 0.58rem; font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .s-link:hover {
            border-color: var(--j-blue);
            color: var(--j-blue);
            background: rgba(0, 212, 255, 0.06);
            transform: translateY(-1px);
        }

        /* Apps */
        .apps-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 5px;
            position: relative;
            z-index: 1;
        }

        .app-item {
            display: flex; flex-direction: column; align-items: center; gap: 4px;
            padding: 8px 3px;
            background: linear-gradient(145deg, rgba(0, 212, 255, 0.04) 0%, rgba(0, 212, 255, 0.01) 100%);
            border: 1px solid rgba(0, 212, 255, 0.07);
            border-radius: 10px;
            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.03),
                inset 0 -1px 0 rgba(0, 0, 0, 0.15);
            color: var(--j-text); cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'Belanosima', sans-serif;
            font-size: 0.42rem; font-weight: 600;
            letter-spacing: 0.5px; text-transform: uppercase;
        }

        .app-item:hover {
            background: linear-gradient(145deg, rgba(0, 212, 255, 0.1) 0%, rgba(0, 212, 255, 0.03) 100%);
            border-color: rgba(0, 212, 255, 0.2);
            color: var(--j-blue);
            transform: translateY(-2px);
            box-shadow:
                0 6px 16px rgba(0, 0, 0, 0.4),
                0 0 12px rgba(0, 212, 255, 0.04),
                inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .app-item i { font-size: 0.85rem; transition: transform 0.3s ease; }
        .app-item:hover i { transform: scale(1.15); }

        /* ===== WELCOME SCREEN ===== */
        .welcome-msg {
            text-align: center;
            padding: 60px 40px;
            animation: welcomeFade 1s ease;
        }

        @keyframes welcomeFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-icon {
            width: 90px; height: 90px;
            margin: 0 auto 28px;
            position: relative;
            display: flex; align-items: center; justify-content: center;
        }

        .welcome-icon .ring {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid transparent;
        }

        .welcome-icon .ring-1 {
            width: 90px; height: 90px;
            border-top-color: var(--j-blue);
            border-bottom-color: var(--j-blue);
            animation: wSpin 4s linear infinite;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.15);
        }

        .welcome-icon .ring-2 {
            width: 72px; height: 72px;
            border-left-color: var(--j-cyan);
            border-right-color: var(--j-cyan);
            animation: wSpin 3s linear infinite reverse;
            box-shadow: 0 0 15px rgba(0, 255, 242, 0.1);
        }

        .welcome-icon .ring-3 {
            width: 54px; height: 54px;
            border-top-color: var(--j-purple);
            border-bottom-color: var(--j-purple);
            animation: wSpin 5s linear infinite;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.1);
        }

        .welcome-icon .core {
            width: 22px; height: 22px;
            background: radial-gradient(circle, #fff 0%, var(--j-blue) 40%, transparent 70%);
            border-radius: 50%;
            animation: wPulse 2s ease-in-out infinite;
            box-shadow:
                0 0 12px var(--j-blue),
                0 0 24px var(--j-glow),
                0 0 48px rgba(0, 212, 255, 0.2);
            z-index: 2;
        }

        .welcome-icon .orbit {
            position: absolute;
            width: 80px; height: 80px;
            animation: wSpin 8s linear infinite;
        }

        .welcome-icon .orbit-dot {
            position: absolute;
            width: 5px; height: 5px;
            background: var(--j-cyan);
            border-radius: 50%;
            top: -2px; left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 8px var(--j-cyan);
        }

        .welcome-icon .orbit-2 {
            width: 65px; height: 65px;
            animation: wSpin 6s linear infinite reverse;
        }

        .welcome-icon .orbit-2 .orbit-dot {
            background: var(--j-purple);
            box-shadow: 0 0 8px var(--j-purple);
            width: 4px; height: 4px;
        }

        @keyframes wSpin { 100% { transform: rotate(360deg); } }
        @keyframes wPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 12px var(--j-blue), 0 0 24px var(--j-glow); }
            50% { transform: scale(1.15); box-shadow: 0 0 18px var(--j-blue), 0 0 36px var(--j-glow), 0 0 60px rgba(0, 212, 255, 0.3); }
        }

        .welcome-title {
            font-family: 'Belanosima', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--j-text-bright) 0%, var(--j-blue) 50%, var(--j-cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            letter-spacing: 2px;
            animation: titleGlow 3s ease-in-out infinite;
        }

        @keyframes titleGlow {
            0%, 100% { filter: drop-shadow(0 0 8px rgba(0, 212, 255, 0.2)); }
            50% { filter: drop-shadow(0 0 16px rgba(0, 212, 255, 0.4)); }
        }

        .welcome-divider {
            width: 60px; height: 2px;
            margin: 0 auto 16px;
            background: linear-gradient(90deg, transparent, var(--j-blue), transparent);
            border-radius: 2px;
            animation: dividerPulse 2s ease-in-out infinite;
        }

        @keyframes dividerPulse {
            0%, 100% { width: 60px; opacity: 0.6; }
            50% { width: 100px; opacity: 1; }
        }

        .welcome-sub {
            font-family: 'Belanosima', sans-serif;
            font-size: 0.95rem;
            color: var(--j-text);
            max-width: 420px;
            margin: 0 auto;
            line-height: 1.8;
        }

        .welcome-sub .highlight {
            color: var(--j-blue);
            font-weight: 600;
        }

        .welcome-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 20px;
            padding: 6px 16px;
            background: rgba(0, 212, 255, 0.04);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 20px;
            font-family: 'Belanosima', sans-serif;
            font-size: 0.7rem;
            color: var(--j-text);
            letter-spacing: 1px;
            animation: tagFade 1.5s ease 1s both;
        }

        .welcome-tag .dot {
            width: 5px; height: 5px;
            background: var(--j-success);
            border-radius: 50%;
            box-shadow: 0 0 6px var(--j-success);
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes tagFade {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-glow"></div>
<div class="bg-glow-2"></div>

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
        <!-- Welcome Screen -->
        <div class="welcome-msg" id="welcomeScreen">
            <div class="welcome-icon">
                <div class="ring ring-1"></div>
                <div class="ring ring-2"></div>
                <div class="ring ring-3"></div>
                <div class="orbit"><div class="orbit-dot"></div></div>
                <div class="orbit-2"><div class="orbit-dot"></div></div>
                <div class="core"></div>
            </div>
            <div class="welcome-title">Good day, sir.</div>
            <div class="welcome-divider"></div>
            <div class="welcome-sub">I am <span class="highlight">JARVIS</span> — your personal AI assistant. All systems are online and operational. How may I be of service?</div>
            <div class="welcome-tag"><div class="dot"></div>Ready for your command</div>
        </div>

        <!-- Chat Messages -->
        <div class="chat-messages" id="chatMessages" style="display:none;"></div>

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
                <button class="icon-btn send-btn" onclick="sendMessage()" title="Send">
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
                    <div class="sys-val" id="sysUptime" style="font-size:0.65rem;">--</div>
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
    let firstMessage = true;

    function getTimeString() {
        return new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    // ========== MARKDOWN PARSER ==========
    function parseMd(md) {
        // Escape HTML
        let html = md
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Code blocks (``` ... ```)
        html = html.replace(/```(\w*)\n?([\s\S]*?)```/g, '<pre><code>$2</code></pre>');

        // Inline code
        html = html.replace(/`([^`]+)`/g, '<code>$1</code>');

        // Bold + Italic
        html = html.replace(/\*\*\*(.+?)\*\*\*/g, '<strong><em>$1</em></strong>');
        // Bold
        html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        html = html.replace(/__(.+?)__/g, '<strong>$1</strong>');
        // Italic
        html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');
        html = html.replace(/_(.+?)_/g, '<em>$1</em>');
        // Strikethrough
        html = html.replace(/~~(.+?)~~/g, '<s>$1</s>');

        // Headers
        html = html.replace(/^#### (.+)$/gm, '<h4>$1</h4>');
        html = html.replace(/^### (.+)$/gm, '<h3>$1</h3>');
        html = html.replace(/^## (.+)$/gm, '<h2>$1</h2>');
        html = html.replace(/^# (.+)$/gm, '<h1>$1</h1>');

        // Horizontal rule
        html = html.replace(/^---+$/gm, '<hr>');

        // Blockquote
        html = html.replace(/^&gt; (.+)$/gm, '<blockquote>$1</blockquote>');

        // Tables
        html = html.replace(/^\|(.+)\|\s*\n\|[-| :]+\|\s*\n((?:\|.+\|\s*\n?)*)/gm, function(match, header, body) {
            const ths = header.split('|').map(h => h.trim()).filter(h => h).map(h => `<th>${h}</th>`).join('');
            const rows = body.trim().split('\n').map(row => {
                const tds = row.replace(/^\||\|$/g, '').split('|').map(c => c.trim()).map(c => `<td>${c}</td>`).join('');
                return `<tr>${tds}</tr>`;
            }).join('');
            return `<table><thead><tr>${ths}</tr></thead><tbody>${rows}</tbody></table>`;
        });

        // Unordered list
        html = html.replace(/^(?:[-*+] (.+)(?:\n|$))+/gm, function(block) {
            const items = block.trim().split('\n').map(line => {
                return `<li>${line.replace(/^[-*+] /, '')}</li>`;
            }).join('');
            return `<ul>${items}</ul>`;
        });

        // Ordered list
        html = html.replace(/^(?:\d+\. (.+)(?:\n|$))+/gm, function(block) {
            const items = block.trim().split('\n').map(line => {
                return `<li>${line.replace(/^\d+\. /, '')}</li>`;
            }).join('');
            return `<ol>${items}</ol>`;
        });

        // Line breaks (convert double newlines to paragraphs, single to <br>)
        html = html.replace(/\n\n+/g, '</p><p>');
        html = html.replace(/\n/g, '<br>');
        html = '<p>' + html + '</p>';

        // Clean empty paragraphs
        html = html.replace(/<p><\/p>/g, '');
        html = html.replace(/<p>(<h[1-4]>)/g, '$1');
        html = html.replace(/(<\/h[1-4]>)<\/p>/g, '$1');
        html = html.replace(/<p>(<table>)/g, '$1');
        html = html.replace(/(<\/table>)<\/p>/g, '$1');
        html = html.replace(/<p>(<ul>)/g, '$1');
        html = html.replace(/(<\/ul>)<\/p>/g, '$1');
        html = html.replace(/<p>(<ol>)/g, '$1');
        html = html.replace(/(<\/ol>)<\/p>/g, '$1');
        html = html.replace(/<p>(<pre>)/g, '$1');
        html = html.replace(/(<\/pre>)<\/p>/g, '$1');
        html = html.replace(/<p>(<blockquote>)/g, '$1');
        html = html.replace(/(<\/blockquote>)<\/p>/g, '$1');
        html = html.replace(/<p>(<hr>)/g, '$1');

        return html;
    }

    // ========== CHAT ==========
    function addMsg(text, type) {
        const m = document.getElementById('chatMessages');
        const d = document.createElement('div');
        d.className = `msg ${type}`;
        const rendered = type === 'jarvis' ? parseMd(text) : text.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
        d.innerHTML = type === 'jarvis'
            ? `<div class="sender">J.A.R.V.I.S.</div>${rendered}<div class="msg-time">${getTimeString()}</div>`
            : `<div class="sender">YOU</div>${rendered}<div class="msg-time">${getTimeString()}</div>`;
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

    function showChat() {
        if (firstMessage) {
            document.getElementById('welcomeScreen').style.display = 'none';
            document.getElementById('chatMessages').style.display = 'flex';
            firstMessage = false;
        }
    }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const msg = input.value.trim();
        if (!msg) return;

        showChat();
        addMsg(msg, 'user');
        input.value = '';
        addTyping();

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
            showChat(); addMsg('Voice not supported. Use Chrome.', 'jarvis'); return;
        }
        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        rec = new SR();
        rec.continuous = false;
        rec.interimResults = false;
        rec.lang = 'en-US';
        rec.onstart = () => { listening = true; document.getElementById('voiceBtn').classList.add('active'); document.getElementById('statusText').textContent = 'LISTENING...'; };
        rec.onresult = (e) => { document.getElementById('chatInput').value = e.results[0][0].transcript; sendMessage(); };
        rec.onerror = () => { stopVoice(); showChat(); addMsg('Could not hear you, sir.', 'jarvis'); };
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
    async function loadWeather(city = 'Khulna') {
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
        showChat();
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
