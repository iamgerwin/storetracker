<?php

class SelloutH extends Gerwin {
	
	public static function dateNow()
	{
		return date('Y-m-d', time());
	}
	public static function dataInvoice($viewTable,$from,$to)
	{	
		if($from == 'all')
			return DB::table($viewTable)->orderBy('invoice_date','desc')->get();
		else if($to == $from)
			return DB::table($viewTable)->where('invoice_date',$from)->orderBy('invoice_date','desc')->get();
		else
			return DB::table($viewTable)->whereBetween('invoice_date',[$from,$to])->orderBy('invoice_date','desc')->get();
	}
	public static function dataInvoice2($viewTable,$from,$to)
	{	
		if($from == 'all')
			return Invoice::with('brancher')->orderBy('invoice_date','desc')->get();
		else if($to == $from)
			return Invoice::with('brancher')->where('invoice_date',$from)->orderBy('invoice_date','desc')->get();
		else
			return Invoice::with('brancher')->whereBetween('invoice_date',[$from,$to])->orderBy('invoice_date','desc')->get();
	}
	public static function dataInvoice3($viewTable,$from,$to)
	{	
		if($from == 'all')
			return InvoiceDetails::orderBy('invoice_date','desc')->get();
		else if($to == $from)
			return InvoiceDetails::where('invoice_date',$from)->orderBy('invoice_date','desc')->get();
		else
			return InvoiceDetails::whereBetween('invoice_date',[$from,$to])->orderBy('invoice_date','desc')->get();
	}
}