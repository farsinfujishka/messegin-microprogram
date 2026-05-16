<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>E-Invoice - {{ $invoice_no }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f0f4f0;
            font-family: 'DM Sans', sans-serif;
            color: #1a2e1a;
            -webkit-font-smoothing: antialiased;
        }

        .email-wrapper {
            max-width: 620px;
            margin: 40px auto;
            padding: 0 16px 40px;
        }

        /* ── Header Bar ── */
        .header {
            background: linear-gradient(135deg, #1a4a2e 0%, #2d7a4f 60%, #3aaa6e 100%);
            border-radius: 16px 16px 0 0;
            padding: 36px 40px 32px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: 60px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .header-company {
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.6);
            margin-bottom: 10px;
        }

        .header-title {
            font-family: 'DM Serif Display', serif;
            font-size: 32px;
            color: #ffffff;
            line-height: 1.15;
        }

        .header-title em {
            font-style: italic;
            color: #a8f0c6;
        }

        .invoice-badge {
            display: inline-block;
            margin-top: 14px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 100px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255,255,255,0.85);
            letter-spacing: 0.5px;
        }

        /* ── Body Card ── */
        .card {
            background: #ffffff;
            padding: 40px;
            border-left: 1px solid #d8ead8;
            border-right: 1px solid #d8ead8;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1a2e1a;
            margin-bottom: 14px;
        }

        .greeting span {
            color: #2d7a4f;
        }

        .body-text {
            font-size: 15px;
            line-height: 1.75;
            color: #4a604a;
            margin-bottom: 24px;
        }

        /* ── Invoice Detail Box ── */
        .detail-box {
            background: #f4faf5;
            border: 1px solid #c6e3cb;
            border-radius: 12px;
            padding: 22px 26px;
            margin-bottom: 32px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #d4e8d4;
        }

        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-row:first-child {
            padding-top: 0;
        }

        .detail-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #7aaa8a;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a3a1a;
            text-align: right;
        }

        /* ── CTA Button ── */
        .cta-wrapper {
            text-align: center;
            margin-bottom: 32px;
        }

        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #1a4a2e, #2d7a4f);
            color: #ffffff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
            padding: 16px 42px;
            border-radius: 100px;
            box-shadow: 0 6px 24px rgba(45, 122, 79, 0.38);
        }

        .cta-sub {
            margin-top: 14px;
            font-size: 12px;
            color: #8aaa8a;
        }

        .cta-sub a {
            color: #2d7a4f;
            word-break: break-all;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #e0eee0;
            margin: 28px 0;
        }

        .note-text {
            font-size: 13px;
            line-height: 1.7;
            color: #7a9a7a;
        }

        /* ── Footer ── */
        .footer {
            background: #1a2e1a;
            border-radius: 0 0 16px 16px;
            padding: 28px 40px;
            text-align: center;
        }

        .footer-company {
            font-family: 'DM Serif Display', serif;
            font-size: 16px;
            color: #a8f0c6;
            margin-bottom: 6px;
        }

        .footer-text {
            font-size: 12px;
            color: rgba(255,255,255,0.35);
            line-height: 1.6;
        }

        .footer-dot {
            display: inline-block;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: rgba(255,255,255,0.25);
            margin: 0 8px;
            vertical-align: middle;
        }

        /* ── Accent Leaf ── */
        .leaf-accent {
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }

        .leaf-accent svg {
            opacity: 0.18;
        }
    </style>
</head>
<body>

<div class="email-wrapper">

    {{-- ── Header ── --}}
    <div class="header">
        <div class="header-company">{{ $company_name }}</div>
        <div class="header-title">Your <em>E-Invoice</em><br>is Ready</div>
        <span class="invoice-badge">Invoice #{{ $invoice_no }}</span>
    </div>

    {{-- ── Body Card ── --}}
    <div class="card">

        <p class="greeting">Hello, <span>{{ $customer_name }}</span> 👋</p>

        <p class="body-text">
            We hope this message finds you well. Your e-invoice has been generated
            and is ready for your review. Please find the details below and use
            the secure link to access your invoice at any time.
        </p>

        {{-- ── Invoice Details ── --}}
        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Invoice No. : </span>
                <span class="detail-value">{{ $invoice_no }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Prepared For : </span>
                <span class="detail-value">{{ $customer_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Issued By : </span>
                <span class="detail-value">{{ $company_name }}</span>
            </div>
        </div>

        {{-- ── CTA ── --}}
        <div class="cta-wrapper">
            {{-- Table-based button for maximum email client compatibility --}}
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0 auto;">
                <tr>
                    <td style="border-radius: 100px; background: linear-gradient(135deg, #1a4a2e, #2d7a4f); box-shadow: 0 6px 24px rgba(45,122,79,0.38);">
                        <a href="{{ $url_link }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           style="display: inline-block;
                                  padding: 16px 42px;
                                  font-family: 'DM Sans', sans-serif;
                                  font-size: 15px;
                                  font-weight: 600;
                                  letter-spacing: 0.3px;
                                  color: #ffffff;
                                  text-decoration: none;
                                  border-radius: 100px;
                                  mso-padding-alt: 0;
                                  -webkit-text-size-adjust: none;">
                            &#128196;&nbsp; View E-Invoice
                        </a>
                    </td>
                </tr>
            </table>
            <p class="cta-sub">
                Or copy this link:<br>
                <a href="{{ $url_link }}" target="_blank" rel="noopener noreferrer">{{ $url_link }}</a>
            </p>
        </div>

        <hr class="divider">

        <p class="note-text">
            This link is secure and intended only for <strong>{{ $customer_name }}</strong>.
            If you did not expect this email or believe it was sent in error,
            please disregard it or contact us immediately. Do not share this
            link with others.
        </p>

    </div>

    {{-- ── Footer ── --}}
    <div class="footer">
        <div class="footer-company">{{ $company_name }}</div>
        <div class="footer-text">
            This is an automated email — please do not reply directly.
            <br>
            © {{ date('Y') }} {{ $company_name }}. All rights reserved.
        </div>
    </div>

</div>

</body>
</html>