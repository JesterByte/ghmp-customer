<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\AdminNotificationModel;
use App\Models\BeneficiaryModel;
use App\Models\CustomerModel;
use App\Models\NotificationModel;

class SignupController extends BaseController
{
    public function index()
    {
        $session = session();

        if ($session->get("isLoggedIn")) {
            return redirect()->to(base_url("dashboard"));
        }

        $data = ["pageTitle" => "Sign Up"];
        return view("signup", $data);
    }

    public function submit()
    {
        $session = session();
        $customerModel = new CustomerModel();
        $beneficiaryModel = new BeneficiaryModel();

        $firstName = FormatterHelper::cleanName($this->request->getPost("first_name"));
        $middleName = !empty($this->request->getPost("middle_name")) ? FormatterHelper::cleanName($this->request->getPost("middle_name")) : "";
        $lastName = FormatterHelper::cleanName($this->request->getPost("last_name"));
        $suffixName = $this->request->getPost("suffix");

        $contactNumber = "+639{$this->request->getPost("contact_number")}";
        $email = FormatterHelper::cleanEmail($this->request->getPost("email"));

        $password = trim($this->request->getPost("password"));
        $confirmPassword = trim($this->request->getPost("confirm_password"));

        // Check if email already exists
        $existingCustomer = $customerModel->where("email_address", $email)->first();
        if ($existingCustomer) {
            $session->setFlashdata("flash_message", [
                "icon" => '<i class="bi bi-exclamation-circle text-danger"></i>',
                "title" => "Email Already in Use",
                "message" => "The email address you entered is already registered. Please use a different email or log in to your existing account."
            ]);
            return redirect()->to(base_url("signup"))->withInput();
        }

        if ($password !== $confirmPassword) {
            $session->setFlashdata("flash_message", [
                "icon" => '<i class="bi bi-exclamation-circle text-danger"></i>',
                "title" => "Password Mismatch",
                "message" => "The passwords you entered do not match. Please try again."
            ]);
            return redirect()->to(base_url("signup"))->withInput();
        }

        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        if ($this->request->getPost("beneficiary_relationship") === "Other") {
            $beneficiaryRelationship = FormatterHelper::cleanName($this->request->getPost("beneficiary_other_relationship"));
        } else {
            $beneficiaryRelationship = FormatterHelper::cleanName($this->request->getPost("beneficiary_relationship"));
        }

        $beneficiaryFirstName = FormatterHelper::cleanName($this->request->getPost("beneficiary_first_name"));
        $beneficiaryMiddleName = FormatterHelper::cleanName($this->request->getPost("beneficiary_middle_name"));
        $beneficiaryLastName = FormatterHelper::cleanName($this->request->getPost("beneficiary_last_name"));
        $beneficiarySuffixName = $this->request->getPost("beneficiary_suffix");
        $beneficiaryContactNumber = "+639{$this->request->getPost("beneficiary_contact_number")}";
        $beneficiaryEmail = FormatterHelper::cleanEmail($this->request->getPost("beneficiary_email"));

        $existingBeneficiary = $beneficiaryModel->where("email_address", $beneficiaryEmail)->first();
        if ($existingBeneficiary) {
            $session->setFlashdata("flash_message", [
                "icon" => '<i class="bi bi-exclamation-circle text-danger"></i>',
                "title" => "Beneficiary Email Already in Use",
                "message" => "The beneficiary email address is already registered. Please enter a different email."
            ]);
            return redirect()->to(base_url("signup"))->withInput();
        }

        if ($email === $beneficiaryEmail) {
            $session->setFlashdata("flash_message", [
                "icon" => '<i class="bi bi-exclamation-circle text-danger"></i>',
                "title" => "Duplicate Email Detected",
                "message" => "Your email address and the beneficiary's email address cannot be the same. Please enter different email addresses."
            ]);
            return redirect()->to(base_url("signup"))->withInput();
        }

        if ($contactNumber === $beneficiaryContactNumber) {
            $session->setFlashdata("flash_message", [
                "icon" => '<i class="bi bi-exclamation-circle text-danger"></i>',
                "title" => "Duplicate Contact Number",
                "message" => "Your contact number and the beneficiary's contact number cannot be the same. Please enter a different contact number."
            ]);
            return redirect()->to(base_url("signup"))->withInput();
        }

        $data = [
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "last_name" => $lastName,
            "suffix_name" => $suffixName,
            "contact_number" => $contactNumber,
            "email_address" => $email,
            "password_hashed" => $passwordHashed,
        ];

        $customerModel->insert($data);
        $customerId = $customerModel->getInsertID();

        $beneficiaryData = [
            "customer_id" => $customerId,
            "relationship_to_customer" => $beneficiaryRelationship,
            "first_name" => $beneficiaryFirstName,
            "middle_name" => $beneficiaryMiddleName,
            "last_name" => $beneficiaryLastName,
            "suffix_name" => $beneficiarySuffixName,
            "contact_number" => $beneficiaryContactNumber,
            "email_address" => $beneficiaryEmail
        ];

        $beneficiaryModel->insert($beneficiaryData);

        // Insert notification for the admin
        $adminNotificationModel = new AdminNotificationModel();
        $notificationMessage = "A new customer, {$firstName} {$lastName}, has registered.";
        $notificationData = [
            "admin_id" => 1,
            "message" => $notificationMessage,
            "link" => "customers",
            "is_read" => 0,
            "created_at" => date("Y-m-d H:i:s")
        ];
        $adminNotificationModel->insert($notificationData);

        $session->setFlashdata("flash_message", [
            "icon" => '<i class="bi bi-check-lg text-success"></i>',
            "title" => "Registration Successful",
            "message" => "Your account has been created successfully! You can now log in to your account."
        ]);

        return redirect()->to(base_url("signin"));
    }
}
