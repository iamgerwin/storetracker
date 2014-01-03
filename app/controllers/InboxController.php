<?php

class InboxController extends BaseController {

    public function getIndex()
    {
        return View::make('inbox');
    }

    public function postInbox()
    {
        $currentDate = date('Y-m-d', time());
        
        $data = Inbox::where('date_sent','=',$currentDate)->get();
        return View::make('inbox.inboxTable')
        ->with('datas',$data);
    }

    public function postInboxrange()
    {
        if(Input::get('from') == Input::get('to'))
            $data = Inbox::where('date_sent', Input::get('from'))->orderBy('date_sent','desc')->take(500)->get();
        else
            $data = Inbox::whereBetween('date_sent', array(Input::get('from'), Input::get('to')))->orderBy('date_sent','desc')->take(500)->get();

        return View::make('inbox.inboxTable')
        ->with('datas',$data);
    }

    public function postInboxall()
    {
        $data = Inbox::all();
        return View::make('inbox.inboxTable')
        ->with('datas',$data);
    }


}