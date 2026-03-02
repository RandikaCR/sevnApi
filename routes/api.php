<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// B
use App\Http\Controllers\Services\BusinessesController;
use App\Http\Controllers\Services\BusinessBranchesController;

// C
use App\Http\Controllers\Services\CategoriesController;
use App\Http\Controllers\Services\CitiesController;
use App\Http\Controllers\Services\ColorsController;
use App\Http\Controllers\Services\CountriesController;
use App\Http\Controllers\Services\CountryRegionsController;
use App\Http\Controllers\Services\CourierChargesController;
use App\Http\Controllers\Services\CourierServicesController;


// D
use App\Http\Controllers\Services\DeliveryMethodsController;
use App\Http\Controllers\Services\DesignationsController;
use App\Http\Controllers\Services\DistrictsController;

// G
use App\Http\Controllers\Services\GenderCategoriesController;

// O
use App\Http\Controllers\Services\OrderStatusesController;

// P
use App\Http\Controllers\Services\PaymentMethodsController;
use App\Http\Controllers\Services\ProgressesController;

// S
use App\Http\Controllers\Services\ScreensController;
use App\Http\Controllers\Services\SizesController;

// S
use App\Http\Controllers\Services\TagsController;

// U
use App\Http\Controllers\Services\UsersController;
use App\Http\Controllers\Services\UserRolesController;
use App\Http\Controllers\Services\UserTitlesController;


// Common Routes
Route::post('/Users/loginUser', [UsersController::class, 'loginUser'])->name('users.loginUser');
Route::post('/Users/setUser', [UsersController::class, 'setUser'])->name('users.setUser');
Route::post('/Users/verifyUserAccountByEmail', [UsersController::class, 'verifyUserAccountByEmail'])->name('users.verifyUserAccountByEmail');



