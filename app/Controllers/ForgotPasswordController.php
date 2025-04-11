<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class ForgotPasswordController extends BaseController
{
    public function index()
    {
        $data = [
            "pageTitle" => "Forgot Password Request"
        ];

        return view('forgot_password_request', $data);
    }

    public function sendOTP()
    {
        $email = $this->request->getPost('email');
        $customerModel = new CustomerModel();
        $user = $customerModel->where('email_address', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email not found.');
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Save OTP to database
        $customerModel->update($user['id'], [
            'otp' => $otp,
            'otp_expires' => $expiry
        ]);

        // Send email with OTP
        $email = \Config\Services::email();
        $email->setFrom($this->adminEmail, 'GHMP Support');
        $email->setTo($user['email_address']);
        $email->setSubject('Password Reset OTP');
        
        $message = view('emails/reset_password_otp', [
            'otp' => $otp,
            'expiry' => $expiry
        ]);
        
        $email->setMessage($message);

        if ($email->send()) {
            session()->set('reset_email', $user['email_address']);
            return redirect()->to(base_url('verify-otp'))
                           ->with('success', 'OTP has been sent to your email.');
        }

        return redirect()->back()
                       ->with('error', 'Failed to send OTP. Please try again.');
    }

    public function verifyOTP()
    {
        if (!session()->has('reset_email')) {
            return redirect()->to('forgot-password/request')
                           ->with('error', 'Please request an OTP first.');
        }

        $data = [
            "pageTitle" => "Verify OTP"
        ];

        return view('verify_otp', $data);
    }

    public function validateOTP()
    {
        $otp = $this->request->getPost('otp');
        $email = session()->get('reset_email');

        if (!$email) {
            return redirect()->to('forgot-password/request')
                           ->with('error', 'Session expired. Please try again.');
        }

        $customerModel = new CustomerModel();
        $user = $customerModel->where('email_address', $email)
                            ->where('otp', $otp)
                            ->where('otp_expires >', date('Y-m-d H:i:s'))
                            ->first();

        if (!$user) {
            return redirect()->back()
                           ->with('error', 'Invalid or expired OTP code.');
        }

        // Clear OTP after successful verification
        $customerModel->update($user['id'], [
            'otp' => null,
            'otp_expires' => null
        ]);

        // Store reset token in session
        session()->set('reset_token', md5(uniqid()));
        session()->set('reset_user_id', $user['id']);

        return redirect()->to('reset-password')
                       ->with('success', 'OTP verified successfully.');
    }

    public function resendOTP()
    {
        $email = session()->get('reset_email');
        
        if (!$email) {
            return redirect()->to('forgot-password/request')
                           ->with('error', 'Session expired. Please try again.');
        }

        $customerModel = new CustomerModel();
        $user = $customerModel->where('email_address', $email)->first();

        if (!$user) {
            return redirect()->to('forgot-password/request')
                           ->with('error', 'Email not found.');
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        // Update OTP in database
        $customerModel->update($user['id'], [
            'otp' => $otp,
            'otp_expires' => $expiry
        ]);

        // Send new OTP email
        $email = \Config\Services::email();
        $email->setFrom($this->adminEmail, 'GHMP Support');
        $email->setTo($user['email_address']);
        $email->setSubject('New Password Reset OTP');
        
        $message = view('emails/reset_password_otp', [
            'otp' => $otp,
            'expiry' => $expiry
        ]);
        
        $email->setMessage($message);

        if ($email->send()) {
            return redirect()->back()
                           ->with('success', 'New OTP has been sent to your email.');
        }

        return redirect()->back()
                       ->with('error', 'Failed to send new OTP. Please try again.');
    }

    public function resetPassword()
    {
        if (!session()->has('reset_token') || !session()->has('reset_user_id')) {
            return redirect()->to('forgot-password/request')
                           ->with('error', 'Invalid reset attempt.');
        }

        $data = [
            "pageTitle" => "Reset Password"
        ];

        return view('reset_password', $data);
    }

    public function updatePassword()
    {
        if (!session()->has('reset_token') || !session()->has('reset_user_id')) {
            return redirect()->to('forgot-password/request')
                           ->with('error', 'Invalid reset attempt.');
        }

        $rules = [
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->with('error', $this->validator->listErrors());
        }

        $userId = session()->get('reset_user_id');
        $password = $this->request->getPost('password');

        $customerModel = new CustomerModel();
        $customerModel->update($userId, [
            'password_hashed' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        // Clear all reset-related session data
        session()->remove(['reset_email', 'reset_token', 'reset_user_id']);

        return redirect()->to(base_url('signin'))
                       ->with('success', 'Password has been reset successfully.');
    }
}
