@extends('layouts.main')

@section("content")

<div class="bg-dark text-secondary px-4 py-5 text-center">
    <div class="py-5">
      <h1 class="display-5 fw-bold text-white">{{$project->name}}</h1>
      <div class="col-lg-6 mx-auto">
        <p class="fs-5 mb-4">{{$project->description}}</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
          <a type="button" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold" href="/">Create Project</a>
          {{-- <button type="button" class="btn btn-outline-light btn-lg px-4">Secondary</button> --}}
        </div>
      </div>
    </div>
</div>

{{-- projects --}}
<div class="row g-4 py-5">

    <div class="col-md-8">
    {{-- @foreach ($project->projects as $project)
    <div class="col d-flex align-items-start" style="background: white; margin:1em; padding:1em">
     
      <div>
        <h3 class="fs-2 text-body-emphasis">{{$project->name}}</h3>
        <p>{{$project->description}}</p>
        <a href="/projects/{{$project->id}}" class="btn btn-primary">
          详细信息
        </a>
      </div>
    </div>
    @endforeach --}}

    {{-- add comments --}}
  <form method="POST" action="{{route('comments.store')}}">

    {{csrf_field()}}
      <input type="hidden" name="commentable_type" value="App\Models\Project">
      <input type="hidden" name="commentable_id" value="{{$project->id}}">

      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Comment<span class="required">*</span></label>
        
        <textarea type="text" class="form-control" style="resize: vertical" rows="5" name="body"></textarea>
        <div id="emailHelp" class="form-text">say anything you want about this project!</div>
      </div>
     

      <button type="submit" class="btn btn-primary">Comment</button>
  </form>
  {{-- show comments --}}
  {{-- @include('partials.comments') --}}
  </div>

  

  <div class="col-md-4">
    <div class="position-sticky" style="top: 2rem;">
      <div class="p-4 mb-3 bg-body-tertiary rounded">
        <h4 class="fst-italic">Actions</h4>
        <ul class="p-4">
            <li class="p-1"><a href="/projects/{{$project->id}}/edit">Edit</a></li>
            <li class="p-1">
            <a href="#" onclick="
                var result= confirm('Are you sure to delete this project!');
                if(result){
                    event.preventDefault();
                    document.getElementById('delete-form').submit();
                }">Delete</a>    
            <form id="delete-form" action="{{route('projects.destroy', [$project->id])}}" method="POST" style="display: none">
            <input type="hidden" name="_method" value="delete">
            {{ csrf_field() }}
            </form>
            
            </li>
            <li class="p-1"><a href="{{route('projects.index')}}">Back</a></li>
        </ul>
       </div>

      <div>
        <h4 class="fst-italic">All Members</h4>
        <ul class="list-unstyled">
          <li>
            <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
              <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"></rect></svg>
              <div class="col-lg-8">
                <h6 class="mb-0">Example blog post title</h6>
                <small class="text-body-secondary">January 15, 2023</small>
              </div>
            </a>
          </li>
          <li>
            <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
              <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"></rect></svg>
              <div class="col-lg-8">
                <h6 class="mb-0">This is another blog post title</h6>
                <small class="text-body-secondary">January 14, 2023</small>
              </div>
            </a>
          </li>
          <li>
            <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
              <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"></rect></svg>
              <div class="col-lg-8">
                <h6 class="mb-0">Longer blog post title: This one has multiple lines!</h6>
                <small class="text-body-secondary">January 13, 2023</small>
              </div>
            </a>
          </li>
        </ul>
      </div>

      <div class="p-4">
        <h4 class="fst-italic">Archives</h4>
        <ol class="list-unstyled mb-0">
          <li><a href="#">March 2021</a></li>
          <li><a href="#">February 2021</a></li>
          <li><a href="#">January 2021</a></li>
          <li><a href="#">December 2020</a></li>
          <li><a href="#">November 2020</a></li>
          <li><a href="#">October 2020</a></li>
          <li><a href="#">September 2020</a></li>
          <li><a href="#">August 2020</a></li>
          <li><a href="#">July 2020</a></li>
          <li><a href="#">June 2020</a></li>
          <li><a href="#">May 2020</a></li>
          <li><a href="#">April 2020</a></li>
        </ol>
      </div>

      <div class="p-4">
        <h4 class="fst-italic">Elsewhere</h4>
        <ol class="list-unstyled">
          <li><a href="#">GitHub</a></li>
          <li><a href="#">Twitter</a></li>
          <li><a href="#">Facebook</a></li>
        </ol>
      </div>
    </div>
  </div>
</div>



  
@endsection