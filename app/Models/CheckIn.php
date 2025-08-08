<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class CheckIn extends Model
{
    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'membership_id',
        'user_id',
        'check_in_at',
        'access_type',
        'notes',
        'ip_address',
        'user_agent',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Tipos de acceso permitidos.
     *
     * @var array<string, string>
     */
    public const ACCESS_TYPES = [
        'check_in' => 'Entrada',
        'check_out' => 'Salida',
    ];

    /**
     * Obtiene el miembro asociado al registro de acceso.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Obtiene la membresía asociada al registro de acceso.
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Obtiene el usuario que registró el acceso.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar por tipo de acceso.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('access_type', $type);
    }

    /**
     * Scope para filtrar por miembro.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $memberId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    /**
     * Scope para filtrar por membresía.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $membershipId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForMembership($query, $membershipId)
    {
        return $query->where('membership_id', $membershipId);
    }

    /**
     * Registra una nueva entrada o salida.
     *
     * @param  \App\Models\Member  $member
     * @param  string  $accessType
     * @param  string|null  $notes
     * @return \App\Models\CheckIn
     */
    public static function register(Member $member, string $accessType = 'check_in', ?string $notes = null)
    {
        // Obtener la membresía activa del miembro
        $activeMembership = $member->activeMembership;
        
        if (!$activeMembership) {
            throw new \Exception('El miembro no tiene una membresía activa.');
        }

        // Verificar si la membresía está vencida
        if ($activeMembership->isExpired()) {
            throw new \Exception('La membresía del miembro ha expirado.');
        }

        // Verificar si el miembro ya tiene un check-in sin check-out
        if ($accessType === 'check_in') {
            $lastCheckIn = self::where('member_id', $member->id)
                ->where('access_type', 'check_in')
                ->whereDoesntHave('checkOut')
                ->latest('check_in_at')
                ->first();

            if ($lastCheckIn) {
                throw new \Exception('El miembro ya tiene un registro de entrada sin salida.');
            }
        }

        // Registrar el acceso
        return self::create([
            'member_id' => $member->id,
            'membership_id' => $activeMembership->id,
            'user_id' => Auth::id(),
            'check_in_at' => now(),
            'access_type' => $accessType,
            'notes' => $notes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Obtiene el check-out asociado a este check-in (si existe).
     */
    public function checkOut()
    {
        if ($this->access_type === 'check_in') {
            return $this->hasOne(CheckIn::class, 'member_id', 'member_id')
                ->where('access_type', 'check_out')
                ->where('check_in_at', '>', $this->check_in_at)
                ->orderBy('check_in_at')
                ->first();
        }
        
        return null;
    }

    /**
     * Obtiene el check-in asociado a este check-out (si existe).
     */
    public function checkIn()
    {
        if ($this->access_type === 'check_out') {
            return $this->hasOne(CheckIn::class, 'member_id', 'member_id')
                ->where('access_type', 'check_in')
                ->where('check_in_at', '<', $this->check_in_at)
                ->orderBy('check_in_at', 'desc')
                ->first();
        }
        
        return null;
    }

    /**
     * Obtiene la duración de la visita en minutos.
     *
     * @return int|null
     */
    public function getDurationInMinutes(): ?int
    {
        if ($this->access_type === 'check_in' && $checkOut = $this->checkOut()) {
            return $this->check_in_at->diffInMinutes($checkOut->check_in_at);
        }
        
        if ($this->access_type === 'check_out' && $checkIn = $this->checkIn()) {
            return $checkIn->check_in_at->diffInMinutes($this->check_in_at);
        }
        
        return null;
    }

    /**
     * Obtiene la duración de la visita formateada (ej: "2h 30m").
     *
     * @return string
     */
    public function getFormattedDuration(): string
    {
        $minutes = $this->getDurationInMinutes();
        
        if ($minutes === null) {
            return 'En progreso';
        }
        
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        $parts = [];
        if ($hours > 0) {
            $parts[] = $hours . 'h';
        }
        if ($remainingMinutes > 0 || empty($parts)) {
            $parts[] = $remainingMinutes . 'm';
        }
        
        return implode(' ', $parts);
    }
}
