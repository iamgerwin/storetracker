@extends('master')

@section('titlepage')
Edit {{$id}} Invoice
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
@stop

@section('footer')
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/jquery.datatables.js"></script>
<script type="text/javascript" charset="utf-8" language="javascript" src="{{URL::to('/')}}/packages/datatables/DT_bootstrap.js"></script>

<script type="text/javascript">
$(document).ready( function () {

        $(".select2").select2();
        $('.datepicker').datepicker();
$( "#editInvoice" ).click(function() {
	var Edit = {};
		Edit.memberDiscount = $('#memberDiscount').val();
		Edit.invoiceDate = $('#invoiceDate').val();
		Edit.invoiceNumber = $('#invoiceNumber').val();
		Edit.invoiceStatus = $("#invoiceStatus").is(':checked') ? 'D' : 'C';
	$.ajax({
		type: 'PUT',
	    	url: '{{URL::to('/')}}/invoice/{{$id}}',
	    	cache: false,
		data: Edit,
		beforeSend: function (tableload) {
				$("div#messageEditInvoice").html('<div id="loadTable"><img src="{{URL::to('/')}}/packages/main/images/preloader-w8-cycle-black.gif" /></div>');
		 }
		})
		.done(function( html ) {	  
			  $("div#messageEditInvoice").html(html);
		});
});


} );



</script>
@stop

@section('content')

<div id="messageEditInvoice" class="span4 pull-right"></div>
<div class="row-fluid">
	<div class=" span12">
		<h3>Invoice ID: {{$id}}</h3>
		
		 <div class="span5">
		 <label for="branchID">Branch Name</label>
		 	<input id="branchID" type="text" class="input-large" value="{{$branch->branch_name}} - {{$branch->branch_code}}" disabled readonly>

    		<label for="contactID">Contact Person</label>
    			<input id="contactID" type="text" class="input-large" value="{{$contact->account_code}} - {{$contact->last_name}} - {{$contact->first_name}}" disabled readonly>
		 	
			<!-- <label for="memberDiscount">Member Discount</label> -->
			

			<label for="invoiceStatus">
			  	<input id="invoiceStatus" type="checkbox" @if($retail->invoice_status == 'D')
			  		checked
			  	@endif >
			  	<span class="label label-important">Active Invoice</span>
			</label>

			
    		</div>

    		<div class="span5">
			
			<label for="invoiceDate">Invoice Date</label>
			<input id="invoiceDate" data-date-format="yyyy-mm-dd" class="span5 datepicker" size="16" type="text" value="{{$retail->invoice_date}}" readonly>

			<label for="invoiceNumber">Invoice Number</label>
			<input id="invoiceNumber" class="span5" size="18" type="number" value="{{$retail->invoice_number}}" placeholder="Invoice Number"> <br/>
			<button id="editInvoice" class="btn btn-large btn-warning">Edit Invoice</button>
    		</div>

    		

    		<div id="itemList" class="span10 hero-unit">
			<h4>Item/s</h4>
	
			<hr>
<table class="table table-hover" id="itemList">
	<thead>
		<th>Item Name</th>
		<th>Selling Price</th>
		<th>Quantity</th>
		<th>Discount (item)</th>
		
	</thead>
	<tbody>
	@foreach($details as $detail)
	<tr>
		<td>
			{{$detail->item_name}} - {{$detail->variant}} - {{$detail->item_size}}
		</td>
		<td>
			<center> {{$detail->sub_total}} </center>
		</td>
		<td>
			<center>{{$detail->quantity}}</enter>
		</td>
		<td>
			<?php $itemDiscount = $detail->discount *100 ; ?> <center> {{$itemDiscount}} <i>%</i></center>
		</td>

	</tr>
	@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td><i>Gross Total</strong></i>
			<td></td>
			<td></td>
			<td><i>{{Gerwin::dec2($grossAmount)}}</i></td>
		</tr>
		<tr>
			<td><i>Total Discount</i></td>
			<td></td>
			<td></td>
			<td><i>{{Gerwin::dec2($totalDiscount)}}</i></td>
		</tr>
		<tr>
			<td><i>VAT</i></td>
			<td></td>
			<td></td>
			<td><i>{{Gerwin::dec2($outputTax)}}</i></td>
		</tr>
		
		<tr>
			
			<td><h3>Net Sales</h3></td>
			<td></td>
			<td></td>
			<td><h3>{{Gerwin::dec2($netSales)}}</h3></td>
		</tr>
	</tfoot>
</table>
 
    		</div>

	</div>

</div>

@stop