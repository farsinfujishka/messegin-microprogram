<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QueueWA — one API, message queued and delivered</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
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

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background:
                radial-gradient(1100px 500px at 90% -10%, rgba(37, 211, 102, .08), transparent 60%),
                radial-gradient(900px 500px at -10% 30%, rgba(83, 189, 235, .05), transparent 60%),
                var(--bg);
            color: var(--text);
            font-family: var(--font-body);
            -webkit-font-smoothing: antialiased;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: .001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .001ms !important;
            }
        }

        a {
            color: inherit;
        }

        .wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* ---------- nav ---------- */
        nav {
            position: sticky;
            top: 0;
            z-index: 60;
            background: rgba(11, 20, 26, .82);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
        }

        nav .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 62px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.05rem;
        }

        .brand-mark {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: linear-gradient(145deg, var(--green), var(--green-deep));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #06251b;
            font-size: .85rem;
            flex-shrink: 0;
        }

        .nav-links {
            display: flex;
            gap: 1.75rem;
            font-size: .85rem;
            color: var(--text-dim);
        }

        .nav-links a {
            text-decoration: none;
            transition: color .15s;
        }

        .nav-links a:hover {
            color: var(--text);
        }

        .nav-cta {
            font-family: var(--font-mono);
            font-size: .78rem;
            background: linear-gradient(145deg, var(--green), var(--green-deep));
            color: #06251b;
            border: none;
            padding: .5rem 1rem;
            border-radius: 9px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        /* ---------- hero ---------- */
        .hero {
            padding: 5rem 0 4rem;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            gap: 3.5rem;
            align-items: center;
        }

        .eyebrow {
            font-family: var(--font-mono);
            font-size: .72rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--green);
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .eyebrow .live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 0 0 rgba(37, 211, 102, .6);
            animation: pulseDot 1.8s ease-out infinite;
        }

        @keyframes pulseDot {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, .55);
            }

            70% {
                box-shadow: 0 0 0 7px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        h1 {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 2.9rem;
            line-height: 1.08;
            margin: .9rem 0 1.1rem;
            letter-spacing: -.5px;
        }

        h1 .accent {
            color: var(--green);
        }

        .hero-sub {
            color: var(--text-dim);
            font-size: 1.05rem;
            line-height: 1.6;
            max-width: 520px;
            margin-bottom: 1.9rem;
        }

        .hero-actions {
            display: flex;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .btn-brand {
            background: linear-gradient(145deg, var(--green), var(--green-deep));
            border: none;
            color: #06251b;
            font-weight: 600;
            border-radius: 11px;
            padding: .75rem 1.35rem;
            font-size: .92rem;
            box-shadow: 0 8px 20px -8px rgba(37, 211, 102, .5);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            border-radius: 11px;
            padding: .75rem 1.3rem;
            font-size: .92rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }

        .btn-ghost:hover {
            border-color: var(--text-dim);
        }

        .hero-meta {
            display: flex;
            gap: 1.8rem;
            margin-top: 2.2rem;
            font-family: var(--font-mono);
            font-size: .76rem;
            color: var(--text-dim);
        }

        .hero-meta b {
            color: var(--text);
            font-family: var(--font-body);
            font-weight: 600;
        }

        /* ---------- signature: request -> queue -> delivery capsule ---------- */
        .capsule-card {
            background: linear-gradient(180deg, var(--surface-2), var(--surface));
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.1rem;
            position: relative;
            overflow: hidden;
        }

        .capsule-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .2rem .3rem .8rem;
        }

        .capsule-title {
            font-family: var(--font-mono);
            font-size: .72rem;
            color: var(--text-dim);
            letter-spacing: .5px;
        }

        .capsule-dots span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-left: 5px;
        }

        .capsule-track {
            position: relative;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 13px;
            padding: 1.4rem 1rem 1.6rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .capsule-stage {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .5rem;
            width: 78px;
            z-index: 2;
        }

        .stage-ring {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--surface-2);
            border: 2px solid var(--sc, var(--text-dim));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sc, var(--text-dim));
            font-size: 1.05rem;
        }

        .stage-label {
            font-family: var(--font-mono);
            font-size: .62rem;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--text-dim);
            text-align: center;
        }

        .capsule-rail {
            position: absolute;
            top: 37px;
            left: 56px;
            right: 56px;
            height: 2px;
            background: repeating-linear-gradient(90deg, var(--border) 0 7px, transparent 7px 14px);
            z-index: 1;
        }

        .ball {
            position: absolute;
            top: 31px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 10px var(--green);
            left: 56px;
            z-index: 3;
            animation: travel 3.2s ease-in-out infinite;
        }

        @keyframes travel {
            0% {
                left: 56px;
                opacity: 0;
                background: var(--text-dim);
                box-shadow: 0 0 10px var(--text-dim);
            }

            6% {
                opacity: 1;
            }

            30% {
                left: calc(50% - 6px);
                background: var(--blue);
                box-shadow: 0 0 10px var(--blue);
            }

            62% {
                left: calc(50% - 6px);
                background: var(--blue);
                box-shadow: 0 0 10px var(--blue);
            }

            90% {
                left: calc(100% - 68px);
                background: var(--green);
                box-shadow: 0 0 10px var(--green);
            }

            100% {
                left: calc(100% - 68px);
                opacity: 0;
            }
        }

        .capsule-code {
            margin-top: 1rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .9rem 1rem;
            font-family: var(--font-mono);
            font-size: .74rem;
            line-height: 1.65;
            color: var(--text-dim);
            overflow: hidden;
        }

        .capsule-code .k {
            color: var(--blue);
        }

        .capsule-code .s {
            color: var(--green);
        }

        .capsule-code .c {
            color: var(--text-dim);
        }

        .capsule-phone {
            margin-top: 1rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .85rem 1rem;
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .bubble {
            background: rgba(37, 211, 102, .12);
            border: 1px solid rgba(37, 211, 102, .3);
            color: var(--text);
            font-size: .78rem;
            padding: .5rem .75rem;
            border-radius: 10px 10px 10px 2px;
            font-family: var(--font-body);
        }

        .bubble .to {
            display: block;
            font-family: var(--font-mono);
            font-size: .66rem;
            color: var(--green);
            margin-bottom: .2rem;
        }

        /* ---------- section shell ---------- */
        section {
            padding: 4.2rem 0;
        }

        .section-head {
            max-width: 640px;
            margin-bottom: 2.6rem;
        }

        .section-title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 2rem;
            margin: .5rem 0 .7rem;
            letter-spacing: -.3px;
        }

        .section-sub {
            color: var(--text-dim);
            font-size: .98rem;
            line-height: 1.6;
        }

        /* ---------- how it works (real sequence -> numbering justified) ---------- */
        .flow-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.1rem;
            position: relative;
        }

        .flow-card {
            background: linear-gradient(180deg, var(--surface-2), var(--surface));
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem 1.4rem;
            position: relative;
        }

        .flow-num {
            font-family: var(--font-mono);
            font-size: .72rem;
            color: var(--text-dim);
        }

        .flow-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            background: rgba(255, 255, 255, .03);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fc, var(--green));
            font-size: 1.1rem;
            margin: .8rem 0 1rem;
        }

        .flow-card h3 {
            font-family: var(--font-display);
            font-size: 1.08rem;
            margin: 0 0 .5rem;
        }

        .flow-card p {
            color: var(--text-dim);
            font-size: .86rem;
            line-height: 1.6;
            margin: 0;
        }

        .flow-arrow {
            position: absolute;
            top: 2.4rem;
            right: -1.55rem;
            color: var(--text-dim);
            font-size: 1.1rem;
            z-index: 2;
        }

        @media (max-width:860px) {
            .flow-row {
                grid-template-columns: 1fr;
            }

            .flow-arrow {
                display: none;
            }
        }

        /* ---------- channels ---------- */
        .channel-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        .channel-card {
            background: linear-gradient(180deg, var(--surface-2), var(--surface));
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .channel-card .tag {
            font-family: var(--font-mono);
            font-size: .68rem;
            letter-spacing: .5px;
            text-transform: uppercase;
            padding: .28rem .6rem;
            border-radius: 100px;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        .tag.wa {
            background: rgba(37, 211, 102, .12);
            color: var(--green);
            border: 1px solid rgba(37, 211, 102, .3);
        }

        .tag.em {
            background: rgba(83, 189, 235, .12);
            color: var(--blue);
            border: 1px solid rgba(83, 189, 235, .3);
        }

        .channel-card h3 {
            font-family: var(--font-display);
            font-size: 1.15rem;
            margin: .8rem 0 .5rem;
        }

        .channel-card p {
            color: var(--text-dim);
            font-size: .87rem;
            line-height: 1.6;
        }

        @media (max-width:760px) {
            .channel-row {
                grid-template-columns: 1fr;
            }
        }

        /* ---------- feature grid ---------- */
        .feat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .feat-card {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
        }

        .feat-card i {
            color: var(--green);
            font-size: 1.05rem;
        }

        .feat-card h4 {
            font-family: var(--font-display);
            font-size: .98rem;
            margin: .7rem 0 .4rem;
        }

        .feat-card p {
            color: var(--text-dim);
            font-size: .83rem;
            line-height: 1.55;
            margin: 0;
        }

        @media (max-width:860px) {
            .feat-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width:560px) {
            .feat-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ---------- code block section ---------- */
        .code-shell {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .code-tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
        }

        .code-tab {
            font-family: var(--font-mono);
            font-size: .78rem;
            padding: .75rem 1.2rem;
            color: var(--text-dim);
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .code-tab.active {
            color: var(--text);
            border-bottom-color: var(--green);
            background: var(--surface-3);
        }

        pre {
            margin: 0;
            padding: 1.3rem 1.4rem;
            font-family: var(--font-mono);
            font-size: .82rem;
            line-height: 1.7;
            color: var(--text-dim);
            overflow-x: auto;
        }

        pre .k {
            color: var(--blue);
        }

        pre .s {
            color: var(--green);
        }

        pre .n {
            color: var(--amber);
        }

        .code-pane {
            display: none;
        }

        .code-pane.active {
            display: block;
        }

        /* ---------- status strip ---------- */
        .status-strip {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
            margin-top: 1.4rem;
        }

        .status-chip {
            font-family: var(--font-mono);
            font-size: .72rem;
            padding: .35rem .7rem;
            border-radius: 100px;
            display: flex;
            align-items: center;
            gap: .4rem;
            border: 1px solid var(--border);
            color: var(--text-dim);
        }

        .status-chip .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-chip.q .dot {
            background: var(--text-dim);
        }

        .status-chip.s .dot {
            background: var(--blue);
        }

        .status-chip.d .dot {
            background: var(--green);
        }

        .status-chip.f .dot {
            background: var(--coral);
        }

        /* ---------- cta ---------- */
        .cta-band {
            background: linear-gradient(135deg, rgba(37, 211, 102, .1), rgba(83, 189, 235, .05));
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            text-align: center;
        }

        .cta-band h2 {
            font-family: var(--font-display);
            font-size: 1.75rem;
            margin: .4rem 0 .8rem;
        }

        .cta-band p {
            color: var(--text-dim);
            max-width: 480px;
            margin: 0 auto 1.6rem;
        }

        footer {
            border-top: 1px solid var(--border);
            padding: 2rem 0;
            color: var(--text-dim);
            font-size: .8rem;
        }

        footer .wrap {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .6rem;
        }

        @media (max-width:900px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 2.2rem;
            }

            .feat-grid {
                grid-template-columns: 1fr 1fr;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>

<body>

    <nav>
        <div class="wrap">
            <div class="brand">
                <div class="brand-mark"><i class="bi bi-diagram-3-fill"></i></div>BQuick
            </div>
            <div class="nav-links">
                <a href="#how">How it works</a>
                <a href="#channels">Channels</a>
                <a href="#features">Features</a>
            </div>
        </div>
    </nav>

    <!-- ============ HERO ============ -->
    <header class="hero">
        <div class="wrap hero-grid">
            <div>
                <div class="eyebrow"><span class="live-dot"></span> Worker pool healthy · 3/3</div>
                <h1>Send a message. <span class="accent">We queue it, we deliver it.</span></h1>
                <p class="hero-sub">
                    One API call with a phone number and a message is all you send. QueueWA takes it from there — queues
                    the job,
                    works through it in order, and hands it off to WhatsApp or email until it's confirmed delivered,
                    retrying
                    on its own when a send fails.
                </p>
                <div class="hero-actions">
                    <a class="btn-ghost" href="#how"><i class="bi bi-arrow-down"></i> How it works</a>
                </div>
                <div class="hero-meta">
                    <div><b>2</b> channels — WhatsApp &amp; Email</div>
                    <div><b>&lt;1s</b> to queue a job</div>
                    <div><b>Auto</b> retry on failure</div>
                </div>
            </div>

            <div class="capsule-card">
                <div class="capsule-head">
                    <span class="capsule-title">REQUEST → QUEUE → DELIVERY</span>
                    <span class="capsule-dots"><span style="background:var(--coral);"></span><span
                            style="background:var(--amber);"></span><span
                            style="background:var(--green);"></span></span>
                </div>
                <div class="capsule-track">
                    <div class="capsule-rail"></div>
                    <div class="ball"></div>
                    <div class="capsule-stage">
                        <div class="stage-ring" style="--sc:var(--text-dim);"><i class="bi bi-arrow-up-right"></i></div>
                        <div class="stage-label">API call</div>
                    </div>
                    <div class="capsule-stage">
                        <div class="stage-ring" style="--sc:var(--blue);"><i class="bi bi-hourglass-split"></i></div>
                        <div class="stage-label">Queued job</div>
                    </div>
                    <div class="capsule-stage">
                        <div class="stage-ring" style="--sc:var(--green);"><i class="bi bi-check2"></i></div>
                        <div class="stage-label">Delivered</div>
                    </div>
                </div>
                <div class="capsule-code"><span class="c">POST</span> <span class="k">/v1/messages</span>
                    <span class="c">{</span>
                    <span class="k">"to"</span>: <span class="s">"+91 98470 xxxxx"</span>,
                    <span class="k">"channel"</span>: <span class="s">"whatsapp"</span>,
                    <span class="k">"message"</span>: <span class="s">"Your order has shipped"</span>
                    <span class="c">}</span>
                </div>
                <div class="capsule-phone">
                    <div class="bubble"><span class="to">to +91 98470 xxxxx</span>Your order has shipped 🚚</div>
                </div>
            </div>
        </div>
    </header>

    <!-- ============ HOW IT WORKS ============ -->
    <section id="how">
        <div class="wrap">
            <div class="section-head">
                <div class="eyebrow">The pipeline</div>
                <div class="section-title">What happens after you call the API</div>
                <div class="section-sub">This part really is a fixed order — a job moves through exactly these three
                    stages, in this sequence, every time.</div>
            </div>
            <div class="flow-row">
                <div class="flow-card">
                    <div class="flow-num">01</div>
                    <div class="flow-icon"><i class="bi bi-inbox-fill"></i></div>
                    <h3>Request comes in</h3>
                    <p>Your backend sends a message and a phone number (or email address) to the API. QueueWA validates
                        it and hands back a job ID right away — it doesn't wait for the send to finish.</p>
                    <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
                </div>
                <div class="flow-card">
                    <div class="flow-num">02</div>
                    <div class="flow-icon" style="--fc:var(--blue);"><i class="bi bi-arrow-repeat"></i></div>
                    <h3>Job sits in the queue</h3>
                    <p>The job is placed on a queue and picked up by a worker in order. If a send fails — number
                        unreachable, provider timeout — it's automatically re-queued for another attempt.</p>
                    <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
                </div>
                <div class="flow-card">
                    <div class="flow-num">03</div>
                    <div class="flow-icon"><i class="bi bi-check2-circle"></i></div>
                    <h3>Message is delivered</h3>
                    <p>The worker sends it out over WhatsApp or email and updates the job's status. You can poll for
                        status or receive a webhook the moment it's delivered — or fails for good.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CHANNELS ============ -->
    <section id="channels">
        <div class="wrap">
            <div class="section-head">
                <div class="eyebrow">Same queue, two destinations</div>
                <div class="section-title">WhatsApp and email, one request shape</div>
                <div class="section-sub">Switch the <code
                        style="font-family:var(--font-mono); color:var(--text);">channel</code> field and everything
                    else about the request stays the same.</div>
            </div>
            <div class="channel-row">
                <div class="channel-card">
                    <span class="tag wa"><i class="bi bi-whatsapp"></i> WhatsApp</span>
                    <h3>Deliver straight to a phone number</h3>
                    <p>Give it a phone number and a message body. The worker sends it through your connected WhatsApp
                        sender and tracks queued → sending → delivered, with retries on transient failures.</p>
                </div>
                <div class="channel-card">
                    <span class="tag em"><i class="bi bi-envelope-fill"></i> Email</span>
                    <h3>Deliver straight to an inbox</h3>
                    <p>Give it an email address instead — the same job moves through the same queue, just routed to your
                        email sender. One integration covers both channels.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FEATURES ============ -->
    <section id="features">
        <div class="wrap">
            <div class="section-head">
                <div class="eyebrow">Built for the queue, not around it</div>
                <div class="section-title">What you don't have to build yourself</div>
            </div>
            <div class="feat-grid">
                <div class="feat-card"><i class="bi bi-arrow-clockwise"></i>
                    <h4>Automatic retries</h4>
                    <p>Failed sends go back on the queue with backoff, instead of silently dropping.</p>
                </div>
                <div class="feat-card"><i class="bi bi-diagram-3"></i>
                    <h4>One queue, two channels</h4>
                    <p>WhatsApp and email jobs share the same queue, worker pool, and status model.</p>
                </div>
                <div class="feat-card"><i class="bi bi-graph-up"></i>
                    <h4>Live status dashboard</h4>
                    <p>Watch jobs move from queued to delivered in real time, and retry failures by hand.</p>
                </div>
                <div class="feat-card"><i class="bi bi-webhook"></i>
                    <h4>Delivery webhooks</h4>
                    <p>Get notified the moment a job is delivered or fails for good — no polling required.</p>
                </div>
                <div class="feat-card"><i class="bi bi-speedometer2"></i>
                    <h4>Rate-aware workers</h4>
                    <p>Sends are paced to stay within your WhatsApp and email provider limits automatically.</p>
                </div>
                <div class="feat-card"><i class="bi bi-shield-check"></i>
                    <h4>Idempotent by job ID</h4>
                    <p>Retry the same request safely — QueueWA won't double-send a job it already queued.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CTA ============ -->
    <section>
        <div class="wrap">
            <div class="cta-band">
                <div class="eyebrow" style="justify-content:center;">Ready when you are</div>
                <h2>Point one endpoint at it. We'll handle the queue.</h2>
                <p>Send your first message and watch it move from queued to delivered on the dashboard.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="wrap">
            <div>BQuick · message queue for whatsApp &amp; email delivery</div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.code-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.code-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.code-pane').forEach(p => p.classList.remove('active'));
                tab.classList.add('active');
                document.querySelector(`.code-pane[data-pane="${tab.dataset.tab}"]`).classList.add(
                'active');
            });
        });
    </script>
</body>

</html>
