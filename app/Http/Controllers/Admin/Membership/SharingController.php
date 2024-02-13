<?php

namespace App\Http\Controllers\Admin\Membership;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membership\Sharing;
use App\Traits\Form;
use App\Traits\CheckInCheckOut;

class SharingController extends Controller
{
    use Form;

    /*
     * Instance of the Sharing model, (used in the Form trait).
     */
    protected $item = null;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware('admin.memberships.sharings');
        $this->item = new Sharing;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Gather the needed data to build the item list.
        $columns = $this->getColumns();
        $actions = $this->getActions('list');
        $filters = $this->getFilters($request);
        $items = Sharing::getSharings($request);
        $rows = $this->getRows($columns, $items);
        //$this->setRowValues($rows, $columns, $items);
        $query = $request->query();
        $url = ['route' => 'admin.memberships.sharings', 'item_name' => 'sharing', 'query' => $query];

        return view('admin.membership.sharing.list', compact('items', 'columns', 'rows', 'actions', 'filters', 'url', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Gather the needed data to build the form.

        $fields = $this->getFields(['updated_by', 'created_at', 'updated_at', 'owner_name']);
        //$this->setFieldValues($fields, $this->item);
        $actions = $this->getActions('form', ['destroy']);
        $query = $request->query();

        return view('admin.membership.sharing.form', compact('fields', 'actions', 'query'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Checks the record back in.
     *
     * @param  Request  $request
     * @param  \App\Models\Post  $post (optional)
     * @return Response
     */
    public function cancel(Request $request, Sharing $sharing = null)
    {
        if ($sharing) {
            $sharing->safeCheckIn();
        }

        return redirect()->route('admin.memberships.sharings.index', $request->query());
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
