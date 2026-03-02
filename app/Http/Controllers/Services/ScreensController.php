<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\Screens;
use Illuminate\Http\Request;

class ScreensController extends Controller
{
    private $screenPrefix = 'screens';
    public function getScreens(Request $request){
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

            $uuId = !empty($request->screen_id) ? $request->screen_id : 0;

            $get = Screens::select('screens.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->orWhere('screen', 'like', '%'.$keyword.'%')
                                ->orWhere('screen_prefix', 'like', '%'.$keyword.'%');
                        });
                    }
                    return $query;
                })
                ->orderBy('screen', 'ASC');

            if (!empty($mode) && $mode == 'for_select'){
                $out = $get->get();
            }else{
                $out = $get->paginate($itemsPerPage, ['*'], 'page', $currentPage);
            }
        }



        return response()->json($out);
    }

    public function getScreen(Request $request){
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
            $uuId = !empty($request->screen_id) ? $request->screen_id : 0;

            $out = Screens::select('screens.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                })
                ->first();
        }

        return response()->json($out);
    }


    public function setScreen(Request $request){
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

        if (empty($isInvalid)){
            $getId = !empty($request->screen_id) ? $request->screen_id : 0;
            $businessId = !empty($request->business_id) ? $request->business_id : 1;
            $screen = !empty($request->screen) ? $request->screen : null;
            $screenPrefix = !empty($request->screen_prefix) ? $request->screen_prefix : null;

            if (!empty($getId)){
                $validated = $request->validate([
                    'screen' => 'required|unique:screens,screen,'.$getId .',uuid',
                    'screen_prefix' => 'required|unique:screens,screen_prefix,'.$getId .',uuid',
                ]);

                $set = Screens::where('uuid', $getId)->first();
                $set->screen = $screen;
                $set->screen_prefix = $screenPrefix;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Screen has been Updated!';

            }else{
                $validated = $request->validate([
                    'screen' => 'required|unique:screens',
                    'screen_prefix' => 'required|unique:screens',
                ]);

                $set = new Screens();
                $set->business_id = $businessId;
                $set->screen = $screen;
                $set->screen_prefix = $screenPrefix;
                $set->status = 1;
                $set->save();

                $update = Screens::find($set->id);


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => $businessId, 'screen' => $this->screenPrefix, 'id' => $set->id]);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Screen Added!';

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

                $set = Screens::where('uuid', $getId)->first();
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
