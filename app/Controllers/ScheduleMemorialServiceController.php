<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\AssetModel;
use App\Models\BurialReservationsModel;

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
                session()->get("user_id"), // Assuming the reservee_id is stored in session
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

            // Return response based on insertion result
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