// Auth Routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // B
    Route::prefix('Businesses')->group(function (){
        Route::post('/getBusinesses', [BusinessesController::class, 'getBusinesses'])->name('businesses.getBusinesses');
        Route::post('/getBusiness', [BusinessesController::class, 'getBusiness'])->name('businesses.getBusiness');
        Route::post('/setBusiness', [BusinessesController::class, 'setBusiness'])->name('businesses.setBusiness');
        Route::post('/setStatus', [BusinessesController::class, 'setStatus'])->name('businesses.setStatus');
    });

    Route::prefix('businessBranches')->group(function (){
        Route::post('/getBusinessBranches', [BusinessBranchesController::class, 'getBusinessBranches'])->name('businessBranches.getBusinessBranches');
        Route::post('/getBusinessBranch', [BusinessBranchesController::class, 'getBusinessBranch'])->name('businessBranches.getBusinessBranch');
        Route::post('/setBusinessBranch', [BusinessBranchesController::class, 'setBusinessBranch'])->name('businessBranches.setBusinessBranch');
        Route::post('/setStatus', [BusinessBranchesController::class, 'setStatus'])->name('businessBranches.setStatus');
    });


    // C
    Route::prefix('Categories')->group(function (){
        Route::post('/getCategories', [CategoriesController::class, 'getCategories'])->name('categories.getCategories');
        Route::post('/getCategory', [CategoriesController::class, 'getCategory'])->name('categories.getCategory');
        Route::post('/setCategory', [CategoriesController::class, 'setCategory'])->name('categories.setCategory');
        Route::post('/setStatus', [CategoriesController::class, 'setStatus'])->name('categories.setStatus');
    });

    Route::prefix('Cities')->group(function (){
        Route::post('/getCities', [CitiesController::class, 'getCities'])->name('cities.getCities');
        Route::post('/getCity', [CitiesController::class, 'getCity'])->name('cities.getCity');
        Route::post('/setCity', [CitiesController::class, 'setCity'])->name('cities.setCity');
        Route::post('/setStatus', [CitiesController::class, 'setStatus'])->name('cities.setStatus');
    });

    Route::prefix('Colors')->group(function (){
        Route::post('/getColors', [ColorsController::class, 'getColors'])->name('colors.getColors');
        Route::post('/getColor', [ColorsController::class, 'getColor'])->name('colors.getColor');
        Route::post('/setColor', [ColorsController::class, 'setColor'])->name('colors.setColor');
        Route::post('/setStatus', [ColorsController::class, 'setStatus'])->name('colors.setStatus');
    });

    Route::prefix('Countries')->group(function (){
        Route::post('/getCountries', [CountriesController::class, 'getCountries'])->name('countries.getCountries');
        Route::post('/getCountry', [CountriesController::class, 'getCountry'])->name('countries.getCountry');
        Route::post('/setCountry', [CountriesController::class, 'setCountry'])->name('countries.setCountry');
        Route::post('/setStatus', [CountriesController::class, 'setStatus'])->name('countries.setStatus');
    });

    Route::prefix('CountryRegions')->group(function (){
        Route::post('/getCountryRegions', [CountryRegionsController::class, 'getCountryRegions'])->name('countryRegions.getCountryRegions');
        Route::post('/getCountryRegion', [CountryRegionsController::class, 'getCountryRegion'])->name('countryRegions.getCountryRegion');
        Route::post('/setCountryRegion', [CountryRegionsController::class, 'setCountryRegion'])->name('countryRegions.setCountryRegion');
        Route::post('/setStatus', [CountryRegionsController::class, 'setStatus'])->name('countryRegions.setStatus');
    });

    Route::prefix('CourierCharges')->group(function (){
        Route::post('/getCourierCharges', [CourierChargesController::class, 'getCourierCharges'])->name('courierCharges.getCourierCharges');
        Route::post('/getCourierCharge', [CourierChargesController::class, 'getCourierCharge'])->name('courierCharges.getCourierCharge');
        Route::post('/setCourierCharge', [CourierChargesController::class, 'setCourierCharge'])->name('courierCharges.setCourierCharge');
        Route::post('/setStatus', [CourierChargesController::class, 'setStatus'])->name('courierCharges.setStatus');
    });

    Route::prefix('CourierServices')->group(function (){
        Route::post('/getCourierServices', [CourierServicesController::class, 'getCourierServices'])->name('courierServices.getCourierServices');
        Route::post('/getCourierService', [CourierServicesController::class, 'getCourierService'])->name('courierServices.getCourierService');
        Route::post('/setCourierService', [CourierServicesController::class, 'setCourierService'])->name('courierServices.setCourierService');
        Route::post('/setStatus', [CourierServicesController::class, 'setStatus'])->name('courierServices.setStatus');
        Route::post('/setDefault', [CourierServicesController::class, 'setDefault'])->name('courierServices.setDefault');
    });



    // D
    Route::prefix('DeliveryMethods')->group(function (){
        Route::post('/getDeliveryMethods', [DeliveryMethodsController::class, 'getDeliveryMethods'])->name('deliveryMethods.getDeliveryMethods');
        Route::post('/getDeliveryMethod', [DeliveryMethodsController::class, 'getDeliveryMethod'])->name('deliveryMethods.getDeliveryMethod');
        Route::post('/setDeliveryMethod', [DeliveryMethodsController::class, 'setDeliveryMethod'])->name('deliveryMethods.setDeliveryMethod');
        Route::post('/setStatus', [DeliveryMethodsController::class, 'setStatus'])->name('deliveryMethods.setStatus');
    });


    Route::prefix('Designations')->group(function (){
        Route::post('/getDesignations', [DesignationsController::class, 'getDesignations'])->name('designations.getDesignations');
        Route::post('/getDesignation', [DesignationsController::class, 'getDesignation'])->name('designations.getDesignation');
        Route::post('/setDesignation', [DesignationsController::class, 'setDesignation'])->name('designations.setDesignation');
        Route::post('/setStatus', [DesignationsController::class, 'setStatus'])->name('designations.setStatus');
    });

    Route::prefix('Districts')->group(function (){
        Route::post('/getDistricts', [DistrictsController::class, 'getDistricts'])->name('districts.getDistricts');
        Route::post('/getDistrict', [DistrictsController::class, 'getDistrict'])->name('districts.getDistrict');
        Route::post('/setDistrict', [DistrictsController::class, 'setDistrict'])->name('districts.setDistrict');
        Route::post('/setStatus', [DistrictsController::class, 'setStatus'])->name('districts.setStatus');
    });


    // G
    Route::prefix('GenderCategories')->group(function (){
        Route::post('/getGenderCategories', [GenderCategoriesController::class, 'getGenderCategories'])->name('genderCategories.getGenderCategories');
        Route::post('/getGenderCategory', [GenderCategoriesController::class, 'getGenderCategory'])->name('genderCategories.getGenderCategory');
        Route::post('/setGenderCategory', [GenderCategoriesController::class, 'setGenderCategory'])->name('genderCategories.setGenderCategory');
        Route::post('/setStatus', [GenderCategoriesController::class, 'setStatus'])->name('genderCategories.setStatus');
    });


    // O
    Route::prefix('OrderStatuses')->group(function (){
        Route::post('/getOrderStatuses', [OrderStatusesController::class, 'getOrderStatuses'])->name('orderStatuses.getOrderStatuses');
        Route::post('/getOrderStatus', [OrderStatusesController::class, 'getOrderStatus'])->name('orderStatuses.getOrderStatus');
        Route::post('/setOrderStatus', [OrderStatusesController::class, 'setOrderStatus'])->name('orderStatuses.setOrderStatus');
        Route::post('/setStatus', [OrderStatusesController::class, 'setStatus'])->name('orderStatuses.setStatus');
    });


    // P
    Route::prefix('PaymentMethods')->group(function (){
        Route::post('/getPaymentMethods', [PaymentMethodsController::class, 'getPaymentMethods'])->name('paymentMethods.getPaymentMethods');
        Route::post('/getPaymentMethod', [PaymentMethodsController::class, 'getPaymentMethod'])->name('paymentMethods.getPaymentMethod');
        Route::post('/setPaymentMethod', [PaymentMethodsController::class, 'setPaymentMethod'])->name('paymentMethods.setPaymentMethod');
        Route::post('/setStatus', [PaymentMethodsController::class, 'setStatus'])->name('paymentMethods.setStatus');
    });

    Route::prefix('Progresses')->group(function (){
        Route::post('/getProgresses', [ProgressesController::class, 'getProgresses'])->name('progresses.getProgresses');
        Route::post('/getProgress', [ProgressesController::class, 'getProgress'])->name('progresses.getProgress');
        Route::post('/setProgress', [ProgressesController::class, 'setProgress'])->name('progresses.setProgress');
        Route::post('/setStatus', [ProgressesController::class, 'setStatus'])->name('progresses.setStatus');
    });

    // S
    Route::prefix('Screens')->group(function (){
        Route::post('/getScreens', [ScreensController::class, 'getScreens'])->name('screens.getScreens');
        Route::post('/getScreen', [ScreensController::class, 'getScreen'])->name('screens.getScreen');
        Route::post('/setScreen', [ScreensController::class, 'setScreen'])->name('screens.setScreen');
        Route::post('/setStatus', [ScreensController::class, 'setStatus'])->name('screens.setStatus');
    });

    Route::prefix('Sizes')->group(function (){
        Route::post('/getSizes', [SizesController::class, 'getSizes'])->name('sizes.getSizes');
        Route::post('/getSize', [SizesController::class, 'getSize'])->name('sizes.getSize');
        Route::post('/setSize', [SizesController::class, 'setSize'])->name('sizes.setSize');
        Route::post('/setStatus', [SizesController::class, 'setStatus'])->name('sizes.setStatus');
    });


    // T
    Route::prefix('Tags')->group(function (){
        Route::post('/getTags', [TagsController::class, 'getTags'])->name('tags.getTags');
        Route::post('/getTag', [TagsController::class, 'getTag'])->name('tags.getTag');
        Route::post('/setTag', [TagsController::class, 'setTag'])->name('tags.setTag');
        Route::post('/setStatus', [TagsController::class, 'setStatus'])->name('tags.setStatus');
    });


    // U
    Route::prefix('Users')->group(function (){
        Route::post('/getUsers', [UsersController::class, 'getUsers'])->name('UserRoles.getUsers');
        Route::post('/getUser', [UsersController::class, 'getUser'])->name('users.getUser');
        Route::post('/getUserBusinesses', [UsersController::class, 'getUserBusinesses'])->name('users.getUserBusinesses');
        Route::post('/getUserBusinessBranches', [UsersController::class, 'getUserBusinessBranches'])->name('users.getUserBusinessBranches');
        Route::post('/setUserProfile', [UsersController::class, 'setUserProfile'])->name('users.setUserProfile');
    });

    Route::prefix('UserRoles')->group(function (){
        Route::post('/getUserRoles', [UserRolesController::class, 'getUserRoles'])->name('userRoles.getUserRoles');
        Route::post('/getUserRole', [UserRolesController::class, 'getUserRole'])->name('userRoles.getUserRole');
        Route::post('/setUserRole', [UserRolesController::class, 'setUserRole'])->name('userRoles.setUserRole');
        Route::post('/setStatus', [UserRolesController::class, 'setStatus'])->name('userRoles.setStatus');
    });

    Route::prefix('UserTitles')->group(function (){
        Route::post('/getUserTitles', [UserTitlesController::class, 'getUserTitles'])->name('userTitles.getUserTitles');
        Route::post('/getUserTitle', [UserTitlesController::class, 'getUserTitle'])->name('userTitles.getUserTitle');
        Route::post('/setUserTitle', [UserTitlesController::class, 'setUserTitle'])->name('userTitles.setUserTitle');
        Route::post('/setStatus', [UserTitlesController::class, 'setStatus'])->name('userTitles.setStatus');
    });



});
