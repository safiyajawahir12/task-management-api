<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login – Task Manager</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background: linear-gradient(135deg, #110f37 0%, #a69fb3 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      background: white;
      border-radius: 16px;
      padding: 2.5rem 2rem;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .logo {
      text-align: center;
      margin-bottom: 1.8rem;
    }

    .logo span {
      font-size: 2.5rem;
    }

    .logo h1 {
      font-size: 1.4rem;
      color: #1e293b;
      margin-top: 0.4rem;
    }

    .logo p {
      font-size: 0.85rem;
      color: #64748b;
      margin-top: 0.25rem;
    }

    /* ── Form elements ── */
    label {
      display: block;
      font-size: 0.85rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 0.35rem;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.7rem 1rem;
      border: 1.5px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.95rem;
      transition: border-color 0.2s, box-shadow 0.2s;
      outline: none;
    }

    input:focus {
      border-color: #4f46e5;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    /* Red border when there's a validation error */
    input.is-invalid {
      border-color: #ef4444;
    }

    .field {
      margin-bottom: 1.1rem;
    }

    .error-msg {
      color: #dc2626;
      font-size: 0.8rem;
      margin-top: 0.3rem;
    }

    /* Remember me row */
    .remember-row {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1.4rem;
      font-size: 0.85rem;
      color: #4b5563;
    }

    /* Submit button */
    .btn-login {
      width: 100%;
      padding: 0.75rem;
      background: #4f46e5;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-login:hover { background: #4338ca; }

    .divider {
      text-align: center;
      font-size: 0.82rem;
      color: #9ca3af;
      margin: 1.2rem 0;
    }

    .link-register {
      display: block;
      text-align: center;
      font-size: 0.9rem;
      color: #4f46e5;
      text-decoration: none;
      font-weight: 600;
    }

    .link-register:hover { text-decoration: underline; }

    /* General error alert (wrong credentials) */
    .alert-error {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fca5a5;
      border-radius: 8px;
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      margin-bottom: 1.2rem;
    }
  </style>
</head>
<body>

<div class="login-box">

  <div class="logo">
    <span>📋</span>
    <h1>Task Manager</h1>
    <p>Sign in to your account</p>
  </div>

  {{-- Show general auth error --}}
  @if ($errors->has('email') && !$errors->has('email'))
    <div class="alert-error">{{ $errors->first('email') }}</div>
  @endif

  <form action="{{ route('login') }}" method="POST" novalidate>
    @csrf {{-- Laravel requires this hidden token to prevent CSRF attacks --}}

    {{-- Email Field --}}
    <div class="field">
      <label for="email">Email Address</label>
      <input
        type="email"
        id="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="you@example.com"
        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
        autocomplete="email"
        required
      />
      @error('email')
        <p class="error-msg">{{ $message }}</p>
      @enderror
    </div>

    {{-- Password Field --}}
    <div class="field">
      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        placeholder="••••••••"
        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
        autocomplete="current-password"
        required
      />
      @error('password')
        <p class="error-msg">{{ $message }}</p>
      @enderror
    </div>

    {{-- Remember Me --}}
    <div class="remember-row">
      <input type="checkbox" id="remember" name="remember" />
      <label for="remember" style="margin:0; font-weight:400;">Remember me</label>
    </div>

    {{-- Submit --}}
    <button type="submit" class="btn-login">Sign In</button>
  </form>

  <div class="divider">— or —</div>

  <a href="{{ route('register') }}" class="link-register">Create a new account</a>

</div>

</body>
</html>
