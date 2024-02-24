<?php

namespace App\Http\Controllers\Admin\Membership;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membership\Sharing;
use App\Models\Cms\Document;
use App\Models\Cms\Setting;
use App\Traits\Form;
use App\Traits\CheckInCheckOut;
use App\Traits\Emails;
use App\Http\Requests\Membership\Sharing\StoreRequest;
use App\Http\Requests\Membership\Sharing\UpdateRequest;
use App\Http\Requests\Membership\Sharing\DocumentRequest;

class SharingController extends Controller
{
    use Form, CheckInCheckOut, Emails;

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $sharing = $this->item = Sharing::select('membership_sharings.*', 'users.name as owner_name', 'users2.name as modifier_name')
                                    ->leftJoin('users', 'membership_sharings.owned_by', '=', 'users.id')
                                    ->leftJoin('users as users2', 'membership_sharings.updated_by', '=', 'users2.id')
                                    ->findOrFail($id);
                        
        if (!$sharing->canAccess()) {
            return redirect()->route('admin.memberships.sharings.index')->with('error',  __('messages.generic.access_not_auth'));
        }

        if ($sharing->checked_out && $sharing->checked_out != auth()->user()->id && !$sharing->isUserSessionTimedOut()) {
            return redirect()->route('admin.memberships.sharings.index')->with('error',  __('messages.generic.checked_out'));
        }

        $sharing->checkOut();

        // Gather the needed data to build the form.
        $except = (auth()->user()->getRoleLevel() > $sharing->getOwnerRoleLevel() || $sharing->owned_by == auth()->user()->id) ? ['owner_name'] : ['owned_by'];
        $fields = $this->getFields($except);
        //$this->setFieldValues($fields, $sharing);
        $except = (!$sharing->canEdit()) ? ['destroy', 'save', 'saveClose'] : [];
        $actions = $this->getActions('form', $except);
        $dateFormat = Setting::getValue('app', 'date_format');
        // Add the id parameter to the query.
        $query = array_merge($request->query(), ['sharing' => $id]);

