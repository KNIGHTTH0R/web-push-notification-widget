@extends("layouts.master")
@section('content')
  <div class="content">
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <form method="POST" action="/notification">
          {!! csrf_field() !!}
          
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Title">
          </div>

          <div class="form-group">
            <label for="body">Message</label>
            <textarea type="text-area" class="form-control" name="body" placeholder="Message">{{ old('body') }}</textarea>
          </div>

          <div class="form-group">
            <label for="icon_url">Icon URL</label>
            <input type="text" class="form-control" name="icon_url" value="{{ old('icon_url') }}" placeholder="https://mydomain.com/myimage.jpeg">
          </div>

          <div class="form-group">
            <label for="icon_url">Redirect URL</label>
            <input type="text" class="form-control" name="redirect_url" value="{{ old('redirect_url') }}" placeholder="https://mydomain.com/redirect">
          </div>

          <button type="submit" class="btn btn-default">Send Notification</button>

        </form>
      </div>
    </div>
  </div>
@stop