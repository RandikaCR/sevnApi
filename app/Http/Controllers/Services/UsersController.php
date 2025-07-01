<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Helpers\DBHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Validator\APIValidator;

class UsersController extends Controller
{
    private $screen = 'users';

    public function getUsers(Request $request){

        $out = [];

        $validate = [
            'screen' => $this->screen,
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
                ->leftJoin('dealers', 'users.switched_dealer_id', 'dealers.id')
                ->when(!empty($keyword), function ($query) use ($keyword) {
                    return $query->where(DB::raw(DBHelper::dbConcat('users', 'first_name','users', 'last_name')), 'like', '%' . $keyword . '%')
                        ->orWhere('users.public_key', 'like', '%' . $keyword . '%')
                        ->orWhere('users.email', 'like', '%' . $keyword . '%');
                })
                ->when(!empty($userId), function ($query) use ($userId) {
                    return $query->where('users.ref_id', $userId);
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
                            ->join('dealers', 'user_dealers.dealer_id', 'dealers.id')
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
            'screen' => $this->screen,
            'allowed_user_roles' => [1, 2],
            'allowed_permissions' => ['is_view' => 1],
        ];

        $check = $this->validateUserPermissions($validate);
        $isInvalid = $check['is_invalid'];
        $permissions = $check['permissions'];
        $out['permissions'] = $permissions;


        if (empty($isInvalid)){
            $request->screen = $this->screen;
            $request->user_id = $this->userId;

            $req = [
                'screen' => $this->screen,
                'user_id' => $this->userId,
                'ref_id' => $request->ref_id,
                'dealer_id' => $request->dealer_id,
            ];

            /*$user = new UsersHelper();
            $user = $user->getUser($req);
            if (!empty($user)){
                $out = $user;
            }*/
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
        ]);

        $isNewUser = 0;
        if (!empty($request->user_id)){
            $user = User::where('ref_id', $request->user_id)->first();
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
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
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

        if (empty($request->ref_id) && empty($user->ref_id)){
            $dealerId = !empty($request->dealer_id) ? $request->dealer_id : 1;
            $getCommon = new CommonHelper();
            $refId = $getCommon->generateRefId($dealerId, $this->screen, $user->id);
            $tuser = User::find($user->id);
            $tuser->ref_id = $refId;
            $tuser->save();
        }

        $getUser = User::find($user->id);

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

            $user = User::where('ref_id', $request->ref_id)->first();

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->save();

            $status = 'success';
            $messageTitle = 'Success!';
            $messageText = 'User info has been Updated!';
        }
        elseif (!empty($mode) && $mode == 'settings'){

            $user = User::where('ref_id', $request->ref_id)->first();

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

            $user = User::where('ref_id', $request->ref_id)->first();

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
            $user = User::where('ref_id', $request->user_id)->first();
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

                if(!empty($user->switched_dealer_id)){
                    $dealer = Dealers::find($user->switched_dealer_id);
                }

                $req = ['user_id' => $user->ref_id];

                $dh = new DealersHelper();
                $dealers = $dh->getUserDealers($req);


                if (!empty($dealer->status) && $dealer->status == 1){
                    $out = [
                        'status' => 'success',
                        'message' => '',
                        'id' => $user->ref_id,
                        'user_role_id' => $user->user_role_id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'token' => $user->public_key,
                        'switched_dealer_id' => $user->switched_dealer_id,
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


    public function getUserDealers(Request $request){

        $out = [];

        $validate = [
            'screen' => $this->screen,
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
            $userId = !empty($request->ref_id) ? $request->ref_id : 0;
            $dealerId = !empty($request->dealer_id) ? $request->dealer_id : 0;
            $status = !empty($request->status) ? $request->status : 1;


            /*$out = UserDealers::select(
                'user_dealers.*',
                'dealers.dealer',
            )
                ->join('dealers', 'user_dealers.dealer_id', 'dealers.id')
                ->join('users', 'user_dealers.user_id', 'users.id')
                ->where('users.ref_id', $userId)
                ->when(!empty($dealerId), function ($query) use ($dealerId) {
                    return $query->where('dealers.ref_id', $dealerId);
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

        /*$userDealer = UserDealers::where('ref_id', $request->user_dealer_id)->first();
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


}
