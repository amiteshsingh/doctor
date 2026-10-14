<?php

use App\Support\AdminModules;

test('user id one bypasses module permission flags', function () {
    expect(AdminModules::allows(1, [], 'blogs', 'access'))->toBeTrue()
        ->and(AdminModules::allows(1, [], 'blogs', 'view'))->toBeTrue()
        ->and(AdminModules::allows(1, [], 'blogs', 'delete'))->toBeTrue();
});

test('module access view and delete permissions are independent', function () {
    $permissions = [
        'blogs' => (object) [
            'access_module' => true,
            'view_data' => false,
            'delete_data' => true,
        ],
    ];

    expect(AdminModules::allows(2, $permissions, 'blogs', 'access'))->toBeTrue()
        ->and(AdminModules::allows(2, $permissions, 'blogs', 'view'))->toBeFalse()
        ->and(AdminModules::allows(2, $permissions, 'blogs', 'delete'))->toBeTrue()
        ->and(AdminModules::allows(2, [], 'blogs', 'access'))->toBeFalse();
});

test('admin route names map to the intended module', function () {
    expect(AdminModules::moduleForRoute('admin.doctor.notification.index'))->toBe('doctor_notifications')
        ->and(AdminModules::moduleForRoute('admin.doctor_availability'))->toBe('doctors')
        ->and(AdminModules::moduleForRoute('admin.app-users.destroy'))->toBe('app_users')
        ->and(AdminModules::modulesForRoute('gallery.delete'))->toBe(['hospitals', 'doctors'])
        ->and(AdminModules::moduleForRoute('admin.permissions.index'))->toBeNull();
});

test('admin route actions distinguish viewing editing and deleting', function () {
    expect(AdminModules::abilityForRoute('admin.hospital', 'POST', 'admin/hospital'))->toBe('view')
        ->and(AdminModules::abilityForRoute('admin.blog.add', 'GET', 'admin/blog/add'))->toBe('access')
        ->and(AdminModules::abilityForRoute('admin.blog.delete', 'DELETE', 'admin/blog/delete/1'))->toBe('delete');
});
