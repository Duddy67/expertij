<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cms\Setting;
use App\Models\Membership;


class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $page = Setting::getPage('profile');
        $user = Auth::user();

        $options = [];
        $membership = new Membership;
        $options['citizenship'] = $membership->getCitizenshipOptions();
        $options['civility'] = $membership->getCivilityOptions();

        return view('themes.'.$page['theme'].'.index', compact('page', 'user', 'options'));
    }
}
