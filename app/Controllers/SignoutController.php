<?php

namespace App\Controllers;

class SignoutController extends BaseController {
    public function signout() {
        $session = session();

        $session->remove(["user_id", "email_address", "isLoggedIn"]);

        $session->setFlashdata("success", "You have been logged out successfully.");

        return redirect()->to(base_url("signin"));
    }

    // public function submit() {
    //     $email = $this->request->getPost("email");
    //     $password = $this->request->getPost("password");

        

    //     return redirect()->to("/home");
    // }
}