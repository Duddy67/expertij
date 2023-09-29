<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Citizenship extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'citizenships';

    /**
     * No timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alpha_3',
        'published',
        'fr',
    ];

    /**
     * The users that belong to the citizenship.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'citizenship_id', 'alpha_3');
    }
}
