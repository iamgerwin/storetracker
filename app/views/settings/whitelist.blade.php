<link rel="stylesheet" href="{{URL::to('/')}}/packages/pnotify/jquery.pnotify.default.css">
<script src="{{URL::to('/')}}/packages/jquery.min.js"></script>

<script src="{{URL::to('/')}}/packages/jquery-ui/js/jquery-ui.min.js"></script>

<script src="{{URL::to('/')}}/packages/pnotify/jquery.pnotify.min.js"></script>
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/jquery.datatables.js"></script>
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/DT_bootstrap.js"></script>



<script type="text/javascript">

	$(document).ready( function () {
		$('table#settingsTable').dataTable( {
    		"aaSorting": [[ 0, "desc" ]]
    	});
		
		
	} ); 
</script>

<div id="messageWhitelist"></div>
<table cellpadding="0" cellspacing="0" border="0" class="table bordered-table table-hover" id="settingsTable">
	<thead>
		<tr>
			<th>Mobile Number</th>
			<th>Remarks</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php $i = 0; ?>
@foreach($datas as $data)

	<tr>
		<input id="id{{$i}}" type="hidden" value= "{{$data->whitelist_id}}" />
		<td><input id="mobileNumber{{$i}}" type="text" value= "{{$data->mobile_number}}" /></td>
		<td><input id="remarks{{$i}}" type="text" value= "{{$data->remarks}}" /></td>

		<td>
			<button id = 'submitEditWhitelist{{$i}}' class="ewl btn btn-warning"><i class="icon icon-edit"></i>Edit</button>
			<button id = 'submitDeleteWhitelist{{$i}}' class="btn btn-danger"><i class="icon icon-remove"></i></button>
		</td>
	</tr>

<script type="text/javascript">
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

	$('button#submitDeleteWhitelist{{$i}}').click(function() {
		var ID{{$i}} ={};
		 ID{{$i}}.id = $('input#id{{$i}}').val();
		$.ajax({
	 	 		type: 'post',
	 	 		url: 'settings/deletewhitelist',
	 	 		data: ID{{$i}},
	 	 		beforeSend: function (tableload) {
	 	 			$("div#messageWhitelist").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-line-black.gif" /></div>');
	 	 		}
	 	 	})
	 	 .done(function(html) {
	 	 	$("div#messageWhitelist").html(html);
	 	 	loadContact();
		});
	});


	$('button#submitEditWhitelist{{$i}}').click(function() {
	 	 var eWL{{$i}} = {};
	 	 	eWL{{$i}}.wid = $('#id{{$i}}').val();
	 	 	eWL{{$i}}.mobileNumber = $('#mobileNumber{{$i}}').val();
	 	 	eWL{{$i}}.remarks = $('#remarks{{$i}}').val();

	 	 $.ajax({
	 	 		type: 'post',
	 	 		url: 'settings/editwhitelist',
	 	 		data: eWL{{$i}},
	 	 		beforeSend: function (tableload) {
	 	 			$("div#messageWhitelist").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-line-black.gif" /></div>');
	 	 		}
	 	 	})
	 	 .done(function(html) {
	 	 	$("div#messageWhitelist").html(html);
	 	 	loadContact();
	 	 });
	 
	});
</script>


	
	<?php $i++; ?>
@endforeach
	</tbody>
</table>

<script type="text/javascript">

		$(".modal").draggable({
		    handle: ".modal-header"
		});
</script>