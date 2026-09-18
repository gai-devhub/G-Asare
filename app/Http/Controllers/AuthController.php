<?php

namespace App\Http\Controllers;

use App\Mail\AccessCodeMail;
use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        return view('auth.login', [
            'email' => $request->session()->get('login_email'),
            'codeSent' => $request->session()->get('code_sent', false),
        ]);
    }

    public function requestAccessCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email address.'])->withInput();
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        LoginCode::where('email', $request->email)->delete();

        LoginCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        $emailSent = true;
        try {
            Mail::to($request->email)->send(new AccessCodeMail($code, 5));
        } catch (TransportExceptionInterface $e) {
            $emailSent = false;
            Log::info('SMTP failed. Access code for ' . $request->email . ': ' . $code . ' (expires in 5 min)');
        }

        $message = $emailSent
            ? 'Access code sent! Check your email.'
            : 'Email delivery failed. Check storage/logs/laravel.log for your code (search for your email).';

        return redirect()->route('login')->with([
            'login_email' => $request->email,
            'code_sent' => true,
            'message' => $message,
            'email_delivery_failed' => !$emailSent,
        ]);
    }

    public function verifyAndLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $loginCode = LoginCode::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$loginCode) {
            return redirect()->route('login')->withErrors(['code' => 'Invalid or expired access code.'])->with([
                'login_email' => $request->email,
                'code_sent' => true,
            ]);
        }

        if ($loginCode->isExpired()) {
            $loginCode->delete();
            return redirect()->route('login')->withErrors(['code' => 'This access code has expired. Please request a new one.'])->with([
                'login_email' => $request->email,
                'code_sent' => true,
            ]);
        }

        $loginCode->delete();

        $user = User::where('email', $request->email)->firstOrFail();
        Auth::login($user, $request->boolean('remember'));

        $request->session()->forget(['login_email', 'code_sent']);

        return redirect()->intended(route('login.passcode'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|in:gasare5326@gmail.com,gai.dev.official@gmail.com',
            'password' => ['required', 'confirmed', Password::defaults()],
            'passcode' => 'required|string|size:6|regex:/^\d{6}$/',
        ], [
            'passcode.regex' => 'Passcode must be 6 digits (numbers only).',
            'email.in' => 'Registration is disabled for unauthorized users.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'passcode' => Hash::make($request->passcode),
        ]);

        return redirect()->route('login')->with('message', 'Account created! You can now log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showPasscodeForm(Request $request)
    {
        if ($request->session()->get('passcode_verified', false)) {
            return redirect()->route('admin.overview');
        }

        return view('auth.passcode');
    }

    public function verifyPasscode(Request $request)
    {
        $request->validate([
            'passcode' => 'required|string|size:6|regex:/^\d{6}$/',
        ]);

        if (Hash::check($request->passcode, Auth::user()->passcode)) {
            $request->session()->put('passcode_verified', true);
            return redirect()->intended(route('admin.overview'));
        }

        return back()->withErrors(['passcode' => 'Incorrect passcode.']);
    }
}
