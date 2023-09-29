<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Cms\Address;

class Country extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries';

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
        'alpha_2',
        'alpha_3',
        'numerical',
        'continent_code',
        'published',
        'fr',
    ];

    /**
     * Get the addresses for the country.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'country_id', 'alpha_3');
    }
}
