<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\User\app\Models\Permission;
use Modules\User\app\Models\Role;
use Modules\User\Enumeration\PermissionsEnum;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'user update',
                'display_name' => 'ویرایش کاربر'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'user create',
                'display_name' => 'ایجاد کاربر'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'notify',
                'display_name' => 'پیام باشگاه مشتریان'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'all address',
                'display_name' => 'لیست تمام آدرس ها'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'all stores',
                'display_name' => 'لیست تمام فروشگاه ها'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'all categories',
                'display_name' => 'لیست تمام دسته بندی ها'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'all foods',
                'display_name' => 'لیست تمام غذاها'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'all orders',
                'display_name' => 'لیست تمام سفارشات'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'discount code',
                'display_name' => 'کد تخفیف '
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'all invoice',
                'display_name' => 'لیست تمام فاکتور ها '
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'all transactions',
                'display_name' => 'لیست تمام تراکنش ها '
            ],
        ];

        Permission::insert($permissions);
        $permissions = [
            PermissionsEnum::USER_CREATE,
            PermissionsEnum::USER_UPDATE,
            PermissionsEnum::NOTIFY,
            PermissionsEnum::ALL_ADDRESS,
            PermissionsEnum::ALL_STORES,
            PermissionsEnum::ALL_CATEGORIES,
            PermissionsEnum::ALL_FOODS,
            PermissionsEnum::ALL_ORDERS,
            PermissionsEnum::DISCOUNT_CODE,
            PermissionsEnum::ALL_INVOICE,
            PermissionsEnum::ALL_TRANSACTIONS
        ];
        $permissionInstances = Permission::whereIn('name', $permissions)->get();
        $admin = Role::where('name', 'superuser')->first();
        foreach ($permissionInstances as $instance) {
            $admin->permissionsRelation()->attach([$instance->id => ['id' => \Illuminate\Support\Str::uuid()]]);
        }

    }
}
