<?php

class Item extends Eloquent {
    protected $guarded = array();
    protected $table = 'fiametta_warehouse.tbl_item';
    public $primaryKey = 'item_id';
    public static $rules = array();
    public $timestamps = false;

	public function scopeAvailableItemKiosk($query) {

		return $query->where('item_type_id',1)->orWhere('item_type_id', 5)->where('active',1);
	}

}