<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\Countries;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    private $screenPrefix = 'countries';

    public function getCountries(Request $request){

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

            $uuId = !empty($request->country_id) ? $request->country_id : 0;

            $get = Countries::select('countries.*', 'country_regions.country_region')
                ->join('country_regions', 'countries.country_region_id', 'country_regions.id')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('countries.uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->orWhere('countries.country', 'like', '%'.$keyword.'%')
                                ->orWhere('country_regions.country_region', 'like', '%'.$keyword.'%');
                        });
                    }
                    return $query;
                })
                ->orderBy('countries.country', 'ASC');

            if (!empty($mode) && $mode == 'for_select'){
                $out = $get->get();
            }else{
                $out = $get->paginate($itemsPerPage, ['*'], 'page', $currentPage);
            }
        }

        return response()->json($out);
    }

    public function getCountry(Request $request){
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
            $uuId = !empty($request->country_id) ? $request->country_id : 0;

            $out = Countries::select('countries.*', 'country_regions.country_region')
                ->join('country_regions', 'countries.country_region_id', 'country_regions.id')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('countries.uuid', $uuId);
                })
                ->first();
        }

        return response()->json($out);
    }

    public function setCountry(Request $request){
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
            $getId = !empty($request->country_id) ? $request->country_id : 0;

            if (!empty($getId)){
                $validated = $request->validate([
                    'country_region_id' => 'required',
                    'country' => 'required|unique:countries,country,'.$getId .',uuid',
                    'iso_2' => 'required|unique:countries,iso_2,'.$getId .',uuid',
                    'iso_3' => 'required|unique:countries,iso_3,'.$getId .',uuid',
                ]);

                $set = Countries::where('uuid', $getId)->first();
                $set->country = $request->country;
                $set->country_region_id = !empty($request->country_region_id) ? $request->country_region_id : 0;
                $set->iso_2 = !empty($request->iso_2) ? $request->iso_2 : null;
                $set->iso_3 = !empty($request->iso_3) ? $request->iso_3 : null;
                $set->flag = !empty($request->flag) ? $request->flag : null;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Country has been Updated!';

            }else{
                $validated = $request->validate([
                    'country_region_id' => 'required',
                    'country' => 'required|unique:countries',
                    'iso_2' => 'required|unique:countries',
                    'iso_3' => 'required|unique:countries',
                ]);

                $set = new Countries();
                $set->country = $request->country;
                $set->country_region_id = !empty($request->country_region_id) ? $request->country_region_id : 0;
                $set->iso_2 = !empty($request->iso_2) ? $request->iso_2 : null;
                $set->iso_3 = !empty($request->iso_3) ? $request->iso_3 : null;
                $set->flag = !empty($request->flag) ? $request->flag : null;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => 0, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = Countries::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Country Added!';

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

                $set = Countries::where('uuid', $getId)->first();
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
