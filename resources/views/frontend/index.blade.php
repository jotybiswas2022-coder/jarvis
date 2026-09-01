<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>J.A.R.V.I.S. — Personal AI Assistant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.82/build/spline-viewer.js"></script>
    <style>
        :root {
            --j-blue: #00d4ff;
            --j-blue-dark: #0066aa;
            --j-cyan: #00fff2;
            --j-glow: rgba(0, 212, 255, 0.4);
            --j-glow-strong: rgba(0, 212, 255, 0.7);
            --j-bg: #050810;
            --j-bg-light: #0a0f1e;
            --j-card: rgba(8, 15, 35, 0.7);
            --j-card-hover: rgba(12, 22, 50, 0.85);
            --j-border: rgba(0, 212, 255, 0.12);
            --j-border-hover: rgba(0, 212, 255, 0.3);
            --j-text: #8899b0;
            --j-text-bright: #f0f4ff;
            --j-text-dim: #4a5568;
            --j-success: #00ff88;
            --j-warning: #ffaa00;
            --j-danger: #ff3366;
            --j-purple: #a855f7;
            --j-pink: #ec4899;
            --radius: 20px;
            --radius-sm: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--j-bg);
            color: var(--j-text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ========== ANIMATED BACKGROUND ========== */
        .bg-grid {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background:
                linear-gradient(rgba(0, 212, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.02) 1px, transparent 1px);
            background-size: 60px 60px;
            z-index: 0;
        }

        .bg-gradient {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(0, 212, 255, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 50%, rgba(168, 85, 247, 0.04) 0%, transparent 50%),
                radial-gradient(ellipse 60% 40% at 20% 80%, rgba(0, 255, 242, 0.03) 0%, transparent 50%);
            z-index: 0;
        }

        .particles {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 2px; height: 2px;
            background: var(--j-blue);
            border-radius: 50%;
            animation: pFloat 20s infinite linear;
            opacity: 0;
        }

        @keyframes pFloat {
            0% { transform: translateY(100vh); opacity: 0; }
            5% { opacity: 0.6; }
            95% { opacity: 0.6; }
            100% { transform: translateY(-10vh); opacity: 0; }
        }

        /* ========== MAIN CONTAINER ========== */
        .jarvis-app {
            position: relative;
            z-index: 1;
        }

        /* ========== HERO SECTION ========== */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        /* Top Nav Bar */
        .top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            position: relative;
            z-index: 10;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-logo {
            width: 48px; height: 48px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .arc-ring {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid transparent;
        }

        .arc-ring-1 {
            width: 48px; height: 48px;
            border-top-color: var(--j-blue);
            border-bottom-color: var(--j-blue);
            animation: arcSpin 3s linear infinite;
        }

        .arc-ring-2 {
            width: 38px; height: 38px;
            border-left-color: var(--j-cyan);
            border-right-color: var(--j-cyan);
            animation: arcSpin 2.5s linear infinite reverse;
        }

        .arc-ring-3 {
            width: 28px; height: 28px;
            border-top-color: var(--j-purple);
            border-bottom-color: var(--j-purple);
            animation: arcSpin 4s linear infinite;
        }

        .arc-ring-4 {
            width: 20px; height: 20px;
            border-left-color: var(--j-pink);
            border-right-color: var(--j-pink);
            animation: arcSpin 2s linear infinite reverse;
        }

        .arc-core {
            width: 10px; height: 10px;
            background: radial-gradient(circle, #fff 0%, var(--j-blue) 40%, transparent 70%);
            border-radius: 50%;
            animation: arcPulse 1.5s ease-in-out infinite;
            box-shadow:
                0 0 8px var(--j-blue),
                0 0 16px var(--j-glow),
                0 0 30px rgba(0, 212, 255, 0.3);
        }

        .arc-orbit {
            position: absolute;
            width: 44px; height: 44px;
            animation: arcSpin 6s linear infinite;
        }

        .arc-orbit-dot {
            position: absolute;
            width: 4px; height: 4px;
            background: var(--j-cyan);
            border-radius: 50%;
            top: -2px; left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 0 6px var(--j-cyan);
        }

        .arc-orbit-2 {
            width: 36px; height: 36px;
            animation: arcSpin 4s linear infinite reverse;
        }

        .arc-orbit-2 .arc-orbit-dot {
            background: var(--j-purple);
            box-shadow: 0 0 6px var(--j-purple);
            width: 3px; height: 3px;
        }

        .arc-glow {
            position: absolute;
            width: 60px; height: 60px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: arcGlow 2s ease-in-out infinite;
        }

        @keyframes arcSpin { 100% { transform: rotate(360deg); } }
        @keyframes arcPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 8px var(--j-blue), 0 0 16px var(--j-glow); }
            50% { transform: scale(1.2); box-shadow: 0 0 12px var(--j-blue), 0 0 24px var(--j-glow), 0 0 40px rgba(0, 212, 255, 0.4); }
        }
        @keyframes arcGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.15); }
        }

        .nav-name {
            font-family: 'Audiowide', 'Orbitron', 'Rajdhani', cursive;
            font-size: 1.1rem;
            font-weight: 400;
            color: var(--j-text-bright);
            letter-spacing: 3px;
        }

        .nav-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: var(--j-success);
            letter-spacing: 1px;
        }

        .nav-status .dot {
            width: 6px; height: 6px;
            background: var(--j-success);
            border-radius: 50%;
            animation: statusBlink 1.5s ease-in-out infinite;
            box-shadow: 0 0 8px var(--j-success);
        }

        @keyframes statusBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Hero Content */
        .hero-content {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 0 60px;
            gap: 40px;
        }

        .hero-left {
            flex: 1;
            max-width: 550px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.08) 0%, rgba(168, 85, 247, 0.06) 100%);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-radius: 40px;
            padding: 10px 22px;
            margin-bottom: 28px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: var(--j-blue);
            letter-spacing: 3px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
            animation: badgeFadeIn 0.8s ease 0.2s both;
        }

        .hero-badge::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.1), transparent);
            animation: badgeShine 3s ease-in-out infinite;
        }

        @keyframes badgeFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes badgeShine {
            0% { left: -100%; }
            50%, 100% { left: 100%; }
        }

        .hero-badge .live {
            width: 7px; height: 7px;
            background: var(--j-success);
            border-radius: 50%;
            animation: livePulse 2s ease-in-out infinite;
            box-shadow: 0 0 8px var(--j-success);
        }

        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.4); }
        }

        .hero-title {
            font-family: 'Audiowide', 'Orbitron', 'Rajdhani', cursive;
            font-size: 4.2rem;
            font-weight: 400;
            color: var(--j-text-bright);
            line-height: 1.05;
            margin-bottom: 24px;
            animation: titleReveal 1s ease 0.4s both;
        }

        @keyframes titleReveal {
            from { opacity: 0; transform: translateY(30px); filter: blur(8px); }
            to { opacity: 1; transform: translateY(0); filter: blur(0); }
        }

        .hero-title .line {
            display: block;
            overflow: hidden;
        }

        .hero-title .gradient {
            background: linear-gradient(135deg, var(--j-blue) 0%, var(--j-cyan) 40%, var(--j-purple) 80%, var(--j-pink) 100%);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientFlow 4s ease infinite;
            position: relative;
        }

        .hero-title .gradient::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--j-blue), var(--j-cyan), var(--j-purple));
            border-radius: 2px;
            animation: lineGrow 1s ease 1s both;
        }

        @keyframes gradientFlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes lineGrow {
            from { width: 0; }
            to { width: 100%; }
        }

        .hero-subtitle {
            font-size: 1.05rem;
            color: var(--j-text);
            line-height: 1.8;
            margin-bottom: 36px;
            max-width: 500px;
            animation: subtitleFade 1s ease 0.6s both;
        }

        .hero-subtitle .highlight {
            color: var(--j-blue);
            font-weight: 500;
        }

        @keyframes subtitleFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            animation: actionsFade 1s ease 0.8s both;
        }

        @keyframes actionsFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: linear-gradient(135deg, var(--j-blue) 0%, var(--j-blue-dark) 100%);
            border: none;
            border-radius: 14px;
            color: white;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(0, 212, 255, 0.3), inset 0 1px 0 rgba(255,255,255,0.1);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before { left: 100%; }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 35px rgba(0, 212, 255, 0.45), inset 0 1px 0 rgba(255,255,255,0.15);
        }

        .btn-primary:active {
            transform: translateY(-1px) scale(0.98);
        }

        .btn-primary i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .btn-primary:hover i {
            transform: scale(1.15);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: rgba(0, 212, 255, 0.04);
            border: 1.5px solid rgba(0, 212, 255, 0.15);
            border-radius: 14px;
            color: var(--j-text-bright);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-secondary::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.08) 0%, rgba(168, 85, 247, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .btn-secondary:hover::before { opacity: 1; }

        .btn-secondary:hover {
            border-color: var(--j-blue);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 212, 255, 0.15);
        }

        .btn-secondary i {
            transition: transform 0.3s ease;
        }

        .btn-secondary:hover i {
            transform: rotate(15deg) scale(1.1);
        }

        .hero-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .model-wrapper {
            width: 100%;
            max-width: 600px;
            height: 500px;
            position: relative;
        }

        .model-glow {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(60px);
            animation: glowPulse 4s ease-in-out infinite;
        }

        @keyframes glowPulse {
            0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.1); }
        }

        .model-wrapper spline-viewer {
            width: 100%; height: 100%;
            position: relative;
            z-index: 2;
        }

        .hero-time {
            position: absolute;
            bottom: 30px;
            left: 60px;
            display: flex;
            align-items: baseline;
            gap: 16px;
        }

        .hero-time .time {
            font-family: 'Audiowide', 'Orbitron', 'Rajdhani', cursive;
            font-size: 2rem;
            font-weight: 700;
            color: var(--j-text-bright);
            text-shadow: 0 0 15px var(--j-glow);
        }

        .hero-time .date {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            color: var(--j-blue);
            letter-spacing: 2px;
        }

        /* ========== FEATURES SECTION ========== */
        .features {
            padding: 60px 60px 80px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 212, 255, 0.06);
            border: 1px solid rgba(0, 212, 255, 0.12);
            border-radius: 30px;
            padding: 6px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            color: var(--j-blue);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Audiowide', cursive;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--j-text-bright);
            margin-bottom: 12px;
        }

        .section-desc {
            color: var(--j-text);
            font-size: 1rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        @media (max-width: 1100px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 700px) {
            .features-grid { grid-template-columns: 1fr; }
            .hero-content { flex-direction: column; padding: 0 24px; }
            .hero-left { max-width: 100%; text-align: center; }
            .hero-title { font-size: 2.2rem; }
            .hero-subtitle { margin: 0 auto 32px; }
            .hero-actions { justify-content: center; }
            .hero-right { width: 100%; }
            .model-wrapper { height: 350px; max-width: 100%; }
            .hero-time { left: 24px; bottom: 20px; }
            .hero-time .time { font-size: 1.2rem; }
            .features { padding: 40px 24px 60px; }
            .top-nav { padding: 16px 24px; }
        }

        /* Feature Card */
        .feature-card {
            background: var(--j-card);
            border: 1px solid var(--j-border);
            border-radius: var(--radius);
            padding: 28px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--j-blue), transparent);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-card:hover {
            border-color: var(--j-border-hover);
            background: var(--j-card-hover);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(0, 212, 255, 0.05);
        }

        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 18px;
            transition: all 0.4s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }

        .feature-icon.blue { background: rgba(0, 212, 255, 0.1); color: var(--j-blue); }
        .feature-icon.green { background: rgba(0, 255, 136, 0.1); color: var(--j-success); }
        .feature-icon.purple { background: rgba(168, 85, 247, 0.1); color: var(--j-purple); }
        .feature-icon.pink { background: rgba(236, 72, 153, 0.1); color: var(--j-pink); }
        .feature-icon.orange { background: rgba(255, 170, 0, 0.1); color: var(--j-warning); }
        .feature-icon.cyan { background: rgba(0, 255, 242, 0.1); color: var(--j-cyan); }

        .feature-title {
            font-family: 'Inter', sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--j-text-bright);
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 0.85rem;
            color: var(--j-text);
            line-height: 1.6;
        }

        /* ========== CHAT + SYSTEM SECTION ========== */
        .chat-section {
            padding: 0 60px 80px;
        }

        .chat-layout {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
        }

        @media (max-width: 900px) {
            .chat-section { padding: 0 24px 60px; }
            .chat-layout { grid-template-columns: 1fr; }
        }

        /* Glass Card */
        .glass-card {
            background: var(--j-card);
            border: 1px solid var(--j-border);
            border-radius: var(--radius);
            padding: 28px;
            backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--j-blue), transparent);
            animation: scanline 4s linear infinite;
        }

        @keyframes scanline {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-label i {
            font-size: 0.9rem;
            color: var(--j-blue);
        }

        .card-label span {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--j-blue);
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .card-badge {
            padding: 4px 12px;
            background: rgba(0, 255, 136, 0.08);
            border: 1px solid rgba(0, 255, 136, 0.15);
            border-radius: 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            color: var(--j-success);
            letter-spacing: 1px;
        }

        /* Chat */
        .chat-messages {
            height: 380px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding-right: 8px;
            margin-bottom: 16px;
        }

        .chat-messages::-webkit-scrollbar { width: 3px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: var(--j-blue-dark); border-radius: 3px; }

        .chat-msg {
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 88%;
            animation: msgSlide 0.3s ease;
        }

        @keyframes msgSlide {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-msg.jarvis {
            background: rgba(0, 212, 255, 0.06);
            border: 1px solid rgba(0, 212, 255, 0.1);
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }

        .chat-msg.jarvis .sender {
            color: var(--j-blue);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .chat-msg.user {
            background: rgba(168, 85, 247, 0.08);
            border: 1px solid rgba(168, 85, 247, 0.12);
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }

        .chat-msg.user .sender {
            color: var(--j-purple);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 1px;
            margin-bottom: 6px;
            text-align: right;
        }

        .quick-row {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .q-btn {
            padding: 6px 14px;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid rgba(0, 212, 255, 0.1);
            border-radius: 20px;
            color: var(--j-text);
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .q-btn:hover {
            background: rgba(0, 212, 255, 0.12);
            border-color: var(--j-blue);
            color: var(--j-blue);
        }

        .chat-input-row {
            display: flex;
            gap: 10px;
        }

        .chat-input {
            flex: 1;
            background: rgba(0, 212, 255, 0.04);
            border: 1px solid var(--j-border);
            border-radius: var(--radius-sm);
            padding: 14px 18px;
            color: var(--j-text-bright);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .chat-input:focus {
            border-color: var(--j-blue);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.1);
        }

        .chat-input::placeholder { color: var(--j-text-dim); }

        .icon-btn {
            width: 48px; height: 48px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--j-border);
            background: rgba(0, 212, 255, 0.06);
            color: var(--j-blue);
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn:hover {
            background: var(--j-blue);
            color: var(--j-bg);
            box-shadow: 0 0 20px var(--j-glow);
        }

        .icon-btn.active {
            background: var(--j-danger);
            border-color: var(--j-danger);
            color: white;
            animation: voicePulse 1s ease-in-out infinite;
        }

        @keyframes voicePulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 51, 102, 0.4); }
            50% { box-shadow: 0 0 25px rgba(255, 51, 102, 0.7); }
        }

        /* System + Weather Cards */
        .side-cards {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sys-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .sys-item {
            background: rgba(0, 212, 255, 0.03);
            border: 1px solid rgba(0, 212, 255, 0.06);
            border-radius: var(--radius-sm);
            padding: 16px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .sys-item:hover {
            border-color: rgba(0, 212, 255, 0.15);
            background: rgba(0, 212, 255, 0.06);
        }

        .sys-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            color: var(--j-blue);
            letter-spacing: 2px;
            margin-bottom: 6px;
        }

        .sys-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--j-text-bright);
        }

        .sys-bar {
            width: 100%;
            height: 3px;
            background: rgba(0, 212, 255, 0.08);
            border-radius: 3px;
            margin-top: 8px;
            overflow: hidden;
        }

        .sys-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 1s ease;
        }

        .sys-bar-fill.green { background: var(--j-success); }
        .sys-bar-fill.blue { background: var(--j-blue); }
        .sys-bar-fill.orange { background: var(--j-warning); }
        .sys-bar-fill.red { background: var(--j-danger); }

        /* Weather */
        .weather-row {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .weather-icon-wrap {
            font-size: 2.5rem;
            color: var(--j-warning);
            text-shadow: 0 0 15px rgba(255, 170, 0, 0.4);
        }

        .weather-temp {
            font-family: 'Audiowide', 'Orbitron', 'Rajdhani', cursive;
            font-size: 2rem;
            font-weight: 700;
            color: var(--j-text-bright);
        }

        .weather-meta {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: var(--j-text);
            line-height: 1.8;
        }

        .weather-meta span { color: var(--j-blue); }

        /* ========== SEARCH + APPS SECTION ========== */
        .bottom-section {
            padding: 0 60px 80px;
        }

        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 900px) {
            .bottom-section { padding: 0 24px 60px; }
            .bottom-grid { grid-template-columns: 1fr; }
        }

        .search-row {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }

        .search-input {
            flex: 1;
            background: rgba(0, 212, 255, 0.04);
            border: 1px solid var(--j-border);
            border-radius: var(--radius-sm);
            padding: 12px 16px;
            color: var(--j-text-bright);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--j-blue);
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.1);
        }

        .search-input::placeholder { color: var(--j-text-dim); }

        .search-go {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--j-blue) 0%, var(--j-blue-dark) 100%);
            border: none;
            border-radius: var(--radius-sm);
            color: white;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-go:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px var(--j-glow);
        }

        .search-links {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .s-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: rgba(0, 212, 255, 0.04);
            border: 1px solid var(--j-border);
            border-radius: var(--radius-sm);
            color: var(--j-text);
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .s-link:hover {
            border-color: var(--j-blue);
            color: var(--j-blue);
            transform: translateY(-2px);
        }

        .apps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        @media (max-width: 500px) {
            .apps-grid { grid-template-columns: repeat(3, 1fr); }
        }

        .app-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 18px 8px;
            background: rgba(0, 212, 255, 0.03);
            border: 1px solid var(--j-border);
            border-radius: var(--radius-sm);
            color: var(--j-text);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .app-item:hover {
            background: rgba(0, 212, 255, 0.1);
            border-color: var(--j-blue);
            color: var(--j-blue);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 212, 255, 0.15);
        }

        .app-item i { font-size: 1.4rem; }

        /* ========== FOOTER ========== */
        .footer {
            text-align: center;
            padding: 30px 40px;
            border-top: 1px solid var(--j-border);
        }

        .footer p {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            color: var(--j-text-dim);
            letter-spacing: 2px;
        }

        /* ========== TYPING INDICATOR ========== */
        .typing-ind {
            display: inline-flex;
            gap: 4px;
            padding: 8px 0;
        }

        .typing-ind span {
            width: 5px; height: 5px;
            background: var(--j-blue);
            border-radius: 50%;
            animation: typingDot 1.4s infinite ease-in-out;
        }

        .typing-ind span:nth-child(2) { animation-delay: 0.2s; }
        .typing-ind span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typingDot {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.3; }
            30% { transform: translateY(-6px); opacity: 1; }
        }

        /* ========== CORNER DECORATIONS ========== */
        .corner {
            position: fixed;
            width: 50px; height: 50px;
            z-index: 2;
            pointer-events: none;
        }

        .c-tl { top: 8px; left: 8px; border-top: 1.5px solid rgba(0,212,255,0.3); border-left: 1.5px solid rgba(0,212,255,0.3); }
        .c-tr { top: 8px; right: 8px; border-top: 1.5px solid rgba(0,212,255,0.3); border-right: 1.5px solid rgba(0,212,255,0.3); }
        .c-bl { bottom: 8px; left: 8px; border-bottom: 1.5px solid rgba(0,212,255,0.3); border-left: 1.5px solid rgba(0,212,255,0.3); }
        .c-br { bottom: 8px; right: 8px; border-bottom: 1.5px solid rgba(0,212,255,0.3); border-right: 1.5px solid rgba(0,212,255,0.3); }
    </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-gradient"></div>
