@extends("layouts.master")

@section("content")
  <div class="content">
    
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <form method="POST" action="/auth/login">
          {!! csrf_field() !!}
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
          </div>
          
          <div class="checkbox">
            <label>
              <input type="checkbox" name="remember"> Remember me
            </label>
          </div>
          <button type="submit" class="btn btn-default">Submit</button>

        </form>   
      </div>
    </div>

  </div>
@stop