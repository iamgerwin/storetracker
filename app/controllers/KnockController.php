<?php

class KnockController extends BaseController {

    public function getIndex()
    {
        return View::make('knock.index');
    }

}