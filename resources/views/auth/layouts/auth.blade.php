<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gimnasio')</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .auth-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            animation: fadeIn 0.5s ease-in-out;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-logo i {
            font-size: 3rem;
            color: #2a5298;
            margin-bottom: 1rem;
        }
        .auth-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .auth-subtitle {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .form-control {
            height: 45px;
            border-radius: 8px;
            padding-left: 40px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
        }
        .input-group-text {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            background: transparent;
            border: none;
            z-index: 10;
            color: #95a5a6;
        }
        .btn-auth {
            background: #2a5298;
            color: white;
            border: none;
            height: 45px;
            border-radius: 8px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-auth:hover {
            background: #1e3c72;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 60, 114, 0.2);
        }
        .btn-auth:active {
            transform: translateY(0);
        }
        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        .auth-footer a {
            color: #2a5298;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #95a5a6;
            font-size: 0.85rem;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        .divider::before {
            margin-right: 1rem;
        }
        .divider::after {
            margin-left: 1rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .input-group {
            margin-bottom: 1rem;
            position: relative;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .form-check-label {
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="bi bi-person-circle"></i>
                <h1 class="auth-title">@yield('title', 'Sistema de Gimnasio')</h1>
                @hasSection('subtitle')
                    <p class="auth-subtitle">@yield('subtitle')</p>
                @endif
            </div>

            <!-- Mensajes de estado -->
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')

            @hasSection('auth-footer')
                <div class="divider"></div>
                <div class="auth-footer">
                    @yield('auth-footer')
                </div>
            @endif

            <div class="auth-footer mt-4">
                © {{ date('Y') }} Sistema de Gimnasio. Todos los derechos reservados.
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto de carga suave
        document.addEventListener('DOMContentLoaded', function() {
            // Efecto de enfoque en los campos de entrada
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                const icon = input.parentElement.querySelector('.input-group-text');
                if (icon) {
                    input.addEventListener('focus', function() {
                        icon.style.color = '#2a5298';
                    });
                    
                    input.addEventListener('blur', function() {
                        icon.style.color = '#95a5a6';
                    });
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
