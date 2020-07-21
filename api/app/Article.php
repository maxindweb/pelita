<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'slug',
        'body',
        'user_id',
        'category_id',
        'tag_id',
        'is_publsih',
        'published_at'
    ];

    // relation one to many from user to article
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    // relation category to article(one to many) 
    public function Category()
    {
        return $this->belongsTo(ArticleCategory::class);
    }

    // many to many relation with pivot table
    public function Tag()
    {
        return $this->belongsToMany(Tag::class);
    }
}
