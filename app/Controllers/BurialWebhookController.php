<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use CodeIgniter\RESTful\ResourceController;

class BurialWebhookController extends ResourceController {
    public function index() {
        $paymongo_secret = "whsk_xBqoQ1X6J2rwEzxYZpTpq9V6"; // Use .env for security
    
        $rawPayload = file_get_contents("php://input");
        $headers = getallheaders();
        log_message('error', "Received Headers: " . print_r($headers, true));
        
        $signatureHeader = $headers['Paymongo-Signature'] ?? '';
        if (!$signatureHeader) {
            log_message('error', 'PayMongo Signature header missing.');
            return $this->failForbidden('Missing Signature Header');
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
            return $this->failForbidden('Invalid Signature Format');
        }
        
        // Compute expected signature
        $expectedSignature = hash_hmac('sha256', $timestamp . "." . $rawPayload, $paymongo_secret);
        if (!hash_equals($expectedSignature, $signatureHash)) {
            log_message('error', 'Unauthorized Webhook Access - Signature Mismatch');
            return $this->failForbidden('Invalid Signature');
        }
        
        log_message('error', "Computed Signature: $expectedSignature");
        log_message('error', "Received Signature: $signatureHash");


        $data = json_decode($rawPayload, true);
        log_message("error", "Webhook Received: " . print_r($data, true));

        if (!isset($data["data"]["attributes"]["data"]["attributes"]["status"])) {
            log_message('error', 'Missing status in webhook payload.');
            return $this->fail("Missing status.");
        }
        
        $status = $data["data"]["attributes"]["data"]["attributes"]["status"];

        if (!isset($data["data"]["attributes"]["data"]["attributes"]["reference_number"])) {
            log_message('error', 'Reference number not found in webhook payload.');
            return $this->fail("Missing reference number.");
        }
        
        $referenceNumber = $data["data"]["attributes"]["data"]["attributes"]["reference_number"];
        
        if (!$status) {
            log_message('error', 'Missing status in webhook payload.');
            return $this->fail("Missing status.");
        }
        
        if (!$referenceNumber) {
            log_message('error', 'Reference number not found in webhook payload.');
            return $this->fail('Missing reference number.');
        }

        // Connect to Database
        $db = \Config\Database::connect();
        
        // Find reservation in either table
        // $reservation = $db->table("lot_reservations")
        //     ->select("reference_number, lot_id AS asset_id, 'lot' AS asset_type, payment_option")
        //     ->where("reference_number", $referenceNumber)
        //     ->get()
        //     ->getRow();
        
        // if (!$reservation) {
        //     $reservation = $db->table("estate_reservations")
        //         ->select("reference_number, estate_id AS asset_id, 'estate' AS asset_type, payment_option")
        //         ->where("reference_number", $referenceNumber)
        //         ->get()
        //         ->getRow();
        // }
        
        // if (!$reservation) {
        //     log_message('error', "No reservation found for Reference Number: $referenceNumber");
        //     return $this->failNotFound("Reservation not found.");
        // }

        // Determine table prefixes
        // $prefix = ($reservation->asset_type === "estate") ? "estate_" : "";
        
        // Identify correct payment option table
        // switch ($reservation->payment_option) {
        //     case "Cash Sale":
        //         $paymentOptionTable = $prefix . "cash_sales";
        //         break;
        //     case "6 Months":
        //         $paymentOptionTable = $prefix . "six_months";
        //         break;
        //     case (strpos($reservation->payment_option, "Installment") !== false):
        //         $paymentOptionTable = $prefix . "installments";
        //         break;
        //     default:
        //         log_message('error', "Unknown payment option for Reference Number: $referenceNumber");
        //         return $this->fail('Invalid payment option.');
        // }

        if ($status === "paid") {
            // Update Reservation Status
            $db->table(("burial_reservations"))
                ->where("reference_number", $referenceNumber)
                ->set(["payment_status" => "Paid"])
                ->update();

            // Update Payment Status
            // $db->table($paymentOptionTable)
            //     ->where("{$reservation->asset_type}_id", $reservation->asset_id)
            //     ->set(["payment_status" => "Paid", "payment_date" => date("Y-m-d H:i:s")])
            //     ->update();

            log_message('info', "Reservation and Payment updated successfully for Reference Number: $referenceNumber");

            return $this->respond(["message" => "Payment successful, reservation updated."]);
        }

        return $this->fail("Payment status is not 'paid'.");
    }
}
