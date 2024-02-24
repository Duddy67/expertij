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
use Illuminate\Support\Facades\DB;

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'licence_types',
        'courts',
        'appeal_courts',
        'status',
        'owned_by',
        'access_level',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'checked_out_time'
    ];

    /**
     * Get the documents associated with the sharing.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Delete the model from the database (override).
     *
     * @return bool|null
     *
     * @throws \LogicException
     */
    public function delete()
    {
        // Delete the documents one by one or the corresponding files
        // on the server won't be deleted.
        foreach ($this->documents as $document) {
            $document->delete();
        }

        parent::delete();
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

    /*
     * Gets the shared documents for a given membership.
     */
    public static function getSharedDocuments(Membership $membership): array
    {
        $documents = [];
        $query = '';

        // Build a query with brackets for each licence type.
        foreach ($membership->licences as $licence) {
            $query .= '(`licence_types` LIKE "%'.$licence->type.'%" AND ';

            // Adapt the conditions according to the licence type.
            if ($licence->type == 'expert') {
                $query .= '(`appeal_courts` = "" OR `appeal_courts` = "'.$licence->jurisdiction_id.'" OR `appeal_courts` LIKE "'.$licence->jurisdiction_id.',%" OR `appeal_courts` LIKE "%,'.$licence->jurisdiction_id.'" OR `appeal_courts` LIKE "%,'.$licence->jurisdiction_id.',%")';
            }
            // ceseda
            else {
                $query .= '(`courts` = "" OR `courts` = "'.$licence->jurisdiction_id.'" OR `courts` LIKE "'.$licence->jurisdiction_id.',%" OR `courts` LIKE "%,'.$licence->jurisdiction_id.'" OR `courts` LIKE "%,'.$licence->jurisdiction_id.',%")';
            }

            // Close the condition and add a OR clause in case of multiple licences.
            $query .= ') OR ';
        }

        // Remove the final OR clause from the end of the query.
        $query = substr($query, 0, -4);

        // Get the sharing ids.
        $ids = DB::table('membership_sharings')
                ->whereRaw($query)
                ->pluck('id')->toArray();

        $sharings = Sharing::whereIn('id', $ids)->get();

        // Get the documents.
        foreach ($sharings as $sharing) {
            foreach ($sharing->documents as $document) {
                $documents[] = $document;
            }
        }

        return $documents;
    }

    public function getLicenceTypesOptions(): array
    {
        $membership = new membership;

        return $membership->getLicenceTypeOptions();
    }

    public function getCourtsOptions(): array
    {
        $options = [];
        $courts = Jurisdiction::where('type', 'court')->get();

        foreach ($courts as $court) {
            $options[] = ['value' => $court->id, 'text' => $court->name];
        }

        return $options;
    }

    public function getAppealCourtsOptions(): array
    {
        $options = [];
        $appealCourts = Jurisdiction::where('type', 'appeal_court')->get();

        foreach ($appealCourts as $appealCourt) {
            $options[] = ['value' => $appealCourt->id, 'text' => $appealCourt->name];
        }

        return $options;
    }
}
