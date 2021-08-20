<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BlogPost;

class CreateBlogPostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_post_comments', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(BlogPost::class)->constrained();

            $table->string('title');
            $table->text('content');

            $table->boolean('published');
            $table->timestamp('published_at');

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
        Schema::dropIfExists('blog_post_comments');
    }
}
