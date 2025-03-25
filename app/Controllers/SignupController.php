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
        $data = ["pageTitle" => "Sign Up"];
        return view("signup", $data);
    }

    public function submit()
    {

        $session = session();
        $firstName = FormatterHelper::cleanName($this->request->getPost("first_name"));
        $middleName = !empty($this->request->getPost("middle_name")) ? FormatterHelper::cleanName($this->request->getPost("middle_name")) : "";
        $lastName = FormatterHelper::cleanName($this->request->getPost("last_name"));
        $suffixName = $this->request->getPost("suffix");

        $contactNumber = $this->request->getPost("contact_number");
        $email = FormatterHelper::cleanEmail($this->request->getPost("email"));

        $password = trim($this->request->getPost("password"));
        $confirmPassword = trim($this->request->getPost("confirm_password"));

        if ($password !== $confirmPassword) {
            return redirect()->to(base_url("signup"));
        }

        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $beneficiaryRelationship = FormatterHelper::cleanName($this->request->getPost("beneficiary_relationship"));
        $beneficiaryFirstName = FormatterHelper::cleanName($this->request->getPost("beneficiary_first_name"));
        $beneficiaryMiddleName = FormatterHelper::cleanName($this->request->getPost("beneficiary_middle_name"));
        $beneficiaryLastName = FormatterHelper::cleanName($this->request->getPost("beneficiary_last_name"));
        $beneficiarySuffixName = $this->request->getPost("beneficiary_suffix");
        $beneficiaryContactNumber = $this->request->getPost("beneficiary_contact_number");
        $beneficiaryEmail = FormatterHelper::cleanEmail($this->request->getPost("beneficiary_email"));

        $data = [
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "last_name" => $lastName,
            "suffix_name" => $suffixName,
            "contact_number" => $contactNumber,
            "email_address" => $email,
            "password_hashed" => $passwordHashed,
        ];

        $customerModel = new CustomerModel();
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

        $beneficiaryModel = new BeneficiaryModel();
        $beneficiaryModel->insert($beneficiaryData);

        // Insert notification for the customer
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
            "title" => "Signup Successful",
            "message" => "Your account has been created successfully!"
        ]);

        return redirect()->to(base_url("signin"));
    }
}
