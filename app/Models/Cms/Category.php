<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\Cms\Setting;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Post\Setting as PostSetting;
use App\Models\Cms\Order;
use App\Models\Cms\Document;
use App\Traits\Node;
use App\Traits\OptionList;
use App\Models\User\Group;
use App\Traits\TreeAccessLevel;
use App\Traits\CheckInCheckOut;
use Illuminate\Http\Request;


class Category extends Model
{
    use HasFactory, Node, TreeAccessLevel, CheckInCheckOut, OptionList;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'status',
        'owned_by',
        'description',
        'alt_img',
        'access_level',
        'parent_id',
        'extra_fields',
        'meta_data',
        'settings',
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
     * The attributes that should be casted.
     *
     * @var array
     */
    protected $casts = [
        'extra_fields' => 'array',
        'meta_data' => 'array',
        'settings' => 'array'
    ];

    protected $categorizableTypes = [
        'post' => Post::class,
    ];

    /**
     * The extra group fields.
     *
     * @var array
     */
    public $fieldGroups = [
        'meta_data',
        'extra_fields'
    ];

    /**
     * Get all of the posts that are assigned this category.
     */
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'categorizable');
    }

    /**
     * The groups that belong to the category.
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'category_group');
    }

    /**
     * The item orders that belong to the category.
     */
    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('item_order');
    }

    /**
     * Get the image associated with the category.
     */
    public function image()
    {
        return $this->morphOne(Document::class, 'documentable')->where('field', 'image');
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
        $this->orders()->delete();
        $this->image()->delete();
        $this->posts()->detach();

        parent::delete();
    }
    /*
     * Gets the category items as a tree.
     */
    public static function getCategories(Request $request, $collectionType)
    {
        $search = $request->input('search', null);

        if ($search !== null) {
            return Category::where('name', 'like', '%'.$search.'%')->where('collection_type', $collectionType)->get();
        }
        else {
            return Category::select('categories.*', 'users.name as owner_name')
                             ->leftJoin('users', 'categories.owned_by', '=', 'users.id')
                             ->where('collection_type', $collectionType)
                             ->defaultOrder()->get()->toTree();
        }
    }

    public function getUrl()
    {
        $segments = Setting::getSegments(ucfirst($this->collection_type));
        return '/'.$segments['categories'].'/'.$this->id.'/'.$this->slug;
    }

    /*public function getAllItems(Request $request, string $itemType)
    {
        $query = $this->categorizableTypes[$itemType]::getCategorizableQuery($request);
        return $query->get();
    }*/

    public function getItems(Request $request, array $options = [])
    {
        //$perPage = $request->input('per_page', Setting::getValue('pagination', 'per_page'));
        return $this->categorizableTypes[$this->collection_type]::getCategorizables($request, $this, $options);

        //return $query->paginate($perPage);
    }

    /*
     * Returns posts without pagination.
     */
    /*public function getAllPosts(Request $request)
    {
        $query = $this->getQuery($request);
        return $query->get();
    }*/

    /*
     * Returns filtered and paginated posts.
     */
    /*public function getPosts(Request $request)
    {
        $perPage = $request->input('per_page', Setting::getValue('pagination', 'per_page'));
        $search = $request->input('search', null);
        $query = $this->getQuery($request);

        if ($search !== null) {
            $query->where('posts.title', 'like', '%'.$search.'%');
        }

        return $query->paginate($perPage);
    }*/

    /*
     * Builds the Post query.
     */
    /*private function getQuery(Request $request)
    {
        $query = Post::query();
        $query->select('posts.*', 'users.name as owner_name')->leftJoin('users', 'posts.owned_by', '=', 'users.id');
        // Join the role tables to get the owner's role level.
        $query->join('model_has_roles', 'posts.owned_by', '=', 'model_id')->join('roles', 'roles.id', '=', 'role_id');

        // Get only the posts related to this category. 
        $query->whereHas('categories', function ($query) {
            $query->where('id', $this->id);
        });

        if (Auth::check()) {

            // N.B: Put the following part of the query into brackets.
            $query->where(function($query) {

                // Check for access levels.
                $query->where(function($query) {
                    $query->where('roles.role_level', '<', auth()->user()->getRoleLevel())
                          ->orWhereIn('posts.access_level', ['public_ro', 'public_rw'])
                          ->orWhere('posts.owned_by', auth()->user()->id);
                });

                $groupIds = auth()->user()->getGroupIds();

                if (!empty($groupIds)) {
                    // Check for access through groups.
                    $query->orWhereHas('groups', function ($query)  use ($groupIds) {
                        $query->whereIn('id', $groupIds);
                    });
                }
            });
        }
        else {
            $query->whereIn('posts.access_level', ['public_ro', 'public_rw']);
        }
 
        // Do not show unpublished posts on front-end.
        $query->where('posts.status', 'published');

        // Set post ordering.
        $settings = $this->getSettings();

        if ($settings['post_ordering'] != 'no_ordering') {
            // Extract the ordering name and direction from the setting value.
            preg_match('#^([a-z-0-9_]+)_(asc|desc)$#', $settings['post_ordering'], $ordering);

            // Check for numerical sorting.
            if ($ordering[1] == 'order') {
                $query->join('orders', function ($join) use ($ordering) { 
                    $join->on('posts.id', '=', 'orderable_id')
                         ->where('orderable_type', '=', Post::class)
                         ->where('category_id', '=', $this->id);
                })->orderBy('item_order', $ordering[2]);
            }
            // Regular sorting.
            else {
                $query->orderBy($ordering[1], $ordering[2]);
            }
        }

        return $query;
    }*/

    public function getOwnedByOptions()
    {
        $users = auth()->user()->getAssignableUsers(['assistant', 'registered']);
        $options = [];

        foreach ($users as $user) {
            $extra = [];

            // The user is a manager who doesn't or no longer have the create-post-category permission.
            if ($user->getRoleType() == 'manager' && !$user->can('create-'.$this->collection_type.'-category')) {
                // The user owns this category.
                // N.B: A new owner will be required when updating this category. 
                if ($this->id && $this->access_level != 'private') {
                    // Don't show this user.
                    continue;
                }

                // If the user owns a private category his name will be shown until the category is no longer private.
            }

            $options[] = ['value' => $user->id, 'text' => $user->name, 'extra' => $extra];
        }

        return $options;
    }

    public function getSettings()
    {
        return Setting::getItemSettings($this, 'categories');
    }

    public function getPostOrderingOptions()
    {
        return PostSetting::getPostOrderingOptions();
    }

    /*
     * Generic function that returns model values which are handled by select inputs. 
     */
    public function getSelectedValue(\stdClass $field): mixed
    {
        if ($field->name == 'groups') {
            return $this->groups->pluck('id')->toArray();
        }

        if (isset($field->group) && $field->group == 'settings') {
            return (isset($this->settings[$field->name])) ? $this->settings[$field->name] : null;
        }

        return $this->{$field->name};
    }

    public function getExtraFieldByAlias($alias)
    {
        return Setting::getExtraFieldByAlias($this, $alias);
    }
}