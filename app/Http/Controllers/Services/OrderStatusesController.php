<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\OrderStatuses;
use Illuminate\Http\Request;

class OrderStatusesController extends Controller
{
    private $screenPrefix = 'order_statuses';

    public function getOrderStatuses(Request $request){

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

            $uuId = !empty($request->order_status_id) ? $request->order_status_id : 0;

            $get = OrderStatuses::select('order_statuses.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->where('status_frontend', 'like', '%'.$keyword.'%')
                                ->orWhere('status_backend', 'like', '%'.$keyword.'%');
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

    public function getOrderStatus(Request $request){
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
            $uuId = !empty($request->order_status_id) ? $request->order_status_id : 0;

            $out = OrderStatuses::select('order_statuses.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                })
                ->first();
        }

        return response()->json($out);
    }

    public function setOrderStatus(Request $request){
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
            $getId = !empty($request->order_status_id) ? $request->order_status_id : 0;

            if (!empty($getId)){
                $validated = $request->validate([
                    'status_frontend' => 'required|unique:order_statuses,status_frontend,'.$getId .',uuid',
                    'status_backend' => 'required|unique:order_statuses,status_backend,'.$getId .',uuid',
                    'label_frontend' => 'required',
                    'label_backend' => 'required',
                ]);

                $set = OrderStatuses::where('uuid', $getId)->first();
                $set->status_frontend = !empty($request->status_frontend) ? $request->status_frontend : null;
                $set->label_frontend = !empty($request->label_frontend) ? $request->label_frontend : null;
                $set->status_backend = !empty($request->status_backend) ? $request->status_backend : null;
                $set->label_backend = !empty($request->label_backend) ? $request->label_backend : null;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Order Status has been Updated!';

            }else{
                $validated = $request->validate([
                    'status_frontend' => 'required|unique:order_statuses',
                    'status_backend' => 'required|unique:order_statuses',
                    'label_frontend' => 'required',
                    'label_backend' => 'required',
                ]);

                $set = new OrderStatuses();
                $set->status_frontend = !empty($request->status_frontend) ? $request->status_frontend : null;
                $set->label_frontend = !empty($request->label_frontend) ? $request->label_frontend : null;
                $set->status_backend = !empty($request->status_backend) ? $request->status_backend : null;
                $set->label_backend = !empty($request->label_backend) ? $request->label_backend : null;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => 0, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = OrderStatuses::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Order Status Added!';

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

                $set = OrderStatuses::where('uuid', $getId)->first();
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
