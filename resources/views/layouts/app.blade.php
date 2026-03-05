<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'TaskFlow')</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
      background: #f8fafc;
      color: #1e293b;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ══════════════════════════════════════
       NAVBAR
    ══════════════════════════════════════ */
    .navbar {
      background: #0e253b;
      border-bottom: 1px solid #e2e8f0;
      padding: 0 2rem;
      height: 64px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      font-size: 1.2rem;
      font-weight: 700;
      color: #4f46e5;
      text-decoration: none;
    }

    .logo-icon {
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      color: white;
      width: 36px;
      height: 36px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      box-shadow: 0 2px 8px rgba(79,70,229,0.35);
    }

    .navbar-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.35rem 0.75rem 0.35rem 0.35rem;
      background: #f1f5f9;
      border-radius: 999px;
      border: 1px solid #e2e8f0;
      font-size: 0.85rem;
    }

    .user-avatar {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.78rem;
      font-weight: 700;
      box-shadow: 0 1px 4px rgba(79,70,229,0.3);
    }

    .user-name {
      font-weight: 600;
      color: #1e293b;
      font-size: 0.88rem;
    }

    .logout-btn {
      background: white;
      color: #64748b;
      border: 1px solid #e2e8f0;
      padding: 0.45rem 1.1rem;
      border-radius: 8px;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 500;
      transition: all 0.2s;
      font-family: 'Inter', sans-serif;
    }

    .logout-btn:hover {
      background: #fef2f2;
      color: #dc2626;
      border-color: #fca5a5;
    }

    /* ══════════════════════════════════════
       MAIN CONTENT
    ══════════════════════════════════════ */
    main {
      max-width: 1100px;
      width: 100%;
      margin: 2rem auto;
      padding: 0 1.5rem;
      flex: 1;
    }

    /* ══════════════════════════════════════
       ALERTS
    ══════════════════════════════════════ */
    .alert {
      padding: 0.9rem 1.2rem;
      border-radius: 10px;
      margin-bottom: 1.2rem;
      font-size: 0.88rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.6rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .alert-success {
      background: #f0fdf4;
      color: #15803d;
      border: 1px solid #bbf7d0;
    }

    .alert-error {
      background: #fef2f2;
      color: #dc2626;
      border: 1px solid #fecaca;
    }

    /* ══════════════════════════════════════
       CARDS
    ══════════════════════════════════════ */
    .card {
      background: white;
      border-radius: 14px;
      padding: 1.75rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
      margin-bottom: 1.5rem;
      border: 1px solid #f1f5f9;
    }

    .card h2 {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 1.2rem;
      color: #0f172a;
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    /* ══════════════════════════════════════
       BADGES
    ══════════════════════════════════════ */
    .badge {
      display: inline-flex;
      align-items: center;
      padding: 0.2rem 0.65rem;
      border-radius: 999px;
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.3px;
    }

    .badge-admin {
      background: #ede9fe;
      color: #6d28d9;
      border: 1px solid #ddd6fe;
    }

    .badge-user {
      background: #dbeafe;
      color: #1d4ed8;
      border: 1px solid #bfdbfe;
    }

    /* ══════════════════════════════════════
       PAGINATION
    ══════════════════════════════════════ */
    nav[aria-label="Pagination Navigation"] {
      background: transparent !important;
      padding: 0 !important;
      display: block !important;
    }

    nav svg { display: none !important; }

    .pagination, nav[role="navigation"] {
      background: transparent !important;
      padding: 0.75rem 0 0.25rem;
      display: flex;
      gap: 0.4rem;
      align-items: center;
      flex-wrap: wrap;
    }

    nav[role="navigation"] a,
    nav[role="navigation"] span {
      display: inline-block;
      padding: 0.4rem 0.8rem;
      border-radius: 8px;
      font-size: 0.82rem;
      font-weight: 600;
      text-decoration: none;
      border: 1px solid #e2e8f0;
      background: white;
      color: #475569;
      transition: all 0.15s;
    }

    nav[role="navigation"] a:hover {
      background: #4f46e5;
      color: white;
      border-color: #4f46e5;
    }

    nav[role="navigation"] span[aria-current="page"] span {
      background: #4f46e5;
      color: white;
      border-color: #4f46e5;
    }

    nav[role="navigation"] span:not([aria-current]) {
      color: #cbd5e1;
      background: #f8fafc;
    }

    nav[role="navigation"] p {
      font-size: 0.82rem;
      color: #94a3b8;
      margin-bottom: 0.5rem;
    }

    /* ══════════════════════════════════════
       FOOTER
    ══════════════════════════════════════ */
    footer {
      background: #0e253b;
      border-top: 1px solid #e2e8f0;
      padding: 1.5rem 2rem;
      margin-top: auto;
    }

    .footer-inner {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .footer-brand {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 700;
      color: #4f46e5;
      font-size: 0.95rem;
    }

    .footer-logo {
      background: linear-gradient(135deg, #4f46e5, #7c3aed);
      color: white;
      width: 26px;
      height: 26px;
      border-radius: 7px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
    }

    .footer-center {
      font-size: 0.82rem;
      color: #94a3b8;
      text-align: center;
    }

    .footer-right {
      display: flex;
      gap: 1rem;
      font-size: 0.8rem;
      color: #64748b;
    }

    .footer-tag {
      display: flex;
      align-items: center;
      gap: 0.3rem;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      padding: 0.25rem 0.65rem;
      border-radius: 999px;
      font-weight: 500;
    }

    /* ══════════════════════════════════════
       SCROLLBAR
    ══════════════════════════════════════ */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #203244; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
  </style>
</head>
<body>

{{-- ── NAVBAR — only show when logged in ── --}}
@auth
<header class="navbar">
  <a href="#" class="navbar-brand">
    <div class="logo-icon">✓</div>
    TaskFlow
  </a>

  <div class="navbar-right">
    <div class="user-info">
      <div class="user-avatar">
        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
      </div>
      <span class="user-name">{{ Auth::user()->name }}</span>
      <span class="badge badge-{{ Auth::user()->role }}">
        {{ ucfirst(Auth::user()->role) }}
      </span>
    </div>

    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="logout-btn">Sign out</button>
    </form>
  </div>
</header>
@endauth {{-- ← closes navbar only --}}

{{-- ── MAIN CONTENT ── --}}
<main>
  @if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-error">⚠️ {{ session('error') }}</div>
  @endif

  @yield('content')
</main>

{{-- ── FOOTER — always visible ── --}}
<footer>
  <div class="footer-inner">

    <div class="footer-brand">
      <div class="footer-logo">✓</div>
      TaskFlow
    </div>

    <div class="footer-center">
      © {{ date('Y') }} TaskFlow. All rights reserved.
    </div>

    <div class="footer-right">
      <span class="footer-tag">🛠️ Laravel 12</span>
      <span class="footer-tag">🗄️ MySQL</span>
      <span class="footer-tag">🔐 Sanctum</span>
    </div>

  </div>
</footer>

</body>
</html>
