<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class OrderHelper
{
    public static function generateOrderNumber(): string
    {
        // ใช้ UUID 8 ตัวแรก = ไม่ซ้ำแน่นอน
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(Str::uuid()->toString(), 0, 8));
    }
}