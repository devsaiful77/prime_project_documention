<?php

namespace App\Http\Middleware;

use App\CIUserSession;
use App\CustomerInterfaceToken;
use App\Setting;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RecordLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Setting::select('setting_id', 'ci_session_time')->first();
        $userToken = "";
        if ($request->get('CIToken')){
            $userToken = $request->get('CIToken');
        }elseif ($request->get('ci_token')){
            $userToken = $request->get('ci_token');
        }

        //ci active session
        activeSession();

        if ($userToken){
           /* try {
                $sessionTokenDecrypt = decrypt($userToken);
            } catch(Throwable $e) {
                return abort(403, 'invalid access token.');
            }*/

            $user = CustomerInterfaceToken::where('token', $userToken)->where('is_verify', 1)->first();
            if ($user){
                if ($user->expires_at < date('Y-m-d H:i:s', strtotime(Carbon::now()))){
                    $data = [
                        'back_to_home' => $user->callback_url,
                        'msg' => 'Your session has been expired',
                    ];
                    $view = View::make('errors.440', $data);
                    if ($request->ajax()){
                        return response()->json(['otpMessage' => 'Your session has been expired']);
                    }else {
                        return new Response($view);
                    }
                }
                $user->last_activity_time = date('d-m-Y H:i:s');
                $user->expires_at = date('Y-m-d H:i:s', strtotime(Carbon::now()->addMinutes($setting->ci_session_time ?? 10)));
                $user->update();

                //ci active session
                $ciSession = CIUserSession::where('token', $userToken)->first(['id','time']);
                if ($ciSession){
                    $ciSession->time = date('Y-m-d H:i:s', strtotime(Carbon::now()));
                    $ciSession->update();
                }
            }
        }
        return $next($request);
    }
}
