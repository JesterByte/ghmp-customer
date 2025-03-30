<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\EstateModel;
use App\Models\EstateReservationModel;
use App\Models\AdminNotificationModel;

class ReserveEstateController extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $data = [
            "pageTitle" => "Reserve an Estate",
            "session" => $session
        ];


        return view("admin/reserve_estate", $data);
    }

    public function getAvailableEstates()
    {
        $estateModel = new EstateModel();
        $availableEstates = $estateModel->getAvailableEstates();

        foreach ($availableEstates as $availableEstate) {
            $availableEstate["formatted_estate_id"] = FormatterHelper::formatEstateId($availableEstate["estate_id"]);

            $estateType = "Estate " . FormatterHelper::extractEstateIdParts($availableEstate["estate_id"])["type"];
            $availableEstate["estate_type"] = $estateType;

            $estatePricing = $estateModel->getEstatePricing($estateType);

            $availableEstate["sqm"] = $estatePricing->sqm;
            $availableEstate["number_of_lots"] = $estatePricing->number_of_lots;

            $availableEstates[] = $availableEstate;
        }

        return $this->response->setJSON($availableEstates);
    }

    // In ReserveController.php
    public function submitReservation()
    {
        $session = session();
        // $estateId = $this->request->getJSON('estate_id');  // Get the estate ID from the AJAX request
        $estateId = $this->request->getJSON()->estate_id;

        // Check if the estate exists and is available
        $estateModel = new EstateModel();
        $estate = $estateModel->where('estate_id', $estateId)->where('status', 'Available')->first();

        if (!$estate) {
            return $this->response->setJSON(['success' => false, 'message' => 'Estate not available']);
        }

        // Reserve the estate (mark it as reserved in the database)
        $estateModel->update($estate['id'], ['status' => 'Reserved']);

        // Optionally: Insert reservation details into a reservations table
        $estateType = FormatterHelper::extractEstateIdParts($estateId)["type"];
        $reservationData = [
            'reservee_id' => $session->get('user_id'),
            'estate_id' => $estateId,
            "estate_type" => $estateType
        ];
        $estateReservationModel = new EstateReservationModel();
        $estateReservationModel->save($reservationData);

        $formattedEstateId = FormatterHelper::formatEstateId($estateId);
        // Insert notification for the admin about the new reservation
        $adminNotificationModel = new AdminNotificationModel();
        $notificationMessage = "A new estate reservation has been made for Estate ID: {$formattedEstateId}.";
        $notificationData = [
            'admin_id' => null,  // Null for general admin notification
            'message' => $notificationMessage,
            'link' => 'estate-reservations-requests',  // Link to the reservations page
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $adminNotificationModel->insert($notificationData);

        return $this->response->setJSON(['success' => true, 'message' => 'Estate reserved successfully']);
    }
}
