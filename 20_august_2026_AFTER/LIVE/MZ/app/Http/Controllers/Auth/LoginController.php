<?php
// Production Server 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\LdapUserLog;
use App\LogUser;
use App\User;
use App\Enum\AccessApp;
use App\RestrictedIp;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/Home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getUserInfo','logout']]);
    }

    // public function getUserInfo(Request $request){
    //     $request->validate([
    //         'user_id' => 'required',
    //     ]);


    //     $uId = Auth::user()->user_id;
    //     $request['username_or_email'] = $uId;

    //     $request['password'] = Session::get($uId);
    //     $request['reqMod'] = 'adSearch';

    //     $info = $this->login($request);


    //     $name = (!empty($info['cn'][0]))? $info['cn'][0]:'';
    //     $emp_id = (!empty($info['description'][0]))? $info['description'][0]:'';
    //     $user_id = (!empty($info['samaccountname'][0]))? $info['samaccountname'][0]:'';
    //     $email = (!empty($info['mail'][0]))? $info['mail'][0]:'';
    //     $designation = (!empty($info['title'][0]))? $info['title'][0]: '';
    //     $phone = (!empty($info['telephonenumber'][0]))? $info['telephonenumber'][0]:'';

    // $userInfo = [
    //     'name' => $name,
    //     'designation' => $designation,
    //     'phone' => $phone,
    //     'user_id' => $user_id,
    //     'emp_id' => $emp_id,
    //     'email' => $email
    // ];

    //     if ($userInfo) {
    //         return response()->json([
    //             'success' => true,
    //             'data' => $userInfo
    //         ]);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User not found'
    //         ]);
    //     }

    // }


    public function getUserInfo(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $uId = Auth::user()->user_id;

        // $request['username_or_email'] = $uId;
        // $ps = Session::get($uId);
        // $request['password'] = $ps;

	try {
            $e2d = Session::get($uId);
            $dPs = Crypt::decrypt($e2d);

        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Decryption failed. Please try again.',
            ], 500);
        }
	
        $request['username_or_email'] = $uId;
        $request['password'] = $dPs;
        $request['reqMod'] = 'adSearch';

	$info = $this->login($request);
	
	Log::info('ADUserInfo',$info);

        $name = $info['cn'][0] ?? '';
        $emp_id = $info['description'][0] ?? '';
        $user_id = $info['samaccountname'][0] ?? '';
        $email = $info['mail'][0] ?? '';
        $designation = $info['title'][0] ?? '';
        $phone = $info['telephonenumber'][0] ?? '';
	
        $userInfo = [
            'name' => $name,
            'designation' => $designation,
            'phone' => $phone,
            'user_id' => $user_id,
            'emp_id' => $emp_id,
            'email' => $email
        ];
	
        if (!empty($userInfo['user_id'])) {
            return response()->json([
                'success' => true,
                'data' => $userInfo
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
    }


    public function logout(Request $request, $redirect = '/')
    {

        $lastLoginId = DB::table('log_users')->where('user_id',Auth::user()->id)->orderBy('id', 'desc')->value('id');
        $logUser = new LogUser();
        $updateData = $logUser->where([['id', $lastLoginId]])->first();
        $updateData->log_out_at = now();
        $updateData->save();

        user_session_end();
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect($redirect);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $clientIP = $request->ip();
        $restrictedIp = RestrictedIp::where('ip', $clientIP)->first();

        $settingsInfo = \App\Setting::first();
        if($settingsInfo && $settingsInfo->allow_ip_restriction == 1) {
            if ($restrictedIp !== null) {
                user_session_end();//important for single session
                $request->session()->regenerate();
                $this->clearLoginAttempts($request);
                user_session_start();

                return $this->authenticated($request, $this->guard()->user())
                    ?: redirect()->intended($this->redirectPath());
            } else {
                user_session_end();
                $this->guard()->logout();
                $request->session()->invalidate();
                return redirect('/');
            }
        } elseif($settingsInfo && $settingsInfo->allow_ip_restriction == 0) {
            user_session_end();//important for single session
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            user_session_start();

            return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
        } else {
            user_session_end();//important for single session
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            user_session_start();

            return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
        }
    }
    protected function credentials(Request $request)
    {
        $field = filter_var($request->input($this->username()), FILTER_VALIDATE_EMAIL) ? 'email' : 'user_id';
        $request->merge([$field => $request->input($this->username())]);
        return array_merge($request->only($field, 'password'), ['status' => 1]);
    }
    public function username()
    {
        return 'username_or_email';
    }

	/*
    protected function authenticated(Request $request, $user)
    {
        flash('Successfully Logged In.', 'success');
        $clientIP = $request->ip();
        $userLog = new LogUser();
        $userLog->user_id = Auth::user()->id;
        $userLog->ip = $clientIP;
        $userLog->log_in_at = now();
        $userLog->save();
        // toast()->success('Successfully Logged In', 'Success');
        return redirect()->intended($this->redirectPath());
    }
    */

    /* Login with LDAP.FORUMSYS.COM */
    /* This method should be renamed with login */
    public function login(Request $request)
    {

        if (empty($request['username_or_email']) || empty($request['password'])) {
            //LDAP Logs
            /*$LdapUserLog = new LdapUserLog();
            $LdapUserLog->request = json_encode($request->except('_token','password'));
            $LdapUserLog->ip = $request->ip();
            $LdapUserLog->response = 'Fail';
            $LdapUserLog->save();*/
            return redirect()->to('login')->withErrors(array('username_or_email'=>'User Name and Password should not be blank'))->withInput();
        }

        // $userDB = User::where('user_id', '=', $request['username_or_email'])->first();
        // if($userDB === null) {
        //     return redirect()->to('login')->withErrors(array('username_or_email'=>'This user is not exists.'))->withInput();
        // }

        if (Adldap::auth()->attempt($request['username_or_email'], $request['password'], true)) {



            // Data search from user team
		if($request['reqMod'] == 'adSearch'){
	
			$userInfos = Adldap::search()->select(['mail', 'cn', 'sn', 'title','description', 'samaccountname','telephonenumber'])
				->where('samaccountname','=',$request['user_id'])
				->where('userAccountControl','=',512)
				->get();
		//dd($request['user_id'],json_decode($userInfos, true) );       
		return json_decode($userInfos, true)[0];
            }



            // prd($userInfos);
            ///////////////////////////////////////////////////////////////////////
            ///// get all data from ad to localDB OR update localDB from AD /////// //////////////////////////////////////////////////////////////////////
            $name = "";
            $username = "";
            $email = "";
            //$userInfos = Adldap::search()->where('uid', '=', $request['username_or_email'])->get();
	   // $userInfos = Adldap::search()->get();

	 //   dd($userInfos);
            // $userInfos = Adldap::search()->select(['mail', 'cn', 'sn', 'title', 'description', 'samaccountname','telephonenumber'])->where('userAccountControl','=',512)->get();

	   $userInfos = Adldap::search()->select(['mail', 'cn', 'sn', 'title','description', 'samaccountname','telephonenumber'])->where('samaccountname','=',$request['username_or_email'])->where('userAccountControl','=',512)->get();

            // data push in session
            $uId = $request['username_or_email'];
            $uPs = $request['password'];
            // Session::put($uId, $uPs);

            try {
                $encryptedPs = Crypt::encrypt($uPs);
                Session::put($uId, $encryptedPs);

            } catch (EncryptException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Encryption failed. Please try again.',
                ], 500);
            }


	    // All Information of single user
	   // $userInfos = Adldap::search()->where('samaccountname','=',$request['username_or_email'])->where('userAccountControl','=',512)->get();

	   //prd($userInfos);
	    $info = json_decode($userInfos, true);
            //prd(count($info));

            $users = [];
            $i=0;
            foreach ($info as $infoArr) {
                /*foreach ($infoArr as $userAtrribute => $userAtrributeValue) {
                    if($userAtrribute === "mail"){
                        $users[$i]["user_id"] = $userAtrributeValue[0];
                        $users[$i]["email"] = $userAtrributeValue[0];
                    }
				    $cust_name = "";
				    $sn_name = "";
                    if($userAtrribute === "cn"){
                           $cust_name = $userAtrributeValue[0];
                    }
		            if($userAtrribute === "sn"){
                           $sn_name = $userAtrributeValue[0];
                    }
                    $users[$i]["name"] = $cust_name.",".$sn_name;

                    if($userAtrribute === "title"){
                        $users[$i]["designation"] = $userAtrributeValue[0];
                    }
                }*/


                $userAD = array();
                $userAD["user_id"] = "";
                $userAD["emp_id"] = "";
                $userAD["email"] = "";
                $userAD["name"] = "";
                $userAD["designation"] = "";
                $userAD["mobile_no"] = "";

                if (!empty($infoArr['samaccountname'])) {
                    $userAD["user_id"] = $infoArr['samaccountname'][0];
                }

                $cust_name = "";
                $sn_name = "";

                if(!empty($infoArr['mail'])){
                    $userAD["email"] = $infoArr['mail'][0];
                }

                if(!empty($infoArr['cn'])){
                    $userAD["name"] = $infoArr['cn'][0];
                    //    $cust_name = $infoArr['cn'][0];

                }
                if(!empty($infoArr['description'])){
                    $userAD["emp_id"] = $infoArr['description'][0];
                }

                // $userAD["name"] = $cust_name.", Emp. ID: ".$sn_name;

                if(!empty($infoArr['title'])){
                    $userAD["designation"] = $infoArr['title'][0];
                }

                if(!empty($infoArr['telephonenumber'])){
                    $userAD["mobile_no"] = $infoArr['telephonenumber'][0];
                }
                // $i++;
                //prd($userAD);
                if(!empty($infoArr['samaccountname'][0])){

                    $user_id = $userAD["user_id"];
                    $emp_id = $userAD["emp_id"];
                    $name = $userAD["name"];
                    $email = $userAD["email"];
		            $designation = $userAD["designation"];
                    $phone = $userAD["mobile_no"];

                    $password = "";
                    $userDB = User::where('user_id', '=', $user_id)->first();

                    if($userDB === null) {
                        // if any user is missing, add it to local DB
                        // $user = new User;
                        // $user->name = $name;
                        // $user->user_id = $user_id;
                        // $user->email = $email;
                        // $user->status = 0;
			            // $user->designation = $designation;
                        // $user->mobile_no = $phone;
                        // $user->password = $password;
                        // $user->save();
                        return redirect()->to('login')->withErrors(array('username_or_email'=>'The provided credentials do not found our record'))->withInput();


                    } else {
                        //update local DB
                        $userDB->name = $name;
                        $userDB->user_id = $user_id;
                        $userDB->emp_id = $emp_id;
                        $userDB->email = $email;
			            $userDB->designation = $designation;
                        $userDB->mobile_no = $phone;
                        $userDB->password = $password;

                        //attach role "admin" for admin and "executive for other users"
                        /*if($user_id === "einstein"){
                            $roleAdmin = Role::where('name', '=', 'admin')->first();
                            if(!empty($roleAdmin)) {
                                $userDB->roles()->syncWithoutDetaching([$roleAdmin->id]);
                            }
                            //$userDB->attachRole(Role::where('name','admin')->first());
                        }*/
                        $userDB->save();
                    }

                }
            }

            // prd($users);

            /* initialize roles table with admin role, if already not there */
            /*$roleDB = Role::where('name', '=', 'admin')->first();
            if($roleDB === null) {
                $role = new Role;
                $role->name="admin";
                $role->display_name="Admin";
                $role->description="Admin of the site";
                $role->save();
            }*/
            /*
            foreach ($users as $userAD) {
                if(!empty($userAD["email"])){
                    $user_id = $userAD["user_id"];
                    $name = $userAD["name"];
                    $email = $userAD["email"];
                    $password = NULL;
                    $userDB = User::where('user_id', '=', $user_id)->first();
                    if($userDB === null) {
                        // if any user is missing, add it to local DB
                        $user = new User;
                        $user->name = $name;
                        $user->user_id = $user_id;
                        $user->email = $email;
                        $user->password = $password;

                        //attach role "admin" for admin and "executive for other users"
                        if($user_id === "einstein"){
                            $roleAdmin = Role::where('name', '=', 'admin')->first();
                            $user->roles()->syncWithoutDetaching([$roleAdmin->id]);
                            //$user->attachRole(Role::where('name','admin')->first());
                        }
                        $user->save();
                    } else {
                        //update local DB
                        $userDB->name = $name;
                        $userDB->user_id = $user_id;
                        $userDB->email = $email;
                        $userDB->password = $password;

                        //attach role "admin" for admin and "executive for other users"
                        if($user_id === "einstein"){
                            $roleAdmin = Role::where('name', '=', 'admin')->first();
                            if(!empty($roleAdmin)) {
                                $userDB->roles()->syncWithoutDetaching([$roleAdmin->id]);
                            }
                            //$userDB->attachRole(Role::where('name','admin')->first());
                        }
                        $userDB->save();
                    }
                }
            }
            */
            /*
                $name = $info['cn'][0];
                $user_id = $request['user_id'];
                $email = $info['mail'][0];
                $password = $info['userpassword'][0];

                echo "<hr/>";
                echo $name."<br/>";
                echo $user_id."<br/>";
                echo $email."<br/>";
                echo $password."<br/>";
            */
            // dd($info);

            ///////////////////////////////////////////////////////////////////////
            //////////////// check user from AD and login user /////////////////// //////////////////////////////////////////////////////////////////////

            $name = "";
            $user_id = "";
            $email = "";
            $userInfos = Adldap::search()->where('sAMAccountName', '=', $request['username_or_email'])->get();

            $info = json_decode($userInfos, true)[0];

            if(!empty($info)){

                // $cname1 = (!empty($info['cn'][0]))? $info['cn'][0]:'';
                // $cname2 = (!empty($info['description'][0]))? $info['description'][0]:'';
                // $name = $cname1.', Emp. ID: '.$cname2;

                $name = (!empty($info['cn'][0]))? $info['cn'][0]:'';
                $emp_id = (!empty($info['description'][0]))? $info['description'][0]:'';

                $user_id = $request['username_or_email'];
                $email = (!empty($info['mail'][0]))? $info['mail'][0]:'';
		        $designation = (!empty($info['title'][0]))? $info['title'][0]: '';
                $phone = (!empty($info['telephonenumber'][0]))? $info['telephonenumber'][0]:'';

                $password = "";
                $user = User::where('user_id', '=', $user_id)->first();

                if($user === null) {
                    // echo "user not found"; die;
                    // $user = new User;
                    // $user->name = $name;
                    // $user->user_id = $user_id;
                    // $user->email = $email;
		            // $user->designation = $designation;
                    // $user->status = 0;
                    // $user->mobile_no = $phone;
                    // $user->password = $password;

                    // attach role "admin" for admin and "executive for other users"
                    // if($user_id === "einstein"){
                    //     $roleAdmin = Role::where('name', '=', 'admin')->first();
                    //     $user->roles()->syncWithoutDetaching([$roleAdmin->id]);
                    //     //$user->attachRole(Role::where('name','admin')->first());
                    // }

                    // $user->save();

                    return redirect()->to('login')->withErrors(array('username_or_email'=>'The provided credentials do not found our record'))->withInput();

                    // Auth::login($user);

                    // if ($user && $user->status == 1) {
                    //     Auth::login($user);
                    // } else {

                        // return redirect()->to('login')->withErrors(array('username_or_email'=>'The provided credentials do not match our records or the account is inactive.'))->withInput();
                        // return back()->withErrors([
                        //     'email' => 'The provided credentials do not match our records or the account is inactive.',
                        // ]);
                    // }

                        // $clientIP = $request->ip();
                        // $userLog = new LogUser();
                        // $userLog->user_id = Auth::user()->id;
                        // $userLog->ip = $clientIP;
                        // $userLog->log_in_at = now();
                        // $userLog->save();

                        //LDAP Logs
                        // $LdapUserLog = new LdapUserLog();
                        // $LdapUserLog->request = json_encode($request->except('_token','password'));
                        // $LdapUserLog->ip = $request->ip();
                        // $LdapUserLog->response = 'Success';
                        // $LdapUserLog->save();

                        // return redirect()->to('/');

                } else {

                    if ($user && $user->status == 1) {
                            Auth::login($user);
                    } else {
                        return redirect()->to('login')->withErrors(array('username_or_email'=>'The provided credentials do not match our records or the account is inactive.'))->withInput();
                    }

                    // Auth::login($user);

					$clientIP = $request->ip();
					$userLog = new LogUser();
					$userLog->user_id = Auth::user()->id;
					$userLog->ip = $clientIP;
					$userLog->log_in_at = now();
					$userLog->save();

                    //LDAP Logs
                    $LdapUserLog = new LdapUserLog();
                    $LdapUserLog->request = json_encode($request->except('_token','password'));
                    $LdapUserLog->ip = $request->ip();
                    $LdapUserLog->response = 'Success';
                    $LdapUserLog->save();



                    // access wise login (module based)
                    $modules = $user->roles->pluck('module')->unique();

                    if ($modules->contains(AccessApp::ServiceComplaint) && $modules->contains(AccessApp::Requisition)) {
                        return redirect('/both-access');
                    } elseif ($modules->contains(AccessApp::ServiceComplaint)) {
                        Session::put('module', AccessApp::ServiceComplaint);
                        return redirect()->intended($this->redirectPath());
                    } elseif ($modules->contains(AccessApp::Requisition)) {
                        Session::put('module', AccessApp::Requisition);
                        return redirect()->route('requisition.requisition.index');
                    }


                    return redirect()->intended($this->redirectPath());
                }

            } else {
                //LDAP Logs
                $LdapUserLog = new LdapUserLog();
                $LdapUserLog->request = json_encode($request->except('_token','password'));
                $LdapUserLog->ip = $request->ip();
                $LdapUserLog->response = 'Fail';
                $LdapUserLog->save();
                return redirect()->to('login')->withErrors('No user info present');
            }
        }
        //LDAP Logs
        $LdapUserLog = new LdapUserLog();
        $LdapUserLog->request = json_encode($request->except('_token','password'));
        $LdapUserLog->ip = $request->ip();
        $LdapUserLog->response = 'Fail';
        $LdapUserLog->save();
        return redirect()->to('login')->withErrors(array('username_or_email'=>'Your username or password is incorrect'));
    }

    /* End of LDAP.FORUMSYS.COM */
}
