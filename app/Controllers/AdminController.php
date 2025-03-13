<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $userFullName = $session->get("user_full_name");

        $session->setFlashdata("flash_message", [
            "icon" => '<i class="bi bi-check-lg text-success"></i>',
            "title" => "Signin Successful",
            "message" => "Welcome, $userFullName!",
        ]);

        $data = ["pageTitle" => "Dashboard"];
        return view("admin/dashboard", $data);
    }
}
