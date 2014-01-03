<?php

class Invoice extends Eloquent {
    protected $guarded = array();
    protected $table = 'tbl_retail_invoice';
    public $primaryKey = 'retail_invoice_id';
    public static $rules = array();
    public $timestamps = false;


    public function brancher()
    {
    	return $this->belongsTo('Branch','branch_id');
    }
    
}