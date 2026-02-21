<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserRolesController extends Controller
{
    private $screenPrefix = 'user_roles';

    public function getUserRoles(Request $request){

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

            $dealerId = !empty($request->dealer_id) ? $request->dealer_id : 0;

            $out = Dealers::select('dealers.*')
                ->when(!empty($dealerId), function ($query) use ($dealerId) {
                    return $query->where('ref_id', $dealerId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->orWhere('dealer', 'like', '%'.$keyword.'%')
                                ->orWhere('motordat_id', 'like', '%'.$keyword.'%');
                        });
                    }
                    return $query;
                })
                ->orderBy('dealer', 'ASC')
                ->paginate($itemsPerPage, ['*'], 'page', $currentPage);
        }


        return response()->json($out);
    }

    public function getUserRole(Request $request){
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

        if (empty($isInvalid )) {
            $dealerId = !empty($request->dealer_id) ? $request->dealer_id : 0;

            $out = Dealers::select('dealers.*')
                ->when(!empty($dealerId), function ($query) use ($dealerId) {
                    return $query->where('ref_id', $dealerId);
                })
                ->first();
        }

        return response()->json($out);
    }

    public function setUserRole(Request $request){
        $out = [];

        $validate = [
            'screen' => $this->screen,
            'allowed_user_roles' => [1, 2],
            'allowed_permissions' => ['is_view' => 1, 'is_create' => 1, 'is_update' => 1],
        ];

        $check = $this->validateUserPermissions($validate);
        $isInvalid = $check['is_invalid'];
        $permissions = $check['permissions'];
        $out['permissions'] = $permissions;


        if (empty($isInvalid)) {
            $getId = !empty($request->dealer_id) ? $request->dealer_id : 0;
            $dealer = !empty($request->dealer) ? $request->dealer : null;
            $motordatId = !empty($request->motordat_id) ? $request->motordat_id : null;

            if (!empty($getId)){
                $validated = $request->validate([
                    'dealer' => 'required|unique:dealers,dealer,'.$getId .',ref_id',
                    'motordat_id' => 'required|unique:dealers,motordat_id,'.$getId .',ref_id',
                ]);

                $set = Dealers::where('ref_id', $getId)->first();
                $set->dealer = $dealer;
                $set->motordat_id = $motordatId;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Dealer has been Updated!';

            }else{
                $validated = $request->validate([
                    'dealer' => 'required|unique:dealers',
                    'motordat_id' => 'required|unique:dealers',
                ]);

                $set = new Dealers();
                $set->dealer = $dealer;
                $set->motordat_id = $motordatId;
                $set->save();

                $update = Dealers::find($set->id);

                $getCommon = new CommonHelper();
                $refId = $getCommon->generateRefId($set->id, $this->screen, $set->id);
                $update->ref_id = $refId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Dealer Added!';

            }
        }

        return response()->json($out);
    }
}
