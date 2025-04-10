<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use CodeIgniter\RESTful\ResourceController;
use App\Models\AdminNotificationModel;
use App\Models\NotificationModel;

class WebhookController extends ResourceController
{
    public function index()
    {
        $session = session();

        $paymongo_secret = "whsk_C7MvzaSKcgRHP7F2gaQrxQmN"; // Use .env for security

        $rawPayload = file_get_contents("php://input");
        $headers = getallheaders();
        log_message('error', "Received Headers: " . print_r($headers, true));

        $signatureHeader = $headers['Paymongo-Signature'] ?? '';
        if (!$signatureHeader) {
            log_message('error', 'PayMongo Signature header missing.');
            return $this->respond(['message' => 'Missing Signature Header'], 200);
        }

        // Extract timestamp and signature
        $signatureParts = explode(',', $signatureHeader);
        $timestamp = null;
        $signatureHash = null;

        foreach ($signatureParts as $part) {
            if (strpos($part, 't=') === 0) {
                $timestamp = substr($part, 2);
            }
            if (strpos($part, 'v1=') === 0 || strpos($part, 'te=') === 0) { // Check for both v1= and te=
                $signatureHash = substr($part, 3);
            }
        }

        if (!$timestamp || !$signatureHash) {
            log_message('error', 'Invalid PayMongo Signature format.');
            return $this->respond(['message' => 'Invalid Signature Format'], 200);
        }

        // Compute expected signature
        $expectedSignature = hash_hmac('sha256', $timestamp . "." . $rawPayload, $paymongo_secret);
        if (!hash_equals($expectedSignature, $signatureHash)) {
            log_message('error', 'Unauthorized Webhook Access - Signature Mismatch');
            return $this->respond(['message' => 'Invalid Signature'], 200);
        }

        log_message('error', "Computed Signature: $expectedSignature");
        log_message('error', "Received Signature: $signatureHash");

        $data = json_decode($rawPayload, true);
        log_message("error", "Webhook Received: " . print_r($data, true));

        $status = $data["data"]["attributes"]["data"]["attributes"]["status"];

        if (!$status) {
            log_message('error', 'Missing status in webhook payload.');
            return $this->respond(['message' => 'Missing status'], 200);
        }

        $referenceNumber = $data["data"]["attributes"]["data"]["attributes"]["reference_number"] ??
            $data["data"]["attributes"]["data"]["attributes"]["external_reference_number"] ??
            $data["data"]["attributes"]["data"]["attributes"]["metadata"]["pm_reference_number"] ?? null;

        if (!$referenceNumber) {
            log_message('error', 'Reference number not found in webhook payload.');
            return $this->respond(['message' => 'Missing reference number'], 200);
        }


        // Connect to Database
        $db = \Config\Database::connect();

        // Find reservation in either table
        $reservation = $db->table("lot_reservations")
            ->select("reference_number, reservee_id, lot_id AS asset_id, 'lot' AS asset_type, payment_option, reservation_status")
            ->where("reference_number", $referenceNumber)
            ->get()
            ->getRow();

        $assetType = "lot";

        if (!$reservation) {
            $reservation = $db->table("estate_reservations")
                ->select("reference_number, reservee_id, estate_id AS asset_id, 'estate' AS asset_type, payment_option, reservation_status")
                ->where("reference_number", $referenceNumber)
                ->get()
                ->getRow();

            $assetType = "estate";
        }

        if (!$reservation) {
            log_message('error', "No reservation found for Reference Number: $referenceNumber");
            return $this->respond(['message' => 'Reservation not found'], 200);
        }

        // Determine table prefixes
        $prefix = ($reservation->asset_type === "estate") ? "estate_" : "";

        // Identify correct payment option table
        switch ($reservation->payment_option) {
            case "Cash Sale":
                $paymentOptionTable = $prefix . "cash_sales";
                break;
            case "6 Months":
                $paymentOptionTable = $prefix . "six_months";
                $installmentPaymentsTable = $prefix . "six_months_payments";

                log_message('error', "Payment option:" . $reservation->payment_option);
                break;
            case (strpos($reservation->payment_option, "Installment") !== false):
                $paymentOptionTable = $prefix . "installments";
                $installmentPaymentsTable = $prefix . "installment_payments";

                log_message('error', "Payment option:" . $reservation->payment_option);
                break;
            default:
                log_message('error', "Unknown payment option for Reference Number: $referenceNumber");
                return $this->respond(['message' => 'Invalid payment option'], 200);
        }

        $assetIdType = FormatterHelper::determineIdType($reservation->asset_id);

        switch ($assetIdType) {
            case "lot":
                $formattedAssetId = FormatterHelper::formatLotId($reservation->asset_id);
                break;
            case "estate":
                $formattedAssetId = FormatterHelper::formatEstateId($reservation->asset_id);
                break;
        }

        if ($status === "paid") {
            if ($reservation->payment_option === "6 Months" || str_contains($reservation->payment_option, "Installment")) {
                log_message('error', "Payment is paid and is 6 Months or Installment");
                switch ($assetType) {
                    case "lot":
                        $assetIdColumn = "lot_id";
                        break;
                    case "estate":
                        $assetIdColumn = "estate_id";
                        break;
                }

                switch ($reservation->payment_option) {
                    case "6 Months":
                        $paymentOptionIdKey = "six_months_id";
                        break;
                    case str_contains($reservation->payment_option, "Installment"):
                        $paymentOptionIdKey = "installment_id";
                        break;
                }

                $installment = $db->table($paymentOptionTable)
                    ->select("id, {$assetIdColumn} AS asset_id, down_reference_number, reference_number, monthly_payment")
                    // ->where($assetIdColumn, $reservation->$assetIdColumn)
                    ->where($assetIdColumn, $reservation->asset_id)
                    ->where("down_reference_number", $referenceNumber)
                    ->orWhere("reference_number", $referenceNumber)
                    ->get()
                    ->getRow();

                if ($installment->down_reference_number === $referenceNumber) {
                    // Update Down Payment Status
                    $db->table($paymentOptionTable)
                        ->where($assetIdColumn, $reservation->asset_id)
                        ->set([
                            "down_payment_status" => "Paid",
                            "down_payment_date" => date("Y-m-d H:i:s"),
                            "payment_status" => "Ongoing"
                        ])
                        ->update();

                    // Insert notification for the admin about the new reservation
                    $adminNotificationModel = new AdminNotificationModel();
                    $notificationMessage = "A down payment has been made for Asset ID: {$formattedAssetId}.";
                    $notificationData = [
                        'admin_id' => null,  // Null for general admin notification
                        'message' => $notificationMessage,
                        'link' => 'installments',  // Link to the reservations page
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $adminNotificationModel->insert($notificationData);

                    // Insert notification for the admin about the new reservation
                    $notificationModel = new NotificationModel();
                    $notificationMessage = "Your down payment for Asset ID: {$formattedAssetId} has been successfully received.";
                    $notificationData = [
                        'admin_id' => null,  // Null for general admin notification
                        'message' => $notificationMessage,
                        'link' => 'installments',  // Link to the reservations page
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $notificationModel->insert($notificationData);
                } else if ($installment->reference_number === $referenceNumber) {
                    // Update Installment Status
                    $db->table($paymentOptionTable)
                        ->where($assetIdColumn, $reservation->asset_id)
                        // ->set(["next_due_date" => date("Y-m-d H:i:s", strtotime("+1 month"))])
                        ->set("next_due_date", date("Y-m-d H:i:s", strtotime("+1 month")))
                        ->update();

                    $data = [
                        $paymentOptionIdKey => $installment->id,
                        "payment_amount" => $installment->monthly_payment,
                        "payment_date" => date("Y-m-d H:i:s"),
                        "payment_status" => "Paid"
                    ];

                    $db->table($installmentPaymentsTable)->insert($data);

                    // Insert notification for the admin about the new reservation
                    $adminNotificationModel = new AdminNotificationModel();
                    $notificationMessage = "An installment payment has been made for Asset ID: {$formattedAssetId}.";
                    $notificationData = [
                        'admin_id' => null,  // Null for general admin notification
                        'message' => $notificationMessage,
                        'link' => 'installments',  // Link to the reservations page
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $adminNotificationModel->insert($notificationData);

                    // Insert notification for the admin about the new reservation
                    $notificationModel = new NotificationModel();
                    $notificationMessage = "Your installment payment for Asset ID: {$formattedAssetId} has been successfully received.";
                    $notificationData = [
                        'admin_id' => null,  // Null for general admin notification
                        'message' => $notificationMessage,
                        'link' => 'payment_log',  // Link to the reservations page
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $notificationModel->insert($notificationData);
                    $isCompleteInstallment = $this->isCompleteInstallment($installment->id, $installment->asset_id);

                    switch ($isCompleteInstallment) {
                        case true:
                            $this->assignAssetOwnership($reservation->reservee_id, $reservation->asset_id);
                            $this->completeInstallment($installment->id, $installment->asset_id);

                            // Insert notification for the admin about the new reservation
                            $adminNotificationModel = new AdminNotificationModel();
                            $notificationMessage = "{$session->get("user_full_name")} has completed the installment for Asset ID: {$formattedAssetId}.";
                            $notificationData = [
                                'admin_id' => null,  // Null for general admin notification
                                'message' => $notificationMessage,
                                'link' => 'fully-paids',  // Link to the reservations page
                                'is_read' => 0,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                            $adminNotificationModel->insert($notificationData);

                            // Insert notification for the admin about the new reservation
                            $notificationModel = new NotificationModel();
                            $notificationMessage = "You have successfully completed the installment for Asset ID: {$formattedAssetId}, congratulations!";
                            $notificationData = [
                                'admin_id' => null,  // Null for general admin notification
                                'message' => $notificationMessage,
                                'link' => 'my_lots_and_estates',  // Link to the reservations page
                                'is_read' => 0,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                            $notificationModel->insert($notificationData);

                            return $this->respond(["message" => "Webhook received successfully"], 200);
                        case false:
                            return $this->respond(["message" => "Webhook received successfully"], 200);
                    }
                }
            } else {
                // Update Reservation Status
                $db->table(($reservation->asset_type === "lot" ? "lot_reservations" : "estate_reservations"))
                    ->where("reference_number", $referenceNumber)
                    ->set(["reservation_status" => "Completed"])
                    ->update();

                // Update Payment Status
                $db->table($paymentOptionTable)
                    ->where("{$reservation->asset_type}_id", $reservation->asset_id)
                    ->where("reservation_id", $reservation->id)
                    ->set(["payment_status" => "Paid", "payment_date" => date("Y-m-d H:i:s")])
                    ->update();

                $this->assignAssetOwnership($reservation->reservee_id, $reservation->asset_id);

                // Insert notification for the admin about the new reservation
                $adminNotificationModel = new AdminNotificationModel();
                $notificationMessage = "{$session->get("user_full_name")} has completed the {$reservation->payment_option} payment for Asset ID: {$formattedAssetId}.";
                $notificationData = [
                    'admin_id' => null,  // Null for general admin notification
                    'message' => $notificationMessage,
                    'link' => 'fully-paids',  // Link to the reservations page
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $adminNotificationModel->insert($notificationData);

                // Insert notification for the admin about the new reservation
                $notificationModel = new NotificationModel();
                $notificationMessage = "You have successfully completed the {$reservation->payment_option} payment for Asset ID: {$formattedAssetId}, congratulations!";
                $notificationData = [
                    'admin_id' => null,  // Null for general admin notification
                    'message' => $notificationMessage,
                    'link' => 'my_lots_and_estates',  // Link to the reservations page
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $notificationModel->insert($notificationData);
            }

            log_message('info', "Reservation and Payment updated successfully for Reference Number: $referenceNumber");
        }

        log_message("info", "Webhook processed successfully.");

        return $this->respond(["message" => "Webhook received successfully"], 200);
    }

    private function isCompleteInstallment($installmentId, $assetId): bool
    {
        $db = \Config\Database::connect();

        $assetType = FormatterHelper::determineIdType($assetId);

        switch ($assetType) {
            case "lot":
                $prefix = "";
                break;
            case "estate":
                $prefix = "estate_";
                break;
        }

        $totalPaid = $db->table($prefix . "installment_payments")
            ->selectSum("payment_amount")
            ->where("installment_id", $installmentId)
            ->get()
            ->getRow()
            ->payment_amount;

        $installment = $db->table($prefix . "installments")
            ->select("total_amount")
            ->where("id", $installmentId)
            ->get()
            ->getRow();

        if (!$installment) {
            log_message("error", "Installment ID: $installmentId not found.");
            return false;
        }

        return $totalPaid >= $installment->total_amount;
    }

    private function completeInstallment($installmentId, $assetId)
    {
        $db = \Config\Database::connect();

        $assetType = FormatterHelper::determineIdType($assetId);

        switch ($assetType) {
            case "lot":
                $table = "lots";
                $assetIdColumn = "lot_id";
                $prefix = "";
                $reservationsTable = "lot_reservations";
                break;
            case "estate":
                $table = "estates";
                $assetIdColumn = "estate_id";
                $prefix = "estate_";
                $reservationsTable = "estate_reservations";
                break;
        }

        $setInstallment = $db->table($prefix . "installments")
            ->where("id", $installmentId)
            ->where($assetIdColumn, $assetId)
            ->set([
                "payment_status" => "Completed"
            ])
            ->update();

        if ($setInstallment) {
            $setReservationsTable = $db->table($reservationsTable)
                ->where($assetIdColumn, $assetId)
                ->set([
                    "reservation_status" => "Completed"
                ])
                ->update();
        }

        switch ($setReservationsTable) {
            case true:
                return true;
            case false:
                return false;
        }
    }

    private function assignAssetOwnership($reserveeId, $assetId)
    {
        $db = \Config\Database::connect();

        $assetType = FormatterHelper::determineIdType($assetId);

        switch ($assetType) {
            case "lot":
                $table = "lots";
                $assetIdColumn = "lot_id";
                break;
            case "estate":
                $table = "estates";
                $assetIdColumn = "estate_id";
                break;
        }

        $db->table($table)
            ->where($assetIdColumn, $assetId)
            ->set([
                "owner_id" => $reserveeId,
                "status" => "Sold"
            ])
            ->update();

        if ($db->affectedRows() === 0) {
            log_message("error", "Failed to assign ownership to Reservee ID: $reserveeId for Asset ID: $assetId");
            return false;
        }

        return true;
    }
}