        return view('admin.membership.sharing.form', compact('sharing', 'fields', 'actions', 'dateFormat', 'query'));
    }

    /**
     * Checks the record back in.
     *
     * @param  Request  $request
     * @param  \App\Models\Membership\Sharing  $sharing (optional)
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
     * Update the specified post. (AJAX)
     *
     * @param  \App\Http\Requests\Post\UpdateRequest  $request
     * @param  \App\Models\Membership\Sharing  $sharing
     * @return JSON
     */
    public function update(UpdateRequest $request, Sharing $sharing)
    {
        if ($sharing->checked_out != auth()->user()->id) {
            $request->session()->flash('error', __('messages.generic.user_id_does_not_match'));
            return response()->json(['redirect' => route('admin.memberships.sharings.index', $request->query())]);
        }

        if (!$sharing->canEdit()) {
            $request->session()->flash('error', __('messages.generic.edit_not_auth'));
            return response()->json(['redirect' => route('admin.memberships.sharings.index', $request->query())]);
        }

        $sharing->name = $request->input('name');
        $sharing->licence_types = implode(',', $request->input('licence_types'));
        $sharing->courts = (empty($request->input('courts', []))) ? null : implode(',', $request->input('courts'));
        $sharing->appeal_courts = (empty($request->input('appeal_courts', []))) ? null : implode(',', $request->input('appeal_courts'));

        if ($sharing->canChangeStatus()) {
            $sharing->status = $request->input('status');
        }

        if ($sharing->canChangeAccessLevel()) {
            $sharing->access_level = $request->input('access_level');
        }

        if ($sharing->canChangeAttachments()) {
            $sharing->owned_by = $request->input('owned_by');
        }

        $sharing->save();

        if ($request->input('_close', null)) {
            $sharing->safeCheckIn();
            // Store the message to be displayed on the list view after the redirect.
            $request->session()->flash('success', __('messages.sharing.update_success'));
            return response()->json(['redirect' => route('admin.memberships.sharings.index', $request->query())]);
        }

        // Used in the Form trait.
        $this->item = $sharing;

        return response()->json(['success' => __('messages.sharing.update_success'), 'updates' => $this->getFieldValuesToUpdate($request)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $sharing = Sharing::create([
          'name' => $request->input('name'), 
          'licence_types' => implode(',', $request->input('licence_types')), 
          'courts' => (empty($request->input('courts', []))) ? null : implode(',', $request->input('courts')), 
          'appeal_courts' => (empty($request->input('appeal_courts', []))) ? null : implode(',', $request->input('appeal_courts')), 
          //'description' => $request->input('description'), 
          'status' => $request->input('status'), 
          'access_level' => $request->input('access_level'), 
          'owned_by' => $request->input('owned_by'),
        ]);

        $sharing->save();

        foreach ($request->all() as $key => $input) {
            if (str_starts_with($key, 'document_')) {
                $document = new Document;
                $document->upload($input, 'sharing');
                $sharing->documents()->save($document);
            }
        }

        $request->session()->flash('success', __('messages.sharing.create_success'));

        if ($request->input('_close', null)) {
            return response()->json(['redirect' => route('admin.memberships.sharings.index', $request->query())]);
        }

        // Redirect to the edit form.
        return response()->json(['redirect' => route('admin.memberships.sharings.edit', array_merge($request->query(), ['sharing' => $sharing->id]))]);
    }

    /**
     * Checks in one or more memberships.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function massCheckIn(Request $request)
    {
        $messages = CheckInCheckOut::checkInMultiple($request->input('ids'), '\\App\\Models\\Membership\\Sharing');

        return redirect()->route('admin.memberships.sharings.index', $request->query())->with($messages);
    }

    /**
     * Remove the specified sharing from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Membership\Sharing $sharing
     * @return Response
     */
    public function destroy(Request $request, Sharing $sharing)
    {
        if (!$sharing->canDelete()) {
            return redirect()->route('admin.memberships.sharings.edit', array_merge($request->query(), ['sharing' => $sharing->id]))->with('error',  __('messages.generic.delete_not_auth'));
        }

        $name = $sharing->name;
        $sharing->delete();

        return redirect()->route('admin.memberships.sharings.index', $request->query())->with('success', __('messages.sharing.delete_success', ['name' => $name]));
    }

    /**
     * Removes one or more sharings from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function massDestroy(Request $request)
    {
        $deleted = 0;

        // Remove the posts selected from the list.
        foreach ($request->input('ids') as $id) {
            $sharing = Sharing::findOrFail($id);

            if (!$sharing->canDelete()) {
              return redirect()->route('admin.memberships.sharings.index', $request->query())->with(
                  [
                      'error' => __('messages.generic.delete_not_auth'),
                      'success' => __('messages.sharing.delete_list_success', ['number' => $deleted])
                  ]);
            }

            $sharing->delete();

            $deleted++;
        }

        return redirect()->route('admin.memberships.sharings.index', $request->query())->with('success', __('messages.sharing.delete_list_success', ['number' => $deleted]));
    }

    /**
     *  Sends notification emails to the recipients.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendEmails(Request $request, Sharing $sharing)
    {
        if ($this->alertDocument($sharing)) {
            $sharing->sending_emails = true;
            $sharing->save();
            return response()->json(['success' => __('messages.sharing.alert_document'), 'updates' => ['sendEmails' => true]]);
        }
        else {
            return response()->json(['warning' => __('messages.generic.cannot_send_email')]);
        }
    }

    public function addDocument(DocumentRequest $request)
    {
        $sharing = Sharing::where('id', $request->input('_sharing_id'))->first();
        // Create and upload the new document.
        $document = new Document;
        $document->upload($request->file('add_document'), 'sharing');
        // Add the new document.
        $sharing->documents()->save($document);
        $row = view('admin.partials.sharing.document-row', compact('document', 'sharing'))->render();

        return response()->json(['success' => __('messages.document.create_success'), 'action' => 'add', 'row' => $row]);
    }

    public function replaceDocument(DocumentRequest $request, $id)
    {
        $sharing = Sharing::where('id', $request->input('_sharing_id'))->first();
        // Create and upload the new document.
        $document = new Document;
        $document->upload($request->file('replace_document_'.$id), 'sharing');
        $sharing->documents()->save($document);
        $row = view('admin.partials.sharing.document-row', compact('document', 'sharing'))->render();

        // Delete the document that was replaced.
        $document = Document::where('id', $id)->first();
        $document->delete();

        return response()->json(['success' => __('messages.document.replace_success'), 'action' => 'replace', 'oldId' => $id, 'row' => $row]);
    }

    public function deleteDocument(Request $request, $id)
    {
        $document = Document::where('id', $id)->first();
        $name = $document->file_name;
        $document->delete();

        return response()->json(['success' => __('messages.document.delete_success', ['name' => $name]), 'action' => 'delete', 'id' => $id]);
    }
}
