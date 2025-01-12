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

        // Verify reCAPTCHA response with Google
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
        $recaptchaSecret = CAPTCHA_SECRET_KEY;
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';

        $data = [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $recaptchaUrl); // reCAPTCHA verification URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($ch, CURLOPT_POST, true); // Use POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // POST data

        // Execute the cURL request and capture the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if(curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $responseKeys = json_decode($response, true);

        if (!$responseKeys['success'] || $responseKeys['score'] < 0.5) {
            log_message('error', 'reCAPTCHA verification failed');
            // Set flash message for failed reCAPTCHA
            session()->setFlashdata('message', 'reCAPTCHA verification failed. Please try again.');
            return redirect()->to(base_url('/contact-us'));
        }

        $email = \Config\Services::email();
        $email->setTo($emailToSend);
        $email->setSubject('Contact Email From Website');
        $message = view('templates/emails/emailForm', [
                'name'=>$name,
                'email' => $emailToSend,
                'surname' => $surName,
                'message' => $message,
            ]
        );
        $email->setMessage($message);

        if (!$email->send()) {
            log_message('error', 'Email not sent to ' . $emailToSend);
            // Set flash message for email failure
            session()->setFlashdata('message', 'Failed to send email.');
            return redirect()->to(base_url('/contact-us'));  // Fixed redirect
        }
    
        // Set flash message for success
        session()->setFlashdata('message', 'Email sent successfully!');
        return redirect()->to(base_url('/contact-us'));  // Fixed redirect
	}
}
