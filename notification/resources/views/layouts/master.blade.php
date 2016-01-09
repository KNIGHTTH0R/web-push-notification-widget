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
              <h1>Re-engage your users with Pushflix</h1>
              <h2><i>Pushflix lets you send targeted notifications to your users</i></h2>
              <ul class="list-inline intro-social-buttons">
                  <li>
                      <a href="" class="btn btn-xl btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">WATCH OVERVIEW</span></a>
                  </li>
                  <li>
                      <a href="" class="btn btn-xl-transparent btn-lg"><i class="fa fa-github fa-fw"></i> <span class="network-name">HAVE A DEMO</span></a>
                  </li>
              </ul>
              <!-- <a href="" class="btn btn-xl">WATCH OVERVIEW</a>
              <a href="" class="btn btn-xl-transparent">HAVE A DEMO</a> -->
            </div>
          </div>
        </div>

        <div class="row image-placeholder">
          <div class="col-lg-12">
            <img src="../images/macbook_and_phone.png">
          </div>
        </div>
      </div>
    </div>

    <div class="content send-notification" style="text-align: center;">
      <div class="container-fluid">
        <h1>Easy to Send Notification</h1>
        <div class="row">
          <div class="col col-lg-4">
            <h2>Get your code</h2>
            <img src="../images/get-code.png">
          </div>
          <div class="col col-lg-4">
            <h2>Create Notification</h2>
            <img src="../images/create-notification.png">
            <img src="../images/create-notification.png">
            <img src="../images/create-notification.png">
          </div>
          <div class="col col-lg-4">
            <h2>Preview & Send</h2>
            <img src="../images/notification.png">
            <img src="../images/notification.png">
            <img src="../images/notification.png">
          </div>
        </div>
      </div>
    </div>

    <div class="content schedule-notification" style="text-align: center;">
      <div class="container-fluid">
        <h1>Schedule your Notification in Advance</h1>
        <h3>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</h3>
        <div class="row">
          <div class="col col-lg-5">
            <img src="../images/notification.png">
          </div>
          <div class="col col-lg-5">
            <img src="../images/notification.png">
          </div>
        </div>

        <div class="row">
          <div class="col col-lg-5">
            <img src="../images/notification.png">
          </div>
          <div class="col col-lg-5">
            <img src="../images/notification.png">
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
