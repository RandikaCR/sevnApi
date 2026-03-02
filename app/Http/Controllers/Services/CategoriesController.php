<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    private $screenPrefix = 'categories';

    public function getCategories(Request $request){

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

            $uuId = !empty($request->category_id) ? $request->category_id : 0;
            $businessId = !empty($request->business_id) ? $request->business_id : 0;

            $get = Categories::select('categories.*', 'businesses.business')
                ->join('businesses', 'categories.business_id', 'businesses.id')
                ->when(!empty($businessId), function ($query) use ($businessId) {
                    return $query->where('categories.business_id', $businessId);
                })
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('categories.uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)){
                        $query->where(function ($query) use ($keyword) {
                            return $query->orWhere('categories.category', 'like', '%'.$keyword.'%')
                                ->orWhere('businesses.business', 'like', '%'.$keyword.'%');
                        });
                    }
                    return $query;
                })
                ->orderBy('categories.display_order', 'ASC')
                ->orderBy('categories.category', 'ASC');

            if (!empty($mode) && $mode == 'for_select'){
                $out = $get->get();
            }else{
                $out = $get->paginate($itemsPerPage, ['*'], 'page', $currentPage);
            }
        }

        return response()->json($out);
    }

    public function getCategory(Request $request){
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
            $uuId = !empty($request->category_id) ? $request->category_id : 0;

            $out = Categories::select('categories.*', 'businesses.uuid AS business_uuid', 'businesses.business')
                ->join('businesses', 'categories.business_id', '=', 'businesses.id')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('categories.uuid', $uuId);
                })
                ->first();
        }

        return response()->json($out);
    }

    public function setCategory(Request $request){
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
            $getId = !empty($request->category_id) ? $request->category_id : 0;

            if (!empty($getId)){
                $validated = $request->validate([
                    'business_id' => 'required',
                    'category' => 'required|unique:categories,category,'.$getId .',uuid',
                ]);

                $c = new CommonHelper();
                $slug = $c->generateSeoURL($request->category, 1);

                $set = Categories::where('uuid', $getId)->first();
                $set->category = $request->category;
                $set->business_id = !empty($request->business_id) ? $request->business_id : 0;
                $set->display_order = !empty($request->display_order) ? $request->display_order : 0;
                $set->slug = $slug;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Category has been Updated!';

            }else{
                $validated = $request->validate([
                    'business_id' => 'required',
                    'category' => 'required|unique:categories',
                ]);

                $c = new CommonHelper();
                $slug = $c->generateSeoURL($request->category, 1);

                $set = new Categories();
                $set->category = $request->category;
                $set->business_id = !empty($request->business_id) ? $request->business_id : 0;
                $set->display_order = !empty($request->display_order) ? $request->display_order : 0;
                $set->slug = $slug;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => $request->business_id, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = Categories::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Category Added!';

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

                $set = Categories::where('uuid', $getId)->first();
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
