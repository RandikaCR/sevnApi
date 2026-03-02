<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Helpers\DBHelper;
use App\Helpers\UsersHelper;
use App\Http\Controllers\Controller;
use App\Mail\accountVerify;
use App\Mail\testMail;
use App\Models\BusinessBranches;
use App\Models\Businesses;
use App\Models\Designations;
use App\Models\User;
use App\Models\UserApplicationSettings;
use App\Models\UserBusinessBranches;
use App\Models\UserBusinesses;
use App\Models\UserRoles;
use App\Models\UserTitles;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use App\Validator\APIValidator;

class UsersController extends Controller
{
    private $screenPrefix = 'users';

    public function getUsers(Request $request){

        $out = [];

        $validate = [
            'screen' => $this->screenPrefix,
            'allowed_user_roles' => [2],
            'allowed_permissions' => ['is_view' => 1],
        ];

        $check = $this->validateUserPermissions($validate);
        $isInvalid = $check['is_invalid'];
        $permissions = $check['permissions'];
        $out['permissions'] = $permissions;


        if (empty($isInvalid)){
            $itemsPerPage = !empty($request->items_per_page) ? $request->items_per_page : $this->defaultItemsPerPage;
            $currentPage = !empty($request->current_page) ? $request->current_page : 0;

            $keyword = !empty($request->keyword) ? $request->keyword : '';
            $uuId = !empty($request->user_id) ? $request->user_id : 0;
            $publicKey = !empty($request->public_key) ? $request->public_key : 0;
            $userRoleId = !empty($request->user_role_id) ? $request->user_role_id : 0;
            $isDeletedOnly = !empty($request->is_deleted) ? $request->is_deleted : 0;
            $status = !empty($request->status) ? $request->status : 1;

            $out = User::select(
                'users.*',
                'user_roles.user_role',
                'businesses.business AS default_business'
            )
                ->join('user_roles', 'users.user_role_id', 'user_roles.id')
                ->leftJoin('businesses', 'users.default_business_id', 'businesses.id')
                ->when(!empty($keyword), function ($query) use ($keyword) {
                    return $query->where(DB::raw(DBHelper::dbConcat('users', 'first_name','users', 'last_name')), 'like', '%' . $keyword . '%')
                        ->orWhere('users.public_key', 'like', '%' . $keyword . '%')
                        ->orWhere('users.email', 'like', '%' . $keyword . '%');
                })
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('users.uuid', $uuId);
                })
                ->when(!empty($publicKey), function ($query) use ($publicKey) {
                    return $query->where('users.public_key', $publicKey);
                })
                ->when(!empty($userRoleId), function ($query) use ($userRoleId) {
                    return $query->where('users.user_role_id', $userRoleId);
                })
                ->when(!empty($isDeletedOnly), function ($query) use ($isDeletedOnly) {
                    return $query->where('users.is_deleted', 1);
                }, function ($query) use ($request){
                    return $query->where('users.is_deleted', 0);
                })
                ->where('users.status', $status)
                ->orderBy('id', 'ASC')
                ->paginate($itemsPerPage, ['*'], 'page', $currentPage);
        }

        return response()->json($out);
    }

    public function getUser(Request $request){

        $out = [];

        $validate = [
            'screen' => $this->screenPrefix,
            'allowed_user_roles' => [2],
            'allowed_permissions' => ['is_view' => 1],
        ];

        $check = $this->validateUserPermissions($validate);
        $isInvalid = $check['is_invalid'];
        $permissions = $check['permissions'];
        $out['permissions'] = $permissions;


        if (empty($isInvalid)){

            $req = [
                'screen' => $this->screenPrefix,
                'user_id' => $request->user_id,
            ];

            $user = new UsersHelper();
            $user = $user->getUser($req);
            if (!empty($user)){
                $out = $user;
            }
        }


        $out['permissions'] = $permissions;

        return response()->json($out);
    }

    public function setUser(Request $request){
        $out = [];

        APIValidator::validate($request, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'registered_business_id' => ['required'],
            'registered_business_branch_id' => ['required'],
        ]);

        $as = new CommonHelper();
        $applicationSetting = $as->getApplicationSettings();


        $isNewUser = 0;
        if (!empty($request->user_id)){
            $user = User::where('uuid', $request->user_id)->first();
        }else{

            $isNewUser = 1;

            $user = new User();
            $user->password = Hash::make($request->password);
            $user->is_deleted = 0;
            $user->status = 1;
            $user->email = $request->email;
            $user->registered_business_id = $request->registered_business_id;
            $user->registered_business_branch_id = $request->registered_business_branch_id;
            $user->registered_by = !empty($this->userId) ? $this->userId : 0;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->image  = !empty($request->image) ? $request->image : 'default-user.png';
        $user->user_role_id  = !empty($request->user_role_id) ? $request->user_role_id : 3;
        $user->save();

        //Set Public Key
        if (!empty($isNewUser) && $user){
            $keyUser = User::find($user->id);
            $key = $keyUser->createToken('public' .'-'. $user->id)->plainTextToken;
            $publicKey = explode('|', $key)[1];
            $keyUser->public_key = $publicKey;
            $keyUser->save();
        }

        if (empty($request->uuid) && empty($user->uuid)){
            $businessId = !empty($request->registered_business_id) ? $request->registered_business_id : 1;

            // Set Default User Business
            $ubRes = [
                'id' => 0, // uuid
                'user_id' => $user->id,
                'business_id' => $businessId,
            ];
            $ub = $this->setUserBusiness($ubRes);

            // Set Default User Business Branch
            $ubbRes = [
                'id' => 0, // uuid
                'user_id' => $user->id,
                'business_id' => $businessId,
                'business_branch_id' =>$request->registered_business_branch_id,
            ];
            $ubb = $this->setUserBusinessBranch($ubbRes);

            // Set Default User Application Settings
            $uasRes = [
                'id' => 0, // uuid
                'user_id' => $user->id,
                'business_id' => $businessId,
                'theme_layout_mode' => 'light',
                'theme_topbar' => 'dark',
                'theme_sidebar' => 'dark',
            ];
            $uas = $this->setUserApplicationSetting($uasRes);


            // Update User Info
            $getCommon = new CommonHelper();
            $uuId = $getCommon->generateUUId(['business_id' => $businessId, 'screen' => $this->screenPrefix, 'id' => $user->id]);
            $tUser = User::find($user->id);
            $tUser->uuid = $uuId;
            $tUser->default_business_id = $ub->id;
            $tUser->default_business_branch_id = $ubb->id;
            $tUser->save();
        }

        $getUser = User::find($user->id);

        if (!empty($isNewUser)){

            $b = new CommonHelper();
            $b = $b->getBusiness($getUser->default_business_id);

            $mailData = [
                'layout' => $b->prefix,
                'email_subject' => 'Thank you for registering with SEVN. Please verify your account.',
                'url' => $this->accountVerifyUrlGenerator($getUser->default_business_id, $getUser->uuid),
            ];
            Mail::to($getUser->email)->send(new accountVerify($mailData));
        }

        return response()->json($getUser);
    }

    public function setUserBusiness($res){

        $id = !empty($res['uuid']) ? $res['uuid'] : 0;
        $userId = !empty($res['user_id']) ? $res['user_id'] : 0;
        $businessId = !empty($res['business_id']) ? $res['business_id'] : 0;

        if (!empty($id)){
            $save = UserBusinesses::where('uuid', $id)->first();
        }else{

            $getCommon = new CommonHelper();
            $uuId = $getCommon->generateUUId(['business_id' => $businessId, 'screen' => 'user_businesses', 'id' => 0]);

            $save = new UserBusinesses();
            $save->uuid = $uuId;
            $save->status = 1;
        }

        $save->user_id = $userId;
        $save->business_id = $businessId;
        $save->save();

        return $save;
    }

    public function setUserBusinessBranch($res){

        $id = !empty($res['uuid']) ? $res['uuid'] : 0;
        $userId = !empty($res['user_id']) ? $res['user_id'] : 0;
        $businessId = !empty($res['business_id']) ? $res['business_id'] : 0;
        $businessBranchId = !empty($res['business_branch_id']) ? $res['business_branch_id'] : 0;

        if (!empty($id)){
            $save = UserBusinessBranches::where('uuid', $id)->first();
        }else{

            $getCommon = new CommonHelper();
            $uuId = $getCommon->generateUUId(['business_id' => $businessId, 'screen' => 'user_business_branches', 'id' => 0]);

            $save = new UserBusinessBranches();
            $save->uuid = $uuId;
            $save->status = 1;
        }

        $save->user_id = $userId;
        $save->business_branch_id = $businessBranchId;
        $save->save();

        return $save;
    }

    public function setUserApplicationSetting($res){

        $id = !empty($res['uuid']) ? $res['uuid'] : 0;
        $userId = !empty($res['user_id']) ? $res['user_id'] : 0;
        $businessId = !empty($res['business_id']) ? $res['business_id'] : 0;

        if (!empty($id)){
            $save = UserApplicationSettings::where('uuid', $id)->first();
        }else {


            $getCommon = new CommonHelper();
            $uuId = $getCommon->generateUUId(['business_id' => $businessId, 'screen' => 'user_application_settings', 'id' => 0]);

            $save = new UserApplicationSettings();
            $save->uuid = $uuId;
            $save->user_id = $userId;
            $save->business_id = $businessId;
            $save->status = 1;
        }

        if (!empty($res['theme_layout_mode'])){
            $save->theme_layout_mode = $res['theme_layout_mode'];
        }
        if (!empty($res['theme_topbar'])){
            $save->theme_topbar = $res['theme_topbar'];
        }
        if (!empty($res['theme_sidebar'])){
            $save->theme_sidebar = $res['theme_sidebar'];
        }

        $save->save();

        return $save;
    }


    public function setUserProfile(Request $request){
        $out = [];

        $mode = $request->mode;
        $status = 'success';
        $messageTitle = '';
        $messageText = '';

        if (!empty($mode) && $mode == 'personal'){

            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
            ]);

            $user = User::where('uuid', $request->user_id)->first();

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->save();

            $status = 'success';
            $messageTitle = 'Success!';
            $messageText = 'User info has been Updated!';
        }
        elseif (!empty($mode) && $mode == 'settings'){

            $user = User::where('uuid', $request->uuid)->first();

            $user->site_layout_mode = !empty($request->site_layout_mode) ? $request->site_layout_mode : 'light';
            $user->site_topbar = !empty($request->site_topbar) ? $request->site_topbar : 'dark';
            $user->side_sidebar = !empty($request->side_sidebar) ? $request->side_sidebar : 'dark';
            $user->save();

            $status = 'success';
            $messageTitle = 'Success!';
            $messageText = 'User settings has been Updated!';
        }
        elseif (!empty($mode) && $mode == 'security'){

            APIValidator::validate($request, [
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);


            $user = User::where('uuid', $request->user_id)->first();

            if (Hash::check($request->password_current, $user->password)) {

                $user->password = Hash::make($request->password);
                $user->save();

                $status = 'success';
                $messageTitle = 'Success!';
                $messageText = 'Password has been Updated!';
            }else{
                $status = 'error';
                $messageTitle = 'Error!';
                $messageText = 'Incorrect Current password!';
            }




        }



        /*$isNewUser = 0;
        if (!empty($request->user_id)){
            $user = User::where('uuid', $request->user_id)->first();
        }else{

            $isNewUser = 1;

            $user = new User();
            $user->password = Hash::make($request->password);
            $user->is_deleted = 0;
            $user->status = 1;
            $user->email = $request->email;
            $user->site_layout_mode = 'light';
            $user->site_topbar = 'dark';
            $user->side_sidebar = 'dark';
        }*/



        $out = [
            'status' => $status,
            'message_title' => $messageTitle,
            'message_text' => $messageText,
        ];

        return response()->json($out);
    }

    public function deleteUser(Request $res)
    {
        APIValidator::validate($res, [
            'id' => 'required',
            'deleted_by' => 'required',
        ]);

        $data = User::find($res['id']);
        $data->is_deleted = 1;
        $data->deleted_by = $res['deleted_by'];
        $data->deleted_at = date('Y-m-d H:i:s', time());
        $data->save();

        return response()->json([
            "success" => true,
            "message" => "User deleted successfully.",
            "data" => $data
        ]);
    }

    public function activateUser(Request $res)
    {
        APIValidator::validate($res, [
            'id' => 'required',
        ]);

        $data = User::find($res['id']);
        $data->is_deleted = 0;
        $data->deleted_by = null;
        $data->deleted_at = null;
        $data->save();

        return response()->json([
            "success" => true,
            "message" => "User activated successfully.",
            "data" => $data
        ]);
    }

    public function loginUser(Request $res)
    {
        if(Auth::attempt(['email' => $res->email, 'password' => $res->password])){
            $user = Auth::user();
            //$success['token'] =  $user->createToken($dealer->dealer .'-'. $user->id)->plainTextToken;

            if ($user->status != 1){
                $out = [
                    'status' => 'error',
                    'message' => 'User Account has been disabled. Please contact admin.',
                ];
            }
            elseif ($user->is_deleted == 1){
                $out = [
                    'status' => 'error',
                    'message' => 'User Account has been deleted. Please contact admin.',
                ];
            }
            else{

                $business = (Object)[
                    'branch' => null,
                ];

                $branch = (Object)[
                    'business' => null,
                ];

                $designation = null;

                if(!empty($user->default_business_id)){
                    $business = Businesses::find($user->default_business_id);
                    $des = Designations::select('designations.*')
                    ->join('user_businesses', 'user_businesses.designation_id', '=', 'designations.id')
                    ->where('user_businesses.id', $user->default_business_id)
                    ->groupBy('user_businesses.id')
                    ->first();
                    if (!empty($des)){
                        $designation = $des->designation;
                    }
                }
                if(!empty($user->default_business_branch_id)){
                    $branch = BusinessBranches::find($user->default_business_branch_id);
                }

                $as = UserApplicationSettings::where('user_id', $user->id)->where('business_id', $user->default_business_id)->first();

                $req = ['user_id' => $user->id, 'business_id' => $user->default_business_id];
                $dh = new CommonHelper();
                $businesses = $dh->getUserBusinesses($req);

                $out = [
                    'status' => 'success',
                    'message' => '',
                    'id' => $user->uuid,
                    'user_role_id' => $user->user_role_id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'designation' => $designation,
                    'image' => $user->image,
                    'token' => $user->public_key,
                    'default_business_id' => $user->default_business_id,
                    'default_business' => $business->business,
                    'default_business_branch_id' => $user->default_business_branch_id,
                    'default_business_branch' => $branch->business_branch,
                    'businesses' => $businesses,
                    'application_settings' => $as,
                ];

            }
        }
        else{

            $out = [
                'status' => 'error',
                'message' => 'Invalid Email or Password!',
            ];
        }

        return response()->json($out);
    }

    public function logoutUser(Request $res)
    {
        /*if (isset(Auth::user()->tokens())){
            Auth::user()->tokens()->delete();
        }*/

        return response()->json([
            "success" => true,
            "message" => "User logged out successfully.",
        ]);
    }

    public function verifyUserAccountByEmail(Request $request)
    {
        $status = 'error';
        $message = '';

        $userId = $request->user_id;
        $expires = $request->expires;

        $user = User::find($userId);
        if (!empty($user)){
            if (empty($user->created_at)){
                if ( $expires > time() ) {
                    $user->email_verified_at = $this->dbInsertTime();
                    $user->save();
                    $status = 'success';
                    $message = 'Account has been activated successfully.';
                }else{
                    $message = 'Account activation link has expired.';
                }
            }else{
                $message = 'Account already has been activated.';
            }
        }


        return response()->json([
            "status" => $status,
            "message" => $message,
        ]);
    }


    public function getUserBusinesses(Request $request){

        $out = [];

        $validate = [
            'screen' => $this->screenPrefix,
            'allowed_user_roles' => [2],
            'allowed_permissions' => ['is_view' => 1],
        ];

        $check = $this->validateUserPermissions($validate);
        $isInvalid = $check['is_invalid'];
        $permissions = $check['permissions'];
        $out['permissions'] = $permissions;


        if (empty($isInvalid)){
            $itemsPerPage = !empty($request->items_per_page) ? $request->items_per_page : $this->defaultItemsPerPage;
            $currentPage = !empty($request->current_page) ? $request->current_page : 0;

            $keyword = !empty($request->keyword) ? $request->keyword : '';
            $userId = !empty($request->uuid) ? $request->uuid : 0;
            $businessId = !empty($request->business_id) ? $request->business_id : 0;
            $status = !empty($request->status) ? $request->status : 1;


            $out = UserBusinesses::select(
                'user_businesses.*',
                'businesses.uuid as business_uuid',
                'businesses.business',
                'businesses.image',
                'designations.designation',
            )
                ->join('businesses', 'user_businesses.business_id', 'businesses.id')
                ->join('users', 'user_businesses.user_id', 'users.id')
                ->leftJoin('designations', 'user_businesses.designation_id', 'designations.id')
                ->where('users.uuid', $userId)
                ->when(!empty($businessId), function ($query) use ($businessId) {
                    return $query->where('businesses.uuid', $businessId);
                })
                ->when(!empty($keyword), function ($query) use ($keyword) {
                    return $query->where('businesses.business', 'like', '%' . $keyword . '%');
                })
                ->with([
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
                ])
                ->orderBy('businesses.business', 'ASC')
                ->groupBy('user_businesses.business_id')
                ->paginate($itemsPerPage, ['*'], 'page', $currentPage);
        }

        return response()->json($out);
    }

    public function getUserBusinessBranches(Request $request){

        $out = [];

        $validate = [
            'screen' => $this->screenPrefix,
            'allowed_user_roles' => [2],
            'allowed_permissions' => ['is_view' => 1],
        ];

        $check = $this->validateUserPermissions($validate);
        $isInvalid = $check['is_invalid'];
        $permissions = $check['permissions'];
        $out['permissions'] = $permissions;


        if (empty($isInvalid)){
            $itemsPerPage = !empty($request->items_per_page) ? $request->items_per_page : $this->defaultItemsPerPage;
            $currentPage = !empty($request->current_page) ? $request->current_page : 0;

            $keyword = !empty($request->keyword) ? $request->keyword : '';
            $userId = !empty($request->uuid) ? $request->uuid : 0;
            $businessId = !empty($request->business_id) ? $request->business_id : 0;
            $status = !empty($request->status) ? $request->status : 1;


            $out = UserBusinessBranches::select(
                'user_business_branches.*',
                'business_branches.business_branch',
                'businesses.business',
                'businesses.image',
            )
                ->join('business_branches', 'user_business_branches.business_branch_id', 'business_branches.id')
                ->join('businesses', 'business_branches.business_id', 'businesses.id')
                ->join('users', 'user_business_branches.user_id', 'users.id')
                ->where('users.uuid', $userId)
                ->when(!empty($businessId), function ($query) use ($businessId) {
                    return $query->where('businesses.uuid', $businessId);
                })
                ->when(!empty($keyword), function ($query) use ($keyword) {
                    return $query->where('businesses.business', 'like', '%' . $keyword . '%')
                        ->orWhere('business_branches.business_branch', 'like', '%' . $keyword . '%');
                })
                ->orderBy('business_branches.business_branch', 'ASC')
                ->groupBy('user_business_branches.business_branch_id')
                ->paginate($itemsPerPage, ['*'], 'page', $currentPage);
        }

        return response()->json($out);
    }


    public function setUserDealerStatus(Request $request){

        $out = [];

        $validate = [
            'screen' => 'user_dealers',
            'allowed_user_roles' => [2],
            'allowed_permissions' => ['is_view' => 1, 'is_create' => 1, 'is_update' => 1],
        ];

        /*$userDealer = UserDealers::where('uuid', $request->user_business_id)->first();
        $gs = $this->commonSwitchStatus($userDealer->status);
        $out['text'] = $gs['text'];
        $out['class'] = $gs['class'];
        $out['status'] = 'error';
        $out['message_title'] = 'Error!';
        $out['message_text'] = 'Something Went Wrong!';*/



        $check = $this->validateUserPermissions($validate);
        $isInvalid = $check['is_invalid'];
        $permissions = $check['permissions'];
        $out['permissions'] = $permissions;

        if (empty($isInvalid)){

            $status = 1;
            if (!empty($userDealer->status)){
                $status = 0;
            }

            $userDealer->status = $status;
            $userDealer->save();

            $gs = $this->commonSwitchStatus($status);
            $out['text'] = $gs['text'];
            $out['class'] = $gs['class'];
            $out['status'] = 'success';
            $out['message_title'] = 'Success!';
            $out['message_text'] = '';
        }



        return response()->json($out);
    }

    private function accountVerifyUrlGenerator($businessId, $uuid){
        $timestamp = strtotime(date('Y-m-d H:i:s', strtotime('+1 hour')));
        $url = businessUrlWithPath('user/email/verify/' . $uuid . '/'.$timestamp.'/' . $uuid . $timestamp . $uuid, $businessId);
        return $url;
    }
}
