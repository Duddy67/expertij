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
use App\Models\Membership\Insurance;
use App\Models\Membership\Licence;
use App\Models\Membership\Language;
use App\Models\Membership\Vote;
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

    const EARLIEST_YEAR = 1980;


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

    public function getLicenceTypeOptions(): array
    {
        return [
            ['value' => 'expert',  'text' => __('labels.membership.expert')],
            ['value' => 'ceseda',  'text' => __('labels.membership.ceseda')],
        ];
    }

    public function getProfessionalStatusOptions(): array
    {
        return [
            ['value' => 'liberal_profession', 'text' => __('labels.membership.liberal_profession')],
            ['value' => 'micro_entrepreneur', 'text' => __('labels.membership.micro_entrepreneur')],
            ['value' => 'company', 'text' => __('labels.membership.company')],
            ['value' => 'other', 'text' => __('labels.generic.other')],
        ];
    }

    public function getSinceOptions(): array
    {
        $options = [];
        // Get the current year.
        $year = date('Y');

	while ($year >= self::EARLIEST_YEAR) {
            $options[] = ['value' => $year, 'text' => $year];
	    $year--;
	}

        return $options;
    }

    public function getCivilityOptions(): array
    {
        // Get the User relationship model then use its method.
        return $this->user()->getRelated()->getCivilityOptions();
    }

    public function getCitizenshipOptions(): array
    {
        // Get the User relationship model then use its method.
        return $this->user()->getRelated()->getCitizenshipOptions();
    }

    public function getLanguageOptions(): array
    {
        $options = [];
        $languages = Language::where('published', 1)->orderBy('fr')->get();

        foreach ($languages as $language) {
            $options[] = ['value' => $language->alpha_3, 'text' => $language->fr];
        }

        return $options;
    }
}
