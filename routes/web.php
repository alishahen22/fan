<?php

use App\Http\Controllers\Admin\ItemsController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::middleware('guest')->group(function () {
// Login Routes
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
});

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\Admin\HomeController::class, 'lang']);

Route::redirect('/', '/admin');

Route::prefix('/admin')->middleware('auth')->group(function () {

    Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'root'])->name('root');
    Route::get('/home/orders/data', [App\Http\Controllers\Admin\HomeController::class, 'newOrdersData'])->name('home.orders.data');

    // Logout Route
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {

        Route::get('/import-items', [ItemsController::class, 'import'])->name('import.items');
        Route::post('/import-items', [ItemsController::class, 'importItems'])->name('import.items.post');

        Route::get('/import-supplies', [\App\Http\Controllers\Admin\SuppliesController::class, 'import'])->name('import.supplies');
        Route::post('/import-supplies', [\App\Http\Controllers\Admin\SuppliesController::class, 'importSupplies'])->name('import.supplies.post');

        Route::resource('/attributes', \App\Http\Controllers\Admin\AttributesController::class)->names('attributes')->except('show')->middleware('permission:attributes_list');
        Route::get('/attributes/data', [\App\Http\Controllers\Admin\AttributesController::class, 'getData'])->name('attributes.data')->middleware('permission:attributes_list');
        Route::post('/attributes/bulkDelete', [\App\Http\Controllers\Admin\AttributesController::class, 'bulkDelete'])->name('attributes.bulkDelete')->middleware('permission:attributes_delete');
        Route::post('/attributes/bulkChangeStatus', [\App\Http\Controllers\Admin\AttributesController::class, 'bulkChangeStatus'])->name('attributes.bulkChangeStatus')->middleware('permission:attributes_edit');
        Route::post('/attributes/changeStatus', [\App\Http\Controllers\Admin\AttributesController::class, 'changeStatus'])->name('attributes.changeStatus')->middleware('permission:attributes_edit');

        // Products
        Route::resource('/products', \App\Http\Controllers\Admin\ProductsController::class)->names('products')->except('show')->middleware('permission:products_list');
        Route::get('/products/data', [\App\Http\Controllers\Admin\ProductsController::class, 'getData'])->name('products.data')->middleware('permission:products_list');
        Route::post('/products/bulkDelete', [\App\Http\Controllers\Admin\ProductsController::class, 'bulkDelete'])->name('products.bulkDelete')->middleware('permission:products_delete');
        Route::post('/products/bulkChangeStatus', [\App\Http\Controllers\Admin\ProductsController::class, 'bulkChangeStatus'])->name('products.bulkChangeStatus')->middleware('permission:products_edit');
        Route::post('/products/changeStatus', [\App\Http\Controllers\Admin\ProductsController::class, 'changeStatus'])->name('products.changeStatus')->middleware('permission:products_edit');
        Route::post('/products/UploadGallery', [\App\Http\Controllers\Admin\ProductsController::class, 'UploadGallery'])->name('products.uploadGallery')->middleware('permission:products_edit');
        Route::post('/products/removeGallery', [\App\Http\Controllers\Admin\ProductsController::class, 'removeGallery'])->name('products.removeGallery')->middleware('permission:products_edit');

//        products_attributes
        Route::resource('product_attributes', \App\Http\Controllers\Admin\ProductAttributesController::class)->names('product_attributes')->except('show');
        Route::get('/product_attributes/data/{id}', [\App\Http\Controllers\Admin\ProductAttributesController::class, 'getData'])->name('product_attributes.data');
        Route::post('/product_attributes/changeType', [\App\Http\Controllers\Admin\ProductAttributesController::class, 'changeType'])->name('product_attributes.changeType');

        Route::resource('product_attribute_options', \App\Http\Controllers\Admin\ProductAttributeOptionsController::class)->names('product_attribute_options')->except('show');
        Route::get('/product_attribute_options/data/{id}', [\App\Http\Controllers\Admin\ProductAttributeOptionsController::class, 'getData'])->name('product_attribute_options.data');

        Route::resource('/users', \App\Http\Controllers\Admin\UsersController::class)->names('users')->middleware('permission:users_list');
        Route::get('/users/get/data', [\App\Http\Controllers\Admin\UsersController::class, 'getData'])->name('users.data')->middleware('permission:users_list');
        Route::post('/users/changeStatus', [\App\Http\Controllers\Admin\UsersController::class, 'changeStatus'])->name('users.changeStatus')->middleware('permission:users_edit');
        Route::get('/users/orders/{userId}', [\App\Http\Controllers\Admin\UsersOrdersController::class, 'index'])->name('users.orders.index')->middleware('permission:users_view');
        Route::get('/users/orders/data/{user_id}', [\App\Http\Controllers\Admin\UsersController::class, 'getOrdersData'])->name('users.orders.data')->middleware('permission:users_view');

        Route::resource('/categories', \App\Http\Controllers\Admin\CategoriesController::class)->names('categories')->except('show')->middleware('permission:categories_list');
        Route::get('/categories/data', [\App\Http\Controllers\Admin\CategoriesController::class, 'getData'])->name('categories.data')->middleware('permission:categories_list');
        Route::post('/categories/bulkDelete', [\App\Http\Controllers\Admin\CategoriesController::class, 'bulkDelete'])->name('categories.bulkDelete')->middleware('permission:categories_delete');
        Route::post('/categories/bulkChangeStatus', [\App\Http\Controllers\Admin\CategoriesController::class, 'bulkChangeStatus'])->name('categories.bulkChangeStatus')->middleware('permission:categories_edit');
        Route::post('/categories/changeStatus', [\App\Http\Controllers\Admin\CategoriesController::class, 'changeStatus'])->name('categories.changeStatus')->middleware('permission:categories_edit');

        Route::resource('/cities', \App\Http\Controllers\Admin\CitiesController::class)->names('cities')->except('show')->middleware('permission:cities_list');
        Route::get('/cities/data', [\App\Http\Controllers\Admin\CitiesController::class, 'getData'])->name('cities.data')->middleware('permission:cities_list');
        Route::post('/cities/bulkDelete', [\App\Http\Controllers\Admin\CitiesController::class, 'bulkDelete'])->name('cities.bulkDelete')->middleware('permission:cities_delete');
        Route::post('/cities/bulkChangeStatus', [\App\Http\Controllers\Admin\CitiesController::class, 'bulkChangeStatus'])->name('cities.bulkChangeStatus')->middleware('permission:cities_edit');
        Route::post('/cities/changeStatus', [\App\Http\Controllers\Admin\CitiesController::class, 'changeStatus'])->name('cities.changeStatus')->middleware('permission:cities_edit');

        Route::get('/cities/areas/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'index'])->name('areas.index')->middleware('permission:areas_list');
        Route::get('/areas/create/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'create'])->name('areas.create')->middleware('permission:areas_create');
        Route::delete('/areas/destroy/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'destroy'])->name('areas.destroy')->middleware('permission:areas_delete');
        Route::post('/areas/store/new/area/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'store'])->name('areas.store')->middleware('permission:areas_create');
        Route::get('/areas/edit/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'edit'])->name('areas.edit')->middleware('permission:areas_edit');
        Route::put('/areas/update/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'update'])->name('areas.update')->middleware('permission:areas_edit');
        Route::get('/areas/data/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'getData'])->name('areas.data')->middleware('permission:areas_list');
        Route::post('/areas/bulkDelete', [\App\Http\Controllers\Admin\AreaController::class, 'bulkDelete'])->name('areas.bulkDelete')->middleware('permission:areas_delete');
        Route::post('/areas/bulkChangeStatus', [\App\Http\Controllers\Admin\AreaController::class, 'bulkChangeStatus'])->name('areas.bulkChangeStatus')->middleware('permission:areas_edit');
        Route::post('/areas/changeStatus', [\App\Http\Controllers\Admin\AreaController::class, 'changeStatus'])->name('areas.changeStatus')->middleware('permission:areas_edit');

        Route::resource('/splashes', \App\Http\Controllers\Admin\SplashesController::class)->names('splashes')->except('show')->middleware('permission:splashes_list');
        Route::get('/splashes/data', [\App\Http\Controllers\Admin\SplashesController::class, 'getData'])->name('splashes.data')->middleware('permission:splashes_list');
        Route::post('/splashes/bulkDelete', [\App\Http\Controllers\Admin\SplashesController::class, 'bulkDelete'])->name('splashes.bulkDelete')->middleware('permission:splashes_delete');
        Route::post('/splashes/bulkChangeStatus', [\App\Http\Controllers\Admin\SplashesController::class, 'bulkChangeStatus'])->name('splashes.bulkChangeStatus')->middleware('permission:splashes_edit');
        Route::post('/splashes/changeStatus', [\App\Http\Controllers\Admin\SplashesController::class, 'changeStatus'])->name('splashes.changeStatus')->middleware('permission:splashes_edit');

        Route::resource('/offers', \App\Http\Controllers\Admin\OffersController::class)->names('offers')->except('show')->middleware('permission:offers_list');
        Route::get('/offers/data', [\App\Http\Controllers\Admin\OffersController::class, 'getData'])->name('offers.data')->middleware('permission:offers_list');
        Route::post('/offers/bulkDelete', [\App\Http\Controllers\Admin\OffersController::class, 'bulkDelete'])->name('offers.bulkDelete')->middleware('permission:offers_delete');
        Route::post('/offers/bulkChangeStatus', [\App\Http\Controllers\Admin\OffersController::class, 'bulkChangeStatus'])->name('offers.bulkChangeStatus')->middleware('permission:offers_edit');
        Route::post('/offers/changeStatus', [\App\Http\Controllers\Admin\OffersController::class, 'changeStatus'])->name('offers.changeStatus')->middleware('permission:offers_edit');

        Route::resource('/branches', \App\Http\Controllers\Admin\BranchesController::class)->names('branches')->except('show')->middleware('permission:branches_list');
        Route::get('/branches/data', [\App\Http\Controllers\Admin\BranchesController::class, 'getData'])->name('branches.data')->middleware('permission:branches_list');
        Route::post('/branches/bulkDelete', [\App\Http\Controllers\Admin\BranchesController::class, 'bulkDelete'])->name('branches.bulkDelete')->middleware('permission:branches_delete');
        Route::post('/branches/bulkChangeStatus', [\App\Http\Controllers\Admin\BranchesController::class, 'bulkChangeStatus'])->name('branches.bulkChangeStatus')->middleware('permission:branches_edit');
        Route::post('/branches/changeStatus', [\App\Http\Controllers\Admin\BranchesController::class, 'changeStatus'])->name('branches.changeStatus')->middleware('permission:branches_edit');
        Route::get('/branches/delete/image/{id}', [\App\Http\Controllers\Admin\BranchesController::class, 'deleteImage'])->name('branches.images.delete')->middleware('permission:branches_edit');

        Route::resource('/articles', \App\Http\Controllers\Admin\ArticlesController::class)->names('articles')->except('show')->middleware('permission:articles_list');
        Route::get('/articles/data', [\App\Http\Controllers\Admin\ArticlesController::class, 'getData'])->name('articles.data')->middleware('permission:articles_list');
        Route::post('/articles/bulkDelete', [\App\Http\Controllers\Admin\ArticlesController::class, 'bulkDelete'])->name('articles.bulkDelete')->middleware('permission:articles_delete');
        Route::post('/articles/bulkChangeStatus', [\App\Http\Controllers\Admin\ArticlesController::class, 'bulkChangeStatus'])->name('articles.bulkChangeStatus')->middleware('permission:articles_edit');
        Route::post('/articles/changeStatus', [\App\Http\Controllers\Admin\ArticlesController::class, 'changeStatus'])->name('articles.changeStatus')->middleware('permission:articles_edit');

        Route::resource('/vouchers', \App\Http\Controllers\Admin\VouchersController::class)->names('vouchers')->except('show')->middleware('permission:vouchers_list');
        Route::get('/vouchers/data', [\App\Http\Controllers\Admin\VouchersController::class, 'getData'])->name('vouchers.data')->middleware('permission:vouchers_list');
        Route::post('/vouchers/bulkDelete', [\App\Http\Controllers\Admin\VouchersController::class, 'bulkDelete'])->name('vouchers.bulkDelete')->middleware('permission:vouchers_delete');
        Route::post('/vouchers/changeStatus', [\App\Http\Controllers\Admin\VouchersController::class, 'changeStatus'])->name('vouchers.changeStatus')->middleware('permission:vouchers_edit');
        Route::post('/vouchers/changeFirstOrder', [\App\Http\Controllers\Admin\VouchersController::class, 'changeFirstOrder'])->name('vouchers.changeFirstOrder')->middleware('permission:vouchers_edit');

        Route::resource('contacts', \App\Http\Controllers\Admin\ContactsController::class)->names('contacts')->except('show')->middleware('permission:contacts_list');
        Route::get('/contacts/data', [\App\Http\Controllers\Admin\ContactsController::class, 'getData'])->name('contacts.data')->middleware('permission:contacts_list');
        Route::post('/contacts/bulkDelete', [\App\Http\Controllers\Admin\ContactsController::class, 'bulkDelete'])->name('contacts.bulkDelete')->middleware('permission:contacts_delete');

        Route::resource('/sliders', \App\Http\Controllers\Admin\SlidersController::class)->names('sliders')->except('show')->middleware('permission:sliders_list');
        Route::get('/sliders/data', [\App\Http\Controllers\Admin\SlidersController::class, 'getData'])->name('sliders.data')->middleware('permission:sliders_list');
        Route::post('/sliders/bulkDelete', [\App\Http\Controllers\Admin\SlidersController::class, 'bulkDelete'])->name('sliders.bulkDelete')->middleware('permission:sliders_delete');
        Route::post('/sliders/changeStatus', [\App\Http\Controllers\Admin\SlidersController::class, 'changeStatus'])->name('sliders.changeStatus')->middleware('permission:sliders_edit');

        //        banners
        Route::resource('/banners', \App\Http\Controllers\Admin\BannersController::class)->names('banners')->except('show')->middleware('permission:banners_list');
        Route::get('/banners/data', [\App\Http\Controllers\Admin\BannersController::class, 'getData'])->name('banners.data')->middleware('permission:banners_list');
        Route::post('/banners/bulkDelete', [\App\Http\Controllers\Admin\BannersController::class, 'bulkDelete'])->name('banners.bulkDelete')->middleware('permission:banners_delete');
        Route::post('/banners/changeStatus', [\App\Http\Controllers\Admin\BannersController::class, 'changeStatus'])->name('banners.changeStatus')->middleware('permission:banners_edit');

        //        steps
        Route::resource('/steps', \App\Http\Controllers\Admin\StepsController::class)->names('steps')->except('show')->middleware('permission:steps_list');
        Route::get('/steps/data', [\App\Http\Controllers\Admin\StepsController::class, 'getData'])->name('steps.data')->middleware('permission:steps_list');
        Route::post('/steps/bulkDelete', [\App\Http\Controllers\Admin\StepsController::class, 'bulkDelete'])->name('steps.bulkDelete')->middleware('permission:steps_delete');
        Route::post('/steps/changeStatus', [\App\Http\Controllers\Admin\StepsController::class, 'changeStatus'])->name('steps.changeStatus')->middleware('permission:steps_edit');

        //        reviews
        Route::resource('/reviews', \App\Http\Controllers\Admin\ReviewsController::class)->names('reviews')->except('show')->middleware('permission:reviews_list');
        Route::get('/reviews/data', [\App\Http\Controllers\Admin\ReviewsController::class, 'getData'])->name('reviews.data')->middleware('permission:reviews_list');
        Route::post('/reviews/bulkDelete', [\App\Http\Controllers\Admin\ReviewsController::class, 'bulkDelete'])->name('reviews.bulkDelete')->middleware('permission:reviews_delete');
        Route::post('/reviews/changeStatus', [\App\Http\Controllers\Admin\ReviewsController::class, 'changeStatus'])->name('reviews.changeStatus')->middleware('permission:reviews_edit');

        Route::resource('/pages', \App\Http\Controllers\Admin\PagesController::class)->names('pages')->only(['edit', 'update'])->middleware('permission:pages_edit');

        Route::get('/notifications/renderNotification', [\App\Http\Controllers\Admin\NotificationsController::class, 'renderNotification'])->name('notifications.renderNotification')->middleware('permission:notifications_create');
        Route::post('/notifications/sendNotification', [\App\Http\Controllers\Admin\NotificationsController::class, 'sendNotification'])->name('notifications.sendNotification')->middleware('permission:notifications_create');

    });

    Route::get('/orders/data', [\App\Http\Controllers\Admin\OrdersController::class, 'getData'])->name('orders.data')->middleware('permission:orders_list');
    Route::resource('/orders', \App\Http\Controllers\Admin\OrdersController::class)->names('orders')->only(['index', 'show'])->middleware('permission:orders_list');
    Route::post('/orders/changeStatus/{id}', [\App\Http\Controllers\Admin\OrdersController::class, 'changeStatus'])->name('orders.changeStatus')->middleware('permission:orders_edit');
    Route::post('/orders/change/system_notes/{id}', [\App\Http\Controllers\Admin\OrdersController::class, 'changeSystemNotes'])->name('orders.update.system_notes')->middleware('permission:orders_edit');
    Route::get('/orders/invoice/{id}', [\App\Http\Controllers\Admin\OrdersController::class, 'invoice'])->name('orders.invoice')->middleware('permission:orders_view');

    Route::get('/direct_orders/data', [\App\Http\Controllers\Admin\DirectOrdersController::class, 'getData'])->name('direct_orders.data')->middleware('permission:direct_orders_list');
    Route::resource('direct_orders', \App\Http\Controllers\Admin\DirectOrdersController::class)->names('direct_orders')->only(['index', 'show'])->middleware('permission:direct_orders_list');
    Route::post('/direct_orders/reply/{id}', [\App\Http\Controllers\Admin\DirectOrdersController::class, 'reply'])->name('direct_orders.reply')->middleware('permission:direct_orders_edit');
    Route::delete('/direct_orders/destroy/{id}', [\App\Http\Controllers\Admin\DirectOrdersController::class, 'destroy'])->name('direct_orders.destroy')->middleware('permission:direct_orders_delete');
    Route::post('/direct_orders/bulkDelete', [\App\Http\Controllers\Admin\DirectOrdersController::class, 'bulkDelete'])->name('direct_orders.bulkDelete')->middleware('permission:direct_orders_delete');

    Route::get('/get_prices/data', [\App\Http\Controllers\Admin\GetPriceOrdersController::class, 'getData'])->name('get_prices.data')->middleware('permission:get_prices_list');
    Route::resource('get_prices', \App\Http\Controllers\Admin\GetPriceOrdersController::class)->names('get_prices')->only(['index', 'show'])->middleware('permission:get_prices_list');
    Route::post('/get_prices/reply/{id}', [\App\Http\Controllers\Admin\GetPriceOrdersController::class, 'reply'])->name('get_prices.reply')->middleware('permission:get_prices_edit');
    Route::delete('/get_prices/destroy/{id}', [\App\Http\Controllers\Admin\GetPriceOrdersController::class, 'destroy'])->name('get_prices.destroy')->middleware('permission:get_prices_delete');
    Route::post('/get_prices/bulkDelete', [\App\Http\Controllers\Admin\GetPriceOrdersController::class, 'bulkDelete'])->name('get_prices.bulkDelete')->middleware('permission:get_prices_delete');

    Route::get('/profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit')->middleware('permission:profile_view');
    Route::post('/profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update')->middleware('permission:profile_edit');

    Route::resource('/point_settings', \App\Http\Controllers\Admin\PointSettingsController::class)->names('point_settings')->except('show')->middleware('permission:point_settings_list');
    Route::get('/point_settings/data', [\App\Http\Controllers\Admin\PointSettingsController::class, 'getData'])->name('point_settings.data')->middleware('permission:point_settings_list');
    Route::post('/point_settings/bulkDelete', [\App\Http\Controllers\Admin\PointSettingsController::class, 'bulkDelete'])->name('point_settings.bulkDelete')->middleware('permission:point_settings_delete');
    Route::post('/point_settings/bulkChangeStatus', [\App\Http\Controllers\Admin\PointSettingsController::class, 'bulkChangeStatus'])->name('point_settings.bulkChangeStatus')->middleware('permission:point_settings_edit');
    Route::post('/point_settings/changeStatus', [\App\Http\Controllers\Admin\PointSettingsController::class, 'changeStatus'])->name('point_settings.changeStatus')->middleware('permission:point_settings_edit');

    Route::get('/settings/edit', [\App\Http\Controllers\Admin\SettingsController::class, 'edit'])->name('settings.edit')->middleware('permission:settings_view');
    Route::post('/settings/update', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update')->middleware('permission:settings_edit');

//
    Route::resource('/roles', \App\Http\Controllers\Admin\RolesController::class)->names('roles')->except('show')->middleware('permission:roles_list');
    Route::get('/roles/data', [\App\Http\Controllers\Admin\RolesController::class, 'getData'])->name('roles.data')->middleware('permission:roles_list');
    Route::post('/roles/bulkDelete', [\App\Http\Controllers\Admin\RolesController::class, 'bulkDelete'])->name('roles.bulkDelete')->middleware('permission:roles_delete');
    Route::post('/roles/changeStatus', [\App\Http\Controllers\Admin\RolesController::class, 'changeStatus'])->name('roles.changeStatus')->middleware('permission:roles_edit');

    Route::resource('/admins', \App\Http\Controllers\Admin\AdminsController::class)->names('admins')->except('show')->middleware('permission:admins_list');
    Route::get('/admins/data', [\App\Http\Controllers\Admin\AdminsController::class, 'getData'])->name('admins.data')->middleware('permission:admins_list');
    Route::post('/admins/bulkDelete', [\App\Http\Controllers\Admin\AdminsController::class, 'bulkDelete'])->name('admins.bulkDelete')->middleware('permission:admins_delete');
    Route::post('/admins/changeStatus', [\App\Http\Controllers\Admin\AdminsController::class, 'changeStatus'])->name('admins.changeStatus')->middleware('permission:admins_edit');

});

//middleware group printer
Route::middleware(['admin', 'printer'])->group(function () {
    Route::resource('/items', \App\Http\Controllers\Admin\ItemsController::class)->names('items')->except('show')->middleware('permission:items_list');

    Route::get('/items/data', [\App\Http\Controllers\Admin\ItemsController::class, 'getData'])->name('items.data')->middleware('permission:items_list');

    Route::post('/items/bulkDelete', [\App\Http\Controllers\Admin\ItemsController::class, 'bulkDelete'])->name('items.bulkDelete')->middleware('permission:items_delete');

    Route::post('/items/bulkChangeStatus', [\App\Http\Controllers\Admin\ItemsController::class, 'bulkChangeStatus'])->name('items.bulkChangeStatus')->middleware('permission:items_edit');

    Route::post('/items/changeStatus', [\App\Http\Controllers\Admin\ItemsController::class, 'changeStatus'])->name('items.changeStatus')->middleware('permission:items_edit');

    Route::resource('/supplies', \App\Http\Controllers\Admin\SuppliesController::class)->names('supplies')->except('show')->middleware('permission:supplies_list');

    Route::get('/supplies/data', [\App\Http\Controllers\Admin\SuppliesController::class, 'getData'])->name('supplies.data')->middleware('permission:supplies_list');

    Route::post('/supplies/bulkDelete', [\App\Http\Controllers\Admin\SuppliesController::class, 'bulkDelete'])->name('supplies.bulkDelete')->middleware('permission:supplies_delete');

    Route::post('/supplies/bulkChangeStatus', [\App\Http\Controllers\Admin\SuppliesController::class, 'bulkChangeStatus'])->name('supplies.bulkChangeStatus')->middleware('permission:supplies_edit');

    Route::post('/supplies/changeStatus', [\App\Http\Controllers\Admin\SuppliesController::class, 'changeStatus'])->name('supplies.changeStatus')->middleware('permission:supplies_edit');

    Route::get('/quotations/data', [\App\Http\Controllers\Admin\QuotationController::class, 'getData'])->name('quotations.data')->middleware('permission:quotations_list');
    Route::get('/quotations/{quotation}/pdf', [\App\Http\Controllers\Admin\QuotationController::class, 'generatePdf'])->name('quotations.pdf')->middleware('permission:quotations_view');
    //convertToInvoice
    Route::get('/quotations/{quotation}/convertToInvoice', [\App\Http\Controllers\Admin\QuotationController::class, 'convertToInvoice'])->name('quotations.convertToInvoice')->middleware('permission:quotations_edit');
    Route::resource('/quotations', \App\Http\Controllers\Admin\QuotationController::class)->middleware('permission:quotations_list');

    Route::get('/invoices/data', [\App\Http\Controllers\Admin\InvoiceController::class, 'getData'])->name('invoices.data')->middleware('permission:quotations_list');
    Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\Admin\InvoiceController::class, 'generatePdf'])->name('invoices.pdf')->middleware('permission:quotations_view');
    Route::resource('/invoices', \App\Http\Controllers\Admin\InvoiceController::class)->middleware('permission:quotations_list');
    //PrintService

    Route::get('/print-services/data', [\App\Http\Controllers\Admin\PrintServiceController::class, 'getData'])->name('print-services.data')->middleware('permission:print_services_list');

    Route::post('/print-services/bulkDelete', [\App\Http\Controllers\Admin\PrintServiceController::class, 'bulkDelete'])->name('print-services  .bulkDelete')->middleware('permission:print_services_delete');

    Route::post('/print-services/bulkChangeStatus', [\App\Http\Controllers\Admin\PrintServiceController::class, 'bulkChangeStatus'])->name('print-services.bulkChangeStatus')->middleware('permission:print_services_edit');
    Route::resource('/print-services', \App\Http\Controllers\Admin\PrintServiceController::class)->names('print-services')->except('show')->middleware('permission:print_services_list');

    Route::get('/print-settings/edit', [\App\Http\Controllers\Admin\PrintSettingsController::class, 'edit'])->name('printSettings.edit')->middleware('permission:print_settings_view');
    Route::post('/print-settings/update', [\App\Http\Controllers\Admin\PrintSettingsController::class, 'update'])->name('printSettings.update')->middleware('permission:print_settings_edit');

    Route::get('/packages/data', [\App\Http\Controllers\Admin\PackageController::class, 'getData'])->name('packages.data')->middleware('permission:packages_list');
    Route::resource('/packages', \App\Http\Controllers\Admin\PackageController::class)->names('packages')->except('show')->middleware('permission:packages_list');
});

Route::get('/sendNotification', function () {
    dd(sendNotification('f8Po2qOxpETJlNj3MiuQ6E:APA91bGZr1njZQ1mrwBpnSP2_l82Cdf7KBvn70IkeH8H9jaSmU2SOqYEk8RQoRO8Vs3yimSpwZc3qOL9jqO0EHqbTW_l4B6A4HEV77LydKtIowNyqApLSI0i5_OhH2YNT-zPZBDKjQgr', 'new notification', 'test message', 'general'));
});

Route::group(['prefix' => "payment"], function () {
    Route::get('/callback', [PaymentController::class, 'callback']);
    Route::get('/failed', [PaymentController::class, 'failed']);
});
