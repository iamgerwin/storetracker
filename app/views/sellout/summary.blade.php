<br>
<center>Covered date: {{$dates[0]}} - {{$dates[1]}}</center>
<hr/>
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
			<td>{{$totPri[$i]}}</td>
			<td>{{$totDis[$i]}}</td>
			<td>{{$groAmt[$i]}}</td>
			<td>{{$taxOut[$i]}}</td>
			<td>{{$netAmt[$i]}}</td>
		</tr>
		<?php $i++; ?>
		@endforeach
	</tbody>
</table>