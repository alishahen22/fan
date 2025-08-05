# دليل نظام الأدوار والصلاحيات (Roles & Permissions Guide)

## 🎯 نظرة عامة

تم تطوير نظام شامل للأدوار والصلاحيات باستخدام Laratrust مع تحسينات كبيرة لجعل النظام أكثر قوة وسهولة في الاستخدام.

## ✨ المميزات الجديدة

### 🔧 التحسينات المضافة:

1. **تحسين الـ Controller**

    - Validation محسن مع رسائل خطأ واضحة
    - إضافة حقل الوصف للـ roles
    - تحسين التعامل مع الـ permissions
    - حماية الأدوار الأساسية من الحذف

2. **تحسين الـ Views**

    - تصميم محسن للـ forms
    - إضافة حقل الوصف
    - تحسين عرض الـ permissions
    - إضافة validation في الـ frontend

3. **إضافة Middleware جديد**

    - `CheckPermission` - للتحقق من الـ permissions
    - `CheckRole` - للتحقق من الـ roles

4. **إضافة Blade Directives**

    - `@permission('permission_name')`
    - `@anypermission(['permission1', 'permission2'])`
    - `@role('role_name')`
    - `@anyrole(['role1', 'role2'])`

5. **إضافة Helper Functions**

    - `PermissionHelper::hasPermission()`
    - `PermissionHelper::hasRole()`
    - `PermissionHelper::getUserPermissions()`

6. **إضافة Seeders**
    - `PermissionsSeeder` - لتشغيل الـ permissions
    - `RolesSeeder` - لإنشاء الـ roles الأساسية

## 🚀 كيفية البدء

### الخطوة 1: تشغيل جميع الـ Seeders

```bash
php artisan db:seed
```

أو لتشغيل seeder محدد:

```bash
php artisan db:seed --class=PermissionsSeeder
php artisan db:seed --class=RolesSeeder
```

### الخطوة 2: تطبيق الـ Permissions على الـ Routes

```php
// في ملف routes/web.php

// التحقق من permission واحد
Route::middleware(['permission:users_list'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});

// التحقق من role واحد
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
});

// التحقق من عدة permissions
Route::middleware(['permission:users_create|users_edit'])->group(function () {
    Route::post('/users', [UserController::class, 'store']);
});
```

### الخطوة 3: استخدام الـ Blade Directives في الـ Views

```blade
{{-- التحقق من permission واحد --}}
@permission('users_list')
    <a href="{{ route('users.index') }}">قائمة المستخدمين</a>
@endpermission

{{-- التحقق من عدة permissions --}}
@anypermission(['users_create', 'users_edit'])
    <button>إضافة/تعديل مستخدم</button>
@endanypermission

{{-- التحقق من role --}}
@role('admin')
    <div>محتوى للمدير فقط</div>
@endrole

{{-- التحقق من عدة roles --}}
@anyrole(['admin', 'manager'])
    <div>محتوى للمدير أو المدير الفرعي</div>
@endanyrole
```

### الخطوة 4: استخدام الـ Helper Functions

```php
use App\Helpers\PermissionHelper;

// التحقق من permission
if (PermissionHelper::hasPermission('users_create')) {
    // كود هنا
}

// التحقق من role
if (PermissionHelper::hasRole('admin')) {
    // كود هنا
}

// الحصول على permissions المستخدم
$permissions = PermissionHelper::getUserPermissions();

// الحصول على roles المستخدم
$roles = PermissionHelper::getUserRoles();
```

### الخطوة 5: إضافة Permissions جديدة

لإضافة permissions جديدة، أعدل ملف `database/seeders/PermissionsSeeder.php`:

```php
// أضف permissions جديدة في المصفوفة
[
    'name' => 'new_feature_list',
    'display_name_ar' => 'قائمة الميزة الجديدة',
    'display_name_en' => 'List New Feature',
    'model' => \App\Models\NewFeature::class,
],
[
    'name' => 'new_feature_create',
    'display_name_ar' => 'إنشاء ميزة جديدة',
    'display_name_en' => 'Create New Feature',
    'model' => \App\Models\NewFeature::class,
],
```

ثم شغل:

```bash
php artisan db:seed --class=PermissionsSeeder
```

## 📋 الـ Roles الأساسية

### 1. Super Admin (مدير عام)

-   لديه جميع الصلاحيات
-   يمكنه إدارة الأدوار والصلاحيات
-   يمكنه إدارة المشرفين
-   يمكنه الوصول للإعدادات

### 2. Admin (مدير)

-   لديه معظم الصلاحيات
-   لا يمكنه إدارة الأدوار والصلاحيات
-   لا يمكنه إدارة المشرفين
-   لا يمكنه الوصول للإعدادات

### 3. Manager (مدير فرع)

-   لديه صلاحيات محدودة
-   يمكنه عرض المستخدمين والطلبات
-   يمكنه عرض الفئات والمنتجات

## 🔐 الـ Permissions المتاحة

### Users (المستخدمين)

-   `users_list` - قائمة المستخدمين
-   `users_create` - إنشاء مستخدم
-   `users_edit` - تعديل مستخدم
-   `users_delete` - حذف مستخدم
-   `users_view` - عرض مستخدم

### Roles (الأدوار)

