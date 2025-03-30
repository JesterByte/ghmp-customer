<?php

namespace App\Controllers;

use App\Helpers\FormatterHelper;
use App\Models\AdminNotificationModel;
use App\Models\LotModel;
use App\Models\LotReservationModel;

class ReserveLotController extends BaseController
{
    public function index()
    {
        $session = session();

        if (!$session->get("user_id")) {
            return redirect()->to(base_url("signin")); // Redirect to signin if not logged in
        }

        $data = [
            "pageTitle" => "Reserve a Lot",
            "session" => $session
        ];
        return view("admin/reserve_lot", $data);
    }

    public function getAvailableLots()
    {
        $lotModel = new LotModel();
        $availableLots = $lotModel->getAvailableLots();
        foreach ($availableLots as $availableLot) {
            $availableLot["formatted_lot_id"] = FormatterHelper::formatLotId($availableLot["lot_id"]);
            $availableLots[] = $availableLot;
        }

        return $this->response->setJSON($availableLots);
    }

    // In ReserveController.php
    public function submitReservation()
    {
        $session = session();
        // $lotId = $this->request->getJSON('lot_id');  // Get the lot ID from the AJAX request
        $lotId = $this->request->getJSON()->lot_id;  // Get the lot ID from the AJAX request

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
        ];
        $lotReservationModel = new LotReservationModel();
        $lotReservationModel->save($reservationData);

        $formattedLotId = FormatterHelper::formatLotId($lotId);

        // Insert notification for the admin about the new reservation
        $adminNotificationModel = new AdminNotificationModel();
        $notificationMessage = "A new lot reservation has been made for Lot ID: {$formattedLotId}.";
        $notificationData = [
            'admin_id' => null,  // Null for general admin notification
            'message' => $notificationMessage,
            'link' => 'lot-reservations-requests',  // Link to the reservations page
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $adminNotificationModel->insert($notificationData);

        return $this->response->setJSON(['success' => true, 'message' => 'Lot reserved successfully']);
    }
}
