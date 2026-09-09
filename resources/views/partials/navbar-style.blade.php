        .landing-navbar {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            position: fixed; width: 100%; top: 0; z-index: 1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 5%; transition: all 0.3s ease;
        }
        .landing-brand { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.5rem; color: var(--ink); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        .landing-nav { display: flex; gap: 2rem; align-items: center; }
        .landing-nav a { color: var(--ink); text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: color 0.2s; }
        .landing-nav a:hover { color: var(--blue-deep); }
        @@media (max-width: 768px) { .landing-nav { display: none; } }
