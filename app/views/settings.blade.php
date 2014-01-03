@extends('master')

@section('titlepage')
Settings
@stop

@section('Sestate')
active
@stop

@section('header')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/packages/datatables/DT_bootstrap.css">
<style type="text/css">
div#settingsDiv{
	padding: 10px;
}
div#settingsTabContent{
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

<link rel="stylesheet" href="{{URL::to('/')}}/packages/pnotify/jquery.pnotify.default.css">

@stop

@section('footer')
<script src="{{URL::to('/')}}/packages/pnotify/jquery.pnotify.min.js"></script>
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/jquery.datatables.js"></script>
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/DT_bootstrap.js"></script>

<script type="text/javascript">
$(document).ready( function () {

	function loadContact() {
		$.ajax({
			  type: "POST",
			  url: "settings/whitelist",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#whitelistTable").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#whitelistTable").html(html);
		});
	}
		loadContact();
} );
</script>


@stop

@section('content')
<div id="settingsDiv" class="tabbable tabs-left">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#changePasswordTab" data-toggle="tab">Change Password</a></li>
    <li class=""><a href="#whiteListTab" data-toggle="tab">Whitelist</a></li>
  </ul>

  <div id="settingsTabContent" class="tab-content">

    <div id="changePasswordTab" class="tab-pane active">
      <h4>Change Password</h4>
	      <div class="well">
	      	<div class="form">
	      		
	      		<div id="messageChangePassword"></div>
		    	<hr />
		    	<center>

		      		<label for='oldPassword'>Current Password</label>
		      		<input type='password' id='oldPassword'>
		      		</input>
		      		<label for='newPassword'>New Password</label>
		      		<input type='password' id='newPassword'>
		      		</input>
		      		<label for='confirmPassword'>Confirm New Password</label>
		      		<input type='password' id='confirmPassword'>
		      		</input>
<button id="submitChangePassword" class="btn btn-large btn-block btn-primary" type="button">Change Password</button>
		      	</center>

<script type="text/javascript">
$(document).ready( function () {
	$("button#submitChangePassword").click(function() {
	  var Enter = {};
	  		Enter.oldPassword = $("input#oldPassword").val();
	  		Enter.newPassword = $("input#newPassword").val();
	  		Enter.confirmPassword = $("input#confirmPassword").val();
	  	$.ajax({
	  			type: 'post',
	  			url: 'settings/settings',
	  			data: Enter,
	  			beforeSend: function(tableload) {
	  				$("div#messageChangePassword").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
	  			}
	  		}).done(function( html ) {
	  			$("#loadTable").remove();
	  			$("div#messageChangePassword").append(html);
	  		});
	});
	$("button#addWhitelistSubmit").click(function() {
	  var Add = {};
	  	Add.mobileNumber = $("input#aMobileNumber").val();
	  	Add.remarks = $("input#aRemarks").val();
	  $.ajax({
	  	type: 'post',
	  	url:'settings/addwhitelist',
	  	data: Add,
	  	beforeSend: function(tableload) {
	  				$("div#addWhitelistMessage").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
	  			}
	  		}).done(function(html) {
	  			$("#loadTable").remove();
	  			$("div#addWhitelistMessage").append(html);
	  			loadContact();
	  		});
	  });
} );
</script>

		    </div>
	   </div>
    </div>

  	<!--whiteListTab-->
  	<div id="whiteListTab" class="tab-pane">
  		
  		<h4>White List</h4>
	      <div class="well">
	      	<div class="form-inline">
	      		<!-- Button to trigger modal -->
				<a href="#addWhitelistModal" role="button" class="btn btn-success" data-toggle="modal"><i class="icon icon-plus"></i>Add</a>
				 
				<!-- Modal -->
				<div id="addWhitelistModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				    <h3 id="myModalLabel">Add Whitelist</h3>
				  </div>
				  <div class="modal-body">
				    <div id="addWhitelistMessage"></div>
				  	<fieldset class="form-horizontal">
				  		<div class="span3">
				  			<label for="aMobileNumber">Mobile Number</label>
				  			<input id="aMobileNumber" type="text"></input>
				  		</div>
				  		<div class="span3">
				  			<label for="aRemarks">Remarks</label>
				  			<input id="aRemarks" type="text"></input>
				  		</div>
				  	</fieldset>
				  </div>
				  <div class="modal-footer">
				    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				    <button id="addWhitelistSubmit" class="btn btn-success">Add Whitelist</button>
				  </div>
				</div>

		    	<hr />
		     <div id="whitelistTable"></div>	
		    </div>
  	</div>  

  </div><!-- /.tab-content -->
</div><!-- /.tabbable -->

@stop