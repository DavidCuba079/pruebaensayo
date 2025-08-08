<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Gimnasio</title>
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
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px; /* Solo este cambio para hacerlo más ancho */
            padding: 2.5rem;
            animation: fadeIn 0.5s ease-in-out;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo i {
            font-size: 3rem;
            color: #2a5298;
            margin-bottom: 1rem;
        }
        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            color: #7f8c8d;
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        .form-control {
            height: 48px;
            border-radius: 8px;
            padding-left: 45px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
            font-size: 0.95rem;
            background-color: #e6f3ff; /* Fondo celeste claro para campos de entrada */
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
            width: 40px;
            background: transparent;
            border: none;
            z-index: 10;
            color: #95a5a6;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .btn-login {
            background: #2a5298;
            color: white;
            border: none;
            height: 48px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-top: 1rem;
        }
        .btn-login:hover {
            background: #1e3c72;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        .form-footer a {
            color: #2a5298;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }
        .form-footer a:hover {
            text-decoration: underline;
            color: #1e3c72;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.8rem 0;
            color: #95a5a6;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        .divider::before {
            margin-right: 1.2rem;
        }
        .divider::after {
            margin-left: 1.2rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .input-group {
            margin-bottom: 1.2rem;
            position: relative;
        }
        .alert {
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: none;
            padding: 0.9rem 1.25rem;
        }
        .alert-dismissible .btn-close {
            padding: 0.75rem 1rem;
        }
        .form-check-input:checked {
            background-color: #2a5298;
            border-color: #2a5298;
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(42, 82, 152, 0.25);
            border-color: #2a5298;
        }
        .btn-outline-secondary {
            border: 1px solid #dee2e6;
            color: #6c757d;
            font-weight: 500;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #2a5298;
        }
        .form-check-label {
            color: #555;
            font-size: 0.9rem;
            cursor: pointer;
            max-width: 400px;
            padding: 2.5rem;
            animation: fadeIn 0.5s ease-in-out;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo i {
            font-size: 3rem;
            color: #2a5298;
            margin-bottom: 1rem;
        }
        .login-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
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
        .btn-login {
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
        .btn-login:hover {
            background: #1e3c72;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 60, 114, 0.2);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        .form-footer a {
            color: #2a5298;
            text-decoration: none;
            font-weight: 500;
        }
        .form-footer a:hover {
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
        .form-floating > label {
            padding-left: 40px;
        }
        .input-group {
            margin-bottom: 1rem;
        }
        .forgot-password {
            font-size: 0.9rem;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.2s;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <i class="bi bi-person-circle"></i>
                <h1 class="login-title">Bienvenido a SPACE GYM</h1>
                <p class="login-subtitle">Inicia sesión para continuar</p>
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

            <form method="POST" action="{{ route('login') }}" class="mt-4">
                @csrf

                <!-- Email -->
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           class="form-control"
                           value="{{ old('email') }}" 
                           placeholder="Correo electrónico"
                           required 
                           autofocus 
                           autocomplete="email">
                </div>

                <!-- Password -->
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           class="form-control"
                           placeholder="Contraseña"
                           required 
                           autocomplete="current-password">
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="remember" 
                               id="remember_me">
                        <label class="form-check-label" for="remember_me">
                            Recordar sesión
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none small">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Aceptar
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="divider">
                o
            </div>

            <!-- Register Link -->
            <div class="d-grid gap-2">
                <a href="{{ route('register') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-person-plus me-2"></i>
                    Crear una cuenta
                </a>
            </div>

            <div class="form-footer">
                {{ date('Y') }} Sistema de Gimnasio. Todos los derechos reservados.
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto de carga suave
        document.addEventListener('DOMContentLoaded', function() {
            // Validación de formulario
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const email = document.getElementById('email')?.value;
                    const password = document.getElementById('password')?.value;
                    
                    if (!email || !password) {
                        e.preventDefault();
                        alert('Por favor, completa todos los campos obligatorios.');
                        return false;
                    }
                    return true;
                });
            }
            
            // Efecto de enfoque en los campos de entrada
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                const inputGroupText = input.parentElement?.querySelector('.input-group-text');
                if (inputGroupText) {
                    input.addEventListener('focus', function() {
                        inputGroupText.style.color = '#2a5298';
                    });
                    
                    input.addEventListener('blur', function() {
                        inputGroupText.style.color = '#95a5a6';
                    });
                }
            });
        });
    </script>
</body>
</html>
