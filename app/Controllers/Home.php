<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $session = session();

        if ($session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Home"];
        return view("brochure/home", $data);
        // return view('brochure/home');
    }

    public function locator()
    {
        $session = session();

        if ($session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Lots & Estates"];
        return view("brochure/locator", $data);
    }

    public function about()
    {
        $session = session();

        if ($session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "About"];
        return view("brochure/about", $data);
    }

    public function contact()
    {
        $session = session();

        if ($session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Contact"];
        return view("brochure/contact", $data);
    }

    // public function signup() {
    //     $data = ["pageTitle" => "Sign Up"];
    //     return view("signup", $data);
    // }

    // public function signin() {
    //     $data = ["pageTitle" => "Sign In"];
    //     return view("signin", $data);
    // }
}
