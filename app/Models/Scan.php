<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    //
    protected $fillable = ['data', 'format', 'type'];

    public function isUrl(): bool
    {
        return $this->type === 'url';
    }

    public static function detectType(string $data): string
    {
        return filter_var($data, FILTER_VALIDATE_URL) ? 'url' : 'text';
    }
}
