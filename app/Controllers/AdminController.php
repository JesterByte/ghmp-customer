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

        $data = [
            "pageTitle" => "Dashboard",
            "session" => $session
        ];
        return view("admin/dashboard", $data);
    }
}
