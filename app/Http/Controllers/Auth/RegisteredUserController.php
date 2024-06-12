<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\IpUtils;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_code' => random_int(10000000, 99999999),
        ]);

        //$user->sendEmailVerificationNotification();

        event(new Registered($user));

        Auth::login($user);

        Mail::to($user->email)->send(new RegisterMail($user));

        return redirect(route('auth.code_verify'));

        //return redirect(route('dashboard', absolute: false));
    }

    public function verifyCode(Request $request)
    {

        $user = User::find($request->user_id);
        if($user){
            if($user->verification_code == $request->verification_code){
                $user->email_verified_at = now();
                $user->save();
            }else{
                return back()->with('error','Verification code does not matched!');
            }
        }else{
            return redirect(route('register'));
        }

        return view('auth.verifyCaptcha', compact('user'));
    }

    public function verifyCaptcha(Request $request)
    {
       
        $recaptcha_response = $request->input('g-recaptcha-response');

        if (is_null($recaptcha_response)) {
            return redirect()->back()->with('status', 'Please Complete the Recaptcha to proceed');
        }

        $url = "https://www.google.com/recaptcha/api/siteverify";

        $body = [
            'secret' => config('services.recaptcha.secret'),
            'response' => $recaptcha_response,
            'remoteip' => IpUtils::anonymize($request->ip()) //anonymize the ip to be GDPR compliant. Otherwise just pass the default ip address
        ];

        $response = Http::asForm()->post($url, $body);

        $result = json_decode($response);

        if ($response->successful() && $result->success == true) {
            //$request->authenticate();

            $request->session()->regenerate();

            return redirect(route('dashboard'));
        } else {
            return redirect()->back()->with('status', 'Please Complete the Recaptcha Again to proceed');
        }
    }

}
