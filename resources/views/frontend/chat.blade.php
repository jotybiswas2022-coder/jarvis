<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>J.A.R.V.I.S. — Chat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Belanosima:wght@400;600;700&family=Hind+Siliguri:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Bengali script → Hind Siliguri, English → Belanosima */
        @font-face {
            font-family: 'Belanosima';
            src: local('Hind Siliguri');
            unicode-range: U+0980-09FF, U+0964-0965, U+200C-200D, U+20B9, U+0966-096F;
        }

        :root {
            --j-blue: #00d4ff;
            --j-blue-dark: #0066aa;
            --j-cyan: #00fff2;
            --j-glow: rgba(0, 212, 255, 0.4);
            --j-bg: #050810;
            --j-card: rgba(8, 15, 35, 0.6);
            --j-border: rgba(0, 212, 255, 0.08);
            --j-text: #c0cfe0;
            --j-text-bright: #ffffff;
            --j-text-dim: #6b7a90;
            --j-success: #00ff88;
            --j-purple: #a855f7;
            --j-pink: #ec4899;
            --radius: 20px;
            --radius-sm: 12px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Belanosima', 'Hind Siliguri', sans-serif;
            background: var(--j-bg);
            color: var(--j-text);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Background - Elegant Dark Pattern */
        .bg-elegant {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; overflow: hidden;
        }
        .bg-elegant-base {
            position: absolute; inset: 0;
            background: radial-gradient(100% 100% at 0% 0%, rgb(46, 46, 46) 0%, rgb(0, 0, 0) 100%);
            mask: radial-gradient(125% 100% at 0% 0%, rgb(0, 0, 0) 0%, rgba(0, 0, 0, 0.22) 88%, rgba(0, 0, 0, 0) 100%);
            -webkit-mask: radial-gradient(125% 100% at 0% 0%, rgb(0, 0, 0) 0%, rgba(0, 0, 0, 0.22) 88%, rgba(0, 0, 0, 0) 100%);
        }
        .bg-streak {
            position: absolute; inset: 0; opacity: 0.2;
            background: linear-gradient(rgb(0, 207, 255) 0%, rgba(0, 207, 255, 0) 100%);
            transform: skewX(45deg);
        }
        .bg-streak-1 {
            mask: linear-gradient(90deg, rgba(0,0,0,0) 0%, rgb(0,0,0) 20%, rgba(0,0,0,0) 36%, rgb(0,0,0) 55%, rgba(0,0,0,0.13) 67%, rgb(0,0,0) 78%, rgba(0,0,0,0) 97%);
            -webkit-mask: linear-gradient(90deg, rgba(0,0,0,0) 0%, rgb(0,0,0) 20%, rgba(0,0,0,0) 36%, rgb(0,0,0) 55%, rgba(0,0,0,0.13) 67%, rgb(0,0,0) 78%, rgba(0,0,0,0) 97%);
        }
        .bg-streak-2 {
            mask: linear-gradient(90deg, rgba(0,0,0,0) 11%, rgb(0,0,0) 25%, rgba(0,0,0,0.55) 41%, rgba(0,0,0,0.13) 67%, rgb(0,0,0) 78%, rgba(0,0,0,0) 97%);
            -webkit-mask: linear-gradient(90deg, rgba(0,0,0,0) 11%, rgb(0,0,0) 25%, rgba(0,0,0,0.55) 41%, rgba(0,0,0,0.13) 67%, rgb(0,0,0) 78%, rgba(0,0,0,0) 97%);
        }
        .bg-streak-3 {
            mask: linear-gradient(90deg, rgba(0,0,0,0) 9%, rgb(0,0,0) 20%, rgba(0,0,0,0.55) 28%, rgba(0,0,0,0.42) 40%, rgb(0,0,0) 48%, rgba(0,0,0,0.27) 54%, rgba(0,0,0,0.13) 78%, rgb(0,0,0) 88%, rgba(0,0,0,0) 97%);
            -webkit-mask: linear-gradient(90deg, rgba(0,0,0,0) 9%, rgb(0,0,0) 20%, rgba(0,0,0,0.55) 28%, rgba(0,0,0,0.42) 40%, rgb(0,0,0) 48%, rgba(0,0,0,0.27) 54%, rgba(0,0,0,0.13) 78%, rgb(0,0,0) 88%, rgba(0,0,0,0) 97%);
        }
        .bg-streak-4 {
            mask: linear-gradient(90deg, rgba(0,0,0,0) 0%, rgb(0,0,0) 17%, rgba(0,0,0,0.55) 26%, rgb(0,0,0) 35%, rgba(0,0,0,0) 47%, rgba(0,0,0,0.13) 69%, rgb(0,0,0) 79%, rgba(0,0,0,0) 97%);
            -webkit-mask: linear-gradient(90deg, rgba(0,0,0,0) 0%, rgb(0,0,0) 17%, rgba(0,0,0,0.55) 26%, rgb(0,0,0) 35%, rgba(0,0,0,0) 47%, rgba(0,0,0,0.13) 69%, rgb(0,0,0) 79%, rgba(0,0,0,0) 97%);
        }
        .bg-streak-5 {
            mask: linear-gradient(90deg, rgba(0,0,0,0) 0%, rgb(0,0,0) 20%, rgba(0,0,0,0.55) 27%, rgb(0,0,0) 42%, rgba(0,0,0,0) 48%, rgba(0,0,0,0.13) 67%, rgb(0,0,0) 74%, rgb(0,0,0) 82%, rgba(0,0,0,0.47) 88%, rgba(0,0,0,0) 97%);
            -webkit-mask: linear-gradient(90deg, rgba(0,0,0,0) 0%, rgb(0,0,0) 20%, rgba(0,0,0,0.55) 27%, rgb(0,0,0) 42%, rgba(0,0,0,0) 48%, rgba(0,0,0,0.13) 67%, rgb(0,0,0) 74%, rgb(0,0,0) 82%, rgba(0,0,0,0.47) 88%, rgba(0,0,0,0) 97%);
        }
        .bg-dots {
            position: absolute; inset: 0; opacity: 0.15;
            background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.4) 1px, transparent 0);
            background-size: 24px 24px;
        }
        .bg-radial {
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 20% 20%, rgba(30, 40, 60, 0.3) 0%, transparent 60%);
        }

        /* Topbar */
        .topbar {
            position: relative; z-index: 10;
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 32px;
            background: rgba(5, 8, 16, 0.75);
            border-bottom: 1px solid var(--j-border);
            backdrop-filter: blur(30px);
        }
        .topbar-left { display: flex; align-items: center; gap: 20px; }
        .back-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 18px; background: rgba(0, 212, 255, 0.04);
            border: 1px solid rgba(0, 212, 255, 0.1); border-radius: 10px;
            color: var(--j-text); text-decoration: none; font-size: 0.8rem; font-weight: 500;
            transition: all 0.3s ease;
        }
        .back-btn:hover { border-color: var(--j-blue); color: var(--j-blue); }
        .topbar-brand { display: flex; align-items: center; gap: 12px; }
        .topbar-logo {
            width: 34px; height: 34px; position: relative;
            display: flex; align-items: center; justify-content: center;
        }
        .topbar-logo .arc-ring { position: absolute; border-radius: 50%; border: 1px solid transparent; }
        .topbar-logo .ar1 { width: 34px; height: 34px; border-top-color: var(--j-blue); border-bottom-color: var(--j-blue); animation: spin 3s linear infinite; }
        .topbar-logo .ar2 { width: 26px; height: 26px; border-left-color: var(--j-cyan); border-right-color: var(--j-cyan); animation: spin 2.5s linear infinite reverse; }
        .topbar-logo .arc-core {
            width: 10px; height: 10px;
            background: radial-gradient(circle, #fff 0%, var(--j-blue) 40%, transparent 70%);
            border-radius: 50%; box-shadow: 0 0 8px var(--j-blue), 0 0 20px var(--j-glow);
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .topbar-name {
            font-family: 'Belanosima', sans-serif; font-size: 0.95rem; font-weight: 700;
            color: var(--j-text-bright); letter-spacing: 4px;
        }
        .topbar-status {
            display: flex; align-items: center; gap: 8px; padding: 6px 14px;
            background: rgba(0, 255, 136, 0.04); border: 1px solid rgba(0, 255, 136, 0.1); border-radius: 20px;
        }
        .topbar-status .dot {
            width: 7px; height: 7px; background: var(--j-success); border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite; box-shadow: 0 0 8px var(--j-success);
        }
        .topbar-status span {
            font-family: 'Belanosima', sans-serif; font-size: 0.6rem;
            color: var(--j-success); letter-spacing: 1.5px;
        }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        /* Main Layout */
        .chat-main {
            flex: 1; display: flex; position: relative; z-index: 1; overflow: hidden;
        }

        /* Chat Area */
        .chat-area { flex: 1; display: flex; flex-direction: column; }
        .chat-messages {
            flex: 1; overflow-y: auto; padding: 30px 40px;
            display: flex; flex-direction: column; gap: 20px;
        }
        .chat-messages::-webkit-scrollbar { width: 3px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: rgba(0, 212, 255, 0.15); border-radius: 3px; }

        .msg {
            max-width: 72%; padding: 18px 22px; border-radius: 20px;
            font-family: 'Hind Siliguri', 'Belanosima', sans-serif;
            font-size: 0.92rem; line-height: 1.75;
            animation: msgSlide 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes msgSlide {
            from { opacity: 0; transform: translateY(16px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .msg.jarvis {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.05), rgba(0, 212, 255, 0.02));
            border: 1px solid rgba(0, 212, 255, 0.08); align-self: flex-start;
            border-bottom-left-radius: 6px;
        }
        .msg.user {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(168, 85, 247, 0.04));
            border: 1px solid rgba(168, 85, 247, 0.12); align-self: flex-end;
            border-bottom-right-radius: 6px;
        }
        .msg .sender {
            display: flex; align-items: center; gap: 6px;
            font-family: 'Belanosima', sans-serif; font-size: 0.58rem;
            letter-spacing: 2.5px; margin-bottom: 10px; text-transform: uppercase; font-weight: 600;
        }
        .msg.jarvis .sender { color: var(--j-blue); }
        .msg.user .sender { color: var(--j-purple); justify-content: flex-end; }
        .msg-time {
            font-family: 'Belanosima', sans-serif; font-size: 0.55rem;
            color: var(--j-text-dim); margin-top: 8px;
        }
        .msg.user .msg-time { text-align: right; }

        .typing-ind { display: inline-flex; gap: 5px; padding: 10px 0 4px; }
        .typing-ind span {
            width: 7px; height: 7px; background: var(--j-blue); border-radius: 50%;
            animation: typeDot 1.4s infinite ease-in-out;
        }
        .typing-ind span:nth-child(2) { animation-delay: 0.2s; }
        .typing-ind span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typeDot {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.3; }
            30% { transform: translateY(-8px); opacity: 1; }
        }

        /* Quick Bar */
        .quick-bar { display: flex; gap: 8px; flex-wrap: wrap; padding: 0 40px 16px; }
        .q-btn {
            padding: 9px 18px; background: rgba(0, 212, 255, 0.03);
            border: 1px solid rgba(0, 212, 255, 0.08); border-radius: 24px;
            color: var(--j-text); font-family: 'Belanosima', 'Hind Siliguri', sans-serif;
            font-size: 0.78rem; font-weight: 500; cursor: pointer;
            transition: all 0.3s ease;
        }
        .q-btn:hover {
            background: rgba(0, 212, 255, 0.1); border-color: rgba(0, 212, 255, 0.25);
            color: var(--j-blue); transform: translateY(-2px);
        }

        /* Input */
        .input-area {
            padding: 20px 40px 28px; background: rgba(5, 8, 16, 0.6);
            border-top: 1px solid var(--j-border); backdrop-filter: blur(30px);
        }
        .input-row {
            display: flex; gap: 12px; align-items: center;
            background: rgba(0, 212, 255, 0.03); border: 1.5px solid rgba(0, 212, 255, 0.08);
            border-radius: 18px; padding: 6px 6px 6px 22px;
            transition: all 0.4s ease;
        }
        .input-row:focus-within {
            border-color: rgba(0, 212, 255, 0.25);
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.08);
        }
        .chat-input {
            flex: 1; background: transparent; border: none; padding: 14px 8px;
            color: var(--j-text-bright); font-family: 'Belanosima', 'Hind Siliguri', sans-serif;
            font-size: 0.95rem; outline: none;
        }
        .chat-input::placeholder { color: var(--j-text-dim); }
        .icon-btn {
            width: 48px; height: 48px; border-radius: 14px; border: 1px solid transparent;
            background: transparent; color: var(--j-text-dim); font-size: 1rem;
            cursor: pointer; transition: all 0.3s ease;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .icon-btn:hover { background: rgba(0, 212, 255, 0.08); color: var(--j-blue); border-color: rgba(0, 212, 255, 0.15); }
        .icon-btn.send-btn {
            background: linear-gradient(135deg, var(--j-blue), var(--j-blue-dark));
            color: white; border: none; box-shadow: 0 4px 16px rgba(0, 212, 255, 0.25);
        }
        .icon-btn.send-btn:hover { transform: scale(1.05); box-shadow: 0 6px 24px rgba(0, 212, 255, 0.35); }
        .icon-btn.active {
            background: rgba(255, 51, 102, 0.15); border-color: rgba(255, 51, 102, 0.3);
            color: #ff3366; animation: voicePulse 1.2s ease-in-out infinite;
        }
        @keyframes voicePulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 51, 102, 0.2); }
            50% { box-shadow: 0 0 25px rgba(255, 51, 102, 0.4); }
        }

        /* ========== SIDE PANEL ========== */
        .side-panel {
            flex: 0 0 20%;
            background: #060b18;
            border-left: 1px solid rgba(0, 212, 255, 0.05);
            padding: 12px 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            overflow: hidden;
        }
        @media (max-width: 900px) {
            .side-panel { display: none; }
            .chat-messages { padding: 20px; }
            .quick-bar { padding: 0 20px 12px; }
            .input-area { padding: 16px 20px 24px; }
        }

        /* Section Boxes */
        .sp-box {
            flex: 1;
            background: #0a1128;
            border-radius: 16px;
            padding: 14px 14px 12px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 212, 255, 0.06);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.4);
        }
        .sp-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--j-blue), transparent);
            opacity: 0.4;
        }
        .sp-divider {
            height: 1px;
            background: rgba(0, 212, 255, 0.06);
            margin: 6px 0;
        }

        /* Section Header */
        .sp-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 8px;
        }
        .sp-head-left { display: flex; align-items: center; gap: 6px; }
        .sp-head-left i { color: var(--j-blue); font-size: 0.55rem; }
        .sp-head-left span {
            font-family: 'Belanosima', sans-serif; font-size: 0.5rem;
            color: var(--j-blue); letter-spacing: 2px; font-weight: 600;
        }
        .sp-badge {
            font-family: 'Belanosima', sans-serif; font-size: 0.42rem;
            color: var(--j-success); letter-spacing: 1px;
            padding: 2px 8px; border: 1px solid rgba(0, 255, 136, 0.15);
            border-radius: 10px; background: rgba(0, 255, 136, 0.05);
        }

        /* System */
        .sp-sys-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 6px;
            flex: 1; align-content: center;
        }
        .sp-sys-box {
            background: #0d1630; border: 1px solid rgba(0, 212, 255, 0.06);
            border-radius: 10px; padding: 8px 6px; text-align: center;
        }
        .sp-sys-label {
            font-family: 'Belanosima', sans-serif; font-size: 0.38rem;
            color: rgba(0, 212, 255, 0.6); letter-spacing: 2px; margin-bottom: 3px;
        }
        .sp-sys-val {
            font-family: 'Belanosima', sans-serif; font-size: 0.7rem;
            font-weight: 600; color: var(--j-text-bright);
        }
        .sp-sys-bar {
            width: 100%; height: 3px; background: rgba(0, 212, 255, 0.06);
            border-radius: 3px; margin-top: 5px; overflow: hidden;
        }
        .sp-sys-bar-fill {
            height: 100%; border-radius: 3px; transition: width 1s ease;
        }
        .sp-sys-bar-fill.green { background: var(--j-success); }
        .sp-sys-bar-fill.blue { background: var(--j-blue); }
        .sp-sys-bar-fill.orange { background: #ffaa00; }
        .sp-sys-bar-fill.red { background: #ff3366; }

        /* Weather */
        .sp-weather {
            display: flex; align-items: center; gap: 10px;
            flex: 1; align-content: center;
        }
        .sp-weather-icon {
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; color: #ffaa00;
            background: rgba(255, 170, 0, 0.08); border-radius: 10px;
            border: 1px solid rgba(255, 170, 0, 0.12); flex-shrink: 0;
        }
        .sp-weather-temp {
            font-family: 'Belanosima', sans-serif; font-size: 1rem;
            font-weight: 700; color: var(--j-text-bright);
        }
        .sp-weather-meta {
            font-family: 'Belanosima', sans-serif; font-size: 0.45rem;
            color: var(--j-text); line-height: 1.6;
        }
        .sp-weather-meta span { color: var(--j-blue); }

        /* Search */
        .sp-search-row { display: flex; gap: 6px; flex: 1; align-content: center; }
        .sp-search-input {
            flex: 1; background: #0d1630; border: 1px solid rgba(0, 212, 255, 0.08);
            border-radius: 8px; padding: 7px 10px; color: var(--j-text-bright);
            font-family: 'Belanosima', sans-serif; font-size: 0.6rem; outline: none;
        }
        .sp-search-input:focus { border-color: var(--j-blue); }
        .sp-search-input::placeholder { color: var(--j-text-dim); }
        .sp-search-go {
            padding: 7px 14px; background: var(--j-blue); border: none; border-radius: 8px;
            color: #fff; font-family: 'Belanosima', sans-serif; font-size: 0.5rem;
            font-weight: 600; letter-spacing: 1px; cursor: pointer;
        }
        .sp-search-links { display: flex; gap: 5px; flex-wrap: wrap; margin-top: 6px; }
        .sp-link {
            display: flex; align-items: center; gap: 4px; padding: 4px 8px;
            background: #0d1630; border: 1px solid rgba(0, 212, 255, 0.06);
            border-radius: 6px; color: var(--j-text); text-decoration: none;
            font-family: 'Belanosima', sans-serif; font-size: 0.52rem;
        }
        .sp-link:hover { border-color: var(--j-blue); color: var(--j-blue); }

        /* Launcher */
        .sp-apps {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 5px;
            flex: 1; align-content: center;
        }
        .sp-app {
            display: flex; flex-direction: column; align-items: center; gap: 3px;
            padding: 7px 2px; background: #0d1630; border: 1px solid rgba(0, 212, 255, 0.06);
            border-radius: 8px; color: var(--j-text); cursor: pointer;
            font-family: 'Belanosima', sans-serif; font-size: 0.4rem;
            font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;
            transition: all 0.2s ease;
        }
        .sp-app:hover {
            background: rgba(0, 212, 255, 0.06); border-color: var(--j-blue);
            color: var(--j-blue); transform: translateY(-2px);
        }
        .sp-app i { font-size: 0.8rem; }

        /* Welcome */
        .welcome-msg { text-align: center; padding: 60px 40px; animation: welcomeFade 1s ease; }
        @keyframes welcomeFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .welcome-icon { width: 90px; height: 90px; margin: 0 auto 28px; position: relative; display: flex; align-items: center; justify-content: center; }
        .welcome-icon .ring { position: absolute; border-radius: 50%; border: 1.5px solid transparent; }
        .welcome-icon .ring-1 { width: 90px; height: 90px; border-top-color: var(--j-blue); border-bottom-color: var(--j-blue); animation: wSpin 4s linear infinite; }
        .welcome-icon .ring-2 { width: 72px; height: 72px; border-left-color: var(--j-cyan); border-right-color: var(--j-cyan); animation: wSpin 3s linear infinite reverse; }
        .welcome-icon .ring-3 { width: 54px; height: 54px; border-top-color: var(--j-purple); border-bottom-color: var(--j-purple); animation: wSpin 5s linear infinite; }
        .welcome-icon .core {
            width: 22px; height: 22px;
            background: radial-gradient(circle, #fff 0%, var(--j-blue) 40%, transparent 70%);
            border-radius: 50%; animation: wPulse 2s ease-in-out infinite; z-index: 2;
            box-shadow: 0 0 12px var(--j-blue), 0 0 24px var(--j-glow), 0 0 48px rgba(0, 212, 255, 0.2);
        }
        .welcome-icon .orbit { position: absolute; width: 80px; height: 80px; animation: wSpin 8s linear infinite; }
        .welcome-icon .orbit-dot {
            position: absolute; width: 5px; height: 5px; background: var(--j-cyan);
            border-radius: 50%; top: -2px; left: 50%; transform: translateX(-50%);
            box-shadow: 0 0 8px var(--j-cyan);
        }
        .welcome-icon .orbit-2 { width: 65px; height: 65px; animation: wSpin 6s linear infinite reverse; }
        .welcome-icon .orbit-2 .orbit-dot { background: var(--j-purple); box-shadow: 0 0 8px var(--j-purple); width: 4px; height: 4px; }
        @keyframes wSpin { 100% { transform: rotate(360deg); } }
        @keyframes wPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        .welcome-title {
            font-family: 'Belanosima', sans-serif; font-size: 2rem; font-weight: 700;
            background: linear-gradient(135deg, var(--j-text-bright), var(--j-blue), var(--j-cyan));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; margin-bottom: 8px; letter-spacing: 2px;
            animation: titleGlow 3s ease-in-out infinite;
        }
        @keyframes titleGlow {
            0%, 100% { filter: drop-shadow(0 0 8px rgba(0, 212, 255, 0.2)); }
            50% { filter: drop-shadow(0 0 16px rgba(0, 212, 255, 0.4)); }
        }
        .welcome-divider {
            width: 60px; height: 2px; margin: 0 auto 16px;
            background: linear-gradient(90deg, transparent, var(--j-blue), transparent);
            border-radius: 2px; animation: dividerPulse 2s ease-in-out infinite;
        }
        @keyframes dividerPulse {
            0%, 100% { width: 60px; opacity: 0.6; }
            50% { width: 100px; opacity: 1; }
        }
        .welcome-sub {
            font-family: 'Belanosima', sans-serif; font-size: 0.95rem;
            color: var(--j-text); max-width: 420px; margin: 0 auto; line-height: 1.8;
        }
        .welcome-sub .highlight { color: var(--j-blue); font-weight: 600; }
        .welcome-tag {
            display: inline-flex; align-items: center; gap: 6px; margin-top: 20px;
            padding: 6px 16px; background: rgba(0, 212, 255, 0.04);
            border: 1px solid rgba(0, 212, 255, 0.1); border-radius: 20px;
            font-family: 'Belanosima', sans-serif; font-size: 0.7rem;
            color: var(--j-text); letter-spacing: 1px;
            animation: tagFade 1.5s ease 1s both;
        }
        .welcome-tag .dot {
            width: 5px; height: 5px; background: var(--j-success);
            border-radius: 50%; box-shadow: 0 0 6px var(--j-success);
            animation: blink 1.5s ease-in-out infinite;
        }
        @keyframes tagFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        /* Markdown */
        .msg strong, .msg b { color: var(--j-text-bright); font-weight: 600; }
        .msg em, .msg i { font-style: italic; }
        .msg code {
            background: rgba(0, 212, 255, 0.08); border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 5px; padding: 2px 6px;
            font-family: 'JetBrains Mono', monospace; font-size: 0.82em; color: var(--j-cyan);
        }
        .msg pre {
            background: rgba(0, 0, 0, 0.3); border: 1px solid var(--j-border);
            border-radius: 10px; padding: 14px; margin: 10px 0; overflow-x: auto;
            font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; line-height: 1.6;
        }
        .msg pre code { background: transparent; border: none; padding: 0; color: var(--j-text-bright); }
        .msg ul, .msg ol { margin: 8px 0; padding-left: 20px; }
        .msg li { margin: 4px 0; line-height: 1.7; }
        .msg li::marker { color: var(--j-blue); }
        .msg table { width: 100%; border-collapse: collapse; margin: 12px 0; font-size: 0.82rem; }
        .msg th {
            background: rgba(0, 212, 255, 0.06); border: 1px solid rgba(0, 212, 255, 0.1);
            padding: 8px 12px; text-align: left; font-weight: 600;
            color: var(--j-blue); font-family: 'Belanosima', sans-serif; font-size: 0.7rem;
        }
        .msg td { border: 1px solid rgba(0, 212, 255, 0.06); padding: 8px 12px; }
        .msg hr { border: none; border-top: 1px solid var(--j-border); margin: 12px 0; }
        .msg blockquote {
            border-left: 3px solid var(--j-blue); margin: 10px 0; padding: 8px 16px;
            background: rgba(0, 212, 255, 0.03); border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body>
<div class="bg-elegant">
    <div class="bg-elegant-base"></div>
    <div class="bg-streak bg-streak-1"></div>
    <div class="bg-streak bg-streak-2"></div>
    <div class="bg-streak bg-streak-3"></div>
    <div class="bg-streak bg-streak-4"></div>
    <div class="bg-streak bg-streak-5"></div>
    <div class="bg-dots"></div>
</div>

<!-- Top Bar -->
<div class="topbar">
    <div class="topbar-left">
        <a href="/" class="back-btn"><i class="fas fa-arrow-left"></i> Home</a>
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
            <div class="welcome-tag"><div class="dot"></div> Ready for your command</div>
        </div>

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


</div>

<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let chatHistory = [];
    let firstMessage = true;

    function getTimeString() {
        return new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    function parseMd(md) {
        let html = md.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        html = html.replace(/```(\w*)\n?([\s\S]*?)```/g, '<pre><code>$2</code></pre>');
        html = html.replace(/`([^`]+)`/g, '<code>$1</code>');
        html = html.replace(/\*\*\*(.+?)\*\*\*/g, '<strong><em>$1</em></strong>');
        html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        html = html.replace(/__(.+?)__/g, '<strong>$1</strong>');
        html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');
        html = html.replace(/_(.+?)_/g, '<em>$1</em>');
        html = html.replace(/~~(.+?)~~/g, '<s>$1</s>');
        html = html.replace(/^#### (.+)$/gm, '<h4>$1</h4>');
        html = html.replace(/^### (.+)$/gm, '<h3>$1</h3>');
        html = html.replace(/^## (.+)$/gm, '<h2>$1</h2>');
        html = html.replace(/^# (.+)$/gm, '<h1>$1</h1>');
        html = html.replace(/^---+$/gm, '<hr>');
        html = html.replace(/^&gt; (.+)$/gm, '<blockquote>$1</blockquote>');
        html = html.replace(/^\|(.+)\|\s*\n\|[-| :]+\|\s*\n((?:\|.+\|\s*\n?)*)/gm, function(match, header, body) {
            const ths = header.split('|').map(h => h.trim()).filter(h => h).map(h => `<th>${h}</th>`).join('');
            const rows = body.trim().split('\n').map(row => {
                const tds = row.replace(/^\||\|$/g, '').split('|').map(c => c.trim()).map(c => `<td>${c}</td>`).join('');
                return `<tr>${tds}</tr>`;
            }).join('');
            return `<table><thead><tr>${ths}</tr></thead><tbody>${rows}</tbody></table>`;
        });
        html = html.replace(/^(?:[-*+] (.+)(?:\n|$))+/gm, function(block) {
            const items = block.trim().split('\n').map(line => `<li>${line.replace(/^[-*+] /, '')}</li>`).join('');
            return `<ul>${items}</ul>`;
        });
        html = html.replace(/^(?:\d+\. (.+)(?:\n|$))+/gm, function(block) {
            const items = block.trim().split('\n').map(line => `<li>${line.replace(/^\d+\. /, '')}</li>`).join('');
            return `<ol>${items}</ol>`;
        });
        html = html.replace(/\n\n+/g, '</p><p>');
        html = html.replace(/\n/g, '<br>');
        html = '<p>' + html + '</p>';
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
        return html;
    }

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
        d.className = 'msg jarvis'; d.id = 'typing';
        d.innerHTML = `<div class="sender">J.A.R.V.I.S.</div><div class="typing-ind"><span></span><span></span><span></span></div>`;
        m.appendChild(d); m.scrollTop = m.scrollHeight;
    }

    function removeTyping() { const el = document.getElementById('typing'); if (el) el.remove(); }
    function showChat() { if (firstMessage) { document.getElementById('welcomeScreen').style.display = 'none'; document.getElementById('chatMessages').style.display = 'flex'; firstMessage = false; } }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const msg = input.value.trim();
        if (!msg) return;
        showChat(); addMsg(msg, 'user'); input.value = ''; addTyping();
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
        } catch (e) { removeTyping(); addMsg('Connection error, sir.', 'jarvis'); }
    }

    function sendQuick(t) { document.getElementById('chatInput').value = t; sendMessage(); }
    document.getElementById('chatInput').addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });

    let rec = null, listening = false;
    function toggleVoice() { listening ? stopVoice() : startVoice(); }
    function startVoice() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) { showChat(); addMsg('Voice not supported. Use Chrome.', 'jarvis'); return; }
        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        rec = new SR(); rec.continuous = false; rec.interimResults = false; rec.lang = 'en-US';
        rec.onstart = () => { listening = true; document.getElementById('voiceBtn').classList.add('active'); document.getElementById('statusText').textContent = 'LISTENING...'; };
        rec.onresult = (e) => { document.getElementById('chatInput').value = e.results[0][0].transcript; sendMessage(); };
        rec.onerror = () => { stopVoice(); showChat(); addMsg('Could not hear you, sir.', 'jarvis'); };
        rec.onend = () => stopVoice(); rec.start();
    }
    function stopVoice() {
        listening = false; document.getElementById('voiceBtn').classList.remove('active');
        document.getElementById('statusText').textContent = 'ALL SYSTEMS ONLINE';
        if (rec) { rec.stop(); rec = null; }
    }

    async function loadWeather(city = 'Khulna') {
        try {
            const r = await fetch('/api/weather', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: JSON.stringify({ city }) });
            const d = await r.json();
            if (d.success) {
                const icons = { '01d':'fa-sun','01n':'fa-moon','02d':'fa-cloud-sun','02n':'fa-cloud-moon','03d':'fa-cloud','03n':'fa-cloud','09d':'fa-cloud-rain','09n':'fa-cloud-rain','10d':'fa-cloud-sun-rain','11d':'fa-bolt','13d':'fa-snowflake','50d':'fa-smog' };
                document.getElementById('weatherCity').textContent = d.city.toUpperCase();
                document.getElementById('weatherContent').innerHTML = `<div class="sp-weather-icon"><i class="fas ${icons[d.icon]||'fa-cloud'}"></i></div><div><div class="sp-weather-temp">${d.temp}°C</div><div class="sp-weather-meta"><span>${d.city}, ${d.country}</span><br>Feels ${d.feels_like}°C · ${d.humidity}%<br>${d.description}</div></div>`;
            }
        } catch (e) {}
    }

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
                document.getElementById('memBar').className = 'sp-sys-bar-fill ' + (i.memory.percent > 80 ? 'red' : i.memory.percent > 60 ? 'orange' : 'green');
                document.getElementById('diskBar').className = 'sp-sys-bar-fill ' + (i.disk.percent > 90 ? 'red' : i.disk.percent > 70 ? 'orange' : 'blue');
            }
        } catch (e) {}
    }

    async function performSearch() {
        const q = document.getElementById('searchInput').value.trim();
        if (!q) return;
        try {
            const r = await fetch('/api/search', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: JSON.stringify({ query: q }) });
            const d = await r.json();
            if (d.success) {
                document.getElementById('searchLinks').innerHTML = `<a href="${d.google_url}" target="_blank" class="sp-link"><i class="fab fa-google"></i> Google</a><a href="${d.youtube_url}" target="_blank" class="sp-link"><i class="fab fa-youtube"></i> YouTube</a><a href="${d.github_url}" target="_blank" class="sp-link"><i class="fab fa-github"></i> GitHub</a>`;
            }
        } catch (e) {}
    }
    document.getElementById('searchInput').addEventListener('keypress', e => { if (e.key === 'Enter') performSearch(); });

    async function openApp(name) {
        showChat(); addMsg(`Opening ${name}...`, 'user');
        try {
            const r = await fetch('/api/open-app', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: JSON.stringify({ app: name }) });
            const d = await r.json(); addMsg(d.message, 'jarvis');
        } catch (e) { addMsg('Unable to launch app.', 'jarvis'); }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadWeather(); loadSystemInfo(); setInterval(loadSystemInfo, 30000);
        document.getElementById('chatInput').focus();
    });
</script>
</body>
</html>
