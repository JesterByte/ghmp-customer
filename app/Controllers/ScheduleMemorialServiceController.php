<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\AssetModel;
use App\Models\BurialReservationsModel;
use App\Models\AdminNotificationModel;

class ScheduleMemorialServiceController extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $data = [
            "pageTitle" => "Schedule a Memorial Service",
            "session" => $session
        ];
        return view("admin/schedule_memorial_service", $data);
        // return view('brochure/home');
    }

    public function getOwnedAssets()
    {
        $session = session();

        $assetModel = new AssetModel();
        $ownedAssets = $assetModel->getOwnedAssets($session->get("user_id"));
        if (!empty($ownedAssets)) {
            foreach ($ownedAssets as $ownedAsset) {
                $ownedAsset["formatted_asset_id"] = FormatterHelper::formatLotId($ownedAsset["asset_id"]);
                $ownedAsset["asset_type"] = FormatterHelper::determineIdType($ownedAsset["asset_id"]);

                // if ($ownedAsset["status"] === "Approved" && $ownedAsset["payment_status"] === "Pending") {
                //     $ownedAsset["payment_link"] = $this->createPaymongoLink()
                // }

                $ownedAssets[] = $ownedAsset;
            }
        } else {
            $ownedAssets = [];
        }

        return $this->response->setJSON($ownedAssets);
    }

    public function submitMemorialService()
    {
        if ($this->request->getMethod() === "POST") {
            // Decode JSON request
            $data = $this->request->getJSON(true);

            // Validate received data
            if (empty($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No data received'
                ]);
            }

            $firstName = FormatterHelper::cleanName($data["first_name"]);
            $middleName = !empty($data["middle_name"]) ? FormatterHelper::cleanName($data["middle_name"]) : "";
            $lastName = FormatterHelper::cleanName($data["last_name"]);
            $obituary = trim($data["obituary"]);

            // Save reservation in the database
            $burialReservationsModel = new BurialReservationsModel();
            $paymentAmount = $burialReservationsModel->getBurialPricing(ucfirst($data["category"]), $data["burial_type"])["price"];

            $inserted = $burialReservationsModel->setBurialReservation(
                $data["asset_id"],
                $data["burial_type"],
                session()->get("user_id"),
                $data["relationship"],
                $firstName,
                $middleName ?? null,
                $lastName,
                $data["suffix"] ?? null,
                $data["date_of_birth"],
                $data["date_of_death"],
                $obituary,
                $paymentAmount,
                $data["date_time"]
            );

            if ($inserted) {
                $assetType = FormatterHelper::determineIdType($data["asset_id"]);

                switch ($assetType) {
                    case "lot":
                        $formattedAssetId = FormatterHelper::formatLotId($data["asset_id"]);
                        break;
                    case "estate":
                        $formattedAssetId = FormatterHelper::formatEstateId($data["asset_id"]);
                        break;
                }

                // Insert notification for the admin
                $adminNotificationModel = new AdminNotificationModel();
                $notificationMessage = "A new burial reservation has been made for Asset ID: {$formattedAssetId}.";
                $notificationData = [
                    'admin_id' => null,
                    'message' => $notificationMessage,
                    'link' => 'burial-reservations',
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $adminNotificationModel->insert($notificationData);

                // Send email notification to admin
                $email = \Config\Services::email();
                $email->setTo($this->adminEmail);
                $email->setSubject("New Burial Reservation");

                $burialDateTime = date("F j, Y h:i A", strtotime($data["date_time"]));

                $emailBody = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
                    <h2 style='text-align: center; color: #333;'>New Burial Reservation</h2>
                    <p style='color: #555;'>A new burial reservation has been made. Below are the details:</p>
                    <table style='width: 100%; border-collapse: collapse; margin-top: 10px;'>
                        <tr>
                            <td style='padding: 8px; border: 1px solid #ddd;'><strong>Deceased:</strong></td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>{$firstName} {$middleName} {$lastName} {$data["suffix"]}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px; border: 1px solid #ddd;'><strong>Burial Type:</strong></td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>{$data["burial_type"]}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px; border: 1px solid #ddd;'><strong>Service Date & Time:</strong></td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>{$burialDateTime}</td>
                        </tr>
                        <tr>
                            <td style='padding: 8px; border: 1px solid #ddd;'><strong>Asset ID:</strong></td>
                            <td style='padding: 8px; border: 1px solid #ddd;'>{$formattedAssetId}</td>
                        </tr>
                    </table>
                    <p style='margin-top: 15px;'>You can review the reservation in the admin panel.</p>
                    <a href=" . $this->admin_url("burial-reservation-requests") . " 
                       style='display: inline-block; padding: 10px 15px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px; text-align: center;'>
                       View Reservation
                    </a>
                    <p style='margin-top: 20px; color: #888; font-size: 12px;'>This is an automated notification. Please do not reply.</p>
                </div>";

                $email->setMessage($emailBody);
                $email->setMailType("html");
                $email->send();
            }

            return $this->response->setJSON([
                'success' => (bool) $inserted,
                'message' => $inserted ? 'Burial service reserved successfully!' : 'Failed to reserve burial service.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Invalid request method'
        ]);
    }
}
