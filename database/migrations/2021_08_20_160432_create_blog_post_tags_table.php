<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BlogPost;
use App\Models\BlogTag;

class CreateBlogPostTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BlogTag::class);
            $table->foreignIdFor(BlogPost::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_post_tags');
    }
}
