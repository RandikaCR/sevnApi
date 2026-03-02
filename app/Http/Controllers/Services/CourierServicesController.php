<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\CourierServices;
use Illuminate\Http\Request;

class CourierServicesController extends Controller
{
    private $screenPrefix = 'courier_services';

    public function getCourierServices(Request $request){

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

            $uuId = !empty($request->courier_service_id) ? $request->courier_service_id : 0;

            $get = CourierServices::select('courier_services.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->where('courier_service', 'like', '%'.$keyword.'%');
                        });
                    }
                    return $query;
                })
                ->orderBy('is_default', 'DESC')
                ->orderBy('courier_service', 'ASC');

            if (!empty($mode) && $mode == 'for_select'){
                $out = $get->get();
            }else{
                $out = $get->paginate($itemsPerPage, ['*'], 'page', $currentPage);
            }
        }

        return response()->json($out);
    }

    public function getCourierService(Request $request){
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
            $uuId = !empty($request->courier_service_id) ? $request->courier_service_id : 0;

            $out = CourierServices::select('courier_services.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                })
                ->first();
        }

        return response()->json($out);
    }

    public function setCourierService(Request $request){
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
            $getId = !empty($request->courier_service_id) ? $request->courier_service_id : 0;

            if (!empty($getId)){
                $validated = $request->validate([
                    'courier_service' => 'required|unique:courier_services,courier_service,'.$getId .',uuid',
                ]);

                $set = CourierServices::where('uuid', $getId)->first();
                $set->courier_service = $request->courier_service;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Courier Service has been Updated!';

            }else{
                $validated = $request->validate([
                    'courier_service' => 'required|unique:courier_services',
                ]);

                $set = new CourierServices();
                $set->courier_service = $request->courier_service;
                $set->is_default = 0;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => 0, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = CourierServices::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Courier Service Added!';

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

                $set = CourierServices::where('uuid', $getId)->first();
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

    public function setDefault(Request $request){
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

                $courierServices = CourierServices::where('is_default', 1)->get();
                foreach ($courierServices as $cs){
                    $c = CourierServices::find($cs->id);
                    $c->is_default = 0;
                    $c->save();
                }

                $set = CourierServices::where('uuid', $getId)->first();
                $set->is_default = 1;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Default courier service has been updated!';

            }
        }

        return response()->json($out);
    }

}
