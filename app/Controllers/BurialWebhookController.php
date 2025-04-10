<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use CodeIgniter\RESTful\ResourceController;
use App\Models\AdminNotificationModel;
use App\Models\NotificationModel;

class BurialWebhookController extends ResourceController
{
    public function index()
    {
        $session = session();

        $paymongo_secret = "whsk_xBqoQ1X6J2rwEzxYZpTpq9V6"; // Use .env for security

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

        if (!isset($data["data"]["attributes"]["data"]["attributes"]["status"])) {
            log_message('error', 'Missing status in webhook payload.');
            return $this->respond(['message' => 'Missing status'], 200);
        }

        $status = $data["data"]["attributes"]["data"]["attributes"]["status"];

        if (!isset($data["data"]["attributes"]["data"]["attributes"]["reference_number"])) {
            log_message('error', 'Reference number not found in webhook payload.');
            return $this->respond(['message' => 'Missing reference number'], 200);
        }

        $referenceNumber = $data["data"]["attributes"]["data"]["attributes"]["reference_number"];

        if (!$status) {
            log_message('error', 'Missing status in webhook payload.');
            return $this->respond(['message' => 'Missing status'], 200);
        }

        if (!$referenceNumber) {
            log_message('error', 'Reference number not found in webhook payload.');
            return $this->respond(['message' => 'Missing reference number'], 200);
        }

        // Connect to Database
        $db = \Config\Database::connect();

        if ($status === "paid") {
            // Update Reservation Status
            $db->table(("burial_reservations"))
                ->where("reference_number", $referenceNumber)
                ->set(["payment_status" => "Paid"])
                ->update();

            $burialReservation = $db->table("burial_reservations")
                ->where("reference_number", $referenceNumber)
                ->get()
                ->getRow();

            log_message('info', "Reservation and Payment updated successfully for Reference Number: $referenceNumber");

            // Insert notification for the admin about the new reservation
            $adminNotificationModel = new AdminNotificationModel();
            $notificationMessage = "{$session->get("user_full_name")} has completed the payment for a burial reservation for Asset ID: {$burialReservation->asset_id}.";
            $notificationData = [
                'admin_id' => null,  // Null for general admin notification
                'message' => $notificationMessage,
                'link' => 'burial-payments',  // Link to the reservations page
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $adminNotificationModel->insert($notificationData);

            // Insert notification for the admin about the new reservation
            $notificationModel = new NotificationModel();
            $notificationMessage = "You have successfully completed the payment for a burial reservation for Asset ID: {$burialReservation->asset_id}, congratulations!";
            $notificationData = [
                'admin_id' => null,  // Null for general admin notification
                'message' => $notificationMessage,
                'link' => 'my_memorial_services',  // Link to the reservations page
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $notificationModel->insert($notificationData);

            return $this->respond(["message" => "Webhook received successfully"], 200);
        }

        return $this->respond(["message" => "Webhook received successfully"], 200);
    }
}
