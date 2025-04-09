<?php

namespace App\Controllers;

use App\Models\AdminModel;

class AdminController extends BaseController
{
    protected $adminModel;

    public function __construct() {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $userFullName = $session->get("user_full_name");

        $ownedPropertiesCount = $this->adminModel->getOwnerPropertiesCount($session->get("user_id"));
        $scheduledMemorialServices = $this->adminModel->getScheduledMemorialServices($session->get("user_id"));

        $data = [
            "pageTitle" => "Dashboard",
            "session" => $session,
            "ownedPropertiesCount" => $ownedPropertiesCount,
            "scheduledMemorialServices" => $scheduledMemorialServices
        ];
        return view("admin/dashboard", $data);
    }
}
