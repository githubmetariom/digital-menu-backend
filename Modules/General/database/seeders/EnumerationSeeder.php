<?php

namespace Modules\General\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Financial\Enumeration\TaxEnum;
use Modules\Financial\Enumeration\TransactionStatusEnumeration;
use Modules\General\app\Models\Enumeration;
use Modules\Shop\Enumeration\FoodStatusEnum;
use Modules\User\app\Models\Address;
use Modules\User\Enumeration\AddressStatusEnum;

class EnumerationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Enumeration::create([
            'title' => 'address',
            'parent_id' => 0
        ]);
        $address = [
            [
                'title' => 'active',
                'parent_id' => AddressStatusEnum::PARENT,
            ],
            [
                'title' => 'inactive',
                'parent_id' => AddressStatusEnum::PARENT,
            ],
        ];
        Enumeration::insert($address);

        Enumeration::create([
            'title' => 'food',
            'parent_id' => 0
        ]);

        $foods = [
            [
                'title' => 'active',
                'parent_id' => FoodStatusEnum::PARENT,
            ],
            [
                'title' => 'inactive',
                'parent_id' => FoodStatusEnum::PARENT,
            ],
        ];
        Enumeration::insert($foods);

        Enumeration::create([
            'title' => 'transaction',
            'parent_id' => 0
        ]);

        $transactions = [
            [
                'title' => 'active',
                'parent_id' => TransactionStatusEnumeration::PARENT,
            ],
            [
                'title' => 'inactive',
                'parent_id' => TransactionStatusEnumeration::PARENT,
            ],
        ];
        Enumeration::insert($transactions);

        Enumeration::create([
            'title' => 'tax rate',
            'parent_id' => 0
        ]);

        $taxes = [
            [
                'title' => '9',
                'parent_id' => TaxEnum::PARENT,
            ]
        ];
        Enumeration::insert($transactions);
    }
}
