<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@yield('titlepage') | StoreTracker</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{URL::to('/ger')}}/favicon.png" rel="icon" />
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="{{URL::to('/')}}/packages/jquery-ui/css/smoothness/jquery-ui.min.css">
        <link rel="stylesheet" href="{{URL::to('/')}}/packages/bootstrap/css/st.min.css">
        <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/select2/3.4.1/select2.min.css">
        <link href='http://fonts.googleapis.com/css?family=Nunito' rel='stylesheet' type='text/css'>
        
        <style type="text/css">
        div#out{
        	float: right;
			padding-right: 8%;
        }
        #mainnavbar{
/*            position: fixed;
            width: 100%;*/
        }
        </style>
        @yield('header')
                <script src="{{URL::to('/')}}/packages/jquery.min.js"></script>
    </head>
    <body>