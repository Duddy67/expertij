<?php

namespace App\Http\Controllers\Admin\Membership;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Form;
use App\Models\Membership\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Cache;

class SettingController extends Controller
{
    use Form;

    /*
     * Instance of the membership Setting model, (used in the Form trait).
     */
    protected $item;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('admin.posts.settings');
        $this->item = new Setting;
    }


    /**
     * Show the membership settings.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $fields = $this->getFields();
        $actions = $this->getActions('form');
        $query = $request->query();
        // Use the CMS setting function.
        $data = \App\Models\Cms\Setting::getData($this->item);

        return view('admin.membership.setting.form', compact('fields', 'actions', 'data', 'query'));
    }

    /**
     * Update the membership parameters. (AJAX)
     *
     * @param  Request  $request
     * @return JSON
     */
    public function update(Request $request)
    {
        $post = $request->except('_token', '_method', '_tab');
        $this->truncateSettings();
//file_put_contents('debog_file.txt', print_r($post, true));
        foreach ($post as $group => $params) {
          foreach ($params as $key => $value) {
              Setting::create(['group' => $group, 'key' => $key, 'value' => $value]);
          }
        }

        return response()->json(['success' => __('messages.general.update_success')]);
    }

    /**
     * Empties the setting table.
     *
     * @return void
     */
    private function truncateSettings()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('membership_settings')->truncate();
        Schema::enableForeignKeyConstraints();

        Artisan::call('cache:clear');
    }
}
