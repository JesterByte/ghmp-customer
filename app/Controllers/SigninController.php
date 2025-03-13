<?php

namespace App\Controllers;
use App\Models\CustomerModel;


class SigninController extends BaseController {
    public function index() {
        $data = ["pageTitle" => "Sign In"];
        return view("signin", $data);
    }

    public function submit() {
        $session = session();
        $email = $this->request->getPost("email");
        $password = $this->request->getPost("password");

        if (empty($email) || empty($password)) {
            $session->setFlashdata("error", "Email and Password are required.");
            return redirect()->to( base_url("signin"));
        }

        $customerModel = new CustomerModel();
        $user = $customerModel->where("email_address", $email)->first();

        if ($user) {
            if (password_verify($password, $user["password_hashed"]) || $password == $user["password_hashed"]) {
                $middleName = !empty($user["middle_name"]) ? " " . $user["middle_name"] . " " : " ";
                $suffixName = !empty($user["suffix_name"]) ? ", " . $user["suffix_name"] : "";

                $userFullName = $user["first_name"] . $middleName . $user["last_name"] . $suffixName;

                $session->set([
                    "user_id" => $user["id"],
                    "user_full_name" => $userFullName,
                    "email" => $user["email_address"],
                    "isLoggedIn" => true
                ]);
                return redirect()->to( base_url("dashboard"));
            } else {
                $session->setFlashdata("error", "Invalid email address or password. Please try again.");
                return redirect()->to(base_url("signin"));
            }
        } else {
            $session->setFlashdata("error", "Invalid email address or password. Please try again.");
            return redirect()->to(base_url("signin"));
        }

        // return redirect()->to( base_url("home"));
    }
}