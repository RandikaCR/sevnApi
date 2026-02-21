<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersHelper
{

    public function getUser($request = [])
    {

        $item = [];
        $check = [];
        $status = 'success';
        $message = 'available';

        $id = !empty($request['id']) ? $request['id'] : 0;
        $userId = !empty($request['user_id']) ? $request['user_id'] : 0;


        if (!empty($id) || !empty($userId)) {

            $item = User::select('users.*', 'user_roles.user_role', 'businesses.business AS default_business')
                ->join('user_roles', 'users.user_role_id', 'user_roles.id')
                ->leftJoin('businesses', 'users.default_business_id', 'businesses.id')
                ->when(!empty($id), function ($query) use ($id) {
                    return $query->where('users.id', $id);
                })
                ->when(!empty($userId), function ($query) use ($userId) {
                    return $query->where('users.uuid', $userId);
                })
                ->with([
                    'user_businesses' => function ($query) {
                        return $query->select(
                            'user_businesses.*',
                            'businesses.business',
                        )
                            ->join('businesses', 'user_businesses.business_id', 'businesses.id')
                            ->where('businesses.status', 1);
                    },
                    'user_business_branches' => function ($query) {
                        return $query->select(
                            'user_business_branches.*',
                            'business_branches.business_id',
                            'business_branches.business_branch',
                            'businesses.business',
                        )
                            ->join('business_branches', 'user_business_branches.business_branch_id', 'business_branches.id')
                            ->join('businesses', 'business_branches.business_id', 'businesses.id')
                            ->where('business_branches.status', 1)
                            ->where('businesses.status', 1);
                    },
                    'user_application_settings' => function ($query) {
                        return $query->select(
                            'user_application_settings.*',
                            'businesses.business',
                        )
                            ->join('businesses', 'user_application_settings.business_id', 'businesses.id')
                            ->where('user_application_settings.status', 1)
                            ->where('businesses.status', 1);
                    },
                    'user_contacts' => function ($query) {
                        return $query->select('user_contacts.*')
                            ->with([
                                'user_contact_bank_account_details' => function ($query) {
                                    return $query->select('user_contact_bank_account_details.*')->where('user_contact_bank_account_details.status', 1);
                                },
                            ])
                            ->where('user_contacts.status', 1);
                    },
                    'user_screens' => function ($query) {
                        return $query->select(
                            'user_screens.*',
                            'screens.screen',
                            'screens.screen_prefix',
                        )
                            ->join('screens', 'user_screens.screen_id', 'screens.id');
                    }
                ])
                ->first();
        }


        $out = [
            'item' => $item,
            'status' => $status,
            'message' => $message,
        ];

        return $out;
    }

    public function getUserByPublicKey($key)
    {
        $out = [];

        if (!empty($key)){
            $out = User::select('users.*', 'user_roles.user_role', 'businesses.business', 'businesses.url AS default_business_url')
                ->join('user_roles', 'users.user_role_id', 'user_roles.id')
                ->join('businesses', 'users.default_business_id', 'businesses.id')
                ->when(!empty($key), function ($query) use ($key) {
                    return $query->where('users.public_key', $key);
                })
                ->first();
        }

        return $out;
    }

}
