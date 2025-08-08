<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Member;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CheckInController extends Controller
{
    /**
     * Muestra el listado de registros de acceso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = CheckIn::with(['member', 'user', 'membership'])
            ->latest('check_in_at');

        // Búsqueda por nombre o DNI del socio
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('member', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo de acceso
        if ($request->filled('access_type') && in_array($request->access_type, ['check_in', 'check_out'])) {
            $query->where('access_type', $request->access_type);
        }

        // Filtro por fecha
        if ($request->filled('date_from')) {
            $query->whereDate('check_in_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_in_at', '<=', $request->date_to);
        }

        $checkIns = $query->paginate(25)->withQueryString();

        return view('admin.check-ins.index', compact('checkIns'));
    }

    /**
     * Muestra el formulario para registrar una nueva entrada/salida.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.check-ins.create');
    }

    /**
     * Busca un miembro por DNI o nombre para el registro de acceso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchMember(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $search = $request->input('q');
        
        $members = Member::where('dni', 'like', "%{$search}%")
            ->orWhere('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->with(['activeMembership'])
            ->limit(10)
            ->get()
            ->map(function($member) {
                return [
                    'id' => $member->id,
                    'text' => "{$member->full_name} (DNI: {$member->dni})",
                    'has_active_membership' => $member->activeMembership !== null,
                    'membership_ends' => $member->activeMembership ? $member->activeMembership->end_date->format('d/m/Y') : null,
                    'photo' => $member->profile_photo_url,
                ];
            });

        return response()->json($members);
    }

    /**
     * Registra una nueva entrada o salida.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'access_type' => ['required', Rule::in(['check_in', 'check_out'])],
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $member = Member::findOrFail($validated['member_id']);
            
            // Registrar el acceso
            $checkIn = CheckIn::register(
                $member,
                $validated['access_type'],
                $validated['notes'] ?? null
            );

            DB::commit();

            $message = $validated['access_type'] === 'check_in' 
                ? 'Entrada registrada exitosamente.' 
                : 'Salida registrada exitosamente.';

            return redirect()
                ->route('admin.check-ins.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al registrar el acceso: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un registro de acceso.
     *
     * @param  \App\Models\CheckIn  $checkIn
     * @return \Illuminate\View\View
     */
    public function show(CheckIn $checkIn)
    {
        $checkIn->load(['member', 'user', 'membership']);
        
        // Si es un check-in, intentamos obtener su check-out correspondiente
        $relatedRecord = null;
        if ($checkIn->access_type === 'check_in') {
            $relatedRecord = $checkIn->checkOut();
        } else {
            $relatedRecord = $checkIn->checkIn();
        }

        return view('admin.check-ins.show', compact('checkIn', 'relatedRecord'));
    }

    /**
     * Muestra el formulario para editar un registro de acceso.
     *
     * @param  \App\Models\CheckIn  $checkIn
     * @return \Illuminate\View\View
     */
    public function edit(CheckIn $checkIn)
    {
        $checkIn->load(['member', 'user']);
        return view('admin.check-ins.edit', compact('checkIn'));
    }

    /**
     * Actualiza un registro de acceso existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CheckIn  $checkIn
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, CheckIn $checkIn)
    {
        $validated = $request->validate([
            'check_in_at' => 'required|date',
            'access_type' => ['required', Rule::in(['check_in', 'check_out'])],
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $checkIn->update([
                'check_in_at' => $validated['check_in_at'],
                'access_type' => $validated['access_type'],
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(), // Actualizar el usuario que realizó la modificación
            ]);

            DB::commit();

            return redirect()
                ->route('admin.check-ins.show', $checkIn)
                ->with('success', 'Registro de acceso actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el registro: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un registro de acceso.
     *
     * @param  \App\Models\CheckIn  $checkIn
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CheckIn $checkIn)
    {
        try {
            // Verificar si es un check-in y tiene un check-out asociado
            if ($checkIn->access_type === 'check_in' && $checkIn->checkOut()) {
                return back()
                    ->with('error', 'No se puede eliminar este registro porque tiene una salida asociada.');
            }

            // Verificar si es un check-out y tiene un check-in asociado
            if ($checkIn->access_type === 'check_out' && $checkIn->checkIn()) {
                return back()
                    ->with('error', 'No se puede eliminar este registro porque tiene una entrada asociada.');
            }

            $checkIn->delete();

            return redirect()
                ->route('admin.check-ins.index')
                ->with('success', 'Registro de acceso eliminado exitosamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el panel de control de acceso.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Últimos registros de acceso
        $recentCheckIns = CheckIn::with(['member', 'user'])
            ->latest('check_in_at')
            ->take(10)
            ->get();

        // Conteo de accesos hoy
        $todayCount = CheckIn::whereDate('check_in_at', today())->count();

        // Miembros activos actualmente (con check-in sin check-out)
        $activeMembers = CheckIn::with('member')
            ->where('access_type', 'check_in')
            ->whereDoesntHave('checkOut')
            ->whereDate('check_in_at', today())
            ->count();

        // Miembros con membresías activas
        $activeMemberships = Membership::active()->count();

        return view('admin.check-ins.dashboard', compact(
            'recentCheckIns',
            'todayCount',
            'activeMembers',
            'activeMemberships'
        ));
    }

    /**
     * Registra una salida para un check-in existente.
     *
     * @param  \App\Models\CheckIn  $checkIn
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkOut(CheckIn $checkIn)
    {
        if ($checkIn->access_type !== 'check_in') {
            return back()->with('error', 'Este registro no es una entrada válida.');
        }

        if ($checkIn->checkOut()) {
            return back()->with('error', 'Ya se registró una salida para esta entrada.');
        }

        try {
            DB::beginTransaction();

            // Registrar la salida
            CheckIn::register(
                $checkIn->member,
                'check_out',
                'Salida registrada manualmente por ' . auth()->user()->name
            );

            DB::commit();

            return back()->with('success', 'Salida registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar la salida: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene el historial de accesos de un miembro.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\View\View
     */
    public function memberHistory(Member $member)
    {
        $checkIns = CheckIn::where('member_id', $member->id)
            ->with(['membership', 'user'])
            ->latest('check_in_at')
            ->paginate(20);

        return view('admin.check-ins.member-history', compact('member', 'checkIns'));
    }
}
