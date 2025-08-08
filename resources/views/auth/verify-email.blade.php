<x-guest-layout>
    <div class="text-center mb-8">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
            <i class="bi bi-envelope-check text-2xl text-blue-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Verifica tu correo electrónico</h2>
        <p class="text-gray-600 mt-2">¡Estás a un paso de activar tu cuenta!</p>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="bi bi-info-circle-fill text-blue-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    ¡Gracias por registrarte! Antes de comenzar, por favor verifica tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar. Si no recibiste el correo, con gusto te enviaremos otro.
                </p>
            </div>
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-check-circle-fill text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        Hemos enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste durante el registro.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="bi bi-envelope-arrow-up mr-2"></i>
                Reenviar correo de verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="bi bi-box-arrow-left mr-2"></i>
                Cerrar Sesión
            </button>
        </form>
    </div>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            ¿No recibiste el correo?
            <button type="button" 
                    onclick="event.preventDefault(); document.getElementById('resend-verification-form').submit();" 
                    class="font-medium text-blue-600 hover:text-blue-500">
                Haz clic aquí para solicitar otro
            </button>
        </p>
        <form id="resend-verification-form" method="POST" action="{{ route('verification.send') }}" class="hidden">
            @csrf
        </form>
    </div>
</x-guest-layout>
