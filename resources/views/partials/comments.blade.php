@if($comments->count()>0)


<div class="list-group mt-6">
  
   
    @foreach($comments as $comment)

    <a href="/users/{{$comment->user->id}}" class="list-group-item list-group-item-action" aria-current="true">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">{{$comment->user->name}}</h5>
          <small>commented on {{$comment->created_at->diffForHumans()}}</small>
        </div>
        <p class="mb-1">{{$comment->body}}</p>
        {{-- <small>And some small print.</small> --}}
      </a>
     
    @endforeach
</div>
@else
    <p>No comments yet!</p>
@endif