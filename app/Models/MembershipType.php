<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipType extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'duration_days',
        'price',
        'discount_price',
        'is_active',
        'features',
        'classes_allowed',
        'max_entries_per_day',
        'max_entries_per_week',
        'can_freeze',
        'freeze_days_allowed',
        'requires_medical_certificate',
        'requires_contract',
        'contract_file',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
        'duration_days' => 'integer',
        'classes_allowed' => 'integer',
        'max_entries_per_day' => 'integer',
        'max_entries_per_week' => 'integer',
        'can_freeze' => 'boolean',
        'freeze_days_allowed' => 'integer',
        'requires_medical_certificate' => 'boolean',
        'requires_contract' => 'boolean',
        'features' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_price',
        'formatted_duration',
    ];

    /**
     * Get the memberships for the membership type.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Get the formatted price attribute.
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'S/ ' . number_format($this->price, 2, '.', ',');
    }

    /**
     * Get the formatted duration attribute.
     *
     * @return string
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration_days >= 30) {
            $months = floor($this->duration_days / 30);
            return $months . ' ' . str_plural('mes', $months);
        }
        
        return $this->duration_days . ' ' . str_plural('día', $this->duration_days);
    }

    /**
     * Scope a query to only include active membership types.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include membership types that require a medical certificate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRequiresMedicalCertificate($query)
    {
        return $query->where('requires_medical_certificate', true);
    }

    /**
     * Scope a query to only include membership types that require a contract.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRequiresContract($query)
    {
        return $query->where('requires_contract', true);
    }

    /**
     * Get the default features if none are set.
     *
     * @return array
     */
    protected function getFeaturesAttribute($value)
    {
        $features = json_decode($value, true);
        
        if (empty($features)) {
            return [
                'Acceso a todas las áreas del gimnasio',
                'Asesoría personalizada',
                'Sin costo de inscripción',
            ];
        }
        
        return $features;
    }
}
