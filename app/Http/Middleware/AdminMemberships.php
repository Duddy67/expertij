<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMemberships
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

        $create = ['admin.memberships.index', 'admin.memberships.create', 'admin.memberships.store'];
        $update = ['admin.memberships.update', 'admin.memberships.edit'];
        $delete = ['admin.memberships.destroy', 'admin.memberships.massDestroy'];

        if (in_array($routeName, $create) && !auth()->user()->isAllowedTo('create-memberships')) {
            return redirect()->route('admin')->with('error', __('messages.generic.access_not_auth'));
        }

        if (in_array($routeName, $update) && !auth()->user()->isAllowedTo('update-memberships')) {
            return redirect()->route('admin.memberships.index')->with('error', __('messages.membership.edit_not_auth'));
        }

        if (in_array($routeName, $delete) && !auth()->user()->isAllowedTo('delete-memberships')) {
            return redirect()->route('admin.memberships.index')->with('error', __('messages.membership.delete_not_auth'));
        }

        return $next($request);
    }
}
