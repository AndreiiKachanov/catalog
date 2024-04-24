<?php

namespace App\Http\Controllers\Auth;

use App\Models\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }
        $authenticate = Auth::attempt(
            $request->only(['email', 'password']),
            $request->filled('remember')
        );
        if ($authenticate) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);
            $user = Auth::user();
            if ($user->isWait()) {
                Auth::logout();
                return back()->with('error', 'You need to confirm your account. Please check your email.');
            }
            return redirect()->intended(route('admin.home'));
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['email' => [trans('auth.failed')]]);
    }

    public function verify(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $this->validate($request, [
            'token' => 'required|string',
        ]);

        if (!$session = $request->session()->get('auth')) {
            throw new BadRequestHttpException('Missing token info.');
        }

        $user = User::findOrFail($session['id']);

        if ($request['token'] === $session['token']) {
            $request->session()->flush();
            $this->clearLoginAttempts($request);
            Auth::login($user, $session['remember']);
            return redirect()->intended(route('admin.home'));
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['token' => ['Invalid auth token.']]);
    }


    protected function username()
    {
        return 'email';
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('home');
    }
}
