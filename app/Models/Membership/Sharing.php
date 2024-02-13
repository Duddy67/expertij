<?php

namespace App\Models\Membership;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Membership;
use App\Models\Membership\Jurisdiction;
use App\Models\Cms\Document;
use App\Models\Cms\Setting;
use App\Traits\AccessLevel;
use App\Traits\CheckInCheckOut;
use App\Traits\OptionList;
use Illuminate\Http\Request;

class Sharing extends Model
{
    use HasFactory, AccessLevel, CheckInCheckOut, OptionList;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'membership_sharings';

    /**
     * Get the documents associated with the sharing.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }


    /*
     * Gets the group items according to the filter, sort and pagination settings.
     */
    public static function getSharings(Request $request)
    {
        $perPage = $request->input('per_page', Setting::getValue('pagination', 'per_page'));
        $search = $request->input('search', null);
        $sortedBy = $request->input('sorted_by', null);
        $ownedBy = $request->input('owned_by', null);
        $permission = $request->input('permission', null);

        $query = Sharing::query();
        $query->select('membership_sharings.*', 'users.name as owner_name')->leftJoin('users', 'membership_sharings.owned_by', '=', 'users.id');
        // Join the role tables to get the owner's role level.
        $query->join('model_has_roles', 'membership_sharings.owned_by', '=', 'model_id')->join('roles', 'roles.id', '=', 'role_id');

        if ($search !== null) {
            $query->where('membership_sharings.name', 'like', '%'.$search.'%');
        }

        if ($sortedBy !== null) {
            preg_match('#^([a-z0-9_]+)_(asc|desc)$#', $sortedBy, $matches);
            $query->orderBy($matches[1], $matches[2]);
        }

        if ($ownedBy !== null) {
            $query->whereIn('membership_sharings.owned_by', $ownedBy);
        }

        $query->where(function($query) {
            $query->where('roles.role_level', '<', auth()->user()->getRoleLevel())
                  ->orWhereIn('membership_sharings.access_level', ['public_ro', 'public_rw'])
                  ->orWhere('membership_sharings.owned_by', auth()->user()->id);
        });

        return $query->paginate($perPage);
    }

    public function getLicenceTypeOptions(): array
    {
        $membership = new membership;

        return $membership->getLicenceTypeOptions();
    }

    public function getCourtOptions(): array
    {
        $options = [];
        $courts = Jurisdiction::where('type', 'court')->get();

        foreach ($courts as $court) {
            $options[] = ['value' => $court->id, 'text' => $court->name];
        }

        return $options;
    }

    public function getAppealCourtOptions(): array
    {
        $options = [];
        $appealCourts = Jurisdiction::where('type', 'appeal_court')->get();

        foreach ($appealCourts as $appealCourt) {
            $options[] = ['value' => $appealCourt->id, 'text' => $appealCourt->name];
        }

        return $options;
    }
}
