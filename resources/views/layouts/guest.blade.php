<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'GymPro System') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
        
        <!-- Fuente Poppins -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                height: 100%;
            }
            .auth-card {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(10px);
            }
            .btn-primary {
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                border: none;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(30, 60, 114, 0.4);
            }
        </style>
    </head>
    <body class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <div class="bg-white p-3 rounded-full shadow-lg inline-block">
                        <i class="bi bi-activity text-4xl text-blue-600"></i>
                    </div>
                </a>
                <h1 class="text-3xl font-bold text-white mt-4">GymPro System</h1>
                <p class="text-blue-100 mt-2">Control total de tu gimnasio</p>
            </div>

            <div class="auth-card w-full p-8">
                {{ $slot }}
            </div>
            
            <div class="text-center mt-6">
                <p class="text-white text-sm">
                    &copy; {{ date('Y') }} GymPro System. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </body>
</html>
