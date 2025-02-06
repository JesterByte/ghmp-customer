<?php

namespace App\Models;

use CodeIgniter\Model;

class EstateReservationModel extends Model {
    protected $table = 'estate_reservations'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'estate_id', 'reservee_id', 'estate_type', 'payment_option', 'reservation_status']; // Define the allowed fields

    // Method to update the payment option for estate reservation
    public function updateEstatePaymentOption($estateId, $reserveeId, $paymentOption) {
        // Perform the update where estate_id and reservee_id match
        return $this->db->table($this->table) // Replace 'estate_reservations' with the actual table name
                        ->where('estate_id', $estateId)
                        ->where('reservee_id', $reserveeId)
                        ->update(['payment_option' => $paymentOption]);
    }

    
}
