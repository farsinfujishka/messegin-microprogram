<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Queue Manager - Admin Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        :root {
            --sage: #6b8f71;
            --sage-dark: #4a6b50;
            --sage-mid: #578060;
            --sage-light: #a8c5ad;
            --leaf: #3d7a46;
            --forest: #2a5230;
            --text-dark: #1a2b1e;
            --text-mid: #3d5443;
            --text-soft: #6e8a74;
            --text-muted: #9ab09f;
            --glass: rgba(255, 255, 255, 0.58);
            --glass-light: rgba(255, 255, 255, 0.74);
            --glass-border: rgba(255, 255, 255, 0.82);
            --glass-bsoft: rgba(168, 197, 173, 0.38);
            --success-bg: rgba(130, 195, 145, 0.26);
            --success-fg: #1f6632;
            --warn-bg: rgba(215, 170, 80, 0.26);
            --warn-fg: #7a5208;
            --danger-bg: rgba(210, 85, 85, 0.20);
            --danger-fg: #8a1e1e;
            --info-bg: rgba(85, 145, 210, 0.20);
            --info-fg: #164e82;
            --neutral-bg: rgba(155, 188, 162, 0.24);
            --neutral-fg: #3e5a44;
            --shadow-xs: 0 2px 8px rgba(30, 45, 34, 0.08);
            --shadow-sm: 0 6px 22px rgba(30, 45, 34, 0.13);
            --shadow-md: 0 16px 48px rgba(30, 45, 34, 0.18);
            --shadow-lg: 0 28px 72px rgba(30, 45, 34, 0.24);
            --r-xs: 5px;
            --r-sm: 10px;
            --r-md: 14px;
            --r-lg: 18px;
            --r-xl: 24px;
            --nav-h: 64px;
        }

        html,
        body {
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            line-height: 1.55;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        body {
            background: #6a9870;
        }

        /* ─── BACKGROUND ─── */
        .bg-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .bg-canvas::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 90% 70% at 15% 5%, #a8d4ae 0%, transparent 55%),
                radial-gradient(ellipse 70% 80% at 85% 95%, #3a6b42 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 70% 20%, #8aba92 0%, transparent 50%),
                radial-gradient(ellipse 50% 60% at 10% 80%, #5a8a62 0%, transparent 50%),
                linear-gradient(145deg, #7aaa82 0%, #4a7a52 60%, #2e5838 100%);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: .38;
            animation: blobDrift var(--bd, 16s) ease-in-out infinite alternate;
        }

        .b1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #c2e0c8, #88bb90);
            top: -160px;
            left: -120px;
            --bd: 18s
        }

        .b2 {
            width: 440px;
            height: 440px;
            background: radial-gradient(circle, #2f5835, #4a7a52);
            bottom: -110px;
            right: -110px;
            --bd: 14s;
            animation-delay: -6s
        }

        .b3 {
            width: 340px;
            height: 340px;
            background: radial-gradient(circle, #d0ecd3, #9cc4a4);
            top: 42%;
            left: 5%;
            --bd: 20s;
            animation-delay: -10s
        }

        .b4 {
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, #4a8854, #6aaa74);
            top: 18%;
            right: 8%;
            --bd: 22s;
            animation-delay: -3s
        }

        @keyframes blobDrift {
            0% {
                transform: translate(0, 0) scale(1) rotate(0deg)
            }

            33% {
                transform: translate(20px, -15px) scale(1.04) rotate(2deg)
            }

            66% {
                transform: translate(-10px, 25px) scale(.97) rotate(-1deg)
            }

            100% {
                transform: translate(25px, 10px) scale(1.06) rotate(3deg)
            }
        }

        .particles {
            position: absolute;
            inset: 0
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .32);
            animation: pfloat var(--pd, 8s) ease-in-out infinite alternate;
        }

        @keyframes pfloat {
            from {
                transform: translateY(0) scale(1);
                opacity: .25
            }

            to {
                transform: translateY(-35px) scale(1.6);
                opacity: .65
            }
        }

        /* ─── PAGE ─── */
        .page {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ─── NAVBAR ─── */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            height: var(--nav-h);
            background: rgba(255, 255, 255, 0.46);
            backdrop-filter: blur(26px) saturate(180%);
            -webkit-backdrop-filter: blur(26px) saturate(180%);
            border-bottom: 1.5px solid var(--glass-border);
            box-shadow: 0 2px 28px rgba(30, 45, 34, 0.10);
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 50;
            animation: slideDown .5s cubic-bezier(.22, .68, 0, 1.1) both;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-110%)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0
        }

        .nav-logo {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--sage) 0%, var(--forest) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 18px rgba(42, 82, 48, .38);
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
            transition: transform .2s cubic-bezier(.22, .68, 0, 1.4), box-shadow .2s;
            cursor: pointer;
        }

        .nav-logo::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, .22), transparent);
        }

        .nav-logo:hover {
            transform: scale(1.1) rotate(-5deg);
            box-shadow: 0 10px 28px rgba(42, 82, 48, .5)
        }

        .nav-logo i {
            color: #fff;
            font-size: 20px
        }

        .nav-title {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: var(--text-dark);
            white-space: nowrap
        }

        .nav-sub {
            font-size: 10px;
            color: var(--text-soft);
            letter-spacing: .1em;
            text-transform: uppercase
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end
        }

        /* Hamburger (mobile) */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            padding: 8px;
            cursor: pointer;
            background: rgba(255, 255, 255, .56);
            border: 1.5px solid var(--glass-border);
            border-radius: var(--r-sm);
            backdrop-filter: blur(8px);
            transition: all .2s;
        }

        .hamburger span {
            width: 20px;
            height: 2px;
            background: var(--text-mid);
            border-radius: 2px;
            transition: all .3s cubic-bezier(.22, .68, 0, 1.2);
            display: block;
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg)
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0)
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg)
        }

        .nav-collapse {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        /* ─── BUTTONS ─── */
        .btn {
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: var(--r-sm);
            border: 1.5px solid rgba(168, 197, 173, .42);
            background: rgba(255, 255, 255, .56);
            color: var(--text-mid);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .22s cubic-bezier(.22, .68, 0, 1.2);
            backdrop-filter: blur(8px);
            position: relative;
            overflow: hidden;
            white-space: nowrap;
        }

        .btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0);
            transition: background .2s;
        }

        .btn:hover {
            background: rgba(255, 255, 255, .82);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm)
        }

        .btn:hover::before {
            background: rgba(255, 255, 255, .1)
        }

        .btn:active {
            transform: translateY(0) scale(.98)
        }

        .btn-sm {
            font-size: 11px;
            padding: 5px 11px;
            border-radius: var(--r-xs)
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--sage), var(--sage-dark));
            color: #fff;
            border-color: transparent;
            box-shadow: 0 5px 18px rgba(74, 107, 80, .35);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--sage-mid), var(--forest));
            box-shadow: 0 10px 28px rgba(42, 82, 48, .45);
        }

        .btn-danger {
            background: rgba(180, 50, 50, .12);
            color: var(--danger-fg);
            border-color: rgba(180, 50, 50, .22)
        }

        .btn-danger:hover {
            background: rgba(180, 50, 50, .22)
        }

        .btn-logout:hover {
            background: rgba(180, 50, 50, .10);
            color: var(--danger-fg);
            border-color: rgba(180, 50, 50, .28)
        }

        .spinning i {
            animation: spinAnim .7s linear infinite
        }

        @keyframes spinAnim {
            to {
                transform: rotate(360deg)
            }
        }

        /* ─── WRAP ─── */
        .wrap {
            max-width: 1320px;
            margin: 0 auto;
            padding: 24px 20px 48px;
            width: 100%
        }

        /* ─── ANIMATIONS ─── */
        @keyframes riseUp {
            from {
                opacity: 0;
                transform: translateY(22px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @keyframes rowIn {
            from {
                opacity: 0;
                transform: translateX(-12px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @keyframes pop {
            0% {
                transform: scale(.9) translateY(16px);
                opacity: 0
            }

            100% {
                transform: none;
                opacity: 1
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        /* ─── METRICS ─── */
        .metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .mc {
            background: var(--glass);
            border: 1.5px solid var(--glass-border);
            border-radius: var(--r-lg);
            padding: 18px 20px 16px;
            backdrop-filter: blur(18px) saturate(160%);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            cursor: default;
            transition: transform .22s cubic-bezier(.22, .68, 0, 1.3), box-shadow .22s;
            animation: riseUp .5s cubic-bezier(.22, .68, 0, 1.1) both;
        }

        .mc:nth-child(1) {
            animation-delay: .06s
        }

        .mc:nth-child(2) {
            animation-delay: .12s
        }

        .mc:nth-child(3) {
            animation-delay: .18s
        }

        .mc:nth-child(4) {
            animation-delay: .24s
        }

        .mc:nth-child(5) {
            animation-delay: .30s
        }

        .mc:nth-child(6) {
            animation-delay: .36s
        }

        .mc::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgb(255 255 255 / 65%), transparent);
            transition: transform .3s, opacity .3s;
        }

        .mc:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: var(--shadow-md)
        }

        .mc:hover::before {
            transform: scale(1.5)
        }

        .mc-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(107, 143, 113, .22), rgba(74, 107, 80, .14));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-size: 16px;
            color: var(--sage-dark);
            transition: transform .2s cubic-bezier(.22, .68, 0, 1.4);
        }

        .mc:hover .mc-icon {
            transform: scale(1.15) rotate(-6deg)
        }

        .mc-lbl {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--text-soft);
            font-weight: 600;
            margin-bottom: 5px
        }

        .mc-val {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: var(--text-dark);
            line-height: 1;
            transition: transform .15s, color .3s;
        }

        .mc-val.bump {
            animation: bumpNum .25s cubic-bezier(.22, .68, 0, 1.5)
        }

        @keyframes bumpNum {
            0% {
                transform: scale(1)
            }

            50% {
                transform: scale(1.18)
            }

            100% {
                transform: scale(1)
            }
        }

        .mc-val.danger {
            color: var(--danger-fg)
        }

        .mc-val.warning {
            color: var(--warn-fg)
        }

        .mc-sub {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 5px
        }

        /* ─── CARDS ─── */
        .card {
            background: #fff;
            border: 1.5px solid var(--glass-border);
            border-radius: var(--r-xl);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            box-shadow: var(--shadow-sm);
            margin-bottom: 16px;
            overflow: hidden;
            animation: riseUp .5s cubic-bezier(.22, .68, 0, 1.1) both;
            transition: box-shadow .25s, transform .25s;
        }

        .card:hover {
            box-shadow: var(--shadow-md)
        }

        .card-inner {
            padding: 18px 22px
        }

        /* ─── CHART ─── */
        .chart-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 8px
        }

        .chart-title {
            font-family: 'Playfair Display', serif;
            font-size: 15px;
            color: var(--text-dark)
        }

        .chart-sub {
            font-size: 11px;
            color: var(--text-soft);
            letter-spacing: .04em;
            margin-top: 2px
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: var(--success-fg);
            font-weight: 600;
            background: var(--success-bg);
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid rgba(63, 135, 90, .2);
            white-space: nowrap;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--leaf);
            animation: ldot 1.8s infinite;
        }

        @keyframes ldot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1)
            }

            50% {
                opacity: .35;
                transform: scale(.75)
            }
        }

        /* ─── TABS ─── */
        .tab-bar {
            display: flex;
            gap: 4px;
            background: rgba(255, 255, 255, .42);
            backdrop-filter: blur(12px);
            padding: 5px;
            border-radius: var(--r-lg);
            width: fit-content;
            margin-bottom: 14px;
            border: 1.5px solid var(--glass-border);
            box-shadow: var(--shadow-xs);
            animation: riseUp .5s .4s cubic-bezier(.22, .68, 0, 1.1) both;
            flex-wrap: wrap;
        }

        .tab {
            padding: 8px 18px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 500;
            border-radius: var(--r-sm);
            cursor: pointer;
            border: none;
            background: transparent;
            color: var(--text-soft);
            transition: all .22s cubic-bezier(.22, .68, 0, 1.2);
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .tab.active {
            background: linear-gradient(135deg, var(--sage), var(--sage-dark));
            color: #fff;
            box-shadow: 0 4px 16px rgba(74, 107, 80, .34);
            transform: translateY(-1px);
        }

        .tab:not(.active):hover {
            background: rgba(255, 255, 255, .65);
            color: var(--text-mid);
            transform: translateY(-1px)
        }

        /* ─── TABLES ─── */
        .of-auto {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch
        }

        .tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px
        }

        .tbl th {
            text-align: left;
            padding: 11px 14px;
            font-size: 10px;
            font-weight: 600;
            color: var(--text-soft);
            letter-spacing: .08em;
            text-transform: uppercase;
            border-bottom: 1.5px solid var(--glass-bsoft);
            background: rgba(168, 197, 173, .16);
            white-space: nowrap;
        }

        .tbl td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(168, 197, 173, .16);
            vertical-align: middle
        }

        .tbl tr:last-child td {
            border-bottom: none
        }

        .tbl tbody tr {
            animation: rowIn .35s ease both
        }

        .tbl tbody tr:hover td {
            background: rgba(255, 255, 255, .55)
        }

        /* ─── BADGES ─── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 20px;
            transition: transform .15s, box-shadow .15s;
        }

        .badge:hover {
            transform: scale(1.06);
            box-shadow: var(--shadow-xs)
        }

        .b-success {
            background: var(--success-bg);
            color: var(--success-fg)
        }

        .b-warn {
            background: var(--warn-bg);
            color: var(--warn-fg)
        }

        .b-danger {
            background: var(--danger-bg);
            color: var(--danger-fg)
        }

        .b-info {
            background: var(--info-bg);
            color: var(--info-fg)
        }

        .b-neutral {
            background: var(--neutral-bg);
            color: var(--neutral-fg)
        }

        .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block
        }

        .d-green {
            background: var(--leaf)
        }

        .d-amber {
            background: #c07a10
        }

        .d-red {
            background: #b03030
        }

        .d-gray {
            background: #9ca3af
        }

        .anim-pulse {
            animation: ldot 2s infinite
        }

        /* ─── PROGRESS ─── */
        .pbar {
            height: 6px;
            border-radius: 3px;
            background: rgba(168, 197, 173, .25);
            overflow: hidden;
            min-width: 80px
        }

        .pfill {
            height: 100%;
            border-radius: 3px;
            transition: width .65s cubic-bezier(.22, .68, 0, 1.1);
            position: relative;
            overflow: hidden;
        }

        .pfill::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .5), transparent);
            animation: shimmer 2.2s infinite;
        }

        @keyframes shimmer {
            to {
                left: 200%
            }
        }

        code {
            font-size: 11px;
            background: rgba(107, 143, 113, .16);
            color: var(--sage-dark);
            padding: 2px 8px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
        }

        /* ─── ERR BANNER ─── */
        .err {
            display: none;
            align-items: center;
            gap: 10px;
            background: rgba(210, 85, 85, .15);
            color: var(--danger-fg);
            border: 1.5px solid rgba(180, 60, 60, .25);
            border-radius: var(--r-md);
            padding: 12px 18px;
            font-size: 13px;
            margin-bottom: 16px;
            backdrop-filter: blur(8px);
            animation: shake .4s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: none
            }

            25% {
                transform: translateX(-6px)
            }

            75% {
                transform: translateX(6px)
            }
        }

        /* ─── MODAL ─── */
        .modal-bg {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(18, 35, 22, .50);
            backdrop-filter: blur(10px);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }

        .modal-bg.open {
            display: flex;
            animation: fadeIn .22s ease
        }

        .modal {
            background: var(--glass-light);
            border: 1.5px solid var(--glass-border);
            backdrop-filter: blur(28px) saturate(180%);
            border-radius: var(--r-xl);
            padding: 24px 28px;
            max-width: 720px;
            width: 92%;
            max-height: 82vh;
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            animation: pop .3s cubic-bezier(.22, .68, 0, 1.3) both;
        }

        .modal-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px
        }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: var(--text-dark)
        }

        .modal pre {
            font-size: 11px;
            white-space: pre-wrap;
            word-break: break-all;
            color: var(--danger-fg);
            background: rgba(210, 85, 85, .12);
            border: 1px solid rgba(180, 60, 60, .2);
            padding: 16px;
            border-radius: var(--r-md);
            margin-top: 14px;
            line-height: 1.65;
        }

        /* ─── EMPTY ─── */
        .empty {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
            font-size: 13px
        }

        .empty i {
            font-size: 30px;
            display: block;
            margin-bottom: 10px;
            opacity: .45
        }

        /* ─── REFRESH TS ─── */
        .refresh-ts {
            font-size: 11px;
            color: var(--text-soft);
            white-space: nowrap
        }

        .logout-form {
            display: none
        }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px
        }

        ::-webkit-scrollbar-track {
            background: transparent
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(107, 143, 113, .4);
            border-radius: 3px
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 143, 113, .65)
        }

        /* ─── SWEETALERT CUSTOM THEME ─── */
        .swal2-popup.swal-bquick {
            font-family: 'DM Sans', sans-serif !important;
            border-radius: var(--r-xl) !important;
            background: rgba(240, 248, 242, .96) !important;
            backdrop-filter: blur(24px) !important;
            border: 1.5px solid rgba(255, 255, 255, .85) !important;
            box-shadow: 0 32px 80px rgba(30, 45, 34, .28) !important;
        }

        .swal2-popup.swal-bquick .swal2-title {
            font-family: 'Playfair Display', serif !important;
            color: var(--text-dark) !important;
            font-size: 19px !important;
        }

        .swal2-popup.swal-bquick .swal2-html-container {
            color: var(--text-soft) !important;
            font-size: 13px !important;
        }

        .swal2-popup.swal-bquick .swal2-confirm {
            font-family: 'DM Sans', sans-serif !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: var(--r-sm) !important;
            box-shadow: none !important;
            padding: 10px 22px !important;
        }

        .swal2-popup.swal-bquick .swal2-cancel {
            font-family: 'DM Sans', sans-serif !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            border-radius: var(--r-sm) !important;
            box-shadow: none !important;
            padding: 10px 22px !important;
            background: rgba(168, 197, 173, .35) !important;
            color: var(--text-mid) !important;
        }

        .swal2-popup.swal-bquick .swal2-cancel:hover {
            background: rgba(168, 197, 173, .55) !important;
        }

        .swal2-icon.swal2-warning {
            border-color: #c07a10 !important;
            color: #c07a10 !important
        }

        .swal2-icon.swal2-error {
            border-color: var(--danger-fg) !important;
            color: var(--danger-fg) !important
        }

        /* Toast SWAl */
        .swal2-container.swal2-top-end .swal2-popup,
        .swal2-container.swal2-top-right .swal2-popup {
            border-radius: var(--r-md) !important;
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 900px) {
            .metrics {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr))
            }
        }

        @media (max-width: 640px) {
            :root {
                --nav-h: 58px
            }

            .navbar {
                padding: 0 16px
            }

            .nav-title {
                font-size: 14px
            }

            .wrap {
                padding: 16px 12px 40px
            }

            .metrics {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px
            }

            .mc {
                padding: 14px 16px 12px
            }

            .mc-val {
                font-size: 24px
            }

            .hamburger {
                display: flex
            }

            .nav-collapse {
                position: fixed;
                top: var(--nav-h);
                left: 0;
                right: 0;
                background: rgba(230, 245, 233, .97);
                backdrop-filter: blur(24px);
                border-bottom: 1.5px solid var(--glass-border);
                padding: 16px 20px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                box-shadow: 0 12px 40px rgba(30, 45, 34, .18);
                transform: translateY(-110%);
                opacity: 0;
                pointer-events: none;
                transition: transform .3s cubic-bezier(.22, .68, 0, 1.1), opacity .3s;
                z-index: 49;
            }

            .nav-collapse.menu-open {
                transform: none;
                opacity: 1;
                pointer-events: all
            }

            .nav-collapse .btn {
                width: 100%;
                justify-content: center
            }

            .refresh-ts {
                order: 10;
                width: 100%;
                text-align: center
            }

            .live-badge {
                font-size: 10px
            }

            .tab {
                padding: 7px 12px;
                font-size: 12px
            }

            .card-inner {
                padding: 14px 14px
            }

            .tbl th,
            .tbl td {
                padding: 10px 12px
            }
        }

        @media (max-width: 400px) {
            .metrics {
                grid-template-columns: 1fr 1fr
            }

            .mc-val {
                font-size: 22px
            }
        }
    </style>
