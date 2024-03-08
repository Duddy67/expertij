<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OptionList;
use Carbon\Carbon;

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

    public static function getLastReminderDate()
    {
        $lastReminderDate = Setting::where('group', 'renewal')->where('key', 'last_reminder_date')->first()->value;
        return ($lastReminderDate) ? Carbon::create($lastReminderDate)->format('d/m/Y H:i') : __('labels.generic.none');
    }

    public static function getOldRenewalDate()
    {
        $oldRenewalDate = Setting::where('group', 'renewal')->where('key', 'old_renewal_date')->first()->value;
        return ($oldRenewalDate) ? Carbon::create($oldRenewalDate) : null; 
    }

    public static function setLastReminderDate(string $date)
    {
        Setting::where('group', 'renewal')->where('key', 'last_reminder_date')->update(['value' => $date]);
    }

    public static function setOldRenewalDate(string $date)
    {
        Setting::where('group', 'renewal')->where('key', 'old_renewal_date')->update(['value' => $date]);
    }
}
