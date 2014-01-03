<?php

class Sms {
	
	public static function sendIt($to= '09323729873',$msg)
	{
		if(count($to) == 0)
		{
			$to = '09323729873';
		}
		                    $mcproGateway = 'http://122.52.163.202/mcproManager/public/gateway';            
		                    $postData = [
		                            "Keyword" => "CKFaQ",
		                            "To" => $to,
		                            "Msg" => $msg
		                        ];

		                         	$ch = curl_init($mcproGateway);
				            curl_setopt($ch, CURLOPT_POST, true);
				            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	return curl_exec($ch); 
	            
	}
	public static function recipient($nums)
	{
		return implode(',',$nums);
	}
	public static function segregate($msg)
	{
		$r = new stdClass;

		$fChunk = explode(':', $msg);

		$upper = $fChunk[0];
		$lower1 = $fChunk[1];
		
		if($upper == 'ci'){
			$r->status = 'ci';
			$lower2 = explode(',',$lower1);
				$r->branchCode = $lower2[0];
				$r->accountCode = $lower2[1];
				$r->invoiceNumber = $lower2[2];
				$r->remark = $lower2[3];

		} else{
		$uChunk = explode(',', $upper);
			$r->status = 'ai';
			$r->branchCode = $uChunk[0];
			$r->accountCode =$uChunk[1];
			$r->invoiceNumber = $uChunk[2];

			$lChunks = explode(',', $lower1);
			foreach($lChunks as $lChunk) {
				$lower2 = explode('-', $lChunk);
					$r->subItemCode[] = $lower2[0];
					$r->quantity[] = $lower2[1];
					$r->memberDiscount[] = $lower2[2];
					$r->promoDiscount[] = $lower2[3];
					$r->discountType[] = $lower2[4];
			}
		}
		

		return $r;
	}
	public static function checkBranchCode($code)
	{
		return Branch::where('branch_code',strtoupper($code))->count();
	}
	public static function checkAccountCode($code)
	{
		return Contact::where('account_code',$code)->where('active',1)->count();
	}
	public static function checkInvoiceExist($invoiceNumber,$branchId)
	{
		return Invoice::where('invoice_number',$invoiceNumber)->where('invoice_status','D')->where('branch_id',$branchId)->count();
	}
	public static function checkBranchInventory($itemId,$branchId)
	{
		return DB::table('fiametta_warehouse.tbl_branch_inventory')->where('branch_id',$branchId)->where('itemId',$itemId)->count();
	}

	public static function selloutFormatFalse()
	{
		$inboxId = Inbox::select(DB::raw('LAST_INSERT_ID()'))->pluck('LAST_INSERT_ID()');
                                           $transaction = Inbox::where('message_id',$inboxId)->first();   
                                           $transaction->valid_sell_out_format = 0;
                                           $transaction->save();
	}
	final public static function getSalesReportRecipient()
	{
		return Sms::recipient(Contact::where('receive_sales_report','=','1')->where('active','=','1')->lists('mobile_number'));
	}
}