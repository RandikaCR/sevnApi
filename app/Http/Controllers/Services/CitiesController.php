<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\Cities;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    private $screenPrefix = 'cities';

    public function getCities(Request $request){

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

            $uuId = !empty($request->city_id) ? $request->city_id : 0;

            $get = Cities::select('cities.*', 'districts.district')
                ->join('districts', 'cities.district_id', 'districts.id')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('cities.uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->where('cities.city', 'like', '%'.$keyword.'%')
                                ->orWhere('cities.zip', 'like', '%'.$keyword.'%')
                                ->orWhere('districts.district', 'like', '%'.$keyword.'%');
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

    public function getCity(Request $request){
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
            $uuId = !empty($request->city_id) ? $request->city_id : 0;

            $out = Cities::select('cities.*', 'districts.district')
                ->join('districts', 'cities.district_id', 'districts.id')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('cities.uuid', $uuId);
                })
                ->first();
        }

        return response()->json($out);
    }

    public function setCity(Request $request){
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
            $getId = !empty($request->city_id) ? $request->city_id : 0;

            if (!empty($getId)){
                $validated = $request->validate([
                    'city' => 'required|unique:cities,city,'.$getId .',uuid',
                    'zip' => 'required|unique:cities,zip,'.$getId .',uuid',
                    'district_id' => 'required',
                ]);

                $set = Cities::where('uuid', $getId)->first();
                $set->district_id = $request->district_id;
                $set->city = $request->city;
                $set->zip = $request->zip;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'City has been Updated!';

            }else{
                $validated = $request->validate([
                    'city' => 'required|unique:cities',
                    'zip' => 'required|unique:cities',
                    'district_id' => 'required',
                ]);

                $set = new Cities();
                $set->district_id = $request->district_id;
                $set->city = $request->city;
                $set->zip = $request->zip;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => 0, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = Cities::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New City Added!';

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

                $set = Cities::where('uuid', $getId)->first();
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
