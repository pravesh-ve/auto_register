<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RegisterMail;
use Faker\Generator as Faker;
use Illuminate\Cache\LuaScripts;
use Illuminate\Container\Container;

class RegisterUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:register {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register User';

    /**
     * Execute the console command.
     */
    public function handle(Faker $faker)
    {
        // Start user registration
        $this->info("User registration start...");

        $email = $this->argument('email');

        if(!$email){
            $email = preg_replace('/@example\..*/', '@domain.com', $faker->unique()->safeEmail);
        }

        $password = Str::random(10);

        $user = User::create([
            'name' => 'Automated User',
            'email' => $email,
            'password' => bcrypt($password),
            'verification_code' => random_int(10000000, 99999999),
        ]);

        // Start sending OTP om email
        Mail::to($user->email)->send(new RegisterMail($user));
        $this->info("User registered and verification code sent to $email");

        // Start email verification
        $this->info("Email verification start.. ");
        $verificationCode = $user->verification_code;
        
        if($verificationCode == $user->verification_code)
        {
            $user->email_verified_at = now();
            $user->save();
            $this->info("Email verified successfully. ");
        }
        else
        {

            $this->info("Email verification failed. ");
        }


    }


}
