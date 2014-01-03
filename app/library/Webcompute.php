<?php

class Webcompute extends Compute {

	public static function summaryInvoice($g)
	{
		// itemID
		// itemQuantity
		// memberDiscount
		// itemDiscounttype
		// itemDiscount
		$gerwin = new stdClass();
		for($i=0;$i<count($g['itemID']);$i++) {
			$ind = Item::find($g['itemID'][$i]);
			$gerwin->gross_totals[] = Compute::_subTotal($g['itemQuantity'][$i],$ind->selling_price);
			if($g['itemDiscounttype'][$i] == 0)
				$gerwin->price_discounts[] =  Compute::_percentageDiscount($gerwin->gross_totals[$i],($g['memberDiscount'][$i] /100),($g['itemDiscount'][$i] / 100));
			else
				$gerwin->price_discounts[] = Compute::_fixedDiscount($gerwin->gross_totals[$i],($g['memberDiscount'][$i] /100),$g['itemDiscount'][$i],$g['itemQuantity'][$i]);
		}

		return $gerwin;
	}
	public static function dateRange($from,$to) {
		
	}

}

