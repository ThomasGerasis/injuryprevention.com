<?php
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\User;

class AdminFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		if (!session()->get('loggedUser'))
		{
			session()->setFlashdata('error', 'Πρέπει να συνδεθείς!');
			return redirect()->to('admin/login');
		}
		if (!session()->get('loggedUser')['is_admin'])
		{
			session()->setFlashdata('error', 'Δεν έχεις πρόσβαση εδώ.');
			return redirect()->to('admin/dashboard');
		}
		$userModel = new User();
		$user = $userModel->find(session()->get('loggedUser')['id']);
		if(empty($user['is_active'])){
			session()->remove('loggedUser');
			session()->destroy();
			session()->setFlashdata('error', 'You have no access!');
			return redirect()->to('admin/login');
		}
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		
	}
}