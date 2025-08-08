<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Restablecer Contraseña</h2>
        <p class="text-gray-600 mt-2">Crea una nueva contraseña para tu cuenta</p>
    </div>

    <!-- Errores de validación -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
            <p class="font-medium">Por favor corrige los siguientes errores:</p>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                       value="{{ old('email', $request->email) }}" 
                       required 
                       autofocus 
                       autocomplete="email"
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="tucorreo@ejemplo.com">
            </div>
        </div>

        <!-- Nueva Contraseña -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-key text-gray-400"></i>
                </div>
                <input id="password" 
                       name="password" 
                       type="password" 
                       required 
                       autocomplete="new-password"
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="••••••••">
            </div>
            <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
        </div>

        <!-- Confirmar Contraseña -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-shield-lock text-gray-400"></i>
                </div>
                <input id="password_confirmation" 
                       name="password_confirmation" 
                       type="password" 
                       required 
                       autocomplete="new-password"
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="••••••••">
            </div>
        </div>

        <!-- Botón de restablecer contraseña -->
        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="bi bi-arrow-repeat mr-2"></i>
                Restablecer Contraseña
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                ← Volver al inicio de sesión
            </a>
        </p>
    </div>
</x-guest-layout>
