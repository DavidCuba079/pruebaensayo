<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Recuperar Contraseña</h2>
        <p class="text-gray-600 mt-2">Te enviaremos un enlace para restablecer tu contraseña</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 p-4 bg-blue-100 text-blue-700 rounded-lg" :status="session('status')" />

    <div class="mb-6 text-sm text-gray-600 bg-blue-50 p-4 rounded-lg">
        <p>¿Olvidaste tu contraseña? No hay problema. Simplemente indícanos tu dirección de correo electrónico y te enviaremos un enlace para que puedas elegir una nueva contraseña.</p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-envelope text-gray-400"></i>
                </div>
                <input id="email" 
                       name="email" 
                       type="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       autocomplete="email"
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="tucorreo@ejemplo.com">
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="bi bi-envelope-check mr-2"></i>
                Enviar Enlace de Recuperación
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            ¿Recordaste tu contraseña?
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Iniciar Sesión
            </a>
        </p>
    </div>
</x-guest-layout>
