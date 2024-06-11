<?php
namespace App\Controllers;

class Contact extends BaseController
{
	public function sendEmail()
	{
        $emailToSend = $this->request->getPost('email');
        $name = $this->request->getPost('name');
        $surName = $this->request->getPost('surname');
        $message = $this->request->getPost('message');

        $email = \Config\Services::email();
        $email->setTo($emailToSend);

        $email->setSubject('Contact Email From Website');
        $message = view('templates/emails/emailForm', [
                'name'=>$name,
                'surname' => $surName,
                'message' => $message,
            ]
        );
        $email->setMessage($message);

        if (!$email->send()) {
            log_message('error', 'Email not sent to ' . $emailToSend);
        }
	}
}
