<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class AdminModules
{
    public const MODULES = [
        'hospitals' => ['label' => 'Hospitals', 'route' => 'admin.hospital'],
        'doctors' => ['label' => 'Doctors', 'route' => 'admin.doctor'],
        'specializations' => ['label' => 'Specialization', 'route' => 'admin.specialization'],
        'users' => ['label' => 'Users (Doctors)', 'route' => 'admin.user'],
        'app_users' => ['label' => 'App Users', 'route' => 'admin.app-users'],
        'blogs' => ['label' => 'Blogs', 'route' => 'admin.blog'],
        'notifications' => ['label' => 'User Notification', 'route' => 'admin.notification'],
        'doctor_notifications' => ['label' => 'Doctor Notification', 'route' => 'admin.doctor.notification'],
        'payment_settings' => ['label' => 'Payment Settings', 'route' => 'admin.payment'],
        'support_tickets' => ['label' => 'Support Tickets', 'route' => 'admin.support'],
        'pregnancy_tracker' => ['label' => 'Pregnancy Tracker', 'route' => 'admin.pregnancy'],
        'banners' => ['label' => 'Banners', 'route' => 'admin.banner'],
        'testimonials' => ['label' => 'Testimonials', 'route' => 'admin.testimonial'],
    ];

    public static function permissionsFor(int $userId): array
    {
        return DB::table('admin_module_permissions')
            ->where('admin_user_id', $userId)
            ->get()
            ->keyBy('module_key')
            ->all();
    }

    public static function allows(int $userId, array $permissions, string $module, string $ability): bool
    {
        if ($userId === 1) {
            return true;
        }

        $column = match ($ability) {
            'access' => 'access_module',
            'view' => 'view_data',
            'delete' => 'delete_data',
            default => null,
        };

        return $column !== null
            && isset($permissions[$module])
            && (bool) $permissions[$module]->{$column};
    }

    public static function moduleForRoute(?string $routeName): ?string
    {
        if (!$routeName) {
            return null;
        }

        $prefixes = [
            'admin.doctor_availability' => 'doctors',
            'admin.doctor.notification' => 'doctor_notifications',
            'admin.app-users' => 'app_users',
            'admin.specialization' => 'specializations',
            'admin.testimonial' => 'testimonials',
            'admin.pregnancy' => 'pregnancy_tracker',
            'admin.notification' => 'notifications',
            'admin.payment' => 'payment_settings',
            'admin.support' => 'support_tickets',
            'admin.banner' => 'banners',
            'admin.hospital' => 'hospitals',
            'admin.doctor' => 'doctors',
            'admin.user' => 'users',
            'admin.blog' => 'blogs',
        ];

        foreach ($prefixes as $prefix => $module) {
            if (str_starts_with($routeName, $prefix)) {
                return $module;
            }
        }

        return null;
    }

    public static function modulesForRoute(?string $routeName): array
    {
        if ($routeName && str_starts_with($routeName, 'gallery.')) {
            return ['hospitals', 'doctors'];
        }

        $module = self::moduleForRoute($routeName);

        return $module === null ? [] : [$module];
    }

    public static function abilityForRoute(string $routeName, string $method, string $path): string
    {
        if (preg_match('/(?:^|\\.)(?:delete|destroy)(?:\\.|$)/', $routeName)
            || preg_match('~/delete(?:/|$)~', $path)) {
            return 'delete';
        }

        if (in_array($routeName, [
            'admin.hospital',
            'admin.doctor',
            'admin.specialization',
            'admin.user',
            'admin.hospital.hospital_specialization',
            'admin.doctor.hospital_specialization',
            'admin.doctor.doctor_location',
        ], true)) {
            return 'view';
        }

        if (preg_match('/(?:^|\\.)(?:add|store|edit|update|toggle|send|reply|close|membership|permission|upload|cancel)(?:\\.|$)/', $routeName)
            || !in_array(strtoupper($method), ['GET', 'HEAD'], true)) {
            return 'access';
        }

        return 'view';
    }
}
