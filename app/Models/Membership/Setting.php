<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OptionList;

class Setting extends Model
{
    use HasFactory, OptionList;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'membership_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    /**
     * No timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;


    /*
     * Returns the maximum number of days in a month.
     */
    public function getDaysInMonthOptions(): array
    {
        $options = [];
        $daysInMonth = 31;

        for ($i = 0; $i < $daysInMonth; $i++) {
            $day = $i + 1;
            // Check for zero fill.
            $day = ($day < 10) ? '0'.$day : $day;
            $options[] = ['value' => $day, 'text' => $day];
        }

        return $options;
    }

    /*
     * Returns the months of the year.
     */
    public function getMonthsOptions(): array
    {
        $options = [];
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];

        foreach ($months as $key => $month) {
            $numeric = $key + 1;
            // Check for zero fill.
            $numeric = ($numeric < 10) ? '0'.$numeric : $numeric;
            $options[] = ['value' => $numeric, 'text' =>  __('labels.generic.'.$month)];
        }

        return $options;
    }

    /*
     * Returns the current state (activated/deactivated) of a given flag.
     */
    public static function checkFlag($name): bool
    {
        return ((int)Setting::where('group', 'flags')->where('key', $name)->value('value')) ? true : false;
    }

    /*
     * Toggles the current state (activated/deactivated) of a given flag.
     */
    public static function toggleFlag($name)
    {
        $value = (self::checkFlag($name)) ? 0 : 1;
        Setting::where('group', 'flags')->where('key', $name)->update(['value' => $value]);
    }

    public static function setRunningRenewalDate(string $date)
    {
        Setting::where('group', 'flags')->where('key', 'running_renewal_date')->update(['value' => $date]);
    }
}
