@extends('master')

@section('titlepage')
Hello!
@stop

@section('Mstate')
active
@stop

@section('header')

<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/packages/datatables/DT_bootstrap.css">
	<style type="text/css">
		#helloDiv{
			max-height: 500px;
			overflow: auto;
			top: 50%;
    		left: 50%;
    		margin: auto;
		}
		#loadTable{
			position: absolute;
			top: 50%;
    		left: 50%;
    		margin: auto;
		}
	</style>
@stop

@section('footer')

<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/jquery.datatables.js"></script>

<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/DT_bootstrap.js"></script>

<script type="text/javascript">
	$(document).ready( function () {
		$('table#helloTable').dataTable( {
			"aoColumns": [
				null,
				{ "asSorting": [ "asc" ] }
			]
		} );
	} );
</script>

<script type="text/javascript">
	$(document).ready( function () {
		function updataTable(){
			$.ajax({
			  url: "helloTable",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#helloDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-line-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#helloDiv").html(html);
			});
			setTimeout(updataTable, 20000);
		}
		updataTable();
	} );
</script>

@stop

@section('content')

	<div id="helloDiv" class="span6 center" >

	</div>



@stop