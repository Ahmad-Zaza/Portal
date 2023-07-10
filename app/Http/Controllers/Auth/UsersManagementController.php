<?php

namespace App\Http\Controllers\Auth;

use App\Engine\Veeam\ManagerVeeam;
use App\Http\Controllers\Controller;
use App\Models\BacPermission;
use App\Models\BacPermissionCategory;
use App\Models\BacRole;
use App\Models\BacRoleHasPermission;
use App\Models\BacUser;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UsersManagementController extends Controller
{
    private $_managerVeeam;

    //-------------------------------------------------------//
    public function __construct()
    {
        $this->_managerVeeam = new ManagerVeeam();
    }
    //-------------------------------------------------------//
    public function main()
    {
        $organization = auth()->user()->organization;
        $roles = BacRole::where("organization_id", $organization->id)->orWhere("organization_id", null)->get();
        return response()->view("users-management.main", compact('roles'));
    }
    //-------------------------------------------------------//
    public function getUsers()
    {
        $organization = auth()->user()->organization;
        return BacUser::where("organization_id", $organization->id)->with("role")->get();
    }
    //-------------------------------------------------------//
    public function saveUser(Request $request)
    {
        if (!$request->userId) {
            $user = new BacUser();
            if ($request->roleId) {
                $user->role_assigned_date = Carbon::now();
            }
        } else {
            $user = BacUser::find($request->userId);
            if ($request->roleId != $user->role_id) {
                $user->role_assigned_date = Carbon::now();
            }
        }
        $user->email = $request->email;
        $user->upn = $request->email;
        $user->role_id = $request->roleId;
        $user->status = "active";
        $user->organization_id = auth()->user()->organization_id;
        $user->save();
    }
    //-------------------------------------------------------//
    public function getRoles()
    {
        $organization = auth()->user()->organization;
        $data = BacRole::where("organization_id", $organization->id)->orWhere("organization_id", null)->get();
        foreach ($data as $record) {
            $record->users_count = BacUser::where("role_id", $record->id)->where("organization_id", $organization->id)->count();
        }
        return $data;
    }
    //-------------------------------------------------------//
    public function rolePage($id = "")
    {
        if($id == 1)
            return abort(404);
        $bac_role = null;
        $permission_categories = BacPermissionCategory::whereNull("parent_permission_category_id")->get();
        $assignedUsers = BacUser::where("role_id", $id)->where("organization_id", auth()->user()->organization->id)->get();
        foreach ($permission_categories as $category) {
            $category->distinct_permissions = BacPermission::where("permission_category_id", $category->id)->distinct()->select("display_name", "name", "id")->get();
            if (count($category->distinct_permissions) == 0) {
                $subIds = $category->subCategories->pluck("id")->toArray();
                $category->distinct_permissions = BacPermission::whereIn("permission_category_id", $subIds)->distinct()->get("display_name", "name", "id");
            }
        }
        if ($id) {
            $bac_role = BacRole::where("id", $id)->with("users")->first();
            $bac_role->permissions = BacRoleHasPermission::where("role_id", $bac_role->id)->pluck("permission_id")->toArray();
        }
        return response()->view("users-management.role", compact("permission_categories", "bac_role","assignedUsers"));
    }
    //-------------------------------------------------------//
    public function saveRole(Request $request)
    {
        try {
            $requestData = $request->all();
            if ($request->roleId) {
                $role = BacRole::where("id", $request->roleId)->first();
                BacRoleHasPermission::where("role_id", $role->id)->delete();
            } else {
                $role = new BacRole();
                $role->organization_id = auth()->user()->organization_id;
            }
            $role->name = $request->roleName;
            $role->description = $request->roleDescription;
            $role->guard_name = "web";
            $role->save();
            $permission = BacPermission::all();
            foreach ($permission as $permission) {
                if (optional($requestData)[$permission->name] == "on") {
                    $rolePermission = new BacRoleHasPermission();
                    $rolePermission->permission_id = $permission->id;
                    $rolePermission->role_id = $role->id;
                    $rolePermission->save();
                }
            }
            return response()->json([], 200);
        } catch (Exception $e) {
            Log::log("error", "Error While Saving Role $e");
            return response()->json(["message" => $e], 500);
        }
    }
    //-------------------------------------------------------//
    public function actionUser(Request $request)
    {
        if ($request->userId) {
            if ($request->action == "Delete") {
                BacUser::findOrFail($request->userId)->delete();
            }

            if ($request->action == "Disable") {
                BacUser::where("id", $request->userId)->update([
                    "status" => "inactive",
                ]);
            }

            if ($request->action == "Enable") {
                BacUser::where("id", $request->userId)->update([
                    "status" => "active",
                ]);
            }

        }
    }
    //-------------------------------------------------------//
}
