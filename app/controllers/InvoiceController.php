<?php

class InvoiceController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$branches = DB::table('fiametta_warehouse.tbl_branch')
			->where('branch_type_id',4)
			->where('active',1)
			->get();
		$contacts = Contact::where('active',1)->get();
		$items = Item::AvailableItemKiosk()->get();
		return View::make('invoice.create')
		->with('items',$items)
		->with('branches',$branches)
		->with('contacts',$contacts);
	}
	public function postUpdateitems()
	{
		$items = DB::table('fiametta_warehouse.tbl_branch_inventory')->where('branch_id',Input::get('branchID'))->get();
	}
	public function postSummary()
	{
		$input = Input::all();

		$rules =  array(
				'branchID' => 'required|numeric',
				'contactID' => 'required|numeric',
				'dateInput' => 'required',
				'invoiceNumber' => 'required'
				);

		$validator = Validator::make($input,$rules);

		if($validator->fails()) {
			$errors = $validator->messages();
			$err = '<ul class="alert alert-error">';
			            foreach ($errors->all() as $error) {
			                $err .= '<li>'.$error.'</li>';
			            }
			            $err .= '</ul>';

		            return $err;
		} 
		$invoiceVal = Invoice::where('invoice_number',$input['invoiceNumber'])->where('invoice_status','D')->count();
		if($invoiceVal > 0)
		{
			return '<ul class="alert alert-error"> <li>Exisiting Active Invoice Number!</li></ul>';
		}
		if(count($input['itemID']) != count(array_unique($input['itemID'])) )
		{
			return '<ul class="alert alert-error"> <li>Duplicate Items!</li></ul>';
		}
		foreach ($input['itemID'] as $item) {
			if($item != '') {
				$it[] = Item::where('item_id',$item)->first();
			} else {
				return '<h1>Invalid Item/s</h1>';
			}

			$branchInventoryExist = DB::table('fiametta_warehouse.tbl_branch_inventory')->where('branch_id',$input['branchID'])->where('item_id',$item)->count();
			if($branchInventoryExist == 0) {
				$ss = Item::find($item);
				return '<h4>Invalid Item No Branch Inventory!<br />'.$ss->item_name.' '.$ss->item_size.'</h4>';
			}
		}

		$branch = DB::table('fiametta_warehouse.tbl_branch')
			->where('branch_type_id',4)->where('branch_id',$input['branchID'])
			->first();
		$contact = Contact::where('active',1)->where('contact_id',$input['contactID'])->first();
		if(!isset($it)) {
			return '<h1>NO ITEM!</h1>';
		}

		$rules = array(
				'Quantity' => 'required|numeric|between:1,999',
				'Promo Discount' => 'required|numeric'
			);

		$compute = Webcompute::summaryInvoice($input);
		
		$computeData = Compute::invoiceData($compute->gross_totals,$compute->price_discounts);
		
		return View::make('invoice.summary')
			->with('invoiceNumber',$input['invoiceNumber'])
			->with('items',$it)
			->with('branch',$branch)
			->with('contact',$contact)
			->with('dateInput',$input['dateInput'])
			->with('memberDiscount',$input['memberDiscount'])
			->with('itemQuantities',$input['itemQuantity'])
			->with('itemMemberDiscounts',$input['memberDiscount'])
			->with('itemDiscounttype',$input['itemDiscounttype'])
			->with('itemDiscounts',$input['itemDiscount'])
			->with('computeData',$computeData);
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::all();
			$exist = Invoice::where('invoice_number',$input['invoiceNumber'])->where('invoice_status','D')->where('branch_id',$input['branchID'])->count();
			if($exist != 0){
				return '<ul class="alert alert-danger">Invoice Number is active and exist in the same branch</ul>';
			}

			Invoice::insert([
				'branch_id' => $input['branchID'],
				'contact_id' => $input['contactID'],
				'invoice_number' => $input['invoiceNumber'],
				'invoice_date' => $input['invoiceDate'],
				'invoice_status' => 'D'
				]);
			
		$last_id = Invoice::select(DB::raw('LAST_INSERT_ID()'))->pluck('LAST_INSERT_ID()');
		
		$g = 0;
		$i = count($input['itemIDs']);

		while($g < $i) {
				$bii = DB::table('fiametta_warehouse.tbl_branch_inventory')->where('branch_id',$input['branchID'])->where('item_id',$input['itemIDs'][$g])->pluck('branch_inventory_id');
				$item = DB::table('fiametta_warehouse.tbl_item')->where('item_id',$input['itemIDs'][$g])->first();

				if($input['itemDTs'][$g] == 0) {
					$itemdisc = ($input['itemDIs'][$g] / 100);
				} else {
					$itemdisc = $input['itemDIs'][$g];
				}

				InvoiceDetails::insert([
					'retail_invoice_id' => $last_id,
					'branch_inventory_id' => $bii,
					'unit_price' => $item->selling_price,
					'quantity' => $input['itemQTYs'][$g],
					'member_discount' => ($input['itemMDs'][$g] /100),
					'discount_type' => $input['itemDTs'][$g],
					'discount' => $itemdisc
				]);
			$g++;
		}

		return '<ul class="alert alert-success"><li>Successfully added!</li></ul>';
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function edit($id)
	{
		
		$infoRetail = DB::table('vwretailinvoice')->where('retail_invoice_id',$id)->first();
		$infoRetailDetail =DB::table('vwretailinvoicedetails')->where('retail_invoice_id',$id)->get();
		
		$mD = DB::table('vwretailinvoicedetails')->where('retail_invoice_id',$id)->avg('member_discount');

		$memberDiscount = $mD*100;

		$grossAmount = 0;
		$iDiscountPrice = 0;
		foreach($infoRetailDetail as $sub) {
			$grossAmount += ($sub->unit_price * $sub->quantity);

			$itemGross = $sub->unit_price * $sub->quantity;
			
			$iDiscountPrice += ($itemGross * $sub->discount);
		}

		$mDiscountPrice = $grossAmount * $mD;

		$totalDiscount = $mDiscountPrice + $iDiscountPrice;
		
		$invoiceAmount = $grossAmount - ($mDiscountPrice + $iDiscountPrice);

		$netSales = $invoiceAmount / 1.12;

		$outputTax = $netSales * 0.12;

		$branch = DB::table('fiametta_warehouse.tbl_branch')
			->where('branch_id',$infoRetail->branch_id)
			->first();

		$contact = Contact::where('contact_id',$infoRetail->contact_id)->first();

		return View::make('invoice.edit')
		->with('id',$id)
		->with('branch',$branch)
		->with('contact',$contact)
		->with('memberDiscount',$memberDiscount)
		->with('details',$infoRetailDetail)
		->with('grossAmount',$grossAmount)
		->with('totalDiscount',$totalDiscount)
		->with('invoiceAmount',$invoiceAmount)
		->with('netSales',$netSales)
		->with('outputTax',$outputTax)
		->with('retail',$infoRetail);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();
		
		$bid = Invoice::where('retail_invoice_id',$id)->pluck('branch_id');
		
		 $rules = [
		            //'memberDiscount' => 'required|numeric|between:0,100',
		            'invoiceDate' => 'required',
		            'invoiceNumber' => 'required|integer|unique:tbl_retail_invoice,invoice_number,'.$id.',retail_invoice_id,branch_id,'.$bid.',invoice_status,D',
		            'invoiceStatus' => 'required|in:D,C'
		];

		$validator = Validator::make($input, $rules);

		        if($validator->passes()) {

		            $discountMem = 0 ;

		        	Invoice::where('retail_invoice_id',$id)
		        		->update([
		        			'invoice_date'=> $input['invoiceDate'],
		        			'invoice_number' => $input['invoiceNumber'],
		        			'invoice_status' =>$input['invoiceStatus']
		        			]);

		        	InvoiceDetails::where('retail_invoice_id',$id)
		        		->update([
		        			'member_discount' => $discountMem
		        			]);


		            $pass = '<ul class="alert alert-success"><li>Successfully edited Invoice # <b>'.$id.'</b>!</li></ul>';
		            return $pass;
		        } else {
		            	$errors = $validator->messages();
			$err = '<ul class="alert alert-error">';
			            foreach ($errors->all() as $error) {
			                $err .= '<li>'.$error.'</li>';
			            }
			            $err .= '</ul>';

		            return $err;
		        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}