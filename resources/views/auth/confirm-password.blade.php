<x-guest-layout>
    <div class="text-center mb-8">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
            <i class="bi bi-shield-lock text-2xl text-blue-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Confirmar Contraseña</h2>
        <p class="text-gray-600 mt-2">Por favor, confirma tu contraseña para continuar</p>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="bi bi-shield-check text-blue-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Esta es un área segura de la aplicación. Por favor, confirma tu contraseña antes de continuar.
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Contraseña -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-key text-gray-400"></i>
                </div>
                <input id="password" 
                       name="password" 
                       type="password" 
                       required 
                       autocomplete="current-password"
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="••••••••">
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="bi bi-shield-check mr-2"></i>
                Confirmar Contraseña
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            ¿Olvidaste tu contraseña?
            <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Restablecer contraseña
            </a>
        </p>
    </div>
</x-guest-layout>
