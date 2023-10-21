<form action="{{route('tasks.store')}}" method="POST" class="form">

@csrf
<div>
    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control">
    @error('name')
        <p>{{$message}}</p>
    @enderror
</div>
{{-- select user --}}
<div class="form-group">
    <label for="user_id">User</label>
    <select name="user_id" id="user_id" class="form-control">
        @foreach ($users as $user)
            <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
    </select>
    @error('user_id')
        <p>{{$message}}</p>
    @enderror
<div>
    <label for="description">Body:</label>
    <textarea name="body" id="body" cols="30" rows="10" class="form-control">{{old('body')}}</textarea>
    @error('body')
        <p>{{$message}}</p>
    @enderror
</div>
{{-- submit --}}
<div>
    <input type="submit" value="Create Task">
</div>

</form>