<?php

namespace App\Controllers;
use App\Models\UserModel;


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

        $userModel = new UserModel();
        $user = $userModel->where("email_address", $email)->first();

        if ($user) {
            if (password_verify($password, $user["password_hashed"]) || $password == $user["password_hashed"]) {
                $session->set([
                    "user_id" => $user["id"],
                    "email" => $user["email_address"],
                    "isLoggedIn" => true
                ]);
                return redirect()->to( base_url("admin/"));
            } else {
                $session->setFlashdata("error", "Invalid password.");
            }
        } else {
            $session->setFlashdata("error", "User not found.");
        }

        return redirect()->to( base_url("home"));
    }
}