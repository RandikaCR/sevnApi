<?php

namespace App\Http\Controllers\Services;

use App\Helpers\CommonHelper;
use App\Helpers\ProductsHelper;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    private $screenPrefix = 'products';

    public function getProducts(Request $request)
    {

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

        if (empty($isInvalid)) {
            $itemsPerPage = !empty($request->items_per_page) ? $request->items_per_page : $this->defaultItemsPerPage;
            $currentPage = !empty($request->current_page) ? $request->current_page : 0;
            $mode = !empty($request->mode) ? $request->mode : null;

            $uuId = !empty($request->progress_id) ? $request->progress_id : 0;

            $get = Products::select('products.*')
                ->when(!empty($uuId), function ($query) use ($uuId) {
                    return $query->where('uuid', $uuId);
                }, function ($query) use ($request) {
                    $keyword = !empty($request->keyword) ? $request->keyword : '';
                    if (!empty($keyword)) {
                        $query->where(function ($query) use ($keyword) {
                            return $query->where('product', 'like', '%' . $keyword . '%');
                        });
                    }
                    return $query;
                })
                ->orderBy('products.id', 'DESC');

            if (!empty($mode) && $mode == 'for_select') {
                $out = $get->get();
            } else {
                $out = $get->paginate($itemsPerPage, ['*'], 'page', $currentPage);
            }
        }

        return response()->json($out);
    }

    public function getProduct(Request $request)
    {
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

        if (empty($isInvalid)) {

            $mode = !empty($request->mode) ? $request->mode : null;

            if ($mode == 'get_product_for_edit') {

                $p = new ProductsHelper();
                $out = $p->getProductForEdit($request);
            } else {
                $uuId = !empty($request->product_id) ? $request->product_id : 0;

                $out = Products::select('products.*')
                    ->when(!empty($uuId), function ($query) use ($uuId) {
                        return $query->where('uuid', $uuId);
                    })
                    ->first();
            }
        }

        return response()->json($out);
    }

    public function setProduct(Request $request)
    {
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
            $getId = !empty($request->product_id) ? $request->product_id : 0;

            if (!empty($getId)) {
                $validated = $request->validate([
                    'product' => 'required|unique:products,product,' . $getId . ',uuid',
                ]);

                $set = Products::where('uuid', $getId)->first();
                $set->product = !empty($request->product) ? $request->product : null;
                $set->save();

                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'Product has been Updated!';
            } else {
                $validated = $request->validate([
                    'product' => 'required|unique:products',
                ]);

                $set = new Products();
                $set->product = !empty($request->product) ? $request->product : null;
                $set->status = 1;
                $set->save();


                $getCommon = new CommonHelper();
                $uuId = $getCommon->generateUUId(['business_id' => 0, 'screen' => $this->screenPrefix, 'id' => $set->id]);

                $update = Products::find($set->id);
                $update->uuid = $uuId;
                $update->save();


                $out['status'] = 'success';
                $out['message_title'] = 'Success!';
                $out['message_text'] = 'New Product Added!';
            }
        }

        return response()->json($out);
    }

    public function setStatus(Request $request)
    {
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

            if (!empty($getId)) {

                $set = Products::where('uuid', $getId)->first();
                if (!empty($set) && $set->status == 1) {
                    $set->status = 0;
                } else {
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
