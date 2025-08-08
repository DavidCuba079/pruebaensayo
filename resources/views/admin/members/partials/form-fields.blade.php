<!-- Nombre -->
<div>
    <label for="first_name" class="block text-sm font-medium text-gray-700">Nombres <span class="text-red-600">*</span></label>
    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $member->first_name ?? '') }}" required
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('first_name') border-red-500 @enderror">
    @error('first_name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Apellido -->
<div>
    <label for="last_name" class="block text-sm font-medium text-gray-700">Apellidos <span class="text-red-600">*</span></label>
    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $member->last_name ?? '') }}" required
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('last_name') border-red-500 @enderror">
    @error('last_name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Fecha de Nacimiento -->
<div>
    <label for="birth_date" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento <span class="text-red-600">*</span></label>
    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', isset($member->birth_date) ? $member->birth_date->format('Y-m-d') : '') }}" required
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('birth_date') border-red-500 @enderror"
        max="{{ now()->subYears(12)->format('Y-m-d') }}">
    @error('birth_date')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Género -->
<div>
    <label for="gender" class="block text-sm font-medium text-gray-700">Género <span class="text-red-600">*</span></label>
    <select name="gender" id="gender" required
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('gender') border-red-500 @enderror">
        <option value="">Seleccione una opción</option>
        <option value="M" {{ old('gender', $member->gender ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
        <option value="F" {{ old('gender', $member->gender ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
        <option value="O" {{ old('gender', $member->gender ?? '') == 'O' ? 'selected' : '' }}>Otro</option>
    </select>
    @error('gender')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- DNI -->
<div>
    <label for="dni" class="block text-sm font-medium text-gray-700">DNI/Identificación <span class="text-red-600">*</span></label>
    <input type="text" name="dni" id="dni" value="{{ old('dni', $member->dni ?? '') }}" required
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('dni') border-red-500 @enderror">
    @error('dni')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Estado Civil -->
<div>
    <label for="marital_status" class="block text-sm font-medium text-gray-700">Estado Civil</label>
    <select name="marital_status" id="marital_status"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('marital_status') border-red-500 @enderror">
        <option value="">Seleccione una opción</option>
        <option value="single" {{ old('marital_status', $member->marital_status ?? '') == 'single' ? 'selected' : '' }}>Soltero/a</option>
        <option value="married" {{ old('marital_status', $member->marital_status ?? '') == 'married' ? 'selected' : '' }}>Casado/a</option>
        <option value="divorced" {{ old('marital_status', $member->marital_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorciado/a</option>
        <option value="widowed" {{ old('marital_status', $member->marital_status ?? '') == 'widowed' ? 'selected' : '' }}>Viudo/a</option>
    </select>
    @error('marital_status')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Email -->
<div>
    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
    <input type="email" name="email" id="email" value="{{ old('email', $member->email ?? '') }}"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-500 @enderror">
    @error('email')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Teléfono -->
<div>
    <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono <span class="text-red-600">*</span></label>
    <input type="tel" name="phone" id="phone" value="{{ old('phone', $member->phone ?? '') }}" required
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('phone') border-red-500 @enderror">
    @error('phone')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Dirección -->
<div class="md:col-span-2">
    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
    <input type="text" name="address" id="address" value="{{ old('address', $member->address ?? '') }}"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('address') border-red-500 @enderror">
    @error('address')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Ciudad -->
<div>
    <label for="city" class="block text-sm font-medium text-gray-700">Ciudad</label>
    <input type="text" name="city" id="city" value="{{ old('city', $member->city ?? '') }}"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('city') border-red-500 @enderror">
    @error('city')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Código Postal -->
<div>
    <label for="postal_code" class="block text-sm font-medium text-gray-700">Código Postal</label>
    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $member->postal_code ?? '') }}"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('postal_code') border-red-500 @enderror">
    @error('postal_code')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Ocupación -->
<div>
    <label for="occupation" class="block text-sm font-medium text-gray-700">Ocupación</label>
    <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $member->occupation ?? '') }}"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('occupation') border-red-500 @enderror">
    @error('occupation')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Contacto de Emergencia - Nombre -->
<div>
    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Contacto de Emergencia (Nombre)</label>
    <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $member->emergency_contact_name ?? '') }}"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('emergency_contact_name') border-red-500 @enderror">
    @error('emergency_contact_name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Contacto de Emergencia - Teléfono -->
<div>
    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Contacto de Emergencia (Teléfono)</label>
    <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $member->emergency_contact_phone ?? '') }}"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('emergency_contact_phone') border-red-500 @enderror">
    @error('emergency_contact_phone')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Contacto de Emergencia - Parentesco -->
<div>
    <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700">Parentesco</label>
    <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $member->emergency_contact_relationship ?? '') }}"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('emergency_contact_relationship') border-red-500 @enderror">
    @error('emergency_contact_relationship')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Notas -->
<div class="md:col-span-2">
    <label for="notes" class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
    <textarea name="notes" id="notes" rows="3"
        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-500 @enderror">{{ old('notes', $member->notes ?? '') }}</textarea>
    @error('notes')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
