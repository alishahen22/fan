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

        Route::resource('/attributes', \App\Http\Controllers\Admin\AttributesController::class)->names('attributes')->except('show');
        Route::get('/attributes/data', [\App\Http\Controllers\Admin\AttributesController::class, 'getData'])->name('attributes.data');
        Route::post('/attributes/bulkDelete', [\App\Http\Controllers\Admin\AttributesController::class, 'bulkDelete'])->name('attributes.bulkDelete');
        Route::post('/attributes/bulkChangeStatus', [\App\Http\Controllers\Admin\AttributesController::class, 'bulkChangeStatus'])->name('attributes.bulkChangeStatus');
        Route::post('/attributes/changeStatus', [\App\Http\Controllers\Admin\AttributesController::class, 'changeStatus'])->name('attributes.changeStatus');

        // Products
        Route::resource('/products', \App\Http\Controllers\Admin\ProductsController::class)->names('products')->except('show');
        Route::get('/products/data', [\App\Http\Controllers\Admin\ProductsController::class, 'getData'])->name('products.data');
        Route::post('/products/bulkDelete', [\App\Http\Controllers\Admin\ProductsController::class, 'bulkDelete'])->name('products.bulkDelete');
        Route::post('/products/bulkChangeStatus', [\App\Http\Controllers\Admin\ProductsController::class, 'bulkChangeStatus'])->name('products.bulkChangeStatus');
        Route::post('/products/changeStatus', [\App\Http\Controllers\Admin\ProductsController::class, 'changeStatus'])->name('products.changeStatus');
        Route::post('/products/UploadGallery', [\App\Http\Controllers\Admin\ProductsController::class, 'UploadGallery'])->name('products.uploadGallery');
        Route::post('/products/removeGallery', [\App\Http\Controllers\Admin\ProductsController::class, 'removeGallery'])->name('products.removeGallery');

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

        Route::resource('/cities', \App\Http\Controllers\Admin\CitiesController::class)->names('cities')->except('show');
        Route::get('/cities/data', [\App\Http\Controllers\Admin\CitiesController::class, 'getData'])->name('cities.data');
        Route::post('/cities/bulkDelete', [\App\Http\Controllers\Admin\CitiesController::class, 'bulkDelete'])->name('cities.bulkDelete');
        Route::post('/cities/bulkChangeStatus', [\App\Http\Controllers\Admin\CitiesController::class, 'bulkChangeStatus'])->name('cities.bulkChangeStatus');
        Route::post('/cities/changeStatus', [\App\Http\Controllers\Admin\CitiesController::class, 'changeStatus'])->name('cities.changeStatus');

        Route::get('/cities/areas/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'index'])->name('areas.index');
        Route::get('/areas/create/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'create'])->name('areas.create');
        Route::delete('/areas/destroy/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'destroy'])->name('areas.destroy');
        Route::post('/areas/store/new/area/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'store'])->name('areas.store');
        Route::get('/areas/edit/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'edit'])->name('areas.edit');
        Route::put('/areas/update/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'update'])->name('areas.update');
        Route::get('/areas/data/{id}', [\App\Http\Controllers\Admin\AreaController::class, 'getData'])->name('areas.data');
        Route::post('/areas/bulkDelete', [\App\Http\Controllers\Admin\AreaController::class, 'bulkDelete'])->name('areas.bulkDelete');
        Route::post('/areas/bulkChangeStatus', [\App\Http\Controllers\Admin\AreaController::class, 'bulkChangeStatus'])->name('areas.bulkChangeStatus');
        Route::post('/areas/changeStatus', [\App\Http\Controllers\Admin\AreaController::class, 'changeStatus'])->name('areas.changeStatus');

        Route::resource('/splashes', \App\Http\Controllers\Admin\SplashesController::class)->names('splashes')->except('show');
        Route::get('/splashes/data', [\App\Http\Controllers\Admin\SplashesController::class, 'getData'])->name('splashes.data');
        Route::post('/splashes/bulkDelete', [\App\Http\Controllers\Admin\SplashesController::class, 'bulkDelete'])->name('splashes.bulkDelete');
        Route::post('/splashes/bulkChangeStatus', [\App\Http\Controllers\Admin\SplashesController::class, 'bulkChangeStatus'])->name('splashes.bulkChangeStatus');
        Route::post('/splashes/changeStatus', [\App\Http\Controllers\Admin\SplashesController::class, 'changeStatus'])->name('splashes.changeStatus');

        Route::resource('/offers', \App\Http\Controllers\Admin\OffersController::class)->names('offers')->except('show');
        Route::get('/offers/data', [\App\Http\Controllers\Admin\OffersController::class, 'getData'])->name('offers.data');
        Route::post('/offers/bulkDelete', [\App\Http\Controllers\Admin\OffersController::class, 'bulkDelete'])->name('offers.bulkDelete');
        Route::post('/offers/bulkChangeStatus', [\App\Http\Controllers\Admin\OffersController::class, 'bulkChangeStatus'])->name('offers.bulkChangeStatus');
        Route::post('/offers/changeStatus', [\App\Http\Controllers\Admin\OffersController::class, 'changeStatus'])->name('offers.changeStatus');

        Route::resource('/branches', \App\Http\Controllers\Admin\BranchesController::class)->names('branches')->except('show');
        Route::get('/branches/data', [\App\Http\Controllers\Admin\BranchesController::class, 'getData'])->name('branches.data');
        Route::post('/branches/bulkDelete', [\App\Http\Controllers\Admin\BranchesController::class, 'bulkDelete'])->name('branches.bulkDelete');
        Route::post('/branches/bulkChangeStatus', [\App\Http\Controllers\Admin\BranchesController::class, 'bulkChangeStatus'])->name('branches.bulkChangeStatus');
        Route::post('/branches/changeStatus', [\App\Http\Controllers\Admin\BranchesController::class, 'changeStatus'])->name('branches.changeStatus');
        Route::get('/branches/delete/image/{id}', [\App\Http\Controllers\Admin\BranchesController::class, 'deleteImage'])->name('branches.images.delete');

        Route::resource('/articles', \App\Http\Controllers\Admin\ArticlesController::class)->names('articles')->except('show');
        Route::get('/articles/data', [\App\Http\Controllers\Admin\ArticlesController::class, 'getData'])->name('articles.data');
        Route::post('/articles/bulkDelete', [\App\Http\Controllers\Admin\ArticlesController::class, 'bulkDelete'])->name('articles.bulkDelete');
        Route::post('/articles/bulkChangeStatus', [\App\Http\Controllers\Admin\ArticlesController::class, 'bulkChangeStatus'])->name('articles.bulkChangeStatus');
        Route::post('/articles/changeStatus', [\App\Http\Controllers\Admin\ArticlesController::class, 'changeStatus'])->name('articles.changeStatus');

        Route::resource('/vouchers', \App\Http\Controllers\Admin\VouchersController::class)->names('vouchers')->except('show');
        Route::get('/vouchers/data', [\App\Http\Controllers\Admin\VouchersController::class, 'getData'])->name('vouchers.data');
        Route::post('/vouchers/bulkDelete', [\App\Http\Controllers\Admin\VouchersController::class, 'bulkDelete'])->name('vouchers.bulkDelete');
        Route::post('/vouchers/changeStatus', [\App\Http\Controllers\Admin\VouchersController::class, 'changeStatus'])->name('vouchers.changeStatus');
        Route::post('/vouchers/changeFirstOrder', [\App\Http\Controllers\Admin\VouchersController::class, 'changeFirstOrder'])->name('vouchers.changeFirstOrder');

        Route::resource('contacts', \App\Http\Controllers\Admin\ContactsController::class)->names('contacts')->except('show');
        Route::get('/contacts/data', [\App\Http\Controllers\Admin\ContactsController::class, 'getData'])->name('contacts.data');
        Route::post('/contacts/bulkDelete', [\App\Http\Controllers\Admin\ContactsController::class, 'bulkDelete'])->name('contacts.bulkDelete');

        Route::resource('/sliders', \App\Http\Controllers\Admin\SlidersController::class)->names('sliders')->except('show');
        Route::get('/sliders/data', [\App\Http\Controllers\Admin\SlidersController::class, 'getData'])->name('sliders.data');
        Route::post('/sliders/bulkDelete', [\App\Http\Controllers\Admin\SlidersController::class, 'bulkDelete'])->name('sliders.bulkDelete');
        Route::post('/sliders/changeStatus', [\App\Http\Controllers\Admin\SlidersController::class, 'changeStatus'])->name('sliders.changeStatus');

//        banners
        Route::resource('/banners', \App\Http\Controllers\Admin\BannersController::class)->names('banners')->except('show');
        Route::get('/banners/data', [\App\Http\Controllers\Admin\BannersController::class, 'getData'])->name('banners.data');
        Route::post('/banners/bulkDelete', [\App\Http\Controllers\Admin\BannersController::class, 'bulkDelete'])->name('banners.bulkDelete');
        Route::post('/banners/changeStatus', [\App\Http\Controllers\Admin\BannersController::class, 'changeStatus'])->name('banners.changeStatus');

        //        steps
        Route::resource('/steps', \App\Http\Controllers\Admin\StepsController::class)->names('steps')->except('show');
        Route::get('/steps/data', [\App\Http\Controllers\Admin\StepsController::class, 'getData'])->name('steps.data');
        Route::post('/steps/bulkDelete', [\App\Http\Controllers\Admin\StepsController::class, 'bulkDelete'])->name('steps.bulkDelete');
        Route::post('/steps/changeStatus', [\App\Http\Controllers\Admin\StepsController::class, 'changeStatus'])->name('steps.changeStatus');

        //        reviews
        Route::resource('/reviews', \App\Http\Controllers\Admin\ReviewsController::class)->names('reviews')->except('show');
        Route::get('/reviews/data', [\App\Http\Controllers\Admin\ReviewsController::class, 'getData'])->name('reviews.data');
        Route::post('/reviews/bulkDelete', [\App\Http\Controllers\Admin\ReviewsController::class, 'bulkDelete'])->name('reviews.bulkDelete');
        Route::post('/reviews/changeStatus', [\App\Http\Controllers\Admin\ReviewsController::class, 'changeStatus'])->name('reviews.changeStatus');

        Route::resource('/pages', \App\Http\Controllers\Admin\PagesController::class)->names('pages')->only(['edit', 'update']);

        Route::get('/notifications/renderNotification', [\App\Http\Controllers\Admin\NotificationsController::class, 'renderNotification'])->name('notifications.renderNotification');
        Route::post('/notifications/sendNotification', [\App\Http\Controllers\Admin\NotificationsController::class, 'sendNotification'])->name('notifications.sendNotification');

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

    Route::get('/get_prices/data', [\App\Http\Controllers\Admin\GetPriceOrdersController::class, 'getData'])->name('get_prices.data');
    Route::resource('get_prices', \App\Http\Controllers\Admin\GetPriceOrdersController::class)->names('get_prices')->only(['index', 'show']);
    Route::post('/get_prices/reply/{id}', [\App\Http\Controllers\Admin\GetPriceOrdersController::class, 'reply'])->name('get_prices.reply');
    Route::delete('/get_prices/destroy/{id}', [\App\Http\Controllers\Admin\GetPriceOrdersController::class, 'destroy'])->name('get_prices.destroy');
    Route::post('/get_prices/bulkDelete', [\App\Http\Controllers\Admin\GetPriceOrdersController::class, 'bulkDelete'])->name('get_prices.bulkDelete');

    Route::get('/profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    Route::resource('/point_settings', \App\Http\Controllers\Admin\PointSettingsController::class)->names('point_settings')->except('show');
    Route::get('/point_settings/data', [\App\Http\Controllers\Admin\PointSettingsController::class, 'getData'])->name('point_settings.data');
    Route::post('/point_settings/bulkDelete', [\App\Http\Controllers\Admin\PointSettingsController::class, 'bulkDelete'])->name('point_settings.bulkDelete');
    Route::post('/point_settings/bulkChangeStatus', [\App\Http\Controllers\Admin\PointSettingsController::class, 'bulkChangeStatus'])->name('point_settings.bulkChangeStatus');
    Route::post('/point_settings/changeStatus', [\App\Http\Controllers\Admin\PointSettingsController::class, 'changeStatus'])->name('point_settings.changeStatus');

    Route::get('/settings/edit', [\App\Http\Controllers\Admin\SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('/settings/update', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

//
    Route::resource('/roles', \App\Http\Controllers\Admin\RolesController::class)->names('roles')->except('show');
    Route::get('/roles/data', [\App\Http\Controllers\Admin\RolesController::class, 'getData'])->name('roles.data');
    Route::post('/roles/bulkDelete', [\App\Http\Controllers\Admin\RolesController::class, 'bulkDelete'])->name('roles.bulkDelete');
    Route::post('/roles/changeStatus', [\App\Http\Controllers\Admin\RolesController::class, 'changeStatus'])->name('roles.changeStatus');

    Route::resource('/admins', \App\Http\Controllers\Admin\AdminsController::class)->names('admins')->except('show');
    Route::get('/admins/data', [\App\Http\Controllers\Admin\AdminsController::class, 'getData'])->name('admins.data');
    Route::post('/admins/bulkDelete', [\App\Http\Controllers\Admin\AdminsController::class, 'bulkDelete'])->name('admins.bulkDelete');
    Route::post('/admins/changeStatus', [\App\Http\Controllers\Admin\AdminsController::class, 'changeStatus'])->name('admins.changeStatus');

});

//middleware group printer
Route::middleware(['admin', 'printer'])->group(function () {
    Route::resource('/items', \App\Http\Controllers\Admin\ItemsController::class)->names('items')->except('show');

    Route::get('/items/data', [\App\Http\Controllers\Admin\ItemsController::class, 'getData'])->name('items.data');

    Route::post('/items/bulkDelete', [\App\Http\Controllers\Admin\ItemsController::class, 'bulkDelete'])->name('items.bulkDelete');

    Route::post('/items/bulkChangeStatus', [\App\Http\Controllers\Admin\ItemsController::class, 'bulkChangeStatus'])->name('items.bulkChangeStatus');

    Route::post('/items/changeStatus', [\App\Http\Controllers\Admin\ItemsController::class, 'changeStatus'])->name('items.changeStatus');
    Route::resource('/items', \App\Http\Controllers\Admin\ItemsController::class)->names('items')->except('show');

    Route::get('/items/data', [\App\Http\Controllers\Admin\ItemsController::class, 'getData'])->name('items.data');

    Route::post('/items/bulkDelete', [\App\Http\Controllers\Admin\ItemsController::class, 'bulkDelete'])->name('items.bulkDelete');

    Route::post('/items/bulkChangeStatus', [\App\Http\Controllers\Admin\ItemsController::class, 'bulkChangeStatus'])->name('items.bulkChangeStatus');

    Route::post('/items/changeStatus', [\App\Http\Controllers\Admin\ItemsController::class, 'changeStatus'])->name('items.changeStatus');

    Route::resource('/supplies', \App\Http\Controllers\Admin\SuppliesController::class)->names('supplies')->except('show');

    Route::get('/supplies/data', [\App\Http\Controllers\Admin\SuppliesController::class, 'getData'])->name('supplies.data');

    Route::post('/supplies/bulkDelete', [\App\Http\Controllers\Admin\SuppliesController::class, 'bulkDelete'])->name('supplies.bulkDelete');

    Route::post('/supplies/bulkChangeStatus', [\App\Http\Controllers\Admin\SuppliesController::class, 'bulkChangeStatus'])->name('supplies.bulkChangeStatus');

    Route::post('/supplies/changeStatus', [\App\Http\Controllers\Admin\SuppliesController::class, 'changeStatus'])->name('supplies.changeStatus');

    Route::get('/quotations/data', [\App\Http\Controllers\Admin\QuotationController::class, 'getData'])->name('quotations.data');
    Route::get('/quotations/{quotation}/pdf', [\App\Http\Controllers\Admin\QuotationController::class, 'generatePdf'])->name('quotations.pdf');
    //convertToInvoice
    Route::get('/quotations/{quotation}/convertToInvoice', [\App\Http\Controllers\Admin\QuotationController::class, 'convertToInvoice'])->name('quotations.convertToInvoice');
    Route::resource('/quotations', \App\Http\Controllers\Admin\QuotationController::class);

    Route::get('/invoices/data', [\App\Http\Controllers\Admin\InvoiceController::class, 'getData'])->name('invoices.data');
    Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\Admin\InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
    Route::resource('/invoices', \App\Http\Controllers\Admin\InvoiceController::class);
    //PrintService

    Route::get('/print-services/data', [\App\Http\Controllers\Admin\PrintServiceController::class, 'getData'])->name('print-services.data');

    Route::post('/print-services/bulkDelete', [\App\Http\Controllers\Admin\PrintServiceController::class, 'bulkDelete'])->name('print-services  .bulkDelete');

    Route::post('/print-services/bulkChangeStatus', [\App\Http\Controllers\Admin\PrintServiceController::class, 'bulkChangeStatus'])->name('print-services.bulkChangeStatus');
    Route::resource('/print-services', \App\Http\Controllers\Admin\PrintServiceController::class)->names('print-services')->except('show');

    Route::get('/print-settings/edit', [\App\Http\Controllers\Admin\PrintSettingsController::class, 'edit'])->name('printSettings.edit');
    Route::post('/print-settings/update', [\App\Http\Controllers\Admin\PrintSettingsController::class, 'update'])->name('printSettings.update');

    Route::get('/packages/data', [\App\Http\Controllers\Admin\PackageController::class, 'getData'])->name('packages.data');
    Route::resource('/packages', \App\Http\Controllers\Admin\PackageController::class)->names('packages')->except('show');
});

Route::get('/sendNotification', function () {
    dd(sendNotification('f8Po2qOxpETJlNj3MiuQ6E:APA91bGZr1njZQ1mrwBpnSP2_l82Cdf7KBvn70IkeH8H9jaSmU2SOqYEk8RQoRO8Vs3yimSpwZc3qOL9jqO0EHqbTW_l4B6A4HEV77LydKtIowNyqApLSI0i5_OhH2YNT-zPZBDKjQgr', 'new notification', 'test message', 'general'));
});

Route::group(['prefix' => "payment"], function () {
    Route::get('/callback', [PaymentController::class, 'callback']);
    Route::get('/failed', [PaymentController::class, 'failed']);
});
