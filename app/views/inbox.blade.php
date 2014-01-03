@extends('master')

@section('titlepage')
Inbox
@stop

@section('Istate')
active
@stop

@section('header')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/packages/datatables/DT_bootstrap.css">
<link rel="stylesheet" href="{{URL::to('/')}}/packages/bootstrap/css/datepicker.css">
<style type="text/css">
div#inboxDiv {
	padding: 20px;
}
div#inboxTabContent{
	border-radius: 20px;
	padding: 10px;
	border-width:medium;
	overflow: auto;
	max-height: 530px;
}
div#loadTable{
	position: absolute;
	bottom: 50%;
	left: 50%;
}
</style>
@stop

@section('footer')
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/jquery.datatables.js"></script>
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/DT_bootstrap.js"></script>

<script type="text/javascript">
$(document).ready( function () {

	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	 
	var checkin = $('#ifromDate').datepicker({
	  onRender: function(date) {
	    return date.valueOf() < now.valueOf() ? 'enabled' : '';
	  }
	}).on('changeDate', function(ev) {
	  if (ev.date.valueOf() > checkout.date.valueOf()) {
	    var newDate = new Date(ev.date)
	    newDate.setDate(newDate.getDate() + 1);
	    checkout.setValue(newDate);
	  }
	  checkin.hide();
	  $('#itoDate')[0].focus();
	}).data('datepicker');
	var checkout = $('#itoDate').datepicker({
	  onRender: function(date) {
	    return date.valueOf() <= checkin.date.valueOf() ? '' : '';
	  }
	}).on('changeDate', function(ev) {
	  checkout.hide();
	}).data('datepicker');

} );

$(document).ready( function () {

		$.ajax({
			  type: "POST",
			  url: "inbox/inbox",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#inboxTable").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#inboxTable").html(html);
		});


	$('button#allInbox').on('click',function() {
		$.ajax({
			  type: "POST",
			  url: "inbox/inboxall",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#inboxTable").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#inboxTable").html(html);
		});
	});

	$('button#todayInbox').on('click',function() {
		$.ajax({
			  type: "POST",
			  url: "inbox/inbox",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#inboxTable").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#inboxTable").html(html);
		});
	});

	$('button#filterInbox').on('click',function() {
		var Obj = {};
		Obj.from= $('input#ifromDate').val();
		Obj.to= $('input#itoDate').val();
		$.ajax({
			  type: "POST",
			  url: "inbox/inboxrange",
			  cache: false,
			  data: Obj,
			  beforeSend: function (tableload) {
			  	$("div#inboxTable").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#inboxTable").html(html);
		});
	});


} );
</script>
@stop

@section('content')

<div id="inboxDiv" class="tabbable tabs-left">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#view" data-toggle="tab">Inbox</a></li>
  </ul>

  <div id="inboxTabContent" class="tab-content">
    <div id="view" class="tab-pane active">
      
      <h4>View</h4>
	      <div class="well">
	     	
	      	<div class="form-inline">
		      		
		      		<label for="ifromDate">From</label>
		      		<div class="input-append date">
		      		<input type="text" data-date-format="yyyy-mm-dd" id="ifromDate" readonly><span class="add-on"><i class="icon icon-th"></i></span>
		      		</div>

		      		<label for="itoDate">To</label>
		      		<div class="input-append date" >
		      		<input type="text" id="itoDate" data-date-format="yyyy-mm-dd" readonly><span class="add-on"><i class="icon icon-th"></i></span>
		      		</div>

		      		<button id="filterInbox" class="btn btn-primary">Filter</button>
		      		<button id="todayInbox" class="btn btn-inverse">Today</button>
		      		<button id="allInbox" class="btn btn-info">All</button>
		      	<hr/ >
		      		
		      			<div id="inboxTable"></div>
		      		
		      	</div>
	      </div>
    </div>
    
  </div><!-- /.tab-content -->
</div><!-- /.tabbable -->
@stop