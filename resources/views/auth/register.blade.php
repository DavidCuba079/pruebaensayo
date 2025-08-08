<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Gimnasio</title>
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
        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            padding: 2.5rem;
            animation: fadeIn 0.5s ease-in-out;
        }
        .register-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .register-logo i {
            font-size: 3rem;
            color: #2a5298;
            margin-bottom: 1rem;
        }
        .register-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        .register-subtitle {
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
        .btn-register {
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
            margin-top: 1.5rem;
        }
        .btn-register:hover {
            background: #1e3c72;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        }
        .form-check-input:checked {
            background-color: #2a5298;
            border-color: #2a5298;
        }
        .form-check-label {
            color: #555;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .form-check-label a {
            color: #2a5298;
            text-decoration: none;
            font-weight: 500;
        }
        .form-check-label a:hover {
            text-decoration: underline;
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #7f8c8d;
            font-size: 0.95rem;
        }
        .login-link a {
            color: #2a5298;
            font-weight: 500;
            text-decoration: none;
            margin-left: 5px;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .invalid-feedback {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        .is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-logo">
                <i class="bi bi-person-plus"></i>
                <h1 class="register-title">Crear Cuenta</h1>
                <p class="register-subtitle">Únete a nuestra comunidad de gimnasio</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Nombre -->
                <div class="mb-4">
                    <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus
                               autocomplete="name"
                               placeholder="Ingresa tu nombre completo">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required
                               autocomplete="email"
                               placeholder="tucorreo@ejemplo.com">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <span class="input-group-text">
                            <i class="bi bi-key"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required
                               autocomplete="new-password"
                               placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <small class="text-muted">Mínimo 8 caracteres</small>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <span class="input-group-text">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               autocomplete="new-password"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Términos y Condiciones -->
                <div class="mb-4 form-check">
                    <input type="checkbox" 
                           class="form-check-input @error('terms') is-invalid @enderror" 
                           id="terms" 
                           name="terms" 
                           required>
                    <label class="form-check-label" for="terms">
                        Acepto los <a href="#" class="text-primary">términos y condiciones</a>
                    </label>
                    @error('terms')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Botón de registro -->
                <button type="submit" class="btn btn-register">
                    <i class="bi bi-person-plus me-2"></i>
                    Crear Cuenta
                </button>

                <!-- Enlace a inicio de sesión -->
                <div class="login-link">
                    ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto de carga suave
        document.addEventListener('DOMContentLoaded', function() {
            // Validación de formulario
            const form = document.getElementById('registerForm');
            
            if (form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            }

            // Mostrar/ocultar contraseña
            const togglePassword = document.querySelector('.toggle-password');
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const password = document.getElementById('password');
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }
        });
    </script>
</body>
</html>
