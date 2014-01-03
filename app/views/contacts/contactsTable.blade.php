<script type="text/javascript">
	$(document).ready( function () {
		$('table#contactTable').dataTable( {
    		"aaSorting": [[ 0, "desc" ]]
    	});

	} ); 
</script>
<link rel="stylesheet" href="{{URL::to('/')}}/packages/pnotify/jquery.pnotify.default.css">
<script src="{{URL::to('/')}}/packages/pnotify/jquery.pnotify.min.js"></script>

<table cellpadding="0" cellspacing="0" border="0" class="table bordered-table table-hover" id="contactTable">
	<thead>
		<tr>
			<th>Code</th>
			<th>Firstname</th>
			<th>Middlename</th>
			<th>Lastname</th>
			<th>Active</th>
			<th>Mobile#</th>
			<th>SalesReport</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php $i = 0; ?>
	@foreach($datas as $data)
		<tr>
			<td>{{htmlentities($data->account_code)}}</td>
			<td>{{htmlentities($data->first_name)}}</td>
			<td>{{htmlentities($data->middle_name)}}</td>
			<td>{{htmlentities($data->last_name)}}</td>
			<td>{{htmlentities($data->active)}}</td>
			<td>{{htmlentities($data->mobile_number)}}</td>
			<td>{{htmlentities($data->receive_sales_report)}}</td>
			<td><!--trigger editContact modal -->
				<a href="#editContact{{$i}}" role="button" class="btn btn-warning" data-toggle="modal"><i class="icon-edit"></i></a>
			</td>
		</tr>

<!-- editContact Modal -->
<div id="editContact{{$i}}" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myEditModal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myEditModal">Edit Contact {{htmlentities($data->account_code)}}</h3>
  </div>
  <div class="modal-body">
 	<div id="editMessage{{$i}}"></div>
    <fieldset class="form-horizontal">
    	<div class="span3">
			<label>Account Code</label>
		    <input type="text" id="accountcode{{$i}}" value="{{htmlentities($data->account_code)}}" readonly>
			<label>Mobile Number</label>
		    <input type="text" id="mobilenumber{{$i}}" value="{{htmlentities($data->mobile_number)}}" >
		    <label class="checkbox">
		     <input id="salesreport{{$i}}" type="checkbox"
		     @if($data->receive_sales_report == '1')
		     	checked
		     @endif
		     > Receive Sales Report
		    </label>
		    <br />
		    <label class="checkbox">
		     <input id="active{{$i}}" type="checkbox"
		     @if($data->active == '1')
		     	checked
		     @endif
		     >Active
		    </label>
	    </div>

	    <div class="span3">
			<label>Firstname</label>
		    <input type="text" id="firstname{{$i}}" value="{{htmlentities($data->first_name)}}">
			<label>Middlename</label>
		    <input type="text" id="middlename{{$i}}" value="{{htmlentities($data->middle_name)}}">
			<label>Lastname</label>
		    <input type="text" id="lastname{{$i}}" value="{{htmlentities($data->last_name)}}">
	    </div>
    </fieldset>
  </div>
  <div class="modal-footer">

    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button id="editContactButton{{$i}}" class="esb btn btn-primary">Edit</button>

<script type="text/javascript">
	$(document).ready( function () {
		function loadContact() {
			$.ajax({
				  type: "POST",
				  url: "{{URL::to('/')}}/contacts/loadcontacts",
				  cache: false,
				  beforeSend: function (tableload) {
				  	$("div#contactTable").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
				  }
				}).done(function( html ) {
				  $("div#contactTable").html(html);
			});
		}

		$('button#editContactButton{{$i}}').on('click',function() {
			var Obj{{$i}} = {};
				Obj{{$i}}.salesreport= $( "input#salesreport{{$i}}:checked" ).length;
				Obj{{$i}}.active= $( "input#active{{$i}}:checked" ).length;
				Obj{{$i}}.accountcode =$('input#accountcode{{$i}}').val();
				Obj{{$i}}.mobilenumber =$('input#mobilenumber{{$i}}').val();
				Obj{{$i}}.firstname =$('input#firstname{{$i}}').val();
				Obj{{$i}}.middlename =$('input#middlename{{$i}}').val();
				Obj{{$i}}.lastname =$('input#lastname{{$i}}').val();

			$.ajax({
				  type: "POST",
				  url: "contacts/edit",
				  cache: false,
			  	  data: Obj{{$i}},
				  beforeSend: function (tableload) {
				  	$("div#editMessage{{$i}}").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-line-black.gif" /></div>');
				  }
				})

				.done(function( html ) {
					if(html == '1') {
						$.pnotify({
						    title: 'Contact updated',
						    text: 'Success!',
						    type: 'success'
						});
						$('#editContact{{$i}}').modal('hide');
				 	    loadContact();
					}   else{
						$("div#editMessage{{$i}}").html(html);
					}
					  
					  
				});
			 
		});
	} );
</script>

  </div>
</div>



	<?php $i++; ?>
	@endforeach

	</tbody>
</table>

<script type="text/javascript">
		$(".modal").draggable({
		    handle: ".modal-header"
		});
</script>

