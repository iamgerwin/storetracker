<?php

class SendController extends BaseController {

    public function getIndex()
    {
        return 'NOT AUTHORIZED! :(';
    }
    public function getTest()
    {
 
    }
    public function getSalesreport()
    {
                    $nowDate = date('Y-m-d');
                    $date = date('Y-m-d', strtotime($nowDate.'+0 day'));
                    
                    $bI = Gerwin::getKiosk();
                    $dS= 'Date: '.$date."\n";
                    foreach ($bI as $c)
                    {
                            $rid = DB::table('vwretailinvoice_mdl')->where('branch_id',$c)->where('invoice_status','D')->where('invoice_date',$date)->groupBy('retail_invoice_id')->lists('retail_invoice_id');
                             $tot =0;
                           for($x=0;$x<count($rid);$x++)
                            {
                                 $itD = (Compute::itemData($rid[$x]));
                                 $inD = Compute::invoiceData($itD->subTotal,$itD->itemDiscount);
                                $tot += $inD->netSales;
                            }
                            $dS .= Gerwin::getBranchNameByBranchId($c).' -  '.number_format($tot,2)."\n";
                    }

                    $nos = Gerwin::getContactReceiveSalesReport();
                    
                    if($_SERVER['REMOTE_ADDR'] == '122.52.163.202') {
                        return Sms::sendIt($nos,$dS);    
                    }  
                    else {
                        return App::abort(401,'Not Authorized');
                    }
     }      
    public function getReplysubmitinvoice()
    {  
        $to = '09323729873';
        $msg = 'Gerwin Here!';
        dd($_SERVER['REMOTE_ADDR']);
        return Sms::sendIt($to,$msg);
     }      

     public function postDailysalesreport()
     {
            $pips = Sms::getSalesReportRecipient();
            $pips = '09323729873';
            echo $pips;

     }

    public function missingMethod($parameters='gerwin')
    {
        dd($parameters);
    }
}