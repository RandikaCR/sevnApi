<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommonHelper
{
    public function checkAccess($req = []){

        $out = [];

        /*$userId = $req['user_id'];
        $dealerId = $req['dealer_id'];
        $dealerMotordatId = $req['motordat_id'];
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
            ->join('dealers', 'user_screens.dealer_id', 'dealers.id')
            ->where('user_screens.status', '1')
            ->where('user_screens.user_id', $userId)
            ->where('dealers.motordat_id', $dealerMotordatId)
            ->where('screens.screen_prefix', $screen)
            ->first();


        if (!empty($userScreen)){

            $isView = $userScreen->is_view;
            $isCreate = $userScreen->is_create;
            $isUpdate = $userScreen->is_update;
            $isDelete = $userScreen->is_delete;

            $isInvalid = 0;

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
                    'is_view' => $isView,
                    'is_create' => $isCreate,
                    'is_update' => $isUpdate,
                    'is_delete' => $isDelete,
                ];
            }

        }*/


        return (Object)$out;
    }


    public function generateRefId($res = [])
    {
        if (!empty($res)){
            $dealerId = !empty($res['dealer_id']) ? $res['dealer_id'] : 1;
            $screen = !empty($res['screen']) ? $res['screen'] : 'temp';
            $id = !empty($res['id']) ? $res['id'] : rand(0,99999999);
            $refId = sha1($dealerId . $screen . $id . time());
        }
        else{
            $refId = sha1(rand(0,99999999) . rand(0,99999999) . rand(0,99999999) );
        }

        return $refId;
    }
}
