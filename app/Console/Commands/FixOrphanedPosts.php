<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Console\Command;

class FixOrphanedPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:orphanedposts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix any posts that belong to non-existent categories';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $postsMissingCategories = BlogPost::whereDoesntHave('category')->get();
        $oldestCategory = BlogCategory::oldest()->take(1)->get()->first();

        foreach($postsMissingCategories as $post){
            $post->category()->associate($oldestCategory);
            $post->save();
        }

        return Command::SUCCESS;
    }
}
