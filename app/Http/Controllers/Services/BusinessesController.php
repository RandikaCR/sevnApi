<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\Businesses;
use Illuminate\Http\Request;

class BusinessesController extends Controller
{
    private $screenPrefix = 'businesses';

    public function getBusinesses(Request $request){

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

            $uuId = !empty($request->business_id) ? $request->business_id : 0;

            $get = Businesses::select('businesses.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->orWhere('business', 'like', '%'.$keyword.'%')
                                ->orWhere('prefix', 'like', '%'.$keyword.'%')
                            ->orWhere('url', 'like', '%'.$keyword.'%');
                        });
                    }
                    return $query;
                })
                ->orderBy('business', 'ASC');

            if (!empty($mode) && $mode == 'for_select'){
                $out = $get->get();
            }else{
                $out = $get->paginate($itemsPerPage, ['*'], 'page', $currentPage);
            }
        }

        return response()->json($out);
    }

    public function getBusiness(Request $request){
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
            $uuId = !empty($request->business_id) ? $request->business_id : 0;

            $out = Businesses::select('businesses.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                })
                ->with([
                    'business_branches' => function ($query) {
                        return $query->select('business_branches.*')->where('business_branches.status', 1);
                    }
                ])
                ->first();
        }

        return response()->json($out);
    }

    public function setBusiness(Request $request){
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
            $getId = !empty($request->business_id) ? $request->business_id : 0;

            if (!empty($getId)){
                $validated = $request->validate([
                    'business' => 'required|unique:businesses,business,'.$getId .',uuid',
                    'prefix' => 'required|unique:businesses,prefix,'.$getId .',uuid',
                ]);

                $set = Businesses::where('uuid', $getId)->first();
                $set->business = $request->business;
                $set->prefix = !empty($request->prefix) ? $request->prefix : null;
                $set->image = !empty($request->image) ? $request->image : null;
                $set->url = !empty($request->url) ? $request->url : null;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Business has been Updated!';

            }else{
                $validated = $request->validate([
                    'business' => 'required|unique:businesses',
                    'prefix' => 'required|unique:businesses',
                ]);

                $set = new Businesses();
                $set->business = $request->business;
                $set->prefix = !empty($request->prefix) ? $request->prefix : null;
                $set->image = !empty($request->image) ? $request->image : null;
                $set->url = !empty($request->url) ? $request->url : null;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => $set->id, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = Businesses::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Business Added!';

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

                $set = Businesses::where('uuid', $getId)->first();
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
