<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\UsersHelper;
use Illuminate\Support\Facades\Http;


abstract class Controller
{

    public $userId = 0;
    public $userRoleId = 0;
    public $defaultBusinessId = 0;
    public $defaultBusinessBranchId = 0;

    public $isSuperAdmin = 0;
    public $isAdmin = 0;
    public $isModerator = 0;

    public $defaultItemsPerPage = 20;

    public function __construct(Request $request){
        if(!empty($request->bearerToken())){
            $token = $request->bearerToken();

            $user = new UsersHelper();
            $user = $user->getUserByPublicKey($token);

            $this->userId = $user->id;
            $this->defaultBusinessId = $user->default_business_id;
            $this->defaultBusinessBranchId = $user->default_business_branch_id;

            if(!empty($user->user_role_id)){
                if($user->user_role_id == 1){
                    $this->isSuperAdmin = 1;
                }
                if($user->user_role_id == 2){
                    $this->isAdmin = 1;
                }
                if($user->user_role_id == 3){
                    $this->isModerator = 1;
                }
            }
        }
    }


    public function dbInsertTime(){
        return date('Y-m-d H:i:s', time());
    }


    public function validateUserPermissions($req){

        $permissions = [];

        $isUserRestricted = 1;
        if (!empty($this->isSuperAdmin)){
            $isUserRestricted = 0;
        }

        $allowedUserRoles = [1];
        $allowedUserRoles = !empty($req['allowed_user_roles']) ? $allowedUserRoles[] = $req['allowed_user_roles'] : $allowedUserRoles;

        $request = [
            'screen' => $req['screen'],
            'user_id' => !empty($req['user_id']) ? $req['user_id'] : $this->userId,
            'business_id' => !empty($req['business_id']) ? $req['business_id'] : $this->defaultBusinessId,
            'business_branch_id' => !empty($req['business_branch_id']) ? $req['business_branch_id'] : $this->defaultBusinessBranchId,
            'allowed_user_roles' => $allowedUserRoles,
            'allowed_permissions' => !empty($req['allowed_permissions']) ? $req['allowed_permissions'] : [],
        ];


        $isInvalid = 0;
        if (!empty($isUserRestricted)){
            $check = New CommonHelper();
            $permissions = $check->checkAccess($request);

            if (!empty($permissions->status)){
                if (empty($permissions->is_view)) {
                    $isInvalid++;
                    $status = 'error';
                    $message = 'Not Allowed!';
                }
            }else{
                $isInvalid++;
                $status = 'error';
                $message = $permissions->message;
            }
        }else{
            $permissions = [
                'status' => 'success',
                'message' => '',
            ];
        }


        $out['is_invalid'] = $isInvalid;
        $out['permissions'] = $permissions;

        return $out;
    }


    public function priceWithCurrency($price){
        $price = str_replace(',', '', $price);
        $price = (float) $price;
        return $this->defaultCurrency() . number_format($price, 2);
    }

    public function priceWithoutCurrency($price){
        $price = str_replace(',', '', $price);
        $price = (float) $price;
        return number_format($price, 2);
    }

    public function dbValueConvertToNumber($price){
        $price = str_replace(',', '', $price);
        return $price;
    }

    public function defaultCurrency(){
        return '£ ';
    }

    public function commonSwitchStatus($status){

        $out = [
            'text' => 'Inactive',
            'class' => 'bg-warning',
        ];

        if ($status == 1){
            $out = [
                'text' => 'Active',
                'class' => 'bg-success',
            ];
        }

        return $out;
    }
}
