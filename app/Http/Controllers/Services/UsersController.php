<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Helpers\DBHelper;
use App\Helpers\UsersHelper;
use App\Http\Controllers\Controller;
use App\Mail\accountVerify;
use App\Mail\testMail;
use App\Models\Designations;
use App\Models\User;
use App\Models\UserApplicationSettings;
use App\Models\UserBusinessBranches;
use App\Models\UserBusinesses;
use App\Models\UserRoles;
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
            'allowed_user_roles' => [1, 2],
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
            $userId = !empty($request->user_id) ? $request->user_id : 0;
            $publicKey = !empty($request->public_key) ? $request->public_key : 0;
            $userRoleId = !empty($request->user_role_id) ? $request->user_role_id : 0;
            $isDeletedOnly = !empty($request->is_deleted) ? $request->is_deleted : 0;
            $status = !empty($request->status) ? $request->status : 1;

            $out = User::select(
                'users.*',
                'user_roles.user_role',
                'dealers.dealer AS default_dealer'
            )
                ->join('user_roles', 'users.user_role_id', 'user_roles.id')
                ->leftJoin('dealers', 'users.switched_business_id', 'dealers.id')
                ->when(!empty($keyword), function ($query) use ($keyword) {
                    return $query->where(DB::raw(DBHelper::dbConcat('users', 'first_name','users', 'last_name')), 'like', '%' . $keyword . '%')
                        ->orWhere('users.public_key', 'like', '%' . $keyword . '%')
                        ->orWhere('users.email', 'like', '%' . $keyword . '%');
                })
                ->when(!empty($userId), function ($query) use ($userId) {
                    return $query->where('users.uuid', $userId);
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
                ->with([
                    'user_dealers' => function ($query) use ($request){
                        return $query->select(
                            'user_dealers.*',
                            'dealers.dealer',
                            'dealers.motordat_id',
                        )
                            ->join('dealers', 'user_dealers.business_id', 'dealers.id')
                            ->where('dealers.status', 1);
                    },
                    'user_screens' => function ($query) use ($request){
                        return $query->select(
                            'user_screens.*',
                            'screens.screen',
                            'screens.screen_prefix',
                        )
                            ->join('screens', 'user_screens.screen_id', 'screens.id');
                    }
                ])
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
            $getUuIdRes = [
                'business_id' => $request->registered_business_id,
                'screen' => 'user_businesses',
                'id' => 0,
            ];
            $getCommon = new CommonHelper();
            $uuId = $getCommon->generateUUId($getUuIdRes);

            $ub = new UserBusinesses();
            $ub->uuid = $uuId;
            $ub->user_id = $user->id;
            $ub->business_id = $request->registered_business_id;
            $ub->status = 1;
            $ub->save();

            // Set Default User Business Branch
            $getUuIdRes = [
                'business_id' => $request->registered_business_id,
                'screen' => 'user_business_branches',
                'id' => 0,
            ];
            $getCommon = new CommonHelper();
            $uuId = $getCommon->generateUUId($getUuIdRes);

            $ubb = new UserBusinessBranches();
            $ubb->uuid = $uuId;
            $ubb->user_id = $user->id;
            $ubb->business_branch_id = $request->registered_business_branch_id;
            $ubb->status = 1;
            $ubb->save();

            // Set Default User Application Settings
            $getUuIdRes = [
                'business_id' => $ub->id,
                'screen' => 'user_application_settings',
                'id' => 0,
            ];
            $getCommon = new CommonHelper();
            $uuId = $getCommon->generateUUId($getUuIdRes);

            $uas = new UserApplicationSettings();
            $uas->uuid = $uuId;
            $uas->user_id = $user->id;
            $uas->business_id = $businessId;
            $uas->theme_layout_mode = !empty($applicationSetting->theme_layout_mode) ? $applicationSetting->theme_layout_mode : 'light';
            $uas->theme_topbar = !empty($applicationSetting->theme_topbar) ? $applicationSetting->theme_topbar : 'dark';
            $uas->theme_sidebar = !empty($applicationSetting->theme_sidebar) ? $applicationSetting->theme_sidebar : 'dark';
            $uas->status = 1;
            $uas->save();

            // Update User Info
            $getUuIdRes = [
                'business_id' => $businessId,
                'screen' => $this->screenPrefix,
                'id' => $user->id,
            ];

            $getCommon = new CommonHelper();
            $uuId = $getCommon->generateUUId($getUuIdRes);
            $tUser = User::find($user->id);
            $tUser->uuid = $uuId;
            $tUser->default_business_id = $ub->id;
            $tUser->default_business_branch_id = $ubb->id;
            $tUser->save();
        }

        $getUser = User::find($user->id);

        if (!empty($isNewUser)){
            $mailData = [
                'email_subject' => 'Thank you for registering with SEVN. Please verify your account.',
                'url' => $this->accountVerifyUrlGenerator($getUser->default_business_id, $getUser->uuid),
            ];
            Mail::to($getUser->email)->send(new accountVerify($mailData));
        }



        return response()->json($getUser);
    }


    public function setUserProfile(Request $request){
        $out = [];

        $mode = $request->mode;
        $status = 'success';
        $messageTitle = '';
        $messageText = '';

        if (!empty($mode) && $mode == 'personal'){

            APIValidator::validate($request, [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
            ]);

            $user = User::where('uuid', $request->uuid)->first();

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

            $user = User::where('uuid', $request->uuid)->first();

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

                /*$dealer = (Object)[
                    'dealer' => null,
                    'motordat_id' => null,
                ];

                if(!empty($user->switched_business_id)){
                    $dealer = Dealers::find($user->switched_business_id);
                }

                $req = ['user_id' => $user->uuid];

                $dh = new DealersHelper();
                $dealers = $dh->getUserDealers($req);


                if (!empty($dealer->status) && $dealer->status == 1){
                    $out = [
                        'status' => 'success',
                        'message' => '',
                        'id' => $user->uuid,
                        'user_role_id' => $user->user_role_id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'token' => $user->public_key,
                        'switched_business_id' => $user->switched_business_id,
                        'switched_dealer' => $dealer->dealer,
                        'dealer_motordat_id' => $dealer->motordat_id,
                        'site_layout_mode' => $user->site_layout_mode,
                        'site_topbar' => $user->site_topbar,
                        'side_sidebar' => $user->side_sidebar,
                        'dealers' => $dealers,
                    ];
                }else{
                    $out = [
                        'status' => 'error',
                        'message' => 'Dealer is not Available. Please contact admin.',
                    ];
                }*/

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


    public function getUserDealers(Request $request){

        $out = [];

        $validate = [
            'screen' => $this->screenPrefix,
            'allowed_user_roles' => [1, 2],
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
            $dealerId = !empty($request->business_id) ? $request->business_id : 0;
            $status = !empty($request->status) ? $request->status : 1;


            /*$out = UserDealers::select(
                'user_dealers.*',
                'dealers.dealer',
            )
                ->join('dealers', 'user_dealers.business_id', 'dealers.id')
                ->join('users', 'user_dealers.user_id', 'users.id')
                ->where('users.uuid', $userId)
                ->when(!empty($dealerId), function ($query) use ($dealerId) {
                    return $query->where('dealers.uuid', $dealerId);
                })
                ->when(!empty($keyword), function ($query) use ($keyword) {
                    return $query->where('dealers.dealer', 'like', '%' . $keyword . '%');
                })
                ->orderBy('dealers.dealer', 'ASC')
                ->paginate($itemsPerPage, ['*'], 'page', $currentPage);*/
        }

        return response()->json($out);
    }


    public function setUserDealerStatus(Request $request){

        $out = [];

        $validate = [
            'screen' => 'user_dealers',
            'allowed_user_roles' => [1, 2],
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
