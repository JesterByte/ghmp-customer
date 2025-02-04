<?php

namespace App\Controllers;

class AdminController extends BaseController {
    public function index() {
        $data = ["pageTitle" => "Dashboard"];
        return view("admin/dashboard", $data);
    }
}