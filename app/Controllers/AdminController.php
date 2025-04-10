<?php

namespace App\Controllers;

use App\Models\AdminModel;

class AdminController extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $userFullName = $session->get("user_full_name");

        $ownedPropertiesCount = $this->adminModel->getOwnerAssetsCount($session->get("user_id"));
        $scheduledMemorialServices = $this->adminModel->getScheduledMemorialServices($session->get("user_id"));
        $nextPaymentDueDate = $this->adminModel->getNextPaymentDueDate($session->get("user_id"));
        $lastTwoPayments = $this->adminModel->getLastTwoPayments($session->get("user_id"));
        $paymentHistory = $this->adminModel->getPaymentHistory($session->get("user_id"));
        $propertyDistribution = $this->adminModel->getAssetDistribution($session->get("user_id"));


        $data = [
            "pageTitle" => "Dashboard",
            "session" => $session,
            "ownedPropertiesCount" => $ownedPropertiesCount,
            "scheduledMemorialServices" => $scheduledMemorialServices,
            "nextPaymentDueDate" => $nextPaymentDueDate,
            "lastTwoPayments" => $lastTwoPayments,
            'chartData' => [
                'paymentMonths' => json_encode($paymentHistory['months']),
                'paymentAmounts' => json_encode($paymentHistory['amounts']),
                'propertyCounts' => json_encode($propertyDistribution['counts'])
            ]
        ];
        return view("admin/dashboard", $data);
    }
}
