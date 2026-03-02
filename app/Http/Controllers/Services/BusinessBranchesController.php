<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\BusinessBranches;
use Illuminate\Http\Request;

class BusinessBranchesController extends Controller
{
    private $screenPrefix = 'business_branches';

    public function getBusinessBranches(Request $request){

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
            $mode = !empty($request->mode) ? $request->mode : null;

            $uuId = !empty($request->business_branch_id) ? $request->business_branch_id : 0;

            $get = BusinessBranches::select('business_branches.*', 'businesses.business')
                ->join('businesses', 'business_branches.business_id', '=', 'businesses.id')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('business_branches.uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->orWhere('business_branches.business_branch', 'like', '%'.$keyword.'%')
                                ->orWhere('businesses.business', 'like', '%'.$keyword.'%');
                        });
                    }
                    return $query;
                })
                ->orderBy('business_branch', 'ASC');

            if (!empty($mode) && $mode == 'for_select'){
                $out = $get->get();
            }else{
                $out = $get->paginate($itemsPerPage, ['*'], 'page', $currentPage);
            }
        }

        return response()->json($out);
    }

    public function getBusinessBranch(Request $request){
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

        if (empty($isInvalid )) {
            $uuId = !empty($request->business_branch_id) ? $request->business_branch_id : 0;

            $out = BusinessBranches::select('business_branches.*', 'businesses.uuid AS business_uuid', 'businesses.business')
                ->join('businesses', 'business_branches.business_id', '=', 'businesses.id')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('business_branches.uuid', $uuId);
                })
                ->first();
        }

        return response()->json($out);
    }

    public function setBusinessBranch(Request $request){
        $out = [];

        $validate = [
            'screen' => $this->screenPrefix,
            'allowed_user_roles' => [2],
            'allowed_permissions' => ['is_view' => 1, 'is_create' => 1, 'is_update' => 1],
        ];

        $check = $this->validateUserPermissions($validate);
        $isInvalid = $check['is_invalid'];
        $permissions = $check['permissions'];
        $out['permissions'] = $permissions;


        if (empty($isInvalid)) {
            $getId = !empty($request->business_branch_id) ? $request->business_branch_id : 0;

            if (!empty($getId)){
                $validated = $request->validate([
                    'business_id' => 'required',
                    'business_branch' => 'required|unique:business_branches,business_branch,'.$getId .',uuid',
                ]);

                $set = BusinessBranches::where('uuid', $getId)->first();
                $set->business_branch = $request->business_branch;
                $set->business_id = !empty($request->business_id) ? $request->business_id : 0;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Business Branch has been Updated!';

            }else{
                $validated = $request->validate([
                    'business_id' => 'required',
                    'business_branch' => 'required|unique:business_branches',
                ]);

                $set = new BusinessBranches();
                $set->business_branch = $request->business_branch;
                $set->business_id = !empty($request->business_id) ? $request->business_id : 0;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => $request->business_id, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = BusinessBranches::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Business Branch Added!';

            }
        }

        return response()->json($out);
    }

    public function setStatus(Request $request){
        $out = [];

        $validate = [
            'screen' => $this->screenPrefix,
            'allowed_user_roles' => [2],
            'allowed_permissions' => ['is_view' => 1, 'is_create' => 1, 'is_update' => 1],
        ];

        $check = $this->validateUserPermissions($validate);
        $isInvalid = $check['is_invalid'];
        $permissions = $check['permissions'];
        $out['permissions'] = $permissions;


        if (empty($isInvalid)) {
            $getId = !empty($request->id) ? $request->id : 0;

            if (!empty($getId)){

                $set = BusinessBranches::where('uuid', $getId)->first();
                if (!empty($set) && $set->status == 1){
                    $set->status = 0;
                }else{
                    $set->status = 1;
                }

                $set->save();

                $out['updated_status'] = $set->status;
                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Status has been updated!';

            }
        }

        return response()->json($out);
    }
}
