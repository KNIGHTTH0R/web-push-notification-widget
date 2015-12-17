<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Larawings - Laravel 5.1 template</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css"/>
        
    @if ( Config::get('app.debug') )
        <link rel="stylesheet" href="css/final.css" />
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
    @yield('content')
</div>

<footer class="footer navbar-fixed-bottom">
    
</footer>

<!-- Scripts -->

<script src="//code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
<script type="text/javascript">
   $("#login-button").click(function(event){
     event.preventDefault();
   
   $('form').fadeOut(500);
   $('.wrapper').addClass('form-success');
});
</script>

</body>
</html>