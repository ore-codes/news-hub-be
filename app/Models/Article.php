<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * @OA\Schema(
 *   schema="Article",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="title", type="string"),
 *   @OA\Property(property="content", type="string"),
 *   @OA\Property(property="author", type="string"),
 *   @OA\Property(property="source", type="string"),
 *   @OA\Property(property="category", type="string"),
 *   @OA\Property(property="published_at", type="string", format="date-time"),
 *   @OA\Property(property="url", type="string"),
 *   @OA\Property(property="url_to_image", type="string"),
 * )
 */
class Article extends Model
{
    use Searchable;

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

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author,
            'source' => $this->source,
            'category' => $this->category,
            'published_at' => $this->published_at,
            'url' => $this->url,
            'url_to_image' => $this->url_to_image,
        ];
    }
}
