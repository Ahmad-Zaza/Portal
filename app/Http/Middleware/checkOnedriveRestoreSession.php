<?php

namespace App\Http\Middleware;

use App\Engine\Veeam\ManagerVeeam;
use Closure;
use Illuminate\Support\Facades\Log;

class CheckOnedriveRestoreSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session("restoreOnedriveSessionId") && !$request->hasHeader("fromHistory")) {
            $_managerVeeam = new ManagerVeeam();
            $sessionInfo = $_managerVeeam->getRestoreSession(session('restoreOnedriveSessionId'))['data'];
            if ($sessionInfo->state != "Working") {
                session()->forget('restoreOnedriveSessionId');
                return response()->json(['message' => __('variables.errors.restore_session_expired')], 402);
            }
        }
        return $next($request);
    }
}
