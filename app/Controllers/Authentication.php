<?php

namespace App\Controllers;

use App\Libraries\JwtTokenHandler;
use CodeIgniter\API\ResponseTrait;
use App\Models\User;
use Google_Client;

class Authentication extends BaseController
{
    use ResponseTrait;

    public function attemptGoogleAuth()
    {
        // This is to avoid Firebase\JWT\BeforeValidException. See https://github.com/googleapis/google-api-php-client/issues/1630
        \Firebase\JWT\JWT::$leeway = 5;

        $previousUrlTemplate = view('users/authGoBack', []);

        $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);
        $payload = $client->verifyIdToken($this->request->getPost('credential'));

        if (!$payload) {
            log_message('error', 'Provider google missing email from user data.');
            log_message('error', 'Profile data ' . print_r($payload, true));
            return $previousUrlTemplate;
        }

        $userDataByEmail = $this->cacheHandler->getUser($payload['email']);

        if ($userDataByEmail && $userDataByEmail['is_active'] === '1') {
            $token = (new JwtTokenHandler())->generateToken($userDataByEmail);
            $data['personalToken'] = $token;
            return view('users/setToken', $data);
        }

        if (!$userDataByEmail) {
            $userHandler = new User();
            $userHandler->registerUser([
                'firstname' => $payload['given_name'],
                'lastname'  => $payload['family_name'],
                'email'     => $payload['email'],
                'username'  => $payload['email'],
                'provider'  => 'Google',
                'ip'        => $this->request->getIPAddress(),
            ]);
        }

        return redirect()->to('/');
    }

    public function logout(): string
    {
        return view('users/removeToken');
    }

    private function emailEncryption($string)
    {
        // you may change these values to your own
        $secret_key = 'my_email_crypt_UkGZXahjBA3JfsaoLP5ZbyEk0XcYKzj3';
        $secret_iv = 'my_email_secret_lku9VFKziq';
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $string = strtolower($string);
        return base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    }
}
