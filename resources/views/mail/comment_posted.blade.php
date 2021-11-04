@component('mail::message')
# New Comment

{{$user->name}} has left a new comment on <a href="{{route('posts.show', ['blog_post' => $post])}}">{{$post->title}}</a>.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
