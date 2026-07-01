<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QueueWA — WhatsApp Message Queue Manager</title>

    <!-- Bootstrap grid/utilities (heavily re-themed below) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --bg: #0B141A;
            --surface: #111B21;
            --surface-2: #182229;
            --surface-3: #1F2C34;
            --border: #22323A;
            --text: #E9EDEF;
            --text-dim: #8696A0;
            --green: #25D366;
            --green-deep: #128C7E;
            --amber: #F2A93B;
            --coral: #FF6B6B;
            --blue: #53BDEB;

            --font-display: 'Space Grotesk', sans-serif;
            --font-body: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background:
                radial-gradient(1100px 500px at 85% -10%, rgba(37, 211, 102, 0.08), transparent 60%),
                radial-gradient(900px 500px at -10% 110%, rgba(83, 189, 235, 0.06), transparent 60%),
                var(--bg);
            color: var(--text);
            font-family: var(--font-body);
            min-height: 100vh;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
            }
        }

        ::selection {
            background: var(--green-deep);
            color: #fff;
        }

        /* ---------- scrollbar ---------- */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--surface);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--surface-3);
            border-radius: 8px;
        }

        /* ---------- top bar ---------- */
        .topbar {
            background: rgba(17, 27, 33, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: linear-gradient(145deg, var(--green) 0%, var(--green-deep) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 18px -6px rgba(37, 211, 102, 0.55);
            flex-shrink: 0;
        }

        .brand-mark i {
            color: #06251b;
            font-size: 1.05rem;
        }

        .brand-name {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.2px;
        }

        .brand-sub {
            color: var(--text-dim);
            font-size: 0.72rem;
            font-family: var(--font-mono);
            letter-spacing: 0.5px;
        }

        .live-pill {
            background: rgba(37, 211, 102, 0.1);
            border: 1px solid rgba(37, 211, 102, 0.35);
            color: var(--green);
            font-family: var(--font-mono);
            font-size: 0.78rem;
            padding: 0.35rem 0.75rem;
            border-radius: 100px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.6);
            animation: pulseDot 1.8s ease-out infinite;
        }

        @keyframes pulseDot {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.55);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        .clock {
            font-family: var(--font-mono);
            font-size: 0.82rem;
            color: var(--text-dim);
        }

        /* ---------- layout shell ---------- */
        .shell {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.75rem 1.25rem 4rem;
        }

        .eyebrow {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--green);
        }

        .section-title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.5rem;
            margin-top: 0.25rem;
        }

        /* ---------- stat cards ---------- */
        .stat-card {
            background: linear-gradient(180deg, var(--surface-2), var(--surface));
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.15rem 1.25rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s ease, border-color 0.25s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--surface-3);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: auto -30% -60% auto;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--accent, var(--green)) 0%, transparent 70%);
            opacity: 0.12;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            color: var(--accent, var(--green));
            font-size: 1rem;
        }

        .stat-value {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 2rem;
            line-height: 1;
            margin-top: 0.65rem;
        }

        .stat-label {
            color: var(--text-dim);
            font-size: 0.8rem;
            margin-top: 0.35rem;
        }

        .stat-trend {
            font-family: var(--font-mono);
            font-size: 0.72rem;
        }

        /* ---------- pipeline (signature element) ---------- */
        .pipeline-card {
            background: linear-gradient(180deg, var(--surface-2), var(--surface));
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.75rem 1.75rem 1.25rem;
            margin-top: 1.75rem;
            overflow: hidden;
            position: relative;
        }

        .pipeline-track {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 2.75rem 0 1rem;
            padding: 0 0.5rem;
        }

        .pipeline-node {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 110px;
        }

        .node-ring {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--surface);
            border: 2px solid var(--node-color, var(--green));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: var(--node-color, var(--green));
            box-shadow: 0 0 0 6px rgba(255, 255, 255, 0.02);
        }

        .node-count {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.3rem;
            margin-top: 0.6rem;
        }

        .node-label {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-top: 0.1rem;
        }

        .pipeline-line {
            position: absolute;
            top: 32px;
            left: 55px;
            right: 55px;
            height: 2px;
            background: repeating-linear-gradient(90deg, var(--border) 0 8px, transparent 8px 16px);
            z-index: 1;
        }

        .flow-lane {
            position: absolute;
            top: 0;
            height: 100%;
        }

        .flow-dot {
            position: absolute;
            top: -3px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 8px var(--green);
            animation: flowMove linear infinite;
        }

        @keyframes flowMove {
            0% {
                left: 0;
                opacity: 0;
            }

            8% {
                opacity: 1;
            }

            92% {
                opacity: 1;
            }

            100% {
                left: 100%;
                opacity: 0;
            }
        }

        .flow-lane.fail .flow-dot {
            background: var(--coral);
            box-shadow: 0 0 8px var(--coral);
        }

        /* ---------- controls ---------- */
        .control-bar {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 0.85rem 1rem;
            margin-top: 1.75rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            align-items: center;
        }

        .filter-chip {
            font-family: var(--font-mono);
            font-size: 0.78rem;
            padding: 0.4rem 0.85rem;
            border-radius: 100px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-dim);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .filter-chip:hover {
            border-color: var(--text-dim);
            color: var(--text);
        }

        .filter-chip.active {
            background: rgba(37, 211, 102, 0.12);
            border-color: var(--green);
            color: var(--green);
        }

        .search-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: var(--font-body);
            font-size: 0.85rem;
            padding: 0.5rem 0.9rem 0.5rem 2.2rem;
        }

        .search-box:focus {
            outline: none;
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.12);
            background: var(--surface);
            color: var(--text);
        }

        .search-wrap {
            position: relative;
        }

        .search-wrap i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-dim);
            font-size: 0.85rem;
        }

        .btn-brand {
            background: linear-gradient(145deg, var(--green), var(--green-deep));
            border: none;
            color: #06251b;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.5rem 1.1rem;
            font-size: 0.85rem;
            box-shadow: 0 6px 16px -6px rgba(37, 211, 102, 0.5);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -8px rgba(37, 211, 102, 0.6);
            color: #06251b;
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-dim);
            border-radius: 10px;
            padding: 0.5rem 0.9rem;
            font-size: 0.85rem;
            transition: all 0.15s ease;
        }

        .btn-ghost:hover {
            border-color: var(--text-dim);
            color: var(--text);
        }

        .btn-ghost.paused {
            border-color: var(--amber);
            color: var(--amber);
        }

        /* ---------- table ---------- */
        .queue-table-wrap {
            margin-top: 1.25rem;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            background: var(--surface-2);
        }

        table.queue-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .queue-table thead th {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-dim);
            background: var(--surface-3);
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            text-align: left;
            font-weight: 500;
        }

        .queue-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s ease;
        }

        .queue-table tbody tr:last-child {
            border-bottom: none;
        }

        .queue-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .queue-table td {
            padding: 0.7rem 1rem;
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .msg-id {
            font-family: var(--font-mono);
            color: var(--text-dim);
            font-size: 0.76rem;
        }

        .msg-preview {
            color: var(--text-dim);
            font-size: 0.78rem;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .recipient {
            font-weight: 500;
        }

        .avatar-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--surface-3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-dim);
            margin-right: 0.6rem;
        }

        .badge-status {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
            border-radius: 100px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border: 1px solid transparent;
        }

        .badge-status .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .badge-queued {
            background: rgba(134, 150, 160, 0.12);
            color: var(--text-dim);
            border-color: rgba(134, 150, 160, 0.25);
        }

        .badge-queued .dot {
            background: var(--text-dim);
        }

        .badge-sending {
            background: rgba(83, 189, 235, 0.12);
            color: var(--blue);
            border-color: rgba(83, 189, 235, 0.3);
        }

        .badge-sending .dot {
            background: var(--blue);
            animation: pulseDot2 1s infinite;
        }

        .badge-delivered {
            background: rgba(37, 211, 102, 0.12);
            color: var(--green);
            border-color: rgba(37, 211, 102, 0.3);
        }

        .badge-delivered .dot {
            background: var(--green);
        }

        .badge-failed {
            background: rgba(255, 107, 107, 0.12);
            color: var(--coral);
            border-color: rgba(255, 107, 107, 0.3);
        }

        .badge-failed .dot {
            background: var(--coral);
        }

        @keyframes pulseDot2 {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.25;
            }
        }

        .row-enter {
            animation: rowIn 0.4s ease;
        }

        @keyframes rowIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
                background: rgba(37, 211, 102, 0.08);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .retry-btn {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-dim);
            border-radius: 8px;
            font-size: 0.72rem;
            padding: 0.25rem 0.6rem;
        }

        .retry-btn:hover {
            border-color: var(--coral);
            color: var(--coral);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-dim);
        }

        .empty-state i {
            font-size: 2rem;
            color: var(--surface-3);
        }

        /* ---------- footer ---------- */
        .foot {
            margin-top: 2.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
            color: var(--text-dim);
            font-size: 0.78rem;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .foot .dot-sep {
            opacity: 0.5;
            margin: 0 0.4rem;
        }

        /* ---------- toast ---------- */
        .toast-stack {
            position: fixed;
            bottom: 1.25rem;
            right: 1.25rem;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .mini-toast {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-left: 3px solid var(--green);
            color: var(--text);
            padding: 0.65rem 1rem;
            border-radius: 10px;
            font-size: 0.82rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.6);
            animation: toastIn 0.3s ease, toastOut 0.3s ease 2.7s forwards;
            min-width: 220px;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toastOut {
            to {
                opacity: 0;
                transform: translateX(20px);
            }
        }

        @media (max-width:768px) {
            .pipeline-track {
                flex-direction: column;
                gap: 1.75rem;
                align-items: flex-start;
            }

            .pipeline-line {
                display: none;
            }

            .pipeline-node {
                flex-direction: row;
                width: auto;
                gap: 0.9rem;
                align-items: center;
            }

            .node-count,
            .node-label {
                margin-top: 0;
            }
        }
    </style>
</head>

<body>

    <!-- ============ TOP BAR ============ -->
    <div class="topbar">
        <div class="shell py-2 d-flex align-items-center justify-content-between"
            style="padding-top:0.65rem;padding-bottom:0.65rem;">
            <div class="d-flex align-items-center gap-3">
                <div class="brand-mark"><i class="bi bi-diagram-3-fill"></i></div>
                <div>
                    <div class="brand-name">QueueWA</div>
                    <div class="brand-sub">MESSAGE QUEUE MICROPROGRAM</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="clock d-none d-sm-inline" id="clock">--:--:--</span>
                <span class="live-pill"><span class="live-dot"></span> WORKER CONNECTED</span>
            </div>
        </div>
    </div>

    <div class="shell">

        <div class="eyebrow">Dashboard · dummy environment</div>
        <div class="d-flex align-items-end justify-content-between flex-wrap gap-2">
            <div class="section-title">Outbound queue overview</div>
        </div>

        <!-- ============ STAT CARDS ============ -->
        <div class="row g-3 mt-1">
            <div class="col-6 col-lg-3">
                <div class="stat-card" style="--accent:var(--text-dim);">
                    <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                    <div class="stat-value" id="statQueued">0</div>
                    <div class="stat-label">Queued messages</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card" style="--accent:var(--blue);">
                    <div class="stat-icon"><i class="bi bi-send-fill"></i></div>
                    <div class="stat-value" id="statSending">0</div>
                    <div class="stat-label">Sending now</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card" style="--accent:var(--green);">
                    <div class="stat-icon"><i class="bi bi-check2-all"></i></div>
                    <div class="stat-value" id="statDelivered">0</div>
                    <div class="stat-label">Delivered today</div>
                    <div class="stat-trend text-success mt-1"><i class="bi bi-arrow-up-right"></i> 98.4% success rate
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card" style="--accent:var(--coral);">
                    <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div class="stat-value" id="statFailed">0</div>
                    <div class="stat-label">Failed / bounced</div>
                </div>
            </div>
        </div>

        <!-- ============ PIPELINE (signature element) ============ -->
        <div class="pipeline-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <div class="eyebrow">Live flow</div>
                    <div class="section-title" style="font-size:1.15rem;">Queue → Send → Delivery pipeline</div>
                </div>
                <div class="text-dim" style="color:var(--text-dim); font-size:0.78rem; font-family:var(--font-mono);">
                    throughput ≈ <span id="throughput">0</span> msg/min</div>
            </div>

            <div class="pipeline-track">
                <div class="pipeline-line"></div>
                <div class="flow-lane" style="left:8%; width:38%;" id="lane1"></div>
                <div class="flow-lane" style="left:54%; width:38%;" id="lane2"></div>

                <div class="pipeline-node">
                    <div class="node-ring" style="--node-color:var(--text-dim);"><i class="bi bi-inbox-fill"></i></div>
                    <div class="node-count" id="nodeQueued">0</div>
                    <div class="node-label">Queued</div>
                </div>
                <div class="pipeline-node">
                    <div class="node-ring" style="--node-color:var(--blue);"><i class="bi bi-arrow-repeat"></i></div>
                    <div class="node-count" id="nodeSending">0</div>
                    <div class="node-label">Sending</div>
                </div>
                <div class="pipeline-node">
                    <div class="node-ring" style="--node-color:var(--green);"><i class="bi bi-check2"></i></div>
                    <div class="node-count" id="nodeDelivered">0</div>
                    <div class="node-label">Delivered</div>
                </div>
            </div>
        </div>

        <!-- ============ CONTROLS ============ -->
        <div class="control-bar">
            <button class="filter-chip active" data-filter="all">All</button>
            <button class="filter-chip" data-filter="queued">Queued</button>
            <button class="filter-chip" data-filter="sending">Sending</button>
            <button class="filter-chip" data-filter="delivered">Delivered</button>
            <button class="filter-chip" data-filter="failed">Failed</button>

            <div class="search-wrap ms-auto" style="min-width:200px;">
                <i class="bi bi-search"></i>
                <input type="text" class="search-box w-100" id="searchInput" placeholder="Search recipient…">
            </div>

            <button class="btn-ghost" id="pauseBtn"><i class="bi bi-pause-fill"></i> Pause queue</button>
            <button class="btn-brand" id="addBtn"><i class="bi bi-plus-lg"></i> Enqueue message</button>
        </div>

        <!-- ============ TABLE ============ -->
        <div class="queue-table-wrap">
            <table class="queue-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Recipient</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Queued at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
            <div class="empty-state d-none" id="emptyState">
                <i class="bi bi-inbox"></i>
                <div class="mt-2">No messages match this view.</div>
            </div>
        </div>

        <div class="foot">
            <div>QueueWA microprogram · dummy interface, no real messages are sent</div>
            <div>v0.4.1 <span class="dot-sep">•</span> region: ap-south-1 <span class="dot-sep">•</span> worker pool:
                3/3 healthy</div>
        </div>
    </div>

    <div class="toast-stack" id="toastStack"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* ================= state ================= */
        const names = ["Aarav Menon", "Fathima K.", "Rahul Nair", "Sneha Pillai", "Vishnu Das", "Anjali S.", "Mohammed I.",
            "Deepa R.", "Arjun T.", "Kavya M.", "Nikhil P.", "Divya S.", "Sooraj V.", "Meera J.", "Akhil B."
        ];
        const messages = [
            "Your order #4821 has shipped 🚚",
            "Reminder: appointment tomorrow at 10 AM",
            "OTP for login is 583092, valid 5 min",
            "Thank you for your payment of ₹1,299",
            "Your subscription renews in 3 days",
            "New offer just for you — 20% off today",
            "Delivery attempted, please reschedule",
            "Your ticket #A203 has been resolved",
            "Booking confirmed for 2 guests, 7 PM",
            "We miss you! Come back for a surprise"
        ];

        let queue = [];
        let idCounter = 1000;
        let paused = false;
        let currentFilter = "all";
        let searchTerm = "";
        let deliveredTotal = 0;

        function randInt(a, b) {
            return Math.floor(Math.random() * (b - a + 1)) + a;
        }

        function pick(arr) {
            return arr[randInt(0, arr.length - 1)];
        }

        function initials(name) {
            return name.split(" ").map(w => w[0]).join("").slice(0, 2).toUpperCase();
        }

        function timeNow() {
            const d = new Date();
            return d.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function makeMessage() {
            idCounter++;
            return {
                id: "MSG-" + idCounter,
                name: pick(names),
                text: pick(messages),
                status: "queued",
                time: timeNow()
            };
        }

        /* seed initial queue */
        for (let i = 0; i < 7; i++) {
            const m = makeMessage();
            if (i < 2) m.status = "delivered";
            if (i === 2) m.status = "sending";
            if (i === 3) m.status = "failed";
            queue.push(m);
        }

        /* ================= rendering ================= */
        function counts() {
            return {
                queued: queue.filter(m => m.status === "queued").length,
                sending: queue.filter(m => m.status === "sending").length,
                delivered: queue.filter(m => m.status === "delivered").length,
                failed: queue.filter(m => m.status === "failed").length,
            };
        }

        function animateNumber(el, to) {
            const from = parseInt(el.textContent) || 0;
            if (from === to) return;
            const dur = 400,
                start = performance.now();

            function step(t) {
                const p = Math.min(1, (t - start) / dur);
                el.textContent = Math.round(from + (to - from) * p);
                if (p < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        }

        function updateStats() {
            const c = counts();
            animateNumber(document.getElementById('statQueued'), c.queued);
            animateNumber(document.getElementById('statSending'), c.sending);
            animateNumber(document.getElementById('statDelivered'), deliveredTotal);
            animateNumber(document.getElementById('statFailed'), c.failed);
            animateNumber(document.getElementById('nodeQueued'), c.queued);
            animateNumber(document.getElementById('nodeSending'), c.sending);
            animateNumber(document.getElementById('nodeDelivered'), deliveredTotal);
            document.getElementById('throughput').textContent = paused ? "0" : randInt(8, 22);
        }

        function badgeFor(status) {
            const map = {
                queued: ['badge-queued', 'Queued'],
                sending: ['badge-sending', 'Sending'],
                delivered: ['badge-delivered', 'Delivered'],
                failed: ['badge-failed', 'Failed']
            };
            const [cls, label] = map[status];
            return `<span class="badge-status ${cls}"><span class="dot"></span>${label}</span>`;
        }

        function renderTable(newestId) {
            const tbody = document.getElementById('tableBody');
            const empty = document.getElementById('emptyState');
            let rows = [...queue].reverse().filter(m => {
                const matchFilter = currentFilter === "all" || m.status === currentFilter;
                const matchSearch = m.name.toLowerCase().includes(searchTerm.toLowerCase());
                return matchFilter && matchSearch;
            });

            if (rows.length === 0) {
                tbody.innerHTML = "";
                empty.classList.remove('d-none');
            } else {
                empty.classList.add('d-none');
                tbody.innerHTML = rows.map(m => `
      <tr class="${m.id===newestId ? 'row-enter' : ''}">
        <td class="msg-id">${m.id}</td>
        <td class="recipient"><span class="avatar-dot">${initials(m.name)}</span>${m.name}</td>
        <td class="msg-preview" title="${m.text}">${m.text}</td>
        <td>${badgeFor(m.status)}</td>
        <td class="msg-id">${m.time}</td>
        <td>${m.status==="failed" ? `<button class="retry-btn" onclick="retryMessage('${m.id}')"><i class="bi bi-arrow-clockwise"></i> Retry</button>` : ""}</td>
      </tr>
    `).join("");
            }
            updateStats();
        }

        /* ================= actions ================= */
        function retryMessage(id) {
            const m = queue.find(x => x.id === id);
            if (!m) return;
            m.status = "queued";
            m.time = timeNow();
            showToast(`${m.id} re-queued for delivery`);
            renderTable(id);
            processQueue();
        }

        function showToast(text, tone = "ok") {
            const stack = document.getElementById('toastStack');
            const el = document.createElement('div');
            el.className = "mini-toast";
            if (tone === "fail") el.style.borderLeftColor = "var(--coral)";
            el.innerHTML =
                `<i class="bi ${tone==='fail' ? 'bi-x-circle-fill' : 'bi-check-circle-fill'}" style="color:${tone==='fail' ? 'var(--coral)' : 'var(--green)'}; margin-right:0.4rem;"></i>${text}`;
            stack.appendChild(el);
            setTimeout(() => el.remove(), 3100);
        }

        function enqueueNew() {
            const m = makeMessage();
            queue.push(m);
            showToast(`${m.id} added to queue`);
            renderTable(m.id);
            processQueue();
        }

        function processQueue() {
            if (paused) return;
            const nextQueued = queue.find(m => m.status === "queued");
            const alreadySending = queue.some(m => m.status === "sending");
            if (nextQueued && !alreadySending) {
                setTimeout(() => {
                    if (paused) return;
                    nextQueued.status = "sending";
                    renderTable();
                    setTimeout(() => {
                        if (paused) return;
                        const success = Math.random() > 0.12;
                        nextQueued.status = success ? "delivered" : "failed";
                        if (success) deliveredTotal++;
                        if (!success) showToast(`${nextQueued.id} failed to deliver`, "fail");
                        renderTable();
                        processQueue();
                    }, randInt(1400, 2200));
                }, randInt(400, 900));
            }
        }

        /* ================= flow dots (ambient pipeline animation) ================= */
        function spawnFlowDots() {
            ['lane1', 'lane2'].forEach(laneId => {
                const lane = document.getElementById(laneId);
                for (let i = 0; i < 3; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'flow-dot';
                    dot.style.animationDuration = randInt(3, 5) + 's';
                    dot.style.animationDelay = (i * 1.3) + 's';
                    if (laneId === 'lane2' && Math.random() < 0.12) dot.classList.add('fail-dot');
                    lane.appendChild(dot);
                }
            });
        }

        /* ================= events ================= */
        document.querySelectorAll('.filter-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-chip').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.dataset.filter;
                renderTable();
            });
        });

        document.getElementById('searchInput').addEventListener('input', (e) => {
            searchTerm = e.target.value;
            renderTable();
        });

        document.getElementById('addBtn').addEventListener('click', enqueueNew);

        document.getElementById('pauseBtn').addEventListener('click', function() {
            paused = !paused;
            this.classList.toggle('paused', paused);
            this.innerHTML = paused ?
                '<i class="bi bi-play-fill"></i> Resume queue' :
                '<i class="bi bi-pause-fill"></i> Pause queue';
            showToast(paused ? "Queue processing paused" : "Queue processing resumed");
            if (!paused) processQueue();
            updateStats();
        });

        /* clock */
        function tickClock() {
            document.getElementById('clock').textContent = new Date().toLocaleTimeString();
        }
        setInterval(tickClock, 1000);
        tickClock();

        /* init */
        renderTable();
        spawnFlowDots();
        processQueue();

        /* periodic ambient new arrivals to keep it feeling alive */
        setInterval(() => {
            if (!paused && queue.length < 40 && Math.random() < 0.5) {
                const m = makeMessage();
                queue.push(m);
                renderTable(m.id);
                processQueue();
            }
        }, 6000);
    </script>
</body>

</html>
