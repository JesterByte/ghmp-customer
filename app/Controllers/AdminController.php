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
            return redirect()->to(base_url("signin"));
        }

        try {
            $userId = $session->get("user_id");
            $userFullName = $session->get("user_full_name");

            // Get all dashboard data
            $ownedPropertiesCount = $this->adminModel->getOwnerAssetsCount($userId);
            $scheduledMemorialServices = $this->adminModel->getScheduledMemorialServices($userId);
            $nextPaymentDueDate = $this->adminModel->getNextPaymentDueDate($userId);
            $lastTwoPayments = $this->adminModel->getLastTwoPayments($userId);
            $paymentHistory = $this->adminModel->getPaymentHistory($userId);
            $propertyDistribution = $this->adminModel->getAssetDistribution($userId);

            // Format payment history data for the chart
            $chartData = [
                'paymentMonths' => json_encode($paymentHistory['months'] ?? []),
                'paymentAmounts' => json_encode($paymentHistory['amounts'] ?? []),
                'propertyCounts' => json_encode($propertyDistribution['counts'] ?? [])
            ];

            return view("admin/dashboard", [
                "pageTitle" => "Dashboard",
                "session" => $session,
                "ownedPropertiesCount" => $ownedPropertiesCount,
                "scheduledMemorialServices" => $scheduledMemorialServices,
                "nextPaymentDueDate" => $nextPaymentDueDate,
                "lastTwoPayments" => $lastTwoPayments,
                "chartData" => $chartData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Dashboard data fetch error: ' . $e->getMessage());
            return view("admin/dashboard", [
                "pageTitle" => "Dashboard",
                "session" => $session,
                "error" => "Unable to load dashboard data. Please try again later."
            ]);
        }
    }
}
