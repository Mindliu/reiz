<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string uuid
 * @property string url
 * @property array selectors
 * @property array content
 * @property string status
 * @property Carbon created_at
 */
class ScrapeJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'url',
        'selectors',
        'status',
        'content',
    ];

    protected $casts = [
        'selectors' => 'array',
        'content' => 'array',
    ];
}
