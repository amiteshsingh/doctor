<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AdminModules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminPermissionController extends Controller
{
    public function index(Request $request): View
    {
        $admins = User::query()
            ->whereHas('role', fn ($query) => $query->where('role', 'admin'))
            ->where('id', '!=', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $selectedAdmin = $admins->firstWhere('id', (int) $request->query('admin_id'))
            ?? $admins->first();
        $permissions = $selectedAdmin
            ? AdminModules::permissionsFor((int) $selectedAdmin->id)
            : [];

        return view('admin.permissions.index', compact('admins', 'selectedAdmin', 'permissions'));
    }

    public function update(Request $request, int $admin): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['array'],
            'permissions.*.access_module' => ['sometimes', 'boolean'],
            'permissions.*.view_data' => ['sometimes', 'boolean'],
            'permissions.*.delete_data' => ['sometimes', 'boolean'],
        ]);

        $submitted = $validated['permissions'] ?? [];
        $unknownModules = array_diff(array_keys($submitted), array_keys(AdminModules::MODULES));
        if ($unknownModules !== []) {
            throw ValidationException::withMessages([
                'permissions' => 'One or more selected modules are invalid.',
            ]);
        }

        $targetAdmin = User::query()
            ->whereKey($admin)
            ->where('id', '!=', 1)
            ->whereHas('role', fn ($query) => $query->where('role', 'admin'))
            ->firstOrFail();

        DB::transaction(function () use ($targetAdmin, $submitted): void {
            $now = now();
            $rows = [];
            foreach (AdminModules::MODULES as $module => $details) {
                $values = $submitted[$module] ?? [];
                $rows[] = [
                    'admin_user_id' => $targetAdmin->id,
                    'module_key' => $module,
                    'access_module' => !empty($values['access_module']),
                    'view_data' => !empty($values['view_data']),
                    'delete_data' => !empty($values['delete_data']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('admin_module_permissions')->upsert(
                $rows,
                ['admin_user_id', 'module_key'],
                ['access_module', 'view_data', 'delete_data', 'updated_at']
            );
        });

        return redirect()
            ->route('admin.permissions.index', ['admin_id' => $targetAdmin->id])
            ->with('success', 'Admin module permissions saved successfully.');
    }
}
