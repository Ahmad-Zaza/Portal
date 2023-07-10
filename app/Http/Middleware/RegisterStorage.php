<?php

namespace App\Http\Middleware;

use Closure;

class RegisterStorage
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
        if (session("registerTime")) {
            return redirect('finish');
        }
        $user = auth()->user();
        if($user && $user->organization->registration_step != 5 && $request->path() != "step".$user->organization->registration_step){
            return redirect('step'.auth()->user()->organization->registration_step);
        }
        if($user && $user->organization->registration_step == 5 && strpos($request->path(),"step") !== false){
            return redirect("home");
        }
        return $next($request);
    }
}
