<?php

class SettingsController extends BaseController {

    public function getIndex()
    {
        return View::make('settings');
    }
    public function postWhitelist()
    {
    	$data = Whitelist::all();
    	return View::make('settings.whitelist')
    	->with('datas',$data);
    }
    public function postAddwhitelist()
    {
        $input = Input::all();
        
        $rules = [
            'mobileNumber' => 'required|numeric|unique:tbl_whitelist,mobile_number',
            'remarks' => 'required',
        ];
        
        $valid = Validator::make($input, $rules);

        if($valid->passes()) {
            $pass ="<script>$.pnotify({
                title: 'Success Add',
                text: 'Adding new Whitelist',
                type: 'success'
            }); </script>";
            
            Whitelist::insert([
                'mobile_number' => $input['mobileNumber'],
                'remarks' => $input['remarks']
            ]);

            return  $pass;            
        } else {
            $errors = $valid->messages();
            $err = '<ul class="alert alert-error">';
            foreach ($errors->all() as $error) {
                $err .= '<li>'.$error.'</li>';
            }
            $err .= '</ul>';
            $failed ="<script>$.pnotify({
                title: 'Failed Adding new Whitelist',
                text: ' ";
                    foreach ($errors->all() as $error) {
                        $failed .= '<li>'.$error.'</li>';
                    }
                $failed .=" ',
                type: 'error'
            }); </script>";

            return $failed;
        }
    }
    public function postDeletewhitelist()
    {
        Whitelist::where('whitelist_id','=',Input::get('id'))->delete();
    }
    public function postEditwhitelist()
    {
    	$input = Input::all();
    	$id = $input['wid'];
    	$rules = [
    		'mobileNumber' => 'required|numeric',
    		'remarks' => 'required',
    	];
    	
    	$valid = Validator::make($input, $rules);

    	if($valid->passes()) {
    		$pass ="<script>$.pnotify({
			    title: 'Success Update for $id',
			    text: 'updated $id',
			    type: 'success'
			}); </script>";
			$wData = Whitelist::where('whitelist_id',$id)
				->first();

			if($wData->mobile_number != $input['mobileNumber'] || $wData->remarks != $input['remarks']) {

				Whitelist::where('whitelist_id',$id)->update([
					'mobile_number' => $input['mobileNumber'],
					'remarks' => $input['remarks']
					]);

				return $pass;
			} else {
				$pass ="<script>$.pnotify({
			    title: 'Noting to Update for $id',
			    text: 'Nothing for $id',
			    type: 'error'
			}); </script>";
				return  $pass;
			}
    		
    	} else {
    		$errors = $valid->messages();
            $err = '<ul class="alert alert-error">';
            foreach ($errors->all() as $error) {
                $err .= '<li>'.$error.'</li>';
            }
            $err .= '</ul>';
            $failed ="<script>$.pnotify({
			    title: 'Failed Update for $id',
			    text: ' ";
				    foreach ($errors->all() as $error) {
	                	$failed .= '<li>'.$error.'</li>';
	            	}
			    $failed .=" ID $id',
			    type: 'error'
			}); </script>";

            return $failed;
    	}
    }
    public function postSettings()
    {
    	$password = User::where('employeeid',Session::get('account_id'))->pluck('password');

    	$input = Input::all();

    	$rules = [
    		'oldPassword' => 'required',
    		'newPassword' => 'required|different:oldPassword|min:6',
            'confirmPassword' =>'required|same:newPassword'
    	];

    	$valid = Validator::make($input, $rules);

    	if($valid->passes()) {
    		if( $password === md5($input['oldPassword']) ) {

    			User::where('employeeid', Session::get('account_id'))->update([
    					'password' => md5($input['newPassword'])
    				]);

    			Session::forget('Login');

    			return '<ul class="alert alert-success">
    				<li>Password Changed!</li>
    			</ul>';
    		} else {
    			return '<ul class="alert alert-error">
    				<li>oldPassword is Wrong!</li>
    			</ul>';
    		}
    	} else {
            $errors = $valid->messages();
            $err = '<ul class="alert alert-error">';
            foreach ($errors->all() as $error) {
                $err .= '<li>'.$error.'</li>';
            }
            $err .= '</ul>';

            return $err;
        }

    }

}