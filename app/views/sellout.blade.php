@extends('master')

@section('titlepage')
Sellout
@stop

@section('Sstate')
active
@stop

@section('header')
<link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/packages/datatables/DT_bootstrap.css">
<script type="text/javascript" src="https://google.com/jsapi"></script>
<link rel="stylesheet" href="{{URL::to('/')}}/packages/bootstrap/css/datepicker.css">
<style type="text/css">
div#invoiceTabContent{
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
	$('#isDate').datepicker();

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

	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	 
	var checkin = $('#idfromDate').datepicker({
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
	  $('#idtoDate')[0].focus();
	}).data('datepicker');
	var checkout = $('#idtoDate').datepicker({
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
			  url: "sellout/invoice",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#selloutInvoiceDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#selloutInvoiceDiv").html(html);
			});
} );

$(document).ready( function () {
	$.ajax({
			  type: "POST",
			  url: "sellout/invoicedetails",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#selloutInvoiceDetailsDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#selloutInvoiceDetailsDiv").html(html);
			});
} );

$(document).ready( function () {
	$('button#filterInvoice').on('click',function() {
		var Obj = {};
		Obj.from= $('input#ifromDate').val();
		Obj.to= $('input#itoDate').val();
		$.ajax({
			  type: "POST",
			  url: "sellout/invoicerange",
			  cache: false,
			  data: Obj,
			  beforeSend: function (tableload) {
			  	$("div#selloutInvoiceDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#selloutInvoiceDiv").html(html);
		});
	});
} );

$(document).ready( function () {
	$('button#suminBtn').on('click',function() {
		var Obj = {};
		Obj.date= $('input#isDate').val();
		Obj.branches= $('select#branchBox').val();
		$.ajax({
			  type: "POST",
			  url: "sellout/summary",
			  cache: false,
			  data: Obj,
			  beforeSend: function (tableload) {
			  	$("div#selloutInvoiceSummaryDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#selloutInvoiceSummaryDiv").html(html);
		});
	});
} );

$(document).ready( function () {
	$('button#filterInvoiceDetails').on('click',function() {
		var Obj = {};
		Obj.from= $('input#idfromDate').val();
		Obj.to= $('input#idtoDate').val();
		$.ajax({
			  type: "POST",
			  url: "sellout/invoicedetailsrange",
			  cache: false,
			  data: Obj,
			  beforeSend: function (tableload) {
			  	$("div#selloutInvoiceDetailsDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#selloutInvoiceDetailsDiv").html(html);
		});
	});
} );

$(document).ready( function () {
	$('button#todayInvoice').on('click',function() {
		$.ajax({
			  type: "POST",
			  url: "sellout/invoice",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#selloutInvoiceDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#selloutInvoiceDiv").html(html);
		});
	});

	$('button#todayInvoiceDetails').on('click',function() {
		$.ajax({
			  type: "POST",
			  url: "sellout/invoicedetails",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#selloutInvoiceDetailsDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#selloutInvoiceDetailsDiv").html(html);
		});
	});

	$('button#allInvoice').on('click',function() {
		$.ajax({
			  type: "POST",
			  url: "sellout/invoiceall",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#selloutInvoiceDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#selloutInvoiceDiv").html(html);
		});
	});
	$('button#allInvoiceDetails').on('click',function() {
		$.ajax({
			  type: "POST",
			  url: "sellout/invoicedetailsall",
			  cache: false,
			  beforeSend: function (tableload) {
			  	$("div#selloutInvoiceDetailsDiv").html('<div id="loadTable"><img src="packages/main/images/preloader-w8-cycle-black.gif" /></div>');
			  }
			}).done(function( html ) {
			  $("div#selloutInvoiceDetailsDiv").html(html);
		});
	});

} );
$("#branchBox").select2();
</script>
@stop

@section('content')



<div id="selloutContainer" class="container-fluid">
	<div class="tabbable tabs-left">
	  
	  <ul class="nav nav-tabs">
	    <li class="active"><a href="#invoice" data-toggle="tab">Invoice</a></li>
	    <li><a href="#invoicedetails" data-toggle="tab">Invoice Details</a></li>
	    <li><a href="#invoicesummary" data-toggle="tab">Invoice Summary</a></li>
	  </ul>

	  <div id="invoiceTabContent" class="tab-content">
	    <div id="invoice" class="tab-pane active">
	      <h4>Invoice</h4>
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

		      		<button id="filterInvoice" class="btn btn-primary">Filter</button>
		      		<button id="todayInvoice" class="btn btn-inverse">Today</button>
		      		<button id="allInvoice" class="btn btn-info">All</button>

		      		
		      		@include('role.control.sellout')
		      		
		      	</div>
	 	<hr />
		      	<div id="selloutInvoiceDiv">
		      	</div>

	      	</div>
	    </div>
	    <div id="invoicedetails" class="tab-pane">
		    <h4>Invoice Details</h4>
		    <div class="well">

		    	<div class="form-inline">
		      		
		      		<label for="idfromDate">From</label>
		      		<div class="input-append date">
		      		<input type="text" id="idfromDate" data-date-format="yyyy-mm-dd" readonly><span class="add-on"><i class="icon icon-th"></i></span>
		      		</div>

		      		<label for="idtoDate">To</label>
		      		<div class="input-append date">
		      		<input type="text" id="idtoDate" data-date-format="yyyy-mm-dd" readonly><span class="add-on"><i class="icon icon-th"></i></span>
		      		</div>

		      		<button id="filterInvoiceDetails" class="btn btn-primary">Filter</button>
		      		<button id="todayInvoiceDetails" class="btn btn-inverse">Today</button>
		      		<button id="allInvoiceDetails" class="btn btn-info">All</button>

		      		@include('role.control.sellout')

		      		
		      	</div>
		    <hr />

		    	<div id="selloutInvoiceDetailsDiv">
				</div>
		    </div>
	    </div>

	    <div id="invoicesummary" class="tab-pane">
		    <h4>Invoice Summary</h4>
		    <div class="well">

		    	<div class="form-inline">
		      		<div class="span6">
			      		<label for="isDate">Date</label>
			      		<div class="input-append date">
			      		<input type="text" id="isDate" data-date-format="yyyy-mm-dd" readonly><span class="add-on"><i class="icon icon-th"></i></span>
			      		</div>
					</div>
					<div class="span4">
						<label for="branchBox">Branch</label>
			      		<select multiple name="branchBox" id="branchBox" class="span3">
			      				<option value="all">
			      					All
			      				</option>
			      				<optgroup label="Kiosk">
			      			@foreach($branches as $branch)
								<option value="{{$branch->branch_code}}">{{$branch->branch_name}}</option>
			      			@endforeach
			      				</optgroup>
			      		</select>
					</div>
		      	</div>
		      	<br>
		      	<div class="form-inline offset4 span6">
		      		<div>
						<button id="suminBtn" class="btn btn-primary">Summarize</button>
		      		</div>	
		      	</div>
		      	<br />
		    	<hr />
				
		    	
				<div id="selloutInvoiceSummaryDiv" ></div>
		    </div>

	    </div>

	  </div><!-- /.tab-content -->

	</div><!-- /.tabbable -->
</div>


@stop