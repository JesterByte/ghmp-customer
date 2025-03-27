<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\AdminNotificationModel;
use App\Models\CustomerModel;
use App\Models\BeneficiaryModel;
use App\Models\NotificationModel;


class SettingsController extends BaseController
{
    protected $customerModel;
    protected $beneficiaryModel;
    protected $adminNotificationModel;
    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->beneficiaryModel = new BeneficiaryModel();
        $this->adminNotificationModel = new AdminNotificationModel();
    }

    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $beneficiariesCount = $this->beneficiaryModel->countBeneficiariesByCustomerId($session->get("user_id"));

        switch ($session->get("user_type")) {
            case "customer":
                $user = (array) $this->customerModel->find($session->get("user_id"));

                break;
            case "beneficiary":
                $user = (array) $this->beneficiaryModel->find($session->get("beneficiary_id"));
                break;
        }

        $beneficiaries = $this->beneficiaryModel->where("customer_id", $session->get("user_id"))
            ->where("status", "Inactive")
            ->findAll();

        $data = [
            "pageTitle" => "Settings",

            "firstName" => $user["first_name"],
            "middleName" => $user["middle_name"],
            "lastName" => $user["last_name"],
            "suffix" => $user["suffix_name"],
            "contactNumber" => $user["contact_number"],
            "email" => $user["email_address"],

            "beneficiaries" => $beneficiaries,
            "beneficiariesCount" => $beneficiariesCount,

            "session" => $session
        ];
        return view("admin/settings", $data);
    }

    public function updateProfile()
    {
        $session = session();

        switch ($session->get("user_type")) {
            case "customer":
                $this->customerModel->save([
                    "id" => $session->get("user_id"),
                    "first_name" => $this->request->getPost("first_name"),
                    "middle_name" => $this->request->getPost("middle_name"),
                    "last_name" => $this->request->getPost("last_name"),
                    "suffix_name" => $this->request->getPost("suffix"),
                    "contact_number" => $this->request->getPost("contact_number"),
                    "email_address" => $this->request->getPost("email")
                ]);
                break;
            case "beneficiary":
                $this->beneficiaryModel->save([
                    "id" => $session->get("beneficiary_id"),
                    "first_name" => $this->request->getPost("first_name"),
                    "middle_name" => $this->request->getPost("middle_name"),
                    "last_name" => $this->request->getPost("last_name"),
                    "suffix_name" => $this->request->getPost("suffix"),
                    "contact_number" => $this->request->getPost("contact_number"),
                    "email_address" => $this->request->getPost("email")
                ]);
                break;
        }

        $notificationMessage = "{$session->get("user_full_name")} has updated their profile.";
        $notificationData = [
            'admin_id' => null,  // Null for general admin notification
            'message' => $notificationMessage,
            'link' => 'customers',  // Link to the settings page
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->adminNotificationModel->save($notificationData);

        $session->setFlashdata("flash_message", [
            "icon" => FormatterHelper::$checkIcon,
            "title" => "Profile Updated",
            "message" => "Profile updated successfully."
        ]);

        return redirect()->to(base_url("settings"));
    }

    public function changePassword()
    {
        $session = session();

        switch ($session->get("user_type")) {
            case "customer":
                $user = (array) $this->customerModel->find($session->get("user_id"));
                break;
            case "beneficiary":
                $user = (array) $this->beneficiaryModel->find($session->get("beneficiary_id"));
                break;
        }

        if (!password_verify($this->request->getPost("current_password"), $user["password_hashed"])) {
            $session->setFlashdata("flash_message", [
                "icon" => FormatterHelper::$xIcon,
                "title" => "Change Password Failed",
                "message" => "Current password is incorrect."
            ]);

            return redirect()->to(base_url("settings"));
        }

        // if ($this->request->getPost("current_password") != $customer["password_hashed"]) {
        //     $session->setFlashdata("flash_message", [
        //         "icon" => FormatterHelper::$xIcon,
        //         "title" => "Change Password Failed",
        //         "message" => "Current password is incorrect."
        //     ]);

        //     return redirect()->to(base_url("settings"));
        // }

        if ($this->request->getPost("new_password") != $this->request->getPost("confirm_password")) {
            $session->setFlashdata("flash_message", [
                "icon" => FormatterHelper::$xIcon,
                "title" => "Change Password Failed",
                "message" => "Passwords do not match."
            ]);

            return redirect()->to(base_url("settings"));
        }

        switch ($session->get("user_type")) {
            case "customer":
                $this->customerModel->save([
                    "id" => $session->get("user_id"),
                    "password_hashed" => password_hash($this->request->getPost("new_password"), PASSWORD_DEFAULT)
                ]);
                break;
            case "beneficiary":
                $this->beneficiaryModel->save([
                    "id" => $session->get("beneficiary_id"),
                    "password_hashed" => password_hash($this->request->getPost("new_password"), PASSWORD_DEFAULT)
                ]);
                break;
        }

        $notificationMessage = "{$session->get("user_full_name")} has changed their password.";
        $notificationData = [
            'admin_id' => null,  // Null for general admin notification
            'message' => $notificationMessage,
            'link' => 'customers',  // Link to the settings page
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->adminNotificationModel->save($notificationData);

        $session->setFlashdata("flash_message", [
            "icon" => FormatterHelper::$checkIcon,
            "title" => "Password Changed",
            "message" => "Password changed successfully."
        ]);

        return redirect()->to(base_url("settings"));
    }

    public function removeBeneficiary()
    {
        $session = session();

        $this->beneficiaryModel->delete($this->request->getPost("beneficiary_id"));

        $session->setFlashdata("flash_message", [
            "icon" => FormatterHelper::$checkIcon,
            "title" => "Beneficiary Removed",
            "message" => "Beneficiary removed successfully."
        ]);

        $notificationMessage = "{$session->get("user_full_name")} has removed a beneficiary.";
        $notificationData = [
            'admin_id' => null,  // Null for general admin notification
            'message' => $notificationMessage,
            'link' => 'customers',  // Link to the settings page
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->adminNotificationModel->save($notificationData);

        return redirect()->to(base_url("settings"));
    }

    public function addBeneficiary()
    {
        $session = session();

        $this->beneficiaryModel->save([
            "customer_id" => $session->get("user_id"),
            "first_name" => $this->request->getPost("beneficiary_first_name"),
            "middle_name" => $this->request->getPost("beneficiary_middle_name"),
            "last_name" => $this->request->getPost("beneficiary_last_name"),
            "suffix_name" => $this->request->getPost("beneficiary_suffix"),
            "contact_number" => $this->request->getPost("beneficiary_contact_number"),
            "email_address" => $this->request->getPost("beneficiary_email")
        ]);

        $session->setFlashdata("flash_message", [
            "icon" => FormatterHelper::$checkIcon,
            "title" => "Beneficiary Added",
            "message" => "Beneficiary added successfully."
        ]);

        $notificationMessage = "{$session->get("user_full_name")} has added a new beneficiary.";
        $notificationData = [
            'admin_id' => null,  // Null for general admin notification
            'message' => $notificationMessage,
            'link' => 'customers',  // Link to the settings page
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->adminNotificationModel->save($notificationData);

        return redirect()->to(base_url("settings"));
    }
}
