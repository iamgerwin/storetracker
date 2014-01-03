<?php

class SelloutController extends BaseController {

    public function getIndex()
    {
        $id = Session::get('account_id');
        $user = User::where('employeeid','=',$id)->first();
    
        return View::make('sellout')
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

}