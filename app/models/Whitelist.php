<?php

class Whitelist extends Eloquent {
    protected $guarded = array();
    protected $table = 'tbl_whitelist';
    public $primaryKey = 'whitelist_id';
    public static $rules = array();
    public $timestamps = false;
}