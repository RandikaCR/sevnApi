<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\GenderCategories;
use Illuminate\Http\Request;

class GenderCategoriesController extends Controller
{
    private $screenPrefix = 'gender_categories';

    public function getGenderCategories(Request $request){

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

            $uuId = !empty($request->gender_category_id) ? $request->gender_category_id : 0;

            $get = GenderCategories::select('gender_categories.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('gender_categories.uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->orWhere('gender_categories.gender_category', 'like', '%'.$keyword.'%');
                        });
                    }
                    return $query;
                })

                ->orderBy('gender_categories.display_order', 'ASC')
                ->orderBy('gender_categories.gender_category', 'ASC');

            if (!empty($mode) && $mode == 'for_select'){
                $out = $get->get();
            }else{
                $out = $get->paginate($itemsPerPage, ['*'], 'page', $currentPage);
            }
        }

        return response()->json($out);
    }

    public function getGenderCategory(Request $request){
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
            $uuId = !empty($request->gender_category_id) ? $request->gender_category_id : 0;

            $out = GenderCategories::select('gender_categories.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('gender_categories.uuid', $uuId);
                })
                ->first();
        }

        return response()->json($out);
    }

    public function setGenderCategory(Request $request){
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
            $getId = !empty($request->gender_category_id) ? $request->gender_category_id : 0;

            if (!empty($getId)){
                $validated = $request->validate([
                    'gender_category' => 'required|unique:gender_categories,gender_category,'.$getId .',uuid',
                ]);

                $c = new CommonHelper();
                $slug = $c->generateSeoURL($request->gender_category, 1);

                $set = GenderCategories::where('uuid', $getId)->first();
                $set->gender_category = $request->gender_category;
                $set->display_order = !empty($request->display_order) ? $request->display_order : 0;
                $set->slug = $slug;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Gender Category has been Updated!';

            }else{
                $validated = $request->validate([
                    'gender_category' => 'required|unique:gender_categories',
                ]);

                $c = new CommonHelper();
                $slug = $c->generateSeoURL($request->gender_category, 1);

                $set = new GenderCategories();
                $set->gender_category = $request->gender_category;
                $set->display_order = !empty($request->display_order) ? $request->display_order : 0;
                $set->slug = $slug;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => 0, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = GenderCategories::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Gender Category Added!';

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

                $set = GenderCategories::where('uuid', $getId)->first();
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
