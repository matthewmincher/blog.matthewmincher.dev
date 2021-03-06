<?php

namespace App\Models;

use App\Observers\Traits\InvalidatesTagsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * App\Models\BlogPost
 *
 * @property int $id
 * @property int $blog_category_id
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property int $published
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property-read \App\Models\BlogCategory $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlogPostComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlogTag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereBlogCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogPost whereUserId($value)
 * @mixin \Eloquent
 */
class BlogPost extends Model
{
    use HasFactory, SoftDeletes, InvalidatesTagsTrait;

    protected $fillable = ['title', 'content', 'slug', 'user_id', 'blog_category_id', 'published'];

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function category(){
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
    public function tags(){
        return $this->belongsToMany(BlogTag::class, 'blog_post_tags');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeOrdered(Builder $query){
        return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
    }

    public function scopePublished(Builder $query){
        return $query->where('published', '=', true);
    }

    public function getCombinedSlugAttribute(){
        return $this->id . '-' . $this->slug;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if($field === 'combined_slug'){
            $id = (int) Str::before($value, '-');
            return $this->where($this->getRouteKeyName(), $id)->first();
        }

        return parent::resolveRouteBinding($value, $field);
    }


}
