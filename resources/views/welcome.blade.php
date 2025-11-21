<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmilePro | Moderne Tandartspraktijk</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
            <style>
        :root {
            --primary: #0f6fff;
            --primary-dark: #0042b8;
            --accent: #00c2a8;
            --text-dark: #0f172a;
            --text-muted: #4b5563;
            --surface: #ffffff;
            --surface-alt: #f8fafc;
            --shadow-soft: 0 20px 60px rgba(15, 23, 42, 0.08);
            --radius-lg: 32px;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(180deg, #f5f8ff 0%, #ffffff 60%, #f8fbff 100%);
            color: var(--text-dark);
        }
        img {
            max-width: 100%;
            display: block;
        }
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px 80px;
        }
        @media (max-width: 768px) {
            .page-container {
                padding: 16px 16px 40px;
            }
        }
        .cta-btn {
            background: var(--primary);
            color: #fff !important;
            padding: 10px 22px;
            border-radius: 999px;
            box-shadow: 0 12px 30px rgba(15, 111, 255, 0.35);
        }
        .hero {
            margin-top: 64px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
            align-items: center;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 4vw, 3.5rem);
            line-height: 1.1;
            margin-bottom: 16px;
        }
        .hero p {
            font-size: 1.05rem;
            color: var(--text-muted);
            margin-bottom: 28px;
        }
        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }
        .ghost-btn {
            border: 1px solid rgba(15, 23, 42, 0.15);
            padding: 10px 22px;
            border-radius: 999px;
            color: var(--text-dark);
            font-weight: 600;
            text-decoration: none;
        }
        .hero-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
        }
        .hero-card img {
            border-radius: 24px;
            width: 100%;
            height: 320px;
            object-fit: cover;
            filter: brightness(0.95);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(0, 194, 168, 0.15);
            color: #036756;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 18px;
            margin-top: 36px;
        }
        .stat-card {
            background: var(--surface);
            padding: 20px;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, 0.05);
        }
        .stat-card h3 {
            margin: 0;
            font-size: 2rem;
            color: var(--primary);
        }
        .stat-card span {
            color: var(--text-muted);
        }
        .section-title {
            text-align: center;
            margin: 90px auto 40px;
            max-width: 640px;
        }
        .section-title h2 {
            font-size: 2.2rem;
        }
        .section-title p {
            color: var(--text-muted);
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
        }
        .feature-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
        }
        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(15, 111, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.3rem;
            margin-bottom: 16px;
        }
        .testimonials {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }
        .testimonial {
            background: #0f172a;
            color: #e2e8f0;
            border-radius: 24px;
            padding: 28px;
            box-shadow: var(--shadow-soft);
        }
        .testimonial small {
            display: block;
            margin-top: 18px;
            color: rgba(226, 232, 240, 0.8);
        }
        .cta {
            margin-top: 90px;
            background: linear-gradient(120deg, #0f6fff, #00c2a8);
            padding: 48px;
            border-radius: var(--radius-lg);
            color: white;
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 35px 80px rgba(15, 111, 255, 0.35);
        }
        .cta h3 {
            margin: 0 0 12px;
            font-size: 1.9rem;
        }
        .cta p {
            margin: 0;
            color: rgba(255, 255, 255, 0.85);
        }
        .cta-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        .cta-actions a {
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 999px;
            font-weight: 600;
        }
        .cta-primary {
            background: white;
            color: var(--primary-dark);
        }
        .cta-secondary {
            border: 1px solid rgba(255, 255, 255, 0.7);
            color: white;
        }
        @media (max-width: 768px) {
            .hero {
                margin-top: 32px;
                gap: 24px;
            }
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 1rem;
            }
            .hero-card img {
                height: 240px;
            }
            .section-title {
                margin: 60px auto 30px;
                padding: 0 16px;
            }
            .section-title h2 {
                font-size: 1.75rem;
            }
            .section-title p {
                font-size: 0.95rem;
            }
            .features {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .testimonials {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .stats {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .stat-card {
                padding: 16px;
            }
            .stat-card h3 {
                font-size: 1.5rem;
            }
            .cta {
                padding: 32px 24px;
                flex-direction: column;
                text-align: center;
            }
            .cta h3 {
                font-size: 1.5rem;
            }
            .cta-actions {
                width: 100%;
                justify-content: center;
            }
            .cta-actions a {
                flex: 1;
                text-align: center;
                min-width: 120px;
            }
        }
            </style>
    </head>
<body class="bg-gray-50">
    @include('layouts.navigation')
    <div class="page-container pt-12">
        <section class="hero" id="services">
            <div>
                <div class="badge">
                    <span>✨</span> Premium Tandzorg sinds 2008
                </div>
                <h1>Glanzende glimlach, professioneel geregeld.</h1>
                <p>
                    SmilePro combineert top-tier tandheelkundige zorg met een modern digitaal portaal.
                    Plan afspraken, beheer dossiers en houd het hele team op één lijn – veilig en intuïtief.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="cta-btn">Plan een intake</a>
                    <a href="#platform" class="ghost-btn">Ontdek het platform</a>
                </div>
                <div class="stats">
                    <div class="stat-card">
                        <h3>12k+</h3>
                        <span>Tevreden patiënten</span>
                    </div>
                    <div class="stat-card">
                        <h3>98%</h3>
                        <span>Klanttevredenheid</span>
                    </div>
                    <div class="stat-card">
                        <h3>24/7</h3>
                        <span>Online toegang</span>
                    </div>
                </div>
            </div>
            <div class="hero-card">
                <img src="https://images.unsplash.com/photo-1606811841689-23dfddce3e92?auto=format&fit=crop&w=900&q=80" alt="SmilePro clinic">
            </div>
        </section>

        <div class="section-title" id="platform">
            <h2>Een portaal voor ieder type gebruiker</h2>
            <p>Van patiënt tot management: iedereen krijgt een gepersonaliseerde ervaring met de juiste tools binnen handbereik.</p>
        </div>
        <section class="features">
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Patiëntagenda</h3>
                <p>Online afspraken maken, herinneringen ontvangen en facturen downloaden vanuit één veilige omgeving.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🦷</div>
                <h3>Teamplanning</h3>
                <p>Tandartsen en mondhygiënisten beheren hun beschikbaarheid en patiëntenstroom moeiteloos.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Management dashboards</h3>
                <p>Realtime inzicht in productie, capaciteit en patiënttevredenheid voor gerichte beslissingen.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3>Veilige communicatie</h3>
                <p>Versleutelde messaging, automatische logging en AVG-compliant toegangsbeheer.</p>
            </div>
        </section>

        <div class="section-title" id="reviews">
            <h2>Cliënten & collega’s over SmilePro</h2>
            <p>Elke dag vertrouwen professionals en gezinnen op onze praktijk en digitale dienstverlening.</p>
        </div>
        <section class="testimonials">
            <article class="testimonial">
                “Sinds we SmilePro gebruiken is onze wachtruimte rustiger en de agenda overzichtelijker. Patiënten waarderen de transparantie.”
                <small>— Dr. Anouk van der Leij, Tandarts & praktijkhouder</small>
            </article>
            <article class="testimonial">
                “Als assistente kan ik in één oogopslag zien wie, wat en wanneer. Geen losse briefjes of spreadsheets meer.”
                <small>— Kim Elbers, Praktijkassistent</small>
            </article>
            <article class="testimonial">
                “Online afspraken maken gaat super snel. De herinneringen per sms zorgen ervoor dat ik nooit meer een controle mis.”
                <small>— Sem Jansen, Patiënt sinds 2021</small>
            </article>
        </section>

        <section class="cta" id="contact">
            <div>
                <h3>Klaar voor een glimlach die indruk maakt?</h3>
                <p>Plan een rondleiding door onze praktijk of log in om direct aan de slag te gaan met uw persoonlijke portaal.</p>
            </div>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="cta-primary">Word patiënt</a>
                <a href="{{ route('login') }}" class="cta-secondary">Log in</a>
            </div>
        </section>
    </div>
    </body>
</html>
