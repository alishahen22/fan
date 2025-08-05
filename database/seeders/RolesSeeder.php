<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating default roles...');

        // Create Super Admin Role
        $superAdmin = Role::first();

        // Give super admin all permissions
        $allPermissions = Permission::all();
        $superAdmin->syncPermissions($allPermissions);
        
        $this->command->info("Super Admin role created with {$allPermissions->count()} permissions");

        // // Create Admin Role
        // $admin = Role::firstOrCreate(
        //     ['name' => 'admin'],
        //     [
        //         'display_name_ar' => 'مدير',
        //         'display_name_en' => 'Admin',
        //         'description'     => 'Administrative access',
        //     ]
        // );

        // // Give admin most permissions except role management
        // $adminPermissions = Permission::whereNotIn('name', [
        //     'roles_create', 'roles_edit', 'roles_delete',
        //     'admins_create', 'admins_edit', 'admins_delete',
        // ])->get();

        // $admin->syncPermissions($adminPermissions);

        // $this->command->info("Admin role created with {$adminPermissions->count()} permissions");

        // // Create Manager Role
        // $manager = Role::firstOrCreate(
        //     ['name' => 'manager'],
        //     [
        //         'display_name_ar' => 'مدير فرع',
        //         'display_name_en' => 'Manager',
        //         'description'     => 'Branch management access',
        //     ]
        // );

        // // Give manager limited permissions
        // $managerPermissions = Permission::whereIn('name', [
        //     // Users permissions
        //     'users_list', 'users_view',
        //     // Orders permissions
        //     'orders_list', 'orders_view', 'orders_edit',
        //     // Categories permissions
        //     'categories_list', 'categories_view',
        //     // Items permissions
        //     'items_list', 'items_view', 'items_create', 'items_edit',
        //     // Products permissions
        //     'products_list', 'products_view',
        //     // Direct orders permissions
        //     'direct_orders_list', 'direct_orders_view', 'direct_orders_edit',
        //     // Get prices permissions
        //     'get_prices_list', 'get_prices_view', 'get_prices_edit',
        //     // Reviews permissions
        //     'reviews_list', 'reviews_view', 'reviews_edit',
        //     // Contacts permissions
        //     'contacts_list', 'contacts_view',
        //     // Profile permissions
        //     'profile_view', 'profile_edit',
        // ])->get();

        // $manager->syncPermissions($managerPermissions);

        // $this->command->info("Manager role created with {$managerPermissions->count()} permissions");

        // // Create Editor Role
        // $editor = Role::firstOrCreate(
        //     ['name' => 'editor'],
        //     [
        //         'display_name_ar' => 'محرر المحتوى',
        //         'display_name_en' => 'Content Editor',
        //         'description'     => 'Content management access',
        //     ]
        // );

        // // Give editor content-related permissions
        // $editorPermissions = Permission::whereIn('name', [
        //     // Articles permissions
        //     'articles_list', 'articles_view', 'articles_create', 'articles_edit', 'articles_delete',
        //     // Pages permissions
        //     'pages_list', 'pages_view', 'pages_edit',
        //     // Sliders permissions
        //     'sliders_list', 'sliders_view', 'sliders_create', 'sliders_edit', 'sliders_delete',
        //     // Banners permissions
        //     'banners_list', 'banners_view', 'banners_create', 'banners_edit', 'banners_delete',
        //     // Steps permissions
        //     'steps_list', 'steps_view', 'steps_create', 'steps_edit', 'steps_delete',
        //     // Offers permissions
        //     'offers_list', 'offers_view', 'offers_create', 'offers_edit', 'offers_delete',
        //     // Splashes permissions
        //     'splashes_list', 'splashes_view', 'splashes_create', 'splashes_edit', 'splashes_delete',
        //     // Notifications permissions
        //     'notifications_list', 'notifications_view', 'notifications_create', 'notifications_edit',
        //     // Profile permissions
        //     'profile_view', 'profile_edit',
        // ])->get();

        // $editor->syncPermissions($editorPermissions);

        // $this->command->info("Editor role created with {$editorPermissions->count()} permissions");

        // // Create Printer Role
        // $printer = Role::firstOrCreate(
        //     ['name' => 'printer'],
        //     [
        //         'display_name_ar' => 'مشغل الطباعة',
        //         'display_name_en' => 'Printer Operator',
        //         'description'     => 'Printing services management',
        //     ]
        // );

        // // Give printer printing-related permissions
        // $printerPermissions = Permission::whereIn('name', [
        //     // Items permissions
        //     'items_list', 'items_view', 'items_create', 'items_edit', 'items_delete',
        //     // Supplies permissions
        //     'supplies_list', 'supplies_view', 'supplies_create', 'supplies_edit', 'supplies_delete',
        //     // Print Services permissions
        //     'print_services_list', 'print_services_view', 'print_services_create', 'print_services_edit', 'print_services_delete',
        //     // Quotations permissions
        //     'quotations_list', 'quotations_view', 'quotations_create', 'quotations_edit', 'quotations_delete',
        //     // Packages permissions
        //     'packages_list', 'packages_view', 'packages_create', 'packages_edit', 'packages_delete',
        //     // Print Settings permissions
        //     'print_settings_view', 'print_settings_edit',
        //     // Orders related to printing
        //     'orders_list', 'orders_view', 'orders_edit',
        //     // Profile permissions
        //     'profile_view', 'profile_edit',
        // ])->get();

        // $printer->syncPermissions($printerPermissions);

        // $this->command->info("Printer role created with {$printerPermissions->count()} permissions");

        // $this->command->info('Default roles created successfully!');
    }
}
