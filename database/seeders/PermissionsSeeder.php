<?php
namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding permissions...');

        $permissions = [
            // Roles Permissions
            [
                'name'            => 'roles_list',
                'display_name_ar' => 'قائمة الأدوار',
                'display_name_en' => 'List Roles',
                'model'           => \App\Models\Role::class,
            ],
            [
                'name'            => 'roles_create',
                'display_name_ar' => 'إنشاء دور',
                'display_name_en' => 'Create Role',
                'model'           => \App\Models\Role::class,
            ],
            [
                'name'            => 'roles_edit',
                'display_name_ar' => 'تعديل دور',
                'display_name_en' => 'Edit Role',
                'model'           => \App\Models\Role::class,
            ],
            [
                'name'            => 'roles_delete',
                'display_name_ar' => 'حذف دور',
                'display_name_en' => 'Delete Role',
                'model'           => \App\Models\Role::class,
            ],
            [
                'name'            => 'roles_view',
                'display_name_ar' => 'عرض دور',
                'display_name_en' => 'View Role',
                'model'           => \App\Models\Role::class,
            ],

            // Admins Permissions
            [
                'name'            => 'admins_list',
                'display_name_ar' => 'قائمة المشرفين',
                'display_name_en' => 'List Admins',
                'model'           => \App\Models\Admin::class,
            ],
            [
                'name'            => 'admins_create',
                'display_name_ar' => 'إنشاء مشرف',
                'display_name_en' => 'Create Admin',
                'model'           => \App\Models\Admin::class,
            ],
            [
                'name'            => 'admins_edit',
                'display_name_ar' => 'تعديل مشرف',
                'display_name_en' => 'Edit Admin',
                'model'           => \App\Models\Admin::class,
            ],
            [
                'name'            => 'admins_delete',
                'display_name_ar' => 'حذف مشرف',
                'display_name_en' => 'Delete Admin',
                'model'           => \App\Models\Admin::class,
            ],
            [
                'name'            => 'admins_view',
                'display_name_ar' => 'عرض مشرف',
                'display_name_en' => 'View Admin',
                'model'           => \App\Models\Admin::class,
            ],

            // Users Permissions
            [
                'name'            => 'users_list',
                'display_name_ar' => 'قائمة المستخدمين',
                'display_name_en' => 'List Users',
                'model'           => \App\Models\User::class,
            ],
            [
                'name'            => 'users_create',
                'display_name_ar' => 'إنشاء مستخدم',
                'display_name_en' => 'Create User',
                'model'           => \App\Models\User::class,
            ],
            [
                'name'            => 'users_edit',
                'display_name_ar' => 'تعديل مستخدم',
                'display_name_en' => 'Edit User',
                'model'           => \App\Models\User::class,
            ],
            [
                'name'            => 'users_delete',
                'display_name_ar' => 'حذف مستخدم',
                'display_name_en' => 'Delete User',
                'model'           => \App\Models\User::class,
            ],
            [
                'name'            => 'users_view',
                'display_name_ar' => 'عرض مستخدم',
                'display_name_en' => 'View User',
                'model'           => \App\Models\User::class,
            ],

            // Categories Permissions
            [
                'name'            => 'categories_list',
                'display_name_ar' => 'قائمة الفئات',
                'display_name_en' => 'List Categories',
                'model'           => \App\Models\Category::class,
            ],
            [
                'name'            => 'categories_create',
                'display_name_ar' => 'إنشاء فئة',
                'display_name_en' => 'Create Category',
                'model'           => \App\Models\Category::class,
            ],
            [
                'name'            => 'categories_edit',
                'display_name_ar' => 'تعديل فئة',
                'display_name_en' => 'Edit Category',
                'model'           => \App\Models\Category::class,
            ],
            [
                'name'            => 'categories_delete',
                'display_name_ar' => 'حذف فئة',
                'display_name_en' => 'Delete Category',
                'model'           => \App\Models\Category::class,
            ],
            [
                'name'            => 'categories_view',
                'display_name_ar' => 'عرض فئة',
                'display_name_en' => 'View Category',
                'model'           => \App\Models\Category::class,
            ],

            // Orders Permissions
            [
                'name'            => 'orders_list',
                'display_name_ar' => 'قائمة الطلبات',
                'display_name_en' => 'List Orders',
                'model'           => \App\Models\Order::class,
            ],
            [
                'name'            => 'orders_create',
                'display_name_ar' => 'إنشاء طلب',
                'display_name_en' => 'Create Order',
                'model'           => \App\Models\Order::class,
            ],
            [
                'name'            => 'orders_edit',
                'display_name_ar' => 'تعديل طلب',
                'display_name_en' => 'Edit Order',
                'model'           => \App\Models\Order::class,
            ],
            [
                'name'            => 'orders_delete',
                'display_name_ar' => 'حذف طلب',
                'display_name_en' => 'Delete Order',
                'model'           => \App\Models\Order::class,
            ],
            [
                'name'            => 'orders_view',
                'display_name_ar' => 'عرض طلب',
                'display_name_en' => 'View Order',
                'model'           => \App\Models\Order::class,
            ],

            // Items Permissions
            [
                'name'            => 'items_list',
                'display_name_ar' => 'قائمة المنتجات',
                'display_name_en' => 'List Items',
                'model'           => \App\Models\Item::class,
            ],
            [
                'name'            => 'items_create',
                'display_name_ar' => 'إنشاء منتج',
                'display_name_en' => 'Create Item',
                'model'           => \App\Models\Item::class,
            ],
            [
                'name'            => 'items_edit',
                'display_name_ar' => 'تعديل منتج',
                'display_name_en' => 'Edit Item',
                'model'           => \App\Models\Item::class,
            ],
            [
                'name'            => 'items_delete',
                'display_name_ar' => 'حذف منتج',
                'display_name_en' => 'Delete Item',
                'model'           => \App\Models\Item::class,
            ],
            [
                'name'            => 'items_view',
                'display_name_ar' => 'عرض منتج',
                'display_name_en' => 'View Item',
                'model'           => \App\Models\Item::class,
            ],

            // Settings Permissions
            [
                'name'            => 'settings_view',
                'display_name_ar' => 'عرض الإعدادات',
                'display_name_en' => 'View Settings',
                'model'           => \App\Models\Setting::class,
            ],
            [
                'name'            => 'settings_edit',
                'display_name_ar' => 'تعديل الإعدادات',
                'display_name_en' => 'Edit Settings',
                'model'           => \App\Models\Setting::class,
            ],

            // Products Permissions
            [
                'name'            => 'products_list',
                'display_name_ar' => 'قائمة المنتجات',
                'display_name_en' => 'List Products',
                'model'           => \App\Models\Product::class,
            ],
            [
                'name'            => 'products_create',
                'display_name_ar' => 'إنشاء منتج',
                'display_name_en' => 'Create Product',
                'model'           => \App\Models\Product::class,
            ],
            [
                'name'            => 'products_edit',
                'display_name_ar' => 'تعديل منتج',
                'display_name_en' => 'Edit Product',
                'model'           => \App\Models\Product::class,
            ],
            [
                'name'            => 'products_delete',
                'display_name_ar' => 'حذف منتج',
                'display_name_en' => 'Delete Product',
                'model'           => \App\Models\Product::class,
            ],
            [
                'name'            => 'products_view',
                'display_name_ar' => 'عرض منتج',
                'display_name_en' => 'View Product',
                'model'           => \App\Models\Product::class,
            ],

            // Attributes Permissions
            [
                'name'            => 'attributes_list',
                'display_name_ar' => 'قائمة الخصائص',
                'display_name_en' => 'List Attributes',
                'model'           => \App\Models\Attribute::class,
            ],
            [
                'name'            => 'attributes_create',
                'display_name_ar' => 'إنشاء خاصية',
                'display_name_en' => 'Create Attribute',
                'model'           => \App\Models\Attribute::class,
            ],
            [
                'name'            => 'attributes_edit',
                'display_name_ar' => 'تعديل خاصية',
                'display_name_en' => 'Edit Attribute',
                'model'           => \App\Models\Attribute::class,
            ],
            [
                'name'            => 'attributes_delete',
                'display_name_ar' => 'حذف خاصية',
                'display_name_en' => 'Delete Attribute',
                'model'           => \App\Models\Attribute::class,
            ],
            [
                'name'            => 'attributes_view',
                'display_name_ar' => 'عرض خاصية',
                'display_name_en' => 'View Attribute',
                'model'           => \App\Models\Attribute::class,
            ],

            // Cities Permissions
            [
                'name'            => 'cities_list',
                'display_name_ar' => 'قائمة المدن',
                'display_name_en' => 'List Cities',
                'model'           => \App\Models\City::class,
            ],
            [
                'name'            => 'cities_create',
                'display_name_ar' => 'إنشاء مدينة',
                'display_name_en' => 'Create City',
                'model'           => \App\Models\City::class,
            ],
            [
                'name'            => 'cities_edit',
                'display_name_ar' => 'تعديل مدينة',
                'display_name_en' => 'Edit City',
                'model'           => \App\Models\City::class,
            ],
            [
                'name'            => 'cities_delete',
                'display_name_ar' => 'حذف مدينة',
                'display_name_en' => 'Delete City',
                'model'           => \App\Models\City::class,
            ],
            [
                'name'            => 'cities_view',
                'display_name_ar' => 'عرض مدينة',
                'display_name_en' => 'View City',
                'model'           => \App\Models\City::class,
            ],

            // Areas Permissions
            [
                'name'            => 'areas_list',
                'display_name_ar' => 'قائمة المناطق',
                'display_name_en' => 'List Areas',
                'model'           => \App\Models\Area::class,
            ],
            [
                'name'            => 'areas_create',
                'display_name_ar' => 'إنشاء منطقة',
                'display_name_en' => 'Create Area',
                'model'           => \App\Models\Area::class,
            ],
            [
                'name'            => 'areas_edit',
                'display_name_ar' => 'تعديل منطقة',
                'display_name_en' => 'Edit Area',
                'model'           => \App\Models\Area::class,
            ],
            [
                'name'            => 'areas_delete',
                'display_name_ar' => 'حذف منطقة',
                'display_name_en' => 'Delete Area',
                'model'           => \App\Models\Area::class,
            ],
            [
                'name'            => 'areas_view',
                'display_name_ar' => 'عرض منطقة',
                'display_name_en' => 'View Area',
                'model'           => \App\Models\Area::class,
            ],

            // Branches Permissions
            [
                'name'            => 'branches_list',
                'display_name_ar' => 'قائمة الفروع',
                'display_name_en' => 'List Branches',
                'model'           => \App\Models\Branch::class,
            ],
            [
                'name'            => 'branches_create',
                'display_name_ar' => 'إنشاء فرع',
                'display_name_en' => 'Create Branch',
                'model'           => \App\Models\Branch::class,
            ],
            [
                'name'            => 'branches_edit',
                'display_name_ar' => 'تعديل فرع',
                'display_name_en' => 'Edit Branch',
                'model'           => \App\Models\Branch::class,
            ],
            [
                'name'            => 'branches_delete',
                'display_name_ar' => 'حذف فرع',
                'display_name_en' => 'Delete Branch',
                'model'           => \App\Models\Branch::class,
            ],
            [
                'name'            => 'branches_view',
                'display_name_ar' => 'عرض فرع',
                'display_name_en' => 'View Branch',
                'model'           => \App\Models\Branch::class,
            ],

            // Sliders Permissions
            [
                'name'            => 'sliders_list',
                'display_name_ar' => 'قائمة الشرائح',
                'display_name_en' => 'List Sliders',
                'model'           => \App\Models\Slider::class,
            ],
            [
                'name'            => 'sliders_create',
                'display_name_ar' => 'إنشاء شريحة',
                'display_name_en' => 'Create Slider',
                'model'           => \App\Models\Slider::class,
            ],
            [
                'name'            => 'sliders_edit',
                'display_name_ar' => 'تعديل شريحة',
                'display_name_en' => 'Edit Slider',
                'model'           => \App\Models\Slider::class,
            ],
            [
                'name'            => 'sliders_delete',
                'display_name_ar' => 'حذف شريحة',
                'display_name_en' => 'Delete Slider',
                'model'           => \App\Models\Slider::class,
            ],
            [
                'name'            => 'sliders_view',
                'display_name_ar' => 'عرض شريحة',
                'display_name_en' => 'View Slider',
                'model'           => \App\Models\Slider::class,
            ],

            // Banners Permissions
            [
                'name'            => 'banners_list',
                'display_name_ar' => 'قائمة البانرات',
                'display_name_en' => 'List Banners',
                'model'           => \App\Models\Banner::class,
            ],
            [
                'name'            => 'banners_create',
                'display_name_ar' => 'إنشاء بانر',
                'display_name_en' => 'Create Banner',
                'model'           => \App\Models\Banner::class,
            ],
            [
                'name'            => 'banners_edit',
                'display_name_ar' => 'تعديل بانر',
                'display_name_en' => 'Edit Banner',
                'model'           => \App\Models\Banner::class,
            ],
            [
                'name'            => 'banners_delete',
                'display_name_ar' => 'حذف بانر',
                'display_name_en' => 'Delete Banner',
                'model'           => \App\Models\Banner::class,
            ],
            [
                'name'            => 'banners_view',
                'display_name_ar' => 'عرض بانر',
                'display_name_en' => 'View Banner',
                'model'           => \App\Models\Banner::class,
            ],

            // Offers Permissions
            [
                'name'            => 'offers_list',
                'display_name_ar' => 'قائمة العروض',
                'display_name_en' => 'List Offers',
                'model'           => \App\Models\Offer::class,
            ],
            [
                'name'            => 'offers_create',
                'display_name_ar' => 'إنشاء عرض',
                'display_name_en' => 'Create Offer',
                'model'           => \App\Models\Offer::class,
            ],
            [
                'name'            => 'offers_edit',
                'display_name_ar' => 'تعديل عرض',
                'display_name_en' => 'Edit Offer',
                'model'           => \App\Models\Offer::class,
            ],
            [
                'name'            => 'offers_delete',
                'display_name_ar' => 'حذف عرض',
                'display_name_en' => 'Delete Offer',
                'model'           => \App\Models\Offer::class,
            ],
            [
                'name'            => 'offers_view',
                'display_name_ar' => 'عرض عرض',
                'display_name_en' => 'View Offer',
                'model'           => \App\Models\Offer::class,
            ],

            // Articles Permissions
            [
                'name'            => 'articles_list',
                'display_name_ar' => 'قائمة المقالات',
                'display_name_en' => 'List Articles',
                'model'           => \App\Models\Article::class,
            ],
            [
                'name'            => 'articles_create',
                'display_name_ar' => 'إنشاء مقال',
                'display_name_en' => 'Create Article',
                'model'           => \App\Models\Article::class,
            ],
            [
                'name'            => 'articles_edit',
                'display_name_ar' => 'تعديل مقال',
                'display_name_en' => 'Edit Article',
                'model'           => \App\Models\Article::class,
            ],
            [
                'name'            => 'articles_delete',
                'display_name_ar' => 'حذف مقال',
                'display_name_en' => 'Delete Article',
                'model'           => \App\Models\Article::class,
            ],
            [
                'name'            => 'articles_view',
                'display_name_ar' => 'عرض مقال',
                'display_name_en' => 'View Article',
                'model'           => \App\Models\Article::class,
            ],

            // Vouchers Permissions
            [
                'name'            => 'vouchers_list',
                'display_name_ar' => 'قائمة القسائم',
                'display_name_en' => 'List Vouchers',
                'model'           => \App\Models\Voucher::class,
            ],
            [
                'name'            => 'vouchers_create',
                'display_name_ar' => 'إنشاء قسيمة',
                'display_name_en' => 'Create Voucher',
                'model'           => \App\Models\Voucher::class,
            ],
            [
                'name'            => 'vouchers_edit',
                'display_name_ar' => 'تعديل قسيمة',
                'display_name_en' => 'Edit Voucher',
                'model'           => \App\Models\Voucher::class,
            ],
            [
                'name'            => 'vouchers_delete',
                'display_name_ar' => 'حذف قسيمة',
                'display_name_en' => 'Delete Voucher',
                'model'           => \App\Models\Voucher::class,
            ],
            [
                'name'            => 'vouchers_view',
                'display_name_ar' => 'عرض قسيمة',
                'display_name_en' => 'View Voucher',
                'model'           => \App\Models\Voucher::class,
            ],

            // Contacts Permissions
            [
                'name'            => 'contacts_list',
                'display_name_ar' => 'قائمة جهات الاتصال',
                'display_name_en' => 'List Contacts',
                'model'           => \App\Models\Contact::class,
            ],
            [
                'name'            => 'contacts_create',
                'display_name_ar' => 'إنشاء جهة اتصال',
                'display_name_en' => 'Create Contact',
                'model'           => \App\Models\Contact::class,
            ],
            [
                'name'            => 'contacts_edit',
                'display_name_ar' => 'تعديل جهة اتصال',
                'display_name_en' => 'Edit Contact',
                'model'           => \App\Models\Contact::class,
            ],
            [
                'name'            => 'contacts_delete',
                'display_name_ar' => 'حذف جهة اتصال',
                'display_name_en' => 'Delete Contact',
                'model'           => \App\Models\Contact::class,
            ],
            [
                'name'            => 'contacts_view',
                'display_name_ar' => 'عرض جهة اتصال',
                'display_name_en' => 'View Contact',
                'model'           => \App\Models\Contact::class,
            ],

            // Splashes Permissions
            [
                'name'            => 'splashes_list',
                'display_name_ar' => 'قائمة شاشات البداية',
                'display_name_en' => 'List Splashes',
                'model'           => \App\Models\Splash::class,
            ],
            [
                'name'            => 'splashes_create',
                'display_name_ar' => 'إنشاء شاشة بداية',
                'display_name_en' => 'Create Splash',
                'model'           => \App\Models\Splash::class,
            ],
            [
                'name'            => 'splashes_edit',
                'display_name_ar' => 'تعديل شاشة بداية',
                'display_name_en' => 'Edit Splash',
                'model'           => \App\Models\Splash::class,
            ],
            [
                'name'            => 'splashes_delete',
                'display_name_ar' => 'حذف شاشة بداية',
                'display_name_en' => 'Delete Splash',
                'model'           => \App\Models\Splash::class,
            ],
            [
                'name'            => 'splashes_view',
                'display_name_ar' => 'عرض شاشة بداية',
                'display_name_en' => 'View Splash',
                'model'           => \App\Models\Splash::class,
            ],

            // Steps Permissions
            [
                'name'            => 'steps_list',
                'display_name_ar' => 'قائمة الخطوات',
                'display_name_en' => 'List Steps',
                'model'           => \App\Models\Step::class,
            ],
            [
                'name'            => 'steps_create',
                'display_name_ar' => 'إنشاء خطوة',
                'display_name_en' => 'Create Step',
                'model'           => \App\Models\Step::class,
            ],
            [
                'name'            => 'steps_edit',
                'display_name_ar' => 'تعديل خطوة',
                'display_name_en' => 'Edit Step',
                'model'           => \App\Models\Step::class,
            ],
            [
                'name'            => 'steps_delete',
                'display_name_ar' => 'حذف خطوة',
                'display_name_en' => 'Delete Step',
                'model'           => \App\Models\Step::class,
            ],
            [
                'name'            => 'steps_view',
                'display_name_ar' => 'عرض خطوة',
                'display_name_en' => 'View Step',
                'model'           => \App\Models\Step::class,
            ],

            // Reviews Permissions
            [
                'name'            => 'reviews_list',
                'display_name_ar' => 'قائمة التقييمات',
                'display_name_en' => 'List Reviews',
                'model'           => \App\Models\Review::class,
            ],
            [
                'name'            => 'reviews_create',
                'display_name_ar' => 'إنشاء تقييم',
                'display_name_en' => 'Create Review',
                'model'           => \App\Models\Review::class,
            ],
            [
                'name'            => 'reviews_edit',
                'display_name_ar' => 'تعديل تقييم',
                'display_name_en' => 'Edit Review',
                'model'           => \App\Models\Review::class,
            ],
            [
                'name'            => 'reviews_delete',
                'display_name_ar' => 'حذف تقييم',
                'display_name_en' => 'Delete Review',
                'model'           => \App\Models\Review::class,
            ],
            [
                'name'            => 'reviews_view',
                'display_name_ar' => 'عرض تقييم',
                'display_name_en' => 'View Review',
                'model'           => \App\Models\Review::class,
            ],

            // Pages Permissions
            [
                'name'            => 'pages_list',
                'display_name_ar' => 'قائمة الصفحات',
                'display_name_en' => 'List Pages',
                'model'           => \App\Models\Page::class,
            ],
            [
                'name'            => 'pages_create',
                'display_name_ar' => 'إنشاء صفحة',
                'display_name_en' => 'Create Page',
                'model'           => \App\Models\Page::class,
            ],
            [
                'name'            => 'pages_edit',
                'display_name_ar' => 'تعديل صفحة',
                'display_name_en' => 'Edit Page',
                'model'           => \App\Models\Page::class,
            ],
            [
                'name'            => 'pages_delete',
                'display_name_ar' => 'حذف صفحة',
                'display_name_en' => 'Delete Page',
                'model'           => \App\Models\Page::class,
            ],
            [
                'name'            => 'pages_view',
                'display_name_ar' => 'عرض صفحة',
                'display_name_en' => 'View Page',
                'model'           => \App\Models\Page::class,
            ],

            // Notifications Permissions
            [
                'name'            => 'notifications_list',
                'display_name_ar' => 'قائمة الإشعارات',
                'display_name_en' => 'List Notifications',
                'model'           => \App\Models\Notification::class,
            ],
            [
                'name'            => 'notifications_create',
                'display_name_ar' => 'إنشاء إشعار',
                'display_name_en' => 'Create Notification',
                'model'           => \App\Models\Notification::class,
            ],
            [
                'name'            => 'notifications_edit',
                'display_name_ar' => 'تعديل إشعار',
                'display_name_en' => 'Edit Notification',
                'model'           => \App\Models\Notification::class,
            ],
            [
                'name'            => 'notifications_delete',
                'display_name_ar' => 'حذف إشعار',
                'display_name_en' => 'Delete Notification',
                'model'           => \App\Models\Notification::class,
            ],
            [
                'name'            => 'notifications_view',
                'display_name_ar' => 'عرض إشعار',
                'display_name_en' => 'View Notification',
                'model'           => \App\Models\Notification::class,
            ],

            // Direct Orders Permissions
            [
                'name'            => 'direct_orders_list',
                'display_name_ar' => 'قائمة الطلبات المباشرة',
                'display_name_en' => 'List Direct Orders',
                'model'           => \App\Models\DirectOrder::class,
            ],
            [
                'name'            => 'direct_orders_create',
                'display_name_ar' => 'إنشاء طلب مباشر',
                'display_name_en' => 'Create Direct Order',
                'model'           => \App\Models\DirectOrder::class,
            ],
            [
                'name'            => 'direct_orders_edit',
                'display_name_ar' => 'تعديل طلب مباشر',
                'display_name_en' => 'Edit Direct Order',
                'model'           => \App\Models\DirectOrder::class,
            ],
            [
                'name'            => 'direct_orders_delete',
                'display_name_ar' => 'حذف طلب مباشر',
                'display_name_en' => 'Delete Direct Order',
                'model'           => \App\Models\DirectOrder::class,
            ],
            [
                'name'            => 'direct_orders_view',
                'display_name_ar' => 'عرض طلب مباشر',
                'display_name_en' => 'View Direct Order',
                'model'           => \App\Models\DirectOrder::class,
            ],

            // Get Price Orders Permissions
            [
                'name'            => 'get_prices_list',
                'display_name_ar' => 'قائمة طلبات الأسعار',
                'display_name_en' => 'List Get Price Orders',
                'model'           => \App\Models\GetPrice::class,
            ],
            [
                'name'            => 'get_prices_create',
                'display_name_ar' => 'إنشاء طلب سعر',
                'display_name_en' => 'Create Get Price Order',
                'model'           => \App\Models\GetPrice::class,
            ],
            [
                'name'            => 'get_prices_edit',
                'display_name_ar' => 'تعديل طلب سعر',
                'display_name_en' => 'Edit Get Price Order',
                'model'           => \App\Models\GetPrice::class,
            ],
            [
                'name'            => 'get_prices_delete',
                'display_name_ar' => 'حذف طلب سعر',
                'display_name_en' => 'Delete Get Price Order',
                'model'           => \App\Models\GetPrice::class,
            ],
            [
                'name'            => 'get_prices_view',
                'display_name_ar' => 'عرض طلب سعر',
                'display_name_en' => 'View Get Price Order',
                'model'           => \App\Models\GetPrice::class,
            ],

            // Point Settings Permissions
            [
                'name'            => 'point_settings_list',
                'display_name_ar' => 'قائمة إعدادات النقاط',
                'display_name_en' => 'List Point Settings',
                'model'           => \App\Models\PointSetting::class,
            ],
            [
                'name'            => 'point_settings_create',
                'display_name_ar' => 'إنشاء إعداد نقاط',
                'display_name_en' => 'Create Point Setting',
                'model'           => \App\Models\PointSetting::class,
            ],
            [
                'name'            => 'point_settings_edit',
                'display_name_ar' => 'تعديل إعداد نقاط',
                'display_name_en' => 'Edit Point Setting',
                'model'           => \App\Models\PointSetting::class,
            ],
            [
                'name'            => 'point_settings_delete',
                'display_name_ar' => 'حذف إعداد نقاط',
                'display_name_en' => 'Delete Point Setting',
                'model'           => \App\Models\PointSetting::class,
            ],
            [
                'name'            => 'point_settings_view',
                'display_name_ar' => 'عرض إعداد نقاط',
                'display_name_en' => 'View Point Setting',
                'model'           => \App\Models\PointSetting::class,
            ],

            // Supplies Permissions (للطابعات)
            [
                'name'            => 'supplies_list',
                'display_name_ar' => 'قائمة المستلزمات',
                'display_name_en' => 'List Supplies',
                'model'           => \App\Models\Supply::class,
            ],
            [
                'name'            => 'supplies_create',
                'display_name_ar' => 'إنشاء مستلزم',
                'display_name_en' => 'Create Supply',
                'model'           => \App\Models\Supply::class,
            ],
            [
                'name'            => 'supplies_edit',
                'display_name_ar' => 'تعديل مستلزم',
                'display_name_en' => 'Edit Supply',
                'model'           => \App\Models\Supply::class,
            ],
            [
                'name'            => 'supplies_delete',
                'display_name_ar' => 'حذف مستلزم',
                'display_name_en' => 'Delete Supply',
                'model'           => \App\Models\Supply::class,
            ],
            [
                'name'            => 'supplies_view',
                'display_name_ar' => 'عرض مستلزم',
                'display_name_en' => 'View Supply',
                'model'           => \App\Models\Supply::class,
            ],

            // Print Services Permissions
            [
                'name'            => 'print_services_list',
                'display_name_ar' => 'قائمة خدمات الطباعة',
                'display_name_en' => 'List Print Services',
                'model'           => \App\Models\PrintService::class,
            ],
            [
                'name'            => 'print_services_create',
                'display_name_ar' => 'إنشاء خدمة طباعة',
                'display_name_en' => 'Create Print Service',
                'model'           => \App\Models\PrintService::class,
            ],
            [
                'name'            => 'print_services_edit',
                'display_name_ar' => 'تعديل خدمة طباعة',
                'display_name_en' => 'Edit Print Service',
                'model'           => \App\Models\PrintService::class,
            ],
            [
                'name'            => 'print_services_delete',
                'display_name_ar' => 'حذف خدمة طباعة',
                'display_name_en' => 'Delete Print Service',
                'model'           => \App\Models\PrintService::class,
            ],
            [
                'name'            => 'print_services_view',
                'display_name_ar' => 'عرض خدمة طباعة',
                'display_name_en' => 'View Print Service',
                'model'           => \App\Models\PrintService::class,
            ],

            // Packages Permissions
            [
                'name'            => 'packages_list',
                'display_name_ar' => 'قائمة الباقات',
                'display_name_en' => 'List Packages',
                'model'           => \App\Models\Package::class,
            ],
            [
                'name'            => 'packages_create',
                'display_name_ar' => 'إنشاء باقة',
                'display_name_en' => 'Create Package',
                'model'           => \App\Models\Package::class,
            ],
            [
                'name'            => 'packages_edit',
                'display_name_ar' => 'تعديل باقة',
                'display_name_en' => 'Edit Package',
                'model'           => \App\Models\Package::class,
            ],
            [
                'name'            => 'packages_delete',
                'display_name_ar' => 'حذف باقة',
                'display_name_en' => 'Delete Package',
                'model'           => \App\Models\Package::class,
            ],
            [
                'name'            => 'packages_view',
                'display_name_ar' => 'عرض باقة',
                'display_name_en' => 'View Package',
                'model'           => \App\Models\Package::class,
            ],

            // Quotations Permissions
            [
                'name'            => 'quotations_list',
                'display_name_ar' => 'قائمة عروض الأسعار',
                'display_name_en' => 'List Quotations',
                'model'           => \App\Models\Quotation::class,
            ],
            [
                'name'            => 'quotations_create',
                'display_name_ar' => 'إنشاء عرض سعر',
                'display_name_en' => 'Create Quotation',
                'model'           => \App\Models\Quotation::class,
            ],
            [
                'name'            => 'quotations_edit',
                'display_name_ar' => 'تعديل عرض سعر',
                'display_name_en' => 'Edit Quotation',
                'model'           => \App\Models\Quotation::class,
            ],
            [
                'name'            => 'quotations_delete',
                'display_name_ar' => 'حذف عرض سعر',
                'display_name_en' => 'Delete Quotation',
                'model'           => \App\Models\Quotation::class,
            ],
            [
                'name'            => 'quotations_view',
                'display_name_ar' => 'عرض عرض سعر',
                'display_name_en' => 'View Quotation',
                'model'           => \App\Models\Quotation::class,
            ],

            // Print Settings Permissions
            [
                'name'            => 'print_settings_view',
                'display_name_ar' => 'عرض إعدادات الطباعة',
                'display_name_en' => 'View Print Settings',
                'model'           => \App\Models\PrintSetting::class,
            ],
            [
                'name'            => 'print_settings_edit',
                'display_name_ar' => 'تعديل إعدادات الطباعة',
                'display_name_en' => 'Edit Print Settings',
                'model'           => \App\Models\PrintSetting::class,
            ],

            // Profile Permissions
            [
                'name'            => 'profile_view',
                'display_name_ar' => 'عرض الملف الشخصي',
                'display_name_en' => 'View Profile',
                'model'           => \App\Models\Admin::class,
            ],
            [
                'name'            => 'profile_edit',
                'display_name_ar' => 'تعديل الملف الشخصي',
                'display_name_en' => 'Edit Profile',
                'model'           => \App\Models\Admin::class,
            ],
        ];
        //delete existing permissions
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Permission::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'display_name_ar' => $permission['display_name_ar'],
                    'display_name_en' => $permission['display_name_en'],
                    'model'           => $permission['model'],
                ]
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
