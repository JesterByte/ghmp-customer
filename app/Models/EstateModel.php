<?php

namespace App\Models;

use CodeIgniter\Model;

class EstateModel extends Model {
    protected $table = 'estates'; // Name of the table
    protected $primaryKey = 'id';

    protected $returnType     = 'array'; // Return results as an array
    protected $allowedFields  = ['id', 'estate_id', 'owner_id', 'latitude_start', 'longitude_start', 'latitude_end', 'longitude_end', 'status', 'occupancy', 'capacity']; // Define the allowed fields

    // Define any custom query to fetch available lots
    public function getAvailableEstates() {
        return $this->where('status', "Available")->findAll();
    }

    public function getEstatePricing($estateType) {
        $builer = $this->db->table("estate_pricing");
        $builer->select("sqm, number_of_lots");
        $builer->where("estate", $estateType);

        $query = $builer->get();

        return $query->getRow();
    }

    
}
