<?php

namespace App\Models;

use CodeIgniter\Model;

class LotReservationModel extends Model {
    protected $table = 'lot_reservations'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'lot_id', 'reservee_id', 'lot_type', 'payment_option', 'reservation_status']; // Define the allowed fields

    // Define any custom query to fetch available lots
    // public function getAvailableLots() {
    //     return $this->where('status', "Available")->findAll();
    // }

    
}
