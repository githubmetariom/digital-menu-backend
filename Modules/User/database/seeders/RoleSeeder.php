<?php

namespace Modules\User\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Validation\Rule;
use Modules\User\app\Models\Role;
use Psy\Util\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'superuser',
                'display_name' => 'مدیر کل'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'sales representative',
                'display_name' => 'نماینده فروش'
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'seller',
                'display_name' => 'فروشنده',
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'name' => 'buyer',
                'display_name' => 'خریدار'
            ]
        ];

        Role::insert($roles);
    }
}
