@extends(".layouts.master")

@section("content")
  <div class="content">
    
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <!-- resources/views/auth/register.blade.php -->
        <form method="POST" action="/auth/register">
            {!! csrf_field() !!}

            <div class="form-group">
                Name
                <input type="text" name="name" value="{{ old('name') }}" class="form-control">
            </div>

            <div class="form-group">
                Email
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
                
            </div>

            <div class="form-group">
                Password
                <input type="password" name="password" class="form-control" id="password">
            </div>

            <div class="form-group">
                Confirm Password
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
            </div>

            
            <button type="submit" class="btn btn-default">Register</button>
            
        </form>
      </div>
    </div>

  </div>
@stop
                
           