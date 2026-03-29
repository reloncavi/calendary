<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calendary - Gestión de Calendario</title>

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,400,600,700" rel="stylesheet">
        <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />

        <style>
            html, body {
                background: linear-gradient(135deg, #006cb7 0%, #004a80 100%);
                color: #fff;
                font-family: 'Nunito', sans-serif;
                font-weight: 400;
                min-height: 100vh;
                margin: 0;
            }

            .welcome-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 2rem 1rem;
            }

            .top-right {
                position: absolute;
                right: 1.5rem;
                top: 1.25rem;
            }

            .top-right a {
                color: rgba(255, 255, 255, 0.85);
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 600;
                letter-spacing: .05rem;
                text-decoration: none;
                text-transform: uppercase;
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 4px;
                margin-left: 0.5rem;
                transition: background-color 0.2s, color 0.2s;
            }

            .top-right a:hover {
                background-color: rgba(255, 255, 255, 0.15);
                color: #fff;
            }

            .brand {
                text-align: center;
                margin-bottom: 2rem;
            }

            .brand-icon {
                font-size: 4rem;
                margin-bottom: 0.75rem;
                display: block;
            }

            .brand-title {
                font-size: 3rem;
                font-weight: 700;
                letter-spacing: 0.05rem;
            }

            .brand-subtitle {
                font-size: 1.125rem;
                font-weight: 200;
                opacity: 0.9;
                margin-top: 0.5rem;
            }

            .features {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 1.5rem;
                margin-top: 2rem;
                max-width: 800px;
            }

            .feature-card {
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.15);
                border-radius: 8px;
                padding: 1.5rem;
                width: 220px;
                text-align: center;
                transition: transform 0.2s, background 0.2s;
            }

            .feature-card:hover {
                transform: translateY(-4px);
                background: rgba(255, 255, 255, 0.18);
            }

            .feature-card i {
                font-size: 2rem;
                margin-bottom: 0.75rem;
                display: block;
            }

            .feature-card h3 {
                font-size: 1rem;
                font-weight: 600;
                margin: 0 0 0.5rem;
            }

            .feature-card p {
                font-size: 0.85rem;
                font-weight: 200;
                opacity: 0.85;
                margin: 0;
                line-height: 1.4;
            }

            .cta {
                margin-top: 2.5rem;
                text-align: center;
            }

            .cta a {
                display: inline-block;
                background: #fff;
                color: #006cb7;
                padding: 0.75rem 2rem;
                font-size: 1rem;
                font-weight: 700;
                text-decoration: none;
                border-radius: 6px;
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .cta a:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>
    <body>
        <div class="welcome-container">
            @if (Route::has('login'))
                <div class="top-right">
                    @auth
                        <a href="{{ url('/home') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    @else
                        <a href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Registro
                            </a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="brand">
                <span class="brand-icon"><i class="fas fa-calendar-alt"></i></span>
                <div class="brand-title">Calendary</div>
                <div class="brand-subtitle">Gestión inteligente de eventos, reuniones y salas</div>
            </div>

            <div class="features">
                <div class="feature-card">
                    <i class="fas fa-calendar-check"></i>
                    <h3>Multi-Calendario</h3>
                    <p>Visualiza eventos y reuniones en una sola vista unificada</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-building"></i>
                    <h3>Gestión de Salas</h3>
                    <p>Administra salas y filtra eventos por ubicación</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-user-shield"></i>
                    <h3>Roles y Permisos</h3>
                    <p>Control de acceso granular por usuario y rol</p>
                </div>
            </div>

            <div class="cta">
                @auth
                    <a href="{{ url('/home') }}">
                        <i class="fas fa-arrow-right"></i> Ir al Panel
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i> Comenzar
                    </a>
                @endauth
            </div>
        </div>
    </body>
</html>
