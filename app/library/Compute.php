<?php

class Compute extends Gerwin {
	
	public static function test($data)
	{
		return Gerwin::getInvoiceSummary($data);
	}
	final public static function itemData($invoiceRetailId)
	{
		$g = new stdClass();
		$ds = Gerwin::getUniqueInvoiceData($invoiceRetailId);
			$i = 0;
			foreach ($ds as $d) {
				$g->itemName[] = $d->item_name .' '. $d->variant .' '.$d->item_size;
				$g->unitPrice[] = $d->unit_price;
				$g->quantity[] = $d->quantity;
				$g->subTotal[] = Compute::_subTotal($g->quantity[$i],$g->unitPrice[$i]);
				if(!isset($g->subTotal[$i])){
					$g->subTotal[$i] = 0;
				}
				
				if($d->discount_type == 0)
					$g->itemDiscount[] =  Compute::_percentageDiscount($g->subTotal[$i],$d->member_discount,$d->discount);
				else
					$g->itemDiscount[] = Compute::_fixedDiscount($g->subTotal[$i],$d->member_discount,$d->discount,$g->quantity[$i]);
				
				if(!isset($g->itemDiscount[$i])){
					$g->itemDiscount[$i] = 0;
				}

				$i++;
			}

		return $g;
	}
	final public static  function invoiceData($subTotal =0,$itemDiscount=0)
	{
		$g = new stdClass();

			$g->totalPrice= Compute::_totalPrice($subTotal);
			$g->totalDiscount = Compute::_totalPrice($itemDiscount);
			$g->grossSales = Compute::_grossSales($g->totalPrice,$g->totalDiscount);
			$g->netSales = Compute::_netSales($g->grossSales);
			$g->outputTax = Compute::_totalTax($g->netSales);

		return $g;
	}
	final public static function invoiceDetails($RIDI)
	{
		$g = new stdClass();
		$ds = Gerwin::getUniqueInvoiceData($RIDI);
			$i = 0;
			foreach ($ds as $d) {
				$g->itemName[] = $d->item_name .' '. $d->variant .' '.$d->item_size;
				$g->unitPrice[] = $d->unit_price;
				$g->quantity[] = $d->quantity;
				$g->subTotal[] = Compute::_subTotal($g->quantity[$i],$g->unitPrice[$i]);
				if(!isset($g->subTotal[$i])){
					$g->subTotal[$i] = 0;
				}
				
				if($d->discount_type == 0)
					$g->itemDiscount[] =  Compute::_percentageDiscount($g->subTotal[$i],$d->member_discount,$d->discount);
				else
					$g->itemDiscount[] = Compute::_fixedDiscount($g->subTotal[$i],$d->member_discount,$d->discount,$g->quantity[$i]);
				
				if(!isset($g->itemDiscount[$i])){
					$g->itemDiscount[$i] = 0;
				}

				$i++;
			}

		return $g;
	}
	public static function getPerBranch($bc)
	{
		return DB::table('vwretailinvoice_mdl')->where('branch_code',$bc)->get();
	}
	public static function _subTotal($qty,$srp)
	{
		return $qty *$srp;
	}
	public static function _fixedDiscount($subTotal,$memberDiscount,$promoDiscount,$qty )
	{
		return ( ($subTotal * $memberDiscount) + ($promoDiscount * $qty) );
	}
	public static function _percentageDiscount($subTotal,$memberDiscount,$promoDiscount)
	{
		return ( ($subTotal * $memberDiscount) + ($subTotal * (1 - $memberDiscount) * $promoDiscount ) );
	}
	public static function _totalPrice($amounts)
	{
		$g=0;
		if(!is_array($amounts)){
			return $amounts;
		}
		foreach ($amounts as $amt) {
			$g += $amt; 
		}
		return $g;
	}
	public static function _grossSales($totalAmount, $totalDiscount)
	{
		return $totalAmount - $totalDiscount;
	}
	public static function _netSales($grossSales)
	{
		return $grossSales / 1.12;
	}
	public static function _totalTax($netSales)
	{
		return $netSales * 0.12;
	}
	final public static function dailySales()
	{

	}
	


}