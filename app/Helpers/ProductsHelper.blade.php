<?php

namespace App\Helpers;

use Illuminate\Http\Request;


class ProductsHelper
{
    public static function getProductForEdit($request){

        $out = [];
        $uuId = !empty($request->product_id) ? $request->product_id : 0;

        if (!empty($uuId)) {
            
            $uuId = !empty($request->product_id) ? $request->product_id : 0;

            $out = Products::select('products.*')
            ->when(!empty($uuId), function ($query) use ($uuId) {
                return $query->where('uuid', $uuId);
            })
            ->first();

            

        }else{

        }

        return $out;
    }


}
