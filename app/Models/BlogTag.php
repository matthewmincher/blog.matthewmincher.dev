<?php

namespace App\Models;

use App\Observers\Traits\InvalidatesTagsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * App\Models\BlogTag
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlogPost[] $posts
 * @property-read int|null $posts_count
 * @method static \Database\Factories\BlogTagFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogTag whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogTag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogTag whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BlogTag extends Model
{
    use HasFactory, InvalidatesTagsTrait;

    protected $fillable = ['title', 'slug', 'content'];

    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;

        $this->setAttribute('slug', Str::slug($value));
    }

    public function posts(){
        return $this->belongsToMany(BlogPost::class, 'blog_post_tags');
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