-   `roles_list` - قائمة الأدوار
-   `roles_create` - إنشاء دور
-   `roles_edit` - تعديل دور
-   `roles_delete` - حذف دور
-   `roles_view` - عرض دور

### Admins (المشرفين)

-   `admins_list` - قائمة المشرفين
-   `admins_create` - إنشاء مشرف
-   `admins_edit` - تعديل مشرف
-   `admins_delete` - حذف مشرف
-   `admins_view` - عرض مشرف

### Orders (الطلبات)

-   `orders_list` - قائمة الطلبات
-   `orders_create` - إنشاء طلب
-   `orders_edit` - تعديل طلب
-   `orders_delete` - حذف طلب
-   `orders_view` - عرض طلب

### Categories (الفئات)

-   `categories_list` - قائمة الفئات
-   `categories_create` - إنشاء فئة
-   `categories_edit` - تعديل فئة
-   `categories_delete` - حذف فئة
-   `categories_view` - عرض فئة

### Items (المنتجات)

-   `items_list` - قائمة المنتجات
-   `items_create` - إنشاء منتج
-   `items_edit` - تعديل منتج
-   `items_delete` - حذف منتج
-   `items_view` - عرض منتج

### Settings (الإعدادات)

-   `settings_view` - عرض الإعدادات
-   `settings_edit` - تعديل الإعدادات

## 🛡️ الأمان

### حماية الأدوار الأساسية

-   لا يمكن حذف أو تعديل الأدوار الأساسية (super_admin, admin)
-   لا يمكن حذف دور له مستخدمين مرتبطين به

### التحقق من الصلاحيات

-   يتم التحقق من الصلاحيات في كل مستوى:
    -   Routes (Middleware)
    -   Views (Blade Directives)
    -   Controllers (Helper Functions)

## 📁 هيكل الملفات

```
app/
├── Helpers/
│   └── PermissionHelper.php
├── Http/
│   ├── Controllers/Admin/
│   │   └── RolesController.php (محسن)
│   └── Middleware/
│       ├── CheckPermission.php (جديد)
│       └── CheckRole.php (جديد)
├── Models/
│   ├── Role.php
│   └── Permission.php
└── Providers/
    └── AppServiceProvider.php (محسن)

database/seeders/
├── PermissionsSeeder.php (جديد)
├── RolesSeeder.php (جديد)
└── DatabaseSeeder.php (محدث)

resources/views/roles/
├── list.blade.php (محسن)
├── form.blade.php (محسن)
├── create.blade.php
└── edit.blade.php
```

## 🔧 التخصيص

### إضافة Permissions جديدة

1. أعدل ملف `database/seeders/PermissionsSeeder.php`
2. أضف permissions جديدة في المصفوفة
3. شغل الأمر:

```bash
php artisan db:seed --class=PermissionsSeeder
```

### إضافة Roles جديدة

1. أعدل ملف `database/seeders/RolesSeeder.php`
2. أضف roles جديدة في الـ seeder
3. شغل الأمر:

```bash
php artisan db:seed --class=RolesSeeder
```

### تخصيص الـ Middleware

يمكنك تخصيص الـ middleware حسب احتياجاتك:

```php
// في app/Http/Middleware/CheckPermission.php
public function handle(Request $request, Closure $next, string $permission): Response
{
    $user = $request->user();

    if (!$user) {
        return abort(401, 'Unauthorized');
    }

    // إضافة منطق مخصص هنا
    if (!$user->hasPermission($permission)) {
        return abort(403, 'Access denied');
    }

    return $next($request);
}
```

## 🐛 استكشاف الأخطاء

### مشاكل شائعة وحلولها

1. **الـ permissions لا تعمل**

    - تأكد من تشغيل `php artisan db:seed --class=PermissionsSeeder`
    - تأكد من إضافة الـ middleware في Kernel.php
    - تأكد من تسجيل الـ Blade directives

2. **الـ roles لا تظهر**

    - تأكد من تشغيل `php artisan db:seed --class=RolesSeeder`
    - تأكد من إضافة الـ permissions للـ roles

3. **الـ middleware لا يعمل**

    - تأكد من تسجيل الـ middleware في Kernel.php
    - تأكد من استخدام الـ middleware الصحيح في الـ routes

4. **الـ Blade directives لا تعمل**
    - تأكد من تسجيل الـ directives في AppServiceProvider.php
    - تأكد من تشغيل `php artisan view:clear`

## 📞 الدعم

إذا واجهت أي مشكلة:

1. راجع الـ logs في `storage/logs/`
2. تأكد من تشغيل جميع الـ seeders
3. تأكد من إعداد الـ database بشكل صحيح
4. راجع الـ middleware configuration

## 🎉 الخلاصة

تم تطوير نظام شامل ومحسن للأدوار والصلاحيات مع:

-   ✅ تحسينات كبيرة في الـ controller والـ views
-   ✅ إضافة middleware جديد للـ permissions والـ roles
-   ✅ إضافة Blade directives للاستخدام في الـ views
-   ✅ إضافة helper functions للتعامل مع الـ permissions
-   ✅ إضافة seeders لتشغيل الـ permissions والـ roles
-   ✅ تحسين الأمان وحماية الأدوار الأساسية
-   ✅ دليل شامل للاستخدام والتخصيص

النظام جاهز للاستخدام ويمكن تخصيصه حسب احتياجات المشروع!
