<?php

namespace App\Helpers;

use Illuminate\Http\Request;


class GeneralHelper
{
    public static function businessDetails(Request $res)
    {
        return (object)[
            'name' => 'sevn',
            'url' => 'sevn.lk',
        ];

    }


}
