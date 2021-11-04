<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Mail\CommentPostedNotification;
use App\Models\BlogPost;
use App\Repositories\UserPreferenceRepository;
use Illuminate\Support\Facades\Mail;

class NotifyWhenCommentPosted
{

    /**
     * @var UserPreferenceRepository
     */
    protected $preferenceRepository;

    public function __construct(UserPreferenceRepository $preferenceRepository){
        $this->preferenceRepository = $preferenceRepository;
    }

    /**
     * Handle the event.
     *
     * @param  CommentPosted  $event
     * @return void
     */
    public function handle(CommentPosted $event)
    {
        $comment = $event->comment;
        $commentable = $comment->commentable;

        if($commentable instanceof BlogPost){
            $author = $commentable->user;

            if($author->id === $comment->user_id || !$author->email){
                return;
            }

            if($this->preferenceRepository->get($author, 'email_on_comment', false)){
                Mail::to($author->email)
                    ->queue(new CommentPostedNotification($comment->user, $commentable, $comment));
            }
        }
    }
}
