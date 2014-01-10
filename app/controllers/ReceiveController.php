<?php

class ReceiveController extends BaseController {

    public function postTest()
    {

    }

    public function postCapture()
    {

        $in = Input::all();
            $date = new DateTime($in['ts']);
            $ts = $date->format('Y-m-d H:i:s');
            $ei = Gerwin::getWhitelistExist($in['from']);
            if($ei < 1)
            {
              $msgBack = 'Your number is not Whitelisted, please don\'t panic.';
              return Sms::sendIt($in['from'],$msgBack);
              die();
            }
            $whitelist = Whitelist::all();
            
                    foreach($whitelist as $w) {
                        if($w->mobile_number == $in['from']) {
                            //insert fiametta_retail.tbl_inbox         
                                 Inbox::insert([
                                        'message_sender' => $in['from'],
                                        'message_text' => $in['msg'],
                                        'date_sent' => $ts,
                                        'valid_sell_out_format' => 1
                                ]); 
                             
                        $sms =Sms::segregate($in['msg']);
                          
                           if($sms->status != 'ci'){
                                    $branchId = Gerwin::getBranchIdbyBranchCode($sms->branchCode);
                                    $contactId = Gerwin::getContactIdbyAccountCode($sms->accountCode);
                                    $invoiceNumber = $sms->invoiceNumber;
                                   

                                    $countInvoiceNumber = Sms::checkInvoiceExist($invoiceNumber,$branchId);
                                    
                                    //filter Branch ID
                                   if ( $branchId == NULL) {
                                        Sms::selloutFormatFalse();
                                        dd(Sms::sendIt($in['from'],"Invalid Branch Code : $sms->branchCode , Transaction is NOT VALID"));
                                   }
                                   if ($contactId == NULL) {
                                       Sms::selloutFormatFalse();
                                       dd(Sms::sendIt($in['from'],"Invalid Account Code : $sms->accountCode , Transaction is NOT VALID"));
                                   }
                                   if($countInvoiceNumber > 0) {
                                      Sms::selloutFormatFalse();
                                      dd(Sms::sendIt($in['from'],"Invoice Number Already Exist : $invoiceNumber , Transaction is NOT VALID"));
                                   }

                                        for ($i=0; $i < count($sms->subItemCode); $i++) { 
                                                  $itemId = Gerwin::getItemIdbySubItemCode($sms->subItemCode[$i]);
                                                  $items[] = Gerwin::getBranchInventoryId($branchId,$itemId); 
                                                  if ($items[$i] == NULL) {
                                                      Sms::selloutFormatFalse();
                                                      dd(Sms::sendIt($in['from'],"Branch Inventory Error: branchCode: $sms->branchCode | itemCode: $sms->subItemCode[$i], Transaction is NOT VALID"));
                                                  }
                                        }

                             //insert to tbl_retail_invoice
                                        Invoice::insert([
                                                'branch_id' => $branchId,
                                                'contact_id' => $contactId,
                                                'invoice_number' => $invoiceNumber,
                                                'invoice_date' => date('Y-m-d'),
                                                'invoice_status' => 'D'
                                            ]);
                             //insert to tbl_retail_invoice_details
                                        $lastId = Invoice::select(DB::raw('LAST_INSERT_ID()'))->pluck('LAST_INSERT_ID()');


                                        //insert to the Invoice Details
                                        for($i=0; $i < count($items); $i++) {
                                                    
                                                    $itemId = Gerwin::getItemIdbySubItemCode($sms->subItemCode[$i]);

                                                      if($sms->discountType[$i] == 0)
                                                        $disc = $sms->promoDiscount[$i] /100;
                                                      else
                                                        $disc = $sms->promoDiscount[$i];
                                                      
                                                          InvoiceDetails::insert([
                                                            'retail_invoice_id' => $lastId,
                                                            'branch_inventory_id' => $items[$i] ,
                                                            'unit_price' => Gerwin::getUnitPricebyItemId(Gerwin::getItemIdbySubItemCode($sms->subItemCode[$i])),
                                                            'quantity' => $sms->quantity[$i],
                                                            'discount_type' => $sms->discountType[$i],
                                                            'member_discount' => $sms->memberDiscount[$i] / 100,
                                                            'discount' =>  $disc
                                                         ]);
                                        }
                                        //textBack Verification of Insert

                                        $msgBack = Compute::test(Gerwin::getUniqueInvoice($branchId,$invoiceNumber));
                                        //dd($msgBack);
                                        Sms::sendIt($in['from'],$msgBack);

                                        return 'New Transaction Inserted!';
                          } else {
                                
                                $remarks = $sms->remark;
                                $invoiceNumber = $sms->invoiceNumber;
                                $accountCode = $sms->accountCode;
                                $branchCode = $sms->branchCode;
                                $branchId = Gerwin::getBranchIdbyBranchCode($branchCode);
                             //check Invoice exist
                                if(Sms::checkInvoiceExist($invoiceNumber,$branchId) <= 0)
                                {
                                    return Sms::sendIt($in['from'],'Invoice Number: '.$invoiceNumber.' Doesn\'t Exist or Has been already Cancelled.');
                                }
                            //update  tbl_retail_invoice
                                   $transaction = Invoice::where('invoice_number',$invoiceNumber)->where('invoice_status','D')->first();
                                   $transaction->remarks = $remarks;
                                   $transaction->invoice_status = 'C';
                                   $transaction->save();
                            //textBack cancel success
                                   Sms::sendIt($in['from'],'Successfully Cancelled Invoice Number: '.$invoiceNumber);

                                    return 'New Transaction Cancelled!'; 
                          }
                           

                        } 

                    }
    }


}