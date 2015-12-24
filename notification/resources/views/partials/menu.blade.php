
<div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">PushFlix</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li>
                <a href="#" title="Official PushFlix">About</a>
            </li>
            <li>
                <a href="#" title="Contact the Start Bootstrap Team">Contact</a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Premium Bootstrap Themes &amp; Templates"><i class="fa fa-star text-yellow"></i> Get Started <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li>
                      <a href="{{ url('auth/login') }}"><i class="fa fa-fw fa-paint-brush"></i> Login </a>
                  </li>
                  <li>
                      <a href="{{ url('auth/register') }}"><i class="fa fa-fw fa-shopping-cart"></i> Sign Up </a>
                  </li>
                  <li>
                      <a href="{{ url('auth/logout') }}"><i class="fa fa-fw fa-shopping-cart"></i> Log out </a>
                  </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</div>
<!-- /.container -->
