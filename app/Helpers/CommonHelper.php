<?php

namespace App\Helpers;


use App\Models\ApplicationSettings;
use App\Models\User;
use App\Models\UserScreens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommonHelper
{
    public function checkAccess($req = []){

        $out = [];

        $userId = $req['user_id'];
        $businessId = $req['business_id'];
        $businessBranchId = $req['business_branch_id'];
        $screen = $req['screen'];
        $allowedUserRoles = !empty($req['allowed_user_roles']) ? $req['allowed_user_roles'] : [];
        $allowedPermissions = !empty($req['allowed_permissions']) ? $req['allowed_permissions'] : [];


        $user = User::find($userId);
        $userRoleId = $user->user_role_id;

        $out = [
            'status' => 0,
            'message' => "You don't have enough permissions to access this service. Please contact admin.",
        ];


        // Check Screen Availability
        $userScreen = UserScreens::select('user_screens.*', 'screens.screen', 'screens.screen_prefix')
            ->join('screens', 'user_screens.screen_id', 'screens.id')
            ->join('businesses', 'user_screens.business_id', 'businesses.id')
            ->where('user_screens.status', '1')
            ->where('user_screens.user_id', $userId)
            ->where('user_screens.business_id', $businessId)
            ->where('screens.screen_prefix', $screen)
            ->first();


        if (!empty($userScreen)){

            $isInvalid = 0;

            $isView = $userScreen->is_view;
            $isCreate = $userScreen->is_create;
            $isUpdate = $userScreen->is_update;
            $isDelete = $userScreen->is_delete;

            if (!empty($allowedUserRoles)){
                if (!in_array($userRoleId, $allowedUserRoles)){
                    $isInvalid++;
                }
            }

            if (!empty($allowedPermissions)){
                if (isset($allowedPermissions['is_view'])){
                    if ($allowedPermissions['is_view'] != $isView){
                        $isInvalid++;
                    }
                }
                if (isset($allowedPermissions['is_create'])){
                    if ($allowedPermissions['is_create'] != $isCreate){
                        $isInvalid++;
                    }
                }
                if (isset($allowedPermissions['is_update'])){
                    if ($allowedPermissions['is_update'] != $isUpdate){
                        $isInvalid++;
                    }
                }
                if (isset($allowedPermissions['is_delete'])){
                    if ($allowedPermissions['is_delete'] != $isDelete){
                        $isInvalid++;
                    }
                }
            }

            if ($isInvalid == 0){
                $out = [
                    'status' => 1,
                    'message' => "",
                    'is_invalid' => $isInvalid,
                    'is_view' => $isView,
                    'is_create' => $isCreate,
                    'is_update' => $isUpdate,
                    'is_delete' => $isDelete,
                ];
            }

        }


        return (Object)$out;
    }

    public function getApplicationSettings($req = []){

        $out = [];

        $applicationSettingId = !empty($req['application_setting_id']) ? $req['application_setting_id'] : 1;
        $businessId = !empty($req['business_id']) ? $req['business_id'] : 0;

        $as = ApplicationSettings::select('application_settings.*')
            ->where('application_settings.id', $applicationSettingId)
            ->when(!empty($businessId), function ($query) use ($businessId) {
                return $query->where('application_settings.business_id', $businessId);
            })
            ->where('application_settings.status', '1')
            ->first();

        return $as;
    }

    public function generateUUId($res = [])
    {
        if (!empty($res)){
            $businessId = !empty($res['business_id']) ? $res['business_id'] : 1;
            $screen = !empty($res['screen']) ? $res['screen'] : 'temp';
            $id = !empty($res['id']) ? $res['id'] : rand(0,99999999);
            $refId = sha1($businessId . $screen . $id . time());
        }
        else{
            $refId = sha1(rand(0,99999999) . rand(0,99999999) . rand(0,99999999) );
        }

        return $refId;
    }
}
