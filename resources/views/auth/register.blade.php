<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register – Task Manager</title>
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

    .box {
      background: white;
      border-radius: 16px;
      padding: 2.5rem 2rem;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .logo { text-align: center; margin-bottom: 1.8rem; }
    .logo span { font-size: 2.5rem; }
    .logo h1 { font-size: 1.4rem; color: #1e293b; margin-top: 0.4rem; }
    .logo p { font-size: 0.85rem; color: #64748b; margin-top: 0.25rem; }

    label {
      display: block;
      font-size: 0.85rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 0.35rem;
    }

    input {
      width: 100%;
      padding: 0.7rem 1rem;
      border: 1.5px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.95rem;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    input:focus {
      border-color: #4f46e5;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    input.is-invalid { border-color: #ef4444; }

    .field { margin-bottom: 1.1rem; }

    .error-msg { color: #dc2626; font-size: 0.8rem; margin-top: 0.3rem; }

    .btn-register {
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

    .btn-register:hover { background: #4338ca; }

    .divider { text-align: center; font-size: 0.82rem; color: #9ca3af; margin: 1.2rem 0; }

    .link-login {
      display: block;
      text-align: center;
      font-size: 0.9rem;
      color: #4f46e5;
      text-decoration: none;
      font-weight: 600;
    }

    .link-login:hover { text-decoration: underline; }
  </style>
</head>
<body>

<div class="box">
  <div class="logo">
    <span>📋</span>
    <h1>Create Account</h1>
    <p>Join Task Manager today</p>
  </div>

  <form action="{{ route('register') }}" method="POST" novalidate>
    @csrf

    <div class="field">
      <label for="name">Full Name</label>
      <input
        type="text"
        id="name"
        name="name"
        value="{{ old('name') }}"
        placeholder="Enter Your Name"
        class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
        required
      />
      @error('name') <p class="error-msg">{{ $message }}</p> @enderror
    </div>

    <div class="field">
      <label for="email">Email Address</label>
      <input
        type="email"
        id="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="you@example.com"
        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
        required
      />
      @error('email') <p class="error-msg">{{ $message }}</p> @enderror
    </div>

    <div class="field">
      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        name="password"
        placeholder="Min. 8 characters"
        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
        required
      />
      @error('password') <p class="error-msg">{{ $message }}</p> @enderror
    </div>

    <div class="field">
      <label for="password_confirmation">Confirm Password</label>
      <input
        type="password"
        id="password_confirmation"
        name="password_confirmation"
        placeholder="Repeat your password"
        required
      />
    </div>

    <button type="submit" class="btn-register">Create Account</button>
  </form>

  <div class="divider">— already have an account? —</div>
  <a href="{{ route('login') }}" class="link-login">Sign in instead</a>
</div>

</body>
</html>
