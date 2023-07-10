<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission = null, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);
        if ($permission && substr($permission, 0, 1) == "_") {
            if (!in_array($request->route()->parameter("type"), ["exchange", "onedrive", "sharepoint", "teams"])) {
                return response()->view('errors.404', [], 404);
            }
            $permission = $request->route()->parameter("type") . $permission;
        }
        try {
            if ($authGuard->guest()) {
                return redirect()->route("login");
            }
            if (!auth()->user()->bac_role) {
                return redirect()->route("login")->with('error', __("variables.messages.login_user_without_role"));
            }
            auth()->user()->permissions = auth()->user()->bac_role->permissions->pluck('name')->toArray();
            if (!is_null($permission)) {
                $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);
            }

            if (is_null($permission)) {
                $permission = $request->route()->getName();
                $permissions = array($permission);
            }
            $role = Role::findById($authGuard->user()->role_id);
            foreach ($permissions as $permission) {
                if ($role->hasPermissionTo($permission)) {
                    return $next($request);
                } else {
                    return redirect()->route("unauthorized", $permission);
                }
            }
        } catch (Exception $e) {
            Log::log("error", "error $e");
            return redirect()->route("login")->with('error', "No Permission to login");
        }
        Log::log("error", "error after catch");
        return redirect()->route("login")->with('error', "No Permission to login");
    }
}
