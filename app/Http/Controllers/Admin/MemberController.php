<?php

namespace App\Http\Controllers\Admin;

use App\Models\Member;
use App\Models\MembershipType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberController extends BaseController
{
    /**
     * El nombre del modelo en singular (para mensajes de error).
     *
     * @var string
     */
    protected $modelName = 'socio';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware moved to route definitions
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource with search, filters and pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // Cargar relaciones necesarias para evitar el problema N+1
            $query = Member::with([
                'activeMembership.membershipType',
                'user' => function($q) {
                    $q->select('id', 'name', 'email');
                },
                'checkIns' => function($q) {
                    $q->latest()->take(1);
                }
            ]);
            
            // Búsqueda avanzada
            $search = request('search');
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('dni', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            
            // Filtros avanzados - Por defecto solo mostrar socios activos
            if (request()->has('status') && in_array(request('status'), ['active', 'inactive'])) {
                $query->where('status', request('status') === 'active');
            } else {
                // Por defecto, solo mostrar socios activos
                $query->where('status', true);
            }
            
            // Filtro por tipo de membresía
            if (request()->has('membership_type')) {
                $query->whereHas('activeMembership.membershipType', function($q) {
                    $q->where('id', request('membership_type'));
                });
            }
            
            // Ordenamiento dinámico con valores por defecto seguros
            $sortableColumns = ['first_name', 'last_name', 'dni', 'email', 'created_at'];
            $sortField = in_array(request('sort'), $sortableColumns) ? request('sort') : 'created_at';
            $sortDirection = in_array(strtolower(request('direction')), ['asc', 'desc']) ? 
                            strtolower(request('direction')) : 'desc';
            
            $query->orderBy($sortField, $sortDirection);
            
            // Paginación con opción de cambiar el número de elementos por página
            $perPage = min(100, max(10, (int)request('per_page', 15)));
            $members = $query->paginate($perPage)->withQueryString();
            
            // Cargar tipos de membresía para los filtros
            $membershipTypes = \App\Models\MembershipType::active()->get(['id', 'name']);
            
            if (request()->expectsJson()) {
                return $this->successResponse([
                    'members' => $members,
                    'filters' => [
                        'search' => $search,
                        'status' => request('status'),
                        'membership_type' => request('membership_type'),
                        'sort' => $sortField,
                        'direction' => $sortDirection,
                        'per_page' => $perPage
                    ],
                    'membership_types' => $membershipTypes
                ]);
            }
            
            return view('admin.members.index', compact('members', 'membershipTypes'));
            
        } catch (\Exception $e) {
            \Log::error('Error en MemberController@index: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => request()->all()
            ]);
            
            $errorMessage = 'Error al cargar la lista de socios. Por favor, intente nuevamente.';
            
            if (request()->expectsJson()) {
                return $this->errorResponse($errorMessage, 500);
            }
            
            return $this->redirectWithError('admin.members.index', $errorMessage);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $membershipTypes = MembershipType::active()->get();
            
            if (request()->expectsJson()) {
                return $this->successResponse([
                    'membershipTypes' => $membershipTypes
                ]);
            }
            
            return view('admin.members.create', compact('membershipTypes'));
            
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return $this->errorResponse('Error al cargar el formulario de creación: ' . $e->getMessage());
            }
            
            return $this->redirectWithError('admin.members.index', 'Error al cargar el formulario de creación: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @param  int|null  $memberId
     * @return array
     */
    protected function validationRules($memberId = null)
    {
        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'dni' => [
                'required',
                'string',
                'max:20',
                'unique:members,dni' . ($memberId ? ",$memberId" : ''),
                'regex:/^[0-9]{8,10}$/'
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:100',
                'unique:members,email' . ($memberId ? ",$memberId" : '')
            ],
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]{7,20}$/',
            'birth_date' => 'required|date|before_or_equal:-18 years',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string|max:255',
          
            
           
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]{7,20}$/',
            'health_notes' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:max_width=2000,max_height=2000',
            'status' => 'required|boolean',
            'user_id' => 'nullable|exists:users,id',
            'membership_type_id' => 'nullable|exists:membership_types,id',
            'start_date' => 'nullable|date|after_or_equal:today',
            'create_user_account' => 'sometimes|boolean',
            'password' => 'required_if:create_user_account,true|string|min:8|confirmed',
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    protected function validationMessages()
    {
        return [
            'dni.regex' => 'El DNI debe contener entre 8 y 10 dígitos.',
            'phone.regex' => 'El número de teléfono no tiene un formato válido.',
            'emergency_contact_phone.regex' => 'El número de teléfono de emergencia no tiene un formato válido.',
            'birthdate.before_or_equal' => 'El socio debe ser mayor de 18 años.',
            'profile_photo.dimensions' => 'La imagen no debe ser mayor a 2000x2000 píxeles.',
            'password.required_if' => 'La contraseña es obligatoria cuando se crea una cuenta de usuario.',
        ];
    }

    /**
     * Process and store the profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string
     */
    protected function processProfilePhoto($file)
    {
        // Generar un nombre único para la imagen
        $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Almacenar la imagen en el disco público
        $path = $file->storeAs('profile-photos', $filename, 'public');
        
        return $path;
    }

    /**
     * Create a user account for the member.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function createUserAccount(array $data)
    {
        return User::create([
            'name' => trim($data['first_name'] . ' ' . $data['last_name']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Create an initial membership for the member.
     *
     * @param  \App\Models\Member  $member
     * @param  array  $data
     * @return void
     */
    protected function createInitialMembership($member, $data)
    {
        $membershipType = MembershipType::findOrFail($data['membership_type_id']);
        $startDate = Carbon::parse($data['start_date']);
        $endDate = $startDate->copy()->addDays($membershipType->duration_days);
        
        $member->memberships()->create([
            'membership_type_id' => $membershipType->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'price' => $membershipType->price,
            'discount' => $data['discount'] ?? 0,
            'final_price' => $this->calculateFinalPrice($membershipType->price, $data['discount'] ?? 0),
            'payment_method' => $data['payment_method'] ?? 'cash',
            'payment_status' => $data['payment_status'] ?? 'pending',
            'notes' => $data['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationMessages = $this->validationMessages();
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'dni' => [
                'required',
                'string',
                'max:20',
                'unique:members,dni',
                'regex:/^[0-9]{8,10}$/'
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:100',
                'unique:members,email'
            ],
            'phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]{7,20}$/',
            'address' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]{7,20}$/',
            'health_notes' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ], $validationMessages);

        try {
            $member = new Member();
            // Generar un código único para el socio
            $member->code = $this->generateMemberCode();
            $member->first_name = $validated['first_name'];
            $member->last_name = $validated['last_name'];
            $member->dni = $validated['dni'];
            $member->email = $validated['email'];
            $member->phone = $validated['phone'] ?? null;
            $member->address = $validated['address'] ?? null;
            $member->emergency_contact_name = $validated['emergency_contact_name'] ?? null;
            $member->emergency_contact_phone = $validated['emergency_contact_phone'] ?? null;
            $member->health_notes = $validated['health_notes'] ?? null;
            $member->status = $validated['status'];

            // Guardar foto si existe
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('members', 'public');
                $member->profile_photo = $path;
            }

            $member->save();

            return redirect()->route('admin.members.index')->with('success', 'Socio registrado correctamente.');
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            if (method_exists($e, 'getPrevious') && $e->getPrevious()) {
                $errorMsg .= ' | SQL: ' . $e->getPrevious()->getMessage();
            }
            return back()->withInput()->with('error', 'No se pudo guardar el socio. Detalle: ' . $errorMsg);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        try {
            // Verificar permisos
            $this->checkPermissions('ver_socios');
            
            // Cargar relaciones con manejo de errores
            $member->load([
                'memberships' => function($query) {
                    $query->latest();
                },
                'memberships.membershipType',
                'memberships.createdBy',
                'checkIns' => function($query) {
                    $query->latest()->take(10);
                },
                'payments' => function($query) {
                    $query->latest()->take(5);
                },
                'user'
            ]);
            
            // Obtener la membresía activa con manejo de errores
            $activeMembership = $member->activeMembership;
            
            // Cargar estadísticas adicionales con valores por defecto seguros
            $stats = [
                'total_visits' => $member->checkIns ? $member->checkIns->count() : 0,
                'active_membership' => $activeMembership,
                'has_active_membership' => (bool)$activeMembership,
                'total_payments' => $member->payments ? $member->payments->sum('amount') : 0,
                'last_visit' => $member->checkIns && $member->checkIns->isNotEmpty() ? $member->checkIns->first()->created_at : null,
            ];
            
            if (request()->expectsJson()) {
                return $this->successResponse([
                    'member' => $member,
                    'stats' => $stats
                ]);
            }
            
            return view('admin.members.show', compact('member', 'stats'));
            
        } catch (\Exception $e) {
            // Registrar el error completo para depuración
            \Log::error('Error en MemberController@show: ' . $e->getMessage(), [
                'exception' => $e,
                'member_id' => $member->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson()) {
                return $this->errorResponse('Error al cargar los detalles del socio. Por favor, intente nuevamente.');
            }
            
            return $this->redirectWithError(
                'admin.members.index', 
                'Error al cargar los detalles del socio. Por favor, intente nuevamente.'
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        try {
            // Verificar permisos
            $this->checkPermissions('editar_socios');
            
            $member->load('user');
            $membershipTypes = MembershipType::active()->get();
            
            // Obtener la membresía activa o la más reciente
            $currentMembership = $member->activeMembership ?? $member->memberships()->latest()->first();
            
            if (request()->expectsJson()) {
                return $this->successResponse([
                    'member' => $member,
                    'membershipTypes' => $membershipTypes,
                    'currentMembership' => $currentMembership
                ]);
            }
            
            return view('admin.members.edit', compact('member', 'membershipTypes', 'currentMembership'));
            
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return $this->errorResponse('Error al cargar el formulario de edición: ' . $e->getMessage());
            }
            
            return $this->redirectWithError(
                'admin.members.show', 
                'Error al cargar el formulario de edición: ' . $e->getMessage(),
                ['member' => $member->id]
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        try {
            // Verificar permisos
            $this->checkPermissions('editar_socios');
            
            // Obtener reglas de validación
            $rules = $this->validationRules($member->id);
            
            // Si no se está actualizando la contraseña, hacerla opcional
            if (!$request->filled('password')) {
                unset($rules['password']);
            }
            
            // Validar los datos de entrada
            $validated = $request->validate($rules, $this->validationMessages());
            
            // Usar transacción para asegurar la integridad de los datos
            return DB::transaction(function () use ($member, $validated, $request) {
                // Procesar foto de perfil si se subió
                if ($request->hasFile('profile_photo')) {
                    // Eliminar la foto anterior si existe
                    if ($member->profile_photo_path) {
                        Storage::disk('public')->delete($member->profile_photo_path);
                    }
                    $validated['profile_photo_path'] = $this->processProfilePhoto($request->file('profile_photo'));
                } elseif ($request->boolean('remove_photo') && $member->profile_photo_path) {
                    // Eliminar foto si se solicitó
                    Storage::disk('public')->delete($member->profile_photo_path);
                    $validated['profile_photo_path'] = null;
                }
                
                // Actualizar los datos del miembro
                $member->update(collect($validated)->except([
                    'membership_type_id', 'start_date', 'end_date', 'payment_method',
                    'payment_status', 'discount', 'notes', 'profile_photo', 'remove_photo',
                    'create_user_account', 'password', 'password_confirmation'
                ])->toArray());
                
                // Actualizar o crear membresía si se proporcionó la información
                if (isset($validated['membership_type_id'])) {
                    $this->updateOrCreateMembership($member, $validated);
                }
                
                // Actualizar o crear cuenta de usuario si es necesario
                if (!empty($validated['email']) && $request->boolean('create_user_account', false)) {
                    $this->createOrUpdateUserForMember($member, $validated);
                } elseif ($member->user && $request->has('remove_user_account') && $request->boolean('remove_user_account')) {
                    // Eliminar cuenta de usuario si se solicitó
                    $member->user->delete();
                    $member->user_id = null;
                    $member->save();
                }
                
                // Cargar relaciones para la respuesta
                $member->load('activeMembership.membershipType', 'user');
                
                // Devolver respuesta adecuada según el tipo de solicitud
                if ($request->expectsJson()) {
                    return $this->successResponse(
                        $member,
                        'Socio actualizado exitosamente.'
                    );
                }
                
                return $this->redirectWithSuccess(
                    'admin.members.show',
                    'Socio actualizado exitosamente.',
                    ['member' => $member->id]
                );
                
            }); // Fin de la transacción
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors(),
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Error al actualizar socio: ' . $e->getMessage(), [
                'exception' => $e,
                'member_id' => $member->id,
                'request' => $request->except(['profile_photo', 'password', 'password_confirmation'])
            ]);
            
            $errorMessage = 'Error al actualizar el socio. Por favor, intente nuevamente.';
            
            if ($request->expectsJson()) {
                return $this->errorResponse($errorMessage, 500);
            }
            
            return $this->redirectWithError(
                'admin.members.edit',
                $errorMessage,
                ['member' => $member->id]
            )->withInput();
        }
    }
    
    /**
     * Update or create a membership for the member.
     *
     * @param  \App\Models\Member  $member
     * @param  array  $validated
     * @return void
     */
    protected function updateOrCreateMembership($member, $validated)
    {
        $membershipType = MembershipType::findOrFail($validated['membership_type_id']);
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = isset($validated['end_date']) 
            ? Carbon::parse($validated['end_date'])
            : $startDate->copy()->addDays($membershipType->duration_days);
        
        $membershipData = [
            'membership_type_id' => $validated['membership_type_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'price' => $membershipType->price,
            'discount' => $validated['discount'] ?? 0,
            'final_price' => $this->calculateFinalPrice($membershipType->price, $validated['discount'] ?? 0),
            'payment_method' => $validated['payment_method'] ?? 'cash',
            'payment_status' => $validated['payment_status'] ?? 'pending',
            'notes' => $validated['notes'] ?? null,
            'updated_by' => auth()->id(),
        ];
        
        if ($member->activeMembership) {
            $member->activeMembership->update($membershipData);
        } else {
            $membershipData['created_by'] = auth()->id();
            $member->memberships()->create($membershipData);
        }
    }
    
    /**
     * Deactivate the specified member (soft delete by setting status to inactive).
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        try {
            // Verificar permisos
            $this->checkPermissions('eliminar_socios');
            
            // Verificar si el socio ya está inactivo
            if (!$member->status) {
                $message = 'El socio ya está inactivo.';
                
                if (request()->expectsJson()) {
                    return $this->errorResponse($message, 422);
                }
                
                return $this->redirectWithError(
                    'admin.members.index',
                    $message
                );
            }
            
            // Usar transacción para asegurar la integridad de los datos
            return DB::transaction(function () use ($member) {
                // Cambiar el estado del socio a inactivo
                $member->update(['status' => false]);
                
                // También desactivar el usuario asociado si existe
                if ($member->user) {
                    $member->user->update(['status' => false]);
                }
                
                if (request()->expectsJson()) {
                    return $this->successResponse(
                        null,
                        'Socio desactivado exitosamente.'
                    );
                }
                
                return $this->redirectWithSuccess(
                    'admin.members.index',
                    'Socio desactivado exitosamente. Ya no aparecerá en la lista de socios activos.'
                );
                
            }); // Fin de la transacción
            
        } catch (\Exception $e) {
            Log::error('Error al eliminar socio: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            if (request()->expectsJson()) {
                return $this->errorResponse('Error al eliminar el socio: ' . $e->getMessage());
            }
            
            return $this->redirectWithError(
                'admin.members.index',
                'Error al eliminar el socio: ' . $e->getMessage()
            );
        }
    }
    
    /**
     * Generate a unique member code.
     *
     * @return string
     */
    protected function generateMemberCode()
    {
        $prefix = 'SOC';
        $lastMember = Member::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $lastMember ? $lastMember->id + 1 : 1;
        
        return $prefix . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
    
    /**
     * Calculate the final price with discount.
     *
     * @param  float|int|string  $price    The original price (can be numeric string, float or int)
     * @param  float|int|string  $discount The discount percentage (0-100)
     * @return float The final price after applying the discount
     * @throws \InvalidArgumentException If price is negative or discount is invalid
     */
    protected function calculateFinalPrice($price, $discount)
    {
        // Convertir a float si es string numérico
        $price = is_numeric($price) ? (float)$price : 0;
        $discount = is_numeric($discount) ? (float)$discount : 0;
        
        // Validar que el precio no sea negativo
        if ($price < 0) {
            throw new \InvalidArgumentException('El precio no puede ser negativo');
        }
        
        // Validar que el descuento esté entre 0 y 100
        if ($discount < 0 || $discount > 100) {
            throw new \InvalidArgumentException('El descuento debe estar entre 0 y 100');
        }
        
        // Si no hay descuento, devolver el precio original
        if ($discount == 0) {
            return $price;
        }
        
        // Calcular el precio final con descuento
        $finalPrice = $price - ($price * ($discount / 100));
        
        // Asegurar que no devolvamos un precio negativo (por si acaso)
        return max(0, round($finalPrice, 2));
    }
    
    /**
     * Create a user account for the member.
     *
     * @param  \App\Models\Member  $member
     * @param  array  $data
     * @return void
     */
    protected function createUserForMember(Member $member, array $data)
    {
        // Verificar si ya existe un usuario con este correo
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            // Generar una contraseña aleatoria
            $password = Str::random(10);
            
            // Crear el usuario
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'role' => 'member',
                'status' => $data['status'] ?? true,
            ]);
            
            // Asociar el usuario al socio
            $member->update(['user_id' => $user->id]);
            
            // Aquí podrías enviar un correo con las credenciales al socio
            // Mail::to($user->email)->send(new MemberCredentials($user, $password));
        } else {
            // Si el usuario ya existe, actualizar sus datos
            $user->update([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'status' => $data['status'] ?? true,
            ]);
            
            // Asociar el usuario al socio si no está asociado
            if (!$member->user_id) {
                $member->update(['user_id' => $user->id]);
            }
        }
    }
    
    /**
     * Create or update user account for the member.
     *
     * @param  \App\Models\Member  $member
     * @param  array  $data
     * @return void
     */
    protected function createOrUpdateUserForMember(Member $member, array $data)
    {
        // Si ya tiene un usuario asociado, actualizarlo
        if ($member->user) {
            $member->user->update([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'status' => $data['status'] ?? true,
            ]);
        } 
        // Si no tiene usuario pero tiene correo, crear uno
        elseif (isset($data['email']) && $data['email']) {
            $this->createUserForMember($member, $data);
        }
    }
}




