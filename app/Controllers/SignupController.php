<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class SignupController extends BaseController {
    public function index() {
        $data = ["pageTitle" => "Sign Up"];
        return view("signup", $data);
    }

    public function submit() {

        $session = session();
        $firstName = $this->request->getPost("first_name");
        $middleName = $this->request->getPost("middle_name");
        $lastName = $this->request->getPost("last_name");
        $suffixName = $this->request->getPost("suffix");

        $email = $this->request->getPost("email");

        $password = $this->request->getPost("password");
        $confirmPassword = $this->request->getPost("confirm_password");

        if ($password !== $confirmPassword) {
            return redirect()->to(base_url("signup"));
        }

        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $beneficiaryEmail1 = $this->request->getPost("beneficiary_email_1");
        $beneficiaryEmail2 = $this->request->getPost("beneficiary_email_2");
        $beneficiaryEmail3 = $this->request->getPost("beneficiary_email_3");

        $data = [
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "last_name" => $lastName,
            "suffix_name" => $suffixName,
            "email_address" => $email,
            "password_hashed" => $passwordHashed,
        ];

        $customerModel = new CustomerModel();
        $customerModel->insert($data);
    }
}