<?php

class Inbox extends Eloquent {
    protected $guarded = array();
    protected $table = 'tbl_inbox';
    public $primaryKey='message_id';
    public static $rules = array();
     public $timestamps = false;
}