<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UsesUuid
{
    /**
     * Laravel จะเรียกเมธอดนี้อัตโนมัติเมื่อใช้ trait ชื่อ UsesUuid
     * (ผ่าน Eloquent::bootTraits)
     */
    protected static function bootUsesUuid(): void
    {
        static::creating(function ($model) {
            // สร้าง uuid เฉพาะตอนยังว่างเท่านั้น
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function ensureUuid(): void
    {
        if (empty($this->uuid)) {
            $this->uuid = (string) Str::uuid();
        }
    }
}
