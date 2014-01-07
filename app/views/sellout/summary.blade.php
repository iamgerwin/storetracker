<table class="table table-hover">
	<thead>
		<td>Branch Code</td>
		<td>Total Price</td>
		<td>Total Discount</td>
		<td>Gross Amount</td>
		<td>Tax</td>
		<td>Net Amount</td>
	</thead>
	<tbody>
		<?php $i=0; ?>
		@foreach($branchId as $br)
		<tr>
			<td>{{Gerwin::getBranchNameByBranchId($br)}}</td>
			<td>{{$groAmt[$i]}}</td>
			<td>{{$totDis[$i]}}</td>
			<td></td>
			<td></td>
			<td>{{$netAmt[$i]}}</td>
		</tr>
		<?php $i++; ?>
		@endforeach
	</tbody>
</table>