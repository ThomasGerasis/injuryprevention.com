<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use Google_Client;
use App\Models\User;

class AccessControl extends BaseController
{

	function login()
	{
		$data = array();
		return view('admin/login', $data);
	}

	public function attemptGoogleAuth()
	{
		// This is to avoid Firebase\JWT\BeforeValidException. See https://github.com/googleapis/google-api-php-client/issues/1630
		\Firebase\JWT\JWT::$leeway = 5;

		$client = new Google_CLient(['client_id' => GOOGLE_CLIENT_ID]);
		$payload = $client->verifyIdToken($this->request->getPost('credential'));

		if (!$payload) {
			return redirect()->back()->withInput()->with('error', 'invalid id token');
		}

		$userModel = new User();
		$user = $userModel->googleLogin($payload['email'], $this->request->getIPAddress());

		if ($user === null) {
			return redirect()->back()->withInput()->with('error', 'User with given email does not exist in Database');
		}

		$this->session->set('loggedUser', $user);
		return redirect()->to('admin/dashboard');
	}

	function logout()
	{
		$this->session->remove('loggedUser');
		$this->session->destroy();
		$this->session->setFlashdata('success', 'Ελπίζουμε να μας ξανάρθεις.');
		return redirect()->to('admin/login');
	}
}
