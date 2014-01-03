<?php

class ContactsController extends BaseController {

    public function getIndex()
    {
        return View::make('contacts');
    }
    public function postLoadcontacts()
    {
    	return View::make('contacts.contactsTable')->with('datas',Contact::all());
    }
    public function postAdd()
    {
    	$input = Input::all();

        $rules = [
            'accountcode' => 'required|unique:tbl_contact,account_code',
            'mobilenumber' => 'required|numeric|unique:tbl_contact,mobile_number',
            'firstname' => 'required|max:20',
            'middlename' => 'max:20',
            'lastname' => 'required|max:20',
            'salesreport' => 'required|in:0,1',
            'active' =>'required|in:0,1'
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            try {
                Contact::insert([
                                'account_code' => htmlspecialchars($input['accountcode']),
                                'last_name' => htmlspecialchars($input['lastname']),
                                'first_name' => htmlspecialchars($input['firstname']),
                                'middle_name' => htmlspecialchars($input['middlename']),
                                'mobile_number' => htmlspecialchars($input['mobilenumber']),
                                'active' => $input['active'],
                                'receive_sales_report' => $input['salesreport']
                            ]);
            } catch (Exception $e) {
                dd($e);
            }
            
            $pass = '<ul class="alert alert-success"><li>New Contact Added!</li></ul>';
            return $pass;
        } else {
            $errors = $validator->messages();
            $err = '<ul class="alert alert-error">';
            foreach ($errors->all() as $error) {
                $err .= '<li>'.$error.'</li>';
            }
            $err .= '</ul>';

            return $err;
        }
        
    }
    public function postEdit()
    {
        $input = Input::all();

        $id = Contact::where('account_code',$input['accountcode'])->pluck('contact_id');

        $rules = [
            'mobilenumber' => 'required|numeric|unique:tbl_contact,mobile_number,'.$id.',contact_id',
            'firstname' => 'required|max:20',
            'middlename' => 'max:20',
            'lastname' => 'required|max:20',
            'salesreport' => 'required|in:0,1',
            'active' =>'required|in:0,1'
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
                Contact::where('contact_id', $id)
                            ->update(array(
                                'last_name' => htmlspecialchars($input['lastname']),
                                'first_name' => htmlspecialchars($input['firstname']),
                                'middle_name' => htmlspecialchars($input['middlename']),
                                'mobile_number' => htmlspecialchars($input['mobilenumber']),
                                'active' => htmlspecialchars($input['active']),
                                'receive_sales_report' => htmlspecialchars($input['salesreport'])
                            ));
            // $pass = '<ul class="alert alert-success"><li>Updated!</li></ul>';
                            $pass = 1;
            return $pass;
            
        } else {
            $errors = $validator->messages();

            $err = '<ul class="alert alert-error">';
            foreach ($errors->all() as $error) {
                $err .= '<li>'.$error.'</li>';
            }
            $err .= '</ul>';
            

            return $err;
        }
    }


}