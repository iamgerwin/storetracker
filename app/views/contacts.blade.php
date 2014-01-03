@extends('master')

@section('titlepage')
Contacts
@stop

@section('Cstate')
active
@stop

@section('header')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/packages/datatables/DT_bootstrap.css">
<style type="text/css">
div#contactsDiv{
	padding: 10px;
}
div#contactsTabContent{
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

	function loadContact() {
		$.ajax({
			  type: "POST",
			  url: "contacts/loadcontacts",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#contactTable").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#contactTable").html(html);
		});
	}
		loadContact();
		$('button#addButton').on('click',function() {
			var Obj = {};
				Obj.salesreport= $( "input#salesreport:checked" ).length;
				Obj.active= $( "input#active:checked" ).length;
				Obj.accountcode =$('input#accountcode').val();
				Obj.mobilenumber =$('input#mobilenumber').val();
				Obj.firstname =$('input#firstname').val();
				Obj.middlename =$('input#middlename').val();
				Obj.lastname =$('input#lastname').val();

			$.ajax({
				  type: "POST",
				  url: "contacts/add",
				  cache: false,
			  	  data: Obj,
				  beforeSend: function (tableload) {
				  	$("div#addMessage").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-line-black.gif" /></div>');
				  }
				})
				.done(function( html ) {	  
					  $("div#addMessage").html(html);
				});
				loadContact();
		});

} );
</script>
@stop

@section('content')



<div id="contactsDiv" class="tabbable tabs-left">
  <ul class="nav nav-tabs">

    <li class="active"><a href="#view" data-toggle="tab">Contacts</a></li>

  </ul>

  <div id="contactsTabContent" class="tab-content">
    <div id="view" class="tab-pane active">
      <h4>View</h4>
	      <div class="well">
	      	<div class="form-inline">
	      		<!--trigger addNewContact modal -->
<a href="#addNewContact" role="button" class="btn btn-success" data-toggle="modal"><i class="icon-plus"></i>Add New</a>

		    	<hr />
		      		<div id="contactTable"></div>
		      		
		    </div>
	   </div>
    </div>
    
  </div><!-- /.tab-content -->
</div><!-- /.tabbable -->


 
<!-- addNewContact Modal -->
<div id="addNewContact" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myAddmodal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myAddmodal">Add new Contact</h3>
  </div>
  <div id="addContactBody"class="modal-body">
  	<div id="addMessage"></div>
    <fieldset class="form-horizontal">
    	<div class="span3">
			<label>Account Code</label>
		    <input type="text" id="accountcode" placeholder="Account Code">
			<label>Mobile Number</label>
		    <input type="text" id="mobilenumber" placeholder="Mobile Number">
		    <label class="checkbox">
		      <input id="salesreport" type="checkbox"> Receive Sales Report
		    </label>
		    <label class="checkbox">
		      <input id="active" type="checkbox" checked> Active
		    </label>
	    </div>
	    <div class="span3">
			<label>Firstname</label>
		    <input type="text" id="firstname" placeholder="Firstname">
			<label>Middlename</label>
		    <input type="text" id="middlename" placeholder="Middlename">
			<label>Lastname</label>
		    <input type="text" id="lastname" placeholder="Lastname">
	    </div>
    </fieldset>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="addButton" class="btn btn-primary">Add</button>
  </div>
</div>



@stop