<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id',
        'membership_type_id',
        'start_date',
        'end_date',
        'status',
        'price',
        'discount',
        'final_price',
        'payment_method',
        'payment_status',
        'notes',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'days_remaining',
        'is_active',
    ];

    /**
     * Get the member that owns the membership.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the membership type for the membership.
     */
    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }

    /**
     * Get the user who created the membership.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculate the remaining days of the membership.
     *
     * @return int|null
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->end_date) {
            return null;
        }

        $now = now();
        $endDate = $this->end_date;

        if ($endDate < $now) {
            return 0;
        }

        return $now->diffInDays($endDate, false);
    }

    /**
     * Check if the membership is active.
     *
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Scope a query to only include active memberships.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        $today = now()->format('Y-m-d');
        return $query->where('status', 'active')
                    ->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
    }

    /**
     * Scope a query to only include expired memberships.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        $today = now()->format('Y-m-d');
        return $query->where('end_date', '<', $today);
    }

    /**
     * Scope a query to only include memberships that will expire soon.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpiringSoon($query, $days = 7)
    {
        $today = now()->format('Y-m-d');
        $targetDate = now()->addDays($days)->format('Y-m-d');
        
        return $query->where('status', 'active')
                    ->where('end_date', '>=', $today)
                    ->where('end_date', '<=', $targetDate);
    }
}
