<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\LogUser;
use App\RestrictedIp;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use Adldap\Laravel\Facades\Adldap;
use App\Enum\AccessApp;
use Illuminate\Support\Facades\Session;

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
        $this->middleware('guest', ['except' => 'logout']);
    }
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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
    protected function authenticated(Request $request, $user)
    {
        flash('Successfully Logged In.', 'success');
        $clientIP = $request->ip();
        $userLog = new LogUser();
        $userLog->user_id = Auth::user()->id;
        $userLog->ip = $clientIP;
        $userLog->log_in_at = now();
        $userLog->save();

        // access wise login
        $modules = $user->roles->pluck('module')->unique(); // [1, 2]

        if ($modules->contains(AccessApp::ServiceComplaint) && $modules->contains(AccessApp::Requisition)) {
            return redirect('/both-access');
        } elseif ($modules->contains(AccessApp::ServiceComplaint)) {
            Session::put('module', AccessApp::ServiceComplaint);
            return redirect()->intended($this->redirectPath());
        } elseif ($modules->contains(AccessApp::Requisition)) {
            Session::put('module', AccessApp::Requisition);
            return redirect()->route('requisition.requisition.index');
        }
        // toast()->success('Successfully Logged In', 'Success');
        return redirect()->intended($this->redirectPath());
    }

    /* Login with LDAP.FORUMSYS.COM */
    /* This method should be renamed with login */
    public function loginWithAd(Request $request)
    {

        if (Adldap::auth()->attempt($request['username_or_email'], $request['password'], true)) {

            ///////////////////////////////////////////////////////////////////////
            ///// get all data from ad to localDB OR update localDB from AD /////// //////////////////////////////////////////////////////////////////////
            $name = "";
            $username = "";
            $email = "";
            // $userInfos = Adldap::search()->where('uid', '=', $request['username_or_email'])->get();
            $userInfos = Adldap::search()->get();
            // $userInfos = Adldap::search()->where('uid','=',$request['username_or_email'])->get();
            $info = json_decode($userInfos, true);
            // prd($info);

            $users = [];
            $i=0;
            foreach ($info as $infoArr) {
                foreach ($infoArr as $userAtrribute => $userAtrributeValue) {
                    if($userAtrribute === "uid"){
                        $users[$i]["username"] = $userAtrributeValue[0];
                    }
                    if($userAtrribute === "cn"){
                        $users[$i]["name"] = $userAtrributeValue[0];
                    }
                    if($userAtrribute === "mail"){
                        $users[$i]["email"] = $userAtrributeValue[0];
                    }
                }
                $i++;
            }

           prd($users);

            // initialize roles table with admin role, if already not there
            // $roleDB = Role::where('name', '=', 'admin')->first();
            // if($roleDB === null)
            // {
            //     $role = new Role;
            //     $role->name="admin";
            //     $role->display_name="Admin";
            //     $role->description="Admin of the site";
            //     $role->save();
            // }

            foreach ($users as $userAD) {
                if(isset($userAD["email"])&&!empty($userAD["email"])){
                    $username = $userAD["username"];
                    $name = $userAD["name"];
                    $email = $userAD["email"];
                    $password = NULL;
                    $userDB = User::where('username', '=', $username)->first();
                    if($userDB === null)
                    {
                        // if any user is missing, add it to local DB
                        $user = new User;
                        $user->name = $name;
                        $user->username = $username;
                        $user->email = $email;
                        $user->password = $password;

                        //attach role "admin" for admin and "executive for other users"
                        if($username === "einstein"){
                            $roleAdmin = Role::where('name', '=', 'admin')->first();
                            $user->roles()->syncWithoutDetaching([$roleAdmin->id]);

                            //$user->attachRole(Role::where('name','admin')->first());
                        }

                        $user->save();
                    }else{
                        //update local DB
                        $userDB->name = $name;
                        $userDB->username = $username;
                        $userDB->email = $email;
                        $userDB->password = $password;

                        //attach role "admin" for admin and "executive for other users"
                        if($username === "einstein"){
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
            // $name = $info['cn'][0];
            //     $username = $request['username'];
            //     //$email = $info['mail'][0];
            //     //$password = $info['userpassword'][0];

            //     echo "<hr/>";
            //     echo $name."<br/>";
            //     echo $username."<br/>";
            //     echo $email."<br/>";
            //     //echo $password."<br/>";

           // dd($info);

            ///////////////////////////////////////////////////////////////////////
            //////////////// check user from AD and login user /////////////////// //////////////////////////////////////////////////////////////////////

            $name = "";
            $username = "";
            $email = "";
            $userInfos = Adldap::search()->where('uid', '=', $request['username'])->get();

            $info = json_decode($userInfos, true)[0];
            if(!empty($info)){
                $name = $info['cn'][0];
                $username = $request['username'];
                $email = $info['mail'][0];
                $password = NULL;
                $user = User::where('username', '=', $username)->first();
                if($user === null)
                {
                    // echo "user not found"; die;
                    $user = new User;
                    $user->name = $name;
                    $user->username = $username;
                    $user->email = $email;
                    $user->password = $password;

                    //attach role "admin" for admin and "executive for other users"
                    if($username === "einstein"){
                        $roleAdmin = Role::where('name', '=', 'admin')->first();
                        $user->roles()->syncWithoutDetaching([$roleAdmin->id]);
                        //$user->attachRole(Role::where('name','admin')->first());
                    }

                    $user->save();

                    Auth::login($user);
                    return redirect()->to('/');

                    //return view('home')->with("title","Home");

                }else{
                   // echo "user found"; die;
                    Auth::login($user);
                    return redirect()->to('/');

                    //return view('home')->with("title","Home");
                }

                // Auth::login($user);
                // //return redirect()->intended('dashboard');
                // return view('home')->with("title","Home");
            }else{
           // echo 'It does not Work!!'; die;
            // return redirect()->to('login')
            // ->withMessage('No user info present');

            return redirect()->to('login')
            ->withErrors('No user info present');
            }
        } else {
            pr("FAILED");
            prd($request->all());
        }

        return redirect()->to('login')->withErrors('Your username or password is incorrect');
    }

    /* End of LDAP.FORUMSYS.COM */
}
