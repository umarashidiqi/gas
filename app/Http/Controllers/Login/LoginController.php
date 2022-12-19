<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Device;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'changePassword']);
        $this->view = 'login.';
    }

    public function index()
    {
        return view($this->view . "index");
    }

    public function actionLogin(Request $request)
    {
        $response = array(
            'status' => 'failed', 'errors' => 'login', 'msg' => 'Username or password wrong.', 'item' => '',
        );

        $rules = array(
            'username' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        $login = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        $rulesCaptcha = ['captcha' => 'required|captcha'];
        $cek_captcha = Validator::make($request->all(), $rulesCaptcha);

        if ($cek_captcha->fails()) {
            $response = array(
                'status' => 'failed',
                'type' => 'captcha',
                'error' => $cek_captcha->fails(),
                'msg' => 'Captcha not match',
            );
        } else {
            if ($validator->fails()) {
                return response()->json(['status' => 'failed', 'errors' => $validator->errors(), 'msg' => 'Failed login, please check your username and password', 'item' => '']);
            } else {
                $user = User::where('username', $request->username)->first();
                if ($user) {
                    if ($user->status_user == 0) {
                        $response = array(
                            'status' => 'failed', 'errors' => 'login', 'msg' => 'User is not actived', 'item' => '',
                        );
                    } else {
                        if (Auth::attempt($login)) {

                            session([
                                'id'        => $user->id,
                                'name'      => $user->nama,
                                'username'  => $user->username,
                                'email'     => $user->email,
                                'no_wa'     => $user->no_wa,
                                'login'     => true,
                            ]);

                            return response()->json([
                                'status' => 'success',
                                'msg' => 'Hi, Welcome ' . $user->nama
                            ]);
                        } else {
                            $response = array(
                                'status' => 'failed', 'errors' => 'login', 'msg' => 'Username or password wrong', 'item' => '',
                            );
                        }
                    }
                } else {
                    $response = array(
                        'status' => 'failed',
                        'type' => 'User not found',
                        'nip' => '',
                        'msg' => 'User not found',
                    );
                }
            }
        }

        return response()->json($response);

    }

    public function recaptcha()
    {
        return captcha_src('flat');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        $request->session()->flush();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'currentpassword' => 'required',
            'newpassword' => 'required',
            'confirmpassword' => 'required|same:newpassword',
        ], [
            'currentpassword.required' => 'Current password is required',
            'newpassword.required' => 'New password is required',
            'confirmpassword.required' => 'Confirm password is required',
            'confirmpassword.same' => 'Confirm password not same with new password',
        ]);

        $user = User::find(Auth::user()->id);

        if (Hash::check($request->get('currentpassword'), $user->password)) {
            $user->password = Hash::make($request->get('newpassword'));
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Password changed successfully']);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Current password is wrong'], 501);
        }
    }

    public function clearCache(){
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return redirect('/');
    }
}
