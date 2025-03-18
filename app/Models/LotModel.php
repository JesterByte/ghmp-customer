<?php

namespace App\Models;

use CodeIgniter\Model;

class LotModel extends Model {
    protected $table = 'lots'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'lot_id', 'owner_id', 'latitude_start', 'longitude_start', 'latitude_end', 'longitude_end', 'status', 'occupancy', 'capacity']; // Define the allowed fields

    // Define any custom query to fetch available lots
    public function getAvailableLots() {
        return $this->where('status', "Available")->findAll();
    }

    
}
