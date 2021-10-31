<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * App\Models\BlogCategory
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlogPost[] $posts
 * @property-read int|null $posts_count
 * @method static \Database\Factories\BlogCategoryFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BlogCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BlogCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'content'];

    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;

        $this->setAttribute('slug', Str::slug($value));
    }

    public function posts(){
        return $this->hasMany(BlogPost::class);
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
