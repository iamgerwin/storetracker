<?php

class Contact extends Eloquent {
    protected $guarded = array();
    protected $table = 'tbl_contact';
    public $primaryKey = 'contact_id';
    public static $rules = array();
    public $timestamps = false;
}