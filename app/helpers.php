<?php

//List all businesses as having on the business urls table with the business ID
function businessUrl($businessId = 1){
    $businesses = [
        '1' => 'https://sevn.lk',
    ];

    return !empty($businesses[$businessId]) ? $businesses[$businessId] : $businesses[1];
}

function businessAssetPath($path, $businessId = 1){
    $separator = mb_substr($path, 0, 1) == '/' ? '' : '/';
    return businessUrl($businessId) . '/assets' . $separator . $path;
}

function priceWithCurrency($price){
    $price = str_replace(',', '', $price);
    $price = (float) $price;
    return defaultCurrency() . number_format($price, 2);
}

function priceWithoutCurrency($price){
    $price = str_replace(',', '', $price);
    $price = (float) $price;
    return number_format($price, 2);
}

function defaultCurrency(){
    return 'Rs.';
}

function dateTimeFormat($date){
    return date('d/m/Y H:i', strtotime($date));
}

function dateFormat($date){
    return date('d/m/Y', strtotime($date));
}

function batchNumberFormat($batchNumber){
    return str_pad($batchNumber, 6, '0', STR_PAD_LEFT);
}

function commonSwitchStatus($status){

    $out = [
        'text' => 'Inactive',
        'class' => 'bg-warning',
    ];

    if ($status == 1){
        $out = [
            'text' => 'Active',
            'class' => 'bg-success',
        ];
    }

    return $out;
}

?>
