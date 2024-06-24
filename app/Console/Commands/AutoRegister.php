<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class AutoRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically submit a registration form, verify OTP, and solve CAPTCHA';

    //private $client;

    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->client = new Client(['cookies' => true]);
    // }

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $username = 'testuser';
        $email = 'devpraveshyadav@gmail.com';
        $password = 'password123';
 
        $client = new Client();
        $jar = new CookieJar();
 
        // Step 1: Get the registration page
        $response = $client->request('GET', 'https://challenge.blackscale.media/register.php', [
            'cookies' => $jar
        ]);
 
        // Step 2: Submit the registration form
        $response = $client->request('POST', 'https://challenge.blackscale.media/register.php', [
            'cookies' => $jar,
            'form_params' => [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'confirm_password' => $password,
            ]
        ]);
 
        dd($response);
        echo "Registration form submitted.\n";
 
        // Step 3: Retrieve the verification code from email (dummy function)
        $verificationCode = $this->getVerificationCodeFromEmail($email);
        if (!$verificationCode) {
            echo "Failed to retrieve verification code from email.\n";
            return;
        }
 
        // Step 4: Verify the email
        $response = $client->request('POST', 'https://challenge.blackscale.media/verify.php', [
            'cookies' => $jar,
            'form_params' => [
                'code' => $verificationCode,
            ]
        ]);
 
        echo "Email verification submitted.\n";
 
        // Step 5: Handle ReCaptcha (dummy function)
        $captchaSolution = $this->solveCaptcha();
        $response = $client->request('POST', 'https://challenge.blackscale.media/complete.php', [
            'cookies' => $jar,
            'form_params' => [
                'g-recaptcha-response' => $captchaSolution,
            ]
        ]);
 
        echo "ReCaptcha completed.\n";
    }
 
    // Dummy function to retrieve verification code from email
    private function getVerificationCodeFromEmail($email)
    {
        // Implement email retrieval and code extraction logic here
        return '123456'; // Example code
    }
 
    // Dummy function to solve ReCaptcha
    private function solveCaptcha()
    {
        // Implement captcha solving logic here
        return 'captcha-solution'; // Example solution
    }


    // public function handle()
    // {
    //     $this->info('Starting the registration process...');

    //     // Step 1: Submit the registration form
    //     $this->submitRegistrationForm();

    //     // Step 2: Verify OTP
    //     //$this->verifyOtp();

    //     // Step 3: Solve CAPTCHA and complete verification
    //     //$this->verifyCaptcha();


    // }

    // private function submitRegistrationForm()
    // {
    //     // $response = $this->client->post('https://challenge.blackscale.media/register.php', [
    //     //     'form_params' => [
    //     //         'fullname' => 'testuser',
    //     //         'email' => 'devpraveshyadav@gmail.com',
    //     //         'password' => 'secret123'
    //     //     ]
    //     // ]);

    //     $response = $this->client->post('http://127.0.0.1:8000/register', [
    //         'form_params' => [
    //             'name' => 'Automated User',
    //             'email' => 'devpraveshyadav@gmail.com',
    //             'password' => bcrypt('12345678'),
    //             'verification_code' => random_int(10000000, 99999999),
    //         ]
    //     ]);
        

    //     if ($response->getStatusCode() == 200) {
    //         $this->info('Registration form submitted successfully.');
    //     } else {
    //         $this->error('Failed to submit the registration form.');
    //         exit(1);
    //     }
    // }

    // private function verifyOtp()
    // {
    //     // Retrieve OTP from email or other source
    //     $otp = $this->getOtpFromEmail();

    //     $response = $this->client->post('https://challenge.blackscale.media/verify.php', [
    //         'form_params' => [
    //             'code' => $otp,
    //         ]
    //     ]);

    //     if ($response->getStatusCode() == 200) {
    //         $this->info('OTP verified successfully.');
    //     } else {
    //         $this->error('Failed to verify OTP.');
    //         exit(1);
    //     }
    // }

    // private function verifyCaptcha()
    // {
    //     // Fetch the page with the CAPTCHA
    //     $response = $this->client->get('https://challenge.blackscale.media/captcha.php');
    //     $crawler = new Crawler($response->getBody()->getContents());

    //     // Extract CAPTCHA image source
    //     $captchaImageSrc = $crawler->filter('#captcha-image')->attr('src');

    //     // Solve CAPTCHA (this part should be implemented with a CAPTCHA solving service or manually)
    //     $captchaSolution = $this->solveCaptcha($captchaImageSrc);

    //     // Submit CAPTCHA solution
    //     $response = $this->client->post('https://challenge.blackscale.media/captcha.php', [
    //         'form_params' => [
    //             'captcha' => $captchaSolution,
    //         ]
    //     ]);

    //     if ($response->getStatusCode() == 200) {
    //         $this->info('CAPTCHA verified successfully.');
    //     } else {
    //         $this->error('Failed to verify CAPTCHA.');
    //         exit(1);
    //     }
    // }

    // private function getOtpFromEmail()
    // {
    //     // Logic to retrieve OTP from email
    //     return '123456'; // Example OTP
    // }

    // private function solveCaptcha($captchaImageSrc)
    // {
    //     // Logic to solve CAPTCHA
    //     // This should ideally be done using a CAPTCHA solving service
    //     return 'captcha_solution'; // Example solution
    // }
}
