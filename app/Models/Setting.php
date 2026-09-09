<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue(string $key): ?string
    {
        $setting = static::where('key', $key)->first();

        return $setting?->value;
    }

    public static function setValue(string $key, string $value): static
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
