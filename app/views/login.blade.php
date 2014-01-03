

@section('content')
<style type="text/css">
    button#loginform{
        position:relative;
        margin:0 auto;
    }
</style>
<div class="row-fluid">
    <div id="loginform" class="span4 offset4">
      <center>
      <div class="well">
        <legend><img src="{{URL::to('/ger/storetracker-logo.png')}}" alt="StoreTracker" width="240px" /></legend>
        
            <!--<div class="alert alert-error">
                <a class="close" data-dismiss="alert" href="#">x</a>Incorrect Username or Password!
            </div>-->      
            
            <input class = "span8" placeholder="Username" type="text" name="username" id="username">
            <br/>
            <input class = "span8" placeholder="Password" type="password" name="password" id="password"> 
            <br/>
            <button class="btn-large btn-primary" id="loginform"><a class="icon-lock"></a></button>   

           
      </div>
  	 </center>
    </div>
</div>

@stop
@section('ajax')
<script type="text/javascript">
$(document).ready(function() {

    $('button#loginform').on('click',function() {
			$.ajax({
		        type: "POST",
		        url: 'login',
                beforeSend : function (){
                    $('div#loginform').block({ 
                        message: '<span class="label label-warning">Processing</span>', 
                        css: { border: '3px solid black' }
                    }); 
                },
		        data: {
		            username: $("input#username").val(),
		            password: $("input#password").val()
		        },
		        success: function(data)
		        {
                    $('div#loginform').unblock(); 
		            if (data === 'Login') {
		                window.location = "hello";
		            }
		            else {
		                alert('Login Query Failed');
		            }

		        }
		    });
	});

});
</script>

@stop
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>StoreTracker</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{URL::to('/ger')}}/favicon.png" rel="icon" />
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="{{URL::to('/')}}/packages/bootstrap/css/st.min.css">
        <link rel="stylesheet" href="{{URL::to('/')}}/packages/pnotify/jquery.pnotify.default.css">
        <style type="text/css">
            #loginform {
                position: fixed;
            }
        </style>
    </head>
    <body>

        <div id ="bodyG">
    	   @yield('content')
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.2.min.js"><\/script>')</script>
        <script src="{{URL::to('/')}}/packages/bootstrap/js/bootstrap.min.js"></script>
        <script src="{{URL::to('/')}}/packages/bootstrap/js/blockui.js"></script>
        <script src="{{URL::to('/')}}/packages/pnotify/jquery.pnotify.min.js"></script>
        @yield('ajax')
    </body>
</html>