<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BlogPostComment
 *
 * @property int $id
 * @property int $blog_post_id
 * @property string $title
 * @property string $content
 * @property int $published
 * @property string $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BlogPost $post
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment whereBlogPostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPostComment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BlogPostComment extends Model
{
    use HasFactory;

    public function post(){
        return $this->belongsTo(BlogPost::class);
    }
}
