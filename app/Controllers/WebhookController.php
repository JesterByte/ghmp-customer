<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use CodeIgniter\RESTful\ResourceController;

class WebhookController extends ResourceController {
    public function index() {
        $paymongo_secret = "whsk_C7MvzaSKcgRHP7F2gaQrxQmN"; // Store in .env

        $headers = getallheaders();
        $signature = $headers['Paymongo-Signature'] ?? '';

        if ($signature !== $paymongo_secret) {
            log_message('error', 'Unauthorized Webhook Access');
            return $this->failForbidden('Invalid Signature');
        }

        $json = file_get_contents("php://input");
        $data = json_decode($json, true);

        log_message("error", "Webhhok Received: " . print_r($data, true));

        if (isset($data["data"]["attributes"])) {
            $status = $data["data"]["attributes"]["status"];
            $metadata = $data["data"]["attributes"]["metadata"] ?? [];

            if (isset($metadata["asset_id"])) {
                $assetId = $metadata["asset_id"];
                $paymentOption = $metadata["payment_option"];

                $assetType = FormatterHelper::determineIdType($assetId);

                switch ($assetType) {
                    case "lot":
                        $reservationTable = "lot_reservation";
                        $column = "lot_id";
                        $prefix = "";
                        break;
                    case "estate":
                        $reservationTable = "estate_reservation";
                        $column = "estate_id";
                        $prefix = "estate_";
                        break;
                }

                switch ($paymentOption) {
                    case "Cash Sale":
                        $paymentOptionTable = $prefix . "cash_sales";
                        break;
                    case "6 Months":
                        $paymentOptionTable = $prefix . "six_months";
                        break;
                    case (strpos($paymentOption, "Installment") !== false):
                        $paymentOptionTable = $prefix . "installments";
                }

                if ($status === "paid") {
                    $db = \Config\Database::connect();
                    $db->table($reservationTable)
                    ->where($column, $assetId)
                    ->set(["reservation_status" => "Paid"])
                    ->update();

                    $db->table($paymentOptionTable)
                    ->where($column, $assetId)
                    ->set(["payment_status" => "Paid"])
                    ->update();

                    return $this->respond(["message" => "Payment successful, reservation updated."]);
                }
            }
        }

        return $this->fail("Invalid webhook paylod");
    }
}