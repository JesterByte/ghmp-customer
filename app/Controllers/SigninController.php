<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\CustomerModel;
use App\Models\BeneficiaryModel;


class SigninController extends BaseController
{
    public function index()
    {
        $data = ["pageTitle" => "Sign In"];
        return view("signin", $data);
    }

    public function submit()
    {
        $session = session();
        $email = FormatterHelper::cleanEmail($this->request->getPost("email"));
        $password = trim($this->request->getPost("password"));

        if (empty($email) || empty($password)) {
            $session->setFlashdata("error", "Email and Password are required.");
            return redirect()->to(base_url("signin"));
        }

        $customerModel = new CustomerModel();
        // $user = $customerModel->where("email_address", $email)->first();
        $user = $customerModel->where("email_address", $email)
            ->where("status", "Active")
            ->where("active_beneficiary IS NULL", null, false)
            ->first();

        $beneficiaryModel = new BeneficiaryModel();
        $beneficiary = $beneficiaryModel->where("email_address", $email)
            ->where("status", "Active")
            ->first();

        if ($user) {
            if (password_verify($password, $user["password_hashed"]) || $password == $user["password_hashed"]) {
                $middleName = !empty($user["middle_name"]) ? " " . $user["middle_name"] . " " : " ";
                $suffixName = !empty($user["suffix_name"]) ? ", " . $user["suffix_name"] : "";

                $userFullName = $user["first_name"] . $middleName . $user["last_name"] . $suffixName;

                $session->set([
                    "user_id" => $user["id"],
                    "user_full_name" => $userFullName,
                    "email" => $user["email_address"],
                    "isLoggedIn" => true,
                    "user_type" => "customer",
                ]);

                $session->setFlashdata("flash_message", [
                    "icon" => FormatterHelper::$checkIcon,
                    "title" => "Signin Successful",
                    "message" => "Welcome, $userFullName!",
                ]);

                return redirect()->to(base_url("dashboard"));
            } else {
                $session->setFlashdata("error", "Invalid email address or password. Please try again.");
                return redirect()->to(base_url("signin"));
            }
        } else if ($beneficiary) {
            if (password_verify($password, $beneficiary["password_hashed"]) || $password == $beneficiary["password_hashed"]) {
                $middleName = !empty($beneficiary["middle_name"]) ? " " . $beneficiary["middle_name"] . " " : " ";
                $suffixName = !empty($beneficiary["suffix_name"]) ? ", " . $beneficiary["suffix_name"] : "";

                $userFullName = $beneficiary["first_name"] . $middleName . $beneficiary["last_name"] . $suffixName;

                $customer = $customerModel->where("active_beneficiary", $beneficiary["id"])->first();

                $session->set([
                    "user_id" => $customer["id"],
                    "user_full_name" => $userFullName,
                    "email" => $beneficiary["email_address"],
                    "isLoggedIn" => true,
                    "user_type" => "beneficiary",
                    "beneficiary_id" => $beneficiary["id"],
                ]);

                $session->setFlashdata("flash_message", [
                    "icon" => FormatterHelper::$checkIcon,
                    "title" => "Signin Successful",
                    "message" => "Welcome, $userFullName!",
                ]);

                return redirect()->to(base_url("dashboard"));
            } else {
                $session->setFlashdata("error", "Invalid email address or password. Please try again.");
                return redirect()->to(base_url("signin"));
            }
        } else {
            $session->setFlashdata("error", "Invalid email address or password. Please try again.");
            return redirect()->to(base_url("signin"));
        }

        // return redirect()->to( base_url("home"));
    }
}
