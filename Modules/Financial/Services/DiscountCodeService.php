<?php

namespace Modules\Financial\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Financial\app\Models\DiscountCode;

class DiscountCodeService
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function create()
    {
        try {
            $data = $this->data;
            DB::beginTransaction();
            $discountData = [
                'id' => Str::uuid(),
                'code' => $data['code'],
                'discount' => $data['discount'],
                'type' => $data['type'],
                'start_at' => $data['start_at'],
                'end_at' => $data['end_at'],
                'max_uses' => $data['max_uses'],
            ];

            if ($data['users']) {
                $discounts = array_map(function ($user) use ($discountData) {
                    return array_merge($discountData, ['user_id' => $user['id']]);
                }, $data['users']);

                DiscountCode::insert($discounts);
            } else {
                DiscountCode::create($discountData);
            }
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

}
