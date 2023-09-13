<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Licence;
use App\Models\Language;

class Skill extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'membership_skills';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'interpreter',
        'interpreter_cassation',
        'translator',
        'translator_cassation',
    ];

    /**
     * Get the licence that owns the skill.
     */
    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class);
    }

    /**
     * Get the language that owns the skill.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
