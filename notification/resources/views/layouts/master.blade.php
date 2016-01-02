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
      <nav class="navbar navbar-fixed-top topnav" role="navigation">
        @include('partials.menu')
      </nav>
    </header>

    <div class="main-bg">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1">
            <div class="site-heading">
              <h1>RE-ENGAGE WITH PUSHFLIX</h1>
              <h2><i>Pushflix lets you send targeted notifications to your users</i></h2>
              <a href="" class="btn btn-xl">WATCH OVERVIEW</a>
              <a href="" class="btn btn-xl-transparent">HAVE A DEMO</a>
            </div>
          </div>
        </div>
      </div>
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
