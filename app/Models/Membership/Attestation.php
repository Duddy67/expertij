<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Licence;
use App\Models\Skill;

class Attestation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'membership_attestations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expiry_date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expiry_date'
    ];

    /**
     * Get the licence that owns the attestation.
     */
    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class);
    }

    /**
     * The skills that belong to the attestation.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}
