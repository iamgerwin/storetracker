

<table cellpadding="0" cellspacing="0" border="0" class="table bordered-table table-hover" id="selloutInvoiceDetailsTable">
	<thead>
		<tr>
			<th>InvoiceDate</th>
			<th>Code</th>
			<th>Name</th>
			<th>Branch</th>
			<th>Invoice #</th>
			<th>UnitPrice</th>
			<th>Quantity</th>
			<th>GrossTotal</th>
			<th>DiscAmt</th>
			<th>NetPrice</th>
			<th>VAT</th>
		</tr>
	</thead>
	<tbody>
	@foreach($datas as $data)
		<tr>
			<?php
				$subTotal = Compute::_subTotal($data->quantity,$data->unit_price);

				if($data->discount_type == 0)
					$discountPrice = Compute::_percentageDiscount($subTotal,($data->member_discount),($data->discount));
				else
					$discountPrice = Compute::_fixedDiscount($subTotal,$data->member_discount,$data->discount,$data->quantity);

				$invoiceDetailsCompute = Compute::invoiceData($subTotal,$discountPrice);

			?>
			<td>{{$data->invoice_date}}</td>
			<td>{{$data->item_code}}</td>
			<td>{{$data->item_name}} - {{$data->variant}}- {{$data->item_size}}</td>
			<td>{{$data->branch_name}}</td>
			<td>{{$data->invoice_number}}</td>

			<td>{{$data->unit_price}}</td>
			<td>{{$data->quantity}}</td>
			<td>{{number_format($subTotal,2)}}</td>
			<td>{{number_format($discountPrice,2)}}</td>
			<td>{{number_format($invoiceDetailsCompute->netSales,2)}}</td>
			<td>{{number_format($invoiceDetailsCompute->outputTax,2)}}</td>
		</tr>
	@endforeach
	</tbody>
</table>

<script type="text/javascript">
$(document).ready(function() {
    $('#selloutInvoiceDetailsTable').dataTable( {
		"aaSorting": [[ 0, "desc" ]]
    } );
} );
</script>