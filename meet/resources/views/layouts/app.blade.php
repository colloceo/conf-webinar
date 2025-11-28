<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Digital Leap Africa - Video Conferencing Platform')</title>
    <meta name="description" content="@yield('meta_description', 'Low-bandwidth video conferencing designed for African communities. Connect, collaborate, and grow together.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary-blue: #2E78C5;
            --deep-blue: #1E4C7C;
            --navy-bg: #0C121C;
            --diamond-white: #F5F7FA;
            --cool-gray: #AEB8C2;
            --charcoal: #252A32;
            --cyan-accent: #00C9FF;
            --purple-accent: #7A5FFF;
            --radius: 12px;
            --max-width: 1100px;
            --header-height: 4rem;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(180deg, #07101a 0%, var(--navy-bg) 100%);
            color: var(--diamond-white);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: var(--header-height);
        }

        .container {
            width: 90%;
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 2rem 0;
        }

        .site-header {
            padding: 1rem 0;
            background: linear-gradient(135deg, var(--charcoal) 0%, var(--navy-bg) 100%);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 201, 255, 0.2);
            height: var(--header-height);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: var(--max-width);
            margin: 0 auto;
            width: 90%;
            padding: 0 1rem;
            gap: 1rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #ffffff;
            flex-shrink: 0;
        }

        .brand h1 {
            font-size: 1.1rem;
            margin: 0;
            letter-spacing: 0.5px;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand .tagline {
            font-size: 0.7rem;
            color: var(--cool-gray);
            margin-top: 2px;
            display: block;
            line-height: 1;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            margin: 0;
            padding: 0;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-links a:hover {
            color: #64b5f6;
            transform: translateY(-2px);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--cyan-accent);
            color: #07101a;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 201, 255, 0.4);
            color: #07101a;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: #64b5f6;
            color: #64b5f6;
            transform: translateY(-2px);
        }

        .card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: var(--radius);
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--diamond-white);
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 0.8rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--diamond-white);
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--cyan-accent);
            box-shadow: 0 0 0 2px rgba(0, 201, 255, 0.2);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        main {
            flex: 1;
            padding: 2rem 0;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            border: 1px solid;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            .container {
                width: 95%;
                padding: 1rem 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <header class="site-header">
        <nav class="nav">
            <a href="{{ url('/') }}" class="brand">
                <div>
                    <h1>Digital Leap Africa</h1>
                    <span class="tagline">Video Conferencing</span>
                </div>
            </a>

            <ul class="nav-links">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('meetings.create') }}">Create Meeting</a></li>
            </ul>

            <div class="nav-actions">
                @auth
                    <span class="btn-outline">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-outline">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('auth.google') }}" class="btn-primary">
                        <i class="fab fa-google"></i> Login with Google
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>