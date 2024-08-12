<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SlugHelper
{
    /**
     * Generates a slug from a given string.
     *
     * @param string $string The string to be converted to a slug.
     * @return string The generated slug.
     */
    public static function generateSlug(Model $model, string $string): string
    {
        $initialSlug = Str::slug($string);

        $slug = $initialSlug;
        $i = 2;

        while (static::checkSlugExists($model, $slug)) {
            $newSlug = $initialSlug . '-' . $i++;
            $slug = $newSlug;
        }

        return $slug;
    }

    /**
     * Checks if a given slug exists in the database.
     *
     * @param Model $model The model instance for which the slug is generated.
     * @param string $slug The slug to be checked.
     * @return bool True if the slug exists, false otherwise.
     */
    public static function checkSlugExists(Model $model, string $slug): bool
    {
        return $model::where('slug', $slug)->exists();
    }
}