<div class="particles" id="particles"></div>

<div class="corner c-tl"></div>
<div class="corner c-tr"></div>
<div class="corner c-bl"></div>
<div class="corner c-br"></div>

<div class="jarvis-app">

    <!-- ========== HERO ========== -->
    <section class="hero">
        <nav class="top-nav">
            <div class="nav-brand">
                <div class="nav-logo">
                    <div class="arc-glow"></div>
                    <div class="arc-ring arc-ring-1"></div>
                    <div class="arc-ring arc-ring-2"></div>
                    <div class="arc-ring arc-ring-3"></div>
                    <div class="arc-ring arc-ring-4"></div>
                    <div class="arc-orbit"><div class="arc-orbit-dot"></div></div>
                    <div class="arc-orbit-2"><div class="arc-orbit-dot"></div></div>
                    <div class="arc-core"></div>
                </div>
                <span class="nav-name">J.A.R.V.I.S.</span>
            </div>
            <div class="nav-status">
                <div class="dot"></div>
                <span id="statusText">ALL SYSTEMS ONLINE</span>
            </div>
        </nav>

        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge">
                    <div class="live"></div>
                    NEURAL NETWORK ACTIVE
                </div>
                <h1 class="hero-title">
                    <span class="line">Your Personal</span>
                    <span class="line"><span class="gradient">AI Assistant</span></span>
                </h1>
                <p class="hero-subtitle">
                    <span class="highlight">Just A Rather Very Intelligent System.</span> Built with advanced AI to help you with anything — from conversations to system control.
                </p>
                <div class="hero-actions">
                    <a href="/chat" class="btn-primary">
                        <i class="fas fa-comments"></i> Start Chat
                    </a>
                    <a href="#features" class="btn-secondary">
                        <i class="fas fa-compass"></i> Explore Features
                    </a>
                </div>
            </div>
            <div class="hero-right">
                <div class="model-wrapper">
                    <div class="model-glow"></div>
                    <spline-viewer url="https://prod.spline.design/kZDDjO5HuC9GJUM2/scene.splinecode"></spline-viewer>
                </div>
            </div>
        </div>

        <div class="hero-time">
            <span class="time" id="heroTime">00:00:00</span>
            <span class="date" id="heroDate">LOADING...</span>
        </div>
    </section>



    <!-- ========== FEATURES ========== -->
    <section class="features" id="features">
        <div class="section-header">
            <div class="section-tag"><i class="fas fa-bolt"></i> CAPABILITIES</div>
            <h2 class="section-title">What I Can Do</h2>
            <p class="section-desc">Powered by advanced AI with real-time system integration</p>
        </div>
        <div class="features-grid">
            <div class="feature-card" onclick="document.getElementById('chat').scrollIntoView({behavior:'smooth'})">
                <div class="feature-icon blue"><i class="fas fa-brain"></i></div>
                <div class="feature-title">AI Chat</div>
                <div class="feature-desc">Intelligent conversations powered by Groq AI. Ask anything, get smart responses instantly.</div>
            </div>
            <div class="feature-card" onclick="document.getElementById('chat').scrollIntoView({behavior:'smooth'})">
                <div class="feature-icon purple"><i class="fas fa-microphone"></i></div>
                <div class="feature-title">Voice Commands</div>
                <div class="feature-desc">Speak naturally and I'll understand. Hands-free control at your command.</div>
            </div>
            <div class="feature-card" onclick="loadWeather('Dhaka')">
                <div class="feature-icon orange"><i class="fas fa-cloud-sun"></i></div>
                <div class="feature-title">Weather Station</div>
                <div class="feature-desc">Real-time weather data for any city worldwide. Temperature, humidity, wind speed.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon green"><i class="fas fa-microchip"></i></div>
                <div class="feature-title">System Monitor</div>
                <div class="feature-desc">Live CPU, memory, and disk usage. Keep your system健康 in check.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon cyan"><i class="fas fa-search"></i></div>
                <div class="feature-title">Web Search</div>
                <div class="feature-desc">Search Google, YouTube, and GitHub instantly from the dashboard.</div>
            </div>
            <div class="feature-card">
                <div class="feature-icon pink"><i class="fas fa-rocket"></i></div>
                <div class="feature-title">App Launcher</div>
                <div class="feature-desc">Launch any application with a single click. Chrome, VS Code, Terminal, and more.</div>
            </div>
        </div>
    </section>

    <!-- ========== SYSTEM + WEATHER ========== -->
    <section class="chat-section">
        <div class="chat-layout">
            <div class="side-cards">
                <!-- System Monitor -->
                <div class="glass-card">
                    <div class="card-header">
                        <div class="card-label">
                            <i class="fas fa-microchip"></i>
                            <span>SYSTEM</span>
                        </div>
                        <div class="card-badge">LIVE</div>
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
                            <div class="sys-val" id="sysUptime" style="font-size:0.75rem;">--</div>
                        </div>
                    </div>
                </div>

                <!-- Weather -->
                <div class="glass-card">
                    <div class="card-header">
                        <div class="card-label">
                            <i class="fas fa-cloud-sun"></i>
                            <span>WEATHER</span>
                        </div>
                        <div class="card-badge" id="weatherCity">--</div>
                    </div>
                    <div class="weather-row" id="weatherContent">
                        <div class="weather-icon-wrap"><i class="fas fa-cloud"></i></div>
                        <div>
                            <div class="weather-temp">--°C</div>
                            <div class="weather-meta">Loading...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== SEARCH + APPS ========== -->
    <section class="bottom-section">
        <div class="bottom-grid">
            <!-- Search -->
            <div class="glass-card">
                <div class="card-header">
                    <div class="card-label">
                        <i class="fas fa-search"></i>
                        <span>WEB SEARCH</span>
                    </div>
                </div>
                <div class="search-row">
                    <input type="text" class="search-input" id="searchInput" placeholder="Search the web...">
                    <button class="search-go" onclick="performSearch()">SEARCH</button>
                </div>
                <div class="search-links" id="searchLinks"></div>
            </div>

            <!-- App Launcher -->
            <div class="glass-card">
                <div class="card-header">
                    <div class="card-label">
                        <i class="fas fa-rocket"></i>
                        <span>LAUNCHER</span>
                    </div>
                </div>
                <div class="apps-grid">
                    <button class="app-item" onclick="openApp('chrome')">
                        <i class="fab fa-chrome"></i> Chrome
                    </button>
                    <button class="app-item" onclick="openApp('vscode')">
                        <i class="fas fa-code"></i> VS Code
                    </button>
                    <button class="app-item" onclick="openApp('terminal')">
                        <i class="fas fa-terminal"></i> Terminal
                    </button>
                    <button class="app-item" onclick="openApp('notepad')">
                        <i class="fas fa-file-alt"></i> Notepad
                    </button>
                    <button class="app-item" onclick="openApp('calculator')">
                        <i class="fas fa-calculator"></i> Calc
                    </button>
                    <button class="app-item" onclick="openApp('explorer')">
                        <i class="fas fa-folder"></i> Files
                    </button>
                    <button class="app-item" onclick="openApp('spotify')">
                        <i class="fab fa-spotify"></i> Spotify
                    </button>
                    <button class="app-item" onclick="openApp('discord')">
                        <i class="fab fa-discord"></i> Discord
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>J.A.R.V.I.S. — Just A Rather Very Intelligent System — Powered by Groq AI</p>
    </footer>

