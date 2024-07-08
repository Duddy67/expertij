<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMembershipSharings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
	$routeName = $request->route()->getName();

        $create = ['admin.memberships.sharings.index', 'admin.memberships.sharings.create', 'admin.memberships.sharings.store'];
        $update = ['admin.memberships.sharings.update', 'admin.memberships.sharings.edit'];
        $delete = ['admin.memberships.sharings.destroy', 'admin.memberships.sharings.massDestroy'];

	if (in_array($routeName, $create) && !auth()->user()->isAllowedTo('create-membership-sharings')) {
	    return redirect()->route('admin')->with('error', __('messages.generic.access_not_auth'));
	}

	if (in_array($routeName, $update) && !auth()->user()->isAllowedTo('update-membership-sharings')) {
	    return redirect()->route('admin.memberships.sharings.index')->with('error', __('messages.category.edit_not_auth'));
	}

	if (in_array($routeName, $delete) && !auth()->user()->isAllowedTo('delete-membership-sharings')) {
	    return redirect()->route('admin.memberships.sharings.index')->with('error', __('messages.category.delete_not_auth'));
	}

        return $next($request);
    }
}
