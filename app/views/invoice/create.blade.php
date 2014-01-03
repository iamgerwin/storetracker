@extends('master')

@section('titlepage')
Add Invoice
@stop

@section('Sstate')
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
<script type="text/javascript" charset="utf-8" language="javascript" src="http://cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js"></script>
@stop

@section('footer')
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/jquery.datatables.js"></script>
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/DT_bootstrap.js"></script>

<script type="text/javascript">
$(document).ready( function () {

        $('.datepicker').datepicker();

	$("select#itemID0").change(function() {
		var itemID = $('select#itemID0').val();
		
		$.ajax({
			type:'post',
			url: "{{URL::to('/')}}/invoice/unitprice",
			data: {itemID : itemID},
			beforeSend: function (tableload) {
				$("div#messageCreateInvoice").html('<div id="loadTable"><img src="{{URL::to('/')}}/packages/main/images/preloader-w8-cycle-black.gif" /></div>');
		 	}
		}).done(function(result) {
			  $("input#unitPrice0").val(result);
			  $("div#messageCreateInvoice").html('');
		});
	});


} );




</script>



@stop

@section('content')
<div class="row-fluid">
	<div class=" span11">
		<h3>Add New Invoice</h3>
		 <div class="span5">
		 <label for="branchID">Branch Name</label>
		 <select id="branchID" class="select2 input-xlarge">
		 	@foreach($branches as $branch)
		 		<option value="{{$branch->branch_id}}">
		 {{$branch->branch_code}} - {{$branch->branch_name}}
		 		</option>
		 	@endforeach
    		</select>
    		<label for="contactID">Contact Person</label>
		 <select id="contactID" class="select2 input-xlarge">
		 	@foreach($contacts as $contact)
		 		<option value="{{$contact->contact_id}}">
		{{$contact->account_code}} - {{$contact->last_name}} -{{$contact->first_name}}
		 		</option>
		 	@endforeach
    		</select>
			<!--<label for="memberDiscount">Member Discount</label>
			<div class="input-append span6">
				 <input class="span4" id="memberDiscount" type="number" min="0" value ="0" max="100">
				 <span class="add-on">%</span>
			</div>-->	
    		</div>

    		<div class="span5">
			
			<label for="dateInput">Invoice Date</label>
			<input id="dateInput" data-date-format="yyyy-mm-dd" class="span5 datepicker" size="16" type="text" value="{{date('Y-m-d')}}" readonly>

			<label for="invoiceNumber">Invoice Number</label>
			<input id="invoiceNumber" class="span5" size="18" type="number" placeholder="Invoice Number"> <br/>
			<a href="#mySummary" id="summaryInvoice" role="button" class="btn btn-large btn-primary" data-toggle="modal">Add Invoice</a>
    		</div>
<hr>
    		<div id="itemList" class="span12">
			<h4>Item/s</h4>
			
<div id="messageCreateInvoice"></div>			
<table class="table" id="itemListTable">
	<thead>
		<th>Item Name - Item Size - Variant - Price</th>
		<th>Quantity</th>
		<th>Member Discount</th>
		<th>Discount Type</th>
		<th>Discount (item)</th>
		<th></th>
	</thead>
	<tbody>

		<tr >
			<td>
				<select id="itemID" name='itemID' class="select2 input-xxlarge">
					<option id="" value=''>--Select Item--</option>
				        @foreach($items as $item)
				        	<option value="{{$item->item_id}}">{{$item->item_name }} - {{$item->item_size}} - {{$item->variant}} - {{$item->selling_price}}</option>
				        @endforeach
				</select>
			</td>

			<td>
				<input type="number" id="quantity" name="quantity" class="span6" value="1" min="0" >
			</td>
			<td>
				<div class="input-append">
					 <input class="span9" id="memberDiscount" name="memberDiscount" type="number" min="0" value ="0" max="100">
					 <span class="add-on">%</span>
				</div>	
			</td>
			<td>
				<select id="discounttype" name="discounttype">
					<option value="0">Percentage</option>
					<option value="1">Fixed</option>
				</select>
			</td>
			<td>
				<div class="input-append">
					<input type="number" id="itemDiscount" name="itemDiscount" class="input-small" value="0" min="0" max="100" >
					
				</div>
			</td>
			<td>
				<button id="addRowItem" class="addRowItem btn btn-success"><i class="icon-plus"></i></button>
			</td>
		</tr>

	</tbody>