</head>

<body>

    <div class="bg-canvas">
        <div class="blob b1"></div>
        <div class="blob b2"></div>
        <div class="blob b3"></div>
        <div class="blob b4"></div>
        <div class="particles" id="particles"></div>
    </div>

    <div class="page">

        {{-- NAVBAR --}}
        <nav class="navbar">
            <div class="nav-brand">
                <div class="nav-logo"><i class="ti ti-database"></i></div>
                <div>
                    <div class="nav-title">Queue Monitor</div>
                    <div class="nav-sub">Bquick DB Manage</div>
                </div>
            </div>

            <!-- Hamburger (mobile) -->
            <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>

            <div class="nav-collapse nav-right" id="navCollapse">
                <div class="live-badge"><span class="live-dot"></span> Live</div>
                <span class="badge b-info"><span class="dot d-green anim-pulse"></span> database</span>
                <span class="refresh-ts" id="lastRefresh"></span>
                <button class="btn" onclick="clearAll()"><i class="ti ti-trash" style="font-size:13px"></i> Clear
                    failed</button>
                <button class="btn btn-primary" id="refreshBtn" onclick="refreshAll()">
                    <i class="ti ti-refresh" style="font-size:13px"></i> Refresh
                </button>
                <form id="logoutForm" class="logout-form" method="POST" action="{{ route('logout') }}">@csrf</form>
                <button class="btn btn-logout" onclick="confirmLogout()">
                    <i class="ti ti-logout" style="font-size:13px"></i> Logout
                </button>
            </div>
        </nav>

        <div class="wrap">

            <div class="err" id="errBanner"><i class="ti ti-alert-triangle"></i><span id="errMsg"></span></div>

            {{-- METRICS --}}
            <div class="metrics">
                <div class="mc">
                    <div class="mc-icon"><i class="ti ti-activity"></i></div>
                    <div class="mc-lbl">Jobs / min</div>
                    <div class="mc-val" id="mJpm">—</div>
                    <div class="mc-sub">rolling count</div>
                </div>
                <div class="mc">
                    <div class="mc-icon"><i class="ti ti-clock-pause"></i></div>
                    <div class="mc-lbl">Pending</div>
                    <div class="mc-val" id="mPending">—</div>
                    <div class="mc-sub">waiting in queue</div>
                </div>
                <div class="mc">
                    <div class="mc-icon"><i class="ti ti-loader-2"></i></div>
                    <div class="mc-lbl">Processing</div>
                    <div class="mc-val" id="mProcessing">—</div>
                    <div class="mc-sub">reserved by workers</div>
                </div>
                <div class="mc">
                    <div class="mc-icon" style="background:rgba(210,85,85,.18);color:var(--danger-fg)"><i
                            class="ti ti-alert-circle"></i></div>
                    <div class="mc-lbl">Failed (24h)</div>
                    <div class="mc-val" id="mFailed24">—</div>
                    <div class="mc-sub" id="mFailedTotal"></div>
                </div>
                <div class="mc">
                    <div class="mc-icon"><i class="ti ti-hourglass-low"></i></div>
                    <div class="mc-lbl">Avg wait</div>
                    <div class="mc-val" id="mWait">—</div>
                    <div class="mc-sub">oldest pending job</div>
                </div>
                <div class="mc">
                    <div class="mc-icon" style="background:rgba(85,175,105,.18);color:var(--leaf)"><i
                            class="ti ti-circle-check"></i></div>
                    <div class="mc-lbl">Processed today</div>
                    <div class="mc-val" id="mThrough">—</div>
                    <div class="mc-sub">via cache counter</div>
                </div>
            </div>

            {{-- CHART --}}
            <div class="card" style="animation-delay:.38s">
                <div class="card-inner">
                    <div class="chart-hdr">
                        <div>
                            <div class="chart-title">Throughput</div>
                            <div class="chart-sub">Jobs processed per minute — last 12 min</div>
                        </div>
                        <div class="live-badge"><span class="live-dot"></span> Updating</div>
                    </div>
                    <div style="position:relative;width:100%;height:115px">
                        <canvas id="sparkline" role="img" aria-label="Throughput chart"></canvas>
                    </div>
                </div>
            </div>

            {{-- TABS --}}
            <div class="tab-bar">
                <button class="tab active" onclick="switchTab('queues',this)">
                    <i class="ti ti-stack-2" style="font-size:13px"></i>Queues
                </button>
                <button class="tab" onclick="switchTab('pending',this)">
                    <i class="ti ti-clock" style="font-size:13px"></i>Pending
                </button>
                <button class="tab" onclick="switchTab('failed',this)">
                    <i class="ti ti-alert-triangle" style="font-size:13px"></i>Failed
                    <span id="failedBadge"></span>
                </button>
            </div>

            {{-- QUEUES TAB --}}
            <div id="tabQueues" class="card of-auto" style="animation-delay:.46s">
                <table class="tbl" style="min-width:580px">
                    <thead>
                        <tr>
                            <th>Queue</th>
                            <th>Connection</th>
                            <th>Pending</th>
                            <th>Processing</th>
                            <th>Failed</th>
                            <th>Avg attempts</th>
                            <th>Wait</th>
                            <th>Load</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="queuesBody">
                        <tr>
                            <td colspan="9" class="empty"><i class="ti ti-loader-2"
                                    style="animation:spinAnim 1s linear infinite"></i>Loading…</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- PENDING TAB --}}
            <div id="tabPending" class="card of-auto" style="display:none;animation-delay:.46s">
                <table class="tbl" style="min-width:560px">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Queue</th>
                            <th>Status</th>
                            <th>Attempts</th>
                            <th>Reserved at</th>
                            <th>Queued at</th>
                            <th>Data preview</th>
                        </tr>
                    </thead>
                    <tbody id="pendingBody">
                        <tr>
                            <td colspan="7" class="empty"><i class="ti ti-loader-2"
                                    style="animation:spinAnim 1s linear infinite"></i>Loading…</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- FAILED TAB --}}
            <div id="tabFailed" class="card of-auto" style="display:none;animation-delay:.46s">
                <table class="tbl" style="min-width:620px">
                    <thead>
                        <tr>
                            <th>Job</th>
                            <th>Queue</th>
                            <th>Failed at</th>
                            <th>Attempts</th>
                            <th>Error</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="failedBody">
                        <tr>
                            <td colspan="6" class="empty"><i class="ti ti-loader-2"
                                    style="animation:spinAnim 1s linear infinite"></i>Loading…</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal-bg" id="exModal" onclick="closeModal(event)">
        <div class="modal">
            <div class="modal-hdr">
                <strong class="modal-title" id="modalTitle"></strong>
                <button class="btn btn-sm" onclick="document.getElementById('exModal').classList.remove('open')">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div style="font-size:11px;color:var(--text-soft);margin-top:2px" id="modalMeta"></div>
            <pre id="modalTrace"></pre>
        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const BASE = '/queue';
        const H = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json'
        };
        let chart = null,
            refreshing = false;

        /* ── SweetAlert2 Base Config ── */
        const SwalBquick = Swal.mixin({
            customClass: {
                popup: 'swal-bquick'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            buttonsStyling: true,
            allowOutsideClick: true,
            focusConfirm: false,
        });

        const SwalToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2800,
            timerProgressBar: true,
            customClass: {
                popup: 'swal-bquick'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        /* ── Particles ── */
        (() => {
            const c = document.getElementById('particles');
            for (let i = 0; i < 30; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                p.style.cssText = `left:${Math.random()*100}%;top:${Math.random()*100}%;
            --pd:${6+Math.random()*10}s;animation-delay:${-Math.random()*10}s;
            width:${2+Math.random()*3}px;height:${2+Math.random()*3}px;opacity:${0.15+Math.random()*0.3}`;
                c.appendChild(p);
            }
        })();

        /* ── Hamburger / Mobile Menu ── */
        function toggleMenu() {
            const btn = document.getElementById('hamburger');
            const menu = document.getElementById('navCollapse');
            btn.classList.toggle('open');
            menu.classList.toggle('menu-open');
        }

        /* ── API ── */
        async function api(path, opts = {}) {
            const r = await fetch(BASE + path, {
                headers: H,
                ...opts
            });
            if (!r.ok) throw new Error(`HTTP ${r.status}`);
            return r.json();
        }

        /* ── Error Banner ── */
        function showErr(msg) {
            const b = document.getElementById('errBanner');
            document.getElementById('errMsg').textContent = msg;
            b.style.display = 'flex';
        }

        function hideErr() {
            document.getElementById('errBanner').style.display = 'none'
        }

        /* ── SWAl Toast Helpers ── */
        function toast(msg, icon = 'success') {
            SwalToast.fire({
                icon,
                title: msg
            });
        }

        function toastInfo(msg) {
            toast(msg, 'info')
        }

        function toastWarn(msg) {
            toast(msg, 'warning')
        }

        function toastErr(msg) {
            toast(msg, 'error')
        }

        /* ── Helpers ── */
        function loadColor(v) {
            return v >= 80 ? '#b03030' : v >= 50 ? '#c07a10' : 'var(--leaf)';
        }

        function escHtml(s) {
            return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
        }

        function fmtWait(s) {
            if (s < 60) return s + ' Sec';
            if (s < 3600) return Math.floor(s / 60) + ' Min ' + (s % 60) + ' Sec';
            return Math.floor(s / 3600) + ' Hr ' + Math.floor((s % 3600) / 60) + ' Min';
        }

        function statusBadge(s) {
            const m = {
                active: ['b-success', 'd-green'],
                processing: ['b-info', 'd-green'],
                busy: ['b-warn', 'd-amber'],
                idle: ['b-neutral', 'd-gray'],
                failed: ['b-danger', 'd-red'],
                pending: ['b-neutral', 'd-gray']
            };
            const [cls, dot] = m[s] ?? ['b-neutral', 'd-gray'];
            return `<span class="badge ${cls}"><span class="dot ${dot}"></span>${s}</span>`;
        }

        const prev = {};

        function setNum(id, val) {
            const el = document.getElementById(id);
            if (!el || prev[id] === String(val)) return;
            prev[id] = String(val);
            el.classList.remove('bump');
            void el.offsetWidth;
            el.textContent = val;
            el.classList.add('bump');
        }

        /* ── Stats ── */
        async function loadStats() {
            const d = await api('/stats');
            setNum('mJpm', d.jobs_per_minute.toLocaleString());
            setNum('mPending', d.pending.toLocaleString());
            setNum('mProcessing', d.processing.toLocaleString());
            setNum('mFailed24', d.failed_24h);
            document.getElementById('mFailed24').className = 'mc-val' + (d.failed_24h > 0 ? ' danger' : '');
            document.getElementById('mFailedTotal').textContent = d.failed_total + ' total';
            setNum('mWait', fmtWait(d.avg_wait_seconds));
            setNum('mThrough', d.processed_today.toLocaleString());
            document.getElementById('failedBadge').innerHTML = d.failed_24h > 0 ?
                `<span class="badge b-danger" style="font-size:10px;margin-left:5px">${d.failed_24h}</span>` : '';
        }

        /* ── Queues ── */
        async function loadQueues() {
            const data = await api('/queues');
            document.getElementById('queuesBody').innerHTML = !data.length ?
                `<tr><td colspan="9" class="empty"><i class="ti ti-database-off"></i>No Queued Jobs.</td></tr>` :
                data.map((q, i) => `
        <tr style="animation-delay:${i*.05}s">
          <td style="font-weight:500"><i class="ti ti-stack-2" style="font-size:13px;vertical-align:-2px;margin-right:6px;color:var(--text-soft)"></i>${q.name}</td>
          <td><span class="badge b-info">${q.connection}</span></td>
          <td>${q.pending}</td>
          <td>${q.processing}</td>
          <td>${q.failed>0?`<span style="color:var(--danger-fg);font-weight:600">${q.failed}</span>`:q.failed}</td>
          <td style="color:var(--text-soft)">${q.avg_attempts}×</td>
          <td style="color:var(--text-soft)">${fmtWait(q.wait_seconds)}</td>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div class="pbar"><div class="pfill" style="width:${q.load}%;background:${loadColor(q.load)}"></div></div>
              <span style="font-size:11px;color:var(--text-soft);min-width:30px">${q.load}%</span>
            </div>
          </td>
          <td>${statusBadge(q.status)}</td>
        </tr>`).join('');
        }

        /* ── Pending ── */
        async function loadPending() {
            const data = await api('/pending');
            document.getElementById('pendingBody').innerHTML = !data.length ?
                `<tr><td colspan="7" class="empty"><i class="ti ti-circle-check"></i>No Pending Jobs.</td></tr>` :
                data.map((j, i) => `
        <tr style="animation-delay:${i*.05}s">
          <td style="font-weight:500">${j.job}</td>
          <td><code>${j.queue}</code></td>
          <td>${statusBadge(j.status)}</td>
          <td><span class="badge b-neutral">${j.attempts}</span></td>
          <td style="font-size:12px;color:var(--text-soft)">${j.reserved_at??'—'}</td>
          <td style="font-size:12px;color:var(--text-soft)">${j.created_at}</td>
          <td style="font-size:11px;color:var(--text-soft);max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
            ${Object.entries(j.payload_preview).map(([k,v])=>`${k}: ${v}`).join(', ')||'—'}
          </td>
        </tr>`).join('');
        }

        /* ── Failed ── */
        async function loadFailed() {
            const data = await api('/failed');
            document.getElementById('failedBody').innerHTML = !data.length ?
                `<tr><td colspan="6" class="empty"><i class="ti ti-confetti"></i>No Failed Jobs.</td></tr>` :
                data.map((f, i) => `
        <tr id="fr-${f.id}" style="animation-delay:${i*.05}s">
          <td style="font-weight:500">${f.job}</td>
          <td><code>${f.queue}</code></td>
          <td style="font-size:12px;color:var(--text-soft);white-space:nowrap" title="${f.failed_at_absolute}">${f.failed_at}</td>
          <td style="text-align:center"><span class="badge b-danger">${f.attempts}</span></td>
          <td style="font-size:12px;color:var(--danger-fg);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;cursor:pointer"
              title="Click for full trace"
              onclick="showTrace('${f.id}','${escHtml(f.job)}','${escHtml(f.failed_at_absolute)}','${escHtml(f.exception)}')">
            ${escHtml(f.error)}
          </td>
          <td>
            <div style="display:flex;gap:6px">
              <button class="btn btn-sm" onclick="retryJob('${f.id}')"><i class="ti ti-refresh" style="font-size:11px"></i> Retry</button>
              <button class="btn btn-sm btn-danger" onclick="forgetJob('${f.id}')"><i class="ti ti-trash" style="font-size:11px"></i></button>
            </div>
          </td>
        </tr>`).join('');
        }

        /* ── Failed Actions ── */
        function removeRow(uuid) {
            const r = document.getElementById('fr-' + uuid);
            if (r) {
                r.style.cssText = 'opacity:0;transform:translateX(40px) scale(.97);transition:all .3s ease';
                setTimeout(() => r.remove(), 300);
            }
        }

        async function retryJob(uuid) {
            const result = await SwalBquick.fire({
                title: 'Retry this job?',
                html: 'The job will be re-queued and processed again.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-refresh"></i> Yes, retry',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#4a6b50',
            });
            if (!result.isConfirmed) return;
            try {
                await api(`/failed/${uuid}/retry`, {
                    method: 'POST'
                });
                removeRow(uuid);
                loadStats();
                toast('Job queued for retry ✓', 'success');
            } catch (e) {
                showErr('Retry failed: ' + e.message);
                toastErr('Retry failed');
            }
        }

        async function forgetJob(uuid) {
            const result = await SwalBquick.fire({
                title: 'Delete this job?',
                html: 'This failed job record will be permanently removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-trash"></i> Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#8a1e1e',
            });
            if (!result.isConfirmed) return;
            try {
                await api(`/failed/${uuid}`, {
                    method: 'DELETE'
                });
                removeRow(uuid);
                loadStats();
                toast('Job deleted', 'success');
            } catch (e) {
                showErr('Delete failed: ' + e.message);
                toastErr('Delete failed');
            }
        }

        async function clearAll() {
            const result = await SwalBquick.fire({
                title: 'Clear all failed jobs?',
                html: `<div style="display:flex;align-items:center;gap:10px;justify-content:center;margin-top:6px">
                 <i class="ti ti-alert-triangle" style="font-size:28px;color:#c07a10"></i>
                 <span>This will permanently delete <strong>every</strong> failed job record. This cannot be undone.</span>
               </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-trash"></i> Yes, clear all',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#8a1e1e',
                reverseButtons: true,
            });
            if (!result.isConfirmed) return;
            try {
                await api('/failed', {
                    method: 'DELETE'
                });
                loadFailed();
                loadStats();
                toast('All failed jobs cleared', 'success');
            } catch (e) {
                showErr('Clear failed: ' + e.message);
                toastErr('Could not clear jobs');
            }
        }

        async function confirmLogout() {
            const result = await SwalBquick.fire({
                title: 'Sign out?',
                html: 'You will be logged out of Queue Monitor.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-logout"></i> Logout',
                cancelButtonText: 'Stay',
                confirmButtonColor: '#4a6b50',
                reverseButtons: true,
            });
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        }

        /* ── Modal ── */
        function showTrace(id, job, at, trace) {
            document.getElementById('modalTitle').textContent = job;
            document.getElementById('modalMeta').textContent = 'Failed at: ' + at;
            document.getElementById('modalTrace').textContent = trace;
            document.getElementById('exModal').classList.add('open');
        }

        function closeModal(e) {
            if (e.target === document.getElementById('exModal'))
                document.getElementById('exModal').classList.remove('open');
        }

        /* ── Chart ── */
        const chartLabels = Array.from({
            length: 12
        }, (_, i) => '-' + (11 - i) + 'm');
        const chartVals = Array(12).fill(0);

        function buildChart() {
            const ctx = document.getElementById('sparkline').getContext('2d');
            const grad = ctx.createLinearGradient(0, 0, 0, 115);
            grad.addColorStop(0, 'rgba(107,143,113,0.40)');
            grad.addColorStop(1, 'rgba(107,143,113,0.02)');
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Jobs/min',
                        data: chartVals,
                        borderColor: '#5a8060',
                        backgroundColor: grad,
                        tension: .42,
                        fill: true,
                        borderWidth: 2.5,
                        pointRadius: 3,
                        pointBackgroundColor: '#4a6b50',
                        pointBorderColor: 'rgba(255,255,255,0.85)',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#3a5440'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 700,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: i => 'At ' + i[0].label,
                                label: i => ` ${i.raw} jobs/min`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10,
                                    family: 'DM Sans'
                                },
                                color: '#7a9080'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(107,143,113,0.12)'
                            },
                            ticks: {
                                font: {
                                    size: 10,
                                    family: 'DM Sans'
                                },
                                color: '#7a9080'
                            },
                            min: 0
                        }
                    }
                }
            });
        }

        async function loadThroughput() {
            const data = await api('/throughput');
            data.forEach((p, i) => {
                chartVals[i] = p.value
            });
            if (chart) {
                chart.data.datasets[0].data = [...chartVals];
                chart.update()
            }
        }

        /* ── Tabs ── */
        function switchTab(name, btn) {
            ['queues', 'pending', 'failed'].forEach(t => {
                const el = document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1));
                if (t === name) {
                    el.style.display = 'block';
                    el.style.animation = 'none';
                    requestAnimationFrame(() => {
                        el.style.animation = 'riseUp .35s ease both'
                    });
                } else el.style.display = 'none';
            });
            document.querySelectorAll('.tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        }

        /* ── Refresh ── */
        async function refreshAll() {
            if (refreshing) return;
            refreshing = true;
            hideErr();
            const btn = document.getElementById('refreshBtn');
            btn.classList.add('spinning');
            try {
                await Promise.all([loadStats(), loadQueues(), loadPending(), loadFailed(), loadThroughput()]);
                const now = new Date().toLocaleTimeString();
                document.getElementById('lastRefresh').textContent = 'Updated ' + now;
            } catch (e) {
                showErr('Refresh failed: ' + e.message);
            } finally {
                btn.classList.remove('spinning');
                refreshing = false;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            buildChart();
            refreshAll();
            setInterval(refreshAll, 5000);
        });
    </script>
</body>

</html>
