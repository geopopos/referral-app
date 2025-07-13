<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'value',
        'label',
        'description',
        'sort_order',
    ];

    /**
     * Get content by key with fallback to default value.
     */
    public static function get(string $key, string $default = ''): string
    {
        $content = static::where('key', $key)->first();
        return $content ? $content->value : $default;
    }

    /**
     * Set content by key.
     */
    public static function set(string $key, string $value, string $type = 'text', string $label = '', string $description = '', int $sortOrder = 0): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'label' => $label ?: ucwords(str_replace('_', ' ', $key)),
                'description' => $description,
                'sort_order' => $sortOrder,
            ]
        );
    }

    /**
     * Get all content ordered by sort_order.
     */
    public static function getAllOrdered()
    {
        return static::orderBy('sort_order')->orderBy('label')->get();
    }
}
