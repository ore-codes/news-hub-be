<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'author',
        'source',
        'category',
        'published_at',
        'source_id',
        'url',
        'url_to_image',
        'raw_json',
    ];
}
