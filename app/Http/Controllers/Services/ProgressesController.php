<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\Progresses;
use Illuminate\Http\Request;

class ProgressesController extends Controller
{
    private $screenPrefix = 'progresses';

    public function getProgresses(Request $request){

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

            $uuId = !empty($request->progress_id) ? $request->progress_id : 0;

            $get = Progresses::select('progresses.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->where('progress_frontend', 'like', '%'.$keyword.'%')
                                ->orWhere('progress_backend', 'like', '%'.$keyword.'%');
                        });
                    }
                    return $query;
                })
                ->orderBy('id', 'ASC');

            if (!empty($mode) && $mode == 'for_select'){
                $out = $get->get();
            }else{
                $out = $get->paginate($itemsPerPage, ['*'], 'page', $currentPage);
            }
        }

        return response()->json($out);
    }

    public function getProgress(Request $request){
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
            $uuId = !empty($request->progress_id) ? $request->progress_id : 0;

            $out = Progresses::select('progresses.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                })
                ->first();
        }

        return response()->json($out);
    }

    public function setProgress(Request $request){
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
            $getId = !empty($request->progress_id) ? $request->progress_id : 0;

            if (!empty($getId)){
                $validated = $request->validate([
                    'progress_frontend' => 'required|unique:progresses,progress_frontend,'.$getId .',uuid',
                    'progress_backend' => 'required|unique:progresses,progress_backend,'.$getId .',uuid',
                    'label_frontend' => 'required',
                    'label_backend' => 'required',
                ]);

                $set = Progresses::where('uuid', $getId)->first();
                $set->progress_frontend = !empty($request->progress_frontend) ? $request->progress_frontend : null;
                $set->label_frontend = !empty($request->label_frontend) ? $request->label_frontend : null;
                $set->progress_backend = !empty($request->progress_backend) ? $request->progress_backend : null;
                $set->label_backend = !empty($request->label_backend) ? $request->label_backend : null;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Progress has been Updated!';

            }else{
                $validated = $request->validate([
                    'progress_frontend' => 'required|unique:progresses',
                    'progress_backend' => 'required|unique:progresses',
                    'label_frontend' => 'required',
                    'label_backend' => 'required',
                ]);

                $set = new Progresses();
                $set->progress_frontend = !empty($request->progress_frontend) ? $request->progress_frontend : null;
                $set->label_frontend = !empty($request->label_frontend) ? $request->label_frontend : null;
                $set->progress_backend = !empty($request->progress_backend) ? $request->progress_backend : null;
                $set->label_backend = !empty($request->label_backend) ? $request->label_backend : null;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => 0, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = Progresses::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Progress Added!';

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

                $set = Progresses::where('uuid', $getId)->first();
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
