<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\AdminNotificationModel;
use App\Models\LotModel;
use App\Models\LotReservationModel;
use App\Models\EstateModel;
use App\Models\EstateReservationModel;

class ReserveController extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $data = [
            "pageTitle" => "Reserve",
            "session" => $session
        ];
        return view("admin/reserve", $data);
    }

    public function submitLotReservation()
    {
        $session = session();
        // $lotId = $this->request->getJSON('lot_id');  // Get the lot ID from the AJAX request
        $lotId = $this->request->getJSON()->lot_id;  // Get the lot ID from the AJAX request
        $lotType = $this->request->getJSON()->lot_type;

        // Check if the lot exists and is available
        $lotModel = new LotModel();
        $lot = $lotModel->where('lot_id', $lotId)->where('status', 'Available')->first();

        if (!$lot) {
            return $this->response->setJSON(['success' => false, 'message' => 'Lot not available']);
        }

        // Reserve the lot (mark it as reserved in the database)
        $lotModel->update($lot['id'], ['status' => 'Reserved']);

        // Optionally: Insert reservation details into a reservations table
        $reservationData = [
            'reservee_id' => $session->get('user_id'),
            'lot_id' => $lotId,
            "lot_type" => $lotType
        ];
        $lotReservationModel = new LotReservationModel();
        $lotReservationModel->save($reservationData);

        // $formattedLotId = FormatterHelper::formatLotId($lotId);

        // Insert notification for the admin about the new reservation
        $adminNotificationModel = new AdminNotificationModel();
        $notificationMessage = "A new lot reservation has been made for Lot: {$lotId}.";
        $notificationData = [
            'admin_id' => null,  // Null for general admin notification
            'message' => $notificationMessage,
            'link' => 'lot-reservation-requests',  // Link to the reservations page
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $adminNotificationModel->insert($notificationData);

        return $this->response->setJSON(['success' => true, 'message' => 'Lot reserved successfully']);
    }


    public function submitEstateReservation()
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

        // $formattedEstateId = FormatterHelper::formatEstateId($estateId);
        // Insert notification for the admin about the new reservation
        $adminNotificationModel = new AdminNotificationModel();
        $notificationMessage = "A new estate reservation has been made for Estate: {$estateId}.";
        $notificationData = [
            'admin_id' => null,  // Null for general admin notification
            'message' => $notificationMessage,
            'link' => 'estate-reservation-requests',  // Link to the reservations page
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $adminNotificationModel->insert($notificationData);

        return $this->response->setJSON(['success' => true, 'message' => 'Estate reserved successfully']);
    }

    public function getAvailableLots()
    {
        $lotModel = new LotModel();
        $availableLots = $lotModel->getAvailableLots();
        foreach ($availableLots as $availableLot) {
            $availableLot["formatted_lot_id"] = FormatterHelper::formatLotId($availableLot["lot_id"]);
            $availableLot["price"] = FormatterHelper::formatPrice($availableLot["price"]);
            $availableLots[] = $availableLot;
        }

        return $this->response->setJSON($availableLots);
    }

    public function getChosenLots()
    {
        $session = session();
        $lotModel = new LotModel();
        $chosenLots = $lotModel->getChosenLots($session->get("user_id"));
        foreach ($chosenLots as $chosenLot) {
            $chosenLot["formatted_lot_id"] = FormatterHelper::formatLotId($chosenLot["lot_id"]);
            $chosenLots[] = $chosenLot;
        }

        return $this->response->setJSON($chosenLots);
    }

    public function getChosenEstates()
    {
        $session = session();
        $estateModel = new EstateModel();
        $chosenEstates = $estateModel->getChosenEstates($session->get("user_id"));
        foreach ($chosenEstates as $chosenEstate) {
            $chosenEstates[] = $chosenEstate;
        }

        return $this->response->setJSON($chosenEstates);
    }

    public function getAvailableEstates()
    {
        $estateModel = new EstateModel();
        $availableEstates = $estateModel->getAvailableEstates();

        foreach ($availableEstates as $availableEstate) {
            $availableEstate["formatted_estate_id"] = FormatterHelper::formatEstateId($availableEstate["estate_id"]);
            $availableEstate["price"] = FormatterHelper::formatPrice($availableEstate["price"]);

            $estateType = "Estate " . FormatterHelper::extractEstateIdParts($availableEstate["estate_id"])["type"];
            $availableEstate["estate_type"] = $estateType;

            $estatePricing = $estateModel->getEstatePricing($estateType);

            $availableEstate["sqm"] = $estatePricing->sqm;
            $availableEstate["number_of_lots"] = $estatePricing->number_of_lots;

            $availableEstates[] = $availableEstate;
        }

        return $this->response->setJSON($availableEstates);
    }
}
