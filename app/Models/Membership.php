<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Traits\AccessLevel;
use App\Traits\CheckInCheckOut;
use App\Traits\OptionList;
use App\Models\User;
use App\Models\Insurance;
use App\Models\Licence;
use App\Models\Vote;
use App\Models\Payment;

class Membership extends Model
{
    use HasFactory, AccessLevel, CheckInCheckOut, OptionList;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'memberships';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'pro_status',
        'pro_status_info',
        'siret_number',
        'naf_code',
        'linguistic_training',
        'extra_linguistic_training',
        'professional_experience',
        'observations',
        'why_expertij',
        'owned_by',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'checked_out_time',
        'member_since'
    ];

    /**
     * Get the user that owns the membership.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the insurance that owns the membership.
     */
    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }

    /**
     * The licences that belong to the membership.
     */
    public function licences(): HasMany
    {
        return $this->hasMany(Licence::class);
    }

    /**
     * The votes that belong to the membership.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * The payments that belong to the membership.
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
