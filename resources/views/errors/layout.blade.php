<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ZeroLib</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --ink: #0E0C0A;
            --cream: #FAF7F2;
            --parchment: #F2EDE4;
            --amber: #C8883A;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            background-color: var(--cream);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Bruit de fond premium */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            opacity: 0.35;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        }
        
        .container {
            position: relative;
            z-index: 2;
            max-width: 550px;
            width: 100%;
            text-align: center;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(200, 136, 58, 0.15);
            border-radius: 24px;
            padding: 3rem 2rem;
            box-shadow: 0 10px 30px -10px rgba(14, 12, 10, 0.05);
            animation: fadeIn 0.8s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .illustration {
            width: 140px;
            height: 140px;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--amber);
        }
        
        .code {
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.25em;
            color: var(--amber);
            margin-bottom: 0.5rem;
        }
        
        .headline {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 1rem;
            line-height: 1.2;
        }
        
        .description {
            font-size: 0.95rem;
            color: rgba(14, 12, 10, 0.6);
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }
        
        .actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            align-items: center;
            justify-content: center;
        }
        
        @media (min-width: 480px) {
            .actions {
                flex-direction: row;
            }
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.8rem 1.6rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--ink);
            color: var(--cream);
            border: 1px solid var(--ink);
        }
        
        .btn-primary:hover {
            background-color: var(--amber);
            border-color: var(--amber);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(200, 136, 58, 0.2);
        }
        
        .btn-secondary {
            background-color: transparent;
            color: var(--ink);
            border: 1px solid rgba(14, 12, 10, 0.15);
        }
        
        .btn-secondary:hover {
            border-color: var(--ink);
            background-color: rgba(14, 12, 10, 0.02);
            transform: translateY(-2px);
        }
        
        .illustration svg {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="illustration">
            @yield('illustration')
        </div>
        <div class="code">@yield('code')</div>
        <h1 class="headline">@yield('headline')</h1>
        <p class="description">@yield('message')</p>
        
        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Retour à l'accueil
            </a>
            @yield('extra-action')
        </div>
    </div>
</body>
</html>
