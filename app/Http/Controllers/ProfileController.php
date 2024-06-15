<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cms\Setting;
use App\Models\Cms\Document;
use App\Models\Membership;
use App\Models\User\Citizenship;
use App\Http\Requests\Profile\UpdateRequest;
use Illuminate\Support\Facades\Hash;


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
     * Show the form for editing the current user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $page = Setting::getPage('profile');
        $user = Auth::user();
        $options = [];

        if ($user->membership()->exists()) {
            $options['citizenship'] = $user->membership->getCitizenshipOptions();
            $options['civility'] = $user->membership->getCivilityOptions();
        }

        return view('themes.'.$page['theme'].'.index', compact('page', 'user', 'options'));
    }

    /**
     * Update the current user profile in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $user = Auth::user();

        $user->last_name = $request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->name = $request->input('first_name').' '.$request->input('last_name');
        $user->email = $request->input('email');

        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($user->membership()->exists()) {
            $user->civility = $request->input('civility');
            $user->birth_name = $request->input('birth_name');
            $user->birth_date = $request->input('_birth_date');
            $user->birth_location = $request->input('birth_location');
            $user->citizenship_id = $request->input('citizenship');
            // address
            $user->address->street = $request->input('street');
            $user->address->additional_address = $request->input('additional_address');
            $user->address->postcode = $request->input('postcode');
            $user->address->city = $request->input('city');
            $user->address->phone = $request->input('phone');
            $user->address->save();
        }

        $user->save();

        $updates = [];

        if ($photo = $this->uploadPhoto($request)) {
            // Delete the previous photo if any.
            if ($user->photo) {
                $user->photo->delete();
            }

            $user->photo()->save($photo);
            // Update the photo.
            $user->photo = $photo;
            $updates['user-photo'] = url('/').$user->photo->getThumbnailUrl();
        }

        return response()->json(['success' => __('messages.profile.update_success'), 'updates' => $updates]);
    }

    /*
     * Creates a Document associated with the uploaded photo file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Cms\Document
     */
    private function uploadPhoto($request)
    {
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $document = new Document;
            $document->upload($request->file('photo'), 'photo');

            return $document;
        }

        return null;
    }
}
