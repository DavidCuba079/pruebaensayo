<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MembershipController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin|staff');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Membership::with(['member', 'membershipType', 'createdBy']);
        
        // Búsqueda
        if (request()->has('search')) {
            $search = request('search');
            $query->whereHas('member', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }
        
        // Filtro por estado
        if (request()->has('status') && in_array(request('status'), ['active', 'expired', 'cancelled'])) {
            if (request('status') === 'active') {
                $query->where('status', 'active')
                      ->where('end_date', '>=', now()->toDateString());
            } else {
                $query->where('status', request('status'));
            }
        } else {
            // Por defecto, mostrar solo activas
            $query->where('status', 'active')
                  ->where('end_date', '>=', now()->toDateString());
        }
        
        // Ordenamiento
        $sortField = request('sort', 'end_date');
        $sortDirection = request('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);
        
        $memberships = $query->paginate(15)->withQueryString();
        
        return view('admin.memberships.index', compact('memberships'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        $membershipTypes = MembershipType::active()->orderBy('name')->get();
        
        return view('admin.memberships.create', compact('members', 'membershipTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'membership_type_id' => 'required|exists:membership_types,id',
            'start_date' => 'required|date',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,other',
            'payment_status' => 'required|in:pending,paid,overdue,refunded,cancelled',
            'discount' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $member = Member::findOrFail($validated['member_id']);
            $membershipType = MembershipType::findOrFail($validated['membership_type_id']);
            
            // Calcular fecha de finalización
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = $startDate->copy()->addDays($membershipType->duration_days);
            
            // Calcular precios
            $price = $membershipType->price;
            $discount = $validated['discount'] ?? 0;
            $finalPrice = $price - ($price * ($discount / 100));
            
            // Crear la membresía
            $membership = new Membership([
                'membership_type_id' => $membershipType->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'price' => $price,
                'discount' => $discount,
                'final_price' => $finalPrice,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_status'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
            
            $member->memberships()->save($membership);
            
            // Si el miembro no tiene usuario pero tiene correo, crear un usuario para él
            if ($member->email && !$member->user_id) {
                $this->createUserForMember($member);
            }
            
            DB::commit();
            
            return redirect()->route('admin.memberships.show', $membership)
                ->with('success', 'Membresía creada exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear membresía: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Ocurrió un error al crear la membresía. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function show(Membership $membership)
    {
        $membership->load([
            'member',
            'membershipType',
            'createdBy',
            'payments' => function($query) {
                $query->latest();
            }
        ]);
        
        $checkIns = $membership->checkIns()
            ->with('user')
            ->latest()
            ->paginate(10, ['*'], 'check_ins_page');
        
        return view('admin.memberships.show', compact('membership', 'checkIns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function edit(Membership $membership)
    {
        $membership->load('member');
        $membershipTypes = MembershipType::active()->orderBy('name')->get();
        
        return view('admin.memberships.edit', compact('membership', 'membershipTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'membership_type_id' => 'required|exists:membership_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,paused,cancelled,expired',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'final_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,other',
            'payment_status' => 'required|in:pending,paid,overdue,refunded,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Actualizar la membresía
            $membership->update([
                'membership_type_id' => $validated['membership_type_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $validated['status'],
                'price' => $validated['price'],
                'discount' => $validated['discount'] ?? 0,
                'final_price' => $validated['final_price'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_status'],
                'notes' => $validated['notes'] ?? null,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.memberships.show', $membership)
                ->with('success', 'Membresía actualizada exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al actualizar membresía: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Ocurrió un error al actualizar la membresía. Por favor, inténtelo de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function destroy(Membership $membership)
    {
        try {
            // Verificar si la membresía tiene pagos asociados
            if ($membership->payments()->exists()) {
                return back()->with('error', 'No se puede eliminar la membresía porque tiene pagos asociados.');
            }
            
            $membership->delete();
            
            return redirect()->route('admin.memberships.index')
                ->with('success', 'Membresía eliminada exitosamente.');
                
        } catch (\Exception $e) {
            \Log::error('Error al eliminar membresía: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Ocurrió un error al eliminar la membresía. Por favor, inténtelo de nuevo.');
        }
    }
    
    /**
     * Renovar una membresía existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function renew(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'membership_type_id' => 'required|exists:membership_types,id',
            'start_date' => 'required|date',
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer,other',
            'payment_status' => 'required|in:pending,paid,overdue,refunded,cancelled',
            'discount' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $membershipType = MembershipType::findOrFail($validated['membership_type_id']);
            
            // Calcular fechas
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = $startDate->copy()->addDays($membershipType->duration_days);
            
            // Calcular precios
            $price = $membershipType->price;
            $discount = $validated['discount'] ?? 0;
            $finalPrice = $price - ($price * ($discount / 100));
            
            // Crear la nueva membresía
            $newMembership = new Membership([
                'membership_type_id' => $membershipType->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'price' => $price,
                'discount' => $discount,
                'final_price' => $finalPrice,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_status'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
            
            $membership->member->memberships()->save($newMembership);
            
            // Actualizar la membresía anterior a expirada
            $membership->update(['status' => 'expired']);
            
            DB::commit();
            
            return redirect()->route('admin.memberships.show', $newMembership)
                ->with('success', 'Membresía renovada exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al renovar membresía: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Ocurrió un error al renovar la membresía. Por favor, inténtelo de nuevo.');
        }
    }
    
    /**
     * Cancelar una membresía.
     *
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function cancel(Membership $membership)
    {
        try {
            $membership->update([
                'status' => 'cancelled',
                'end_date' => now(),
            ]);
            
            return back()->with('success', 'Membresía cancelada exitosamente.');
                
        } catch (\Exception $e) {
            \Log::error('Error al cancelar membresía: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Ocurrió un error al cancelar la membresía. Por favor, inténtelo de nuevo.');
        }
    }
    
    /**
     * Crear un usuario para un miembro si no existe.
     *
     * @param  \App\Models\Member  $member
     * @return void
     */
    protected function createUserForMember(Member $member)
    {
        if ($member->email && !$member->user_id) {
            $user = User::firstOrCreate(
                ['email' => $member->email],
                [
                    'name' => $member->first_name . ' ' . $member->last_name,
                    'password' => bcrypt(Str::random(10)),
                    'role' => 'member',
                    'status' => $member->status,
                ]
            );
            
            // Asociar el usuario al miembro
            $member->update(['user_id' => $user->id]);
            
            // Aquí podrías enviar un correo con las credenciales al miembro
            // Mail::to($user->email)->send(new MemberCredentials($user, $password));
        }
    }
}
