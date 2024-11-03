<?php

namespace App\Controllers;

use App\Controllers\Authentication;
use App\Models\User;
use App\Libraries\JwtTokenHandler;
use App\Libraries\User\NotRegisteredUser;
use App\Libraries\User\User as UserUser;
use CodeIgniter\API\ResponseTrait;

class UserAccount extends BaseController
{
    use ResponseTrait;

    private JwtTokenHandler $tokenHandler;

    public function __construct()
    {
        $this->tokenHandler = new JwtTokenHandler;
    }

    private function emailDecryption($string)
    {
        // you may change these values to your own
        $secretKey = 'my_email_crypt_UkGZXahjBA3JfsaoLP5ZbyEk0XcYKzj3';
        $secretIV = 'my_email_secret_lku9VFKziq';
        $encryptMethod = "AES-256-CBC";
        $key = hash('sha256', $secretKey);
        $iv = substr(hash('sha256', $secretIV), 0, 16);
        return openssl_decrypt(base64_decode($string), $encryptMethod, $key, 0, $iv);
    }


    public function authorizeUser(): \CodeIgniter\HTTP\ResponseInterface
    {
        $providers = array(
            'saveGoBackUrl' => true,
            'cacheHandler' => $this->cacheHandler
        );

        $modalLogin = view('users/providers', $providers);
        $menuButtons = view('users/menu/notLoggedIn', $providers);

        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return $this->respond(["user" => new NotRegisteredUser($menuButtons, $modalLogin)]);
        }

        $token = $matches[1] ?? '';

        if (empty($token)) {
            return $this->respond(["user" => new NotRegisteredUser($menuButtons, $modalLogin)]);
        }

        $verifiedToken = $this->tokenHandler->verifyToken($token);

        if (empty($verifiedToken->username) || empty($verifiedToken->email) ) {
            return $this->respond(["user" => new NotRegisteredUser($menuButtons, $modalLogin)]);
        }

        $userData = $this->cacheHandler->getUser($verifiedToken->email,true);

		if (empty($userData) || empty($userData['is_active'])) {
			return $this->respond(["user" => new NotRegisteredUser($menuButtons, $modalLogin)]);
		}

        $user = new UserUser(
            $verifiedToken->username,
            $verifiedToken->email,
            $menuButtons,
            $userData["id"],
        );

        $data = [
            'user' => $user,
            'isMobile' => $this->isMobile,
        ];

        $menuButtons = view('users/menu/loggedIn', $data);
        $user->userMenuButtons = $menuButtons;
        return $this->respond($data);
    }

    public function signUpUser()
    {
        $emailToRegister = $this->request->getJSON()->email;
		$userDataByEmail = $this->cacheHandler->getUser($emailToRegister);

		$data['userId'] = $userDataByEmail['id'];

		$userProfile = [
			'email' => $emailToRegister,
			'username' => $userDataByEmail['username']
		];

		$token = $this->tokenHandler->generateToken($userProfile);

		$userProfile['step'] = 4;
		$userProfile['is_active'] = true;
		$userProfile['mobile_confirmed'] = true;

		$user = new User();
		$user->updateProfile(
			$userProfile,
			$userDataByEmail['id']
		);

		$data = [
			'userName' => $userDataByEmail['username'],
		];

		$user = new UserUser(
            $userData["id"],
			$userData['username'],
			$userData['email'],
		);

		$dataLoggedIn = [
			'user' => $user,
			'token' => $token
		];

		$menuButtons = view('users/menu/loggedIn', $dataLoggedIn);
		$user->userMenuButtons = $menuButtons;
		return $this->respond($dataLoggedIn);
    }


}