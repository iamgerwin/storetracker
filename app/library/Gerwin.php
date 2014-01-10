<?php


class Gerwin {
	
	public static function dec2($value)
	{
		return number_format($value, 2);
	}
	public static function lastInsertID($branchID,$invoiceNumber,$dateInput)
	{
		$id = DB::table('fiametta_retail.tbl_retail_invoice')->insertGetId(
		    array('invoice_date' => $dateInput, 'invoice_number' => $invoiceNumber, 'branch_id' => $branchID)
		);
	}
	protected static function getDailySales()
	{
		
	}
	public static function getBranchIdbyBranchCode($code){
		return Branch::where('branch_code',strtoupper($code))->pluck('branch_id');
	}
	public static function getContactIdbyAccountCode($code){
		return Contact::where('account_code',$code)->pluck('contact_id');
	}
	public static function getUnitPricebyItemId($itemId)
	{
		return Item::where('item_id',$itemId)->pluck('selling_price');
	}
	public static function getBranchInventoryId($branchId, $itemId)
	{
		return DB::table('fiametta_warehouse.tbl_branch_inventory')->where('branch_id',$branchId)->where('item_id',$itemId)->pluck('branch_inventory_id');
	}
	public static function getItemIdbySubItemCode($subItemCode)
	{
		return Item::where('sub_item_code',$subItemCode)->pluck('item_id');
	}
	public static function getContactReceiveSalesReport()
	{
		return implode(',',Contact::where('receive_sales_report','=','1')->where('active','=','1')->lists('mobile_number'));
	}
	protected  function getInvoiceData($invoiceNumber, $status = 0)
	{//->where('invoice_status','D')
		if($status == 0)
			return DB::table('fiametta_retail.vwretailinvoice_mdl')->where('invoice_number',$invoiceNumber)->get();
		else
			return DB::table('fiametta_retail.vwretailinvoice_mdl')->where('invoice_number',$invoiceNumber)->where('invoice_status','D')->get();
	} 
	public static function getUniqueInvoice($branchId,$invoiceNumber)
	{
		return DB::table('fiametta_retail.vwretailinvoice_mdl')->where('invoice_number',$invoiceNumber)->where('branch_id',$branchId)->pluck('retail_invoice_id');
	}
	final static function getUniqueInvoiceData($invoiceRetailId,$status=0)
	{//->where('invoice_status','D')
		if($status == 0)
			return DB::table('fiametta_retail.vwretailinvoice_mdl')->where('retail_invoice_id',$invoiceRetailId)->get();
		else
			return DB::table('fiametta_retail.vwretailinvoice_mdl')->where('retail_invoice_id',$invoiceRetailId)->where('invoice_status','D')->get();
	}
	final static function getWhitelistExist($number)
	{
		return Whitelist::where('mobile_number',$number)->count();
	}
	final public static function getRIDI($invoiceRetailId)
	{
		return DB::table('vwretailinvoicedetails')->where('retail_invoice_id',$invoiceRetailId)->get();
	}
	final public static function getInvoiceSummary($retailInvoiceId)
	{
		$data =  Gerwin::getUniqueInvoiceData($retailInvoiceId,1);
		
		$summary = 'Invoice Number: '.$data[0]->invoice_number.' was added! ';
		$summary .= "\n----\n";
			$items = Compute::itemData($retailInvoiceId);
			$totals = Compute::invoiceData($items->subTotal,$items->itemDiscount);
			$i = 0;

		for($i=0;$i<count($items->itemName);$i++)
		{
			$summary .= 'item name: '.$items->itemName[$i] ."\n";
			$summary .= 'unit price: '.number_format($items->unitPrice[$i],2) ."\n";
			$summary .= 'quantity: '.$items->quantity[$i] ."\n";
			$summary .= 'gross amount: '.number_format($items->subTotal[$i],2) ."\n";
			//$summary .= 'member discount: '.$items->quantity[$i] ."\n";
			//$summary .= 'discount type: '.$items->quantity[$i] ."\n";
			$summary .= 'item discount: '.number_format($items->itemDiscount[$i] ,2)."\n";
			$summary .= "----\n";
			
		}
		$summary .= "Number of item/s: $i \n";
		$summary .= 'Grand Total: '.number_format($totals->totalPrice,2)."\n";
		$summary .= 'Total Discount: '.number_format($totals->totalDiscount,2)."\n";
		$summary .= 'Invoice Amount: '.number_format($totals->grossSales,2) ."\n";
		$summary .= 'Net Sales: '.number_format($totals->netSales,2) ."\n";
		$summary .= 'Output Tax: '.number_format($totals->outputTax,2) ;
		return $summary;
	}
	public static function getBranchNameByBranchId($id)
	{
		return Branch::where('branch_id',$id)->pluck('branch_name');
	}
	public static function getKiosk()
	{
		return Branch::where('branch_type_id',4)->where('active',1)->orderBy('branch_name', 'asc')->lists('branch_id');
	}
	public static function getBranchKiosk()
	{
		return Branch::where('branch_type_id',4)->where('active',1)->orderBy('branch_name', 'asc')->get();
	}
	final public static function getSalesDailySummary()
	{
		
	}



}