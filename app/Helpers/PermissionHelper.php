<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if current user has permission
     */
    public static function hasPermission($permission)
    {
        $user = Auth::user();
        return $user && $user->hasPermission($permission);
    }

    /**
     * Check if current user has any of the given permissions
     */
    public static function hasAnyPermission($permissions)
    {
        $user = Auth::user();
        return $user && $user->hasAnyPermission($permissions);
    }

    /**
     * Check if current user has all of the given permissions
     */
    public static function hasAllPermissions($permissions)
    {
        $user = Auth::user();
        return $user && $user->hasAllPermissions($permissions);
    }

    /**
     * Check if current user has role
     */
    public static function hasRole($role)
    {
        $user = Auth::user();
        return $user && $user->hasRole($role);
    }

    /**
     * Check if current user has any of the given roles
     */
    public static function hasAnyRole($roles)
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole($roles);
    }

    /**
     * Get user permissions
     */
    public static function getUserPermissions()
    {
        $user = Auth::user();
        return $user ? $user->permissions : collect();
    }

    /**
     * Get user roles
     */
    public static function getUserRoles()
    {
        $user = Auth::user();
        return $user ? $user->roles : collect();
    }
}
