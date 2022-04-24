<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * @return void
     */
    protected static function bootHasUuid(): void
    {
        static::creating(static function (Model $model): void {
            $model->id = Str::uuid()->toString();
        });
    }
}
