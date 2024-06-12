<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RegisterMail;
use Faker\Generator as Faker;
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
        Mail::to($user->email)->send(new RegisterMail($user));
        $this->info("User registered and verification email sent to $email");

    }
}
