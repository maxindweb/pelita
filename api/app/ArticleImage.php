<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleImage extends Model
{
    protected $fillable = [
        'name',
        'path',
        'caption',
        'alt'
    ];

    public function Article()
    {
        return $this->belongsTo(Article::class);
    }
}
