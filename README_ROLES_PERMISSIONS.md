# نظام الأدوار والصلاحيات - دليل سريع

## 🚀 البدء السريع

### 1. تشغيل النظام

```bash
php artisan db:seed
```

### 2. الوصول لصفحة إدارة الأدوار

```
http://127.0.0.1:8000/admin/roles
```

## 📋 الـ Roles الأساسية

-   **Super Admin** - جميع الصلاحيات
-   **Admin** - معظم الصلاحيات (بدون إدارة الأدوار)
-   **Manager** - صلاحيات محدودة

## 🔐 الـ Permissions المتاحة

### الأدوار والمشرفين

-   `roles_list`, `roles_create`, `roles_edit`, `roles_delete`, `roles_view`
-   `admins_list`, `admins_create`, `admins_edit`, `admins_delete`, `admins_view`

### المستخدمين والطلبات

-   `users_list`, `users_create`, `users_edit`, `users_delete`, `users_view`
-   `orders_list`, `orders_create`, `orders_edit`, `orders_delete`, `orders_view`

### الفئات والمنتجات

-   `categories_list`, `categories_create`, `categories_edit`, `categories_delete`, `categories_view`
-   `items_list`, `items_create`, `items_edit`, `items_delete`, `items_view`

### الإعدادات

-   `settings_view`, `settings_edit`

## 🛠️ الاستخدام

### في الـ Routes

```php
Route::middleware(['permission:users_list'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});
```

### في الـ Views

```blade
@permission('users_list')
    <a href="{{ route('users.index') }}">قائمة المستخدمين</a>
@endpermission

@role('admin')
    <div>محتوى للمدير فقط</div>
@endrole
```

### في الـ Controllers

```php
use App\Helpers\PermissionHelper;

if (PermissionHelper::hasPermission('users_create')) {
    // كود هنا
}
```

## 🔧 إضافة Permissions جديدة

1. أعدل `database/seeders/PermissionsSeeder.php`
2. أضف permissions جديدة في المصفوفة
3. شغل: `php artisan db:seed --class=PermissionsSeeder`

## 📞 الدعم

-   راجع `ROLES_PERMISSIONS_GUIDE.md` للدليل التفصيلي
-   راجع الـ logs في `storage/logs/`
-   تأكد من تشغيل الـ seeders
