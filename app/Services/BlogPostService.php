<?php
namespace App\Services;

use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BlogPostService {
    public function createPostForUser($validated, User $user): BlogPost {
        if($validated['slug'] === null){
            $validated['slug'] = Str::slug($validated['title']);
        }

        $post = $user->posts()->create($validated);

        self::synchroniseTags($validated['tags'], $post);

        return $post;
    }
    public function updatePost($validated, BlogPost $blogPost): void {
        $blogPost->fill($validated)->save();
        self::synchroniseTags($validated['tags'], $blogPost);
    }
    public function deletePost(BlogPost $blogPost): void {
        $blogPost->tags()->detach();
        $blogPost->delete();
    }

    private static function synchroniseTags($tagList, BlogPost $blogPost){
        $existingTags = BlogTag::all();
        $selectedTags = new Collection();

        if(is_array($tagList)){
            foreach($tagList as $tagTitle){
                $tag = $existingTags->firstWhere('title', $tagTitle);

                if($tag === null){
                    $tag = BlogTag::create(['title' => $tagTitle, 'slug' => Str::slug($tagTitle)]);
                }

                $selectedTags->add($tag);
            }
        }

        $blogPost->tags()->sync($selectedTags);
    }
}
