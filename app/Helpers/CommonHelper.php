<?php

namespace App\Helpers;


use App\Models\ApplicationSettings;
use App\Models\Businesses;
use App\Models\Categories;
use App\Models\Colors;
use App\Models\GenderCategories;
use App\Models\ProductRanges;
use App\Models\Sizes;
use App\Models\Tags;
use App\Models\User;
use App\Models\UserBusinesses;
use App\Models\UserScreens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommonHelper
{
    public function checkAccess($req = [])
    {

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


        if (!empty($userScreen)) {

            $isInvalid = 0;

            $isView = $userScreen->is_view;
            $isCreate = $userScreen->is_create;
            $isUpdate = $userScreen->is_update;
            $isDelete = $userScreen->is_delete;

            if (!empty($allowedUserRoles)) {
                if (!in_array($userRoleId, $allowedUserRoles)) {
                    $isInvalid++;
                }
            }

            if (!empty($allowedPermissions)) {
                if (isset($allowedPermissions['is_view'])) {
                    if ($allowedPermissions['is_view'] != $isView) {
                        $isInvalid++;
                    }
                }
                if (isset($allowedPermissions['is_create'])) {
                    if ($allowedPermissions['is_create'] != $isCreate) {
                        $isInvalid++;
                    }
                }
                if (isset($allowedPermissions['is_update'])) {
                    if ($allowedPermissions['is_update'] != $isUpdate) {
                        $isInvalid++;
                    }
                }
                if (isset($allowedPermissions['is_delete'])) {
                    if ($allowedPermissions['is_delete'] != $isDelete) {
                        $isInvalid++;
                    }
                }
            }

            if ($isInvalid == 0) {
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


        return (object)$out;
    }

    public function getBusiness($businessId = 1)
    {
        $out = Businesses::find($businessId);
        return $out;
    }

    public function getUserBusinesses($req = [])
    {

        $userId = !empty($req['user_id']) ? $req['user_id'] : 0;
        $businessId = !empty($req['business_id']) ? $req['business_id'] : 0;

        $out = UserBusinesses::select(
            'user_businesses.*',
            'businesses.prefix',
            'businesses.business',
            'businesses.image',
            'businesses.url',
            'designations.designation',
        )
            ->join('businesses', 'user_businesses.business_id', 'businesses.id')
            ->join('designations', 'user_businesses.designation_id', 'designations.id')
            ->where('user_businesses.user_id', $userId)
            ->where('user_businesses.business_id', $businessId)
            ->where('user_businesses.status', '1')
            ->get();

        return $out;
    }

    public function getApplicationSettings($req = [])
    {

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
        if (!empty($res)) {
            $businessId = !empty($res['business_id']) ? $res['business_id'] : 1;
            $screen = !empty($res['screen']) ? $res['screen'] : 'temp';
            $id = !empty($res['id']) ? $res['id'] : rand(0, 99999999);
            $refId = sha1($businessId . $screen . $id . time());
        } else {
            $refId = sha1(rand(0, 99999999) . rand(0, 99999999) . rand(0, 99999999));
        }

        return $refId;
    }

    public function generateSeoURL($string, $withoutTimestamp = 0, $wordLimit = 0)
    {
        $separator = '-';

        if ($wordLimit != 0) {
            $wordArr = explode(' ', $string);
            $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
        }

        $quoteSeparator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;'                 => '',
            '[^\w\d _-]'            => '',
            '\s+'                   => $separator,
            '(' . $quoteSeparator . ')+' => $separator
        );

        $string = strip_tags($string);
        foreach ($trans as $key => $val) {
            $string = preg_replace('#' . $key . '#iu', $val, $string);
        }

        $string = strtolower($string);

        if (!empty($withoutTimestamp)) {
            $slug = trim(trim($string, $separator));
        } else {
            $slug = trim(trim($string, $separator)) . '-' . time();
        }

        return $slug;
    }

    public function getCategories($req = [])
    {
        $businessId = !empty($req['business_id']) ? $req['business_id'] : 1;
        $id = !empty($req['id']) ? $req['id'] : 0;
        $uuId = !empty($req['category_id']) ? $req['category_id'] : 0;

        $out = Categories::select('categories.*')
            ->when(!empty($id), function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when(!empty($uuId), function ($query) use ($uuId) {
                return $query->where('uuid', $uuId);
            })
            ->when(!empty($businessId), function ($query) use ($businessId) {
                return $query->where('business_id', $businessId);
            })
            ->where('status', 1)
            ->orderBy('display_order', 'ASC')
            ->orderBy('category', 'ASC')
            ->get();

        return $out;
    }

    public function getGenderCategories($req = [])
    {
        $id = !empty($req['id']) ? $req['id'] : 0;
        $uuId = !empty($req['gender_category_id']) ? $req['gender_category_id'] : 0;

        $out = GenderCategories::select('gender_categories.*')
            ->when(!empty($id), function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when(!empty($uuId), function ($query) use ($uuId) {
                return $query->where('uuid', $uuId);
            })
            ->where('status', 1)
            ->orderBy('display_order', 'ASC')
            ->orderBy('gender_category', 'ASC')
            ->get();

        return $out;
    }

    public function getTags($req = [])
    {
        $id = !empty($req['id']) ? $req['id'] : 0;
        $uuId = !empty($req['tag_id']) ? $req['tag_id'] : 0;

        $out = Tags::select('tags.*')
            ->when(!empty($id), function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when(!empty($uuId), function ($query) use ($uuId) {
                return $query->where('uuid', $uuId);
            })
            ->where('status', 1)
            ->orderBy('tag', 'ASC')
            ->get();

        return $out;
    }

    public function getColors($req = [])
    {
        $id = !empty($req['id']) ? $req['id'] : 0;
        $uuId = !empty($req['color_id']) ? $req['color_id'] : 0;

        $out = Colors::select('colors.*')
            ->when(!empty($id), function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when(!empty($uuId), function ($query) use ($uuId) {
                return $query->where('uuid', $uuId);
            })
            ->where('status', 1)
            ->orderBy('color', 'ASC')
            ->get();

        return $out;
    }

    public function getSizes($req = [])
    {
        $id = !empty($req['id']) ? $req['id'] : 0;
        $uuId = !empty($req['size_id']) ? $req['size_id'] : 0;

        $out = Sizes::select('sizes.*')
            ->when(!empty($id), function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when(!empty($uuId), function ($query) use ($uuId) {
                return $query->where('uuid', $uuId);
            })
            ->where('status', 1)
            ->orderBy('size', 'ASC')
            ->get();

        return $out;
    }

    public function getProductRanges($req = [])
    {
        $id = !empty($req['id']) ? $req['id'] : 0;
        $uuId = !empty($req['product_range_id']) ? $req['product_range_id'] : 0;

        $out = ProductRanges::select('product_ranges.*')
            ->when(!empty($id), function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when(!empty($uuId), function ($query) use ($uuId) {
                return $query->where('uuid', $uuId);
            })
            ->where('status', 1)
            ->orderBy('product_range', 'ASC')
            ->get();

        return $out;
    }
}
