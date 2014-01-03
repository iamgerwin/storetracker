<?php

class Branch extends Eloquent {
    protected $guarded = array();
    protected $table = 'fiametta_warehouse.tbl_branch';
    public $primaryKey = 'branch_id';
    public static $rules = array();
    public $timestamps = false;

    public function invoices()
    {
    	$this->hasMany('Invoice');
    }
}