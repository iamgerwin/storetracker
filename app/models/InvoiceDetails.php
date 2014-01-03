<?php

class InvoiceDetails extends Eloquent {
    protected $guarded = array();
    protected $table = 'tbl_retail_invoice_details';
    public $privateKey= 'retail_invoice_details_id';
    public static $rules = array();
     public $timestamps = false;
}