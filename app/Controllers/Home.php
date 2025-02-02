<?php

namespace App\Controllers;

class Home extends BaseController {
    public function index(): string {
        $data = ["pageTitle" => "Home"];
        return view("brochure/home", $data);
        // return view('brochure/home');
    }

    public function locator() {
        $data = ["pageTitle" => "Lots & Estates"];
        return view("brochure/locator", $data);
    }

    public function about() {
        $data = ["pageTitle" => "About"];
        return view("brochure/about", $data);
    }

    public function contact() {
        $data = ["pageTitle" => "Contact"];
        return view("brochure/contact", $data);
    }
}
