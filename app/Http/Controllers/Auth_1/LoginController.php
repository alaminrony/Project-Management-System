<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth; //model class

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

    use AuthenticatesUsers;
	
	
	//only override this function for adding condition status wise user can login 
    //directory main file where implements this credentials function LARAVEL 5.6 default : atms\vendor\laravel\framework\src\Illuminate\Foundation\Auth\AuthenticatesUsers.php
    protected function credentials(Request $request) {
        $data = $request->only($this->username(), 'password');
        $data['status'] = '1';
        return $data;
    }
	
	
	//user mail cange for username
    //directory main file where implements this credentials function LARAVEL 5.6 default : atms\vendor\laravel\framework\src\Illuminate\Foundation\Auth\AuthenticatesUsers.php
    public function username() {
        return 'username';
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request) {
        $request->session()->put('paginatorCount', __('label.PAGINATION_COUNT'));
    }
}
