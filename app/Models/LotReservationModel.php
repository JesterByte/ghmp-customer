<?php

namespace App\Models;

use CodeIgniter\Model;

class LotReservationModel extends Model {
    protected $table = 'lot_reservations'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'lot_id', 'reservee_id', 'lot_type', 'payment_option', 'reservation_status']; // Define the allowed fields

    public function updateLotPaymentOption($lotId, $reserveeId, $paymentOption) {
        return $this->db->table($this->table)
        ->where("lot_id", $lotId)
        ->where("reservee_id", $reserveeId)
        ->update(["payment_option" => $paymentOption]);
    }

    public function setCashSalePayment($lotId)
}
