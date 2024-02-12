<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMembershipSettings
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

	if ($routeName == 'admin.memberships.settings.index' && !auth()->user()->isAllowedTo('update-membership-settings')) {
	    return redirect()->route('admin')->with('error', __('messages.generic.access_not_auth'));
	}

        return $next($request);
    }
}
