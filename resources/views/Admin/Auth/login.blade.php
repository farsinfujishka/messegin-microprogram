<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bquick DB Manage</title>
    <link rel="shortcut icon" href="{{ asset('admin/images/al_hazmi_fav.jpg') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sage:       #6b8f71;
            --sage-dark:  #4a6b50;
            --sage-light: #a8c5ad;
            --cream:      #f5f0e8;
            --text-dark:  #1e2d22;
            --text-mid:   #4a5e4e;
            --text-soft:  #7a9080;
            --glass-bg:   rgba(255,255,255,0.55);
            --glass-border: rgba(255,255,255,0.75);
            --shadow:     0 24px 64px rgba(30,45,34,0.18);
            --radius:     18px;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
            background: #7a9e82;
            overflow: hidden;
            position: relative;
        }

        /* Layered background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%, #9ec4a4 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 90%, #4a7a52 0%, transparent 55%),
                radial-gradient(ellipse 50% 50% at 50% 50%, #6b9e73 0%, #3d6644 100%);
            z-index: 0;
        }

        /* Floating decorative blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.35;
            pointer-events: none;
            animation: drift 12s ease-in-out infinite alternate;
        }
        .blob-1 { width: 420px; height: 420px; background: #b8d9be; top: -100px; left: -80px; animation-delay: 0s; }
        .blob-2 { width: 300px; height: 300px; background: #3a6140; bottom: -60px; right: -60px; animation-delay: -4s; }
        .blob-3 { width: 200px; height: 200px; background: #c8e6cc; top: 55%; left: 10%; animation-delay: -8s; }

        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, 20px) scale(1.06); }
        }

        /* Card */
        .card-wrap {
            position: relative;
            z-index: 10;
            animation: rise 0.7s cubic-bezier(.22,.68,0,1.2) both;
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(32px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .login-card {
            width: 400px;
            background: var(--glass-bg);
            border: 1.5px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            padding: 44px 40px 40px;
        }

        /* Logo area */
        .logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 36px;
        }
        .logo-area img {
            width: 100px;
            margin-bottom: 14px;
            filter: drop-shadow(0 4px 12px rgba(74,107,80,0.25));
        }
        .logo-area .tagline {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: var(--text-dark);
            letter-spacing: 0.01em;
        }
        .logo-area .sub {
            font-size: 12px;
            color: var(--text-soft);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .divider span { flex: 1; height: 1px; background: linear-gradient(90deg, transparent, #9ab89f, transparent); }
        .divider p { font-size: 11px; color: var(--text-soft); letter-spacing: 0.1em; text-transform: uppercase; white-space: nowrap; }

        /* Form fields */
        .field-group {
            margin-bottom: 18px;
            position: relative;
        }
        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-mid);
            margin-bottom: 7px;
        }
        .field-input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            background: rgba(255,255,255,0.7);
            border: 1.5px solid rgba(107,143,113,0.3);
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .field-input::placeholder { color: var(--text-soft); }
        .field-input:focus {
            border-color: var(--sage);
            background: rgba(255,255,255,0.9);
            box-shadow: 0 0 0 3px rgba(107,143,113,0.15);
        }
        .field-icon {
            position: absolute;
            left: 14px;
            top: 38px;
            color: var(--sage);
            pointer-events: none;
        }

        /* Parsley */
        .parsley-errors-list {
            list-style: none;
            padding: 0;
            margin: 5px 0 0;
            color: #c0392b;
            font-size: 12px;
        }
        .parsley-error  { border-color: #e74c3c !important; }
        .parsley-success{ border-color: var(--sage) !important; }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px;
            margin-top: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.04em;
            color: #fff;
            background: linear-gradient(135deg, var(--sage) 0%, var(--sage-dark) 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            box-shadow: 0 6px 20px rgba(74,107,80,0.35);
            position: relative;
            overflow: hidden;
        }
        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
        }
        .btn-login:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(74,107,80,0.4);
        }
        .btn-login:active:not(:disabled) { transform: translateY(0); }
        .btn-login:disabled { opacity: 0.72; cursor: not-allowed; }

        /* Footer note */
        .card-footer-note {
            text-align: center;
            font-size: 11px;
            color: var(--text-soft);
            margin-top: 24px;
            letter-spacing: 0.02em;
        }

        /* SVG icons inline */
        .icon { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    </style>
</head>

<body>
    <!-- Background blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="card-wrap">
        <div class="login-card">

            <!-- Logo -->
            <div class="logo-area">
                <div class="tagline">Queue Manager</div>
                <div class="sub">Admin Portal</div>
            </div>

            <div class="divider">
                <span></span><p>Sign in to continue</p><span></span>
            </div>

            <form id="loginForm" method="POST" action="{{ route('login') }}" data-parsley-validate>
                @csrf

                <!-- Email -->
                <div class="field-group">
                    <label class="field-label" for="email">Email Address</label>
                    <svg class="field-icon icon" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input id="email" type="email"
                        class="field-input @error('email') parsley-error @enderror"
                        name="email" value="{{ old('email') }}"
                        placeholder="you@example.com"
                        autocomplete="email" autofocus
                        required
                        data-parsley-required="true"
                        data-parsley-type="email"
                        data-parsley-required-message="Email is required"
                        data-parsley-type-message="Please enter a valid email address"
                        data-parsley-errors-container="#email_err">
                    <span id="email_err"></span>
                </div>

                <!-- Password -->
                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <svg class="field-icon icon" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input id="password" type="password"
                        class="field-input @error('password') parsley-error @enderror"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                        data-parsley-required="true"
                        data-parsley-minlength="6"
                        data-parsley-required-message="Password is required"
                        data-parsley-minlength-message="Password must be at least 6 characters"
                        data-parsley-errors-container="#password_err">
                    <span id="password_err"></span>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="btn-text">Sign In</span>
                    <svg class="icon btn-arrow" viewBox="0 0 24 24" style="width:15px;height:15px;">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>

            <p class="card-footer-note">Secure access &mdash; Fujishka Solution &copy; {{ date('Y') }}</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>

    <script>
        $(document).ready(function () {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "3000",
                extendedTimeOut: "1000"
            };

            @if (session()->has('message'))
                toastr.error("{{ session()->get('message') }}");
            @endif

            const parsleyForm = $('#loginForm').parsley({
                errorClass: 'parsley-error',
                successClass: 'parsley-success',
                errorsWrapper: '<ul class="parsley-errors-list"></ul>',
                errorTemplate: '<li></li>'
            });

            $('#loginForm').on('submit', function (e) {
                e.preventDefault();

                if (!parsleyForm.validate()) {
                    toastr.error('Please fix the errors in the form');
                    return false;
                }

                const $btn = $('#loginBtn');
                $btn.prop('disabled', true);
                $btn.find('.btn-text').text('Signing in…');
                $btn.find('.btn-arrow').addClass('d-none');
                $btn.find('.spinner-border').removeClass('d-none');

                $.ajax({
                    url: "{{ route('login') }}",
                    type: 'POST',
                    data: {
                        email: $('#email').val(),
                        password: $('#password').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        toastr.success(response.message ?? 'Login successful! Redirecting…');
                        setTimeout(function () {
                            window.location.href = response.redirect ?? "{{ route('queue.index') }}";
                        }, 1000);
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false);
                        $btn.find('.btn-text').text('Sign In');
                        $btn.find('.btn-arrow').removeClass('d-none');
                        $btn.find('.spinner-border').addClass('d-none');

                        let msg = 'An error occurred. Please try again.';
                        if (xhr.status === 422) {
                            const e = xhr.responseJSON.errors;
                            if (e.email) msg = e.email[0];
                            else if (e.password) msg = e.password[0];
                        } else if (xhr.status === 401) {
                            msg = xhr.responseJSON?.message || 'Invalid credentials';
                        } else if (xhr.status === 403) {
                            msg = xhr.responseJSON?.message || 'Permission denied';
                        }

                        toastr.error(msg);
                        setTimeout(() => window.location.reload(), 3000);
                    }
                });
            });
        });
    </script>
</body>

</html>