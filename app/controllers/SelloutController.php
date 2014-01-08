<?php

class SelloutController extends BaseController {

    public function getIndex()
    {

        $id = Session::get('account_id');
        $user = User::where('employeeid','=',$id)->first();
        $branches = Gerwin::getBranchKiosk();

        return View::make('sellout')
        ->with('branches',$branches)
        ->with('role',$user->role_id);
    }
    public function postInvoice()
    {    
        //$data = SelloutH::dataInvoice('vwretailinvoice_mdl',SelloutH::dateNow(),SelloutH::dateNow());
            $data =  SelloutH::dataInvoice2('fiametta_retail.tbl_retail_invoice',SelloutH::dateNow(),SelloutH::dateNow());
            //$br = $data->brancher;
          $itemData = null;
          $invoiceData = null;
            for($i = 0;$i<count($data);$i++) {
                $itemData[] = Compute::itemData($data[$i]->retail_invoice_id);
                $invoiceData[] = Compute::invoiceData($itemData[$i]->subTotal,$itemData[$i]->itemDiscount);
            }

        return View::make('sellout.invoiceTable')
        ->with('data',$data)
        
        ->with('invoiceData',$invoiceData);
    }
    public function postInvoicedetails()
    {
        $data = SelloutH::dataInvoice('vwretailinvoicedetails',SelloutH::dateNow(),SelloutH::dateNow());
            //dd($data);
        return View::make('sellout.invoiceDetailTable')
                ->with('datas',$data);
    }
    public function postInvoicerange()
    {
        // $data = SelloutH::dataInvoice('fiametta_retail.tbl_retail_invoice',Input::get('from'),Input::get('to'));
        //         //dd($data);
        // return View::make('sellout.invoiceTable')
        //        ->with('datas',$data);
        $data =  SelloutH::dataInvoice2('fiametta_retail.tbl_retail_invoice',Input::get('from'),Input::get('to'));
        $br = $data;
       //dd($br);
          $itemData = null;
          $invoiceData = null;
            for($i = 0;$i<count($data);$i++) {
                $itemData[] = Compute::itemData($data[$i]->retail_invoice_id);
                $invoiceData[] = Compute::invoiceData($itemData[$i]->subTotal,$itemData[$i]->itemDiscount);
            }

        return View::make('sellout.invoiceTable')
        ->with('data',$data)
        
        ->with('invoiceData',$invoiceData);
    }
    public function postInvoicedetailsrange()
    {
        $data = SelloutH::dataInvoice('vwretailinvoicedetails',Input::get('from'),Input::get('to'));
            //dd($data);
        return View::make('sellout.invoiceDetailTable')
               ->with('datas',$data);
    }
    public function postInvoiceall()
    {
        $data = SelloutH::dataInvoice2('fiametta_retail.tbl_retail_invoice','all',null);
                //dd($data);
        $br = $data->brancher;
          $itemData = null;
          $invoiceData = null;
            for($i = 0;$i<count($data);$i++) {
                $itemData[] = Compute::itemData($data[$i]->retail_invoice_id);
                $invoiceData[] = Compute::invoiceData($itemData[$i]->subTotal,$itemData[$i]->itemDiscount);
            }

        return View::make('sellout.invoiceTable')
        ->with('data',$data)
        
        ->with('invoiceData',$invoiceData);
    }
    public function postInvoicedetailsall()
    {
        $data = SelloutH::dataInvoice('vwretailinvoicedetails','all',null);
            //dd($data);
        return View::make('sellout.invoiceDetailTable')
        ->with('datas',$data);
    }
    public function postSummary()
    {
        $input = Input::all();
                    if($input['from'] == '') {
                        dd('date err0r');
                    }
                    if($input['branches'] == '') {
                        dd('branch err0r');
                    }

                    $nowDate = $input['from'];
                    $nowDate2 = $input['to'];
                    $date = date('Y-m-d', strtotime($nowDate.'-0 day'));
                    $date2 = date('Y-m-d', strtotime($nowDate2.'-0 day'));
                    $dates[] = $date;
                    $dates[] = $date2;
                    if($date > $date2)
                    {
                        dd('date range err0r');
                    }
                    if(in_array('all', $input['branches']))
                    {
                        $bI = Gerwin::getKiosk();
                    } else {
                        $bI=$input['branches'];
                    }
                        
                    
                    $dS='';
                    
                    foreach ($bI as $c)
                    {
                            if($date2 == '' || $date2 == $date)
                            {
                                $rid = DB::table('vwretailinvoice_mdl')->where('branch_id',$c)->where('invoice_status','D')->where('invoice_date',$date)->groupBy('retail_invoice_id')->lists('retail_invoice_id');
                            } 
                            else
                            {
                                $rid = DB::table('vwretailinvoice_mdl')->where('branch_id',$c)->where('invoice_status','D')->whereBetween('invoice_date',[$date,$date2])->groupBy('retail_invoice_id')->lists('retail_invoice_id');
                            }
                            
                             $tot=0;
                             $tid=0;
                             $tst=0;
                             $tgs=0;
                             $tta=0;
                           for($x=0;$x<count($rid);$x++)
                            {
                                 $itD = (Compute::itemData($rid[$x]));
                                 $inD = Compute::invoiceData($itD->subTotal,$itD->itemDiscount);
                               
                                //$tid += $itD->itemDiscount[0];
                                //$tst += $itD->subTotal[0];
                                $tst += $inD->totalPrice;
                                $tta += $inD->outputTax;
                                $tgs += $inD->grossSales;
                                $tid += $inD->totalDiscount;
                                $tot += $inD->netSales;
                            }
                           
                            $branchId[] = $c;
                            $taxOut[] = number_format($tta,2);
                            $totPri[] = number_format($tst,2);
                            $groAmt[] = number_format($tgs,2);
                            $totDis[] = number_format($tid,2);
                            $netAmt[] = number_format($tot,2);
  
                    }
        
                    return View::make('sellout.summary')
                        ->with('dates',$dates)
                        ->with('taxOut',$taxOut)
                        ->with('totPri',$totPri)
                        ->with('branchId',$branchId)
                        ->with('groAmt',$groAmt)
                        ->with('totDis',$totDis)
                        ->with('netAmt',$netAmt);

    }
    public function postInvoicesummary()
    {

    }

}