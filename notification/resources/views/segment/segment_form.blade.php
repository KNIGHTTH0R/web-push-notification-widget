@extends("layouts.master")
@section('content')
  <div class="content">
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <form method="POST" action="/segment" role="form">
          {!! csrf_field() !!}
          
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name of the segment">
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea type="text-area" class="form-control" name="description" placeholder="Please provide a description">{{ old('description') }}</textarea>
          </div>

          <div class="form-group row">
            <select name = "browser[]" title="Select Browsers" class="selectpicker col-lg-12" multiple="true">
              @foreach ($segmentBrowsers as $key=>$browser)
                <option value={{ $key }}>{{ $browser }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group row">
            <select name = "registration" title="Select Registration Criteria" class="selectpicker col-lg-5">
              @foreach ($segmentRules as $key=>$segmentRule)
                <option value={{ $key }}>{{ $segmentRule }}</option>
              @endforeach
            </select>
            <input type="text" class="form-control col-xs-3" name="registration_date" value="{{ old('registration_date') }}">
          </div>         

          <button type="submit" class="btn btn-default">Send Notification</button>

        </form>
      </div>
    </div>
  </div>
@stop