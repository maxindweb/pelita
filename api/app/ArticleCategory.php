<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    protected $fillable = [
        'category',
        'slug'
    ];

    public function Article()
    {
        return $this->hasMany(Article::class);
    }
}
