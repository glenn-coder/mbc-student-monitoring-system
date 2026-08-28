<x-guest-layout>
    <style>
        /* ── Description text ── */
        .fp-desc {
            font-size: 13.5px;
            color: rgba(255, 255, 255);
            line-height: 1.6;
            margin-bottom: 22px;
            text-align: center;
        }

        /* ── Field label ── */
        .fp-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: rgb(255, 255, 255);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        /* ── Email input ── */
        .fp-input-wrap {
            position: relative;
            margin-bottom: 6px;
        }
        .fp-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(13, 1, 182, 0.45);
            pointer-events: none;
        }
        .fp-input {
            width: 100%;
            padding: 13px 14px 13px 44px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px) saturate(150%);
            -webkit-backdrop-filter: blur(10px) saturate(150%);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 12px;
            color: #ffffff;
            font-size: 14.5px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.25s ease;
            caret-color: #ffffff;
            box-sizing: border-box;
        }
        .fp-input::placeholder { color: rgba(30, 27, 75, 0.38); }
        .fp-input:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(15, 31, 134, 0.7);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.15);
        }
        
        /* ── Autofill Hack ── */
        .fp-input:-webkit-autofill,
        .fp-input:-webkit-autofill:hover, 
        .fp-input:-webkit-autofill:focus, 
        .fp-input:-webkit-autofill:active {
            transition: background-color 5000s ease-in-out 0s;
            -webkit-text-fill-color: #ffffff !important;
        }
        .fp-input.error {
            border: 2px solid #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25);
        }
        .fp-error {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 6px;
            color: #ff3b3b;
            font-size: 12.5px;
            font-weight: 500;
        }

        /* ── Submit button ── */
        .btn-fp {
            width: 100%;
            margin-top: 18px;
            padding: 14px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 24px rgba(29, 20, 212, 0.72);
            letter-spacing: 0.3px;
        }
        .btn-fp:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(79, 70, 229, 0.55);
        }

        /* ── Back to login ── */
        .back-to-login {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-to-login:hover { color: #ffffff; }
        .back-to-login svg { flex-shrink: 0; }
    </style>

    {{-- Session Status --}}
    @if (session('status'))
        <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:rgba(6,182,212,0.15);border:1px solid rgba(6,182,212,0.4);border-radius:12px;color:#ffffff;font-size:13.5px;margin-bottom:18px;">
            {{ session('status') }}
        </div>
    @endif

    <p class="fp-desc">
        Forgot your password? No problem. Enter your email address and we'll send you a reset link.
    </p>

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="fp-label">Email address</label>
            <div class="fp-input-wrap">
                <svg class="fp-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <path d="M2 7l10 7 10-7"/>
                </svg>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="fp-input {{ $errors->get('email') ? 'error' : '' }}"
                    value="{{ old('email') }}"
                    placeholder="Enter your email address"
                    autofocus
                    autocomplete="email"
                />
            </div>
            @if ($errors->get('email'))
                <div class="fp-error">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="#ff3b3b"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-fp">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/>
            </svg>
            Send Reset Link
        </button>
    </form>

    {{-- Back to Login --}}
    <a href="{{ route('login') }}" class="back-to-login">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Back to Login
    </a>
</x-guest-layout>
