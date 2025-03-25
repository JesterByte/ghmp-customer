<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\CashSaleModel;
use App\Models\InstallmentModel;
use App\Models\PaymentModel;
use App\Models\SixMonthsModel;

class PaymentLogController extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $paymentModel = new PaymentModel();
        $payments = $paymentModel->getAllPayments();

        $data = [
            "pageTitle" => "Payment Log",
            "payments" => $payments,
            "session" => $session
        ];

        return view("admin/payment_log", $data);
    }

    public function getCashSales()
    {
        $cashSaleModel = new CashSaleModel();

        $cashSales = $cashSaleModel->getCashSales();

        return $this->response->setJSON($cashSales);
    }

    public function getSixMonths()
    {
        $sixMonthsModel = new SixMonthsModel();
        $sixMonths = $sixMonthsModel->getSixMonths();

        return $this->response->setJSON($sixMonths);
    }

    public function getInstallmentDownPayments()
    {
        $installmentsModel = new InstallmentModel();
        $installmentDownPayments = $installmentsModel->getInstallmentDownPayments();

        return $this->response->setJSON($installmentDownPayments);
    }

    public function payCashSale()
    {
        $assetId = $this->request->getPost("asset_id");
        $paymentAmount = $this->request->getPost("payment_amount");
        $receipt = $this->request->getFile("receipt");

        if (!$assetId || !$paymentAmount || !$receipt) {
            return $this->response->setJSON(["success" => false, "message" => "All fields are required!"]);
        }

        if ($receipt->isValid() && !$receipt->hasMoved()) {
            $newName = $receipt->getRandomName();
            $receipt->move(FCPATH . "uploads/receipts/", $newName);
        } else {
            return $this->response->setJSON(["success" => false, "message" => "Invalid file upload!"]);
        }

        $assetIdType = FormatterHelper::determineIdType($assetId);

        switch ($assetIdType) {
            case "lot":
                $table = "cash_sales";
                $assetIdKey = "lot_id";
                break;
            case "estate":
                $table = "estate_cash_sales";
                $assetIdKey = "estate_id";
                break;
        }
        $receiptPath = "uploads/receipts/" . $newName;

        $cashSaleModel = new CashSaleModel();
        $result = $cashSaleModel->setCashSalePayment($assetIdType, $table, $assetId, $assetIdKey, $paymentAmount, $receiptPath);

        return $this->response->setJSON($result);
    }

    public function paySixMonths()
    {
        $assetId = $this->request->getPost("asset_id");
        $paymentAmount = $this->request->getPost("payment_amount");
        $receipt = $this->request->getFile("receipt");

        if (!$assetId || !$paymentAmount || !$receipt) {
            return $this->response->setJSON(["success" => false, "message" => "All fields are required!"]);
        }

        if ($receipt->isValid() && !$receipt->hasMoved()) {
            $newName = $receipt->getRandomName();
            $receipt->move(FCPATH . "uploads/receipts/", $newName);
        } else {
            return $this->response->setJSON(["success" => false, "message" => "Invalid file upload!"]);
        }

        $assetIdType = FormatterHelper::determineIdType($assetId);

        switch ($assetIdType) {
            case "lot":
                $table = "six_months";
                $assetIdKey = "lot_id";
                break;
            case "estate":
                $table = "estate_six_months";
                $assetIdKey = "estate_id";
                break;
        }
        $receiptPath = "uploads/receipts/" . $newName;

        $sixMonthsModel = new SixMonthsModel();
        $result = $sixMonthsModel->setSixMonthsPayment($assetIdType, $table, $assetId, $assetIdKey, $paymentAmount, $receiptPath);

        return $this->response->setJSON($result);
    }
}