</table>
 

<div id="mySummary" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header" id="">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="mySummary Header">Confirm Adding Invoice</h3>
    <div id="msgAddinvoice"></div>
  </div>
  <div class="modal-body">
    	<div id="sumbod"></div>
  </div>


<script type="text/javascript">
$(document).ready(function(){

	$("#mySummary").draggable({
	    handle: ".modal-header"
	});
	$('a#summaryInvoice').click(function(){
		var inp = $("#invoiceNumber").val();
		if($.trim(inp).length > 0)
		{
		   		$('#mySummary').on('show', function () {

					var itemArr = new Array();
					$("select[name='itemID']").each(function() {
					   itemArr.push($(this).val());
					});
					var qtyArr = new Array();
					$("input[name='quantity']").each(function() {
					   qtyArr.push($(this).val());
					});
					var memDiscArr = new Array();
					$("input[name='memberDiscount']").each(function() {
					   memDiscArr.push($(this).val());
					});
					var itemdisctypeArr = new Array();
					$("select[name='discounttype']").each(function() {
					   itemdisctypeArr.push($(this).val());
					});
					var itemdiscArr = new Array();
					$("input[name='itemDiscount']").each(function() {
					   itemdiscArr.push($(this).val());
					});

					var Invoice = {};
						Invoice.branchID = $("select#branchID").val();
						Invoice.contactID = $("select#contactID").val();
						Invoice.dateInput = $("input#dateInput").val();
						Invoice.invoiceNumber = $("input#invoiceNumber").val();
						Invoice.itemID = itemArr;
						Invoice.itemQuantity = qtyArr;
						Invoice.memberDiscount = memDiscArr;
						Invoice.itemDiscounttype= itemdisctypeArr;
						Invoice.itemDiscount = itemdiscArr;	
					
				  	$.ajax({
						type:'post',
						url: "{{URL::to('/')}}/invoice/summary",
						data:  Invoice,
						beforeSend: function (tableload) {
							$("div#sumbod").html('<div id="loadTable"><img src="{{URL::to('/')}}/packages/main/images/preloader-w8-line-black.gif" /></div>');
					 	}
					}).done(function(result) {
						  $("div#sumbod").html(result);
					});
				});

		} else {
			//alert('Empty Invoice Number!');
			 $("div#sumbod").html('<h1>INVALID INPUT!</h1>');
		}
	});

var i = 1;
$(".select2").select2();
	    $("button#addRowItem").click(function(){
			
	    	 $("table#itemListTable").append('<tr ><td><select name="itemID" id="itemID" class="select2 addSelect input-xxlarge"><option value="">--Select Item--</option> @foreach($items as $item)<option value="{{$item->item_id}}">{{$item->item_name }} - {{$item->item_size}} - {{$item->variant}} - {{$item->selling_price}}</option>@endforeach </select></td><td><input type="number" name="quantity" class="span6" value="1" min="0" ></td><td><div class="input-apped"><input class="span9" id="memberDiscount" name="memberDiscount" type="number" min="0" value="0" max="100"><span class="add-on">%</span></div></td><td><select id="discounttype" name="discounttype"><option value="0">Percentage</option><option value="1">Fixed</option></select></td><td><div class="input-append"><input type="number" name="itemDiscount" class="input-small" value="0" min="0" max="100" ></div></td><td><button id="removeRowItem" class="removeRowItem btn btn-danger"><i class="icon-minus"></i></a</td></tr>');

	    	 $("select[name='itemID']").select2();

	    	$("table#itemListTable").on('click', '.removeRowItem', function(){
		        $(this).parent().parent().remove();
		});	
i++;
	    });
});
</script>		

	</div>
</div>
@stop