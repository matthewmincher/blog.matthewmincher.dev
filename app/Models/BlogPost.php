<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    public function comments(){
        return $this->hasMany(BlogPostComment::class);
    }
    public function category(){
        return $this->belongsTo(BlogCategory::class);
    }
    public function tags(){
        return $this->belongsToMany(BlogTag::class, 'blog_post_tags');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }


}
