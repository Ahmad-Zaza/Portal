<?php

namespace App\Http\Controllers;

use App\Engine\Base\Manager;
use App\Http\Controllers\Controller;
use App\Models\BacPermission;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            Manager::generateVeeamToken();
        } catch (Exception $e) {
            Log::log("error","Error While Generating Veeam Server Token ".$e);
            return response()->view("errors.500",[],500);
        }
        return response()->view('home');
    }
    public function execPs(Request $request)
    {
        dd($request->all());
    }

    public function unauthorized($code)
    {
        $permission = BacPermission::where("name", $code)->with("permissionCategory")->first();
        return response()->view("errors.401", compact("permission"),401);
    }
}