</div>

<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ========== PARTICLES ==========
    (function() {
        const c = document.getElementById('particles');
        for (let i = 0; i < 25; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + '%';
            p.style.animationDuration = (Math.random() * 20 + 12) + 's';
            p.style.animationDelay = (Math.random() * 15) + 's';
            p.style.width = p.style.height = (Math.random() * 2.5 + 0.5) + 'px';
            c.appendChild(p);
        }
    })();

    // ========== CLOCK ==========
    function updateClock() {
        const now = new Date();            const t = now.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const d = now.toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }).toUpperCase();
        document.getElementById('heroTime').textContent = t;
        document.getElementById('heroDate').textContent = d;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ========== CHAT ==========
    function addMessage(text, type) {
        const m = document.getElementById('chatMessages');
        const d = document.createElement('div');
        d.className = `chat-msg ${type}`;
        d.innerHTML = type === 'jarvis'
            ? `<div class="sender">J.A.R.V.I.S.</div>${text}`
            : `<div class="sender">YOU</div>${text}`;
        m.appendChild(d);
        m.scrollTop = m.scrollHeight;
    }

    function addTyping() {
        const m = document.getElementById('chatMessages');
        const d = document.createElement('div');
        d.className = 'chat-msg jarvis';
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
        addMessage(msg, 'user');
        input.value = '';
        addTyping();
        try {
            const r = await fetch('/api/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({ message: msg })
            });
            const d = await r.json();
            removeTyping();
            addMessage(d.success ? d.reply : 'Malfunction detected, sir.', 'jarvis');
        } catch (e) {
            removeTyping();
            addMessage('Connection error, sir.', 'jarvis');
        }
    }

    function sendQuick(t) { document.getElementById('chatInput').value = t; sendMessage(); }
    document.getElementById('chatInput').addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
    document.getElementById('searchInput').addEventListener('keypress', e => { if (e.key === 'Enter') performSearch(); });

    // ========== VOICE ==========
    let recognition = null, listening = false;
    function toggleVoice() { listening ? stopVoice() : startVoice(); }

    function startVoice() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            addMessage('Voice not supported. Use Chrome.', 'jarvis'); return;
        }
        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
        recognition = new SR();
        recognition.continuous = false;
        recognition.interimResults = false;
        recognition.lang = 'en-US';
        recognition.onstart = () => { listening = true; document.getElementById('voiceBtn').classList.add('active'); document.getElementById('statusText').textContent = 'LISTENING...'; };
        recognition.onresult = (e) => { document.getElementById('chatInput').value = e.results[0][0].transcript; sendMessage(); };
        recognition.onerror = () => { stopVoice(); addMessage('Could not hear you, sir.', 'jarvis'); };
        recognition.onend = () => stopVoice();
        recognition.start();
    }

    function stopVoice() {
        listening = false;
        document.getElementById('voiceBtn').classList.remove('active');
        document.getElementById('statusText').textContent = 'ALL SYSTEMS ONLINE';
        if (recognition) { recognition.stop(); recognition = null; }
    }

    // ========== WEATHER ==========
    async function loadWeather(city = 'Dhaka') {
        try {
            const r = await fetch('/api/weather', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({ city })
            });
            const d = await r.json();
            if (d.success) {
                const icons = { '01d':'fa-sun','01n':'fa-moon','02d':'fa-cloud-sun','02n':'fa-cloud-moon','03d':'fa-cloud','03n':'fa-cloud','04d':'fa-cloud','04n':'fa-cloud','09d':'fa-cloud-rain','09n':'fa-cloud-rain','10d':'fa-cloud-sun-rain','10n':'fa-cloud-moon-rain','11d':'fa-bolt','11n':'fa-bolt','13d':'fa-snowflake','13n':'fa-snowflake','50d':'fa-smog','50n':'fa-smog' };
                document.getElementById('weatherCity').textContent = d.city.toUpperCase();
                document.getElementById('weatherContent').innerHTML = `
                    <div class="weather-icon-wrap"><i class="fas ${icons[d.icon]||'fa-cloud'}"></i></div>
                    <div>
                        <div class="weather-temp">${d.temp}°C</div>
                        <div class="weather-meta">
                            <span>${d.city}, ${d.country}</span><br>
                            Feels ${d.feels_like}°C · ${d.humidity}% · ${d.wind_speed}m/s<br>
                            ${d.description}
                        </div>
                    </div>`;
            }
        } catch (e) {}
    }

    // ========== SYSTEM INFO ==========
    async function loadSystemInfo() {
        try {
            const r = await fetch('/api/system-info', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
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
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
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

    // ========== APP LAUNCHER ==========
    async function openApp(name) {
        addMessage(`Opening ${name}...`, 'user');
        try {
            const r = await fetch('/api/open-app', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: JSON.stringify({ app: name })
            });
            const d = await r.json();
            addMessage(d.message, 'jarvis');
        } catch (e) {
            addMessage('Unable to launch app.', 'jarvis');
        }
    }

    // ========== INIT ==========
    document.addEventListener('DOMContentLoaded', () => {
        loadWeather();
        loadSystemInfo();
        setInterval(loadSystemInfo, 30000);
    });
</script>

</body>
</html>
