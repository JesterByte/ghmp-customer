<?php

namespace App\Controllers;

class SignupController extends BaseController {
    public function index() {
        $data = ["pageTitle" => "Sign Up"];
        return view("signup", $data);
    }

    // public function submit() {
    //     $email = $this->request->getPost("email");
    //     $password = $this->request->getPost("password");

        

    //     return redirect()->to("/home");
    // }
}