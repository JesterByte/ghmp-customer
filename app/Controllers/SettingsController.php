<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\CustomerModel;
use App\Models\BeneficiaryModel;


class SettingsController extends BaseController
{
    public function index()
    {
        $session = session();

        $customerModel = new CustomerModel();
        $customer = (array) $customerModel->find($session->get("user_id"));

        $data = [
            "pageTitle" => "Settings",

            "firstName" => $customer["first_name"],
            "middleName" => $customer["middle_name"],
            "lastName" => $customer["last_name"],
            "suffix" => $customer["suffix_name"],
            "contactNumber" => $customer["contact_number"],
            "email" => $customer["email_address"],

            "session" => $session
        ];
        return view("admin/settings", $data);
    }
}
