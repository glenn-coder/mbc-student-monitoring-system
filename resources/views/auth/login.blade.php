<x-guest-layout>
    <style>
        /* ── Form ── */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        /* ── Field Group ── */
        .field-group {
            margin-bottom: 10px;
            position: relative;
        }

        .field-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .field-input-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(13, 1, 182, 0.4);
            pointer-events: none;
            transition: color 0.2s;
        }

        .field-input-wrap:focus-within .field-icon {
            color: rgba(16, 7, 145, 0.7);
        }

        .field-input {
            width: 100%;
            padding: 13px 14px 13px 44px;
            /* glassmorphism: frosted white with blur */
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px) saturate(150%);
            -webkit-backdrop-filter: blur(10px) saturate(150%);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 12px;
            color: rgba(16, 7, 145, 0.7);
            font-size: 14.5px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.25s ease;
            caret-color: currentColor;
        }

        /* Fix for browser autocomplete/autofill overriding glassmorphism */
        .field-input:-webkit-autofill,
        .field-input:-webkit-autofill:hover,
        .field-input:-webkit-autofill:focus,
        .field-input:-webkit-autofill:active {
            transition: background-color 5000s ease-in-out 0s;
            -webkit-text-fill-color: rgba(16, 7, 145, 0.7) !important;
            -webkit-box-shadow: 0 0 0 30px transparent inset !important;
        }

        .field-input::placeholder {
            color: rgba(30, 27, 75, 0.38);
        }

        .field-input:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(15, 31, 134, 0.7);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.15);
        }

        /* ── Error state: solid red border + red glow ── */
        .field-input.error {
            border: 2px solid #ef4444;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25);
        }

        .field-input.error:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3);
        }

        /* Password toggle */
        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(30, 27, 75, 0.4);
            padding: 4px;
            transition: color 0.2s;
            display: flex;
            align-items: center;
        }

        .toggle-pw:hover {
            color: rgba(11, 6, 82, 0.8);
        }

        /* ── Error notification below input ── */
        .field-error {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 6px;
            padding: 0;
            background: none;
            border: none;
            color: #ff3b3b;
            font-size: 12.5px;
            font-weight: 500;
            animation: errShake 0.35s cubic-bezier(.36, .07, .19, .97) both;
        }

        .field-error svg {
            flex-shrink: 0;
            color: #ff3b3b;
        }

        @keyframes errShake {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-5px);
            }

            40% {
                transform: translateX(5px);
            }

            60% {
                transform: translateX(-3px);
            }

            80% {
                transform: translateX(3px);
            }
        }

        /* ── Remember + Forgot row ── */
        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            user-select: none;
            color: rgb(255, 255, 255);
        }

        .remember-check {
            appearance: none;
            -webkit-appearance: none;
            width: 14px;
            height: 14px;
            border: none;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.25);
            cursor: pointer;
            position: relative;
            transition: 0.2s ease;
            flex-shrink: 0;
        }

        .remember-check:hover {
            background: rgba(255, 255, 255, 0.38);
        }

        .remember-check:checked {
            background-color: #4f46e5 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath d='M2 6l3 3 5-5' stroke='white' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            background-size: 10px 10px !important;
            border-color: transparent !important;
        }

        .remember-text {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.9);
        }

        .forgot-link {
            font-size: 13px;
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            position: relative;
        }

        .forgot-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 0;
            height: 1px;
            background: #ffffff;
            transition: width 0.2s;
        }

        .forgot-link:hover {
            color: rgba(255, 255, 255, 0.75);
        }

        .forgot-link:hover::after {
            width: 100%;
        }

        /* ── Submit Button ── */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #1A2CA3;
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(13, 26, 99, 0.72);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(10, 7, 166, 0.62);
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        /* Spinner */
        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        .btn-login.loading .spinner {
            display: block;
        }

        .btn-login.loading .btn-text {
            opacity: 0.75;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Footer ── */
        .auth-footer {
            margin-top: 20px;
            text-align: center;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .auth-footer p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.98);
            margin: 0;
        }
    </style>

    {{-- Session Status --}}
    @if (session('status'))
    <x-toast type="primary" :message="session('status')" class="mb-5 shadow-xl" />
    @endif

    <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm" novalidate>
        @csrf

        {{-- Username --}}
        <div class="field-group">
            <label for="username" class="field-label">Username</label>
            <div class="field-input-wrap">
                <svg class="field-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <input
                    id="username"
                    type="text"
                    name="username"
                    class="field-input {{ $errors->get('username') ? 'error' : '' }}"
                    value="{{ old('username') }}"
                    placeholder="Enter your username"
                    required
                    autofocus
                    autocomplete="username"
                    readonly
                    onfocus="this.removeAttribute('readonly');" />
            </div>
            @if ($errors->get('username'))
            <div class="field-error">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
                {{ $errors->first('username') }}
            </div>
            @endif
        </div>

        {{-- Password --}}
        <div class="field-group">
            <label for="password" class="field-label">Password</label>
            <div class="field-input-wrap">
                <svg class="field-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                </svg>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="field-input {{ $errors->get('password') ? 'error' : '' }}"
                    placeholder="Enter your password"
                    required
                    autocomplete="current-password"
                    readonly
                    onfocus="this.removeAttribute('readonly');" />
                <button type="button" class="toggle-pw" id="togglePw" aria-label="Toggle password visibility">
                    <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    <svg id="eyeOffIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94" />
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" />
                        <line x1="1" y1="1" x2="23" y2="23" />
                    </svg>
                </button>
            </div>
            @if ($errors->get('password'))
            <div class="field-error">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
                {{ $errors->first('password') }}
            </div>
            @endif
        </div>

        {{-- Remember + Forgot --}}
        <div class="form-meta">
            <label class="remember-label">
                <input id="remember_me" type="checkbox" name="remember" class="remember-check">
                <span class="remember-text">Remember me</span>
            </label>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-login" id="loginBtn">
            <div class="spinner"></div>
            <span class="btn-text">Access Portal</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
        </button>
    </form>

    <div class="auth-footer">
        <p>© {{ date('Y') }} MBC Student Monitoring System. All rights reserved.</p>
    </div>

    <script>
        // Password toggle
        const togglePw = document.getElementById('togglePw');
        const pwInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        togglePw.addEventListener('click', function() {
            const isText = pwInput.type === 'text';
            pwInput.type = isText ? 'password' : 'text';
            eyeIcon.style.display = isText ? 'none' : 'block';
            eyeOffIcon.style.display = isText ? 'block' : 'none';
        });

        // (Removed the submit handler to ensure Chrome's password manager triggers properly)

        // Input validation hint styling
        document.querySelectorAll('.field-input').forEach(function(input) {
            input.addEventListener('input', function() {
                this.classList.remove('error');
            });
        });
    </script>
</x-guest-layout>