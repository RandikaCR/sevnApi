<?php

namespace App\Helpers;

use App\Models\Products;
use Illuminate\Http\Request;


class ProductsHelper
{
    public static function getProductForEdit($request){

        $product = [];
        $categories = [];
        $businessId = !empty($request->business_id) ? $request->business_id : 0;
        $uuId = !empty($request->product_id) ? $request->product_id : 0;

        //Get Categories
        if (!empty($businessId)){
            $c = new CommonHelper();
            $categories = $c->getCategories(['business_id' => $businessId]);
        }

        //Get Gender Categories
        $c = new CommonHelper();
        $genderCategories = $c->getGenderCategories();

        //Get Product Ranges
        $c = new CommonHelper();
        $productRanges = $c->getProductRanges();

        //Get Sizes
        $c = new CommonHelper();
        $sizes = $c->getSizes();

        //Get Colors
        $c = new CommonHelper();
        $colors = $c->getColors();

        //Get Tags
        $c = new CommonHelper();
        $tags = $c->getTags();


        //Get Product
        if (!empty($uuId)) {
            $product = Products::select('products.*')
            ->when(!empty($uuId), function ($query) use ($uuId) {
                return $query->where('uuid', $uuId);
            })
            ->first();
        }

        $out = [
            'product' => $product,
            'categories' => $categories,
            'gender_categories' => $genderCategories,
            'product_ranges' => $productRanges,
            'colors' => $colors,
            'sizes' => $sizes,
            'tags' => $tags,
        ];

        return $out;
    }


}
