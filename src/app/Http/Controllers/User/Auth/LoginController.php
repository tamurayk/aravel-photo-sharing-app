<?php

namespace App\Http\Controllers\User\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Eloquents\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override to \Illuminate\Foundation\Auth\AuthenticatesUsers
     *
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
    }

    /**
     * Override to \Illuminate\Foundation\Auth\AuthenticatesUsers
     *
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    /**
     * Override to \Illuminate\Foundation\Auth\AuthenticatesUsers
     *
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard('user')->logout();

        return $this->loggedOut($request);
    }

    /**
     * Override to \Illuminate\Foundation\Auth\AuthenticatesUsers
     *
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function loggedOut(Request $request)
    {
        return redirect(route('login'));
    }

    /**
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)
            ->redirect();
    }

    /**
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback(string $provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->user();
            \Log::debug('$providerUser->getId() : ' . $providerUser->getId());
            \Log::debug('$providerUser->getNickname() : ' . $providerUser->getNickname());
            \Log::debug('$providerUser->getName() : ' . $providerUser->getName());
            \Log::debug('$providerUser->getEmail() : ' . $providerUser->getEmail());
            \Log::debug('$providerUser->getAvatar() : ' . $providerUser->getAvatar());
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->route('home')->with('oauth_error', '予期せぬエラーが発生しました');
        }

        $email = $providerUser->getEmail();
        if (!$email) {
            return redirect()->route('home')->with('oauth_error', 'メールアドレスが取得できませんでした');
        }

        $name = $providerUser->getName() ? $providerUser->getName() : $providerUser->getNickname();
        if (!$name) {
            return redirect()->route('home')->with('oauth_error', '名前が取得できませんでした');
        }

        Auth::login(User::firstOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => $name,
            ]
        ));

        return redirect($this->redirectTo);
    }
}
