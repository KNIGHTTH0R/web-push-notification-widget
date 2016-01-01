<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PushFlix</title>

    @if ( Config::get('app.debug') == true )
        <link rel="stylesheet" href="/css/final.css" />
    @else 
        <link href="{{ elixir('css/final.css') }}" rel="stylesheet" type="text/css">
    @endif
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

  <div class="wrapper">
    <header>
      <nav class="navbar" role="navigation">
        @include('partials.menu')
      </nav>
    </header>

    <div class="container">

      @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
        </div>
      @endif
      
      @yield('content')  
    </div>

    <footer class="footer navbar-fixed-bottom">
        
    </footer>

    <!-- Scripts -->
    @if ( Config::get('app.debug') == true )
      <!-- Latest compiled and minified JavaScript -->
      <script src="/js/app.js"></script>
    @else 
      <script src="{{ elixir("js/app.js") }}"></script>
    @endif
  </div>
</body>
</html>
